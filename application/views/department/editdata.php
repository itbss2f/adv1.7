<div class="block-fluid">      
    <form action="<?php echo site_url('departments/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Dept Code</b></div>    
        <div class="span1"><input type="text" name="dept_code" id="dept_code" value="<?php echo $data['dept_code'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Description</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="dept_name" id="dept_name" value="<?php echo $data['dept_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Dept Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="mdept_name" id="mdept_name" value="<?php echo $data['mdept_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Dept Branch Status</b></div>    
        <div class="span3" style="width:190px">
            <select name="dept_branchstatus" id="dept_branchstatus"></div>                              
                <option value="N" <?php if($data['dept_branchstatus'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
                <option value="Y" <?php if($data['dept_branchstatus'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>           
            </select>
        </div>  
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Section Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="sect_name" id="sect_name" value="<?php echo $data['sect_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
<!--     <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Product</b></div>    
        <div class="span3" style="width:190px">
            <select name="prod_code" id="prod_code">
                <option value="0">------</option> 
                <?php foreach ($prod as $prod) : 
                if ($prod['id'] == $data['prod_code']) : ?> 
                <option value="<?php echo $prod['id'] ?>" selected='selected'><?php echo $prod['prod_name'] ?></option>                           
                <?php else : ?>  
                <option value="<?php echo $prod['id'] ?>"><?php echo $prod['prod_name'] ?></option> 
                <?php endif; ?>                  
                <?php endforeach; ?>
            </select>
        </div>  
    </div> -->
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Expenses Type</b></div>    
        <div class="span3" style="width:190px">
            <select name="exp_type" id="exp_type">
                <option value="0">------</option> 
                <?php foreach ($acctlist as $row) : 
                if ($row['caf_code'] == $data['exp_type']) : ?> 
                <option value="<?php echo $row['caf_code'] ?>" selected='selected'><?php echo $row['caf_code'].'-'.$row['acct_title'] ?></option>                           
                <?php else : ?>  
                <option value="<?php echo $row['caf_code'] ?>"><?php echo $row['caf_code'].'-'.$row['acct_title'] ?></option> 
                <?php endif; ?>                  
                <?php endforeach; ?>
            </select>
        </div>  
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Update Department</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
//$("#exp_type").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#dept_code', '#dept_name', '#dept_branchstatus','#mdept_name', '#sect_name', '#exp_type'];

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
