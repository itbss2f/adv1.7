<?php        
$gapplied = 0; $gwvat = 0; $gwtax = 0; $gppd = 0; 
$sapplied = 0; $swvat = 0; $swtax = 0; $sppd = 0; $xap = ""; 
foreach ($applied_list as $x => $cmf) :  ?>
	<tr>						
		<td width="180px" colspan="26" style="background-color: #73A4D9;color:#FFFFFF;font-size:13px"><?php echo $x; ?></td>	
	</tr>	 	
	<?php
    $color = "";
    $sapplied = 0; $swvat = 0; $swtax = 0; $sppd = 0;
    
	foreach($cmf as $row) :
    #echo $row['amt']."x";
    $gapplied += $row['applied_amt']; $gwvat += $row['a_wvat']; $gwtax += $row['a_wtax']; $gppd += $row['a_ppd'];
    $sapplied += $row['applied_amt']; $swvat += $row['a_wvat']; $swtax += $row['a_wtax']; $sppd += $row['a_ppd'];
    $color = "";
    if ($row['amountdue'] < 0) {
        $color = 'style="background-color: red;"';
        $xap = "err";
    }

	?>
	<tr>
        <?php $row['status'];  if ($row['status'] == 'O') : ?>
        <td widtd="20px" style="background-color: red">Posted</td>  
        <?php else: ?>
        <?php if ($row['ao_type'] == "DM") : ?>  						
        <td widtd="20px" <?php echo $color ?>><a href="#<?php echo $row['aoptmid'] ?>" class="updateapplieddm" id="<?php echo $row['aoptmid'] ?>"><?php echo $row['aoptmid'] ?></a></td>
        <?php else: ?>
		<td widtd="20px" <?php echo $color ?>><a href="#<?php echo $row['aoptmid'] ?>" class="updateapplied" id="<?php echo $row['aoptmid'] ?>"><?php echo $row['aoptmid'] ?></a></td>    
        <?php endif; ?>
        <?php endif; ?>
		<td widtd="40px" class="xappliedprod<?php echo $xap ?>" <?php echo $color ?>><?php echo $row['prod_name'] ?></td>
		<td widtd="40px" <?php echo $color ?>><?php echo $row['ao_issuefrom'] ?></td>
		<td widtd="40px" <?php echo $color ?>><?php echo $row['ao_type'] ?></td>   
        <?php if ($row['ao_type'] == "DM") : ?>                                                           
        <td widtd="40px" <?php echo $color ?>><?php echo $row['aoptmid'] ?></td>  
        <?php else: ?> 
		<td widtd="40px" <?php echo $color ?>><?php echo $row['ao_sinum'] ?></td>   
        <?php endif; ?>    
		<td widtd="60px" class="span_limit" <?php echo $color ?>><?php echo $row['ao_billing_prodtitle'] ?></td>     
		<td widtd="40px" <?php echo $color ?>><?php echo $row['size'] ?></td>    
        <td widtd="40px" <?php echo $color ?>><?php echo $row['ao_ref'] ?></td>    
        <td widtd="40px" <?php echo $color ?>><?php echo $row['adtype_code'] ?></td>    
		<td widtd="40px" <?php echo $color ?>><?php echo $row['lastapplieddate'] ?></td>    
		<td widtd="40px" style="text-align:right" <?php echo $color ?>><?php echo number_format($row['amt'], 2, ".", ",") ?></td> 
        <?php if ($row['ao_type'] == "DM") : ?>
        <td widtd="40px" style="text-align:right"><?php echo number_format($row['amountdue'], 2, ".", ",") ?></td>
        <?php else: ?>
		<td widtd="40px" style="text-align:right"><?php echo number_format($row['amt'] - ($row['totalor'] + $row['totaldc']), 2, ".", ",") ?></td>
        <?php endif; ?>
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
    ?>
    <tr>
        <td colspan="11" style="background-color: gray;"></td>
        <td style="background-color: gray;"><b>Subtotal:</b></td>
        <td style="text-align: right; background-color: gray"><b><?php echo number_format($sapplied, 2, ".", ",") ?></b></td>
        <td style="text-align: right; background-color: gray"><b><?php echo number_format($swvat, 2, ".", ",") ?></b></td>
        <td style="background-color: gray;"></td>
        <td style="text-align: right; background-color: gray"><b><?php echo number_format($swtax, 2, ".", ",") ?></b></td>  
        <td style="background-color: gray;"></td>
        <td style="text-align: right; background-color: gray"><b><?php echo number_format($sppd, 2, ".", ",") ?></b></td> 
        <td style="background-color: gray;"></td> 
    </tr>
    <?php
endforeach;
?>
<tr>
    <td colspan="11" style="background-color: gray;"></td>
    <td style="background-color: gray;"><b>Grandtotal:</b></td>
    <td style="text-align: right; background-color: gray"><b><?php echo number_format($gapplied, 2, ".", ",") ?></b></td>
    <td style="text-align: right; background-color: gray"><b><?php echo number_format($gwvat, 2, ".", ",") ?></b></td>
    <td style="background-color: gray;"></td>
    <td style="text-align: right; background-color: gray"><b><?php echo number_format($gwtax, 2, ".", ",") ?></b></td>  
    <td style="background-color: gray;"></td>
    <td style="text-align: right; background-color: gray"><b><?php echo number_format($gppd, 2, ".", ",") ?></b></td> 
    <td style="background-color: gray;"></td> 
</tr>
<script>
$(".updateapplied").click(function() {
	var $id = $(this).attr("id");
	$.ajax({
		url: "<?php echo site_url('payment/updateappliedpaymentview') ?>",
		type: "post",
		data: {id: $id, mykeyid: "<?php echo $mykeyid ?>", code: $("#payeecode").val(), type: $("#applicationtype").val(), choose: $("#prchoose:checked").val()},
		success: function(response) {
			var $response = $.parseJSON(response);
			$('#updatepaymentapplied_view').html($response['updatepaymentapplied_view']).dialog('open');
		}
	});	

});
$(".updateapplieddm").click(function() {
    var $id = $(this).attr("id");
    $.ajax({
        url: "<?php echo site_url('payment/updateapplieddmpaymentview') ?>",
        type: "post",
        data: {id: $id, mykeyid: "<?php echo $mykeyid ?>", code: $("#payeecode").val(), type: $("#applicationtype").val(), choose: $("#prchoose:checked").val()},
        success: function(response) {
            var $response = $.parseJSON(response);
            $('#updatepaymentapplieddm_view').html($response['updatepaymentapplieddm_view']).dialog('open');
        }
    });         
});
</script>
