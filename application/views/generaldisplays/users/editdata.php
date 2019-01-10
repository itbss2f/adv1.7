<style>
#result{
    margin-left:5px;
}
 
#register .short{
    color:#FF0000;
}
 
#register .weak{
    color:#E66C2C;
}
 
#register .good{
    color:#2D98F3;
}
 
#register .strong{
    color:#006400;
}


.ui-autocomplete {
    z-index: 10000 !important;
}

</style>
<div class="block-fluid">      
    <form action="<?php echo site_url('user/saveUpdateUser/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Employee No.</b></div>    
        <div class="span2" style="width:120px"><input type="text" name="empno" id="empno" value="<?php echo $data['emp_id'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>First Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="firstname" id="firstname" value="<?php echo $data['firstname'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Middle Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="middlename" id="middlename" value="<?php echo $data['middlename'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Last Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="lastname" id="lastname" value="<?php echo $data['lastname'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>User Level</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="userlevel" id="userlevel" value="<?php echo $data['userlevel_id'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch</b></div>    
        <div class="span2" style="width:190px">
            <select name="branch" id="branch">                
                <?php foreach ($branch as $branch) : ?>
                <?php if ($branch['branch_code'] == $data['branch']) : ?>
                <option value="<?php echo $branch['branch_code'] ?>" selected="selected"><?php echo $branch['branch_name'] ?></option>
                <?php else: ?>
                <option value="<?php echo $branch['branch_code'] ?>"><?php echo $branch['branch_name'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Department</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="department" id="department" value="<?php echo $data['dept_id'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Position</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="position" id="position" value="<?php echo $data['position'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Email Address</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="emailadd" id="emailadd" value="<?php echo $data['email'] ?>"></div>        
        <div class="clear"></div>    
    </div>

    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Expiry Date</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="expirydate" id="expirydate" value="<?php echo $data['expiration_date'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2"><button class="btn btn-success" type="button" name="save" id="save">Save User button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>

<script>
$("#edition_totalccm").autoNumeric();
$("#expirydate").die().live().datepicker({dateFormat:'yy-mm-dd'}); 
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#empno', '#firstname', '#middlename', '#lastname', '#userlevel', '#branch', '#department', '#position', '#emailadd', '#username', '#expirydate'];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    countValidate = 0;
    if (countValidate == 0) {
        
        $('#formsave').submit(); 

        return false;

    } else {            
        return false;
    }    
});


</script>
