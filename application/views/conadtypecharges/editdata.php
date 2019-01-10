<div class="block-fluid">      
    <form action="<?php echo site_url('conadtypecharges/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Type</b></div>    
        <div class="span2">            
            <select name="type" id="type">
                <option value="D" <?php if ($data['adtypecharges_type'] == 'D') { echo "selected='selected'";}?>>DISPLAY</option>
                <option value="C" <?php if ($data['adtypecharges_type'] == 'C') { echo "selected='selected'";}?>>CLASSIFIEDS</option>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Charge Code</b></div>                                      
        <div class="span2" style="width:100px;"><input type="text" name="charge_code" id="charge_code" value="<?php echo $data['adtypecharges_code'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Charge Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="charge_name" id="charge_name" value="<?php echo $data['adtypecharges_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Product</b></div>    
        <div class="span2" style="width:190px">
            <select name="product" id="product">
                <option value="">--</option>
                <?php foreach ($prod as $prod) : ?>
                <?php if ($prod['id'] == $data['adtypecharges_prod'] ) : ?>
                <option value="<?php echo $prod["id"] ?>" selected="selected"><?php echo $prod["prod_name"] ?></option>        
                <?php else: ?>
                <option value="<?php echo $prod["id"] ?>"><?php echo $prod["prod_name"] ?></option>        
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Class</b></div>    
        <div class="span2" style="width:190px">
            <select name="class" id="class">
                <option value="">--</option>
                <?php foreach($class as $class) : ?>
                <?php if ($class['id'] == $data['adtypecharges_class']) :?>
                <option value="<?php echo $class["id"] ?>" selected="selected"><?php echo $class["class_name"] ?></option>
                <?php else: ?>
                <option value="<?php echo $class["id"] ?>"><?php echo $class["class_name"] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Date Start</b></div>    
        <div class="span2" style="width:190px"><input type="text" class="datepicker" name="datestart" id="datestart" value="<?php echo $data['startdate'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Date End</b></div>    
        <div class="span2" style="width:190px"><input type="text" class="datepicker" name="dateend" id="dateend" value="<?php echo $data['enddate'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Amount</b></div>    
        <div class="span2" style="width:100px"><input type="text" style="text-align: right;" name="amount" id="amount" value="<?php echo $data['adtypecharges_amt'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Rate</b></div>    
        <div class="span2" style="width:100px"><input type="text" style="text-align: right;"  name="rate" id="rate" value="<?php echo $data['adtypecharges_rate'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Rank [ 1 , 2, 3]</b></div>    
        <div class="span2" style="width:100px"><input type="text" style="text-align: right;"  name="rank" id="rank" value="<?php echo $data['adtypecharges_rank'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking" style="padding-left: 30px;">   
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="mon" id="mon" value="1" <?php if ($data['adtypecharges_monday'] == 1) { echo "checked='checked'"; } ?>> Mon</span>                                
        </div>  
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="tue" id="tue" value="1" <?php if ($data['adtypecharges_tuesday'] == "1") { echo "checked='checked'"; } ?>> Tue</span>                                
        </div> 
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="wed" id="wed" value="1" <?php if ($data['adtypecharges_wednesday'] == "1") { echo "checked='checked'"; } ?>> Wed</span>                                
        </div> 
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="thu" id="thu" value="1" <?php if ($data['adtypecharges_thursday'] == "1") { echo "checked='checked'"; } ?>> Thu</span>                                
        </div>       
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="fri" id="fri" value="1" <?php if ($data['adtypecharges_friday'] == "1") { echo "checked='checked'"; } ?>> Fri</span>                                
        </div> 
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="sat" id="sat" value="1" <?php if ($data['adtypecharges_saturday'] == "1") { echo "checked='checked'"; } ?>> Sat</span>                                
        </div> 
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="sun" id="sun" value="1" <?php if ($data['adtypecharges_sunday'] == "1") { echo "checked='checked'"; } ?>> Sun</span>                                
        </div>         
        <div class="clear"></div>    
    </div>          
    <div class="row-form-booking">
        <?php if ($canEDIT) : ?>
        <div class="span2" style="width: 140px;"><button class="btn btn-success" type="button" name="save" id="save">Save button</button></div>        
        <?php endif; ?>
        <?php if ($canDELETE) : ?>
        <div class="span2" style="width: 140px;"><button class="btn btn-danger" type="button" name="remove" id="remove">Delete button</button></div>  
        <?php endif; ?>
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
$("#amount, #rate").autoNumeric();
   var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#charge_code', '#charge_name', '#datestart', '#dateend'];

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

$("#remove").click(function() {
    var $id = "<?php echo $data['id'] ?>";
    var ans = confirm("Are you sure you want to remove this Charges?");    

    if (ans) {
        window.location = "<?php echo site_url('conadtypecharges/removeData') ?>/"+$id;
    } 
});

</script>
