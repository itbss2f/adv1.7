<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">
	<div class="row-fluid">

		<div class="span3">
			<div class="head">
			<div class="isw-cloud"></div>
			<h1>Searching</h1>
			<div class="clear"></div>
			</div>
			<div class="block-fluid">                        

				<div class="row-form">
					<div class="span3">Invoice:</div>
					<div class="span5"><input type="text" id="invoicenofrom" name="invoicenofrom"/> <span>Invoice Number From</span></div>
					<div class="clear"></div>
				</div>   

				<div class="row-form">
					<div class="span3">Invoice:</div>
					<div class="span5"><input type="text" id="invoicenoto" name="invoicenoto"/> <span>Invoice Number To</span></div>
					<div class="clear"></div>
				</div>   

				<div class="row-form">
					<div class="span3"></div>
					<div class="span9"><button class="btn" type="button" name="search" id="search">Search button</button></div>
					<div class="clear"></div>
				</div>       

				<div class="row-form" style="background-color:#335A85">
					<div class="span3"><span class="ibw-left_circle" name="nav_first" id="nav_first"></span></div>
					<div class="span3"><span class="ibw-left" name="nav_previous" id="nav_previous"></span></div>
					<div class="span3"><span class="ibw-right" name="nav_next" id="nav_next"></span></div>
					<div class="span3"><span class="ibw-right_circle" name="nav_last" id="nav_last"></span></div>
					<div class="clear"></div>
				</div>  

				<div class="row-form" style="background-color:#335A85">
					<div class="span3">
                        <?php if ($canPRINT) : ?>
                        <span class="ibw-print" name="aiform_print" id="aiform_print" title="Print"></span>
                        <?php endif; ?>
                    </div>
					<div class="span3">
                        <?php if ($canEXDEAL) : ?>     
                        <span class="ibw-list" name="aiform_exdeal" id="aiform_exdeal" title="Exdeal"></span>
                        <?php endif; ?>   
                    </div>
					<div class="span3">
                        <?php if ($canAIRFA) : ?>     
                        <span class="ibw-graph" name="aiform_rfa" id="aiform_rfa" title="RFA"></span>
                        <?php endif; ?>   
                    </div>
					<div class="span3">
                        <?php if ($canVIEWPAYMENT) : ?>     
                        <span class="ibw-calc" name="aiform_payment" id="aiform_payment" title="Payment"></span>
                        <?php endif; ?>   
                    </div>       
					<div class="clear"></div>
				</div>  
                
                <div class="row-form" style="background-color:#335A85">
                    <div class="span3">
                        <span class="ibw-chats" name="aiform_payment2" id="aiform_payment2" title="Payment View All"></span> 
                    </div>
                    <div class="span3" align="center">
                        <?php if ($canEDITINVOICE) : ?>
                        <span class="ibw-settings" name="aiform_sinum" id="aiform_sinum" title="Invoice Data"></span>
                        <?php endif; ?>
                    </div>
                    <div class="span3">
                        <?php if($canRETURNINV) : ?>
                        <span class="ibw-grid" name="aiform_return_invoice" id="aiform_return_invoice" title="Return Invoice"></span> 
                        <?php endif; ?>
                    </div>
                    <div class="span3">
                        <!-- URL to Invoice Uploading -->
                        <a href="<?php echo site_url('aiform/uploading_of_invoicedata')?>" class="ibw-attachment upload" name="aiform_fileupload" id="aiform_fileupload" title="Uploading of Invoice" target="_blank"></a>
                    </div>
                    
                    <div class="clear"></div>
                </div>
               			                                               			    
			</div>
		</div>

		<div class="span9">
			<div class="head">
			    <div class="isw-ok"></div>
			    <h1>Advertising Invoice Form</h1>
			    <div class="clear"></div>
			</div>
			<div class="block-fluid">                        			    
				   
				<div id='aiform_sheet'>
					<?php echo $aiform; ?>    
				</div>               
				   			   
			</div>				                      
		</div>
		       
	</div>

	<div class="dr"><span></span></div>        
    
</div>  
<div id="ai_rfa_view" title="Request For Adjustment"></div>
<div id="ai_exdeal_view" title="Exdeal Tagging"></div>
<div id="ai_payment_view" title="Detailed Payment"></div>
<div id="ai_sinum_view" title="Detailed Invoice Data"></div>
<div id="monitoring_return_invoice" title="Monitoring of Return Invoice"></div>
<script>

$(".upload").click(function () {
    
    //var ans = window.confirm("Are you sure you want to upload invoice?");
    
    if (ans) {
    
        var countValidate = 0;  
        var validate_fields = ['#']; 

        for (x = 0; x < validate_fields.length; x++) {            
            if($(validate_fields[x]).val() == "") {                        
                $(validate_fields[x]).css(errorcssobj);          
                  countValidate += 1;
            } else {        
                  $(validate_fields[x]).css(errorcssobj2);       
            }        
        }
     
        if (countValidate == 0) {
            
            //window.alert("Successfully upload.");                                                  
        } 
        else { 
            
            window.alert("Required fields must fill up");           
            return false;
        }   
    } else {
        return false;
    } 
    
});


$('#invoicenofrom').mask('99999999');  
$('#invoicenoto').mask('99999999');  
$('#ai_rfa_view, #ai_exdeal_view, #ai_payment_view, #ai_sinum_view, #monitoring_return_invoice').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: 650,    
    height:'auto',
    modal: true,
    resizable: false
}); 
//$('#invoicenofrom').mask('99999999');
//$('#invoicenoto').mask('99999999');
var list = "";
var cur = 0;
var aoptmid = 0;
$('#search').click(function(){
    $.ajax({
        url: "<?php echo site_url('aiform/aiformsearch')?>",
        type: 'post',
        data: {from: $('#invoicenofrom').val(),
               to: $('#invoicenoto').val()},
        success: function(response) {
            var $response = $.parseJSON(response);            
            list = $response['result'];
            loadAIForm();
            aoptmid = 0;
            if (list != "") {
                $('#aiform_sheet_menu').show('slow');    
            } else {
                $('#aiform_sheet_menu').hide('slow');    
            }
        }
    });
});

function loadAIForm() {
    $.ajax({
        url: "<?php echo site_url('aiform/loadAIForm')?>",
        type: 'post',
        data: {list: list},
        success: function(response) {
            var $response = $.parseJSON(response);
            $('#aiform_sheet').html($response['aiform']);     
            aoptmid = 0;                 
        }    
    });    
}

$("#nav_first").click(function(){
    navigateAIForm('first');
});
$("#nav_next").click(function(){
    navigateAIForm('next');
});
$("#nav_previous").click(function(){
    navigateAIForm('previous');
});
$("#nav_last").click(function(){
    navigateAIForm('last');
});



function navigateAIForm(key) {
    if (list != '') {
        $.ajax({
            url: "<?php echo site_url('aiform/navigateAIForm')?>",
            type: 'post',
            data: {list: list, event: key, cur: $('#indexholder').val()},
            success: function(response) {
                var $response = $.parseJSON(response);            
                list = $response['result'];                   
                $('#aiform_sheet').html($response['aiform']);
                $('#indexholder').val($response['index']);      
                aoptmid = 0;         
            }    
        }); 
    }    
}

$(document).keydown(function(e){
    if (e.keyCode == 37) { 
       navigateAIForm('previous');        
    }
    if (e.keyCode == 39) { 
       navigateAIForm('next');        
    }
});
</script>

