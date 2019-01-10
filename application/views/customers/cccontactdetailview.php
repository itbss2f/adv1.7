<div class="block-fluid">      
    <form action="<?php echo site_url('customer/ccupdate/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span1" style="width: 100px;"><b>Customer Data:</b></div>    
        <div class="span3" style="width: 300px;"><?php echo $data['cmf_code'].' - '.$data['cmf_name'] ?></div>           
        <div class="span1" style="width: 130px;"><b>TIN:</b><?php echo $data['cmf_tin'] ?></div>             
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span1"><b>Contact #1</b></div>    
        <div class="span2"><input type="text" name="contact1_name" id="contact1_name" placeholder="Name" value="<?php echo $data['cc_name1'] ?>"></div>        
        <div class="span1"><input type="text" name="contact1_position" id="contact1_position" placeholder="Position" value="<?php echo $data['cc_position1'] ?>"></div>   
        <div class="span2"><input type="text" name="contact1_email" id="contact1_email" placeholder="Email" value="<?php echo $data['cc_email1'] ?>"></div>             
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span1"><b></b></div>          
        <div class="span2"><input type="text" name="contact1_contactnumber" id="contact1_contactnumber" placeholder="Contact Number" value="<?php echo $data['cc_contact1'] ?>"></div>        
        <div class="span3"><input type="text" name="contact1_address" id="contact1_address" placeholder="Address" value="<?php echo $data['cc_address1'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span1"><b>Contact #2</b></div>    
        <div class="span2"><input type="text" name="contact2_name" id="contact2_name" placeholder="Name" value="<?php echo $data['cc_name2'] ?>"></div>        
        <div class="span1"><input type="text" name="contact2_position" id="contact2_position" placeholder="Position" value="<?php echo $data['cc_position2'] ?>"></div>   
        <div class="span2"><input type="text" name="contact2_email" id="contact2_email" placeholder="Email" value="<?php echo $data['cc_email2'] ?>"></div>             
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span1"><b></b></div>          
        <div class="span2"><input type="text" name="contact2_contactnumber" id="contact2_contactnumber" placeholder="Contact Number" value="<?php echo $data['cc_contact2'] ?>"></div>        
        <div class="span3"><input type="text" name="contact2_address" id="contact2_address" placeholder="Address" value="<?php echo $data['cc_address2'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span1"><b>Contact #3</b></div>    
        <div class="span2"><input type="text" name="contact3_name" id="contact3_name" placeholder="Name" value="<?php echo $data['cc_name3'] ?>"></div>        
        <div class="span1"><input type="text" name="contact3_position" id="contact3_position" placeholder="Position" value="<?php echo $data['cc_position3'] ?>"></div>   
        <div class="span2"><input type="text" name="contact3_email" id="contact3_email" placeholder="Email" value="<?php echo $data['cc_email3'] ?>"></div>             
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span1"><b></b></div>          
        <div class="span2"><input type="text" name="contact3_contactnumber" id="contact3_contactnumber" placeholder="Contact Number" value="<?php echo $data['cc_contact3'] ?>"></div>        
        <div class="span3"><input type="text" name="contact3_address" id="contact3_address" placeholder="Address" value="<?php echo $data['cc_address3'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Collection Contact Detail button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#save").click(function() {
    $('#formsave').submit();         
});
</script>
