<style>
.highlight{overflow:hidden;color:#051D2D!important}
.highlight a{background:#EB8F00!important;}
</style>
<div id="calendarpicker"></div>
<div id="warning_msg" title="Warning"></div>
<div id="production_view" title="Production Remarks"></div>
<div id="issuedate_view" title="Issue Date Edit"></div>
<div id="supercedissuedate_view" title="Superced Issue Date Edit"></div>
<div id="classifiedsline_view" title="Classifieds Line Editor"></div>
<div id="lookup_view" title="Ad Order Booking Searching"></div>

<script>
$(function() {
	var myDays = new Array();  
	var $uri = "<?php echo $this->uri->segment(2); ?>";
	//var myDays = ['2013/03/02', '2013/03/13'];

	$("#action_lookupbooking").click(function() {		
		$.ajax({
			url: "<?php echo site_url('booking/lookup_view') ?>",
			type: "post",
			data: {type: "<?php echo $type ?>"},
			success:function(response) {
				$response = $.parseJSON(response);
				$('#lookup_view').html($response['lookupview']).dialog("open");
			}
		});		
	});
	

	$('#lookup_view').dialog({
		autoOpen: false, 
		closeOnEscape: false,
		draggable: true,
		width: 560,    
		height:600,
		modal: true,
		resizable: false
	});

	if ($uri == "load_booking") {
		$.ajax({
			url: "<?php echo site_url('bookingissue/refreshCalendarIssuedate') ?>",
			type: "post",
			data: {mykeyid: $('#mykeyid').val()},
			success:function(response) {
				$response = $.parseJSON(response);
				$.each($response['datelist'],function(i) {
					var item = $response['datelist'][i];  
					myDays.push(item['datepickerdate']);
				});  
				jQuery("#calendarpicker").datepicker("refresh");	
			}
		});
	}
	
	
	
	$("#prod_remarks_btn").click(function() {
		
		var $prodremarks = $("#production").val();
		$.ajax({
			url: "<?php echo site_url('booking/productionremarks') ?>",
			type: "post",
			data: {prodremarks: $prodremarks},
			success: function(response) {
				$response = $.parseJSON(response);
							
				$('#production_view').html($response['production_remarks_view']).dialog('open');
			}
		});
	});

	$('#classifiedsline_view').dialog({
		autoOpen: false, 
		closeOnEscape: false,
		draggable: true,
		width: 800,    
		height: 'auto',
		modal: true,
		resizable: false
	});
	
	$('#production_view').dialog({
		autoOpen: false, 
		closeOnEscape: false,
		draggable: true,
		width: 450,    
		height:400,
		modal: true,
		resizable: false
	});
	
	$('#issuedate_view, #supercedissuedate_view').dialog({
		autoOpen: false, 
		closeOnEscape: false,
		draggable: true,
		width: 670,    
		height: 'auto',
		modal: true,
		resizable: false
	});

	$('#warning_msg').dialog({
		autoOpen: false, 
		closeOnEscape: false,
		draggable: true,
		width: 400,    
		height:150,
		modal: true,
		resizable: false
	});      
	
	var myDays = []; 	
	jQuery("#calendarpicker").datepicker("refresh");	
	$('#calendarpicker').datepicker({            
		dateFormat: 'yy-mm-dd',             
		<?php if ($canANTIDATE != 1) :?>
	     minDate: new Date(<?php echo date('Y') ?>, <?php echo date('m') ?> - 1, <?php echo date('d') ?>),               
		<?php endif; ?>
		beforeShowDay: coloredSelectedDate,    
		onSelect: function(dateText, inst) {
		  selectIssueDate(dateText, inst)
		}
	}); 
	
	function coloredSelectedDate(date) {

	   for (var i = 0; i < myDays.length; i++) {
		if (new Date(myDays[i]).toString() == date.toString()) {
		   return [true, 'highlight', '']; 
		}
	   }	   
	   return [<?php echo $daysofissue ?>, '' ]; 
	   return [true, '']; // return true not highlight        
	}				
	
	function selectIssueDate(dateText, inst) {  
        
        //var $agecycom = $('#duepercent').val();

		var $product = $('#product').val();
		var $classification = $('#classification').val();
		var $subclassification = $('#subclassification').val();
		var $ratecode = $('#ratecode').val();
		var $width = $('#width').val();
		var $length = $('#length').val();
		var $vatcode = $('#vatcode').val();	
		var $misc1 = $('#misc1').val();
		var $misc2 = $('#misc2').val();
		var $misc3 = $('#misc3').val();
		var $misc4 = $('#misc4').val();
		var $misc5 = $('#misc5').val();
		var $misc6 = $('#misc6').val();
		var $miscper1 = $('#miscper1').val();
		var $miscper2 = $('#miscper2').val();
		var $miscper3 = $('#miscper3').val();
		var $miscper4 = $('#miscper4').val();
		var $miscper5 = $('#miscper5').val();
		var $miscper6 = $('#miscper6').val();

		var $eps = $('#eps').val();
		var $color = $('#color').val();
		var $position = $('#position').val();
		var $records = $('#records').val();
		var $production = $('#production').val();
		var $followup = $('#followup').val();
		var $billing = $('#billing').val();
		var $adsize = $('#adsize').val();
		var $totalsize = $('#totalsize').val();

		var $totalprem = $('#totalprem').val();
		var $totaldisc = $('#totaldisc').val();

		var $pagemin = $('#pagemin').val();
		var $pagemax = $('#pagemax').val();

		var $paytype = $('#paytype').val();
		var $duepercent = $('#duepercent').val();
	
		if ($product != "" && $classification != "" && $ratecode != "" && $width != "" && $length != "" && $vatcode != "" && $paytype != "") {
			var $aonum = "<?php echo $aonum; ?>";			
			$.ajax({
				url: "<?php echo site_url('bookingissue/validateFlow_Paginate') ?>",
				type: "post",
				data: {aonum: $aonum, date: dateText},
				success:function(response) {
					$response = $.parseJSON(response);
					if($response['isflow'] == 'y') {
						alert('This issue date has been dummied already please call desk to unflow first before proceeding to undate!.');
						return false;
					 } else if ($response['paginated'] == 1) {
						alert('This issue date has been paginated already!.');
						return false;    
					 }
					$.ajax({
						url: "<?php echo site_url('bookingissue/selectedDate') ?>",
						type: "post",
						data: {dateText: dateText,
							  mykeyid: $('#mykeyid').val(),
							  type: "<?php echo $type ?>",
							  product: $product,                      
							  classification: $classification,                      
							  subclassification: $subclassification,                      
							  ratecode: $ratecode,                      
							  width: $width,                      
							  length: $length,                      
							  vatcode: $vatcode,
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
							  duepercent: $duepercent,
							  paytype: $paytype,
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
							  totalsize: $totalsize
							 },
						success:function(response) {
							$response = $.parseJSON(response);

							$('.date_list').html($response['issuedate_data']);
							$('#startdate').val($response['startdate']);				 
							$('#enddate').val($response['enddate']);	
							$('#totalissueno').val($response['totalissueno']);

							$('#computedamount').val($response['total_computedamt']);
							$('#totalcost').val($response['total_totalcost']);			
							$('#agencycomm').val($response['total_agencycom']);			
							$('#netvatsales').val($response['total_nvs']);			
							$('#vatexempt').val($response['total_vatexempt']);			
							$('#vatzero').val($response['total_vatzerorate']);			
							$('#vatableamt').val($response['total_vatamt']);			
							$('#amountdue').val($response['total_amtdue']);
	
							if ($response['totalissueno'] == 0 ){
                                
                                $("#agency").show(); $("#agency_dummy").hide().empty();
                                $("#adtype").show(); $("#adtype_dummy").hide().empty();
								$("#paytype").show(); $("#paytype_dummy").hide().empty();
								$("#product").show(); $("#product_dummy").hide().empty();
								$("#classification").show(); $("#classification_dummy").hide();
								$("#subclassification").show(); $("#subclassification_dummy").hide();
								$("#ratecode").show(); $("#ratecode_dummy").hide().empty();
								$("#vatcode").hide(); $("#vatcode_dummy").hide().empty();
							} else {
                                //$("#vatcodedum").hide().empty();   
                                $("#agency").hide(); $("#agency_dummy").show().empty().append("<option>"+$("#agency option:selected").text()+"</option>");                        
                                $("#adtype").hide(); $("#adtype_dummy").show().empty().append("<option>"+$("#adtype option:selected").text()+"</option>");                        
								$("#paytype").hide(); $("#paytype_dummy").show().empty().append("<option>"+$("#paytype option:selected").text()+"</option>");						
								$("#product").hide(); $("#product_dummy").show().empty().append("<option>"+$("#product option:selected").text()+"</option>");						
								$("#classification").hide(); $("#classification_dummy").show().empty().append("<option>"+$("#classification option:selected").text()+"</option>");
								$("#subclassification").hide(); $("#subclassification_dummy").show().empty().append("<option>"+$("#subclassification option:selected").text()+"</option>");												
								$("#ratecode").hide(); $("#ratecode_dummy").show().empty().append("<option>"+$("#ratecode option:selected").text()+"</option>");						
								$("#vatcode").hide(); $("#vatcode_dummy").hide();//$("#vatcode_dummy").show().empty().append("<option>"+$("#vatcode option:selected").text()+"</option>");						
							}
												
							if ($response['datelist'] == 0 || $response['datelist'] == null) {
							myDays = new Array();
							} else {
							myDays = new Array();                                         
								$.each($response['datelist'],function(i) {
								var item = $response['datelist'][i];  
								myDays.push(item['datepickerdate']);
								});  
							}
							jQuery("#calendarpicker").datepicker("refresh");		 			 
						} 
					});
				}
			});				
		} else { $('#warning_msg').html('Product, Classification, Pay type, Rate Code, Width, Length, Vat Code must not be empty!').dialog('open'); return false; }
	}

	
});
</script>
