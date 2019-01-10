<div class="block-fluid">   

    <form id="parameter_form" action="<?php echo site_url("exdeal_parameterfile/".$action."/".$id) ?>" method="post" >

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Company Code</b></div>    
            <div class="span2"><input type="text" <?php if($action=='update') { echo "readonly"; } ?> name="company_code" id="company_code" value="<?php if(count($result) != 0) { echo $result->company_code; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Company Name</b></div>    
            <div class="span2"><input type="text" name="company_name" id="company_name" value="<?php if(count($result) != 0) { echo $result->company_name; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Recommended By 1</b></div>    
            <div class="span2"><input type="text" name="recommended_by" id="recommended_by" value="<?php if(count($result) != 0) { echo $result->recommended_by; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Position</b></div>    
            <div class="span2"><input type="text" name="rec_position" id="rec_position" value="<?php if(count($result) != 0) { echo $result->rec_position; } ?>"></div>        
            <div class="clear"></div>    
            </div>
            
             <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Recommended By 2</b></div>    
            <div class="span2"><input type="text" name="recommended_by2" id="recommended_by2" value="<?php if(count($result) != 0) { echo $result->recommended_by2; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Position</b></div>    
            <div class="span2"><input type="text" name="rec_position2" id="rec_position2" value="<?php if(count($result) != 0) { echo $result->rec_position2; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Noted By 1</b></div>    
            <div class="span2"><input type="text" name="noted_by" id="noted_by" value="<?php if(count($result) != 0) { echo $result->noted_by; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Position</b></div>    
            <div class="span2"><input type="text" name="not_position" id="not_position" value="<?php if(count($result) != 0) { echo $result->not_position; } ?>"></div>        
            <div class="clear"></div>    
            </div>
            
            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Noted By 2</b></div>    
            <div class="span2"><input type="text" name="noted_by2" id="noted_by2" value="<?php if(count($result) != 0) { echo $result->noted_by2; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Position</b></div>    
            <div class="span2"><input type="text" name="not_position2" id="not_position2" value="<?php if(count($result) != 0) { echo $result->not_position2; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Approved By</b></div>    
            <div class="span2"><input type="text" name="approved_by" id="approved_by" value="<?php if(count($result) != 0) { echo $result->approved_by; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Position</b></div>    
            <div class="span2"><input type="text" name="app_position" id="app_position" value="<?php if(count($result) != 0) { echo $result->app_position; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Last Contract No. B</b></div>    
            <div class="span2"><input type="text" name="b_last_contract_no" id="b_last_contract_no" value="<?php if(count($result) != 0) { echo $result->b_last_contract_no; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Last Contract No. N</b></div>    
            <div class="span2"><input type="text" name="n_last_contract_no" id="n_last_contract_no" value="<?php if(count($result) != 0) { echo $result->n_last_contract_no; } ?>"></div>        
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
    var validate_fields = ['#company_code', '#company_name'];

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
            url: "<?php echo site_url('exdeal_parameterfile/validateCode') ?>",
            type: "post",
            data: {company_code : $("#company_code").val()},
            success: function(response) {
                if (response == "true") {                    
                       alert("Company Code must be unique!.");
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
