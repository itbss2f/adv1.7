<div class="block-fluid">       
	<div class="row-form-booking">
		<div class="span1">AO Number</div>	
		<div class="span1"><input type="text" name="revenue_aono" id="revenue_aono" style="text-align:right"></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="margin-left:15%;">
            <button class="btn btn-block" type="button" id="btn_loadrevenue" name="btn_loadrevenue">Load Booking</button>
        	</div> 
		<div class="clear"></div>
	</div>
</div>

<script>
$("#btn_loadrevenue").click(function(){
	var $aonum = $("#revenue_aono").val();
	$.ajax({
		url: "<?php echo site_url('prpayment/loadrevenue') ?>",
		type: "post",
		data: {aonum: $aonum},
		success:function(response) {
			$response = $.parseJSON(response);			
			if ($response['valid']) {
				$("#payeecode").val("REVENUE").attr("readonly", "readonly");
				$("#payeename").val($response['data']['ao_payee']);
				$("#tin").val($response['data']['ao_tin']);
				$("#zipcode").val($response['data']['ao_zip']);
				$("#address1").val($response['data']['ao_add1']);
				$("#address2").val($response['data']['ao_add2']);    
				$("#address3").val($response['data']['ao_add3']);    
				$("#tel1prefix").val($response['data']['ao_telprefix1']);
				$("#tel2prefix").val($response['data']['ao_telprefix2']);    
				$("#tel1").val($response['data']['ao_tel1']);        
				$("#tel2").val($response['data']['ao_tel2']);        
				$("#celprefix").val($response['data']['ao_celprefix']);                
				$("#cel").val($response['data']['ao_cel']);
				$("#faxprefix").val($response['data']['ao_faxprefix']);
				$("#fax").val($response['data']['ao_fax']);    
				$("#revenue_view").dialog('close');
			} else {
				alert("Booking AO Number data not valid!");
			}
		}
	});
});
</script>
