<?php

	class Settings {
	
		private $db;
	
		function __construct(PDO $pdo) {
			$this->db = $pdo;
		}
		
		function Insert($data) {
			$q = $this->db->prepare("INSERT INTO `settings` (`name`,`description`,`value`) VALUES (?,?,?)");
			return $q->execute(Array(
				$data['name'],
				$data['description'],
				$data['value']
			));
		}		
		
		function Edit($data) {
			$q = $this->db->prepare("UPDATE `settings` SET `name`=?, `description`=?, `value`=? WHERE `id`=?");
			return $q->execute(Array(
				$data['name'],
				$data['description'],
				$data['value'],
				$data['id']
			));
		}
		
		function Delete($id) {
			$q = $this->db->prepare("DELETE FROM `settings` WHERE `id` = ?");
			$q->execute(Array($id));
			return $q;
		}
		
		function getSettings() {
			$q = $this->db->query("SELECT * FROM `settings` ORDER BY `name` ASC");
			return $q->fetchAll(PDO::FETCH_ASSOC);
		}
		
		function getSetting($id) {
			$q = $this->db->prepare("SELECT * FROM `settings` WHERE `id` = ? OR `name` = ?");
			$q->execute(Array($id,$id));
			return $q->fetch(PDO::FETCH_ASSOC);
		}
		
	}
		
?>