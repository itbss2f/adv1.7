<div class="block-fluid">    
	<form action="<?php echo site_url('yms_rates/save') ?>" method="post" name="formsave" id="formsave"> 
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Edition</b></div>	
		<div class="span2" style="width:190px">
			<select name="edition" id="edition">
			<?php foreach ($ymsedition as $ymsedition) : ?>
			<option value="<?php echo $ymsedition['id'] ?>"><?php echo $ymsedition['code'].' - '.$ymsedition['name'] ?></option>
			<?php endforeach; ?>
			</select>
		</div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Printing Press</b></div>	
		<div class="span2" style="width:190px">
			<select name="printingpress" id="printingpress">
			<?php foreach ($printingpress as $printingpress) : ?>
			<option value="<?php echo $printingpress['id'] ?>"><?php echo $printingpress['code'].' - '.$printingpress['name'] ?></option>
			<?php endforeach; ?>
			</select>
		</div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Period Covered</b></div>	
		<div class="span1" style="width:80px"><input type="text" placeholder="From" name="periodcoveredfrom" id="periodcoveredfrom"></div>		
		<div class="span1" style="width:80px"><input type="text" placeholder="To" name="periodcoveredto" id="periodcoveredto"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Circulation Copies</b></div>	
		<div class="span1" style="width:80px"><input type="text" name="circulationcopies" id="circulationcopies" value="0.00" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Newsprint Cost Rate</b></div>	
		<div class="span1" style="width:80px"><input type="text" name="newsprintcostrate" id="newsprintcostrate" value="0.00" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Printing Cost Rate</b></div>	
		<div class="span1" style="width:80px">Black / White</div>
		<div class="span1" style="width:80px"><input type="text" name="bw" id="bw" value="0.00" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"></div>	
		<div class="span1" style="width:80px">Spot 1</div>
		<div class="span1" style="width:80px"><input type="text" name="spot1" id="spot1" value="0.00" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"></div>	
		<div class="span1" style="width:80px">Spot 2</div>
		<div class="span1" style="width:80px"><input type="text" name="spot2" id="spot2" value="0.00" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"></div>	
		<div class="span1" style="width:80px">Full Color</div>
		<div class="span1" style="width:80px"><input type="text" name="fullcolor" id="fullcolor" value="0.00" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"></div>	
		<div class="span1" style="width:80px">Discount %</div>
		<div class="span1" style="width:80px"><input type="text" name="discount" id="discount" value="0.00" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Pre-Press</b></div>	
		<div class="span1" style="width:80px">Charge</div>
		<div class="span1" style="width:80px"><input type="text" name="prepresscharge" id="prepresscharge" value="0.00" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"></div>	
		<div class="span1" style="width:80px">Discount %</div>
		<div class="span1" style="width:80px"><input type="text" name="prepressdiscount" id="prepressdiscount" value="0.00" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Delivery/Handling</b></div>	
		<div class="span1" style="width:80px">Cost per copy</div>
		<div class="span1" style="width:80px"><input type="text" name="deliverycostcopy" id="deliverycostcopy" value="0.00" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"></div>	
		<div class="span1" style="width:80px">Cost per issue</div>
		<div class="span1" style="width:80px"><input type="text" name="deliverycostissue" id="deliverycostissue" value="0.00" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1" style="width:120px"><b>Remarks</b></div>	
		<div class="span2" style="width:190px"><input type="text" name="remarks" id="remarks"></div>		
		<div class="clear"></div>	
	</div>
	</form>
	<div class="row-form-booking">
		<div class="span1" style="width:120px"><button class="btn btn-success" type="button" name="save" id="save">Save Rate button</button></div>		
		<div class="clear"></div>		
	</div>
</div>

<script>
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
	var countValidate = 0;  
	var validate_fields = ['#edition', '#printingpress', '#periodcoveredfrom', '#periodcoveredto', '#circulationcopies', '#newsprintcostrate'];

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
		/*$.ajax({
			url: "<?php echo site_url('yms_rates/validateCode') ?>",
			type: "post",
			data: {edition : $("#edition").val(), printingpress: $("#printingpress").val()},
			success: function(response) {
				if (response == "true") {                    
		               alert("Edition and Printing Press must be unique!.");
					return false;		               
		           } else {
		               $('#formsave').submit();
		           }
			}
		});		*/
	} else {			
		return false;
	}	
});
$("#circulationcopies").autoNumeric();
$("#periodcoveredfrom, #periodcoveredto").datepicker({dateFormat: 'yy-mm-dd'});
</script>
