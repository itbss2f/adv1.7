<div class="block-fluid">      
    <form action="<?php echo site_url('module/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Main Module</b></div>    
        <div class="span1" style="width:190px">
            <select name="mainmodule" id="mainmodule">
                <option value="">--</option>
                <?php foreach($mainmodule as $row) : ?>
                <?php if ($row['id'] == $data['main_module_id']) : ?>
                <option value="<?php echo $row['id'] ?>" selected="selected"><?php echo $row['name'] ?></option>
                <?php else: ?>
                <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                <?php 
                    endif;
                endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Module Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="modulename" id="modulename" value="<?php echo $data['name'] ?>"></div>       
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Description</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="description" id="description" value="<?php echo $data['description'] ?>"></div>  
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Segment</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="segment" id="segment" value="<?php echo $data['segment_path'] ?>"></div>  
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2"><button class="btn btn-success" type="button" name="save" id="save">Save Module button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#mainmodule', '#modulename', '#description', '#segment'];

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
