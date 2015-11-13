<?php
	require('include/config.php');
	require('include/class.source.php');
	$n = new Source($db);
	
	if (isset($_POST['submitedit'])) {
		$n->Edit($_POST);
		if ($_POST['type']!='bad') Misc::Redirect('sources_list.php');
		else Misc::Redirect('badsources_list.php');
	}
	
	if (isset($_POST['submitdelete'])) {
		$n->Delete($_POST['source_id']);
		if ($_POST['type']!='bad') Misc::Redirect('sources_list.php');
		else Misc::Redirect('badsources_list.php');
	}
	
	$s = $n->getSource($_GET['id']);
	include('include/top.php');
?>
<form method="post">
	<input type="hidden" id="hidden" name="source_id" value="<?php echo $s['source_id']; ?>" />
	<p>
		<label><span>Source ID </span>#<?php echo $s['source_id']; ?></label>
	</p>
	<p>
		<label><span>Source Name</span></label>
		<input type="text" name="source_name" id="source_name" placeholder="Enter source name" value="<?php echo $s['source_name']; ?>" />
	</p>
	<p>
		<label><span>Source type</span></label>
		Good <input type="radio" name="type" value="good" <?php if ($s['type']=='good') echo 'checked="checked"'; ?> />&nbsp;<input type="radio" name="type" value="bad" <?php if ($s['type']=='bad') echo 'checked="checked"'; ?> /> Bad
	</p>
	<p>
		<input type="submit" name="submitedit" value=" Edit " />
		<input type="submit" name="submitdelete" value=" Delete " onClick="return confirm('Delete source?')" />
	</p>
</form>
<?php include('include/bottom.php'); ?>