<div class="block-fluid">      
    <form action="<?php echo site_url('classification/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Sort</b></div>    
        <div class="span1"><input type="text" name="class_sort" id="class_sort"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Classification</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="class_code" id="class_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Type</b></div>    
        <div class="span2" style="width:190px">
        <select name="class_type" id="class_type">
        <option value = "">--</option>
        <option value = "D">Dislpayed</option>
        <option value = "C">Classified</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Sub Type</b></div>    
        <div class="span2" style="width:190px">
        <select name="class_subtype" id="class_subtype">
        <option value = "">--</option>
        <option value = "S">S</option>
        <option value = "L">L</option>
        <option value = "R">R</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="class_name" id="class_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Product</b></div>    
        <div class="span2" style="width:190px">
        <select name="class_prod" id="class_prod">
             <option value="">--</option>
                <?php foreach ($prod as $caf1) : ?>
                <option value="<?php echo $caf1['id'] ?>"><?php echo $caf1['prod_name'] ?></option>
                <?php endforeach; ?>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Classification button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#tab_view").tabs();
$("#edition_totalccm").autoNumeric();
$("#expirydate").datepicker({dateFormat: 'yy-mm-dd'});
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
