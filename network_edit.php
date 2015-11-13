<?php
	require('include/config.php');
	require('include/class.network.php');
	$n = new Network($db);
	
	if (isset($_POST['submitedit'])) {
		$n->Edit($_POST);
		Misc::Redirect('network_list.php');
	}
	
	if (isset($_POST['submitdelete'])) {
		$n->Delete($_POST['id']);
		Misc::Redirect('network_list.php');
	}
	
	$net = $n->getNetwork($_GET['id']);
	include('include/top.php');
?>
<form method="post">
	<input type="hidden" id="hidden" name="id" value="<?php echo $net['id']; ?>" />
	<p>
		<label><span>Name</span></label>
		<input type="text" name="name" id="name" placeholder="Enter network name" value="<?php echo $net['name']; ?>" />
	</p>
	<p>
		<label><span>Syntax</span></label>
		<input type="text" name="syntax" id="syntax" placeholder="Enter network syntax" value="<?php echo $net['syntax']; ?>" />
	</p>
	<p>
		<input type="submit" name="submitedit" value=" Edit " />
		<input type="submit" name="submitdelete" value=" Delete " onClick="return confirm('Delete network?')" />
	</p>
</form>
<?php include('include/bottom.php'); ?>