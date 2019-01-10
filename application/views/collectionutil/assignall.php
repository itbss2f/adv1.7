<div class="block-fluid">
    <div class="row-form">
        <div class="span1">Coll Asst:</div>
        <div class="span3">
            <select name="acollasst" id="acollasst">                    
                <?php foreach ($collasst as $collasst) : ?>
                <option value="<?php echo $collasst['user_id'] ?>"><?php echo $collasst['firstname'].' '.$collasst['lastname'] ?></option>    
                <?php endforeach; ?>                    
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="row-form">
        <div class="span1">Collector:</div>
        <div class="span3">
            <select name="acollector" id="acollector">                    
                <?php foreach ($coll as $coll) : ?>
                <option value="<?php echo $coll['user_id'] ?>"><?php echo $coll['firstname'].' '.$coll['lastname'] ?></option>    
                <?php endforeach; ?>                    
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="row-form">
        <div class="span1">Pickup:</div>
        <div class="span1">
            <input type="text" placeholder="Date" class="datepicker" id="pickup" name="pickup">
        </div>
        <div class="span1">Followup:</div>
        <div class="span1">
            <input type="text" placeholder="Date" class="datepicker" id="followup" name="followup"> 
        </div>
        <div class="clear"></div>
    </div>
    <div class="row-form">   
        <div class="span1">Remarks:</div>  
        <div class="span3">
            <textarea cols="2" rows="3" name="remarks" id="remarks"></textarea>
        </div>     
        <div class="clear"></div>
    </div>
    <div class="row-form">   
        <div class="span1">Return:</div>  
        <div class="span3">
            <textarea cols="2" rows="3" name="returnrem" id="returnrem"></textarea>
        </div>     
        <div class="clear"></div>
    </div>
    <div class="row-form">
        <div class="span2">Pickup Address:</div>
        <div class="span2">
            <select name="pickupaddress" id="pickupaddress">
                <option value="1">Client</option>
                <option value="2">Agency</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>
    
    <div class="row-form"> 
        <div class="span1" style="width:130px;margin-top:12px"><button class="btn btn-success" id="saveassignall" type="button">Save Assign All</button></div>               
        <div class="clear"></div>
    </div>
</div>

<script>
 $(".datepicker").datepicker({dateFormat: 'yy-mm-dd'}); 
 
 $('#saveassignall').click(function() {                 
    var acollasst = $('#acollasst').val();  
    var acollector = $('#acollector').val();  
    var pickup = $('#pickup').val();  
    var followup = $('#followup').val();  
    var remarks = $('#remarks').val();  
    var returnrem = $('#returnrem').val();  
    var pickupaddress = $('#pickupaddress').val();  
    
    $.ajax({
        url: "<?php echo site_url('collectionutility/saveAssignAll')?>",
        type: "post",
        data: {acollasst: acollasst, acollector: acollector, pickup: pickup,
               followup: followup, remarks: remarks, returnrem: returnrem, pickupaddress: pickupaddress, chck: chck},
        success: function (response) {
            $( "#generatereport" ).trigger( "click" );    
            
            $("#assignallview").dialog("close");
            $('.checkselect').removeAttr('checked');         
            
        }
    })
 });
</script>