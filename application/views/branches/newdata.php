<div class="block-fluid">      
    <form action="<?php echo site_url('branch/save') ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Code</b></div>    
        <div class="span1"><input type="text" name="branch_code" id="branch_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="branch_name" id="branch_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Bank Acct</b></div>    
        <div class="span2" style="width:190px">
        <select name="branch_bnacc" id="branch_bnacc">
            <option value="">--</option>
            <?php foreach ($bankbranch as $row) : ?>
            <option value="<?php echo $row['id'] ?>"><?php echo $row['bbf_bnch'] ?></option>
            <?php endforeach; ?>
        </select>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Branch button</button></div>        
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
    var validate_fields = ['#branch_code', '#branch_name', '#branch_bnacc'];

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
            url: "<?php echo site_url('branch/validateCode') ?>",
            type: "post",
            data: {code : $("#branch_code").val()},
            success: function(response) {
                if (response == "true") {                    
                       alert("Branch Code must be unique!.");
                       $('#branch_code').val('');
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
