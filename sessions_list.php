<?php
	require('include/config.php');
	require('include/class.tracking.php');
	require('include/class.product.php');
	
	$t = new Tracking($db);
	$ses = $t->getSessions(isset($_GET['filter'])?$_GET['filter']:0,Array('pageID'=>isset($_GET['pageID'])?$_GET['pageID']*1:0));
	
	include('include/top.php');
?>
<form method="get" action="">
	<p>
		<label>Filter sessions</label>
		<input type="text" name="filter[users.email]" value="<?php echo $_GET['filter']['users.email']; ?>" placeholder="Email" style="width: 120px;" />
		<input type="text" name="filter[tracking.hash]" value="<?php echo $_GET['filter']['tracking.hash']; ?>" placeholder="Session ID" style="width: 120px;" />
		<input type="text" name="filter[users.source]" value="<?php echo $_GET['filter']['users.source']; ?>" placeholder="Source" style="width: 120px;" />
		<input type="text" name="filter[tracking.cid]" value="<?php echo $_GET['filter']['tracking.cid']; ?>" placeholder="Custom ID" style="width: 120px;" />
		<select name="filter[products.id]" style="width: 210px;">
				<option value=""></option>
			<?php
				$p = new Product($db);
				$ps = $p->getProducts();
				foreach ($ps as $pr) {?>
					<option value="<?php echo $pr['id']; ?>" <?php if ($_GET['filter']['products.id']==$pr['id']) echo "selected"; ?>><?php echo $pr['title']; ?></option>
				<?php } ?>
		</select>
		<input type="submit" name="submit" value=" Filter " />
	</p>
</form>
<table class="tablesorter">
	<thead>
		<tr>
			<th>Hash</th>
			<th>User email</th>
			<th>Country</th>
			<th>City</th>
			<th>Source</th>
			<th>CID</th>
			<th>Product</th>
			<th>Started</th>
			<th>Converted</th>
			<th>Views</th>
			<th>Earning</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($ses as $s) { ?>
			<tr>
				<td><a href="click_list.php?hash=<?php echo $s['hash']; ?>"><?php echo $s['hash']; ?></a></td>
				<td><?php echo $s['email']; ?></td>
				<td><?php echo $s['country']; ?></td>
				<td><?php echo $s['city']; ?></td>
				<td><?php echo $s['source']; ?></td>
				<td><?php echo $s['cid']; ?></td>
				<td><?php echo $s['title']; ?></td>
				<td><?php echo $s['started']; ?></td>
				<td><?php echo $s['converted']; ?></td>
				<td><?php echo $s['views']; ?></td>
				<td><?php echo $s['earning']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="10" align="center">
			<div class="pagination">
			<?php
				$pd = $t->getPaginationData();
				$pager_options = array(
					'mode'       => 'Sliding',
					'perPage'    => $pd['limit'],
					'delta'      => 10,
					'totalItems' => $pd['total'],
				);
				$pager = Pager::factory($pager_options);
				echo $pager->links;
			?>
			</div>
			</td>
		</tr>
	</tfoot>
</table>
<?php include('include/bottom.php'); ?>