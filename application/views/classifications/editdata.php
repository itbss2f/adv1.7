<div class="block-fluid">      
    <form action="<?php echo site_url('classification/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Sort</b></div>    
        <div class="span1"><input type="text" name="class_sort" id="class_sort" value="<?php echo $data['class_sort'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Class Code</b></div>    
        <div class="span2" style="width:190px">
            <input type="text" name="class_code" id="class_code" value="<?php echo $data['class_code'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Type</b></div>    
        <div class="span2" style="width:190px">
            <select name="class_type" id="class_type"></div>        
                <option value="">--</option>
                <option value="D" <?php if($data['class_type'] == 'D') { echo "selected='selected'";}  ?>>Display</option>
                <option value="C" <?php if($data['class_type'] == 'C') { echo "selected='selected'";}  ?>>Classified</option>                                         
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Sub Type</b></div>    
        <div class="span2" style="width:190px">
            <select name="class_subtype" id="class_subtype"></div>        
                <option value="">--</option>
                <option value="S" <?php if($data['class_subtype'] == 'S') { echo "selected='selected'";}  ?>>S</option>
                <option value="L" <?php if($data['class_subtype'] == 'L') { echo "selected='selected'";}  ?>>L</option>                                         
                <option value="R" <?php if($data['class_subtype'] == 'R') { echo "selected='selected'";}  ?>>R</option>                                         
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Name</b></div>    
        <div class="span2" style="width:190px">
            <input type="text" name="class_name" id="class_name" value="<?php echo $data['class_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Product</b></div>    
        <div class="span2" style="width:190px">
            <select name="class_prod" id="class_prod">
                <option value="">--</option>
                <?php foreach ($prod as $des) : ?>
                <?php if ($des['id'] == $data['class_prod']) : ?>                           
                <option value="<?php echo $des['id'] ?>" selected="selected"><?php echo $des['prod_name'] ?></option>
                <?php else: ?>
                <option value="<?php echo $des['id'] ?>"><?php echo $des['prod_name'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Classification button</button></div>        
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
    var validate_fields = ['#class_sort', '#class_code', '#class_type', '#class_subtype', '#class_name', '#class_prod'];

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
