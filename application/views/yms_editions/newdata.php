<div class="block-fluid">      
	<form action="<?php echo site_url('yms_edition/save') ?>" method="post" name="formsave" id="formsave"> 
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Edition Code</b></div>	
		<div class="span1"><input type="text" name="edition_code" id="edition_code"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Edition Name</b></div>	
		<div class="span2" style="width:190px"><input type="text" name="edition_name" id="edition_name"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Total CCM</b></div>	
		<div class="span1"><input type="text" name="edition_totalccm" id="edition_totalccm" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Type</b></div>	
		<div class="span2" style="width:190px">
			<select name="edition_type" id="edition_type">
				<option value="D">Display</option>
				<option value="C">Classifieds</option>
				<option value="B">Display/Classifieds</option>
			</select>
		</div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2"><button class="btn btn-success" type="button" name="save" id="save">Save YMS - Edition button</button></div>		
		<div class="clear"></div>		
	</div>
	</form>
</div>
<script>
$("#edition_totalccm").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
	var countValidate = 0;  
	var validate_fields = ['#edition_code', '#edition_name', '#edition_totalccm', '#edition_type'];

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
			url: "<?php echo site_url('yms_edition/validateCode') ?>",
			type: "post",
			data: {code : $("#edition_code").val()},
			success: function(response) {
				if (response == "true") {                    
		               alert("Edition Code must be unique!.");
		               $('#edition_code').val('');
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
