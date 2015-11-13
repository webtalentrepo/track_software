<?php
	if (isset($_POST['submit'])) {
		require('include/config.php');
		$r = Misc::csvParse($_FILES['file']['tmp_name']);
		if (count($r)>0) {
			require('include/class.tracking.php');
			$t = new Tracking($db);
			foreach ($r as $s) {
				if (isset($_POST['custom_revenue']) && $_POST['custom_revenue']=='1') {
					$s['custom_rev'] = str_replace('$','',$s['custom_rev']);
					$t->convertSession($s['transaction_id'],$s['custom_rev'],$_POST['overwrite']);
				}
				else $t->convertSession($s['transaction_id'],-1,$_POST['overwrite']);
			}
		}
		Misc::Redirect('sessions_list.php');
	}	
	include('include/top.php');
?>
<form method="post" enctype="multipart/form-data">
	<p>
		<label for="file">Select file</label>
		<input type="file" name="file" id="file" />
	</p>
	<p>
		<input type="hidden" name="overwrite" value="0" />
		<input type="checkbox" name="overwrite" value="1" /> Overwrite existing Session Ids</br />
		<input type="checkbox" name="custom_revenue" value="1" /> Include custom conversion revenue (from file)
	</p>
	<p>
		<input type="submit" name="submit" value=" Upload " />
	</p>
</form>
<?php include('include/bottom.php'); ?>