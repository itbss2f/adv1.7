<div class="block-fluid">      
	<form action="<?php echo site_url('yms_printingpress/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Printing Press Code</b></div>	
		<div class="span2" style="width:190px"><input type="text" name="printing_code" id="printing_code" value="<?php echo $data['code'] ?>" readonly="readonly"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Printing Press Name</b></div>	
		<div class="span2" style="width:190px"><input type="text" name="printing_name" id="printing_name"  value="<?php echo $data['name'] ?>"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2"><button class="btn btn-success" type="button" name="save" id="save">Save Printing Press button</button></div>		
		<div class="clear"></div>		
	</div>
	</form>
</div>
<script>
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
	var countValidate = 0;  
	var validate_fields = ['#printing_code', '#printing_name'];

	for (x = 0; x < validate_fields.length; x++) {			
		if($(validate_fields[x]).val() == "") {                        
			$(validate_fields[x]).css(errorcssobj);          
		  	countValidate += 1;
		} else {        
		  	$(validate_fields[x]).css(errorcssobj2);       
		}        
	}   
	if (countValidate == 0) {
		$('#formsave').submit();
	} else {			
		return false;
	}	
});
</script>