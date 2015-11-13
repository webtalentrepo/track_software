<?php
	require('include/config.php');
	require('include/class.mailing.php');
	$n = new Mailing($db);

	if (isset($_POST['submit'])) {
		$n->Edit($_POST);
		Misc::Redirect('mailing_list.php');
	}
	
	if (isset($_POST['submitdelete'])) {
		$n->Delete($_POST['id']);
		Misc::Redirect('mailing_list.php');
	}
	
	include('include/top.php');
	$s = $n->getMailing($_GET['id']);
?>
<form method="post">
	<input type="hidden" id="hidden" name="id" value="<?php echo $s['id']; ?>" />
	<p>
		<label><span>Title</span></label>
		<input type="text" name="title" id="title" placeholder="Enter mailing list title" value="<?php echo $s['title']; ?>" />
	</p>
	<p>
		<label><span>List ID</span></label>
		<input type="text" name="list_id" id="list_id" placeholder="Enter mailing list id" value="<?php echo $s['list_id']; ?>" />
	</p>
	<p>
		<label><span>URL</span></label>
		<input type="text" name="url" id="url" placeholder="Enter mailing list url" value="<?php echo $s['url']; ?>" />
	</p>
	<p>
		<label><span>Autoresponder</span></label>
		<select name="type">
			<option value="getresponse" <?php if ($s['type']=='getresponse') echo 'selected'; ?>>GetResponse</option>
			<option value="infusionsoft" <?php if ($s['type']=='infusionsoft') echo 'selected'; ?>>InfusionSoft</option>
			<option value="aweber" <?php if ($s['type']=='aweber') echo 'selected'; ?>>Aweber</option>
		</select>
	</p>
	<p>
		<input type="submit" name="submit" value=" Edit " />
		<input type="submit" name="submitdelete" value=" Delete " onClick="return confirm('Delete mailing list?')" />
	</p>
</form>
<?php include('include/bottom.php'); ?>