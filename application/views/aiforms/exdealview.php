<div class="block-fluid">    
	<div class="row-form-booking">
		<div class="span1" style="margin-left:0px;width:100px"><b><input type="checkbox" <?php if ($data['ao_exdealstatus'] == 1) { echo "checked='checked'";} ?> name="exdealstatus" id="exdealstatus" value="1"> Exdeal</b></div>	
		<div class="span1" style="width:100px"><input type="text" placeholder="Amount" value="<?php echo number_format($data['ao_exdealamt'], 2, '.', ',') ?>" class="number_field" name="exdealamount" id="exdealamount" style="text-align:right"></div>		
		<div class="span1" style="width:50px"><input type="text" placeholder="%" value="<?php echo number_format($data['ao_exdealpercent'], 2, '.', ',') ?>" class="number_field" name="exdealpercent" id="exdealpercent" style="text-align:right"></div>	
		<div class="span2" style="width:250px"><input type="text" placeholder="Remarks" value="<?php echo $data['ao_exdealpart'] ?>" name="exdealrem" id="exdealrem"></div>	
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1" style="margin-left:0px;width:100px"><b><input type="checkbox" <?php if ($data['ao_wtaxstatus'] == 1) { echo "checked='checked'";} ?> name="wtaxstatus" id="wtaxstatus" value="1"> W/Tax</b></div>	
		<div class="span1" style="width:100px"><input type="text" placeholder="Amount" value="<?php echo number_format($data['ao_wtaxamt'], 2, '.', ',') ?>" class="number_field" name="wtaxamount" id="wtaxamount" style="text-align:right"></div>		
		<div class="span1" style="width:50px"><input type="text" placeholder="%" value="<?php echo number_format($data['ao_wtaxpercent'], 2, '.', ',') ?>" class="number_field" name="wtaxpercent" id="wtaxpercent" style="text-align:right"></div>	
		<div class="span2" style="width:250px"><input type="text" placeholder="Remarks" value="<?php echo $data['ao_wtaxpart'] ?>" name="wtaxrem" id="wtaxrem"></div>	
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1" style="margin-left:0px;width:100px"><b><input type="checkbox" <?php if ($data['ao_ploughbackstatus'] == 1) { echo "checked='checked'";} ?>  name="ploughbackstatus" id="ploughbackstatus" value="1"> Ploughback</b></div>	
		<div class="span1" style="width:100px"><input type="text" placeholder="Amount" value="<?php echo number_format($data['ao_ploughbackamt'], 2, '.', ',') ?>" class="number_field" name="ploughbackamount" id="ploughbackamount" style="text-align:right"></div>		
		<div class="span1" style="width:50px"><input type="text" placeholder="%" value="<?php echo number_format($data['ao_ploughbackpercent'], 2, '.', ',') ?>" class="number_field" name="ploughbackpercent" id="ploughbackpercent" style="text-align:right"></div>	
		<div class="span2" style="width:250px"><input type="text" placeholder="Remarks" value="<?php echo $data['ao_ploughbackpart'] ?>" name="ploughbackrem" id="ploughbackrem"></div>	
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1" style="margin-left:0px;width:100px"><b><input type="checkbox" <?php if ($data['ao_otherstatus'] == 1) { echo "checked='checked'";} ?> name="otherstatus" id="otherstatus" value="1"> Other</b></div>	
		<div class="span1" style="width:100px"><input type="text" placeholder="Amount" value="<?php echo number_format($data['ao_otheramt'], 2, '.', ',') ?>" class="number_field" name="otheramount" id="otheramount" style="text-align:right"></div>		
		<div class="span1" style="width:50px"><input type="text" placeholder="%" value="<?php echo number_format($data['ao_otherpercent'], 2, '.', ',') ?>" class="number_field" name="otherpercent" id="otherpercent" style="text-align:right"></div>	
		<div class="span2" style="width:250px"><input type="text" placeholder="Remarks" value="<?php echo $data['ao_otherpart'] ?>" name="otherrem" id="otherrem"></div>	
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1" style="margin-left:0px;width:100px"><b><input type="checkbox" <?php if ($data['ao_writeoffstatus'] == 1) { echo "checked='checked'";} ?> name="writeoffstatus" id="writeoffstatus" value="1"> Write-Off</b></div>	
		<div class="span1" style="width:100px"><input type="text" placeholder="Amount" value="<?php echo number_format($data['ao_writeoffamt'], 2, '.', ',') ?>" class="number_field" name="writeoffamount" id="writeoffamount" style="text-align:right"></div>		
		<div class="span1" style="width:50px"><input type="text" placeholder="%" value="<?php echo number_format($data['ao_writeoffpercent'], 2, '.', ',') ?>" class="number_field" name="writeoffpercent" id="writeoffpercent" style="text-align:right"></div>	
		<div class="span2" style="width:250px"><input type="text" placeholder="Remarks" value="<?php echo $data['ao_writeoffpart'] ?>" name="writeoffrem" id="writeoffrem"></div>	
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2"><button class="btn btn-success" type="button" name="save" id="save">Save Exdeal button</button></div>		
		<div class="clear"></div>		
	</div>
