<div class="span6">
	<p><span class="label label-info" style="font-size:13px;">Editing Issue Date: <?php echo date("M d, Y", strtotime($data['myissuedate'])); ?></span></p>
</div>
<div class="dr"><span></span></div>
<?php 

$paginate = $data['ao_paginated_status']; 

$readonly = "";
$isflow_readonly = "";
if($paginate == 1) :
$readonly = "readonly='readonly'";
endif; 
$isflow = $data['is_flow'];
if ($isflow != 0) :
$isflow_readonly = "readonly='readonly'";
endif;
?>
<div id="tabs" class="block-fluid" style="margin-top:-5px;">
	<ul>
	<li><a href="#tabs-1">Detailed</a></li>
	<li><a href="#tabs-2">Remarks</a></li>
	<li><a href="#tabs-3">Charges</a></li>
	</ul>
	<div id="tabs-1">
		<div class="row-form-booking">
		   <div class="span1">Classification</div>	
		   <div class="span2">
			<select id="d_classification" name="d_classification">
				<?php if ($paginate != 1) : ?>
				<option value="">...</option>
				<?php endif; ?>
				<?php foreach ($class as $class) :?>
				<?php  if ($class['id'] == $data['classif']) : ?>
				<option value="<?php echo $class['id'] ?>" selected="selected"><?php echo $class['class_name'] ?></option>
				<?php else: if ($paginate != 1) :?>
				<option value="<?php echo $class['id'] ?>"><?php echo $class['class_name'] ?></option>
				<?php endif; endif;?>
				<?php endforeach; ?>			
			</select>
		   </div> 
		   <div class="span1">Sub Class</div>	
		   <div class="span2">
			<select id="d_subclassification" name="d_subclassification">
				<?php if ($paginate != 1) : ?>
				<option value="">...</option>
				<?php endif; ?>
				<?php foreach ($subclass as $subclass) :?>
				<?php  if ($subclass['id'] == $data['subclass']) : ?>
				<option value="<?php echo $subclass['id'] ?>" selected="selected"><?php echo $subclass['class_name'] ?></option>
				<?php else: if ($paginate != 1) :?>
				<option value="<?php echo $subclass['id'] ?>"><?php echo $subclass['class_name'] ?></option>
				<?php endif; endif; ?>
				<?php endforeach; ?>				
			</select>		  
             </div> 
		   <div class="clear"></div>	
		</div>

		<div class="row-form-booking">
		   <div class="span1" style="width:20px;margin-left:5px">Adsize</div>	
		   <div class="span1">
			<select id="d_adsize" name="d_adsize">
				<?php if ($isflow !=0 ): ?>
				<?php foreach ($adsize as $isflow_adsize) :?>
				<?php  if ($isflow_adsize['id'] == $data['adsize']) : ?>
				<option value="<?php echo $isflow_adsize['id'] ?>" selected="selected"><?php echo $isflow_adsize['adsize_code'] ?></option>
				<?php endif; ?>
				<?php endforeach; ?>	
				<?php else : ?>

				<?php if ($paginate != 1) : ?>
				<option value="">...</option>
				<?php endif; ?>
				<?php foreach ($adsize as $adsize) :?>
				<?php  if ($adsize['id'] == $data['adsize']) : ?>
				<option value="<?php echo $adsize['id'] ?>" selected="selected"><?php echo $adsize['adsize_code'] ?></option>
				<?php else: if ($paginate != 1) : ?>
				<option value="<?php echo $adsize['id'] ?>"><?php echo $adsize['adsize_code'] ?></option>
				<?php endif; endif; ?>
				<?php endforeach; ?>	
				<?php endif; ?>			
			</select>
		   </div> 
		   <div class="span1" style="width:20px;">Width</div>	
		   <div class="span1"><input type="text" id="d_width" name="d_width" <?php echo $readonly.' '.$isflow_readonly ?> value="<?php echo $data['width'] ?>"/></div> 
		   <div class="span1" style="width:20px;">Length</div>	
		   <div class="span1"><input type="text" id="d_length" name="d_length" <?php echo $readonly.' '.$isflow_readonly ?> value="<?php echo $data['length'] ?>"/></div> 
		   <div class="span1" style="width:60px;">Total Size</div>	
		   <div class="span1"><input type="text" id="d_totalsize" name="d_totalsize" readonly="readonly" value="<?php echo $data['totalsize'] ?>"/></div> 	
		   <div class="clear"></div>	
		</div>

		<div class="row-form-booking">
		   <div class="span1">Color</div>	
		   <div class="span2">
			<select id="d_color" name="d_color">
				<?php if ($isflow !=0 ): ?>
				<?php foreach ($color as $isflow_color) :?>
				<?php  if ($isflow_color['id'] == $data['color']) : ?>
				<option value="<?php echo $isflow_color['id'] ?>" selected="selected"><?php echo $isflow_color['color_code'] ?></option>
				<?php endif; ?>
				<?php endforeach; ?>	
				<?php else : ?>

				<?php if ($paginate != 1) : ?>
				<option value="">...</option>
				<?php endif; ?>
				<?php foreach ($color as $color) :?>
				<?php  if ($color['id'] == $data['color']) : ?>
				<option value="<?php echo $color['id'] ?>" selected="selected"><?php echo $color['color_code'] ?></option>
				<?php else: if($paginate != 1) :?>
				<option value="<?php echo $color['id'] ?>"><?php echo $color['color_code'] ?></option>
				<?php endif; endif;?>
				<?php endforeach; ?>	
				<?php endif; ?>			
			</select>
		   </div> 
		   <div class="span1">Position</div>	
		   <div class="span2">
			<select id="d_position" name="d_position">
				<?php if ($paginate != 1) : ?>
				<option value="">...</option>
				<?php endif; ?>
				<?php foreach ($position as $position) :?>
				<?php  if ($position['id'] == $data['position']) : ?>
				<option value="<?php echo $position['id'] ?>" selected="selected"><?php echo $position['pos_name'] ?></option>
				<?php else: if($paginate != 1) :?>
				<option value="<?php echo $position['id'] ?>"><?php echo $position['pos_name'] ?></option>
				<?php endif; endif;?>
				<?php endforeach; ?>			
			</select>
		   </div> 
		   <div class="clear"></div>	
		</div>

		<div class="row-form-booking">
		   <div class="span1">Page Min</div>	
		   <div class="span1"><input type="text" id="d_pagemin" name="d_pagemin" <?php echo $readonly ?> value="<?php echo $data['pagemin'] ?>"/></div> 
		   <div class="span1">Page Max</div>	
		   <div class="span1"><input type="text" id="d_pagemax" name="d_pagemax" <?php echo $readonly ?> value="<?php echo $data['pagemax'] ?>"/></div>
		   <div class="clear"></div>	
		</div>
	</div>
	<div id="tabs-2">
			<div class="row-form-booking">
			   <div class="span1">Billing</div>	
			   <div class="span5"><input type="text" id="d_billing" name="d_billing" value="<?php echo $data['billing'] ?>"/></div> 
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span1">Records</div>	
			   <div class="span5"><input type="text" id="d_records" name="d_records" value="<?php echo $data['records'] ?>"/></div> 
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span1">Production</div>	
			   <div class="span5"><input type="text" id="d_production" name="d_production" value="<?php echo $data['production'] ?>"/></div> 
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span1">Follow-up</div>	
			   <div class="span5"><input type="text" id="d_followup" name="d_followup" value="<?php echo $data['followup'] ?>"/></div> 
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span1">EPS / M. Ver</div>	
			   <div class="span5"><input type="text" id="d_eps" name="d_eps" value="<?php echo $data['eps'] ?>"/></div> 
			   <div class="clear"></div>	
			</div>
	</div>
	<div id="tabs-3">	
			<div class="row-form-booking">
			   <div class="span6">       
			   <?php 
			   $newmisc = "";
			   foreach ($misccharges as $misccharges) : 	               
			   	$newmisc .= "'".$misccharges['adtypecharges_code']."',";	
			   endforeach; 
			   $valuemisc = "";
			   for ($i = 1; $i <=6; $i++) {
				if ($data['mischarge'.$i] != "") :
					$valuemisc .= $data['mischarge'.$i].",";	
				endif;												
			   }
                  ?>
			   </div>	
			   <input type="hidden" id="d_misc_charges" <?php echo $readonly ?> name="d_misc_charges" style="width:100%" value="<?php echo substr($valuemisc, 0, -1); ?>"/>	
			   <div class="clear"></div>		  
			</div>
			<div class="row-form-booking">
			   <div class="span1"><input type="text" id="d_misc1" name="d_misc1" style="font-size:10px" readonly="readonly" value="<?php echo $data['mischarge1'] ?>"/></div>
			   <div class="span1"><input type="text" id="d_misc2" name="d_misc2" style="font-size:10px" readonly="readonly" value="<?php echo $data['mischarge2'] ?>"/></div>  
			   <div class="span1"><input type="text" id="d_misc3" name="d_misc3" style="font-size:10px" readonly="readonly" value="<?php echo $data['mischarge3'] ?>"/></div> 
			   <div class="span1"><input type="text" id="d_misc4" name="d_misc4" style="font-size:10px" readonly="readonly" value="<?php echo $data['mischarge4'] ?>"/></div> 
			   <div class="span1"><input type="text" id="d_misc5" name="d_misc5" style="font-size:10px" readonly="readonly" value="<?php echo $data['mischarge5'] ?>"/></div> 
			   <div class="span1"><input type="text" id="d_misc6" name="d_misc6" style="font-size:10px" readonly="readonly" value="<?php echo $data['mischarge6'] ?>"/></div> 
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span1"><input type="text" id="d_miscper1" name="d_miscper1" readonly="readonly" value="<?php echo $data['mischargepercent1'] ?>" style="text-align:right;"/></div>
			   <div class="span1"><input type="text" id="d_miscper2" name="d_miscper2" readonly="readonly" value="<?php echo $data['mischargepercent2'] ?>" style="text-align:right;"/></div>  
			   <div class="span1"><input type="text" id="d_miscper3" name="d_miscper3" readonly="readonly" value="<?php echo $data['mischargepercent3'] ?>" style="text-align:right;"/></div> 
			   <div class="span1"><input type="text" id="d_miscper4" name="d_miscper4" readonly="readonly" value="<?php echo $data['mischargepercent4'] ?>" style="text-align:right;"/></div> 
			   <div class="span1"><input type="text" id="d_miscper5" name="d_miscper5" readonly="readonly" value="<?php echo $data['mischargepercent5'] ?>" style="text-align:right;"/></div> 
			   <div class="span1"><input type="text" id="d_miscper6" name="d_miscper6" readonly="readonly" value="<?php echo $data['mischargepercent6'] ?>" style="text-align:right;"/></div> 
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span1">Total Prem</div>	
			   <div class="span2"><input type="text" id="d_totalprem" name="d_totalprem" readonly="readonly" value="<?php echo $data['surcharge'] ?>" style="text-align:right;"/></div> 
			   <div class="span1">Total Disc</div>	
			   <div class="span2"><input type="text" id="d_totaldisc" name="d_totaldisc" readonly="readonly" value="<?php echo $data['discount'] ?>" style="text-align:right;"/></div>   	
			   <div class="clear"></div>	
			</div>  	
	</div>
