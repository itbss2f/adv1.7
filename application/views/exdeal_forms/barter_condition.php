<div class="block-fluid">   

    <form id="parameter_form" action="<?php echo site_url("exdeal_bartercondition/".$action."/".$id) ?>" method="post" >
            
            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Condition</b></div>    
            <div class="span2"><input type="text" name="condition" id="condition" value="<?php if(count($result) != 0) { echo $result->barter_condition; } ?>"></div>        
            <div class="clear"></div>    
            </div>
    

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Category</b></div>    
            <div class="span2">
                    <select name="category" id="category">
                                <option value="">--</option>
                                <option <?php if(count($result) != 0 and $result->category_id == '1' ) { echo "selected";} ?> value="1">Commodities</option>
                                <option <?php if(count($result) != 0 and $result->category_id == '2' ) { echo "selected";} ?> value="2">Gift Certificates</option>
                                <option <?php if(count($result) != 0 and $result->category_id == '3' ) { echo "selected";} ?> value="3">Hotels/Resorts</option>
                    </select>
            </div>        
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
    var validate_fields = ['#condition', '#category'];

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
            url: "<?php echo site_url('exdeal_bartercondition/check_condition') ?>",
            type: "post",
            data: {condition : $("#condition").val(),category:$("#condition option:selected").val()},
            success: function(response) {
                if (response == "true") {                    
                       alert("This condition and category is already existing! ");
                      
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
