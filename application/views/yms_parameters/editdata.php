<div class="block-fluid">      
	<form action="<?php echo site_url('yms_parameter/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Company Code</b></div>	
		<div class="span1"><input type="text" name="parameter_code" id="parameter_code" value="<?php echo $data['company_code'] ?>"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Company Name</b></div>	
		<div class="span2" style="width:190px"><input type="text" name="parameter_name" id="parameter_name" value="<?php echo $data['company_name'] ?>"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>VAT inclusive</b></div>	
		<div class="span2"><input type="text" name="parameter_vatinclusive" id="parameter_vatinclusive" value="<?php echo number_format($data['vat_inclusive'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Net Rate Return</b></div>	
		<div class="span2"><input type="text" name="parameter_netratereturn" id="parameter_netratereturn" value="<?php echo number_format($data['net_returns_rate'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Insert Rate</b></div>	
		<div class="span2"><input type="text" name="parameter_insertrate" id="parameter_insertrate" value="<?php echo number_format($data['insert_rate'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Ave. Daily Circ.</b></div>	
		<div class="span2"><input type="text" name="parameter_avedailycirc" id="parameter_avedailycirc" value="<?php echo number_format($data['ave_daily_circ'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Fixed Expenses</b></div>	
		<div class="span2"><input type="text" name="parameter_fixedexpenses" id="parameter_fixedexpenses" value="<?php echo number_format($data['fixed_expenses'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save YMS - Parameter button</button></div>		
		<div class="clear"></div>		
	</div>
	</form>
</div>
<script>
$("#parameter_vatinclusive, #parameter_netratereturn, #parameter_insertrate, #parameter_avedailycirc, #parameter_fixedexpenses").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
	var countValidate = 0;  
	var validate_fields = ['#parameter_code', '#parameter_name'];

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