<div>
<div class="dr" style="margin-top:-10px"><span></span></div>
<div class="span6" style="margin-top:-10px">
	<div class="row-form-booking">
	   <?php if ($data['ao_sinum'] == 0) : ?>                           
        <div class="span2">
            <button class="btn btn-block" type="button" id="update_detailed" name="update_detailed">Update Detailed</button>
        </div>	
	   <?php else: ?>
	   <div class="span5"> 
        	  <p class="text-error">Has invoice already no access to update nor override!</p>
	   </div>	
	   <?php endif; ?>  
	   <?php if ($data['ao_paginated_status'] == 1 && $data['ao_sinum'] == 0) : ?>
	   <div class="span3"> 
        	  <p class="text-error">Already paginated no access to override!</p>
	   </div>	  
	   <?php else: ?> 	
	   <?php if ($canBOOKINGOVERRIDE && $data['ao_paginated_status'] != 1 && $data['ao_sinum'] == 0) : ?>
        <div class="span1" style="width:100px">
            <input type="text" style="text-align:right" id="override_amt" name="override_amt"/>
        </div>                                
        <div class="span1">
            <button class="btn btn-block btn-danger" style="width: 100px;" type="button" id="override_cost" name="override_cost">Override Cost</button>
        </div> 
        <?php if ($paginatedTransac <= 0 &&  abs($data['ao_num']) != 0) : ?>   
        <div class="span1">
            <button class="btn btn-block btn-danger" style="width: 100px;" type="button" id="override_costall" name="override_costall">Override All</button>
        </div> 
        <?php endif; ?>                         
	   <?php else: ?>
	   <div class="span3"> 
        	  <p class="text-error">You have no access to override an amount!</p>
	   </div>	
	   <?php endif; ?>                             
	   <?php endif; ?>   
        <div class="clear"></div>
    </div>   
