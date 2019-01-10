<div class="block-fluid">      
    <form action="<?php echo site_url('paycheckbankbranch/save') ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Bank</b></div>    
        <div class="span2">
        <select name="bbf_bank" id="bbf_bank">
            <option value="">--</option>
            <?php foreach ($bank as $row) : ?>
            <option value="<?php echo $row['id'] ?>"><?php echo $row['bmf_name'] ?></option>
            <?php endforeach; ?>
        </select>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch</b></div>    
        <div class="span2"><input type="text" name="bbf_bnch" id="bbf_bnch"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Address</b></div>    
        <div class="span2"><input type="text" name="bbf_add1" id="bbf_add1"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Telephone</b></div>    
        <div class="span2"><input type="text" name="bbf_tel1" id="bbf_tel1"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Contact Person</b></div>    
        <div class="span2"><input type="text" name="bbf_name" id="bbf_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Bank Branch button</button></div>        
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
    var validate_fields = ['#bbf_bank', '#bbf_bnch'];

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
