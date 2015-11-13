<?php
	require('include/config.php');
	require('include/class.network.php');
	
	$n = new Network($db);
	$nets = $n->getNetworks();
	
	include('include/top.php');
?>
<table class="tablesorter">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Syntax</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($nets as $net) { ?>
			<tr>
				<td><?php echo $net['id']; ?></td>
				<td><a href="network_edit.php?id=<?php echo $net['id']; ?>"><?php echo $net['name']; ?></a></td>
				<td><?php echo $net['syntax']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?php include('include/bottom.php'); ?>