</div>
<div class="dr"><span></span></div>			
<div class="span6" style="margin-top:15px">
	<div class="span5" style="width:520px;">                    
		<div class="wBlock red">                        
		    <div class="dSpace">
		        <h3>Amount Due</h3>
		        <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--130,190,260,230,290,400,340,360,390--></span>
		        <span class="number" id="d_amountdue"><?php echo number_format($data['amtdue'], 2, ".", ","); ?></span>
		    </div>
		    <div class="rSpace">
			   <span class="number" id="d_computedamt"><?php echo number_format($data['computedamt'], 2, ".", ","); ?> Computed Amount</span>
			   <span class="number" id="d_totalcost"><?php echo number_format($data['totalcost'], 2, ".", ","); ?> Total Cost</span>
			   <span class="number" id="d_agencycom"><?php echo number_format($data['agencycom'], 2, ".", ","); ?> Agency Comm.</span>
			   <span class="number" id="d_vatamt"><?php echo number_format($data['vatamt'], 2, ".", ","); ?> VAT Amount</span>
		    </div>                          
		</div>                     		
	 </div>       		
</div>
<script>
$("#update_detailed").click(function(){
	var ans = confirm("Are you sure you want to update the detailed transaction!?");
	if (ans) {

	var $classification = $('#d_classification').val();
	var $subclassification = $('#d_subclassification').val();
	var $width = $('#d_width').val();
	var $length = $('#d_length').val();
	var $misc1 = $('#d_misc1').val();
	var $misc2 = $('#d_misc2').val();
	var $misc3 = $('#d_misc3').val();
	var $misc4 = $('#d_misc4').val();
	var $misc5 = $('#d_misc5').val();
	var $misc6 = $('#d_misc6').val();
	var $miscper1 = $('#d_miscper1').val();
	var $miscper2 = $('#d_miscper2').val();
	var $miscper3 = $('#d_miscper3').val();
	var $miscper4 = $('#d_miscper4').val();
	var $miscper5 = $('#d_miscper5').val();
	var $miscper6 = $('#d_miscper6').val();

	var $eps = $('#d_eps').val();
	var $color = $('#d_color').val();
	var $position = $('#d_position').val();
	var $records = $('#d_records').val();
	var $production = $('#d_production').val();
	var $followup = $('#d_followup').val();
	var $billing = $('#d_billing').val();
	var $adsize = $('#d_adsize').val();
    var $totalsize = $('#d_totalsize').val();
	var $paytype = $('#paytype').val();


	var $totalprem = $('#d_totalprem').val();
	var $totaldisc = $('#d_totaldisc').val();

	var $pagemin = $('#d_pagemin').val();
	var $pagemax = $('#d_pagemax').val();

	var $rateamt = "<?php echo $data['rateamt'] ?>";

	var $duepercent = "<?php echo $duepercent ?>";	
	var $vatcode = "<?php echo $vatcode ?>";

		$.ajax({
			url: "<?php echo site_url('bookingissue/updatedetailed') ?>",
			type: "post",
			data: {
				classification: $classification,                      
				subclassification: $subclassification,                                         
				width: $width,                      
				length: $length,                      
				misc1: $misc1,                      
				misc2: $misc2,
				misc3: $misc3,
				misc4: $misc4,
				misc5: $misc5,
				misc6: $misc6,
				miscper1: $miscper1,                      
				miscper2: $miscper2,
				miscper3: $miscper3,
				miscper4: $miscper4,
				miscper5: $miscper5,
				miscper6: $miscper6,				
				totalprem: $totalprem,
				totaldisc: $totaldisc, 	
				pagemin: $pagemin,
				pagemax: $pagemax,
				eps: $eps,
				color: $color,
				position: $position,
				records: $records,
				production: $production,
				followup: $followup,
				billing: $billing,
				adsize: $adsize,
				totalsize: $totalsize,
                rateamt: $rateamt,
			    duepercent: $duepercent,
				vatcode: $vatcode,
				issuedate: "<?php echo $issuedate; ?>", 
				mykeyid: "<?php echo $mykeyid; ?>",
				type: "<?php echo $type; ?>",
				product: "<?php echo $product; ?>",
                paytype: $paytype
                     },
			success: function(response) {
				$response = $.parseJSON(response);	
				$("#d_totalcost").html($response['totalcost']);			
				$("#d_agencycom").html($response['agencycom']);	
				$("#d_vatamt").html($response['vatamt']);	
				$("#d_amountdue").html($response['amtdue']);	

				$('.date_list').html($response['issuedate_data']);
				$('#computedamount').val($response['total_computedamt']);
				$('#totalcost').val($response['total_totalcost']);			
				$('#agencycomm').val($response['total_agencycom']);			
				$('#netvatsales').val($response['total_nvs']);			
				$('#vatexempt').val($response['total_vatexempt']);			
				$('#vatzero').val($response['total_vatzerorate']);			
				$('#vatableamt').val($response['total_vatamt']);			
				$('#amountdue').val($response['total_amtdue']);	

				$('#issuedate_view').dialog('close');
			}
		});
	}
});
$("#override_amt").autoNumeric({});  
$("#override_cost").click(function() {
	var ans = confirm("Are you sure you want to override the amount!?");
	if (ans) {
		var $override_amt = $("#override_amt").val();
		if ($override_amt == "") {
			alert("Override Amount must not be empty!");	return false;	
		} 
		var $duepercent = "<?php echo $duepercent ?>";	
		var $vatcode = "<?php echo $vatcode ?>";
		$.ajax({
			url: "<?php echo site_url('bookingissue/overrideamt') ?>",
			type: "post",
			data: {override_amt: $override_amt, duepercent: $duepercent, vatcode: $vatcode,
                      issuedate: "<?php echo $issuedate; ?>", mykeyid: "<?php echo $mykeyid; ?>",
				  type: "<?php echo $type; ?>", product: "<?php echo $product; ?>" },
			success:function(response) {
				$response = $.parseJSON(response);	
				$("#override_amt").val("");			
				$("#d_totalcost").html($response['totalcost']);			
				$("#d_agencycom").html($response['agencycom']);	
				$("#d_vatamt").html($response['vatamt']);	
				$("#d_amountdue").html($response['amtdue']);	

				$('.date_list').html($response['issuedate_data']);
				$('#computedamount').val($response['total_computedamt']);
				$('#totalcost').val($response['total_totalcost']);			
				$('#agencycomm').val($response['total_agencycom']);			
				$('#netvatsales').val($response['total_nvs']);			
				$('#vatexempt').val($response['total_vatexempt']);			
				$('#vatzero').val($response['total_vatzerorate']);			
				$('#vatableamt').val($response['total_vatamt']);			
				$('#amountdue').val($response['total_amtdue']);		
				
				$('#issuedate_view').dialog('close');
			}
		});
	} return false;
});

