<div class="block-fluid">      
	<form action="<?php echo site_url('yms_incomeparameter/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Period Covered</b></div>	
		<div class="span1" style="width:80px"><input type="text" placeholder="From" name="periodcoveredfrom" id="periodcoveredfrom" value="<?php echo $data['period_covered_from'] ?>"></div>		
		<div class="span1" style="width:80px"><input type="text" placeholder="To" name="periodcoveredto" id="periodcoveredto" value="<?php echo $data['period_covered_to'] ?>"></div>			
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Ave. Daily Copies</b></div>	
		<div class="span1" style="width:80px"><input type="text" name="avedailycopies" id="avedailycopies" value="<?php echo number_format($data['ave_daily_circ'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Circulation</b></div>		
		<div class="span1" style="width:80px"><b>Manila</b></div>	
		<div class="span1" style="width:80px"><input type="text" name="circmanila" id="circmanila" value="<?php echo number_format($data['circ_manila'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b></b></div>		
		<div class="span1" style="width:80px"><b>Cebu</b></div>	
		<div class="span1" style="width:80px"><input type="text" name="circcebu" id="circcebu" value="<?php echo number_format($data['circ_cebu'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b></b></div>		
		<div class="span1" style="width:80px"><b>Davao</b></div>	
		<div class="span1" style="width:80px"><input type="text" name="circdavao" id="circdavao" value="<?php echo number_format($data['circ_davao'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Net Return Rate</b></div>		
		<div class="span1" style="width:80px"><input type="text" name="netreturnrate" id="netreturnrate" value="<?php echo number_format($data['net_return_rate'], 2, '.', ',') ?>" style="text-align:right"></div>	
		<div class="span1" style="width:80px"><b>PHP</b></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Fixed Expenses</b></div>		
		<div class="span1" style="width:80px"><b>Monthly</b></div>	
		<div class="span1" style="width:80px"><input type="text" name="fixedmonthly" id="fixedmonthly" value="<?php echo number_format($data['fixed_monthly'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b></b></div>		
		<div class="span1" style="width:80px"><b>Daily</b></div>	
		<div class="span1" style="width:80px"><input type="text" name="fixeddaily" id="fixeddaily" value="<?php echo number_format($data['fixed_daily'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Percentage</b></div>		
		<div class="span1" style="width:80px"><b>Circulation</b></div>	
		<div class="span1" style="width:80px"><input type="text" name="percentagecirc" id="percentagecirc" value="<?php echo number_format($data['percentage_circ'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b></b></div>		
		<div class="span1" style="width:80px"><b>Del/Handling</b></div>	
		<div class="span1" style="width:80px"><input type="text" name="percentagedelivery" id="percentagedelivery" value="<?php echo number_format($data['percentage_delivery'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b></b></div>		
		<div class="span1" style="width:80px"><b>Commision</b></div>	
		<div class="span1" style="width:80px"><input type="text" name="percentegecomm" id="percentegecomm" value="<?php echo number_format($data['percentage_comm'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1"><b>Remarks</b></div>				
		<div class="span3" style="width:200px"><input type="text" name="remarks" id="remarks" value="<?php echo $data['remarks'] ?>"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save YMS - Income Parameter button</button></div>		
		<div class="clear"></div>		
	</div>
	</form>
</div>
<script>
$("#avedailycopies, #circmanila, #circcebu, #circdavao, #netreturnrate, #fixedmonthly, #fixeddaily, #percentagecirc, #percentagedelivery, #percentegecomm").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
	var countValidate = 0;  
	var validate_fields = ['#periodcoveredfrom', '#periodcoveredto'];

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
$("#periodcoveredfrom, #periodcoveredto").datepicker({dateFormat: 'yy-mm-dd'});
</script>
