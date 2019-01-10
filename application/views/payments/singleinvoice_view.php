<div class="block-fluid">       
	<div class="row-form-booking">
		<div class="span1">Invoice No.</div>	
		<div class="span1" style="width:80px"><input type="text" id="invoiceno" name="invoiceno"></div>
		<div class="span1"><button class="btn btn-block btn-info" type="button" id="btn_invoicefind" name="btn_invoicefind">Find</button></div>
		<div class="span1"><button class="btn btn-block btn-success" type="button" id="btn_sapplied" name="btn_sapplied">Applied</button></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:200px"> 
	<table cellpadding="0" cellspacing="0" style="white-space:nowrap;width:1000px" class="table" id="tSortable_2">
		<thead>
			<tr>						
				<th width="10px"></th>
				<th width="40px">Issue Date</th>
				<th width="60px">Amount Due</th>                                    
				<th width="70px">Applied</th>       
				<th width="70px">WTAX</th>      
				<th width="70px">WVAT</th> 
				<th width="70px">PPD</th>			
			</tr>
		</thead>
		<tbody class="invoice_list">					  					  			                     
		</tbody>
	</table> 
	</div>
	<div class="row-form-booking">
		<div class="span1" style="width:100px">WTAX %: <b><?php echo $wtax ?></b></div>				
		<div class="span1" style="width:100px">WVAT %: <b><?php echo $wvat ?></b></div>	
		<div class="span1" style="width:100px">PPD %: <b><?php echo $ppd ?></b></div>	
		<div class="clear"></div>	
	</div>
</div>
<script>
$("#btn_sapplied").click(function() {
	var count = $(".check_singleinvoice:checked").length;
	var _id = [];
	var _applied = [];
	var _wtax = [];
	var _wvat = [];
	var _ppd = [];

	$(".check_singleinvoice:checked").each(function(){
		var $this = $(this);
		var $id = $this.attr("id");	
		var $d_applied = $("#sapplied"+$id).val();
		var $d_wtax = $("#swtax"+$id).val();
		var $d_wvat = $("#swvat"+$id).val();
		var $d_ppd = $("#sppd"+$id).val();
		_id.push($id);
		_applied.push($d_applied);
		_wtax.push($d_wtax);
		_wvat.push($d_wvat);
		_ppd.push($d_ppd);
	});

	var $mykeyid = "<?php echo $mykeyid ?>";
	var $wtaxp = "<?php echo $wtax ?>";
	var $wvatp = "<?php echo $wvat ?>";
	var $ppdp = "<?php echo $ppd ?>";
	$.ajax({
		url: "<?php echo site_url('payment/applieSingleInvoice') ?>",
		type: "post",
		data: {mykeyid: $mykeyid, id: _id, applied: _applied, wtax: _wtax, wvat: _wvat, ppd: _ppd, wtaxp: $wtaxp, wvatp: $wvatp, ppdp: $ppdp},
		success: function(response) {
			$response = $.parseJSON(response);
			$('#paymentapplied_list').html($response['applied_list']);
			$('#wvatassign').val($response['summaryassign']['totalwvat']);
			$('#wtaxassign').val($response['summaryassign']['totalwtax']);
			$('#ppdassign').val($response['summaryassign']['totalppd']);
			$('#assignedamount').val($response['summaryassign']['totalappliedamt']);				
			$('#singleinvoice_view').dialog('close');
		}
	});
});
$("#invoiceno").mask('99999999');
$("#btn_invoicefind").click(function() {
	var $invoiceno = $("#invoiceno").val();

	$.ajax({
		url: "<?php echo site_url('payment/invoicenofind') ?>",
		type: "post",
		data: {invoiceno: $invoiceno,	
			  mykeyid: "<?php echo $mykeyid ?>",		  
			  wtaxp: "<?php echo $wtax ?>",
			  wvatp: "<?php echo $wvat ?>",
			  ppdp: "<?php echo $ppd ?>",
			  vatcode: "<?php echo $vatcode ?>"
		      },
		success: function(response) {
			$response = $.parseJSON(response);
            
            if ($response['empty'] == '0') {
                alert('No record found!');
            }
            $('.search_list').html($response['search_list']);      
			$(".invoice_list").html($response['invoice_list']);
		}
	});
});
</script>
