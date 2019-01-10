<?php if (!empty($datelist)) : ?>
<?php foreach ($datelist as $list) : 
$color = "";
$tip = "";
if ($list['newinvoice'] != 0) {
$color = "style='background-color:#73A4D9'";
$tip = "Superced";
}
?>
<tr style="height:10px;" class="data_tbody">
	<td width="40px" <?php echo $color ?>>
    <?php if ($list['status'] != 'C') : ?>
    <a href="#" class="issue_edit" id="<?php echo $list['aoptmid'] ?>" title="<?php echo $tip ?>"><span class="icon-pencil"></span></a>
    <?php else: ?>
    <span class="icon-remove"></span>
    <?php endif; ?>
    </td>						 
	<td width="40px"><?php echo $list['ao_num'] ?></td>					 
	<td width="40px"><?php echo $list['newinvoice'] ?></td>			
	<td width="40px"><?php echo $list['newinvoicedate'] ?></td>
	<td width="60px"><?php echo date("M d, Y", strtotime($list['datepickerdate'])); ?></td>
	<td width="40px" style="text-align:right;"><?php echo $list['rateamt'] ?></td>
	<td width="40px" style="text-align:right;"><?php echo $list['width'] ?></td>
	<td width="40px" style="text-align:right;"><?php echo $list['length'] ?></td>
	<td width="40px" style="text-align:right;"><?php echo $list['totalsize'] ?></td>
	<td width="40px" style="text-align:right;"><?php echo number_format($list['premiumamt'], 2, ".", ",") ?></td>
	<td width="40px" style="text-align:right;"><?php echo number_format($list['discountamt'], 2, ".", ",") ?></td>
	<td width="40px" style="text-align:right;"><?php echo number_format($list['baseamt'], 2, ".", ",") ?></td>
	<td width="40px" style="text-align:right;"><?php echo number_format($list['computedamt'], 2, ".", ",") ?></td>
	<td width="40px" style="text-align:right;"><?php echo number_format($list['totalcost'], 2, ".", ",") ?></td>
	<td width="40px" style="text-align:right;"><?php echo number_format($list['agencycom'], 2, ".", ",") ?></td>
	<td width="40px" style="text-align:right;"><?php echo number_format($list['nvs'], 2, ".", ",") ?></td>
	<td width="40px" style="text-align:right;"><?php echo number_format($list['vatexempt'], 2, ".", ",") ?></td>
	<td width="40px" style="text-align:right;"><?php echo number_format($list['vatzerorate'], 2, ".", ",") ?></td>
	<td width="40px" style="text-align:right;"><?php echo number_format($list['vatamt'], 2, ".", ",") ?></td>
	<td width="40px" style="text-align:right;"><?php echo number_format($list['amtdue'], 2, ".", ",") ?></td>
	<td width="50px"><?php echo $list['classifname'] ?></td>
	<td width="50px"><?php echo $list['subclassname'] ?></td>
	<td width="50px"><?php echo $list['color'] ?></td>
	<td width="50px" class="span_limit"><?php echo $list['position'] ?></td>
	<td width="40px"><?php echo $list['pagemin'] ?></td>
	<td width="40px"><?php echo $list['pagemax'] ?></td>
	<td width="80px" class="span_limit"><?php echo $list['billing'] ?></td>
	<td width="80px" class="span_limit"><?php echo $list['records'] ?></td>
	<td width="80px" class="span_limit"><?php echo $list['production'] ?></td>
	<td width="80px" class="span_limit"><?php echo $list['followup'] ?></td>
	<td width="80px" class="span_limit"><?php echo $list['eps'] ?></td>		
	<td width="40px"><?php echo $list['mischarge1'] ?></td>
	<td width="40px"><?php echo $list['mischarge2'] ?></td>
	<td width="40px"><?php echo $list['mischarge3'] ?></td>
	<td width="40px"><?php echo $list['mischarge4'] ?></td>
	<td width="40px"><?php echo $list['mischarge5'] ?></td>
	<td width="40px"><?php echo $list['mischarge6'] ?></td>
	<td width="40px"><?php echo $list['mischargepercent1'] ?></td>
	<td width="40px"><?php echo $list['mischargepercent2'] ?></td>
	<td width="40px"><?php echo $list['mischargepercent3'] ?></td>
	<td width="40px"><?php echo $list['mischargepercent4'] ?></td>
	<td width="40px"><?php echo $list['mischargepercent5'] ?></td>
	<td width="40px"><?php echo $list['mischargepercent6'] ?></td>
	<td width="40px"><?php echo $list['surcharge'] ?></td>
	<td width="40px"><?php echo $list['discount'] ?></td> 
</tr>	
<?php endforeach; ?>
<?php endif; ?>
<script>
$(document).ready(function() {
	$(".issue_edit").click(function() {
		var $issuedate = $(this).attr('id');
        
		//var $duepercent = "<?php #echo $duepercent; ?>";
        var $duepercent = $('#duepercent').val();   
        var $vatcode = $('#vatcode').val();   
		//var $vatcode = "<?php #echo $vatcode; ?>";
		$.ajax({
			url: "<?php echo site_url('bookingissue/supercedissue_edit') ?>",
			type: "post",
			data: {issuedate: $issuedate, mykeyid: "<?php echo $mykeyid; ?>", 
                      type: "<?php echo $type; ?>", product: "<?php echo $product; ?>",
                      duepercent: $duepercent, vatcode: $vatcode},
			success:function(response) {
				$response = $.parseJSON(response);
				$('#supercedissuedate_view').html($response['supercedissuedate_detailed']).dialog('open');			
			}
		});

	});
});

</script>
