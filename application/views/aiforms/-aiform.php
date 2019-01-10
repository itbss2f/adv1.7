<input type='hidden' name='indexholder' id='indexholder' value='<?php echo @$indexholder ?>'>
<div class="row-form-booking">   <?php #echo $airesult[$invoice]['invoice']['is_invoiceprint']; ?>
	<div class="span1" style="width:70px"><h6>Invoice No.</h6></div>
	<div class="span2" style="width:70px"><h6><p class="text-info" <?php if (@$airesult[$invoice]['invoice']['is_invoiceprint'] != 0) { echo "style='color: red'"; } ?>><?php echo @$airesult[$invoice]['invoice']['sinum'] ?></p></h6></div>
	<div class="span1" style="width:50px"><h6>Date:</h6></div>
	<div class="span2" style="width:100px"><h6><p class="text-info"><?php echo date("M. d, Y", strtotime(@$airesult[$invoice]['invoice']['date']));?></p></h6></div>
	<div class="span1" style="width:50px"><h6>Type:</h6></div>
	<div class="span2" style="width:80px"><h6><p class="text-info"><?php echo @$airesult[$invoice]['invoice']['type'] ?></p></h6></div>
	<div class="span1" style="width:70px"><h6>Pay Type:</h6></div>
	<div class="span2" style="width:80px"><h6><p class="text-info"><?php echo @$airesult[$invoice]['invoice']['paytype'] ?></p></h6></div>
	<div class="clear"></div>
