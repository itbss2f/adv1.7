<?php 
foreach ($applied_list as $x => $cmf) :  ?>
	<tr>						
		<td width="180px" colspan="26" style="background-color: #73A4D9;color:#FFFFFF;font-size:13px"><?php echo $x; ?></td>	
	</tr>	 	
	<?php
    $color = "";  
	foreach($cmf as $row) :
    if ($row['amountdue'] < 0) {
        $color = 'style="background-color: red;"';
    }
	?>
	<tr>						
		<td widtd="20px" <?php echo $color ?>><a href="#<?php echo $row['aoptmid'] ?>" class="updateapplied" id="<?php echo $row['aoptmid'] ?>"><?php echo $row['aoptmid'] ?></a></td>
		<td widtd="40px" <?php echo $color ?>><?php echo $row['prod_name'] ?></td>
		<td widtd="40px" <?php echo $color ?>><?php echo $row['ao_issuefrom'] ?></td>
		<td widtd="40px" <?php echo $color ?>><?php echo $row['ao_type'] ?></td>                                    
		<td widtd="40px" <?php echo $color ?>><?php echo $row['ao_sinum'] ?></td>      
		<td widtd="60px" class="span_limit"><?php echo $row['ao_part_billing'] ?></td>     
		<td widtd="40px" <?php echo $color ?>><?php echo $row['size'] ?></td>    
		<td widtd="40px" <?php echo $color ?>><?php echo $row['ao_ref'] ?></td>    
		<td widtd="40px" style="text-align:right"><?php echo number_format($row['amt'], 2, ".", ",") ?></td> 
		<td widtd="40px" style="text-align:right"><?php echo number_format($row['amountdue'] - $row['prtotalamt'], 2, ".", ",") ?></td>
		<td widtd="40px" style="text-align:right"><?php echo number_format($row['applied_amt'], 2, ".", ",") ?></td>       
		<td widtd="40px" style="text-align:right"><?php echo number_format($row['a_wvat'], 2, ".", ",") ?></td> 
		<td widtd="40px"><?php echo $row['a_wvatp'] ?></td> 							
		<td widtd="40px" style="text-align:right"><?php echo number_format($row['a_wtax'], 2, ".", ",") ?></td> 							
		<td widtd="40px"><?php echo $row['a_wtaxp'] ?></td> 							
		<td widtd="40px" style="text-align:right"><?php echo number_format($row['a_ppd'], 2, ".", ",") ?></td> 							
		<td widtd="40px"><?php echo $row['a_ppdp'] ?></td> 							 	
	</tr>
	<?php
	endforeach;
endforeach;
?>
<script>
$(".updateapplied").click(function() {
	var $id = $(this).attr("id");
	$.ajax({
		url: "<?php echo site_url('prpayment/updateappliedpaymentview') ?>",
		type: "post",
		data: {id: $id, mykeyid: "<?php echo $mykeyid ?>", code: $("#payeecode").val(), type: $("#applicationtype").val(), choose: $("#prchoose:checked").val()},
		success: function(response) {
			var $response = $.parseJSON(response);
			$('#updatepaymentapplied_view').html($response['updatepaymentapplied_view']).dialog('open');
		}
	});	

});
</script>
