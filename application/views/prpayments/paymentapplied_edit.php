<div class="block-fluid">       
	<div class="row-form-booking">
		<div class="span1">Invoice No.</div>	
		<div class="span1" style="width:80px"><input type="text" value="<?php echo $data['ao_sinum'] ?>" readonly="readonly"></div>
		<div class="span1">Invoice Date</div>	
		<div class="span1" style="width:80px"><input type="text" value="<?php echo $data['ao_sidate'] ?>" readonly="readonly"></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1">Rundate</div>	
		<div class="span1" style="width:80px"><input type="text" value="<?php echo $data['ao_issuefrom'] ?>" readonly="readonly"></div>
		<div class="span1">Description</div>	
		<div class="span3"><input type="text" value="<?php echo $data['ao_part_billing'] ?>" readonly="readonly"></div>
		<div class="clear"></div>	
	</div>   
	<div class="row-form-booking">
		<div class="span1">Invoice Amt</div>	
		<div class="span1" style="width:80px"><input type="text" style="text-align:right" value="<?php echo number_format($data['amt'], 2, ".", ","); ?>" readonly="readonly"></div>
		<div class="span1">Amount Due</div>	
		<div class="span1" style="width:80px"><input type="text" style="text-align:right" value="<?php echo number_format($data['amountdue'] - $data['prtotalamt'], 2, ".", ","); ?>" readonly="readonly"></div>
		<div class="span1"><p class="text-info" style="height:18px;font-size:20px"><?php echo number_format($data['amountdue'] + $data['applied_amt'], 2, ".", ","); ?></p></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1">Applied</div>	
		<div class="span1" style="width:80px"><input type="text" name="u_appliedamt" id="u_appliedamt" value="<?php echo  number_format($data['applied_amt'], 2, '.', ',') ?>" style="text-align:right"></div>
		<div class="span1">W/TAX</div>	
		<div class="span1" style="width:80px"><input type="text" name="u_wtax" id="u_wtax" value="<?php echo  number_format($data['a_wtax'], 2, '.', ',') ?>" style="text-align:right"></div>
		<div class="span1" style="width:50px">W/TAX%</div>	
		<div class="span1" style="width:80px"><input type="text" name="u_wtaxp" id="u_wtaxp" value="<?php echo  number_format($data['a_wtaxp'], 2, '.', ',') ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div> 
	<div class="row-form-booking">
		<div class="span1" style="width:180px"><button class="btn btn-block btn-success" type="button" id="btn_updateapplied" name="btn_updateapplied">Update Applied</button></div>	
		<div class="span1">W/VAT</div>	
		<div class="span1" style="width:80px"><input type="text" name="u_wvat" id="u_wvat" value="<?php echo  number_format($data['a_wvat'], 2, '.', ',') ?>" style="text-align:right"></div>
		<div class="span1" style="width:50px">W/VAT%</div>	
		<div class="span1" style="width:80px"><input type="text" name="u_wvatp" id="u_wvatp" value="<?php echo  number_format($data['a_wvatp'], 2, '.', ',') ?>" style="text-align:right"></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">	
		<div class="span1" style="width:180px"><button class="btn btn-block btn-danger" type="button" id="btn_removeapplied" name="btn_removeapplied">Remove Applied</button></div>		
		<div class="span1">PPD</div>	
		<div class="span1" style="width:80px"><input type="text" name="u_ppd" id="u_ppd" value="<?php echo  number_format($data['a_ppd'], 2, '.', ',') ?>" style="text-align:right"></div>
		<div class="span1" style="width:50px">PPD%</div>	
		<div class="span1" style="width:80px"><input type="text" name="u_ppdp" id="u_ppdp" value="<?php echo  number_format($data['a_ppdp'], 2, '.', ',') ?>" style="text-align:right"></div>
		<div class="clear"></div>	
	</div>  
</div>
<script>
$("#u_appliedamt, #u_wvatp, #u_wtaxp, #u_ppdp").keyup(function(){
	updateapplied_recomputePercent();	
});

