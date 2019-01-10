<div class="block-fluid">      
    <form action="<?php echo site_url('vat/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>VAT Code</b></div>    
        <div class="span1"><input type="text" name="vat_code" id="vat_code" value="<?php echo $data['vat_code'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>VAT Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="vat_name" id="vat_name" value="<?php echo $data['vat_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>VAT Rate</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="vat_rate" id="vat_rate" value="<?php echo $data['vat_rate'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>VAT Date From</b></div>    
        <div class="span2" style="width:100px"><input type="text" name="vat_date_from" id="vat_date_from" value="<?php echo $data['vat_from'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>VAT Date To</b></div>    
        <div class="span2" style="width:100px"><input type="text" name="vat_date_to" id="vat_date_to" value="<?php echo $data['vat_to'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Vat button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#vat_date_from").datepicker({dateFormat: 'yy-mm-dd'});
$("#vat_date_to").datepicker({dateFormat: 'yy-mm-dd'});  
$("#edition_totalccm").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#vat_code', '#vat_name', '#vat_rate', '#vat_date_from', '#vat_date_to'];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) {
        $('#formsave').submit();         
    } else {            
        return false;
    }    
});
</script>