</div> 
<div class="row-form-booking-form">
	
	<div style="width:35%;float:left">
		<div class="row-form-booking-form">
			<div class="span1" style="width:60px;min-height: 10px;"><b>Advertiser</b></div>
			<div class="span8 span_limit" style="min-height: 10px;"><?php echo "<b>".@$airesult[$invoice]['invoice']['advertisercode'].'</b> '.@$airesult[$invoice]['invoice']['advertisername']; if (@$airesult[$invoice]['invoice']['ao_branch'] == '9') { echo ' / '.@$airesult[$invoice]['invoice']['ao_authorizedby'];} ?></div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking-form">
			<div class="span1" style="width:60px;min-height: 10px;"></div>
			<div class="span8 span_limit" style="min-height: 10px;"><?php echo str_replace('\\','',@$airesult[$invoice]['invoice']['clientadd1']) ?></div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking-form">
			<div class="span1" style="width:60px;min-height: 10px;"></div>
			<div class="span8 span_limit" style="min-height: 10px;"><?php echo str_replace('\\','',@$airesult[$invoice]['invoice']['clientadd2'].' '.@$airesult[$invoice]['invoice']['clientadd3']) ?></div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking-form">
			<div class="span1" style="width:60px;min-height: 10px;"><b>PO #</b></div>
			<div class="span8 span_limit" style="min-height: 10px;"><?php echo @$airesult[$invoice]['invoice']['po']; if (@$airesult[$invoice]['invoice']['ao_paytype'] == "2") { echo " / ".$airesult[$invoice]['invoice']['ao_num']; } echo " ".@$airesult[$invoice]['invoice']['adtype'].' - '.@$airesult[$invoice]['invoice']['branch']; ?></div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking-form">
			<div class="span1" style="width:60px;min-height: 10px;"><b>Tel No</b></div>
			<div class="span8 span_limit" style="min-height: 10px;"><?php echo @$airesult[$invoice]['invoice']['advertisercontact'] ?></div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking-form">
			<div class="span1" style="width:85px;min-height: 10px;"><b>Contact Person</b></div>
			<div class="span8 span_limit" style="width:150px;min-height: 10px;"><?php echo @$airesult[$invoice]['invoice']['advertiserperson'] ?></div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking-form">
			<div class="span1" style="width:60px;min-height: 10px;"><b>TIN</b></div>
			<div class="span8 span_limit" style="min-height: 10px;"><?php echo @$airesult[$invoice]['invoice']['tin'] ?></div>
			<div class="clear"></div>
		</div>
	</div>
    <div style="width:35%;float:left">
        <div class="row-form-booking-form">
            <div class="span1" style="width:60px;min-height: 10px;"><b>Agency</b></div>
            <div class="span8 span_limit" style="min-height: 10px;"><?php echo "<b>".@$airesult[$invoice]['invoice']['agencycode'].'</b> '.@$airesult[$invoice]['invoice']['agencyname'] ?></div>
            <div class="clear"></div>
        </div>
        <div class="row-form-booking-form">
            <div class="span1" style="width:60px;min-height: 10px;"></div>
            <div class="span8 span_limit" style="min-height: 10px;"><?php echo str_replace('\\','',@$airesult[$invoice]['invoice']['agencyadd1']) ?></div>
            <div class="clear"></div>
        </div>
        <div class="row-form-booking-form">
            <div class="span1" style="width:60px;min-height: 10px;"></div>
            <div class="span8 span_limit" style="min-height: 10px;"><?php echo str_replace('\\','',@$airesult[$invoice]['invoice']['agencyadd2'].' '.@$airesult[$invoice]['invoice']['agencyadd3']) ?></div>
            <div class="clear"></div>
        </div>
        <div class="row-form-booking-form">
            <div class="span1" style="width:60px;min-height: 10px;"><b>Acct Exec</b></div>
            <div class="span8 span_limit" style="min-height: 10px;"><?php echo @$airesult[$invoice]['invoice']['agencyae'] ?></div>
            <div class="clear"></div>
        </div>
        <div class="row-form-booking-form">
            <div class="span1" style="width:60px;min-height: 10px;"><b>Remarks</b></div>
            <div class="span8 span_limit" style="min-height: 10px;" title="<?php echo @$airesult[$invoice]['invoice']['remark'] ?>"><?php echo @$airesult[$invoice]['invoice']['remark'] ?></div>
            <div class="clear"></div>
        </div>
        <div class="row-form-booking-form">
            <div class="span1" style="width:60px;min-height: 10px;"><b>Tel. No</b></div>
            <div class="span8 span_limit" style="min-height: 10px;"><?php echo @$airesult[$invoice]['invoice']['agencycontact'] ?></div>
            <div class="clear"></div>
        </div>
        <div class="row-form-booking-form">
            <div class="span1" style="width:85px;min-height: 10px;"><b>Contact Person</b></div>
            <div class="span8 span_limit" style="width:150px;min-height: 10px;"><?php echo @$airesult[$invoice]['invoice']['agencyperson'] ?></div>
            <div class="clear"></div>
        </div>
    </div>
	<div style="width:30%;float:left">
		<div class="row-form-booking-form">
			<div class="span1" style="width:80px;min-height: 10px;"><b>Total Billing</b></div>
			<div class="span6" style="text-align:right;min-height: 10px;"><strong><?php echo number_format(@$airesult[$invoice]['invoice']['grossamt'],2,'.',',') ?></strong></div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking-form">
			<div class="span1" style="width:80px;min-height: 10px;"><b>Due to Agency</b></div>
			<div class="span6" style="text-align:right;min-height: 10px;"><strong><?php echo number_format(@$airesult[$invoice]['invoice']['agycommamt'],2,'.',',') ?></strong></div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking-form">
			<div class="span1" style="width:80px;min-height: 10px;"><b>Net VAT Sales</b></div>
			<div class="span6" style="text-align:right;min-height: 10px;"><strong><?php echo number_format(@$airesult[$invoice]['invoice']['vatsales'],2,'.',',') ?></strong></div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking-form">
			<div class="span1" style="width:80px;min-height: 10px;"><b>Plus <?php echo @$airesult[$invoice]['invoice']['vat_rate'] ?>% VAT</b></div>
			<div class="span6" style="text-align:right;min-height: 10px;"><strong><?php echo number_format(@$airesult[$invoice]['invoice']['vatamt'],2,'.',',') ?></strong></div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking-form">
			<div class="span1" style="width:80px;min-height: 10px;"><b>VAT Zero Rated</b></div>
			<div class="span6" style="text-align:right;min-height: 10px;"><strong><?php echo number_format(@$airesult[$invoice]['invoice']['vatzero'],2,'.',',') ?></strong></div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking-form">
			<div class="span1" style="width:80px;min-height: 10px;"><b>Amount Due</b></div>
			<div class="span6" style="text-align:right;min-height: 10px;"><strong><?php echo number_format(@$airesult[$invoice]['invoice']['amt'],2,'.',',') ?></strong></div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking-form">
			<div class="span1" style="width:80px;min-height: 10px;"></div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
</div> 

