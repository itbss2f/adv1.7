<div class="block-fluid">      
    <form action="<?php echo site_url('con_function/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="functionname" id="functionname" value="<?php echo $data['name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Description</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="description" id="description" value="<?php echo $data['description'] ?>"></div>  
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2"><button class="btn btn-success" type="button" name="save" id="save">Save Function button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#functionname', '#description'];

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
