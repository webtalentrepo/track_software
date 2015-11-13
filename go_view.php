<?php
	require('include/config.php');
	
	if (!isset($_GET['email']) || !isset($_GET['product'])) die('Both email and product id are required!');
	
	require('include/class.product.php');
	$p = new Product($db);
	$product = $p->getProduct($_GET['product']);
	if (!isset($product['id'])) die('Product does not exist!');
	
	include("go_template.html");
	
	require('include/class.user.php');
	$u = new User($db);
	$user = $u->getUserByEmail($_GET['email']);
	if (!isset($user['id'])) {
		$u->Insert(Array(
			'name' => 'Unknown user',
			'email' => $_GET['email'],
			'source' => isset($_GET['source'])?$_GET['source']:'' 
		));
		$user = $u->getUserByEmail($_GET['email']);
	}
	
	require('include/class.tracking.php');
	$t = new Tracking($db);
	$hash = $t->newSession(Array(
		'user_id' => $user['id'],
		'product_id' => $product['id'],
		'source' => 'forwarder'
	));
	
	$link = $product['networkLink'];
		
	if (strlen($product['parameter'])>0) {
		if (strpos($link,'?')!==false) $link .= "&".$product['parameter'];
		else $link .= "?".$product['parameter'];
	}
	
	$link = str_replace(Array("{product}","{transaction_id}","{email}"),Array($product['replacement'],$hash,$_GET['email']),$link);

	if ($product['auto_subscribe_enabled']!='1' || $product['auto_subscribe_lists']=='0' || $product['auto_subscribe_lists']=='') { 
?>
<script type="text/javascript">
	setTimeout( function() {
		alert('No lists, you would be redirected now to: <?php echo $link; ?>');
	}, 100);
</script>
<?php } else { 

	require('include/class.mailing.php');
	require('include/class.settings.php');
	$settings = new Settings($db);
	$interval = $settings->getSetting('autosub_interval');
	$lists = explode(",",$product['auto_subscribe_lists']);
	$m = new Mailing($db);
	$lists = $m->getMailingsById($lists); 
	$outHtml = "";
	$outScript = "";
	?>
	<script type="text/javascript">
		var totalFrames = <?php echo count($lists); ?>;
		var loadedFrames = 0;
		var started = false;
		var pauseInterval = <? echo $interval['value']; ?>;
		
		function loadFrame(f) {
			if (started) {
				loadedFrames++;
				if (loadedFrames>totalFrames) alert('You would be redirected now to: <?php echo $link; ?>');
				else {
					var c = loadedFrames - 1;
					setTimeout( function() {
						document.forms[c].submit.click();
					},(c+1)*pauseInterval);
				}
			}
		}
	<?php
	$i = 0;
	foreach ($lists as $lid=>$l) {
			$outHtml .= str_replace(Array('{list_id}','{email}','{url}','{title}','display: none;'),Array($l['list_id'],$_GET['email'],$l['url'],$l['title'],''),$l['template'])."\r\n"; 
			$outScript .= 'setTimeout( function() { document.form_'.$l['list_id'].'.submit.click(); }, '.($i+1).'*pauseInterval);'."\r\n";
	} ?>
	</script>
	<?php echo $outHtml; ?>
	<script type="text/javascript">
		started=true;
		setTimeout( function() {
			loadFrame(0);
		}, 500);
	</script>
<?php } ?>