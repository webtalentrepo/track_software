$(document).ready(function() { 
	$(".tablesorter").tablesorter(); 
	
	$(".markbadsource").click(function() {
		var el = $(this);
		if (confirm('Mark source as bad?')) {
			$.ajax({
				url: 'ajax/markBadSource.php',
				type: 'POST',
				data: {
					'source' : el.attr('rel')
				},
				success: function() {
					location.reload();
				}
			});
		}
	});
	
	$(".markgoodsource").click(function() {
		var el = $(this);
		if (confirm('Mark source as good?')) {
			$.ajax({
				url: 'ajax/markGoodSource.php',
				type: 'POST',
				data: {
					'source' : el.attr('rel')
				},
				success: function() {
					location.reload();
				}
			});
		}
	});
});
  