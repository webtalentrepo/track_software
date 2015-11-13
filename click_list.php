<?php
	require('include/config.php');
	require('include/class.tracking.php');
	
	$t = new Tracking($db);
	$views = $t->getViews($_GET['hash']);
	
	include('include/top.php');
?>
<table>
	<thead>
		<tr>
			<th>Date/Time</th>
			<th>Browser</th>
			<th>Referer</th>
			<th>IP</th>
			<th>Source</th>
			<th>Parameters</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($views as $v) { ?>
			<tr>
				<td><?php echo $v['datetime']; ?></td>
				<td><?php echo $v['browser']; ?></td>
				<td><?php echo $v['referer']; ?></td>
				<td><?php echo $v['ip']; ?></td>
				<td><?php echo $v['source']; ?></td>
				<td><?php echo $v['parameters']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?php include('include/bottom.php'); ?>