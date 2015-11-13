<?php
	include('include/top.php');

	if (isset($_POST['submit'])) {
		require('include/config.php');
		$r = Misc::csvParse($_FILES['file']['tmp_name']);
		if (count($r)>0) {
			require('include/class.user.php');
			$u = new User($db);
			$total = 0;
			$inserted = 0;
			foreach ($r as $user) {
				$user['name']='';
				$total++;
				if ($u->Insert($user)) $inserted++;
			}
			?>
			<h1>Upload complete. Inserted <em><?php echo $inserted; ?></em> out of <em><?php echo $total; ?></em> users in file.</h1>
			<?php
		}
	} else 	{ ?>
<form method="post" enctype="multipart/form-data">
	<p>
		<label for="file"><span>Select file</span></label>
		<input type="file" name="file" id="file" />
	</p>
	<p>
		<input type="submit" name="submit" value=" Upload " />
	</p>
</form>
<?php } ?>
<?php include('include/bottom.php'); ?>