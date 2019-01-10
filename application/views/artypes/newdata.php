<div class="block-fluid">      
    <form action="<?php echo site_url('artype/save') ?>" method="post" name="formsave" id="formsave"> 
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Artype Code</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="tarf_code" id="tarf_code"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Artype Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="tarf_name" id="tarf_name"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Artype Group</b></div>    
        <div class="span2" style="width:190px">
        <select name="tarf_group" id="tarf_group">
        <option value = "">--</option>
        <option value = "A">ADVERTISING</option>
        <option value = "B">CIRCULATION</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Artype button</button></div>        
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
    var validate_fields = ['#tarf_code', '#tarf_name', '#tarf_group'];

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
