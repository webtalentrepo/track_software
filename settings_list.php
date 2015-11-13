<?php
	require('include/config.php');
	require('include/class.settings.php');
	
	$n = new Settings($db);
	$sets = $n->getSettings();
	
	include('include/top.php');
?>
<table class="tablesorter">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Value</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($sets as $s) { ?>
			<tr>
				<td><?php echo $s['id']; ?></td>
				<td><a href="settings_edit.php?id=<?php echo $s['id']; ?>"><?php echo $s['name']; ?></a></td>
				<td><?php echo $s['value']; ?></td>
				<td><?php echo $s['description']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?php include('include/bottom.php'); ?>