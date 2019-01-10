<div class="row-form">
	<div class="span2" style="width:80px">Main Module</div>
	<div class="span3">
		<select name="main_module" id="main_module">
			<option value="">--</option>
			<?php foreach ($main_module as $main_module) : ?>
			<option value="<?php echo $main_module['id'] ?>"><?php echo $main_module['name'] ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="clear"></div>
</div>
<div class="block-fluid table-sorting" style="overflow:auto;height:350px">
	<table cellpadding="0" cellspacing="0" width="100%" class="table">
		<tbody class="module_function_list">               
		</tbody>
	</table>
	<div class="clear"></div>
</div>
<script>
$("#main_module").change(function(){
	var $main_module = $("#main_module").val();
	var $userid = "<?php echo $userid ?>";
	$.ajax({
		url: "<?php echo site_url('user/module_function_list') ?>",
		type: "post",
		data: {main_module: $main_module, userid: $userid},
		success: function(response) {
			$response = $.parseJSON(response);
			$(".module_function_list").html($response['modulelistview']);
		}
	});
}); 
</script>
