<?php 
	$m_totalnetsales = $main['sales_'.strtolower($m)];
	$m_totalcm = $main['cm_'.strtolower($m)];
?>
<div class="row-form-booking-form">
    <input text="hidden" id="m_totalnetsales" style="display: none;" value="<?php echo number_format($main['sales_'.strtolower($m)], 2, '.', ',') ?>">      
    <input text="hidden" id="m_totalcm" style="display: none;" value="<?php echo number_format($main['cm_'.strtolower($m)], 2, '.', ',') ?>">       
	<div class="span1" style="color:#335A85"><b><?php echo $main['budget_year'] ?></div>
	<div class="span1" style="color:#335A85"><?php echo $month ?></div>
	<div class="span2" style="color:#335A85"><?php echo $main['productname'] ?></div>
	<div class="span2" style="color:#335A85"><?php echo $main['caf_code'].' - '.$main['adtype_name'] ?></b></div>	
	<div class="clear"></div>	
</div>
<div class="row-form-booking-form">	
	<div class="span1"><b>Total Sales</b></div>
	<div class="span2"><?php echo number_format($main['sales_'.strtolower($m)], 2, '.', ',') ?></div>
	<div class="span1"><b>Total CM</b></div>
	<div class="span2"><?php echo number_format($main['cm_'.strtolower($m)], 2, '.', ',') ?></div>
	<div class="clear"></div>	
</div>
<div class="dr"><span></span></div>
<div class="block-fluid table-sorting" style="overflow:auto; height:300px">
	<table cellpadding="0" cellspacing="0" width="100%" class="table">
	<thead>
		<tr>
			<th width="15%">Issue Date</th>
			<th width="15%">Day</th>
			<th width="20%">Net Sales</th>
			<th width="20%">CM Amount</th>                                                                
		</tr>
	</thead>
	<tbody>
		<?php $count = 1;
		$totalnetsales = 0;
		$totalcm = 0; 
		foreach ($data as $row) : ?>
		<tr>
			<td style="height :14px"><?php echo $row['issue_date'] ?></td>
			<td style="height :14px"><?php echo $row['day'] ?></td>
			<td><input type="text" style="margin: 0px; padding: 0px; height: 20px;text-align: right" class="text_netsales" name="netsales[]" id="<?php echo $row['id'] ?>" data-count="<?php echo $count ?>" data-value="<?php echo $row['day'] ?>" style="text-align:right;width:150px" value="<?php echo number_format($row['netsales'], 2, '.', ',') ?>"></td>
			<td><input type="text" style="margin: 0px; padding: 0px; height: 20px;text-align: right" class="text_cmamount" name="cmamount[]" id="<?php echo $row['id'] ?>" data-count="<?php echo $count ?>" data-value="<?php echo $row['day'] ?>" style="text-align:right;width:150px" value="<?php echo number_format($row['cm_amount'], 2, '.', ',') ?>"></td>
		</tr>
		<?php 
		$totalnetsales += $row['netsales'];
		$totalcm += $row['cm_amount'];
		$count += 1; 
		endforeach; ?>
	</tbody>
	</table>
</div>
<div class="row-form-booking-form" style="margin-top:5px">
	<div class="span2" style="width:100px;margin-top:5px"><b>Total Netsales</b></div>
	<div class="span2" style="width:120px"><input type="text"  style="text-align:right;" id="totalnetsales" value="<?php echo number_format($totalnetsales, 2, '.', ',') ?>" readonly="readonly"></div>
	<div class="span2" style="width:100px;margin-top:5px"><b>Total CM Amount</b></div>
	<div class="span2" style="width:120px"><input type="text"  style="text-align:right;" id="totalcm" value="<?php echo number_format($totalcm, 2, '.', ',') ?>" readonly="readonly"></div>
	<div class="clear"></div>	
</div>
<div class="row-form-booking-form" style="margin-top:10px">
	<div class="span2"><button class="btn btn-block btn-info" type="button" name="duplicate" id="duplicate">Duplicate button</button></div>
	<div class="span2"><button class="btn btn-block btn-success" name="savedetailed" id="savedetailed" type="button">Save button</button></div>
	<div class="span2"><button class="btn btn-block btn-danger" name="close" id="close" type="button">Close button</button></div>
	<div class="clear"></div>	
