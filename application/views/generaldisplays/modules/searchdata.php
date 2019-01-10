<div class="block-fluid">      
    <form action="<?php echo site_url('module/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Main Module</b></div>    
        <div class="span1" style="width:190px">
            <select name="mainmodule" id="mainmodule">
                <option value="">--</option>
                <?php foreach($mainmodule as $row) : ?>
                <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Module Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="modulename" id="modulename"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Description</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="description" id="description"></div>  
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2"><button class="btn btn-success" type="button" name="search" id="search">Search Module button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#search").click(function() {
    var countValidate = 0;  
    var validate_fields = [];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) {
        $('#formsearch').submit();
    } else {            
        return false;
    }  
});
</script>