<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;min-height:240px;border-top:1px solid #DDDDDD"> 
	<table cellpadding="0" cellspacing="0" style="white-space:nowrap;" class="table" id="tSortable_2">
	   <thead>
			<tr>						
				<th width="20px"></th>
				<th width="60px">AO No#</th>
				<th width="40px">Issue Date</th>
				<th width="150px">Particulars</th>                                    
				<th width="40px">Width</th>       
				<th width="40px">Length</th>       
				<th width="40px">Total Size</th>      
				<th width="40px">Base Total</th>  
				<th width="40px">Prem %</th> 
				<th width="40px">Disc %</th>
				<th width="40px">Total Amount</th>			
			</tr>
	   </thead>
	   <tbody>

		<?php 
        if (!empty($airesult[$invoice]['issuedate'])) :   
		   foreach(@$airesult[$invoice]['issuedate'] as $row) {
				 $baserate = " ";        
				 if ($row['ao_paytype'] == "6" || $row['ao_paytype'] == "OV" || $row['ao_class'] == "152" || $row['ao_class'] == "167") {
				     $baserate = " "; // tested done check with maam ai...
				 } else {     
                 
                    if ($row['ao_computedamt'] != $row['ao_grossamt']) {
                        $baserate = "";           
                    } else {                               
				         if ($row['ao_adtyperate_rate'] > 500) {
				             $baserate = "";    
				         } else {
				             if ($row['ao_class'] == "154" || $row['type'] == "C") {
				                 #$baserate = ($row['runcharge'] / $row['totalsize']); // tested done with maam ai...
				                 $baserate = $row['ao_adtyperate_rate']; // tested done     
				             } else {
				                 #$baserate = $row['runcharge']; // tested done
				                 $baserate = $row['ao_adtyperate_rate']; // tested done
				             }
				         }
                     }
				 }        
		   ?>
		   <tr>						
				<td width="20px" style="line-height: 10px;"><input type="radio" name="issuedate" class="issuedate" value="<?php echo @$row['id'] ?>"></td>
				<td width="60px" style="line-height: 10px;"><?php echo @$row['ao_num'] ?></td>
				<td width="40px" style="line-height: 10px;"><?php echo date('M d, Y', strtotime(@$row['issuedate']));?></td>
				<td width="150px" style="line-height: 10px;"><?php echo @$row['particulars'] ?></td>                                    
				<td width="40px" style="line-height: 10px;"><?php echo @$row['width'] ?></td>       
				<td width="40px" style="line-height: 10px;"><?php echo @$row['length'] ?></td>       
				<td width="40px" style="line-height: 10px;"><?php echo @$row['totalsize'] ?></td>      
				<td width="40px" style="line-height: 10px;"><?php echo @$baserate ?></td>  
				<td width="40px" style="line-height: 10px;"><?php echo @$row['premium'] ?></td> 
				<td width="40px" style="line-height: 10px;"><?php echo @$row['discount'] ?></td>
				<td width="40px" style="line-height: 10px;text-align:right"><?php echo number_format(@$row['amt'],2,'.',',') ?></td>			
			</tr>
		   <?php
		   }
        endif;
		?>

	   </tbody>
    </table>
    <div class="clear"></div>
</div>
<div class="row-form-booking-form" style="padding-left:1px">
	<div class="span2" style="min-height: 10px;"><b>RFA No.</b></div>
	<div class="span2" style="min-height: 10px;"><b>AO No#:</b></div>
	<div class="span2" style="min-height: 10px;"><b>Issue Date:</b ></div>
	<div class="span2" style="min-height: 10px;"><b>Status :</b></div>
    <div class="span2" style="min-height: 10px;"><b>Superceding AI:</b></div>
	<div class="span2" style="min-height: 10px;"><b>PPD Status</b></div>
	<div class="clear"></div>