</div>
<script>
$(".text_cmamount").keyup(function() {
	var arr = [];
	var count = $('.text_cmamount').length;	
	for (var c = 1; c <= count; c++) {
		var netval = $(".text_cmamount[data-count='"+c+"']").val();
		arr.push(netval);
	}
	$.ajax({
		url: "<?php echo site_url('yms_product_budget/computeDetailed') ?>",
		type: "post",
		data: {arr: arr},
		success: function(response) {
			$response = $.parseJSON(response);
			$("#totalcm").val($response['total']);			
		}
	});
});
$(".text_netsales").keyup(function() {
	var arr = [];
	var count = $('.text_netsales').length;	
	for (var c = 1; c <= count; c++) {
		var netval = $(".text_netsales[data-count='"+c+"']").val();
		arr.push(netval);
	}
	$.ajax({
		url: "<?php echo site_url('yms_product_budget/computeDetailed') ?>",
		type: "post",
		data: {arr: arr},
		success: function(response) {
			$response = $.parseJSON(response);
			$("#totalnetsales").val($response['total']);			
		}
	});
});
$("#savedetailed").click(function(){
	var arr = [];
	var arr2 = [];
	var arr3 = [];
	var count = $('.text_netsales').length;
	for (var c = 1; c <= count; c++) {
		var id = $(".text_netsales[data-count='"+c+"']").attr('id');
		var netval = $(".text_netsales[data-count='"+c+"']").val();
		var cmval = $(".text_cmamount[data-count='"+c+"']").val();
		arr.push(id);
		arr2.push(netval);
		arr3.push(cmval);
	}

	var m_totalnetsales = $('#m_totalnetsales').val(); //"<?php //echo $m_totalnetsales ?>";
	var m_totalcm = $('#m_totalcm').val(); //"<?php #echo $m_totalcm ?>";
	
	var d_totalnetsales = $('#totalnetsales').val(); //"<?php #echo $totalnetsales ?>";
	var d_totalcm = $('#totalcm').val(); //"<?php #echo $totalcm ?>";
    //alert(m_totalcm); return false;
	if (m_totalnetsales !== d_totalnetsales) {
		alert('Total Netsales is not equal to detailed netsales!. Data Successfully Save!');
	}

	if (m_totalcm !== d_totalcm) {
		alert('Total CM Amount is not equal to detailed cm amount!. Data Successfully Save!');
	}

	$.ajax({
		url: "<?php echo site_url('yms_product_budget/saveDetail') ?>",
		type: "post",
		data: {arr: arr, arr2: arr2, arr3: arr3},
		success: function(response) {
            //alert('');
		}
	});
});
$("#close").click(function() { $("#modal_detaildata").dialog('close'); });
$(".text_netsales").autoNumeric({nBracket:null,vMin: '-999999999' }); 
$(".text_cmamount").autoNumeric({nBracket:null,vMin: '-999999999' });
$("#duplicate").click(function() {
	var d1 = $(".text_netsales[data-count='1']").attr('data-value');
	var d2 = $(".text_netsales[data-count='2']").attr('data-value');
	var d3 = $(".text_netsales[data-count='3']").attr('data-value');
	var d4 = $(".text_netsales[data-count='4']").attr('data-value');
	var d5 = $(".text_netsales[data-count='5']").attr('data-value');
	var d6 = $(".text_netsales[data-count='6']").attr('data-value');
	var d7 = $(".text_netsales[data-count='7']").attr('data-value');

	var cmd1 = $(".text_cmamount[data-count='1']").attr('data-value');
	var cmd2 = $(".text_cmamount[data-count='2']").attr('data-value');
	var cmd3 = $(".text_cmamount[data-count='3']").attr('data-value');
	var cmd4 = $(".text_cmamount[data-count='4']").attr('data-value');
	var cmd5 = $(".text_cmamount[data-count='5']").attr('data-value');
	var cmd6 = $(".text_cmamount[data-count='6']").attr('data-value');
	var cmd7 = $(".text_cmamount[data-count='7']").attr('data-value');

	var v1 = $(".text_netsales[data-count='1']").val();
	var v2 = $(".text_netsales[data-count='2']").val();
	var v3 = $(".text_netsales[data-count='3']").val();
	var v4 = $(".text_netsales[data-count='4']").val();
	var v5 = $(".text_netsales[data-count='5']").val();
	var v6 = $(".text_netsales[data-count='6']").val();
	var v7 = $(".text_netsales[data-count='7']").val();

	var cmv1 = $(".text_cmamount[data-count='1']").val();
	var cmv2 = $(".text_cmamount[data-count='2']").val();
	var cmv3 = $(".text_cmamount[data-count='3']").val();
	var cmv4 = $(".text_cmamount[data-count='4']").val();
	var cmv5 = $(".text_cmamount[data-count='5']").val();
	var cmv6 = $(".text_cmamount[data-count='6']").val();
	var cmv7 = $(".text_cmamount[data-count='7']").val();


	$(".text_netsales[data-value='"+d1+"']").val(v1);
	$(".text_netsales[data-value='"+d2+"']").val(v2);
	$(".text_netsales[data-value='"+d3+"']").val(v3);
	$(".text_netsales[data-value='"+d4+"']").val(v4);
	$(".text_netsales[data-value='"+d5+"']").val(v5);
	$(".text_netsales[data-value='"+d6+"']").val(v6);
	$(".text_netsales[data-value='"+d7+"']").val(v7);

	$(".text_cmamount[data-value='"+cmd1+"']").val(cmv1);
	$(".text_cmamount[data-value='"+cmd2+"']").val(cmv2);
	$(".text_cmamount[data-value='"+cmd3+"']").val(cmv3);
	$(".text_cmamount[data-value='"+cmd4+"']").val(cmv4);
	$(".text_cmamount[data-value='"+cmd5+"']").val(cmv5);
	$(".text_cmamount[data-value='"+cmd6+"']").val(cmv6);
	$(".text_cmamount[data-value='"+cmd7+"']").val(cmv7);


	var arr1 = [];
	var arr2 = [];
	var count = $('.text_cmamount').length;	
	for (var c = 1; c <= count; c++) {
		var cmval = $(".text_cmamount[data-count='"+c+"']").val();
		var netval = $(".text_netsales[data-count='"+c+"']").val();
		arr1.push(cmval);
		arr2.push(netval);
	}
	$.ajax({
		url: "<?php echo site_url('yms_product_budget/dupcomputeDetailed') ?>",
		type: "post",
		data: {arr1: arr1, arr2: arr2},
		success: function(response) {
			$response = $.parseJSON(response);
			$("#totalcm").val($response['total1']);		
			$("#totalnetsales").val($response['total2']);				
		}
	});

	return false;
});
</script>