function updateapplied_recomputePercent()
{
	var $amountpaid = $("#u_appliedamt").val();
	var $vatcode = $("#vatcode").val();
	var $wvatpercent = $("#u_wvatp").val();
	var $wtaxpercent = $("#u_wtaxp").val();
	var $ppdpercent = $("#u_ppdp").val();

	var $totalpayable = "<?php echo $data['amountdue'] + $data['applied_amt'] ?>"; 
	var $xx = $amountpaid.replace(',','');
	if (parseFloat($xx) > parseFloat($totalpayable)) {
		alert("Applied amount must not be greather than Total Payable Amount available "+$totalpayable); 

		return false;
	}

	$.ajax({
		url: "<?php echo site_url('prpayment/getRecomputeValuePercent') ?>",
		type: "post",
		data: {amountpaid: $amountpaid, vatcode: $vatcode, wvatpercent: $wvatpercent, wtaxpercent: $wtaxpercent, ppdpercent: $ppdpercent},
		success: function(response) {
			$response = $.parseJSON(response);
			$("#u_wvat").val($response['wvatamount']);		
			$("#u_wtax").val($response['wtaxamount']);
			$("#u_ppd").val($response['ppdamount']);			
		}
	});
}
var validate_fields = ['#a_appliedamt'];
$("#btn_removeapplied").click(function() {
	var $id = "<?php echo $id ?>";
	var $confirm = confirm("Are you sure you want to remove this applied pr payment?");

	if ($confirm) {
		$.ajax({
			url: "<?php echo site_url('prpayment/removeTempPRPaymentApplied') ?>",
			type: "post",
			data: {id: $id,
                     mykeyid: "<?php echo $mykeyid ?>",
		           type: "<?php echo $type ?>",
				 code: "<?php echo $code ?>",
				 choose: "<?php echo $choose ?>" 
				 },
			success:function(response) {
				$response = $.parseJSON(response); 
				$('.search_list').html($response['search_list']);
				$('#paymentapplied_list').html($response['applied_list']);
				
				$('#wvatassign').val($response['summaryassign']['totalwvat']);
				$('#wtaxassign').val($response['summaryassign']['totalwtax']);
				$('#ppdassign').val($response['summaryassign']['totalppd']);
				$('#assignedamount').val($response['summaryassign']['totalappliedamt']);

				$('#updatepaymentapplied_view').dialog('close');	
			}
		});
	}
});
$("#btn_updateapplied").click(function(){
	var countValidate = 0;  
	for (x = 0; x < validate_fields.length; x++) {			
		if($(validate_fields[x]).val() == "") {                        
			$(validate_fields[x]).css(errorcssobj);          
		  	countValidate += 1;
		} else {        
		  	$(validate_fields[x]).css(errorcssobj2);       
		}        
	}  
	if (countValidate == 0) {
		var $a_appliedamt = $("#u_appliedamt").val(); 			
		var $a_wvat = $("#u_wvat").val();
		var $a_wvatp = $("#u_wvatp").val();
		var $a_wtax = $("#u_wtax").val();
		var $a_wtaxp = $("#u_wtaxp").val();
		var $a_ppd = $("#u_ppd").val();
		var $a_ppdp = $("#u_ppdp").val();

		$.ajax({
			url: "<?php echo site_url('prpayment/updateTempPRPaymentApplied') ?>",
			type: "post",
			data: {
				 id: "<?php echo $id ?>",
				 mykeyid: "<?php echo $mykeyid ?>",
		           type: "<?php echo $type ?>",
				 code: "<?php echo $code ?>",
				 choose: "<?php echo $choose ?>",
				 a_appliedamt: $a_appliedamt,
				 a_wvat: $a_wvat,
				 a_wvatp: $a_wvatp,
				 a_wtax: $a_wtax,
				 a_wtaxp: $a_wtaxp,
				 a_ppd: $a_ppd,
				 a_ppdp: $a_ppdp	
		           },
			success:function(response) {
				$response = $.parseJSON(response); 
				$('.search_list').html($response['search_list']);
				$('#paymentapplied_list').html($response['applied_list']);
				
				$('#wvatassign').val($response['summaryassign']['totalwvat']);
				$('#wtaxassign').val($response['summaryassign']['totalwtax']);
				$('#ppdassign').val($response['summaryassign']['totalppd']);
				$('#assignedamount').val($response['summaryassign']['totalappliedamt']);

				$('#updatepaymentapplied_view').dialog('close');	
			}
		});
	} else {			
		return false;
	}   
});
$("#u_appliedamt, #u_wvat, #u_wvatp, #u_wtax, #u_wtaxp, #u_ppd, #u_ppdp").autoNumeric();
</script>
