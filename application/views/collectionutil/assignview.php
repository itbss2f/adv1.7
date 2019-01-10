<div class="block-fluid">
    <div class="row-form">
        <div class="span1">Coll Asst:</div>
        <div class="span2">
            <select name="acollasst" id="acollasst">                    
                <?php foreach ($collasst as $collasst) : ?>
                
                <?php if ($collasst['user_id'] == $data['ao_coll_collasst']) : ?>
                <option value="<?php echo $collasst['user_id'] ?>" selected="selected"><?php echo $collasst['firstname'].' '.$collasst['lastname'] ?></option>    
                <?php else: ?>
                <option value="<?php echo $collasst['user_id'] ?>"><?php echo $collasst['firstname'].' '.$collasst['lastname'] ?></option>  
                <?php endif; ?>  
                <?php endforeach; ?>                    
            </select>
        </div>
        <div class="span1">Collector: </div>
        <div class="span2">
            <select name="acollector" id="acollector">  
                <option value="0">-- Courier -- </option>                   
                <?php foreach ($coll as $coll) : ?>
                <?php if ($coll['user_id'] == $data['ao_coll_collector']) : ?>  
                <option value="<?php echo $coll['user_id'] ?>" selected="selected"><?php echo $coll['firstname'].' '.$coll['lastname'] ?></option>    
                <?php else: ?>        
                <option value="<?php echo $coll['user_id'] ?>"><?php echo $coll['firstname'].' '.$coll['lastname'] ?></option>    
                <?php endif; ?>    
                <?php endforeach; ?>                    
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="row-form">
        <div class="span1">Pickup:</div>
        <div class="span1">
            <input type="text" placeholder="Date" class="datepicker" id="pickup2" name="pickup2" value="<?php echo $data['pickupdate'] ?>">
        </div>
        <div class="span1">Followup:</div>
        <div class="span1">
            <input type="text" placeholder="Date" class="datepicker" id="followup2" name="followup2" value="<?php echo $data['followupdate'] ?>"> 
        </div>
        <div class="span1" style="width: 100px;">Pickup Address:</div>
        <div class="span1">
            <select name="pickupaddress" id="pickupaddress">
                <option value="1" <?php if ($data['ao_coll_pickupadd'] == 1) { echo "selected='selected'"; } ?>>Client</option>
                <option value="2" <?php if ($data['ao_coll_pickupadd'] == 2) { echo "selected='selected'"; } ?>>Agency</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="row-form">   
        <div class="span1">Remarks:</div>  
        <div class="span4">
            <textarea style="width: 490px;" name="remarks" id="remarks"><?php echo $data['ao_coll_rem'] ?></textarea>
        </div>     
        <div class="clear"></div>
    </div>
    <div class="row-form">   
        <div class="span1">Return:</div>  
        <div class="span4">
            <textarea style="width: 490px;" name="returnrem" id="returnrem"><?php echo $data['ao_coll_returnrem'] ?></textarea>
        </div>     
        <div class="clear"></div>
    </div>

    <div class="row-form"> 
        <div class="span1" style="width:130px;margin-top:8px"><button class="btn btn-success" id="saveassign" type="button">Save Assign</button></div>               
        <div class="span1" style="width:200px;margin-top:8px"><button class="btn btn-normal" id="viewinvoicebutton" type="button">View Invoice Detail</button></div>  
        <div class="span1" style="width:130px;margin-top:5px;color: red"><h4><?php echo $inv; ?></h4></div>             
        <div class="clear"></div>
    </div>
</div>

<script>
$("#pickup2").datepicker({dateFormat: 'yy-mm-dd'}); 
$("#followup2").datepicker({dateFormat: 'yy-mm-dd'}); 

$('#viewinvoicebutton').click(function() {
    var invnum = "<?php echo $inv ?>"; 
    
    $.ajax({
        url: '<?php echo site_url('collectionutility/viewinvoicedata') ?>',    
        type: 'post',
        data: {invnum: invnum}, 
        success: function(response) {    
            var $response = $.parseJSON(response);        
            
            $('#viewinvoice').html($response['viewinvoice']).dialog('open');  
        }    
    }); 
    
    $('#viewinvoice').dialog('open');
});
 
$('#saveassign').click(function(){
    var invnum = "<?php echo $inv ?>";
    var acollasst = $('#acollasst').val();  
    var acollector = $('#acollector').val();  
    var pickup = $('#pickup2').val();  
    var followup = $('#followup2').val();  
    var remarks = $('#remarks').val();  
    var returnrem = $('#returnrem').val();  
    var pickupaddress = $('#pickupaddress').val();  
    
    $.ajax({
        url: '<?php echo site_url('collectionutility/saveassign') ?>',
        type: 'post',
        data: {invnum: invnum, acollasst: acollasst, acollector: acollector, pickup: pickup,
               followup: followup, remarks: remarks, returnrem: returnrem, pickupaddress: pickupaddress},
        success: function(response) {
            
            $("#generatereport" ).trigger( "click" );     
            $("#assignview").dialog("close");
            //$('.checkselect').removeAttr('checked');             
        }    
    });
     
});
</script>
