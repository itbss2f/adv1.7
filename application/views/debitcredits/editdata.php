<div class="block-fluid">      
    <form action="<?php echo site_url('debitcredit/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Code</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="tdcf_code" id="tdcf_code" value="<?php echo $data['tdcf_code'] ?>"></div>        
        <div class="clear"></div>    
    </div>      
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="tdcf_name" id="tdcf_name" value="<?php echo $data['tdcf_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>     
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Apply</b></div>    
        <div class="span2" style="width:190px">
            <select name="tdcf_apply" id="tdcf_apply"></div>        
                <option value="">--</option>
                <option value="Y" <?php if($data['tdcf_apply'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
                <option value="N" <?php if($data['tdcf_apply'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
            </select>
        </div> 
        <div class="clear"></div>    
    </div>    
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save DC button</button></div>        
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
    var validate_fields = ['#tdcf_code'];

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
