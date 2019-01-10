<?php 
$finalstat = "";
if(@$print['ao_rfa_finalstatus'] == 1) { $finalstat="readonly='readonly'"; }
?>

<div>
	<div align="center">
		<div><b><?php echo $parameter['com_name'] ?></b></div>
		<div><b><u>ADVERTISING</u></b></div>
		<div><b><u>REQUEST FOR ADJUSTMENT</u></b></div>
	</div>  
	<div class="row-form-booking-form" style="margin-top:10px">
		<div class="span1">Advertiser</div>
		<div class="span2 span_limit"><b>:</b>  <?php echo @$print['ao_payee'] ?></div>
		<div class="span3" style="text-align:right">Date: <?php if (@$print['ao_rfa_date'] == "") { echo date('F d, Y', strtotime(DATE('Y-m-d'))); } else { echo date('F d, Y', strtotime(@$print['ao_rfa_date'])); } ?></div>
		<div class="clear"></div>
	</div>  
	<div class="row-form-booking-form">
		<div class="span1">Agency</div>
		<div class="span2 span_limit"><b>:</b>  <?php echo @$print['agency'] ?></div>
		<div class="clear"></div>
	</div>  
	<div class="row-form-booking-form">
		<div class="span1">AE</div>
		<div class="span2 span_limit"><b>:</b>  <?php echo @$print['ae'] ?></div>
		<div class="clear"></div>
	</div>
	<div class="row-form-booking-form">
		<div class="span1">Invoice No#</div>
		<div class="span2 span_limit"><b>:</b>  <?php echo @$print['ao_sinum'] ?></div>
		<div class="span1"></div>
		<div class="span1"><u><b>Amount</b></u></div>
		<div class="span1"></div>
		<div class="clear"></div>
	</div>
	<div class="row-form-booking-form">
		<div class="span1">Issue Date</div>
		<div class="span2 span_limit"><b>:</b>  <?php echo @$print['issuedateaffected'] ?></div>
		<div class="span1">Per Invoice </div>
		<div class="span1" style="width:20px">:</div>
		<div class="span1" style="text-align:right"><?php echo number_format(@$print['invoiceamt'], 2) ?></div>
		<div class="clear"></div>
	</div>
	<div class="row-form-booking-form">
		<div class="span1">Type Ad</div>
		<div class="span2 span_limit"><b>:</b>  <?php echo @$print['adtype_name'] ?></div>
		<div class="span1">Adjusted </div>
		<div class="span1" style="width:20px">:</div>
		<div class="span1" style="text-align:right"><?php echo number_format(@$print['adjustmentamt'], 2) ?></div>
		<div class="clear"></div>
	</div>
	<div class="row-form-booking-form">
		<div class="span1">Size</div>
		<div class="span2 span_limit"><b>:</b> <?php echo @$print['ao_width'] ?> x <?php echo @$print['ao_length'] ?></div>
		<div class="span1">Difference </div>
		<div class="span1" style="width:20px">:</div>
		<div class="span1" style="text-align:right"><?php echo number_format(@$print['invoiceamt'] - @$print['adjustmentamt'], 2) ?></div>
		<div class="clear"></div>
	</div>

	<div class="row-form-booking" style="margin-top:10px;border-top:1px solid #333333">
		<div class="span1">RFA Type</div>
		<div class="span5">
			<select class='select' name='typecode' id='typecode'>
			<?php
			if(@$print['ao_rfa_finalstatus'] != 1) : ?>
			<option value=''>--</option>                                                        
			<?php 
			endif;
			foreach ($rfatype as $row) : 

				if(@$print['ao_rfa_finalstatus'] == 1) {
					if($row['id'] == $rfadata['ao_rfa_type']) :
					?>
					<option value='<?php echo $row['id'] ?>' selected='selected'><?php echo str_pad($row['id'],2,'0',STR_PAD_LEFT).' - '. $row['rfatype_name']?></option>
					<?php 
					endif;    
					} else {
					if($row['id'] == $rfadata['ao_rfa_type']) :
					?>
					<option value='<?php echo $row['id'] ?>' selected='selected'><?php echo str_pad($row['id'],2,'0',STR_PAD_LEFT).' - '. $row['rfatype_name']?></option>
					<?php 
					else :
					?>
					<option value='<?php echo $row['id'] ?>'><?php echo str_pad($row['id'],2,'0',STR_PAD_LEFT).' - '. $row['rfatype_name']?></option>
					<?php 
					endif;    
				}
			endforeach;
			?>
			</select>
		</div>		
		<div class="clear"></div>
	</div>
	<div class="row-form-booking">
		<div class="span2">Findings/Nature of Complaint</div>		
		<div class="span4"><input type='text' name='findings' id='findings' <?php echo $finalstat; ?> value="<?php echo @$print['ao_rfa_findings'] ?>"></div>
		<div class="clear"></div>
	</div>
	<div class="row-form-booking">
		<div class="span2">Possible Adjustments</div>		
		<div class="span4"><input type='text' name='possibleadjustment' id='possibleadjustment' <?php echo $finalstat; ?> value="<?php echo @$print['ao_rfa_adjustment'] ?>"></div>
		<div class="clear"></div>
	</div>
	<div class="row-form-booking">
		<div class="span2">P / A / C / O Responsible</div>		
		<div class="span1">
			<?php 
			if(@$print['ao_rfa_finalstatus'] == 1) : ?>
			<select class='select' style='width:100px;' name='person' id='person'>     
			 <?php 
			 if (@$print['ao_rfa_person'] == 'P') { ?>
				<option value='P' <?php if ($print['ao_rfa_person'] == 'P') { echo "selected='selected'";}?>>Person</option>    
			 <?php
			 } else if (@$print['ao_rfa_person'] == 'A') { ?>
				<option value='A' <?php if ($print['ao_rfa_person'] == 'A') { echo "selected='selected'";}?>>Agency</option>
			 <?php
			 } else if (@$print['ao_rfa_person'] == 'C') { ?>
				<option value='C' <?php if ($print['ao_rfa_person'] == 'C') { echo "selected='selected'";}?>>Client</option>  
			 <?php 
			 } else if (@$print['ao_rfa_person'] == 'O') {  ?>
				<option value='O' <?php if ($print['ao_rfa_person'] == 'O') { echo "selected='selected'";}?>>Others</option>   
			 <?php
			 }
			 ?>
			</select>
			<?php
			else :                                                    
			?>
			<select class='select' style='width:100px;' name='person' id='person'>
			 <option value=''>--</option>                       
			 <option value='P' <?php if ($print['ao_rfa_person'] == 'P') { echo "selected='selected'";}?>>Person</option>
			 <option value='A' <?php if ($print['ao_rfa_person'] == 'A') { echo "selected='selected'";}?>>Agency</option>
			 <option value='C' <?php if ($print['ao_rfa_person'] == 'C') { echo "selected='selected'";}?>>Client</option>
			 <option value='O' <?php if ($print['ao_rfa_person'] == 'O') { echo "selected='selected'";}?>>Others</option>
			</select>
			<?php 
			endif;
			?>
		</div>
		<div class="span3"><input type='text' name='responsiblename' id='responsiblename' <?php echo $finalstat; ?> value="<?php echo $print['ao_rfa_reason'] ?>"></div>
		<div class="clear"></div>
	</div>
	<div class="row-form-booking" style="margin-top:10px;border-top:1px solid #333333">
		<div class="span2">Adjustment Amount</div>
		<div class="span1" style="width:100px"><input type='text' class='text-right' name='adjustmentamt' id='adjustmentamt' <?php #echo $finalstat; ?> value="<?php echo number_format(@$print['ao_rfa_amt'], 2, '.', ','); ?>"></div>				
		<div class="span2" style="width:200px"><input type='checkbox' name='signatories' id='signatories' style="width:30px;<?php if(@$print['ao_rfa_finalstatus'] == 1){echo "display:none";} ?>" <?php if(@$print['ao_rfa_finalstatus'] == 1) { echo "checked='checked'";}?> value='1'><?php if(@$print['ao_rfa_finalstatus'] == 1) { echo "<span style='font-zie:13px;color:red'>Approved for superceding   ".@$print['ao_rfa_supercedingai']."</span>"; } else { echo "Signatories";} ?></div>
		<div class="clear"></div>
	</div>
	<div class="row-form-booking">
		<div class="span1">Status</div>				
		<div class="span1" style="width:100px">
		<select class='select' name='rfastatus' id='rfastatus'>          
			<?php if(@$print['ao_rfa_finalstatus'] == 0) : ?>
			<option value=''></option>                                                          
			<?php endif; ?>
			<option value='C' <?php if ($rfadata['ao_rfa_aistatus'] == 'C') { echo "selected='selected'";}?>>Cancelled</option>
			<option value='A' <?php if ($rfadata['ao_rfa_aistatus'] == 'A') { echo "selected='selected'";}?>>Active</option>
		</select>
		</div>	
		<div class="span1" style="width:100px">Superceding AI</div>	
		<div class="span1" style="width:100px"><input type='text' name='supercedingai' id='supercedingai' value="<?php if ($print['ao_rfa_supercedingai'] != '') { echo $print['ao_rfa_supercedingai']; } else { echo str_pad($lastinv + 1,8,"0",STR_PAD_LEFT); } ?>"></div>	
        
		<div class="clear"></div>
	</div>
	<div class="row-form-booking">	
		<?php 
		$atts = array(
				'width'      => '800',
				'height'     => '600',
				'scrollbars' => 'yes',
				'status'     => 'yes',
				'resizable'  => 'yes',
				'screenx'    => '0',
				'screeny'    => '0',
				'class'      => 'ibw-print'
			   );

		$site = site_url('aiform/pdfRFA/'.$aoptmid);
		if ($canPRINT) :
		?>		
		<div class="span2"><span class="ibw-print" name="rfa_print" id="rfa_print"><?php echo anchor_popup($site, 'Print!', $atts); ?></span></div>        
		<?php 
		endif;
		if (($canRFASAVE && $print['ao_rfa_finalstatus'] == 0) || ($canRFAOVERRIDE && $print['ao_rfa_finalstatus'] == 1)) :
		?>
		<div class="span2"><button class="btn" name="save" id="save" type="button">Save button</button></div>
		<?php 
		endif;
        if ($print['ao_rfa_supercedingai'] == '') {   
		?>
        
        <div class="span2" style="color: red">LAST INVOICE: <?php echo $lastinv ?></div>  
        
        <?php } ?>
	</div>
