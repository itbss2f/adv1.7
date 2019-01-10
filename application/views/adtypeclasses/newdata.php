<div class="block-fluid">      
    <form action="<?php echo site_url('adtypeclass/save') ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Ad Class Code</b></div>    
        <div class="span1"><input type="text" name="adtypeclass_code" id="adtypeclass_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Ad Class Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="adtypeclass_name" id="adtypeclass_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Ad Class Main</b></div>    
        <div class="span2" style="width:190px">
        <select name="adtypeclass_main" id="adtypeclass_main">
             <option value="">--</option>
                <?php foreach ($main as $caf1) : ?>
                <option value="<?php echo $caf1['id'] ?>"><?php echo $caf1['adtypeclassmain_name']; ?></option>
                <?php endforeach; ?>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Adtypeclass button</button></div>        
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
        $.ajax({
            url: "<?php echo site_url('adtypeclass/validateCode') ?>",
            type: "post",
            data: {code : $("#adtypeclass_code").val()},
            success: function(response) {
                if (response == "true") {                    
                       alert("Adtypeclass Code must be unique!.");
                       $('#adtypeclass_code').val('');
                   } else {
                       $('#formsave').submit();
                   }
            }
        });        
    } else {            
        return false;
    }    
});
</script>
