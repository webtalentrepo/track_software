<?php
	require('include/config.php');
	require('include/class.user.php');
	
	$u = new User($db);
	$users = $u->getUsers(Array('pageID'=>isset($_GET['pageID'])?$_GET['pageID']*1:0));

	include('include/top.php');
?>
<table class="tablesorter">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Email</th>
			<th>Source</th>
			<th>Country</th>
			<th>City</th>
			<th>Hash</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user) { ?>
			<tr>
				<td><?php echo $user['id']; ?></td>
				<td><a href="user_edit.php?id=<?php echo $user['id']; ?>"><?php echo $user['name']; ?></a></td>
				<td><?php echo $user['email']; ?></td>
				<td><?php echo $user['source']; ?></td>
				<td><?php echo $user['country']; ?></td>
				<td><?php echo $user['city']; ?></td>
				<td><?php echo $user['hash']; ?></td>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="7" align="center">
			<div class="pagination">
			<?php
				$pd = $u->getPaginationData();
				$pager_options = array(
					'mode'       => 'Sliding',
					'perPage'    => $pd['limit'],
					'delta'      => 8,
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