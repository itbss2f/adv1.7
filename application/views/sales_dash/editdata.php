<div class="block-fluid">      
    <form action="<?php echo site_url('sales_dash/aeupdate/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span1" style="width: 100px;"><b>Customer Data:</b></div>    
        <div class="span3" style="width: 300px;"><?php echo $data['cmf_code'].' - '.$data['cmf_name'] ?></div>           
        <div class="span1" style="width: 130px;"><b>TIN:</b><?php echo $data['cmf_tin'] ?></div>             
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span1"><b>Contact #1</b></div>    
        <div class="span2"><input type="text" name="contact1_name" id="contact1_name" placeholder="Name" value="<?php echo $data['ae_name1'] ?>"></div>        
        <div class="span1"><input type="text" name="contact1_position" id="contact1_position" placeholder="Position" value="<?php echo $data['ae_position1'] ?>"></div>   
        <div class="span2"><input type="text" name="contact1_email" id="contact1_email" placeholder="Email" value="<?php echo $data['ae_email1'] ?>"></div>             
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span1"><b></b></div>          
        <div class="span2"><input type="text" name="contact1_contactnumber" id="contact1_contactnumber" placeholder="Contact Number" value="<?php echo $data['ae_contact1'] ?>"></div>             
        <div class="clear"></div>    
    </div>

    <div class="row-form-booking">
        <div class="span1"><b>Contact #2</b></div>    
        <div class="span2"><input type="text" name="contact2_name" id="contact2_name" placeholder="Name" value="<?php echo $data['ae_name2'] ?>"></div>        
        <div class="span1"><input type="text" name="contact2_position" id="contact2_position" placeholder="Position" value="<?php echo $data['ae_position2'] ?>"></div>   
        <div class="span2"><input type="text" name="contact2_email" id="contact2_email" placeholder="Email" value="<?php echo $data['ae_email2'] ?>"></div>             
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span1"><b></b></div>          
        <div class="span2"><input type="text" name="contact2_contactnumber" id="contact2_contactnumber" placeholder="Contact Number" value="<?php echo $data['ae_contact2'] ?>"></div>             
        <div class="clear"></div>    
    </div>
    
     <div class="row-form-booking">
        <div class="span1"><b>Industry</b></div>    
        <div class="span3">
            <select name="industry" id="industry">
                <option value="0"></option>
                <?php foreach($industry as $industry) : ?>
                <?php if ($industry['id'] == $data['cmf_industry']) : ?>
                <option value="<?php echo $industry['id']?>" selected="selected"><?php echo $industry['ind_name'] ?></option>
                <?php else : ?>
                <option value="<?php echo $industry['id']?>"><?php echo $industry['ind_name'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>            
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save AE Contact Detail button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#save").click(function() {
    $('#formsave').submit();         
});
</script>
