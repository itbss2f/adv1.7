<div class="block-fluid">       
	<div class="row-form-booking">
		<div class="span2" style="width:100px">Payment Type</div>	
		<div class="span2">
		<select name="payment_type" id="payment_type">    
			<option value="CH">Cash</option>
			<option value="CK">Check</option>
			<option value="CC">Credit Card</option>                
			<option value="DD">Direct Deposit</option>                
			<option value="EX">Exdeal</option>
		</select>
		</div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-hidden" id="display_bank" style="display:none">
		<div class="span2" style="width:100px">Bank</div>	
		<div class="span2">
		<select name="payment_bank" id="payment_bank">
			<option value="">--</option>
			<?php foreach($banks as $banks) : ?>
			<option value="<?php echo $banks['id'] ?>"><?php echo $banks['bmf_name'] ?></option>
			<?php endforeach; ?>
		</select>    
		</div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-hidden" id="display_branch" style="display:none">
		<div class="span2" style="width:100px">Branch</div>	
		<div class="span2">
		<select name="payment_branch" id="payment_branch">
			<option value="">--</option>
		</select>
		</div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-hidden" id="display_checkno" style="display:none">
		<div class="span2" style="width:100px">Check No.</div>	
		<div class="span2"><input type="text" id="check_no" name="check_no"/></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-hidden" id="display_checkdate" style="display:none">
		<div class="span2" style="width:100px">Check Date</div>	
		<div class="span2"><input autocomplete="off" type="text" id="check_date" name="check_date"/></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-hidden" id="display_creditcard" style="display:none">
		<div class="span2" style="width:100px">Credit Card</div>	
		<div class="span2">
			<select name="credit_card" id="credit_card">
			<option value="">--</option>
			<?php foreach($creditcard as $creditcard) : ?>                   
			<option value="<?php echo $creditcard['id'] ?>"><?php echo $creditcard['creditcard_name'] ?></option>
			<?php endforeach; ?>    
			</select>    
		</div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-hidden" id="display_creditcardno" style="display:none">
		<div class="span2" style="width:100px">Credit Card No.</div>	
		<div class="span2"><input type="text" id="credit_card_no" name="credit_card_no"/></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-hidden" id="display_authorizationo" style="display:none">
		<div class="span2" style="width:100px">Authorization No.</div>	
		<div class="span2"><input type="text" id="authorization_no" name="authorization_no"/></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-hidden" id="display_expirydate" style="display:none">
		<div class="span2" style="width:100px">Expiry Date</div>	
		<div class="span2"><input type="text" id="expiry_date" name="expiry_date"/></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-hidden" id="display_remarks" style="display:none">
		<div class="span2" style="width:100px">Remarks</div>	
		<div class="span2"><input type="text" id="remarks" name="remarks"/></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:100px">Amount</div>	
		<div class="span2"><input type="text" style="text-align:right" id="payment_amount" name="payment_amount"/></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span3" style="margin-left:15%;">
            <button class="btn btn-block" type="button" id="btn_addpayment" name="btn_addpayment">Add Payment</button>
        	</div> 
		<div class="clear"></div>
	</div>
