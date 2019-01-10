<div class="block-fluid">      
    <form action="<?php echo site_url('branch/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Code</b></div>    
        <div class="span1"><input type="text" name="branch_code" id="branch_code" value="<?php echo $data['branch_code'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="branch_name" id="branch_name" value="<?php echo $data['branch_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Bank Acct</b></div>    
        <div class="span2" style="width:190px">
        <select name="branch_bnacc" id="branch_bnacc" value="<?php echo $data['branch_bnacc']?>"
            <option value="">--</option>
            <?php foreach ($bankbranch as $row) : 
            if ($row['id'] == $data['branch_bnacc']) : ?>
            <option value="<?php echo $row['id'] ?>" selected='selected'><?php echo $row['bbf_bnch'] ?></option>
            <?php else : ?>
            <option value="<?php echo $row['id'] ?>"><?php echo $row['bbf_bnch'] ?></option>
            <?php endif; 
            endforeach; ?>
        </select>
        <div class="clear"></div>    
    </div> 
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Branch Button</button></div>        
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
        $('#formsave').submit();         
    } else {            
        return false;
    }    
});
</script>
