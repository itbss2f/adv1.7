<div class="block-fluid">      
    <form action="<?php echo site_url('bankaccount/save') ?>" method="post" name="formsave" id="formsave"> 
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Account Code</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="baf_acct" id="baf_acct"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Bank Code</b></div>    
        <div class="span2" style="width:190px">
        <select name="baf_bank" id="baf_bank">
             <option value="">--</option>
                <?php foreach ($bank as $row) : ?>
                <option value="<?php echo $row['id'] ?>"><?php echo $row['bmf_code'] ?></option>
                <?php endforeach; ?>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Code</b></div>    
        <div class="span2" style="width:190px">
        <select name="baf_bnch" id="baf_bnch">
             <option value="">--</option>
                <?php foreach ($branch as $row) : ?>
                <option value="<?php echo $row['id'] ?>"><?php echo $row['bbf_bnch'] ?></option>
                <?php endforeach; ?>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Account Type</b></div>    
        <div class="span2" style="width:190px">
        <select name="baf_at" id="baf_at">
        <option value = "">--</option>
        <option value = "C">CURRENT</option>
        <option value = "D">DOLLAR</option>
        <option value = "S">SAVINGS</option>
        <option value = "P">PLACEMENT</option>
        <option value = "A">ALL IN ONE</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Account Number</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="baf_an" id="baf_an"></div>        
        <div class="clear"></div>    
    </div>    
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Account button</button></div>        
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
    var validate_fields = ['#baf_accnt'];

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
