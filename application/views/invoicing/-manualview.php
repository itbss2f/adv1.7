<div class="row-fluid">
    <div class="row-form">
		<div class="span2"><b>AO No.#</b></div>
		<div class="span2"><?php echo $data['ao_num'] ?></div>
		<div class="span3"><b>Issue Date</b></div>
		<div class="span5"><?php echo date('F m, Y [D]', strtotime($data['ao_issuefrom'])) ?></div>
		<div class="clear"></div> 		
    </div>

    <div class="row-form-booking">
		<div class="span4">Invoice Number:</div>
		<div class="span5"><input type='text' name='m_invoicenumber' id='m_invoicenumber' value="<?php if (empty($data['ao_sinum'])) { echo $lastinv; } else { echo $data['ao_sinum']; } ?>  " style="text-align:right;<?php if (empty($data['ao_sinum'])) { echo 'color:red'; }  ?>  "></div>
		<div class="clear"></div> 		
    </div>
    <div class="row-form-booking">
		<div class="span4">Invoice Date:</div>
          <div class="span5"><input type='text' name='m_invoicedate' id='m_invoicedate' value="<?php if (!empty($data['ao_sidate'])) { echo $data['ao_sidate'];} else { echo date('Y-m-d'); } ?>"></div>
		<div class="clear"></div> 		
    </div>
    <div class="row-form">
		<div class="span6"><button class="btn btn-success" type="button" name="m_save" id="m_save">Save button</button></div>
		<div class="span6"><button class="btn btn-danger" type="button" name="m_close" id="m_close">Close button</button></div>
		<div class="clear"></div>
    </div> 		
</div>

<script>
$(document).ready(function()
{
    
    $("#m_invoicedate").datepicker({dateFormat: 'yy-mm-dd'});   
    $('#m_invoicenumber').mask('99999999');   
    /*$('#m_invoicedate').focusout(function(){
       var xx = $('#m_invoicedate').val();
       
       if (xx == "" || xx == undefined) {
           $('#m_invoicenumber').val('');
           $('#m_invoicedate').val('');
       }
    });

    $('#m_invoicenumber').focusout(function(){    
       var x = $('#m_invoicenumber').val();
       
       if (x == "" || x == undefined) {
           $('#m_invoicenumber').val('');
           $('#m_invoicedate').val('');
       }
    });*/

    $('#m_save').click(function(){
        
        saveInvoice();        
        /*$.ajax({
            url: "<?php #echo site_url('invoicing/validationInvoice') ?>",
            type: "post",
            data: {invoiceno: $("#m_invoicenumber").val(),
                   invoicedate: $("#m_invoicedate").val()},
            success: function(response) {
                
                $response = $.parseJSON(response);    
                if ($response["validate"] != "") {
                    alert("Invoice already exist invoice date is "+ $response["validate"]);
                    $('#m_invoicedate').val($response["validate"]);
                    saveInvoice();                       
                } else {
                    
                }
            }
        });*/  
        
        

    });
    
    function saveInvoice() {
        
        $.ajax({
            url: "<?php echo site_url('invoicing/checkInvoiceApplication') ?>",
            type: 'post',
            data: {id: "<?php echo $id ?>"},
            success: function(response) {
                var $response = $.parseJSON(response);
                
                if ($response['result'] == 1) {
                    alert('Invoice number has application!.');
                    return false;
                } else {
                    $.ajax({
                        url: "<?php echo site_url('invoicing/manualInvoiceSave') ?>",
                        type: 'post',
                        data: {id: "<?php echo $id ?>",
                               invoiceno: $('#m_invoicenumber').val(),
                               invoicedate: $('#m_invoicedate').val(),
                               manualissuedatefrom: $("#manualissuedatefrom").val(),
                               manualissuedateto: $("#manualissuedateto").val(),
                               manualfilter: $("#manualfilter").val()},
                       success: function(response) {  
                            var $response = $.parseJSON(response);
                                       
                            if ($response['return'] == "false") {
                                alert('One or Two of the fields is empty!. Invoice Number and Invoice Date will be set to NULL');
                            }

                            $('#prevInvoice').html($response['prevInvoice']);   
                            $('#lastinvno').text($response['lastinv']);                          
                            $('#manualview').dialog('close');
                        }            
                    });   
                }
            }
        });
        
        //return false;
        
        
    }
    
    $('#m_close').click(function(){
        $('#manualview').dialog('close');
    });
    
});
</script>
