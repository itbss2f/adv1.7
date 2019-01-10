<div class="block-fluid">      
    <form action="<?php echo site_url('maincustomer/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Main Customer Code</b></div>    
        <div class="span1"><input type="text" name="cmfgroup_code" id="cmfgroup_code" value="<?php echo $data['cmfgroup_code'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>MC Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_name" id="cmfgroup_name" value="<?php echo $data['cmfgroup_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Credit Limit</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_creditlimit" id="cmfgroup_creditlimit" value="<?php echo $data['cmfgroup_creditlimit'] ?>"></div>        
        <div class="clear"></div>    
    </div> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Address 1</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_add1" id="cmfgroup_add1" value="<?php echo $data['cmfgroup_add1'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Address 2</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_add2" id="cmfgroup_add2" value="<?php echo $data['cmfgroup_add2'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Address 3</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_add3" id="cmfgroup_add3" value="<?php echo $data['cmfgroup_add3'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Tel 1</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_tel1" id="cmfgroup_tel1" value="<?php echo $data['cmfgroup_tel1'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Tel 2</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_tel2" id="cmfgroup_tel2" value="<?php echo $data['cmfgroup_tel2'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Contact Person</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_contact" id="cmfgroup_contact" value="<?php echo $data['cmfgroup_contact'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Main Customer button</button></div>        
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
    var validate_fields = ['#cmfgroup_code'];

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
