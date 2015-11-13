<?php
	require('include/config.php');
	require('include/class.source.php');
	
	$n = new Source($db);
	$sors = $n->getSources();
	
	include('include/top.php');
?>
<table class="tablesorter">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($sors as $s) { ?>
			<tr>
				<td><?php echo $s['source_id']; ?></td>
				<td><a href="sources_edit.php?id=<?php echo $s['source_id']; ?>"><?php echo $s['source_name']; ?></a></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?php include('include/bottom.php'); ?>