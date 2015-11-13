<?php

	class Network {
	
		private $db;
	
		function __construct(PDO $pdo) {
			$this->db = $pdo;
		}
		
		function Insert($data) {
			$q = $this->db->prepare("INSERT INTO `networks` (`name`,`syntax`) VALUES (?,?)");
			return $q->execute(Array(
				$data['name'],
				$data['syntax']
			));
		}		
		
		function Edit($data) {
			$q = $this->db->prepare("UPDATE `networks` SET `name`=?, `syntax`=? WHERE `id`=?");
			return $q->execute(Array(
				$data['name'],
				$data['syntax'],
				$data['id']
			));
		}
		
		function Delete($id) {
			$q = $this->db->prepare("DELETE FROM `networks` WHERE `id` = ?");
			$q->execute(Array($id));
			return $q;
		}
		
		function getNetworks() {
			$q = $this->db->query("SELECT * FROM `networks` ORDER BY `name` ASC");
			return $q->fetchAll(PDO::FETCH_ASSOC);
		}
		
		function getNetwork($id) {
			$q = $this->db->prepare("SELECT * FROM `networks` WHERE `id` = ?");
			$q->execute(Array($id));
			return $q->fetch(PDO::FETCH_ASSOC);
		}
		
	}
		
?>