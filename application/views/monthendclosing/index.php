<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">

    <div class="row-fluid">

        <div class="span4">
        <div class="head">
            <div class="isw-list"></div>
            <h1>Month End Closing</h1>
            <div class="clear"></div>
        </div>
        <div class="block-fluid">                        
            
            <div class="row-form">
               <div class="span2">Closing Year</div>
               <div class="span3"><input type="text" class="datepicker" placeholder="YYYY" name="endyear" id="endyear" value="<?php echo $monthend['last_yr'] ?>"></div>
               <div class="span2">Closing Month</div>
               <div class="span3"><input type="text" class="datepicker" placeholder="MM" name="endmonth" id="endmonth" value="<?php echo $monthend['last_mon'] ?>"></div>
               <div class="clear"></div>
            </div>                         
            
            <div class="row-form">        
               <div class="span3"><button class="btn btn-success" type="button" name="closemonth" id="closemonth">Close Month</button></div> 
               <div class="span8"><div id="monend" style="color: red; font-size: 30px"><?php echo $monthend['last_yr'].' - '.$monthend['lastmon'] ?></div></div> 
               <div class="clear"></div>
            </div>     
            
                      
        </div>
        </div>

    </div>                

    <div class="dr"><span></span></div>                

</div>  

<script>
//$('#tSortable1, #tSortable2').dataTable({});

$("#endyear").datepicker({dateFormat: 'yy'});
$("#endmonth").datepicker({dateFormat: 'mm'});

var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

$("#closemonth").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#endyear', '#endmonth'];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) {
        $.ajax({
            url: "<?php echo site_url('monthendclosing/closethismonth') ?>",
            type: "post",
            data: {endyear : $("#endyear").val(), endmonth: $("#endmonth").val()},
            success: function(response) {
                 
                 
                 $response = $.parseJSON(response);   
                 
                 alert('Successfully Close Month End');    
                 
                 $('#monend').html($response['monthend']['last_yr']+' - '+$response['monthend']['lastmon'])  
                 
                 
            }
        });        
    } else {            
        return false;
    }    
});

</script>
