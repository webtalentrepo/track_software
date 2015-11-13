<?php 
	require('include/config.php');
	require('include/class.product.php');
	include('include/top.php'); 
?>
<form method="get">
	Date start: <input type="text" name="start" value="<?php echo isset($_GET['start']) ? $_GET['start'] : 	date("Y-m-d",mktime()-30*24*3600); ?>" style="width: 100px;" />
	Date end: <input type="text" name="end" value="<?php echo isset($_GET['end']) ? $_GET['end'] : date("Y-m-d"); ?>" style="width: 100px;" />
	Group by: <select name="group" style="width: 190px;">
		<option value="email" <?php if ($_GET['group']=='email') echo 'selected'; ?>>E-mail address</option>
		<option value="product" <?php if ($_GET['group']=='product') echo 'selected'; ?>>Product</option>
		<option value="country" <?php if ($_GET['group']=='country') echo 'selected'; ?>>Country</option>
		<option value="city" <?php if ($_GET['group']=='city') echo 'selected'; ?>>City</option>
		<option value="network" <?php if ($_GET['group']=='network') echo 'selected'; ?>>Network</option>
		<option value="source" <?php if ($_GET['group']=='source') echo 'selected'; ?>>Source name</option>
		<option value="cid" <?php if ($_GET['group']=='cid') echo 'selected'; ?>>Custom ID</option>
	</select> 
	<input type="checkbox" name="filter[sources.type]" value="good" <?php if ($_GET['filter']['sources.type']=='good' || !isset($_GET['submit'])) echo 'checked="checked"'; ?> /> Hide bad sources
	<input type="submit" name="submit" value="Generate" />
	<p>
		<label>Filter report</label>
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
<?php
	if (isset($_GET['submit'])) {
		require('include/class.tracking.php');
		
		$t = new Tracking($db);
		
		$ses = $t->getSessionsReport($_GET['start'].' 00:00:00',$_GET['end'].' 23:59:59',$_GET['group'],isset($_GET['filter'])?$_GET['filter']:0);
?>
<table class="tablesorter">
	<thead>
		<tr>
			<th>Param</th>
			<th>Revenue</th>
			<th>Unique clicks</th>
			<th>Raw Clicks</th>
			<th>Conversion Count</th>
			<th>Unique Clicks CR</th>
			<th>Raw Clicks CR</th>
			<th>Unique EPC</th>
			<th>Raw EPC</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($ses as $s) { 
			if ($_GET['group']=='source') {
				if (strlen($s['source_name'])>0) $s['param'] = $s['source_name'];
			}
		?>
		<tr>
			<td class="showa">
				<?php if ($_GET['group']=='source') { ?>
					<?php if($s['sourceType']!='bad') { ?>
					<a href="javascript: void(0)" class="markbadsource" rel="<? echo $s['sourceID']; ?>" title="Mark source as 'bad'">X</a>
					<?php } else { ?>
					<a href="javascript: void(0)" class="markgoodsource" rel="<? echo $s['sourceID']; ?>" title="Mark source as 'good'">OK</a>
					<?php } ?>
				<?php } ?>
				<?php echo $s['param']; ?>
			</td>
			<td align="right">$<?php echo $s['earnings']; $total1 += $s['earnings']; ?></td>
			<td align="right"><?php echo $s['sessions']; $total2 += $s['sessions']; ?></td>
			<td align="right"><?php echo $s['views']; $total3 += $s['views']; ?></td>
			<td align="right"><?php echo $s['converted']; $total4 += $s['converted']; ?></td>
			<td align="center"><?php echo round(($s['converted']/$s['sessions'])*100,2); ?>%</td>
			<td align="center"><?php echo round(($s['converted']/$s['views'])*100,2); ?>%</td>
			<td align="center">$<?php echo round($s['earnings']/$s['sessions'],2); ?></td>
			<td align="center">$<?php echo round($s['earnings']/$s['views'],2); ?></td>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<th>Param</th>
			<th>Revenue</th>
			<th>Unique clicks</th>
			<th>Raw Clicks</th>
			<th>Conversion Count</th>
			<th>Unique Clicks CR</th>
			<th>Raw Clicks CR</th>
			<th>Unique EPC</th>
			<th>Raw EPC</th>
		</tr>
	</tfoot>
</table>
<table>
	<thead>
		<tr>
			<td align="right">Total</td>
			<td align="right"><?php echo $total1; ?></td>
			<td align="right"><?php echo $total2; ?></td>
			<td align="right"><?php echo $total3; ?></td>
			<td align="right"><?php echo $total4; ?></td>
			<td> </td>
			<td> </td>
			<td> </td>
			<td> </td>
		</tr>
	</tfoot>
</table>
<?php } ?>
<?php include('include/bottom.php'); ?>