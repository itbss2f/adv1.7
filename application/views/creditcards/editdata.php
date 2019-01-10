<div class="block-fluid">      
    <form action="<?php echo site_url('creditcard/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Creditcard Code</b></div>    
        <div class="span1"><input type="text" name="creditcard_code" id="creditcard_code" value="<?php echo $data['creditcard_code'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Creditcard Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="creditcard_name" id="creditcard_name" value="<?php echo $data['creditcard_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Creditcard Verify</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="creditcard_verify" id="creditcard_verify" value="<?php echo $data['creditcard_verify'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Credit Card button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#edition_totalccm").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#creditcard_code', '#creditcard_name', '#creditcard_verify'];

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
