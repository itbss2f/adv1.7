<style>
.bg-text
{
    color:#00FFFF;
    font-size:15px;
    transform:rotate(30deg);
    -webkit-transform:rotate(30deg); 
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    margin-top: -9px;

}
</style>
<script>
	var page  = "";
	var key = '<?php echo $key ?>';
	var product = '<?php echo $product ?>';
	var date = '<?php echo $date ?>';
	var xpos = 0;
	var ypos = 0;
	var boxwidth = 0;
	var pagewidth = 0;
	var pageheight = 0;
	var dragposition = "";		
	var origx = 0;
	var origy = 0;	
</script>

<?php 
$font = "font-size: 13px;";
$totalpages = count($pages);
		switch ($viewing)
		{
			case 1:
				$grid = "gridlarge_trans.png";
				$gridsnap = "grid: [ 35,5 ],";
				$font = "font-size: 13px;";
			break;
			case 2:
				$grid = "gridmedium_trans.png";
				$gridsnap = "grid: [ 29,4 ],";
				$font = "font-size: 12px;";
			break;
			case 3:				
				$grid = "gridsmall_trans.png";
				$gridsnap = "grid: [ 23,3 ],";
				$font = "font-size: 11px;";
			break;
			case 4:
				$grid = "gridxsmall_trans.png";
				$gridsnap = "grid: [ 17,2 ],";
				$font = "font-size: 10px;";
			break;
			case 5:
				$grid = "gridxxsmall_trans.png";
				$gridsnap = "grid: [ 8,1 ],";
				$font = "font-size: 8px;";
			break;
			case 6:
				$grid = "gridxxxsmall_trans.png";
				$gridsnap = "grid: [ 4,.5 ],";
				$font = "font-size: 8px;";
			break;
            case 7:
                $grid = "grid15_trans.png";
                $gridsnap = "grid: [ 12,1.5],";
                $font = "font-size: 8px;";
            break;   
		}
