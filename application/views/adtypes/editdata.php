<div class="block-fluid">      
    <form action="<?php echo site_url('adtype/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype Code</b></div>    
        <div class="span1"><input type="text" name="adtype_code" id="adtype_code" value="<?php echo $data['adtype_code'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype Name</b></div>    
        <div class="span2" style="width:190px">
            <input type="text" name="adtype_name" id="adtype_name" value="<?php echo $data['adtype_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype Type</b></div>    
        <div class="span2" style="width:190px">
            <select name="adtype_type" id="adtype_type"></div>        
                <option value="">--</option>
                <option value="C" <?php if($data['adtype_type'] == 'C') { echo "selected='selected'";}  ?>>Classified</option>
                <option value="D" <?php if($data['adtype_type'] == 'D') { echo "selected='selected'";}  ?>>Displayed</option>                                         
            </select>
        </div>         
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype Catad</b></div>    
        <div class="span2" style="width:190px">
            <select name="adtype_catad" id="adtype_catad">
                <option value="">--</option>
                <?php foreach ($caf as $caf1) : ?>
                <?php if ($caf1['id'] == $data['adtype_catad']) : ?>
                <option value="<?php echo $caf1['id'] ?>" selected="selected"><?php echo $caf1['catad_name'] ?></option>
                <?php else: ?>
                <option value="<?php echo $caf1['id'] ?>"><?php echo $caf1['catad_name'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype Class</b></div>    
        <div class="span2" style="width:190px">
           <select name="adtype_class" id="adtype_class">
                <option value="">--</option>
                <?php foreach ($row as $row) : ?>
                <?php if ($row['id'] == $data['adtype_class']) : ?>                
                <option value="<?php echo $row['id'] ?>" selected="selected"><?php echo $row['adtypeclass_code'] ?></option>
                <?php else: ?>
                <option value="<?php echo $row['id'] ?>"><?php echo $row['adtypeclass_code'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>AR Account</b></div>    
        <div class="span2" style="width:190px">
            <select name="adtype_araccount" id="adtype_araccount">
                <option value="">--</option>
                <?php foreach ($des as $des) : ?>
                <?php if ($des['id'] == $data['adtype_araccount']) : ?>                           
                <option value="<?php echo $des['id'] ?>" selected="selected"><?php echo $des['caf_code'].' | '.$des['acct_des'] ?></option>
                <?php else: ?>
                <option value="<?php echo $des['id'] ?>"><?php echo $des['caf_code'].' | '.$des['acct_des'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Adtype button</button></div>        
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
    var validate_fields = ['#adtype_code', '#adtype_name', '#adtype_type', '#adtype_class', '#adtype_araccount', '#adtype_catad'];

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
