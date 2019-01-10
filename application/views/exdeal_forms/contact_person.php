<div class="block-fluid">   

    <form id="parameter_form" action="<?php echo site_url("exdeal_contactperson/".$action."/".$id) ?>" method="post" >
            
            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Name</b></div>    
            <div class="span2"><input type="text" name="contact_person" id="contact_person" value="<?php if(count($result) != 0) { echo $result->contact_person; } ?>"></div>        
            <div class="clear"></div>    
            </div>
    

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Company Name</b></div>    
            <div class="span2"><input type="text" name="company_name" id="company_name" value="<?php if(count($result) != 0) { echo $result->company; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Designation</b></div>    
            <div class="span2"><input type="text" name="designation" id="designation" value="<?php if(count($result) != 0) { echo $result->designation; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Contact No.</b></div>    
            <div class="span2"><input type="text" name="contact_no" id="contact_no" value="<?php if(count($result) != 0) { echo $result->contact_no; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Fax No.</b></div>    
            <div class="span2"><input type="text" name="fax_no" id="fax_no" value="<?php if(count($result) != 0) { echo $result->fax_no; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Email</b></div>    
            <div class="span2"><input type="text" name="email" id="email" value="<?php if(count($result) != 0) { echo $result->email; } ?>"></div>        
            <div class="clear"></div>    
            </div>
        

            <div class="row-form-booking">
            <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">SUBMIT</button></div>        
            <div class="clear"></div>        
            </div>

    </form>
   
</div>
    
<!--   <div class="dr"><span></span></div>   -->

<div class="dialog" id="b_popup_4" style="display: none;" title="Message"></div>


<script>

//$("#parameter_vatinclusive, #parameter_netratereturn, #parameter_insertrate, #parameter_avedailycirc, #parameter_fixedexpenses").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#contact_person', '#company_name'];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    } 
   
    if (countValidate == 0) {
        
        <?php if($action == 'insert') { ?>  
        
        $.ajax({
            url: "<?php echo site_url('exdeal_contactperson/check_contactperson') ?>",
            type: "post",
            data: {contact_person : $("#contact_person").val()},
            success: function(response) {
                if (response == "true") {                    
                       alert("Contact person must be unique!.");
                       $('#company_code').val('');
                   } else {
                       $('#parameter_form').submit();
                   }
            }
        }); 
       
       <?php } else { ?> 
       
            $('#parameter_form').submit();
       
       <?php } ?>
              
    } else {            
        return false;
    }    
});
</script>
