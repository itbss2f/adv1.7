<fieldset style="color: #000000; width: 300px;">
		<legend>Reserve Area</legend>
		<p>
			<label>Width</label>
			<input type="text" name="reservewidth" id="reservewidth" size="8">cols
			<label>Height</label>
			<input type="text" name="reserveheight" id="reserveheight" size="8">cm
		</p>
		<p><label>Description</label></p>
		<p>
			<textarea style="width: 273px; height: 67px;" name="reservedescription" id="reservedescription"></textarea>
		</p>
		<p>
		<input type="button" name="okblockout" id="okblockout" value="OK">
		<input type="button" name="cancelblockout" id="cancelblockout" value="CANCEL">
		</p>
</fieldset>
<script>
	$("#cancelblockout").click(function(){
		jQuery.facebox.close(this);
	});
	
	$("#okblockout").click(function(){
		if (product == "" || date == "") {		
			alert('No product and issue date');
		} else {	
			var reservewidth = $(":input[name='reservewidth']").val();
			var reserveheight = $(":input[name='reserveheight']").val();
			var reservedescription = $(":input[name='reservedescription']").val();
			if (reservewidth == "" || reserveheight == "" || reservedescription == "") {
				alert("Filled up all fields");
			} else {
				$.ajax({
					url: '<?php echo site_url('displaydummy/dummy/addblockout') ?>',
					type: 'post',
					data: {product: product, date: date, key: key, viewing: $("#viewing").val(),
						   reservewidth: reservewidth, reserveheight: reserveheight, reservedescription: reservedescription
					},
					success: function(response){
						var $response = $.parseJSON(response);
						//$("#content1").html($response['pagelayout']);
						$("#content2-content2").html($response['blockoutlist']);
						jQuery.facebox.close(this);
					}
				});
			}
		}
	});
</script>