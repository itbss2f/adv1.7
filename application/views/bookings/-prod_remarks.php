<?php 
if ($valid != 0) :
$reverse = count($explodedata);
for ($i = $reverse - 1; $i >=0; $i--) { ?>
<tr id="prod_reverse<?php echo $i ?>">
	<td><span class="date"><?php echo date("M d, Y", strtotime($explodedata[$i]['0'])); ?></span><span class="time"><?php echo date("h:m:s", strtotime($explodedata[$i]['0'])); ?></span></td>
	<td><a href="#"><?php echo @$explodedata[$i]['1'] ?></td>
	<td><span class="price"><?php echo @$explodedata[$i]['2'] ?></span></td>
	<td><div class="controls">
		<?php if (@$explodedata[$i]['3'] == "$") :?>		
		<a class="icon-trash" href="#" id="<?php echo $i; ?>"></a>
		<?php else : ?>
		<a class="icon-tag" href="#"></a>
		<?php endif;?>		
	    </div>
	</td>
</tr>
<?php
}
endif;	
?>

<script>
	$(".prod_remarks_view .icon-trash").click(function() {
		var $rev_id = $(this).attr('id');

		var $ext_prod_rem = $("#ext_prod_rem_text").val();

		$.ajax({
			url: "<?php echo site_url('booking/remove_productionremarks') ?>",
			type: "post",
			data: {ext_prod_rem: $ext_prod_rem, rev_id: $rev_id},
			success: function(response) {
				$response = $.parseJSON(response);
				
				$("#prod_rem_text").focus().val("");
				$("#production").val($response['prod_remarks']);
				$("#ext_prod_rem_text").val($response['prod_remarks']);
				$(".prod_remarks_view").html($response['prodremarks']);
			}
		});
	});
</script>