</div> 
<div class="row-form-booking-form" style="padding-left:1px" id="sheet_footer">
	<div class="span2" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo str_pad(@$airesult[$invoice]['defaultstat']['rfanum'], 8, "0", STR_PAD_LEFT); ?></p></div>
	<div class="span2" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo str_pad(@$airesult[$invoice]['defaultstat']['ao_num'], 8, "0", STR_PAD_LEFT); ?></p></div>
	<div class="span2" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo date('M d, Y', strtotime(@$airesult[$invoice]['defaultstat']['issuedate']));?></p></div>
	<div class="span2" style="min-height: 10px;text-indent:10px;<?php if (@$airesult[$invoice]['defaultstat']['rfa_status'] == 'C') { echo 'color:red'; } else { echo 'color:green'; } ?>">
            											<?php if (@$airesult[$invoice]['defaultstat']['rfa_status'] == 'C') { echo 'Cancelled'; } else { echo 'Active'; } ?>

	</div>
	<div class="span2" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo @$airesult[$invoice]['defaultstat']['rfa_supercedingai'] ?></p></div>
    
    <div class="span2" style="min-height: 10px;text-indent:10px"><p class="text-error"><?php if(@$ppd['stat'] != 0) { echo "PPD ".$ppd['stat']."%"; }  ?></p></div>    
	<div class="clear"></div>
</div> 

<div class="row-form-booking-form" style="padding-left:1px">
    <div class="span4" style="min-height: 10px;"><b>Receive Date</b></div>
    <div class="span4" style="min-height: 10px;"><b>Receive Remarks</b></div>
    <div class="clear"></div>
</div> 
<div class="row-form-booking-form" style="padding-left:1px">
    <div class="span4" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo @$airesult[$invoice]['defaultstat']['invrec'] ?></p></div>  
    <div class="span4" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo @$airesult[$invoice]['defaultstat']['invpart'] ?></p></div>  
    <div class="clear"></div>
</div>

<div class="row-form-booking-form" style="padding-left:1px">    
    <div class="span4" style="min-height: 10px;"><b>Accounting Remarks</b></div>
    <div class="clear"></div>
</div> 
<div class="row-form-booking-form" style="padding-left:1px">
    <div class="span12" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo @$airesult[$invoice]['defaultstat']['ao_ai_remarks'] ?></p></div>      
    <div class="clear"></div>
</div>
<div class="row-form-booking-form" style="padding-left:1px">
    <div class="span4" style="min-height: 10px;"><b>Return Date</b></div>
    <div class="span4" style="min-height: 10px;"><b>Receive By Adv</b></div>
    <div class="span4" style="min-height: 10px;"><b>Receive By Bill</b></div>
    <div class="clear"></div>
</div> 
<div class="row-form-booking-form" style="padding-left:1px">
    <div class="span4" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo @$airesult[$invoice]['defaultstat']['ao_return_inv'] ?></p></div>  
    <div class="span4" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo @$airesult[$invoice]['defaultstat']['ao_dateto_inv'] ?></p></div>  
    <div class="span4" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo @$airesult[$invoice]['defaultstat']['ao_datefrom_inv'] ?></p></div>   
    <div class="clear"></div>
</div>
<div class="row-form-booking-form" style="padding-left:1px">
    <div class="span4" style="min-height: 10px;"><b>INVOICE ATTACHMENT:</b></div>
    <div class="clear"></div>
</div>

<!-- INVOICE UPLOADING -->
<div class="block-fluid table-sorting" style="margin-top: 10px;min-height: 150px;">
		<table cellpadding="0" cellspacing="0" width="100%" class="table">
			<thead>
				<tr>                       
					<th colspan="1" width="3%">Action</th>  
					<th width="20%">File Name</th>  
					<th width="10%">Upload By</th>  
					<th width="10%">Upload Date | Time</th>                               
				</tr>
			</thead>
			<?php if (empty($invoiceupload)) : ?>
			    <tr>
			        <td colspan="4" style="text-align: center; color: red; font-size: 20px;">No Attachment Found</td>
			    </tr>

			<?php endif; ?>
			<?php 
						$atts = array(
			              'width'      => '3000',
			              'height'     => '3000',
			              'scrollbars' => 'yes',
			              'status'     => 'yes',
			              'resizable'  => 'yes',
			              'screenx'    => '0',
			              'screeny'    => '0'
			            );

			            //echo anchor_popup('news/local/123', 'Click Me!', $atts);        
			?>
			<?php foreach ($invoiceupload as $invoiceupload) : ?>
				<tr>
					<td><?php echo anchor_popup('aiform/viewinvoicedatafile/'.$invoiceupload['id'], 'View', $atts) ?></td>
					<td><?php echo $invoiceupload['filename']?></td>
					<td><?php echo $invoiceupload['username']?></td>
					<td><?php echo $invoiceupload['uploaddate']?></td>
				</tr> 
		    <?php endforeach; ?>
    	</table>
    </div> 
    <div class="clear"></div>
