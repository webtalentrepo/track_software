<?php
	require('include/config.php');
	require('include/class.user.php');
	$user = new User($db);
	
	if (isset($_POST['submitedit'])) {
		$user->Edit($_POST);
		Misc::Redirect('user_list.php');
	}
	
	if (isset($_POST['submitdelete'])) {
		$user->Delete($_POST['id']);
		Misc::Redirect('user_list.php');
	}
	
	$u = $user->getUser($_GET['id']);
	
	include('include/top.php');
?>
<form method="post">
	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
	<p>
		<label><span>Name</span></label>
		<input type="text" name="name" id="name" placeholder="Enter user's name" value="<?php echo $u['name']; ?>"  />
	</p>
	<p>
		<label><span>E-mail</span></label>
		<input type="text" name="email" id="email" placeholder="Enter user's email address" value="<?php echo $u['email']; ?>" />
	</p>
	<p>
		<label><span>Source</span></label>
		<input type="text" name="source" id="source" placeholder="Source" value="<?php echo $u['source']; ?>" />
	</p>
	<p>
		<label><span>Country</span></label>
		<input type="text" name="country" id="country" placeholder="Country" value="<?php echo $u['country']; ?>" />
	</p>
	<p>
		<label><span>City</span></label>
		<input type="text" name="city" id="city" placeholder="City" value="<?php echo $u['city']; ?>" />
	</p>
	<p>
		<input type="submit" name="submitedit" value=" Edit " />
		<input type="submit" name="submitdelete" value=" Delete " onClick="return confirm('Delete user?')" />
	</p>
</form>
<?php include('include/bottom.php'); ?>