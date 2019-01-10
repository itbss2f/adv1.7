<div class="block-fluid">    
 
 <form action="<?php echo site_url("exdeal_advertisermaingroup/".$action) ?>" method="post" id="advertisergroup_form">
    
        <input type="hidden" id="group_id" name="group_id" value="<?php if($action=='update'){ echo $id; } ?>">
    
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Main Group</b></div>    
        <div class="span2"><input type="text" name="group_name" <?php if($action=='update'){ echo "readonly";} ?> id="group_name" value="<?php if($action=='update'){ echo $result->group_name;} ?>" ></div>        
        <div class="clear"></div>    
        </div>
        
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Advertiser Name</b></div>    
        <div class="span2"><input type="text" name="advertiser" id="advertiser" value="<?php if($action=='update'){ echo $result->advertiser;} ?>"></div>        
        <div class="clear"></div>    
        </div>
        
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Credit Limit</b></div>    
        <div class="span2"><input type="text" name="credit_limit" id="credit_limit" value="<?php if($action=='update'){ echo number_format($result->credit_limit,2,'.',',');} ?>"></div>        
        <div class="clear"></div>    
        </div>
        
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Contact Person</b></div>    
        <div class="span2"><input type="text" name="contact_person" id="contact_person" value="<?php if($action=='update'){ echo $result->contact_person;} ?>"></div>        
        <div class="clear"></div>    
        </div>
        
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Telephone</b></div>    
        <div class="span2"><input type="text" name="telephone" id="telephone" value="<?php if($action=='update'){ echo $result->telephone;} ?>"></div>        
        <div class="clear"></div>    
        </div>
        
 </form>
 
</div> 

<script>

$("#credit_limit").autoNumeric();  
</script>