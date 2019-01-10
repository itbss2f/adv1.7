<div class="block-fluid">      
    <form action="<?php echo site_url('paycheckbank/save') ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Bank Code</b></div>    
        <div class="span1"><input type="text" name="bmf_code" id="bmf_code" ></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Bank Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="bmf_name" id="bmf_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Bank button</button></div>        
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
    var validate_fields = ['#bmf_code', '#bmf_name'];

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
            url: "<?php echo site_url('paycheckbank/validateCode') ?>",
            type: "post",
            data: {code : $("#bmf_code").val()},
            success: function(response) {
                if (response == "true") {                    
                       alert("Bank Code must be unique!.");
                       $('#bank_code').val('');
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
