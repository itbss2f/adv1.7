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
            
            $stringrem = @$ads[$a]['ao_eps'];
            /*$explode = explode("||", $prod_remarks);
            $explode2 = "";
            $stringrem = "";
            $count = 0;
            if ($prod_remarks != '') {     
            foreach ($explode as $exp) {
                //print_r2($exp);
                $explode2[] = explode("@*",$exp);
                //print_r2($explode2);
                $stringrem .= $explode2[$count][1]." ";
                
                $count += 1;
            }
            } */
            
            $title = @$ads[$a]['ao_num'].' '.@$ads[$a]['agencyname'].' '.@$ads[$a]['agencycontacts'].' '.@$ads[$a]['ao_width'].' x '.@$ads[$a]['ao_length'].' '.@$ads[$a]['ao_part_records'].' AO Date: '.@$ads[$a]['ao_date'].' Production: '.@$stringrem.' Entered: '.@$ads[$a]['entered'];            
            #$title = @$ads[$a]['ao_num'].' '.@$ads[$a]['agencyname'].' '.@$ads[$a]['agencycontacts'].' '.@$ads[$a]['ao_part_records'];            
        ?>
        <tr id='trlist_<?php echo $ads[$a]['id'] ?>' title="<?php echo $title ?>" <?php if (@$ads[$a]['ao_amt'] <= 0) { echo "style='background: red'"; } ?> <?php if (@$ads[$a]['status'] == "F") { echo "style='background: gray'"; } if (@$ads[$a]['is_flow'] == "2") { echo "style='background: #F78181'"; } ?> class='tbody'>
            <td style='width:20px' align="center">
                <?php 
                if (@$ads[$a]['is_dummycancel'] == "1") {
                ?>
                <div class="ndraggablebox" style="border:1px solid #000;<?php echo $divcolor ?>" id="<?php echo $ads[$a]['id']?>"></div>                    
                <?php    
                } else if (@$ads[$a]['status'] != "F" && @$ads[$a]['is_flow'] != "2" && @$ads[$a]['is_flow'] != "1") {                    
                ?>
                <div class="draggablebox" style="<?php echo $divcolor ?>" id="<?php echo $ads[$a]['id']?>"></div>                    
                <?php
                } else { ?>
                <div class="boxcfcolor" style="<?php echo $divcolor ?>"><input type='radio' name='radio_find' class='radio_find' value='<?php echo $ads[$a]['id']?>'> </div>
                <?php     
                }                    
                ?>
            </td>
            <td style='width:45px;text-align:center;'><a href="#" class="aonum" id="<?php echo @$ads[$a]['id'] ?>"><?php echo @$ads[$a]['ao_num'] ?></a></td>                
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
            $('#'+$bid).css({'outline': '4px solid #0040FF', 'outline-offset': '-2px'});
            //alert($bid);    
            
        }); 
        
        $(".aonum").click(function(){
            var xid = $(this).attr('id');    
            var show = $('#filtershow').val();
            
            
            if (show == 0 || show == 3) {
                var ans = confirm('Are you sure to hide/unhide this AO rundate?');  
                
                if (ans) {
                    
                    $.ajax({
                        url: '<?php echo site_url('classdummy_modular/dummy/ajxHideAds') ?>',
                        type: 'post',
                        data: {xid: xid},
                        success: function(response) {      
                        
                             // Do Nothing   
                            $('#trlist_'+xid).hide();    
                        }    
                    });
                } 
            }    
        });   
}); 

</script>