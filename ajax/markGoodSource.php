<?
	require('../include/config.php');
	require('../include/class.source.php');
	$n = new Source($db);
	
	$source = $_POST['source'];
	
	$s = $n->getSource($source);
	if (!isset($s['source_id'])) {
		$n->Insert(Array(
			'source_id' => $source,
			'source_name' => $source,
			'type' => 'good'
		));
	} else {
		var_dump($n->setType(Array(
			'type' => 'good',
			'source_id' => $source
		)));
	}
?>