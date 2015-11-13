<?php
	require('Pager/Pager.php');

	class User {
	
		private $db;
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
		
		function getUsers($paginationData=0) {
			
			$this->parsePaginationData($paginationData);
		
			$q = $this->db->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM `users` ORDER BY `id` ASC LIMIT :start,:limit");
			
			$q->bindParam(':start',$this->paginationData['start'],PDO::PARAM_INT);
			$q->bindParam(':limit',$this->paginationData['limit'],PDO::PARAM_INT);
			
			$q->execute();
			
			$this->calculateTotal();
			
			return $q->fetchAll(PDO::FETCH_ASSOC);
		}
		
		function getUser($id) {
			$q = $this->db->prepare("SELECT * FROM `users` WHERE `id` = ? OR `hash` = ?");
			$q->execute(Array($id,$id));
			return $q->fetch(PDO::FETCH_ASSOC);
		}
		
		function getUserByEmail($email) {
			$q = $this->db->prepare("SELECT * FROM `users` WHERE `email` = ?");
			$q->execute(Array($email));
			return $q->fetch(PDO::FETCH_ASSOC);
		}
		
		function Insert($data) {
			if ($data['source']=='') unset($data['source']);
			
			$q = $this->db->prepare("INSERT INTO `users` (`name`,`email`,`hash`,`source`,`country`,`city`) VALUES (?,?,REPLACE(UUID_SHORT(),'-',''),?,?,?)");
			try {
				$r = $q->execute(Array(
					$data['name'],
					$data['email'],
					$data['source'],
					$data['country'],
					$data['city']
				));
				return $r;
			} catch (Exception $e) {
				return false;
			}
		}		
		
		function Edit($data) {
			if ($data['source']=='') unset($data['source']);
		
			$q = $this->db->prepare("UPDATE `users` SET `name`=?, `email`=?, `source`=?, `country`=?, `city`=? WHERE `id`=? OR `hash`=?");
			$q->execute(Array(
				$data['name'],
				$data['email'],
				$data['source'],
				$data['country'],
				$data['city'],
				$data['id'],
				$data['id']
			));
		}
		
		function Delete($id) {
			$q = $this->db->prepare("DELETE FROM `users` WHERE `id` = ? OR `hash` = ?");
			$q->execute(Array($id,$id));
			return $q;
		}

	}

?>