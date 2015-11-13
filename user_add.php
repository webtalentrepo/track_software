<?php
	if (isset($_POST['submit'])) {
		require('include/config.php');
		require('include/class.user.php');
		$user = new User($db);
		$user->Insert($_POST);
		Misc::Redirect('user_list.php');
	}
	
	include('include/top.php');
?>
<form method="post">
	<p>
		<label><span>Name</span></label>
		<input type="text" name="name" id="name" placeholder="Enter user's name" />
	</p>
	<p>
		<label><span>E-mail</span></label>
		<input type="text" name="email" id="email" placeholder="Enter user's email address" />
	</p>
	<p>
		<label><span>Source</span></label>
		<input type="text" name="source" id="source" placeholder="Source" />
	</p>
	<p>
		<label><span>Country</span></label>
		<input type="text" name="country" id="country" placeholder="Country" />
	</p>
	<p>
		<label><span>City</span></label>
		<input type="text" name="city" id="city" placeholder="City" />
	</p>
	<p>
		<input type="submit" name="submit" value=" Add " />
	</p>
</form>
<?php include('include/bottom.php'); ?>