</div>
<script>
$('#supercedingai').mask('99999999');
$('#signatories').click(function(){
    var $sign = $('#signatories:checked').val();
    
    if ($sign == 1){
        $('#rfastatus').val('C');   
    } else {
        $('#rfastatus').val('');       
    }
});
$('#save').click(function(){
    var ans = confirm('Are you sure you want to save this RFA?.');
    if (ans) {
        
        var $typecode = $('#typecode').val();
        var $findings = $('#findings').val();
        var $possibleadjustment = $('#possibleadjustment').val();
        var $person = $('#person').val();
        var $responsiblename = $('#responsiblename').val();
        var $adjustmentamt = $('#adjustmentamt').val();
        var $signatories = $('#signatories:checked').val();
        var $rfastatus = $('#rfastatus').val();
        var $supercedingai = $('#supercedingai').val();        
        
        $.ajax({
            url: '<?php echo site_url('rfa/ajxsaveRFA')?>',
            type: 'post',
            data: {id: '<?php echo $aoptmid ?>',
                   rfano: '<?php echo @$print['ao_rfa_num'] ?>',     
                   invoiceno: '<?php echo @$print['ao_sinum'] ?>',     
                   typecode: $typecode,    
                   findings: $findings,    
                   possibleadjustment: $possibleadjustment,    
                   person: $person,    
                   responsiblename: $responsiblename,    
                   adjustmentamt: $adjustmentamt,    
                   signatories: $signatories,    
                   rfastatus: $rfastatus,    
                   supercedingai: $supercedingai
                   },
            success: function(response) {
                alert('RFA successfully save');                
                $('#ai_rfa_view').dialog('close');                    
                $.ajax({
                    url: '<?php echo site_url('rfa/searchRFA') ?>',
                    type: 'post',
                    data: { complaint: $(":input[name='complaint']").val(),
                            advertisername: $(":input[name='advertisername']").val(),   
                            agencyname: $(":input[name='agencyname']").val(),   
                            accountexec: $(":input[name='accountexec']").val(),   
                            invoiceno: $(":input[name='invoiceno']").val(),   
                            issuedatefrom: $(":input[name='issuedatefrom']").val(),   
                            issuedateto: $(":input[name='issuedateto']").val(),   
                            rfano: $(":input[name='rfano']").val(),   
                            rfadatefrom: $(":input[name='rfadatefrom']").val(),   
                            rfadateto: $(":input[name='rfadateto']").val(),
                            person: $(":input[name='searchperson']").val(),
                            responsible: $(":input[name='responsible']").val()
                          },
                    success: function(response) {
                        var $response = $.parseJSON(response);
                        
                        $('#searchresult').html($response['searchresult']);    
                    }
                });    
            }     
        }); 
    } 
});

$('#adjustmentamt').autoNumeric();
$('#person').change(function() {
   var person = $(this).val();
   
   $.ajax({
       url: '<?php echo site_url('aiform/ajaxResponsible') ?>',
       type: 'post',
       data: {id: '<?php echo $rfadata['id'] ?>', person: person},
       success: function(response) {
           var $response = $.parseJSON(response);
           
           $('#responsiblename').val($response['responsible']);
       }
       
   })
});
</script>
