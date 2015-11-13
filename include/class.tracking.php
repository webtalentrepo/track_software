<?php
	require('Pager/Pager.php');

	class Tracking {
	
		private $db;
		private $allowedGroups = Array(
			'email'=>'users.email',
			'product'=>'products.title',
			'country'=>'users.country',
			'city'=>'users.city',
			'network'=>'networks.name',
			'source'=>'users.source',
			'cid'=>'tracking.cid'
		);
		private $paginationData;
	
		function __construct(PDO $pdo) {
			$this->db = $pdo;
			$this->paginationData =  Array(
				'start' => 0,
				'limit' => 100,
				'total' => 0
			);
		}
		
		function parsePaginationData($pd) {
			if (isset($pd['start'])) $this->paginationData['start'] = $pd['start'];
			if (isset($pd['limit'])) $this->paginationData['limit'] = $pd['limit'];
			if (isset($pd['pageID']) && $pd['pageID']>0) $this->paginationData['start'] = (((int)$pd['pageID'])-1)*((int)$this->paginationData['limit']);
		}
		
		function calculateTotal() {
			$q = $this->db->query("SELECT FOUND_ROWS()");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			$this->paginationData['total'] = $r['FOUND_ROWS()'];
		}
		
		function getPaginationData() {
			return $this->paginationData;
		}
		
		function newSession($data) {
			$hash = $this->getSessionByData($data);
			if (isset($hash['hash'])) {
				$this->addView($hash['hash'],$data['source']);
				return $hash['hash'];
			} else {
				$q = $this->db->query("SELECT REPLACE(UUID_SHORT(),'-','') AS `hash`");
				$hash = $q->fetch(PDO::FETCH_ASSOC);
				$hash = $hash['hash'];
				$q = $this->db->prepare("INSERT INTO `tracking` (`hash`,`user_id`,`product_id`,`started`,`cid`) VALUES (?,?,?,NOW(),?)");
				if ($q->execute(Array(
					$hash,
					$data['user_id'],
					$data['product_id'],
					$data['cid']
				))) {
					$this->addView($hash,$data['source'].' initial');
					return $hash;
				}
				else return false;
			}
		}
		
		function getSessionByData($data) {
			$q = $this->db->prepare("SELECT * FROM `tracking` WHERE `user_id` = ? AND `product_id` = ? AND `converted`='0000-00-00 00:00:00'");
			$q->execute(Array(
				$data['user_id'],
				$data['product_id']
			));
			return $q->fetch(PDO::FETCH_ASSOC);
		}
		
		function getSession($hash) {
			$q = $this->db->prepare("SELECT * FROM `tracking` WHERE `hash`=?");
			$q->execute(Array($hash));
			return $q->fetch(PDO::FETCH_ASSOC);
		}
		
		function addView($hash,$source='') {
			$q = $this->db->prepare("INSERT INTO `views` (`hash`,`datetime`,`browser`,`referer`,`ip`,`source`,`parameters`) VALUES (?,NOW(),?,?,?,?,?)");
			return $q->execute(Array(
				$hash,
				$_SERVER['HTTP_USER_AGENT'],
				$_SERVER['HTTP_REFERER'],
				$_SERVER['REMOTE_ADDR'],
				$source,
				json_encode($_GET)
			));
		}
		
		function getSessions($filter,$paginationData) {
			$this->parsePaginationData($paginationData);
		
			if (is_array($filter) && count($filter)>0) {
				$fields = Array();
				$values = Array();
				foreach ($filter as $field=>$value) if (strlen($value)>0) {
					if ($field == "users.source") {
						$fields[] = "IFNULL(sources.source_name,users.source) LIKE ?";
						$values[] = '%'.$value.'%';
					} else {
						$fields[] = $field." LIKE ?";
						$values[] = '%'.$value.'%';
					}
				}
			}
			if (is_array($fields) && count($fields)) {
				$q = $this->db->prepare("SELECT SQL_CALC_FOUND_ROWS tracking.hash, users.email, users.country, users.city, IFNULL(sources.source_name,users.source) AS source, products.title, tracking.started, tracking.converted, tracking.earning, COUNT(views.datetime) AS views FROM tracking
				LEFT JOIN users ON users.id = tracking.user_id
				LEFT JOIN products ON products.id = tracking.product_id
				LEFT JOIN views ON views.hash = tracking.hash
				LEFT JOIN sources ON sources.source_id = users.source
				WHERE ".join(" AND ",$fields)."
				GROUP BY tracking.hash
				ORDER BY tracking.started DESC
				LIMIT ".$this->paginationData['start'].",".$this->paginationData['limit']);
		
				$q->execute($values);
			}
			else {
				$q = $this->db->query("SELECT SQL_CALC_FOUND_ROWS tracking.hash, users.email, users.country, users.city, IFNULL(sources.source_name,users.source) AS source, products.title, tracking.started, tracking.converted, tracking.earning, COUNT(views.datetime) AS views FROM tracking
				LEFT JOIN users ON users.id = tracking.user_id
				LEFT JOIN products ON products.id = tracking.product_id
				LEFT JOIN views ON views.hash = tracking.hash
				LEFT JOIN sources ON sources.source_id = users.source
				GROUP BY tracking.hash
				ORDER BY tracking.started DESC
				LIMIT ".$this->paginationData['start'].",".$this->paginationData['limit']);
			}
			$this->calculateTotal();
			return $q->fetchAll(PDO::FETCH_ASSOC);
		}
		
		function getSessionsReport($start,$end,$group,$filter) {
			if (!in_array($group,array_keys($this->allowedGroups))) die("Invalid group: {$group}");
			$group = $this->allowedGroups[$group];
			
			if (is_array($filter) && count($filter)>0) {
				$fields = Array();
				$values = Array($start,$end);
				foreach ($filter as $field=>$value) if (strlen($value)>0) {
					if ($field == "users.source") {
						$fields[] = "IFNULL(sources.source_name,users.source) LIKE ?";
						$values[] = '%'.$value.'%';
					} else {
						$fields[] = $field." LIKE ?";
						$values[] = '%'.$value.'%';
					}
				}
			}
			
			if (is_array($fields) && count($fields)) {
				$q = $this->db->prepare("SELECT {$group} AS `param`, SUM(tracking.earning) AS `earnings`, SUM((SELECT COUNT(`hash`) FROM `views` WHERE views.hash = tracking.hash)) AS views, SUM(IF(tracking.converted>'0000-00-00 00:00:00',1,0)) AS `converted`, COUNT(tracking.hash) as `sessions`, sources.source_name, IFNULL(sources.source_id, users.source) AS sourceID, sources.type as sourceType FROM tracking
				LEFT JOIN users ON users.id = tracking.user_id
				LEFT JOIN products ON products.id = tracking.product_id
				LEFT JOIN networks ON networks.id = products.network
				LEFT JOIN sources ON sources.source_id = users.source
				WHERE tracking.started BETWEEN ? AND ? AND (".join(" AND ",$fields).")
				GROUP BY {$group}");
				$q->execute($values);
			} else {
				$q = $this->db->prepare("SELECT {$group} AS `param`, SUM(tracking.earning) AS `earnings`, SUM((SELECT COUNT(`hash`) FROM `views` WHERE views.hash = tracking.hash)) AS views, SUM(IF(tracking.converted>'0000-00-00 00:00:00',1,0)) AS `converted`, COUNT(tracking.hash) as `sessions`, sources.source_name, IFNULL(sources.source_id, users.source) AS sourceID, sources.type as sourceType FROM tracking
				LEFT JOIN users ON users.id = tracking.user_id
				LEFT JOIN products ON products.id = tracking.product_id
				LEFT JOIN networks ON networks.id = products.network
				LEFT JOIN sources ON sources.source_id = users.source
				WHERE tracking.started BETWEEN ? AND ?
				GROUP BY {$group}");
				$q->execute(Array(
					$start,
					$end
				));
			}
			return $q->fetchAll(PDO::FETCH_ASSOC);
		}
		
		function getViews($hash) {
			$q = $this->db->prepare("SELECT * FROM `views` WHERE `hash` = ?");
			$q->execute(Array($hash));
			return $q->fetchAll(PDO::FETCH_ASSOC);
		}
		
		function convertSession($hash,$earning=-1,$overwrite=0) {
			
			if ($earning==-1) $earning = "(SELECT `earning` FROM `products` WHERE products.id = tracking.product_id)";
			else $earning = (float)$earning;
		
			if ($_POST['overwrite']==1) $q = $this->db->prepare("UPDATE `tracking` SET `converted` = NOW(), `earning` = {$earning} WHERE `hash` = ?");
			else $q = $this->db->prepare("UPDATE `tracking` SET `converted` = NOW(), `earning` = {$earning} WHERE `hash` = ?  AND `converted`='0000-00-00 00:00:00'");
			
			return $q->execute(Array($hash));
			
		}
		
	}
	
?>