<div class="block-fluid">
    <div class="row-form">
        <div class="span1">Invoice:</div>
        <div class="span1">
        <input type="text" placeholder="FROM" class="datepicker" id="invdate3" name="invdate3" value='1986-01-01' style="width: 200px;">
        </div>
        <div class="span1">Invoice:</div>
        
        <div class="span1">
        <input type="text" placeholder="TO" class="datepicker" id="invto3" name="invto3" value='<?php echo date('Y-m-d') ?>' style="width: 200px;">
        </div>
        
         <div class="clear"></div>
    </div>
    
    <div class="row-form">   
        <div class="span1">Pickup:</div>         
        <div class="span1">
        <input type="text" placeholder="PICKUP" class="datepicker" id="pickup3" name="pickup3"  style="width: 200px;">
        </div>
        
        <div class="span1">Followup:</div>  
        <div class="span1">
        <input type="text" placeholder="FOLLOWUP" class="datepicker" id="followup3" name="followup3"  style="width: 200px;">
        </div>
        
        <div class="clear"></div>
    </div>
    
     <div class="row-form">
        <div class="span2">Assign Collection Asst:</div>
        <div class="span3" style="width: 200px;">
            <select name="pcollasst" id="pcollasst">
            <option value="0">All</option>  
            <?php foreach ($collasst2 as $collasst) : ?>

            <option value="<?php echo $collasst['user_id'] ?>"><?php echo $collasst['firstname'].' '.$collasst['lastname'] ?></option>    

            <?php endforeach; ?>                    
            </select>        
        </div>
        <div class="clear"></div>
    </div>
    
    <div class="row-form">          
        <div class="span2">Assign Collector:</div>
        <div class="span3" style="width: 200px;">
            <select name="pcollector" id="pcollector">
            <option value="0">All</option>
            <?php foreach ($coll2 as $coll) : ?>
            <option value="<?php echo $coll['user_id'] ?>"><?php echo $coll['firstname'].' '.$coll['lastname'] ?></option>    
            <?php endforeach; ?>                    
            </select>        
        </div>
        <div class="clear"></div>
    </div>

    <div class="row-form"> 
        <div class="span1" style="width:130px;margin-top:8px"><button class="btn btn-success" id="viewprintout" type="button">View Printout</button></div>               
        <div class="clear"></div>
    </div>
</div>

<script>
$("#invdate3").datepicker({dateFormat: 'yy-mm-dd'}); 
$("#invto3").datepicker({dateFormat: 'yy-mm-dd'}); 
$("#pickup3").datepicker({dateFormat: 'yy-mm-dd'}); 
$("#followup3").datepicker({dateFormat: 'yy-mm-dd'}); 
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$('#viewprintout').click(function() {

    var countValidate = 0;  
    var validate_fields = ['#invdate3', '#invto3'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
    var invfrom = $('#invdate3').val();    
    var invto = $('#invto3').val();    
    var pickup = $('#pickup3').val();    
    var followup = $('#followup3').val();   
    var acollasst = $('#pcollasst').val(); 
    var acollector = $('#pcollector').val(); 
    
    
    window.open("<?php echo site_url('collectionutility/iteprintout') ?>?invfrom="+invfrom+"&invto="+invto+"&pick="+pickup+"&followup="+followup+"&acollasst="+acollasst+"&acollector="+acollector, "_blank", "scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
    }
});
</script>