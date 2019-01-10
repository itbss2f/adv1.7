<div class="block-fluid">      
    <form action="<?php echo site_url('color/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Color Code</b></div>    
        <div class="span1"><input type="text" name="color_code" id="color_code" value="<?php echo $data['color_code'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Description</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="color_name" id="color_name" value="<?php echo $data['color_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Rate</b></div>    
        <div class="span1"><input type="text" name="color_rate" id="color_rate" value="<?php echo $data['color_rate'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Display</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="color_display" id="color_display" value="<?php echo $data['color_display'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Color button</button></div>        
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
    var validate_fields = ['#color_code', '#color_name', '#color_rate', '#color_display'];

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
