<div class="workplace">

	<div class="row-fluid">
	 
	 <div class="span3">
		<div class="head">
		    <div class="isw-cloud"></div>
		    <h1>Invoicing</h1>
		    <div class="clear"></div>
		</div>
		<div class="block-fluid">                        
			<div id="tabs">
				<ul>
				<li><a href="#auto" id="autotable">Automatic Invoicing</a></li>
				<li><a href="#manual" id="manualtable">Single Invocing</a></li>
				</ul>
			<div id="auto">
				<div class="row-form">
					<div class="span4"><b>Issue Date</b></div>
					<div class="span5"><input type='text' name='paginateissuedate' id='paginateissuedate'></div>
					<div class="clear"></div>
				</div>
				<div class="row-form">
					<div class="span3"><b>Adtype</b></div>
					<div class="span4"><input type='radio' style='width:20px' name='adtype' class='paytype' id='adtype' checked='checked' value='D'>Display</div>
					<div class="span5"><input type='radio' style='width:20px' name='adtype' class='paytype' id='adtype' value='C'>Classifieds</div>
					<div class="clear"></div>
				</div>    
				<div class="row-form">
					<div class="span3"><b>Paytpe</b></div>
					<div class="span5"><input type='radio' style='width:20px' name='paytype' class='paytype' id='paytype' checked='checked' value='1'>Billable Ad</div>
					<div class="span4"><input type='radio' style='width:20px' name='paytype' class='paytype' id='paytype' value='2'>PTF Ad</div>
					<div class="clear"></div>
				</div>    
				<div class="row-form">
					<div class="span6"><button class="btn btn-success" type="button" name="auto_tag" id="auto_tag">Tag button</button></div>
					<div class="span6"><button class="btn btn-danger" type="button" name="auto_clear" id="auto_clear">Clear button</button></div>
					<div class="clear"></div>
				</div>    
				<div class="row-form-booking">
					<div class="span4"><b>Start Invoice</b></div>
					<div class="span5"><input type='text' style='text-align:right' name='auto_startinvoice' id='auto_startinvoice' value="<?php echo str_pad($lastinv + 1,8,"0",STR_PAD_LEFT); ?>" readonly="readonly"></div>
					<div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span4"><b>Invoice Date</b></div>
					<div class="span5"><input type='text' name='invoicingdate' id='invoicingdate'></div>
					<div class="clear"></div>
				</div>    
				<div class="row-form">
					<div class="span12"><button class="btn btn-success" type="button" name="process_autoinvoice" id="process_autoinvoice">Process Automatic Invoicing button</button></div>					
					<div class="clear"></div>
				</div>
			</div>
			<div id="manual">
				<div class="row-form">
					<div class="span4"><b>Issue Date</b></div>
					<div class="span5"><input type='text' placeholder="From" name='manualissuedatefrom' id='manualissuedatefrom'></div>
					<div class="clear"></div>
				</div>
				<div class="row-form">
					<div class="span4"><b>Issue Date</b></div>
					<div class="span5"><input type='text' placeholder="To" name='manualissuedateto' id='manualissuedateto'></div>
					<div class="clear"></div>
				</div>
				<div class="row-form">
					<div class="span4"><b>Filtering</b></div>
					<div class="span5">
					    <select name='manualfilter' id='manualfilter'>
		                       <option value=''>All</option>
		                       <option value='1'>W/O Invoice</option>
		                       <option value='2'>With Invoice</option>
		                   </select> 
					</div>
					<div class="clear"></div>
				</div>
				<div class="row-form">
					<div class="span12"><button class="btn btn-success" type="button" name="retrieve_invoice" id="retrieve_invoice">Retrieve Invoice List button</button></div>					
					<div class="clear"></div>
				</div>   
			</div>
			</div>
		</div>
        <div class="wBlock red">                        
                <div class="dSpace">
                    <h3>Last Invoice Number</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--130,190,260,230,290,400,340,360,390--></span>
                    <span class="number" id="lastinvno" style="font-size:20px"><?php echo $lastinv ?></span>                    
                </div>
                <div class="rSpace">
                    <span><b>Double check invoice no.</b></span>
                    <span><b>Double check invoice date</b></span>
                    <span><b></b></span>
                </div>                          
            </div>
	 </div>
	 
	 <div class="span9">
		<div class="head">
		    <div class="isw-ok"></div>
		    <h1>Data Results</h1>
            
		    <div class="clear"></div>
		</div>    
        <div id="prevTempInvoice" style="min-height: 550px;background-color: #FFFFFF;"></div>                                    
        <div id="prevInvoice" style="min-height: 550px;background-color: #FFFFFF;"></div>                                    
	 </div>
		            
	</div>

</div>
<div id='manualview' title='Single Invoicing'></div>
<div id='autoview' title='Automatic Invoicing Results'></div>

<script>

