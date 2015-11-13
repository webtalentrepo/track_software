<?php
	require('include/config.php');
	require('include/class.settings.php');
	$n = new Settings($db);

	if (isset($_POST['submit'])) {
		$n->Edit($_POST);
		Misc::Redirect('settings_list.php');
	}
	if (isset($_POST['submitdelete'])) {
		$n->Delete($_POST['id']);
		Misc::Redirect('settings_list.php');
	}
	
	include('include/top.php');
	
	$s = $n->getSetting($_GET['id']);
?>
<form method="post">
	<input type="hidden" name="id" value="<?php echo $s['id']; ?>" />
	<p>
		<label><span>Option name</span></label>
		<input type="text" name="name" id="name" placeholder="Option name" value="<?php echo $s['name']; ?>" />
	</p>
	<p>
		<label><span>Option value</span></label>
		<input type="text" name="value" id="value" placeholder="Option value" value="<?php echo $s['value']; ?>" />
	</p>
	<p>
		<label><span>Option description</span></label>
		<input type="text" name="description" id="description" placeholder="Option description" value="<?php echo $s['description']; ?>" />
	</p>
	<p>
		<input type="submit" name="submit" value=" Edit " />
		<input type="submit" name="submitdelete" value=" Delete " onClick="return confirm('Delete option?')" />
	</p>
</form>
<?php include('include/bottom.php'); ?>