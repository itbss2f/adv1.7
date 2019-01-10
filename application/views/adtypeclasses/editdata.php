<div class="block-fluid">      
    <form action="<?php echo site_url('adtypeclass/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Ad Class Code</b></div>    
        <div class="span1"><input type="text" name="adtypeclass_code" id="adtypeclass_code" value="<?php echo $data['adtypeclass_code'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Ad Class Name</b></div>    
        <div class="span2" style="width:190px">
            <input type="text" name="adtypeclass_name" id="adtypeclass_name" value="<?php echo $data['adtypeclass_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
     <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Ad Class Main</b></div>    
        <div class="span2" style="width:190px">
            <select name="adtypeclass_main" id="adtypeclass_main">
                <option value="">--</option>
                <?php foreach ($main as $caf1) : ?>
                <?php if ($caf1['id'] == $data['adtypeclass_main']) : ?>
                <option value="<?php echo $caf1['id'] ?>" selected="selected"><?php echo $caf1['adtypeclassmain_name'] ?></option>
                <?php else: ?>
                <option value="<?php echo $caf1['id'] ?>"><?php echo $caf1['adtypeclassmain_name'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save AdtypeClass button</button></div>        
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
    var validate_fields = ['#adtypeclass_code', '#adtypeclass_name', '#adtypeclass_main'];

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