$("#manualtable").click(function(){
	$("#prevTempInvoice").hide();
	$("#prevInvoice").show();	
});
$("#autotable").click(function(){
	$("#prevInvoice").hide();
	$("#prevTempInvoice").show();	
});
var errorcssobj = {'background': '#E1CECE', 'border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#FFFFFF', 'border' : '1px solid #CCCCCC'}; 
$('#manualview').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: 350,    
    height: 300,
    resizable: false,
    modal: true
});

$('#autoview').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: '70%',    
    height: 'auto',
    resizable: false,
    modal: true
});

//$('#auto_startinvoice').mask('99999999');
$('#auto_startinvoice').focusout(function() {
    $.ajax({
        url: "<?php echo site_url('invoicing/checkInvoice') ?>",
        type: 'post',
        data: {invoice: $('#auto_startinvoice').val(),},
        success: function(response) {
            var $response = $.parseJSON(response);           
            if ($response == true) {
                alert('This invoice number is already used!');
                $('#auto_startinvoice').val('');
            }
        }        
    });
});

$('#process_autoinvoice').click(function(){
    var ans = confirm ("Are you sure you want to run Auto Invoice?");
    
    if (ans) {
    var countValidate = 0;  
    var validate_fields = ['#auto_startinvoice', '#invoicingdate'];

    for (x = 0; x < validate_fields.length; x++) {
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);    
            countValidate += 1;
        } else {        
            $(validate_fields[x]).css(errorcssobj2);    
        }        
    }
    var chck = Array();  
    if (countValidate == 0) {
        $(".chck:checked").each(function(){chck.push($(this).val())});

        if (chck != "") {
            $.ajax({
                url: "<?php echo site_url('invoicing/doInvoice') ?>",
                type: 'post',
                data: {paginateissuedate: $("#paginateissuedate").val(),
                       adtype: $('#adtype:checked').val(),
                       paytype: $('#paytype:checked').val(),
                       auto_startinvoice: $("#auto_startinvoice").val(),
                       invoicingdate: $("#invoicingdate").val(),
                       chck: chck},
                success: function(response){
                    var $response = $.parseJSON(response);
                    
                    //$('#prevTempInvoice').html($response['prevTempInvoice']);   
                    //$('#lastinvno').text($response['lastinv']);   
                    
                    //alert('Invoice Process Done!');  
                    
                    $('#autoview').html($response['tempresult']).dialog('open');
                    
                    $('.chck').removeAttr('checked');    
                }
            })
        } else { 
            alert('Tick atleast 1 issuedate to invoice!');
        }
    }  
    }          
});
  
$('#auto_tag').click(function(){
    if ($("#paginateissuedate").val() == "") {
        $("#paginateissuedate").css(errorcssobj);
    } else {
        $("#paginateissuedate").css(errorcssobj2);  
        $.ajax({
           url: "<?php echo site_url('invoicing/pretagginginvoice')?>",
           type: 'post',
           data: {paginateissuedate: $("#paginateissuedate").val(),
                  adtype: $('#adtype:checked').val(),
                  paytype: $('#paytype:checked').val()
                 },
           success: function(response) {
                var $response = $.parseJSON(response);
                $('#prevTempInvoice').html($response['prevTempInvoice']);
                $('.adtype').attr('disabled','disabled');  
                $('.paytype').attr('disabled','disabled');  
                $('#auto_tag').attr('disabled','disabled');                  
           }
        });
    }   
});

$('#auto_clear').click(function(){
   $("#paginateissuedate").val(''); 
   $('#prevTempInvoice').empty();   
   $('.chck').removeAttr('checked');   
   $('.adtype').removeAttr('disabled');            
   $('.paytype').removeAttr('disabled');            
   $('#auto_tag').removeAttr('disabled');            
});



$("#paginateissuedate").datepicker({dateFormat: 'yy-mm-dd'});
$("#invoicingdate").datepicker({
    dateFormat: 'yy-mm-dd',
    minDate: new Date(<?php echo $monthend['last_yr'] ?>, <?php echo $monthend['last_mon'] ?> - 1, <?php echo $monthend['last_d']?> + 1)  
});
$("#manualissuedatefrom").datepicker({dateFormat: 'yy-mm-dd'});
$("#manualissuedateto").datepicker({dateFormat: 'yy-mm-dd'});

$('#retrieve_invoice').click(function(){
    var countValidate = 0;  
    var validate_fields = ['#manualissuedatefrom', '#manualissuedateto'];

    for (x = 0; x < validate_fields.length; x++) {
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);    
            countValidate += 1;
        } else {        
            $(validate_fields[x]).css(errorcssobj2);    
        }        
    }

    if (countValidate == 0) {
        $.ajax({
            url: "<?php echo site_url('invoicing/retrieveInvoice') ?>",
            type: 'post',
            data: {manualissuedatefrom: $("#manualissuedatefrom").val(),
                   manualissuedateto: $("#manualissuedateto").val(),
                   manualfilter: $("#manualfilter").val()
                   },
            success: function(response){
                var $response = $.parseJSON(response);
                $('#prevInvoice').html($response['prevInvoice']);                                          
            }
        })
    }            
});

$(document).ready(function()
{
   $("#tabs").tabs();
}); 
</script>