</div>
<!-- INVOICE UPLOADING END-->

<script>

$(".delete").click(function () {
    
    var $id = $(this).attr('id');
    var ans = window.confirm("Are you sure you want to delete?")

    if (ans)
    {
    window.location = "<?php echo site_url('aiform/removeDataUpload') ?>/"+$id; 
    return true;
    }
    else
    {
    window.alert("Are you sure you want to cancel?");
    return false;    
    }
    
});

$('#aiform_exdeal').click(function() {
	var aoptmid = $(".issuedate:checked").val();
	if (aoptmid == "" || aoptmid == null) {
		alert('No Issue Date Detailed!.'); return false;
	} else {
		$.ajax({
			url: "<?php echo site_url('aiform/exdealview') ?>",
			type: 'post',
			data: {aoptmid: aoptmid},
			success: function(response) {
				$response = $.parseJSON(response);
				$("#ai_exdeal_view").html($response['exdealview']).dialog('open');
			}
		});	
	}	
});

$(".issuedate").click(function() {
	var aoptmid = $(this).val();
	$.ajax({
		url: "<?php echo site_url('aiform/aistatus') ?>",
		type: 'post',
		data: {aoptmid: aoptmid},
		success:function(response) {
		 $response = $.parseJSON(response);
		 
		 $('#sheet_footer').html($response['aistatus']);    
		}
	});
});

$('#aiform_rfa').unbind('click').click(function(){
    var aoptmid = $(".issuedate:checked").val();
    if (aoptmid == "" || aoptmid == null) {
        alert('No Issue Date Detailed!.'); return false;
    } else {
        
        $.ajax({
            url: "<?php echo site_url('aiform/rfa_view') ?>",
            type: 'post',
            data:{aoptmid:aoptmid},
            success: function(response) {
                var $response = $.parseJSON(response);
                $('#ai_rfa_view').html($response['rfa_index']).dialog('open');      
                               
            }
        });
    }
});

$('#aiform_payment').unbind('click').click(function(){
    var aoptmid = $(".issuedate:checked").val();
    if (aoptmid == "" || aoptmid == null) {
        alert('No Issue Date Detailed!.'); return false;
    } else {
        
        $.ajax({
            url: "<?php echo site_url('aiform/aiform_payment_view') ?>",
            type: 'post',
            data:{aoptmid:aoptmid},
            success: function(response) {
                var $response = $.parseJSON(response);
                $('#ai_payment_view').html($response['ai_payment_view']).dialog('open');                                     
            }
        });
    }
});

$('#aiform_return_invoice').unbind('click').click(function(){    
    var aoptmid = $(".issuedate:checked").val();
    if (aoptmid == "" || aoptmid == null) {
        alert('No Issue Date Detailed!.'); return false;
    } else {
        
         $.ajax({
            url: "<?php echo site_url('aiform/monitoring_return_invoice') ?>",
            type: "post",
            data:{aoptmid:aoptmid}, 
            success:function(response) {
            var $response = $.parseJSON(response); 
               $('#monitoring_return_invoice').html($response['monitoring_return_invoice']).dialog('open');    
            }    
        });        
    }  
    
}); 

$('#aiform_payment2').unbind('click').click(function(){
    var aoptmid = $(".issuedate:checked").val();
    if (aoptmid == "" || aoptmid == null) {
        alert('No Issue Date Detailed!.'); return false;
    } else {
        
        $.ajax({
            url: "<?php echo site_url('aiform/aiform_payment_view2') ?>",
            type: 'post',
            data:{aoptmid:aoptmid},
            success: function(response) {
                var $response = $.parseJSON(response);
                $('#ai_payment_view').html($response['ai_payment_view']).dialog('open');                                     
            }
        });
    }
});


$('#aiform_sinum').unbind('click').click(function(){
    var aoptmid = $(".issuedate:checked").val();
    if (aoptmid == "" || aoptmid == null) {
        alert('No Issue Date Detailed!.'); return false;
    } else {
        
        $.ajax({
            url: "<?php echo site_url('aiform/ai_sinum_view') ?>",
            type: 'post',
            data:{aoptmid:aoptmid},
            success: function(response) {
                var $response = $.parseJSON(response);
                $('#ai_sinum_view').html($response['ai_sinum_view']).dialog('open');                                     
            }
        });
    }
});   


</script>

