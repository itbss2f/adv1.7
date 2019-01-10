<div class="block-fluid">      
	<form action="<?php echo site_url('yms_product/save') ?>" method="post" name="formsave" id="formsave"> 
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Product Code</b></div>	
		<div class="span1"><input type="text" name="product_code" id="product_code"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Product Name</b></div>	
		<div class="span2" style="width:190px"><input type="text" name="product_name" id="product_name"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Total CCM</b></div>	
		<div class="span1"><input type="text" name="product_totalccm" id="product_totalccm" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Edition</b></div>	
		<div class="span2" style="width:190px">
			<select name="product_edition" id="product_edition">
				<?php foreach ($edition as $edition) : ?>
				<option value="<?php echo $edition['id'] ?>"><?php echo $edition['code'].' - '.$edition['name'] ?></option>
				<?php endforeach; ?>
			</select>
		</div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2"><button class="btn btn-success" type="button" name="save" id="save">Save YMS - Product button</button></div>		
		<div class="clear"></div>		
	</div>
	</form>
</div>
<script>
$("#product_totalccm").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
	var countValidate = 0;  
	var validate_fields = ['#product_code', '#product_name', '#product_totalccm', '#product_edition'];

	for (x = 0; x < validate_fields.length; x++) {			
		if($(validate_fields[x]).val() == "") {                        
			$(validate_fields[x]).css(errorcssobj);          
		  	countValidate += 1;
		} else {        
		  	$(validate_fields[x]).css(errorcssobj2);       
		}        
	}   
	if (countValidate == 0) {
		$.ajax({
			url: "<?php echo site_url('yms_product/validateCode') ?>",
			type: "post",
			data: {code : $("#product_code").val()},
			success: function(response) {
				if (response == "true") {                    
		               alert("Product Code must be unique!.");
		               $('#product_code').val('');
		           } else {
		               $('#formsave').submit();
		           }
			}
		});		
	} else {			
		return false;
	}	
});
</script>