</div>

<script>
$(".number_field").autoNumeric({});

var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
	var countValidate = 0;  
	var validate_fields = [];

	var exdealstatus = $('#exdealstatus').is(':checked'); 
	if($('#exdealstatus').is(':checked')){
		validate_fields.push('#exdealamount', '#exdealpercent', '#exdealrem');
	} else { $('#exdealamount, #exdealpercent, #exdealrem').css(errorcssobj2).val('');      } 
	if($('#wtaxstatus').is(':checked')){
		validate_fields.push('#wtaxamount', '#wtaxpercent', '#wtaxrem');
	} else { $('#wtaxamount, #wtaxpercent, #wtaxrem').css(errorcssobj2).val('');      }  
	if($('#ploughbackstatus').is(':checked')){
		validate_fields.push('#ploughbackamount', '#ploughbackpercent', '#ploughbackrem');
	} else { $('#ploughbackamount, #ploughbackpercent, #ploughbackrem').css(errorcssobj2).val('');      }  
	if($('#otherstatus').is(':checked')){
		validate_fields.push('#otheramount', '#otherpercent', '#otherrem');
	} else { $('#otheramount, #otherpercent, #otherrem').css(errorcssobj2).val('');      }  
	if($('#writeoffstatus').is(':checked')){
		validate_fields.push('#writeoffamount', '#writeoffpercent', '#writeoffrem');
	} else { $('#writeoffamount, #writeoffpercent, #writeoffrem').css(errorcssobj2).val('');      }  
	
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
			url: "<?php echo site_url('aiform/saveexdealtag') ?>",
			type: "post",
			data: {	id: "<?php echo $data['id'] ?>",
					exdealstatus: $("#exdealstatus:checked").val(),
					exdealamount: $("#exdealamount").val(),
					exdealpercent: $("#exdealpercent").val(),
					exdealrem: $("#exdealrem").val(),
					wtaxstatus: $("#wtaxstatus:checked").val(),
					wtaxamount: $("#wtaxamount").val(),
					wtaxpercent: $("#wtaxpercent").val(),
					wtaxrem: $("#wtaxrem").val(),
					ploughbackstatus: $("#ploughbackstatus:checked").val(),
					ploughbackamount: $("#ploughbackamount").val(),
					ploughbackpercent: $("#ploughbackpercent").val(),
					ploughbackrem: $("#ploughbackrem").val(),
					otherstatus: $("#otherstatus:checked").val(),
					otheramount: $("#otheramount").val(),
					otherpercent: $("#otherpercent").val(),
					otherrem: $("#otherrem").val(),
					writeoffstatus: $("#writeoffstatus:checked").val(),
					writeoffamount: $("#writeoffamount").val(),
					writeoffpercent: $("#writeoffpercent").val(),
					writeoffrem: $("#writeoffrem").val()
                     },
			success: function(response) {
				alert('Successfully update!');
			}
		});
	} else {			
		return false;
	}	
});
</script>
	
