<?php
	if (isset($_POST['submit'])) {
		require('include/config.php');
		require('include/class.settings.php');
		$n = new Settings($db);
		$n->Insert($_POST);
		Misc::Redirect('settings_list.php');
	}
	
	include('include/top.php');
?>
<form method="post">
	<p>
		<label><span>Option name</span></label>
		<input type="text" name="name" id="name" placeholder="Option name" />
	</p>
	<p>
		<label><span>Option value</span></label>
		<input type="text" name="value" id="value" placeholder="Option value" />
	</p>
	<p>
		<label><span>Option description</span></label>
		<input type="text" name="description" id="description" placeholder="Option description" />
	</p>
	<p>
		<input type="submit" name="submit" value=" Add " />
	</p>
</form>
<?php include('include/bottom.php'); ?>