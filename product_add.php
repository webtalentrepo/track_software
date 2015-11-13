<?php
	require('include/config.php');
	require('include/class.network.php');
	require('include/class.mailing.php');

	if (isset($_POST['submit'])) {
		require('include/class.product.php');
		$p = new Product($db);
		
		$as = Array();
		foreach ($_POST['auto_subscribe_lists'] as $network=>$list) if ($list>0) $as[]=$list;
		$_POST['auto_subscribe_lists'] = join(",",$as);
		
		$p->Insert($_POST);
		Misc::Redirect('product_list.php');
	}
	include('include/top.php');
?>
<form method="post">
	<p>
		<label><span>Title</span></label>
		<input type="text" name="title" id="title" placeholder="Enter product title" />
	</p>
	<p>
		<label><span>Description</span></label>
		<textarea name="description" id="description"></textarea>
	<p>
		<label><span>Earning</span></label>
		<input type="text" name="earning" id="earning" placeholder="Earning" />
	</p>
	<p>
		<label><span>Network Vendor ID</span></label>
		<input type="text" name="replacement" id="replacement" placeholder="Network Affiliate ID" />
	</p>
	<p>
		<label><span>CPA Network</span></label>
		<select name="network">
			<?php
				$n = new Network($db);
				$nets = $n->getNetworks();
				foreach ($nets as $net) { ?>
				<option value="<?php echo $net['id']; ?>"><?php echo $net['name']; ?></option>
				<?php } ?>
		</select>
	</p>
	<p>
		<label><span>Custom parameter</span></label>
		<input type="text" name="parameter" id="parameter" placeholder="parameter=value" />
	</p>
	<p>
		<label><span>Auto subscribe</span></label>
		<input type="checkbox" name="auto_subscribe_enabled" value="1" /> Enable auto subscribe <br />
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
					<input type="radio" name="auto_subscribe_lists[<?php echo $network; ?>]" value="<?php echo $list['id']; ?>"  /> <?php echo $list['title']; ?>
				<?php } ?>
			</fieldset>
		<?php } ?>
	</p>
	<p>
		<input type="submit" name="submit" value=" Add " />
	</p>
</form>
<?php include('include/bottom.php'); ?>