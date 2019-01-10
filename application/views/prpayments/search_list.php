<?php
foreach ($search_list as $x => $cmf) :  ?>
	<tr>						
	<td colspan="5" style="background-color: #73A4D9;color:#FFFFFF;font-size:13px;max-width:180px;white-space:nowrap;overflow:hidden;-width:100%;" title="<?php echo $x; ?>"><?php echo $x; ?></td>    
	</tr>	 	
	<?php
	foreach($cmf as $row) :
	?>
	<tr>						
	<td width="40px"><?php echo $row['prod_code'] ?></td>
	<td width="40px"><?php echo $row['ao_issuefrom'] ?></td>
	<td width="40px"><?php echo $row['ao_type'] ?></td>                                    
	<td width="40px"><?php echo $row['ao_sinum'] ?></td>       
	<td width="20px"><a href="#<?php echo $row['id'] ?>" class="payment_applied" id="<?php echo $row['id'] ?>"><span class="icon-star"></span></a></td>						 
	</tr>
	<?php

	endforeach;
endforeach;
?>
<script>
$(".payment_applied").click(function() {
	var $id = $(this).attr('id');
    var $doctype = "SI";
	var $amountpaid = $("#amountpaid").val();
    var $assoramt = $("#assignedamount").val();
    var $vatcode = $("#vatcode").val();
    
    var $amountpaidxx = $amountpaid.replace(/\,/g,'');
    var $assoramtxx = $assoramt.replace(/\,/g,'');
 
    if (parseFloat($assoramtxx) >= parseFloat($amountpaidxx)) {
        alert("No more remaining assignment amount!"); 

        return false;
    }
     
    
	if ($amountpaid == "0.00") {
		alert("Amount paid must not equal to zero!"); $("#vatcode").val(''); return false;
	} else {
		$.ajax({
			url: "<?php echo site_url('prpayment/appliedpaymentview') ?>",
			type: "post",
			data: {id: $id, mykeyid: "<?php echo $mykeyid ?>", code: "<?php echo $code ?>", type: "<?php echo $type ?>", choose: "<?php echo $choose ?>",
				  wvatpercent: $("#wvatpercent").val(), wtaxpercent: $("#wtaxpercent").val(), ppdpercent: $("#ppdpercent").val(),
                  doctype: $doctype, oramt: $amountpaid, assoramt: $assoramt, vatcode: $vatcode                   
		           },
			success: function(response) {
				var $response = $.parseJSON(response);
				$('#paymentapplied_view').html($response['paymentapplied_view']).dialog('open');
			}
		});
	}	
});
</script>