$("#override_costall").click(function() {
    var ans = confirm("Are you sure you want to override all the amount!?");
    if (ans) {
        var $override_amt = $("#override_amt").val();
        if ($override_amt == "") {
            alert("Override Amount must not be empty!");    return false;    
        } 
        var $duepercent = "<?php echo $duepercent ?>";    
        var $vatcode = "<?php echo $vatcode ?>";
        $.ajax({
            url: "<?php echo site_url('bookingissue/overrideall') ?>",
            type: "post",
            data: {override_amt: $override_amt, duepercent: $duepercent, vatcode: $vatcode,
                      issuedate: "<?php echo $issuedate; ?>", mykeyid: "<?php echo $mykeyid; ?>",
                  type: "<?php echo $type; ?>", product: "<?php echo $product; ?>" },
            success:function(response) {
                $response = $.parseJSON(response);    
                $("#override_amt").val("");            
                $("#d_totalcost").html($response['totalcost']);            
                $("#d_agencycom").html($response['agencycom']);    
                $("#d_vatamt").html($response['vatamt']);    
                $("#d_amountdue").html($response['amtdue']);    

                $('.date_list').html($response['issuedate_data']);
                $('#computedamount').val($response['total_computedamt']);
                $('#totalcost').val($response['total_totalcost']);            
                $('#agencycomm').val($response['total_agencycom']);            
                $('#netvatsales').val($response['total_nvs']);            
                $('#vatexempt').val($response['total_vatexempt']);            
                $('#vatzero').val($response['total_vatzerorate']);            
                $('#vatableamt').val($response['total_vatamt']);            
                $('#amountdue').val($response['total_amtdue']);        
                
                $('#issuedate_view').dialog('close');
            }
        });
    } return false;
});
$('#d_width, #d_length').keyup(function(){    
    $w = parseFloat($('#d_width').val());
    $l = parseFloat($('#d_length').val());    
    $total = $w * $l;     
    if (isNaN($total)) { $total = 0; }       
    $('#d_totalsize').val($total.toFixed(2));    
}).keyup(); 

