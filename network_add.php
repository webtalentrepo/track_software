<?php
	if (isset($_POST['submit'])) {
		require('include/config.php');
		require('include/class.network.php');
		$n = new Network($db);
		$n->Insert($_POST);
		Misc::Redirect('network_list.php');
	}
	
	include('include/top.php');
?>
<form method="post">
	<p>
		<label><span>Name</span></label>
		<input type="text" name="name" id="name" placeholder="Enter network name" />
	</p>
	<p>
		<label><span>Syntax</span></label>
		<input type="text" name="syntax" id="syntax" placeholder="Enter network syntax" />
	</p>
	<p>
		<input type="submit" name="submit" value=" Add " />
	</p>
</form>
<?php include('include/bottom.php'); ?>