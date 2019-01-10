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
		<div class="span1" style="font-size: 15px; color: red;"><b><?php echo number_format($remainoramt, 2, '.', ',') ?></b></div>    
        <div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1">Applied</div>	
		<div class="span1" style="width:80px"><input type="text" name="a_appliedamt" id="a_appliedamt" value="<?php echo number_format($default_appamount, 2, '.', ','); ?>" style="text-align:right"></div>
		<div class="span1">W/TAX</div>	
		<div class="span1" style="width:80px"><input type="text" name="a_wtax" id="a_wtax" value="<?php echo number_format($default_wtax, 2, '.', ','); ?>" style="text-align:right"></div>
		<div class="span1" style="width:50px">W/TAX%</div>	
		<div class="span1" style="width:80px"><input type="text" name="a_wtaxp" id="a_wtaxp" value="<?php echo $wtaxpercent ?>" style="text-align:right"></div>		
		<div class="clear"></div>	
	</div> 
	<div class="row-form-booking">
		<div class="span1" style="width:180px"><button class="btn btn-block btn-success" type="button" id="btn_applied" name="btn_applied">Applied</button></div>	
		<div class="span1">W/VAT</div>	
		<div class="span1" style="width:80px"><input type="text" name="a_wvat" id="a_wvat" value="<?php echo number_format($default_wvat, 2, '.', ','); ?>" style="text-align:right"></div>
		<div class="span1" style="width:50px">W/VAT%</div>	
		<div class="span1" style="width:80px"><input type="text" name="a_wvatp" id="a_wvatp" value="<?php echo $wvatpercent ?>" style="text-align:right"></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">	
		<div class="span1" style="width:180px"></div>	
		<div class="span1">PPD</div>	
		<div class="span1" style="width:80px"><input type="text" name="a_ppd" id="a_ppd" value="<?php echo number_format($default_ppd, 2, '.', ','); ?>" style="text-align:right"></div>
		<div class="span1" style="width:50px">PPD%</div>	
		<div class="span1" style="width:80px"><input type="text" name="a_ppdp" id="a_ppdp" value="<?php echo $ppdpercent ?>" style="text-align:right"></div>
		<div class="clear"></div>	
	</div>  
</div>
<script>
$("#a_appliedamt , #a_wtaxp, #a_wvatp, #a_ppdp").keyup(function(){
	applied_recomputePercent();	
});

function applied_recomputePercent()
{
	var $amountpaid = $("#a_appliedamt").val();
	var $vatcode = $("#vatcode").val();
	var $wvatpercent = $("#a_wvatp").val();
	var $wtaxpercent = $("#a_wtaxp").val();
	var $ppdpercent = $("#a_ppdp").val();
	$.ajax({
		url: "<?php echo site_url('prpayment/getRecomputeValuePercent') ?>",
		type: "post",
		data: {amountpaid: $amountpaid, vatcode: $vatcode, wvatpercent: $wvatpercent, wtaxpercent: $wtaxpercent, ppdpercent: $ppdpercent},
		success: function(response) {
			$response = $.parseJSON(response);
			$("#a_wvat").val($response['wvatamount']);		
			$("#a_wtax").val($response['wtaxamount']);
			$("#a_ppd").val($response['ppdamount']);			
		}
	});
}

var validate_fields = ['#a_appliedamt'];
$("#btn_applied").click(function(){
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
		var $a_appliedamt = $("#a_appliedamt").val(); 			
		var $a_wvat = $("#a_wvat").val();
		var $a_wvatp = $("#a_wvatp").val();
		var $a_wtax = $("#a_wtax").val();
		var $a_wtaxp = $("#a_wtaxp").val();
		var $a_ppd = $("#a_ppd").val();
		var $a_ppdp = $("#a_ppdp").val();
        
		var $totalpayable = "<?php echo $data['amountdue'] - $data['prtotalamt'] ?>"; 
		var $xx = $a_appliedamt.replace(',','');
        
        var $ramt = "<?php echo $remainoramt ?>";
        
        if (parseFloat($xx) > parseFloat($ramt)) {
            alert("Applied amount must not be greather than Remaining OR Amount available "+$ramt); 

            return false;
        }
        
		if (parseFloat($xx) > parseFloat($totalpayable)) {
			alert("Applied amount must not be greather than Total Payable Amount available "+$totalpayable); 

			return false;
		}
        
        var $doctype = "<?php echo $doctype ?>";   
		$.ajax({
			url: "<?php echo site_url('prpayment/saveTempPRPaymentApplied') ?>",
			type: "post",
			data: {
				 id: "<?php echo $id ?>",
				 mykeyid: "<?php echo $mykeyid ?>",
		         type: "<?php echo $type ?>",
				 code: "<?php echo $code ?>",
				 choose: "<?php echo $choose ?>",
                 doctype: $doctype, 
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
				$('#paymentapplied_view').dialog('close');	
			}
		});
	} else {			
		return false;
	}   
});
$("#a_appliedamt, #a_wvat, #a_wvatp, #a_wtax, #a_wtaxp, #a_ppd, #a_ppdp").autoNumeric();
</script>
