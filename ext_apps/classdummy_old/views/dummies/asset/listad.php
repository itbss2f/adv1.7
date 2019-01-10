<?php   
if (!empty($ads)) {
$totalads = count($ads);
?>
<div class='divsorter'>
    <table id="myTable" class="tablesorter" style='white-space:nowrap;font-size:11px'> 
        <thead> 
        <tr> 
            <th style='width:20px'>BOX</th>
            <th style='width:45px'>AO NUM</th>                
            <th style='width:45px'>CODE</th>        
            <th style='width:120px'>ADVERTISER</th>                
            <th style='width:40px'>CLASS</th>        
            <th style='width:40px'>WIDTH</th>                
            <th style='width:40px'>LENGTH</th>                                                    
            <th style='width:150px'>RECORDS</th>                                    
            <th style='width:40px'>STATUS</th>                                
        </tr> 
        </thead> 
        <tbody class='table_body'> 
        <?php 
        for ($a = 0; $a < $totalads; $a++) {
            if ($ads[$a]['color_html'] == "") {
                $divcolor = "background: #918F8F";                    
            } else {                    
                $divcolor = "background: #".$ads[$a]['color_html'];                    
            }
            $title = @$ads[$a]['ao_num'].' '.@$ads[$a]['agencyname'].' '.@$ads[$a]['agencycontacts'].' '.@$ads[$a]['ao_width'].' x '.@$ads[$a]['ao_length'].' '.@$ads[$a]['ao_part_records'].' '.@$ads[$a]['ao_date'];            
            #$title = @$ads[$a]['ao_num'].' '.@$ads[$a]['agencyname'].' '.@$ads[$a]['agencycontacts'].' '.@$ads[$a]['ao_part_records'];            
        ?>
        <tr id='trlist_<?php echo $ads[$a]['id'] ?>' title="<?php echo $title ?>" <?php if (@$ads[$a]['status'] == "F") { echo "style='background: gray'"; } if (@$ads[$a]['is_flow'] == "2") { echo "style='background: #F78181'"; } ?> class='tbody'>
            <td style='width:20px' align="center">
                <?php 
                if (@$ads[$a]['status'] != "F" && @$ads[$a]['is_flow'] != "2" && @$ads[$a]['is_flow'] != "1") {                    
                ?>
                <div class="draggablebox" style="<?php echo $divcolor ?>" id="<?php echo $ads[$a]['id']?>"></div>                    
                <?php
                } else { ?>
                <input type='radio' name='radio_find' class='radio_find' value='<?php echo $ads[$a]['id']?>'>
                <?php     
                }                    
                ?>
            </td>
            <td style='width:45px;text-align:center;'><?php echo @$ads[$a]['ao_num'] ?></td>                
            <td style='width:45px;text-align:left;'><span style='overflow:hidden;width:45px;display:block'><?php echo @$ads[$a]['ao_cmf'] ?></span></td>        
            <td style='width:120px;text-align:left;'><span style='overflow:hidden;width:120px;display:block'><?php echo @$ads[$a]['ao_payee'] ?></span></td>                
            <td style='width:40px;text-align:left;'><span style='overflow:hidden;width:40px;display:block'><?php echo @$ads[$a]['class_code'] ?></span></td>        
            <td style='width:40px;text-align:left;'><?php echo @$ads[$a]['ao_width'] ?></td>                
            <td style='width:40px;text-align:left;'><?php echo @$ads[$a]['ao_length'] ?></td>                                                    
            <td style='width:150px;text-align:left;'><span style='overflow:hidden;width:150px;display:block'><?php echo @$ads[$a]['ao_part_records'] ?></span></td>                                    
            <td style='width:40px;text-align:center;'><?php echo @$ads[$a]['status'] ?></td>                                
        </tr>
        <?php
        }
        ?>  
        </tbody> 
    </table> 
</div>  
<?php	
} else {
	echo "No Booking Record For This Date!";
}

?>
<style>
	.draggablebox { width: 15px; 
				    height: 15px; 				 				    
					display: block;					
			      }
</style>
<script>
$(document).ready(function() { 
        $("#myTable").tablesorter(); 
        var box = "";
        $(".draggablebox").draggable({
            opacity: 0.40,            
            cursor: "crosshair",
            start: function(event,ui){
                dragposition = ui.position;
            },        
            revert: "invalid",
            
        });
        
        $('.radio_find').click(function() {
            
            var $bid = $('.radio_find:checked').val();
            //var $bid = this.val();
            //outline: solid black 5px;
            //outline-offset: 5px;
            $('.draggablebox').css('outline', '0px none #FFFFFF');
            $('#'+$bid).css({'outline': '2px solid #007ACC', 'outline-offset': '-2px'});
            //alert($bid);    
            
        });    
}); 

</script>