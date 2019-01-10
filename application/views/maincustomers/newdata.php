<div class="block-fluid">      
    <form action="<?php echo site_url('maincustomer/save') ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Main Customer</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_code" id="cmfgroup_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>MC Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_name" id="cmfgroup_name"></div>        
        <div class="clear"></div>    
    </div>
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Credit Limit</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_creditlimit" id="cmfgroup_creditlimit"></div>        
        <div class="clear"></div>    
    </div> 
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Address 1</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_add1" id="cmfgroup_add1"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Address 2</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_add2" id="cmfgroup_add2"></div>        
        <div class="clear"></div>    
    </div>
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Address 3</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_add3" id="cmfgroup_add3"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Tel 1</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_tel1" id="cmfgroup_tel1"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Tel 2</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_tel2" id="cmfgroup_tel2"></div>        
        <div class="clear"></div>    
    </div>
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Contact Person</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_contact" id="cmfgroup_contact"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Main Customer button</button></div>        
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
    var validate_fields = ['#cmfgroup_code'];

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
            url: "<?php echo site_url('maincustomer/validateCode') ?>",
            type: "post",
            data: {code : $("#cmfgroup_code").val()},
            success: function(response) {
                if (response == "true") {                    
                       alert("Main Customer Code must be unique!.");
                       $('#cmfgroup_code').val('');
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
