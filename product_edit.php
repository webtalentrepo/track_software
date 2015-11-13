<?php
	require('include/config.php');
	require('include/class.product.php');
	require('include/class.network.php');
	require('include/class.mailing.php');
	
	$p = new Product($db);
	
	if (isset($_POST['submitedit'])) {
	
		$as = Array();
		foreach ($_POST['auto_subscribe_lists'] as $network=>$list) if ($list>0) $as[]=$list;
		$_POST['auto_subscribe_lists'] = join(",",$as);
	
		$p->Edit($_POST);
		Misc::Redirect('product_list.php');
	}
	
	if (isset($_POST['submitdelete'])) {
		$p->Delete($_POST['id']);
		Misc::Redirect('product_list.php');
	}
	
	$pr = $p->getProduct($_GET['id']);
	$pr['auto_subscribe_lists'] = explode(',',$pr['auto_subscribe_lists']);
	include('include/top.php');
?>
<form method="post">
	<input type="hidden" id="hidden" name="id" value="<?php echo $pr['id']; ?>" />
	<p>
		<label><span>Title</span></label>
		<input type="text" name="title" id="title" placeholder="Enter product title" value="<?php echo $pr['title']; ?>" />
	</p>
	<p>
		<label><span>Description</span></label>
		<textarea name="description" id="description"><?php echo $pr['description']; ?></textarea>
	<p>
		<label><span>Earning</span></label>
		<input type="text" name="earning" id="earning" placeholder="Earning" value="<?php echo $pr['earning']; ?>" />
	</p>
	<p>
		<label><span>Network Vendor ID</span></label>
		<input type="text" name="replacement" id="replacement" placeholder="Network Affiliate ID"  value="<?php echo $pr['replacement']; ?>" />
	</p>
	<p>
		<label><span>CPA Network</span></label>
		<select name="network">
			<?php
				$n = new Network($db);
				$nets = $n->getNetworks();
				foreach ($nets as $net) { ?>
				<option value="<?php echo $net['id']; ?>" <?php if ($net['id']==$pr['network']) echo "selected"; ?>><?php echo $net['name']; ?></option>
				<?php } ?>
		</select>
	</p>
	<p>
		<label><span>Custom parameter</span></label>
		<input type="text" name="parameter" id="parameter" placeholder="parameter=value"  value="<?php echo $pr['parameter']; ?>" />
	</p>
	<p>
		<label><span>Auto subscribe</span></label>
		<input type="checkbox" name="auto_subscribe_enabled" value="1" <?php if ($pr['auto_subscribe_enabled']=='1') echo 'checked="checked"'; ?> /> Enable auto subscribe <br />
		<?php
			$m = new Mailing($db);
			$ms = $m->getMailings();
			$mailings = Array();
			foreach ($ms as $mal) $mailings[$mal['type']][] = $mal;
			foreach ($mailings as $network=>$lists) { ?>
			<fieldset>
				<legend><?php echo ucfirst($network); ?></legend>
				<input type="radio" name="auto_subscribe_lists[<?php echo $network; ?>]" value="0" checked="checked" /> None
				<?php foreach ($lists as $list) { ?>
					<input type="radio" name="auto_subscribe_lists[<?php echo $network; ?>]" value="<?php echo $list['id']; ?>" <?php if (in_array($list['id'],$pr['auto_subscribe_lists'])) echo 'checked="checked"'; ?>  /> <?php echo $list['title']; ?>
				<?php } ?>
			</fieldset>
		<?php } ?>
	</p>
	<p>
		<input type="submit" name="submitedit" value=" Edit " />
		<input type="submit" name="submitdelete" value=" Delete " onClick="return confirm('Delete product?')" />
	</p>
</form>
<?php include('include/bottom.php'); ?>