for ($a = 0 ; $a < $totalpages; $a++) {
	if (empty($pages[$a]['color_html'])) { $pages[$a]['color_html'] = "EDEDED";}
	$color = "border:2px solid #".$pages[$a]['color_html']."";
	$colorcode = $pages[$a]['color_code'];			
	if ($pages[$a]['is_merge'] != "x") {		
?>
<div class="pagecon" id="pageconpagex<?php echo $pages[$a]['layout_id'] ?>" align="center" style="width: <?php echo $pages[$a]['columnpixel'] ?>px; height: <?php echo $pages[$a]['lenpixel'] + 20 ?>px;">
	<div class="pagediv" data-page="<?php echo $pages[$a]['in_page'] ?>" id="pagex<?php echo $pages[$a]['layout_id'] ?>" style="position: relative; width: <?php echo $pages[$a]['columnpixel'] ?>px; height: <?php echo $pages[$a]['lenpixel']?>px; <?php echo $color ?>; background: <?php echo $pages[$a]['pagecolor'] ?> url(<?php echo base_url(); ?>assets/images/dummy/<?php echo $grid ?>);">
		<!-- Boxes inside -->
		<script>			
			$.ajax({
				url: '<?php echo site_url('displaydummy/dummy/ajxRetMyOwnBox') ?>',
				type: 'post',
				data: {page: <?php echo "'pagex",$pages[$a]['layout_id']."'" ?>, key: key, product: product, date: date, viewing: $("#viewing").val()},
				success: function(response) {
					var $response = $.parseJSON(response);
					
					/* Algo for xpos and ypos percentage */	
					var $xminus = 0;					
					var $yminus = 0;						
					var	v = $("#viewing").val();

					if (v == 1) {
						$xminus = 35;
						$yminus = 5;
					} else if (v == 2) {
						$xminus = 29;
						$yminus = 4;						
					} else if (v == 3) {
						$xminus = 23;
						$yminus = 3;
					} else if (v == 4) {
						$xminus = 17;
						$yminus = 2;
					} else if (v == 5) {
						$xminus = 8;
						$yminus = 1;
					} else if (v == 6) {
						$xminus = 4;
						$yminus = .5;
					} else if (v == 7) {
                        $xminus = 12;
                        $yminus = 1.5;
                    }                        						
					
					// Append Box to the corresponding page all
					
					
					$.each($response['box'], function(i){
						var $item = $response['box'][i];
						
						/* Modulus */	
						var xmod = parseInt($item['xaxis'] / 35);				
						var $xpos = xmod * $xminus;
						var ymod = parseInt($item['yaxis'] / 5);				
						var $ypos = ymod * $yminus;
						var $divcolor = "";		
                        var $divmaterial = "";
                        var $txtcolor = "color: #000000;";					
						if ($item['component_type'] == "blockout") {
							$divcolor = "background: #E6E6E6";							
						} else {
							if ($item['color_html'] == "") {
								$divcolor = "background: #918F8F";
							} else {
								$divcolor = "background: #"+$item['color_html'];
							}
						} 
                        
                        if ($item['material_status'] == "U") {
                            $divmaterial = "<p class='bg-text'>MATERIAL</p>";    
                        }   
                        
						if ($item['component_type'] == "blockout") {
                            var $title =  $item['ao_num']+' '+ $item['box_description'] +' '+ $item['columns'] +' x '+ $item['len'] ;  
                            var $box = "<div class='draggablebox' title='"+$title+"' id='" + $item['layout_boxes_id'] + "' style='overflow:hidden;-width:100%;display:block;position: absolute; z-index: 20; width:" + $item['columnpixel'] + "px;  height: " + $item['lenpixel'] + "px; left: " + $xpos + "px; top: " + $ypos + "px;" + $divcolor +";<?php echo $font ?>'><p>"+$item['ao_num']+" "+ $item['box_description'] + " " + $item['columns'] + " x " + $item['len'] + "</p></div>";
                        } else {	
                            if ($item['amt'] == 'x') {
                                $txtcolor = "color: #FFFF00;";    
                            }                                              	
                            var $title = $item['ao_num']+' '+ $item['ao_payee'] +' '+ $item['colx'] +' x '+ $item['lenx']+' '+ $item['ao_part_records']+' '+ $item['agencyname']+' '+ $item['agencycontacts']+' '+$item['inputdate']+' Production: '+$item['ao_eps']+' Entered: '+$item['entered'];  															
						                            
                            if ($item['ao_paginated_status'] == '1') {
                                var $box = "<div class='draggableboxpage' data-value='pr' title='"+$title+"' id='" + $item['layout_boxes_id'] + "' style='"+$txtcolor+"overflow:hidden;-width:100%;display:block;outline:5px dotted #FFFF00;outline-offset:-2px;position: absolute; z-index: 20; width:" + $item['columnpixel'] + "px;  height: " + $item['lenpixel'] + "px; left: " + $xpos + "px; top: " + $ypos + "px;" + $divcolor +";<?php echo $font ?>'><p>"+$item['ao_num']+" "+ $item['ao_payee'] + " " + $item['colx'] + " x " + $item['lenx'] + "</p>" + $divmaterial + "</div>";     
                            }
                            else if ($item['is_lock'] == '1') {
                                var $box = "<div class='draggableboxlock' data-value='pr' title='"+$title+"' id='" + $item['layout_boxes_id'] + "' style='"+$txtcolor+"overflow:hidden;-width:100%;display:block;outline:3px dotted #000000;outline-offset:-2px;position: absolute; z-index: 20; width:" + $item['columnpixel'] + "px;  height: " + $item['lenpixel'] + "px; left: " + $xpos + "px; top: " + $ypos + "px;" + $divcolor +";<?php echo $font ?>'><p>"+$item['ao_num']+" "+ $item['ao_payee'] + " " + $item['colx'] + " x " + $item['lenx'] + "</p>" + $divmaterial + "</div>";                                        
                            }  else {
                                var $box = "<div class='draggablebox' data-value='pr' title='"+$title+"' id='" + $item['layout_boxes_id'] + "' style='"+$txtcolor+"overflow:hidden;-width:100%;display:block;position: absolute; z-index: 20; width:" + $item['columnpixel'] + "px;  height: " + $item['lenpixel'] + "px; left: " + $xpos + "px; top: " + $ypos + "px;" + $divcolor +";<?php echo $font ?>'><p>"+$item['ao_num']+" "+ $item['ao_payee'] + " " + $item['colx'] + " x " + $item['lenx'] + "</p>" + $divmaterial + "</div>";
                            }  
                        }
						$("#pagex<?php echo $pages[$a]['layout_id'] ?>").fadeIn('slow').append($box);
						
						$("#"+ $item['layout_boxes_id'] +"").click(function(){	
                            
                            
                                var boxid = $item['layout_boxes_id'];                    
                                var selection = $(":input[name='selection']").val();                            
                                if (selection == "Unflow") {
                                    
                                    var unfl = $(this).attr('class');
                            
                                    if (unfl == "draggableboxpage") {
                                        alert("Cannot unflow ads is paginated!");
                                    } else {
                                        $.ajax({
                                            url: "<?php echo site_url('displaydummy/dummy/unflow') ?>",
                                            type: "post",
                                            data: {product: product, date: date, key: key, boxid: boxid},
                                            success: function(response){    
                                                var $response = $.parseJSON(response);        
                                                $("#"+ boxid).remove();
                                                $("#content2-content").html($response['listad']);            
                                            }
                                        });  
                                    }                                                                              
                                }    
                            
                               if (selection == "Material") {
                                   window.open("<?php echo site_url('displaydummy/dummy/material') ?>/"+boxid, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=600, height=500");
                               }

						});									
					});		

					$(".draggablebox").draggable({
						opacity: 0.40,            
						cursor: "crosshair",
						revert: "invalid",
						<?php echo $gridsnap ?>
						start: function(event,ui){
							dragposition = ui.position;							
							/* var self = ui.instance; */							
							/*origx = $(this).css('left');							
							origy = $(this).css('top');*/							
						},						
					});                                    
				}				
			});			
						
		</script>
		<!-- Boxes inside -->
	</div>
	<div class="pagedivname" align="center" style="width: <?php echo $pages[$a]['columnpixel'] + 2 ?>px; height: 20px;" title='<?php echo $a+ 1 ?>'>
		<!-- Name, Color, Description inside -->
		<?php 
		$wd = "30%";
		if (!empty($pages[$a]['is_merge'])) {
		$wd = "15%";
		}
		?>
		<div style="width: <?php echo $wd?>; float: left; text-align: left; <?php echo $font; ?>"><?php echo $pages[$a]['book_name'];?></div>
		<div id = "namepagex<?php echo $pages[$a]['layout_id'] ?>" style="width: <?php echo $wd?>; float: left; text-align: center;<?php echo $font; ?>">&nbsp;<?php echo $pages[$a]['class_code'];?></div>
		<div style="width: <?php echo $wd?>; float: left; text-align: right;<?php echo $font; ?>"><?php echo $pages[$a]['folio_number']; ?></div>
		<?php
		if (!empty($pages[$a]['is_merge'])) {
		?>
		<div style="width: <?php echo $wd?>; float: left; text-align: left; margin-left: 5%; <?php echo $font; ?>"><?php echo $pages[$a]['book_name2'];?></div>
		<div id = "mergenamepagex<?php echo $pages[$a]['layout_id'] ?>" style="width: <?php echo $wd?>; float: left; text-align: center;<?php echo $font; ?>">&nbsp;<?php echo $pages[$a]['class_code2'];?></div>
		<div style="width: <?php echo $wd?>; float: left; text-align: right;<?php echo $font; ?>"><?php echo $pages[$a]['folio_number2']; ?></div>
		<?php
		}
		?>				
	</div>	
</div>
<?php
	}
}
?>
<script type="text/javascript">	

	$(".pagediv").droppable({
			accept: ".draggablebox",
			drop: function( event, ui ) {					
				page = $(this).attr("id");  				
				box = ui.draggable.attr("id");  			
				xpos = ui.offset.left - $(this).offset().left;					
				ypos = ui.offset.top - $(this).offset().top;	
				pagewidth = $(this).width();	
				pageheight = $(this).height();									
				PgBxValidation(event, ui);											
			}			
	});		
	
	function PgBxValidation(event, ui)
	{
				$.ajax({
					url: '<?php echo site_url('displaydummy/dummy/ajxPgBxVal') ?>',
					type: 'post',
					data: {product: product, date: date, key: key, page: page, box: box},
					success: function (response) {
						var $response = $.parseJSON(response);
						if($response['isColorValid'] == false) {
							/* Invalid */
							alert('The box color not valid to be dummy in this page!.');	
							
							//alert(dragposition.left);
							//alert(dragposition.top);
																			
							ui.draggable.css({
							  'left': dragposition.left,
							  'top': dragposition.top
							});							
							//ui.draggable.revert();	
							//origx = 0;
						} else {
							/* Valid */									
							layoutBoxToPage(event, ui, key, page, box);
						}								
					}
				});		
				
	}
			
	$(".pagediv").click(function() {
		var pageID = $(this).attr("id");
		var selection = $(":input[name='selection']").val();
		
		if (selection == "" || selection == "Unflow" || selection == "Remarks" || selection == "Lockbox" || selection == "Unlockbox") {			
			return false;
		} else {            
			$.ajax({
				url: '<?php echo site_url('displaydummy/dummy/ajxtSetValue') ?>',
				type: 'post',
				data: {key: key, product: product, date: date, pageID: pageID, selection: selection},
				success: function(response) {
					var $response = $.parseJSON(response);
					
					if ($response['validcolor'] == true) {
						$("#"+pageID).css('border', '2px solid #'+$response['color_html']);
					} 
					if ($response['validcolorrank'] == false) {
						alert("Cannot change page color there are box ads that has a higher color rank!.");
					}
					if ($response['validasection'] == true) {
                        $('#'+pageID).css({'background-color': $response['pagecolor']})
						$("#name"+pageID).html($response['section']);
						$("#mergename"+pageID).html($response['section']);
					}
					if ($response['validdelete'] == true) {
						alert("Cannot delete page there are box ads you must unflow all the box in this page!.");
					}
                    if ($response['validdelete2'] == true) {
                        alert("Cannot delete page. Please unmerge first!.");     
                    }		
					if ($response['validdelete'] == false) {
						//$(this).remove();			
						$(".pagecon").remove("#pagecon"+pageID);
					}
                    $("#totalpages").html($response['totalpages']);     
				}
			});		
		}
		
	});
	
	$(".pagediv").dblclick(function() {
		var pageID = $(this).attr("id");
        
		$.ajax({
			url: '<?php echo site_url('displaydummy/dummy/openFolio') ?>',
			type: 'post',
			data: {key: key, product: product, date: date, pageID: pageID, viewing: $("#viewing").val()},
			success: function(response) {
				var $response = $.parseJSON(response);
				jQuery.facebox($response['folio']);
			}
		});
	});
    
    //class='draggablebox' data-value='prod_remarks'
    //$('.draggablebox ui-draggable').dblclick(function() {
    //    alert('test'); 
    //});
    
    function productionremarks(id) {
        var boxid = id;        
        $.ajax({
            url: "<?php echo site_url('displaydummy/dummy/productremarksview') ?>",
            type: 'post',
            data: {boxid: boxid},
            success: function(response) {
                var $response = $.parseJSON(response);
                
                $('#prod_remark').html($response['productremarksview']).dialog('open');
            }    
        });
    }
    
    function do_lockbox(id) {    
        //Do is_lock to 2           
        $.ajax({
            url: "<?php echo site_url('displaydummy/dummy/do_lock') ?>",
            type: 'post',
            data: {boxid: id},
            success:function(response) {
                $('#'+id).draggable( "destroy" );
                $('#'+id).css({'outline': '3px dotted #000000', 'outline-offset':'-2px'});                
            }
        });         
    }
    
    function do_unlockbox(id) {    
        //Do is_lock to 3        
        $.ajax({
            url: "<?php echo site_url('displaydummy/dummy/do_unlock') ?>",
            type: 'post',
            data: {boxid: id},
            success:function(response) {
                $('#'+id).draggable();
                $('#'+id).attr('class', 'draggablebox');    
                $('#'+id).css({'outline': 'none'});            
            }
        });                
    }
    
						
</script>