$("#d_adsize").change(function(){
	$.ajax({
	   url: "<?php echo site_url('adsize/ajaxSize') ?>",
	   type: 'post',
	   data: {adsize : $(":input[name='d_adsize']").val()},
	   success: function(response) {
		  var $response = $.parseJSON(response);
		  $('#d_width').val($response['adsize']['adsize_width']);
		  $('#d_length').val($response['adsize']['adsize_length']);    
		  $w = parseFloat($response['adsize']['adsize_width']);
		  $l = parseFloat($response['adsize']['adsize_length']);
		  $total = $w * $l;  
		  if (isNaN($total)) { $total = 0; }                 
		  $('#d_totalsize').val($total.toFixed(2));  
	   } 
	});                 
});
$("#tabs").tabs({});
$("#d_misc_charges").select2({maximumSelectionSize: 6,tags:[<?php echo substr($newmisc, 0, -1); ?>]});
$("#d_misc_charges").change(function(){ 
	var $charges = $("#d_misc_charges").val();
	var $product = "<?php echo $product; ?>";
	var $classification = "<?php echo $data['classif'] ?>";
	if ($charges == null) {
		$("#d_misc1").val('');$("#d_miscper1").val('');
		$("#d_totalprem").val('');$("#d_totaldisc").val('');
		return false;		
	}
	if ($product != "" && $classification != "") {
		$.ajax({
			url: "<?php echo site_url('booking/ajaxmisc_charges') ?>",
			type: "post",
			data: {charges: $charges, product: $product, classification: $classification, type: "<?php echo $type; ?>"},
			success: function(response) {
				$response = $.parseJSON(response);
				$("#d_misc1").val($response['misc1']);$("#d_misc2").val($response['misc2']);$("#d_misc3").val($response['misc3']);					
				$("#d_misc4").val($response['misc4']);$("#d_misc5").val($response['misc5']);$("#d_misc6").val($response['misc6']);					
				$("#d_miscper1").val($response['miscper1']);$("#d_miscper2").val($response['miscper2']);$("#d_miscper3").val($response['miscper3']);					
				$("#d_miscper4").val($response['miscper4']);$("#d_miscper5").val($response['miscper5']);$("#d_miscper6").val($response['miscper6']);					
				$("#d_totalprem").val($response['totalprem']);$("#d_totaldisc").val($response['totaldisc']);
			}
		});
	}else { $('#warning_msg').html('Product, Classification must not be empty!').dialog('open'); $("#misc_charges").select2('data', null); return false; }
});
</script>

