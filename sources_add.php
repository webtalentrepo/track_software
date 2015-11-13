<?php
	if (isset($_POST['submit'])) {
		require('include/config.php');
		require('include/class.source.php');
		$n = new Source($db);
		$n->Insert($_POST);
		if ($_POST['type']!='bad') Misc::Redirect('sources_list.php');
		else Misc::Redirect('badsources_list.php');
	}
	
	include('include/top.php');
?>
<form method="post">
	<p>
		<label><span>Source ID</span></label>
		<input type="text" name="source_id" id="source_id" placeholder="Enter source ID" />
	</p>
	<p>
		<label><span>Source name</span></label>
		<input type="text" name="source_name" id="source_name" placeholder="Enter source name" />
	</p>
	<p>
		<label><span>Source type</span></label>
		Good <input type="radio" name="type" value="good" checked="checked" />&nbsp;<input type="radio" name="type" value="bad" /> Bad
	</p>
	<p>
		<input type="submit" name="submit" value=" Add " />
	</p>
</form>
<?php include('include/bottom.php'); ?>