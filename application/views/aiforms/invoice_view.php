<div class="block-fluid">                      
    <div class="row-form-booking">    
        <div class="span1">AO Number:</div>
        <div class="span1"><?php echo $data['ao_num'] ?></div>
        <div class="span1">Issue Date:</div>
        <div class="span1"><?php echo $data['issuedate'] ?></div>
        <div class="clear"></div>  
    </div>
    <?php if ($canEDITINVOICENO) : ?>  
    <div class="row-form-booking">    
        <div class="span1">Invoice #</div>
        <div class="span1"><input type='post' name='invnum' style="width:80px" id='invnum' value='<?php echo $data['ao_sinum'] ?>' /></div>
        <div class="span1">Invoice Date</div>
        <div class="span1"><input type='post' name='invdate' style="width:80px" id='invdate' value='<?php echo $data['invdate'] ?>'/></div>
        <div class="clear"></div>
    </div> 
    <?php else: ?> 
    <div class="row-form-booking">    
        <div class="span1">Invoice #</div>
        <div class="span1"><?php echo $data['ao_sinum'] ?></div>
        <div class="span1">Invoice Date</div>
        <div class="span1"><?php echo $data['invdate'] ?></div>
        <div class="clear"></div>
    </div> 
    <?php endif; ?>   
    <?php if ($canEDITINVOICENO) : ?>     
    <div class="row-form-booking">    
        <div class="span1">Product Title</div>
        <div class="span3"><input type='post' name='invprodtitle' id='invprodtitle' style="width:400px" placeholder='Product Title' value='<?php echo $data['ao_billing_prodtitle'] ?>' /></div>
        <div class="clear"></div>
    </div> 
    <?php endif; ?>   
    <?php if ($canEDITINVOICREM) : ?>
    <div class="row-form-booking">    
        <div class="span1">Remarks</div>
        <div class="span3" style="width:340px" ><input type='post' name='invremarks' id='invremarks' style="width:320px" placeholder='REMARKS' value='<?php echo $data['ao_ai_remarks'] ?>' /></div>
        <div class="span1"><input type='post' name='invremdate' id='invremdate' style="width:80px" placeholder='DATE' value='<?php echo $data['ao_ai_remarksdate'] ?>' /></div>
        <div class="clear"></div>
    </div>    
    <?php endif; ?>
    <?php if ($canEDITINVOICENO) : ?> 
    <div class="row-form-booking">    
        
        <div class="span2"><button class="btn btn-success" type="button" name="saveinv" id="saveinv">Save Invoice Data</button></div>         
        <div class="span3" style="color: red;">To remove invoice just put 0 in invoice # field</div>         
        <div class="clear"></div>
    </div> 
    <?php endif; ?>    
    <?php if ($canEDITINVOICREM) : ?>
    <div class="row-form-booking">    
        
        <div class="span2"><button class="btn btn-success" type="button" name="savereminv" id="savereminv">Save Remarks Data</button></div>         
        <div class="clear"></div>
    </div> 
    <?php endif; ?>    
    
</div>

<script>    
$("#invdate").datepicker({dateFormat: 'yy-mm-dd'});           
$("#invremdate").datepicker({dateFormat: 'yy-mm-dd'});           
$("#saveinv").click(function() {
    var $id = '<?php echo $data['id'] ?>';
    $.ajax({
        url: "<?php echo site_url('aiform/saveInvData') ?>",
        type: "post",
        data: {id : $id, 
               from: $('#invoicenofrom').val(),
               to: $('#invoicenoto').val(),
               invnum: $('#invnum').val(),
               invdate: $('#invdate').val(),
               invprodtitle: $('#invprodtitle').val()
        },
        success: function(response) {
    
            alert('Save data for invoice successful');        
            
            navigateAIForm('self'); 
            
            $('#ai_sinum_view').dialog('close');       
        }
    });        
}); 

$("#savereminv").click(function() {
    
    var $id = '<?php echo $data['id'] ?>';
    $.ajax({
        url: "<?php echo site_url('aiform/saveRemInvData') ?>",
        type: "post",
        data: {id : $id, 
               invnum: '<?php echo $data['ao_sinum'] ?>', 
               invremarks: $('#invremarks').val(),
               invremdate: $('#invremdate').val()
        },
        success: function(response) {
    
            alert('Save data for invoice successful');        
            
            navigateAIForm('self'); 
            
            $('#ai_sinum_view').dialog('close');       
        }
    });        
}); 
</script>