<?php
	require('include/config.php');

	require('include/class.network.php');
	require('include/class.product.php');
	
	include('include/top.php');
?>
<script type="text/javascript">
	function generateLink(f) {
		var l = 'http://govisit.it/go.php?email=';
		l += f.syntax.value;
		l += '&product=';
		v = f.product.options[f.product.selectedIndex].value.split('|');
		l += v[0];
		
		if (f.src.value.length>0) l += '&source='+f.src.value;
		if (v[1].length>0) l+= '&' + v[1];
		if (f.cid.value.length>0) l += '&cid='+f.cid.value;
				
		$("#link").html(l);
		return false;
	}
</script>
<form method="post" onsubmit="return generateLink(this)">
	<p>
		<label><span>Email placeholder syntax</span></label>
		<input type="text" name="syntax" id="syntax" placeholder="Email placeholder syntax" />
	</p>
	<p>
		<label><span>Product to promote</span></label>
		<select name="product" id="product">
			<?php
				$p = new Product($db);
				$pr = $p->getProducts();
				
				foreach ($pr as $product) { ?>
				<option value="<?php echo $product['id']."|".((substr($product['parameter'],0,6)!='email=')?$product['parameter']:''); ?>"><?php echo $product['title'];?></option>
				<?php } ?>
		</select>
	</p>
	<p>
		<label><span>Source</span></label>
		<input type="text" name="src" id="src" placeholder="Source" />
	</p>
	<p>
		<label><span>CID</span></label>
		<input type="text" name="cid" id="cid" placeholder="Custom ID" />
	</p>
	<p>
		<h3 id="link"></h3>
	<p>
		<input type="submit" name="submit" value=" Generate " />
	</p>
</form>
<?php include('include/bottom.php'); ?>