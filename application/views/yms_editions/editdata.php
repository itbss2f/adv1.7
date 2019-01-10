<div class="block-fluid">      
	<form action="<?php echo site_url('yms_edition/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Edition Code</b></div>	
		<div class="span1"><input type="text" name="edition_code" id="edition_code" value="<?php echo $data['code'] ?>" readonly="readonly"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Edition Name</b></div>	
		<div class="span2" style="width:190px"><input type="text" name="edition_name" id="edition_name" value="<?php echo $data['name'] ?>"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Total CCM</b></div>	
		<div class="span1"><input type="text" name="edition_totalccm" id="edition_totalccm" value="<?php echo number_format($data['total_ccm'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Type</b></div>	
		<div class="span2" style="width:190px">
			<select name="edition_type" id="edition_type">
				<option value="D" <?php if ($data['type'] == 'D') { echo "selected='selected'";} ?> >Display</option>
				<option value="C" <?php if ($data['type'] == 'C') { echo "selected='selected'";} ?> >Classifieds</option>
				<option value="B" <?php if ($data['type'] == 'B') { echo "selected='selected'";} ?> >Display/Classifieds</option>
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
$("#product_totalccm").autoNumeric();
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
		$('#formsave').submit();
	} else {			
		return false;
	}	
});
</script>
