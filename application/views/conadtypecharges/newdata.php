<div class="block-fluid">      
    <form action="<?php echo site_url('conadtypecharges/save') ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Type</b></div>    
        <div class="span2">            
            <select name="type" id="type">
                <option value="D">DISPLAY</option>
                <option value="C">CLASSIFIEDS</option>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Charge Code</b></div>    
        <div class="span2" style="width:100px;"><input type="text" name="charge_code" id="charge_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Charge Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="charge_name" id="charge_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Product</b></div>    
        <div class="span2" style="width:190px">
            <select name="product" id="product">
                <option value="">--</option>
                <?php foreach ($prod as $prod) : ?>
                <option value="<?php echo $prod["id"] ?>"><?php echo $prod["prod_name"] ?></option>        
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
                <option value="<?php echo $class["id"] ?>"><?php echo $class["class_name"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Date Start</b></div>    
        <div class="span2" style="width:190px"><input type="text" class="datepicker" name="datestart" id="datestart"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Date End</b></div>    
        <div class="span2" style="width:190px"><input type="text" class="datepicker" name="dateend" id="dateend"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Amount</b></div>    
        <div class="span2" style="width:100px"><input type="text" style="text-align: right;" name="amount" id="amount"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Rate</b></div>    
        <div class="span2" style="width:100px"><input type="text" style="text-align: right;"  name="rate" id="rate"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Rank [ 1 , 2, 3]</b></div>    
        <div class="span2" style="width:100px"><input type="text" style="text-align: right;"  name="rank" id="rank"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking" style="padding-left: 30px;">   
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="mon" id="mon" value="1"> Mon</span>                                
        </div>  
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="tue" id="tue" value="1"> Tue</span>                                
        </div> 
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="wed" id="wed" value="1"> Wed</span>                                
        </div> 
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="thu" id="thu" value="1"> Thu</span>                                
        </div>       
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="fri" id="fri" value="1"> Fri</span>                                
        </div> 
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="sat" id="sat" value="1"> Sat</span>                                
        </div> 
        <div style="width: 50px; float: left;">
            <span><input type="checkbox" name="sun" id="sun" value="1"> Sun</span>                                
        </div>         
        <div class="clear"></div>    
    </div>          
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Charge button</button></div>        
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
</script>
