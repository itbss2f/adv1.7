<?php foreach($prpaymentlist as $list) : ?>
<tr style="height:10px" class="data_tbody">
    <?php if ($stat != 'O') : ?>
	<td width="20px"><a href="#<?php echo $list['id'] ?>" class="paymenttype_remove" id="<?php echo $list['id'] ?>"><span class="icon-remove"></span></a></td>						 
    <?php else : ?>
    <td width="20px"></td>
    <?php endif; ?>
	<td width="40px"><?php echo $list['type'] ?></td>
	<td width="40px"><?php echo $list['bank'] ?></td>
	<td width="40px"><?php echo $list['bankbranch'] ?></td>
	<td width="40px"><?php echo $list['checknumber'] ?></td>
	<td width="40px"><?php echo $list['checkdate'] ?></td>
	<td width="40px"><?php echo $list['creditcard'] ?></td>
	<td width="40px"><?php echo $list['creditcardnumber'] ?></td>
	<td width="40px"><?php echo $list['authorizationno'] ?></td>
	<td width="40px"><?php echo $list['expirydate'] ?></td>
	<td width="60px" class="span_limit"><?php echo $list['remarks'] ?></td>
	<td width="40px" style="text-align:right;"><?php echo number_format($list['amount'], 2, '.', ',') ?></td>
</td>
<?php endforeach; ?>
<script>
$(".paymenttype_remove").click(function() {
	var $id = $(this).attr('id');
	var $mykeyid = "<?php echo $mykeyid ?>";
    var ortype = $('#ortype').val();
	$.ajax({
		url: "<?php echo site_url('payment/paymenttyperemove') ?>",
		type: "post",
		data: {id: $id, mykeyid: $mykeyid},
		success: function(response) {
			$response = $.parseJSON(response); 
			$(".paymenttype_list").html($response['prpayments_list']);
            $("#amountpaid").val($response['amountpaid']);
            
            if (ortype != 1) {
               $("#assignedamount").val($response['amountpaid']);         
            }
            
            $('#exdeal_note').val($response['exdealnote']);  
            if ($response['exdealnote'] == 0) {
                $('#add_paymenttype').show();     
            } 
			
            
			recompute();
			$('#paymenttype_view').dialog('close');				
		}
	}); 
});
</script>

