<?php

class Mailing {

	private $db;

	function __construct(PDO $pdo) {
		$this->db = $pdo;
	}

	// curl get method.
	function httpGet($url, array $postary = NULL, $flag = '') {
		$reffer = 'https://www.google.com';
		$ip = '19121221' . rand(0, 1000);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 400000);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:29.0) Gecko/20120101 Firefox/29.0');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		if ($flag == '') {
			curl_setopt($ch, CURLOPT_REFERER, $reffer);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
		}
		if (!is_null($postary)) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postary));
		}
		$result = curl_exec($ch);
		return $result;
	}

	function Insert($data) {
		$q = $this->db->prepare("INSERT INTO `mailing` (`list_id`,`title`,`type`,`url`) VALUES (?,?,?,?)");
		$url = '';
		$list_id = '';
		$type = '';
		$pos = strpos(strtolower($data['formcode']), 'getresponse');
		$pos1 = strpos(strtolower($data['formcode']), 'infusionsoft');
		$pos2 = strpos(strtolower($data['formcode']), 'aweber');
		if ($pos !== false) {
			$type = 'getresponse';
		} else if ($pos1 !== false) {
			$type = 'infusionsoft';
		} else if ($pos2 !== false) {
			$type = 'aweber';
		}
		if ($type == 'getresponse') {
			$pos3 = strpos(strtolower($data['formcode']), 'script');
			if ($pos3 === false) {
				$dataary = explode("&webforms_id=", $data['formcode']);
				$list_id = $dataary[1];
				$cont = file_get_contents($data['formcode']);
				$document = new DOMDocument();
				@$document->loadHTML($cont);
				$dform = $document->getElementsByTagName('form')->item(0);
				$uary = explode('?', $dataary[0]);
				if ($dform) {
					$action = $dform->getAtrribute('action');
					$url = 'https://app.getresponse.com/' . $action . '?' . $uary[1];
				} else {
					$url = 'https://app.getresponse.com/add_contact_webform.html?' . $uary[1];
				}
			} else {
				$pattern = '<script[\r\s\n]type="text/javascript"[\r\s\n]src="(.+?)">';
				if (preg_match_all($pattern, $data['formcode'], $fmatchs)){
					$dataary = explode("&webforms_id=", $fmatchs[1][0]);
					$list_id = $dataary[1];
					$uary = explode('?', $dataary[0]);
					$url = 'https://app.getresponse.com/add_contact_webform.html?' . $uary[1];
				}
			}
		}
//		var_dump($url);
//		var_dump($list_id);
//		var_dump($type);
//		exit;
		return $q->execute(Array(
			$list_id,
			$data['title'],
			$type,
			$url
		));
	}

	function Edit($data) {
		$q = $this->db->prepare("UPDATE `mailing` SET `list_id`=?, `title`=?, `type`=?, `url`=? WHERE `id`=?");
		return $q->execute(Array(
					$data['list_id'],
					$data['title'],
					$data['type'],
					$data['url'],
					$data['id']
		));
	}

	function Delete($id) {
		$q = $this->db->prepare("DELETE FROM `mailing` WHERE `id` = ?");
		$q->execute(Array($id));
		return $q;
	}

	function getMailings() {
		$q = $this->db->query("SELECT * FROM `mailing` ORDER BY `type` ASC,`title` ASC");
		return $q->fetchAll(PDO::FETCH_ASSOC);
	}

	function getMailing($id) {
		$q = $this->db->prepare("SELECT * FROM `mailing` WHERE `id` = ?");
		$q->execute(Array($id));
		return $q->fetch(PDO::FETCH_ASSOC);
	}

	function getMailingsById($ids) {
		$w = Array();
		foreach ($ids as $id)
			$w[] = (int) $id;
		$q = $this->db->query("SELECT * FROM `mailing` WHERE `id` IN (" . join(',', $w) . ") ORDER BY FIELD(`type`,'getresponse','aweber','infusionsoft')");
		$res = $q->fetchAll(PDO::FETCH_ASSOC);
		$r = Array();
		foreach ($res as $m) {
			$mfile = 'mailing_templates/' . $m['type'] . '.html';
			if (file_exists($mfile)) {
				$m['template'] = file_get_contents($mfile);
				$r[$m['id']] = $m;
			}
		}
		return $r;
	}

}

?>