</div>
<script>
$("#check_date").datepicker({dateFormat: 'yy-mm-dd', maxDate: "5D"});
$("#expiry_date").datepicker({dateFormat: 'yy-mm-dd'});
var validate_fields = ['#payment_type', '#payment_amount'];
$("#btn_addpayment").click(function(){
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
		var $payment_type = $("#payment_type").val(); 			
		var $payment_bank = $("#payment_bank").val();
		var $payment_branch = $("#payment_branch").val();
		var $check_no = $("#check_no").val();
		var $check_date = $("#check_date").val();
		var $credit_card = $("#credit_card").val();
		var $credit_card_no = $("#credit_card_no").val();
		var $authorization_no = $("#authorization_no").val();
		var $expiry_date = $("#expiry_date").val();
		var $remarks = $("#remarks").val();
		var $payment_amount = $("#payment_amount").val();
        var $ortype = $("#ortype").val();  

		$.ajax({
			url: "<?php echo site_url('payment/saveTempORPayment') ?>",
			type: "post",
			data: {
				 mykeyid: "<?php echo $mykeyid ?>",
				 payment_type: $payment_type,
				 payment_bank: $payment_bank,
				 payment_branch: $payment_branch,
				 check_no: $check_no,
				 check_date: $check_date,
				 credit_card: $credit_card,
				 credit_card_no: $credit_card_no,
				 authorization_no: $authorization_no,
				 expiry_date: $expiry_date,
				 payment_amount: $payment_amount,
				 remarks: $remarks,
                 ortype: $ortype
                     },
			success:function(response) {
				$response = $.parseJSON(response); 
                
                if ($response['msg'] == 1) {
                    alert('Delete previous payment type applied first!.');
                } else {
                    if ($payment_type == 'EX') {
                        alert('This is an exdeal payment!. Adding payment type will be disabled.');
                        $('#add_paymenttype').hide();
                        $('#bank').val(''); 
                        $('#exdeal_note').val($response['exdealnote']);   
                    }                    
                }
                
				$(".paymenttype_list").html($response['prpayments_list']);
                $("#amountpaid").val($response['amountpaid']);
                
                if ($ortype == 2) {
                $("#assignedamount").val($response['assamountpaid']);                      
                }

				recomputePercent();
                
				$('#paymenttype_view').dialog('close');	
                
                
			}
		});
	} else {			
		return false;
	}   
});
$("#payment_type").change(function(){
    //var $ordate2 = $('#ordate2').val();     
    //var $ordate = $('#ordate').val();     
	var $payment_type = $("#payment_type").val();
	$(".row-form-booking-hidden").hide();	
	$("#payment_bank").val("");	
	$("#payment_branch").empty();$("#payment_branch").append("<option value=''>--</option>");        
	$("#check_no").val("");	
	$("#check_date").val("");
	$("#credit_card").val("");
	$("#credit_card_no").val("");
	$("#authorization_no").val("");
	$("#expiry_date").val("");
	$("#remarks").val("");
	if ($payment_type == "CK") {
        //$("#check_date").datepicker({dateFormat: 'yy-mm-dd', minDate: $ordate, maxDate: '2Y' });          
        //$("#check_date").datepicker({dateFormat: 'yy-mm-dd', minDate: $ordate2, maxDate: '2Y' });          
		$("#display_bank").show();	
		$("#display_branch").show();	
		$("#display_checkno").show();	
		$("#display_checkdate").show();
		validate_fields = ['#payment_type', '#payment_amount', '#payment_bank', '#payment_branch', '#check_no', '#check_date'];
	} else if ($payment_type == "CC") {
        $("#display_creditcard").show();    
		$("#display_creditcard").show();	
		$("#display_creditcardno").show();	
		$("#display_authorizationo").show();	
		$("#display_expirydate").show();
		validate_fields = ['#payment_type', '#payment_amount', '#credit_card', '#credit_card_no' , '#authorization_no', '#expiry_date'];
	} else if ($payment_type == "EX") {
		$("#display_remarks").show();
		validate_fields = ['#payment_type', '#payment_amount', '#remarks'];
	} else {
		validate_fields = ['#payment_type', '#payment_amount'];
	}
});
$("#payment_amount").autoNumeric();
$("#payment_bank").change(function(){
    $.ajax({
        url: "<?php echo site_url('payment/ajxGetBranch') ?>",
        type: 'post',
        data: {bank: $(':input[name=payment_bank]').val()},
        success: function(response){
        
            var $response = $.parseJSON(response);
            $('#payment_branch').empty();    
            if ($response['branch'] == "") {
                $('#payment_branch').append("<option value=''>--</option>");    
            } else {
                $.each($response['branch'], function(i)
                {
                    var item = $response['branch'][i];
                    var option = $('<option>').val(item['id']).text(item['bbf_bnch']);
                    $('#payment_branch').append(option);                            
                });    
            }
        }
    });
});   
</script>
