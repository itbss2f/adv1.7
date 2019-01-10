<div class="block-fluid">      
    <form action="<?php echo site_url('yms_cmtheoriticalsales/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Product</b></div>    
        <div class="span2">        <?php #print_r2($prod);?>
        <select name="product" id="product">
            <option value="">--</option>
            <?php foreach ($product as $row) : ?>
            <?php if ($row['id'] == $data['product']) : ?>
            <option value="<?php echo $row['id'] ?>" selected="selected"><?php echo $row['name'] ?></option>
            <?php else: ?>
            <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
            <?php endif; ?>
            <?php endforeach; ?>
        </select>  
        </div>      
        <div class="clear"></div>    
    </div>
                   
    <div class="row-form-booking">
        <div class="span2" style="width:120px;margin-top:12px"><b>Date Retrieval:</div>
        <div class="span1" style="margin-top:10px"><input type="text" id="datefrom" value="<?php echo $data['datefrom'] ?>" placeholder="FROM" name="datefrom" class="datepicker"/></div>   
        <div class="span1" style="margin-top:10px"><input type="text" id="dateto" value="<?php echo $data['dateto'] ?>" placeholder="TO" name="dateto" class="datepicker"/></div>   
        <div class="clear"></div>      
  </div>
     <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Rate Amount</b></div>    
        <div class="span1"><input type="text" name="rateamount" id="rateamount" style="text-align: right; width: 100px;" value="<?php echo $data['rateamount'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Update button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

$("#edition_totalccm").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'};

 
$("#save").click(function() {
    
    var datefrom = $("#datefrom").val();   
    var datefrom = $("#dateto").val();   
    var datefrom = $("#product").val();   
    var datefrom = $("#rateamount").val();   
    
    
    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#product', '#rateamount'];

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
