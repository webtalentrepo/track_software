<?php
	require('include/config.php');
	require('include/class.product.php');
	
	$p = new Product($db);
	$prod = $p->getProducts();
	
	include('include/top.php');
?>
<table class="tablesorter">
	<thead>
		<tr>
			<th>ID</th>
			<th>Title</th>
			<th>Earning</th>
			<th>Network Vendor ID</th>
			<th>Network</th>
			<th>Hash</th>
			<th>Parameter</th>
			<th>Auto subscribe enabled</th>
			<th>Auto subscribe lists</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($prod as $pr) { ?>
			<tr>
				<td><?php echo $pr['id']; ?></td>
				<td><a href="product_edit.php?id=<?php echo $pr['id']; ?>"><?php echo $pr['title']; ?></a></td>
				<td><?php echo $pr['earning']; ?></td>
				<td><?php echo $pr['replacement']; ?></td>
				<td><?php echo $pr['networkName']; ?></td>
				<td><?php echo $pr['hash']; ?></td>
				<td><?php echo $pr['parameter']; ?></td>
				<td><?php echo $pr['auto_subscribe_enabled']; ?></td>
				<td><?php echo $pr['auto_subscribe_lists']; ?></td>
		<?php } ?>
	</tbody>
</table>
<?php include('include/bottom.php'); ?>