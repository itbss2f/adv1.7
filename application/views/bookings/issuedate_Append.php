<?php if (!empty($datelist)) : ?>
<?php foreach ($datelist as $list) : 
$color = "";
$tip = "";
if ($list['is_flow'] != 0) {
$color = "style='background-color:#73A4D9'";
$tip = "Dummy flow";
}
if ($list['ao_paginated_status'] == 1) {
$color = "style='background-color:#ECB200'";
$tip = "Paginated";
} 
if ($list['ao_sinum'] != 0) {
$color = "style='background-color:#CB2C1A'";
$tip = $list['ao_sinum'];
}  
$colorbox = ""; 
?>
<tr style="height:10px;" class="data_tbody">
	<?php if ($type == "M") : ?>	
	<td width="40px" <?php echo $color ?>><span class="icon-briefcase"></td>
	<?php else: ?>
    <?php if ($list['status'] != 'C') : ?>	
	<td width="40px" <?php echo $color ?>><a href="#" class="issue_edit" id="<?php echo $list['datepickerdate'] ?>" title="<?php echo $tip ?>"><span class="icon-pencil"></span></a></td>	
    <?php else :?>
    <td width="40px" style="background-color: red;"><span class=" icon-lock"></span></td>
    <?php endif; ?>	
    				 
	<?php endif; ?>
    <?php if ($list['color'] == '4Co') {
        $colorbox = '<div style="background-color: #FF0000; width: 20px; height: 5px;"></div>';
    } else if ($list['color'] == '2Sp') { 
        $colorbox = '<div style="background-color: #00FFFF; width: 20px; height: 5px;"></div>';
    } else if ($list['color'] == 'Spot') { 
        $colorbox = '<div style="background-color: #00FF00; width: 20px; height: 5px;"></div>';
    }  ?>
	<td width="60px"><?php echo date("M d, Y", strtotime($list['datepickerdate'])); ?><?php echo $colorbox ?></td>
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
    <td width="60px"><?php echo $list['ao_sinum'] ?></td>
    <td width="60px"><?php echo @$list['ao_sidate'] ?></td>
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
        var $duepercent = $('#duepercent').val();
        var $vatcode = $('#vatcode').val();
		//var $duepercent = "<?php #echo #$duepercent; ?>";
		//var $vatcode = "<?php #echo #$vatcode; ?>";
		$.ajax({
			url: "<?php echo site_url('bookingissue/issue_edit') ?>",
			type: "post",
			data: {issuedate: $issuedate, mykeyid: "<?php echo $mykeyid; ?>", 
                      type: "<?php echo $type; ?>", product: "<?php echo $product; ?>",
                      duepercent: $duepercent, vatcode: $vatcode},
			success:function(response) {
				$response = $.parseJSON(response);
				$('#issuedate_view').html($response['issuedate_detailed']).dialog('open');			
			}
		});

	});
});

</script>
