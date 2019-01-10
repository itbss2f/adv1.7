<div class="block-fluid">      
	<form action="<?php echo site_url('yms_reports/savenewreports') ?>" method="post" name="formsave" id="formsave">
	<div class="row-form-booking">
		<div class="span1"><b>Report Title</b></div>	
		<div class="span3"><input type="text" name="title" id="title"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1"><b>Description</b></div>	
		<div class="span4"><input type="text" name="description" id="description"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1"><b>SQL Query</b></div>	
		<div class="span4"><textarea name="sqlquery" id="sqlquery"></textarea></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1"><b>Formula</b></div>	
		<div class="span4"><textarea name="formula" id="formula"></textarea></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1"><b>Field Name</b></div>	
		<div class="span4"><input type="text" name="fname" id="fname"><span>Example: Name 1;Name 2;Name 3</span></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1"><b>Field Align</b></div>	
		<div class="span4"><input type="text" name="falign" id="falign"><span>Example: L;R;C</span></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1"><b>Field Size</b></div>	
		<div class="span4"><input type="text" name="fsize" id="fsize"><span>Example: .02;.02;.03</span></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2"><button class="btn btn-success" type="button" name="save" id="save">Save YMS - Report button</button></div>		
		<div class="clear"></div>		
	</div>
	</form>
</div>
<script>
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
	var countValidate = 0;  
	var validate_fields = ['#title', '#description', '#sqlquery', '#formula'];

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

