<?php
	require('include/config.php');
	require('include/class.mailing.php');
	
	$n = new Mailing($db);
	$nets = $n->getMailings();
	
	include('include/top.php');
?>
<table class="tablesorter">
	<thead>
		<tr>
			<th>ID</th>
			<th>Title</th>
			<th>List ID</th>
			<th>URL</th>
			<th>Autoresponder</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($nets as $net) { ?>
			<tr>
				<td><?php echo $net['id']; ?></td>
				<td><a href="mailing_edit.php?id=<?php echo $net['id']; ?>"><?php echo $net['title']; ?></a></td>
				<td><?php echo $net['list_id']; ?></td>
				<td><?php echo $net['url']; ?></td>
				<td><?php echo $net['type']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?php include('include/bottom.php'); ?>