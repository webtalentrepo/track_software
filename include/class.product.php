<?php

	class Product {
	
		private $db;
	
		function __construct(PDO $pdo) {
			$this->db = $pdo;
		}
		
		function getProducts() {
			$q = $this->db->query("SELECT products.*, networks.name AS `networkName`, networks.syntax AS `networkLink` FROM `products` LEFT JOIN `networks` ON networks.id = products.network ORDER BY `title` ASC");
			return $q->fetchAll(PDO::FETCH_ASSOC);
		}
		
		function getProduct($id) {
			$q = $this->db->prepare("SELECT products.*, networks.name AS `networkName`, networks.syntax AS `networkLink` FROM `products` LEFT JOIN `networks` ON networks.id = products.network WHERE products.id = ? OR `hash` = ?");
			$q->execute(Array($id,$id));
			return $q->fetch(PDO::FETCH_ASSOC);
		}
		
		function Insert($data) {
			$q = $this->db->prepare("INSERT INTO `products` (`title`,`description`,`hash`,`earning`,`replacement`,`network`,`parameter`,`auto_subscribe_enabled`,`auto_subscribe_lists`) VALUES (?,?,REPLACE(UUID_SHORT(),'-',''),?,?,?,?,?,?)");
			return $q->execute(Array(
				$data['title'],
				$data['description'],
				$data['earning'],
				$data['replacement'],
				$data['network'],
				$data['parameter'],
				$data['auto_subscribe_enabled'],
				$data['auto_subscribe_lists']
			));
		}		
		
		function Edit($data) {
			$q = $this->db->prepare("UPDATE `products` SET `title`=?, `description`=?, `earning`=?, `replacement`=?, `network`=?, `parameter`=?, `auto_subscribe_enabled`=?, `auto_subscribe_lists`=? WHERE `id`=? OR `hash`=?");
			return $q->execute(Array(
				$data['title'],
				$data['description'],
				$data['earning'],
				$data['replacement'],
				$data['network'],
				$data['parameter'],
				$data['auto_subscribe_enabled'],
				$data['auto_subscribe_lists'],
				$data['id'],
				$data['id']
			));
		}
		
		function Delete($id) {
			$q = $this->db->prepare("DELETE FROM `products` WHERE `id` = ? OR `hash` = ?");
			$q->execute(Array($id,$id));
			return $q;
		}
	
	}

?>