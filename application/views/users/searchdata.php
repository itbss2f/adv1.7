
<div class="block-fluid">      
    <form action="<?php echo site_url('user/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Employee No.</b></div>    
        <div class="span2" style="width:120px"><input type="text" name="emp_id" id="emp_id"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>First Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="firstname" id="firstname"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Middle Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="middlename" id="middlename"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Last Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="lastname" id="lastname"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Username</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="username" id="username"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Email</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="email" id="email"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Position</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="position" id="position"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch</b></div>    
        <div class="span1" style="width:190px">
            <select name="branch" id="branch">
                <option value="">------</option>
                <?php foreach($branch as $row) : ?>
                <option value="<?php echo $row['branch_code'] ?>"><?php echo $row['branch_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>User Status</b></div>    
        <div class="span1" style="width:190px">
            <select name="userstat" id="userstat">
                <option value="">------</option>
                <option value="0">Active</option>
                <option value="1">Inactive</option>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2"><button class="btn btn-success" type="button" name="search" id="search">Search User button</button></div>        
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