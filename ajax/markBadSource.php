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
			'type' => 'bad'
		));
	} else {
		$n->setType(Array(
			'type' => 'bad',
			'source_id' => $source
		));
	}
?>