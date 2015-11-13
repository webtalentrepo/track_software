<?php

	class Source {
	
		private $db;
	
		function __construct(PDO $pdo) {
			$this->db = $pdo;
		}
		
		function Insert($data) {
			$q = $this->db->prepare("INSERT INTO `sources` (`source_id`,`source_name`,`type`) VALUES (?,?,?)");
			return $q->execute(Array(
				$data['source_id'],
				$data['source_name'],
				isset($data['type'])?$data['type']:'good'
			));
		}		
		
		function Edit($data) {
			$q = $this->db->prepare("UPDATE `sources` SET `source_name`=?, `type`=? WHERE `source_id`=?");
			return $q->execute(Array(
				$data['source_name'],
				isset($data['type'])?$data['type']:'good',
				$data['source_id']
			));
		}
		
		function setType($data) {
			$q = $this->db->prepare("UPDATE `sources` SET `type` = ? WHERE `source_id` = ?");
			return $q->execute(Array(
				$data['type'],
				$data['source_id']
			));
		}
		
		function Delete($id) {
			$q = $this->db->prepare("DELETE FROM `sources` WHERE `source_id` = ?");
			$q->execute(Array($id));
			return $q;
		}
		
		function getSources($type='good') {
			$q = $this->db->prepare("SELECT * FROM `sources` WHERE `type` = ? ORDER BY `source_name` ASC");
			$q->execute(Array(
				$type
			));
			return $q->fetchAll(PDO::FETCH_ASSOC);
		}
		
		function getSource($id) {
			$q = $this->db->prepare("SELECT * FROM `sources` WHERE `source_id` = ?");
			$q->execute(Array($id));
			return $q->fetch(PDO::FETCH_ASSOC);
		}
		
	}
		
?>