<script>

var $boxlayout = "";
var $divcolor = "";
var $font = '13px';
				
function layoutBoxToPage(event, ui, key, page, box)
{	
    var $viewing = $('#viewing').val();    
    if ($viewing == 1) {
        $font = "13px;";              
    } else if ($viewing == 2) {
        $font = "12px;";              
    } else if ($viewing == 3) {
        $font = "11px;";              
    } else if ($viewing == 4) {
        $font = "10px;";              
    } else if ($viewing == 5) {
        $font = "8px;";              
    } else if ($viewing == 6) {
        $font = "8px;";              
    } else if ($viewing == 7) {
        $font = "8px;";              
    }         
	$.ajax({
		url: '<?php echo site_url('displaydummy/dummy/ajxLayBxTPg') ?>',
		type: 'post',
		data: {product: product, date: date, key: key, page: page, box: box, xpos: xpos, ypos: ypos, viewing: $("#viewing").val()},
		success: function(response) {
			var $response = $.parseJSON(response);	
            
            $('#trlist_'+box).hide();					
			//$("#content2-content").html($response['listad']);	
						if ($response['box']['component_type'] == "blockout") {
							$divcolor = "background: #918F8F";							
						} else {							
							$divcolor = "background: #"+$response['box']['color_html'];						
						}	
            
            
                        
			if ($response['todo'] == "I") {		
                var $txtcolor = "color: #000000;";                    
                if ($response['box']['amt'] == 'x') {
                    $txtcolor = "color: #FFFF00;";    
                }                                                  
				var $title = $response['box']['ao_num']+' '+ $response['box']['ao_payee'] +' '+ $response['box']['colx'] +' x '+ $response['box']['lenx'] ;     										
				$boxlayout = "<div class='draggablebox' title='"+$title+"' id='" + $response['box']['layout_boxes_id'] + "' style='"+$txtcolor+"overflow:hidden;-width:100%;display:block;position:absolute;font-size:"+$font+" z-index: 50; width:" + $response['box']['columnpixel'] + "px;  height: " + $response['box']['lenpixel'] + "px;" + $divcolor + ";'><p>" + $response['box']['ao_num'] + " " + $response['box']['ao_payee'] + " " + $response['box']['colx'] + " x " + $response['box']['lenx']  + "</p></div>";								
				$('#'+page).append($boxlayout);																												
					
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
				
				/* Start Algo for x and y position */	
								
				var totalxp = parseInt(xpos) + parseInt($response['box']['columnpixel']);		
				var totalyp = parseInt(ypos) + parseInt($response['box']['lenpixel']);	
				if (totalxp >= pagewidth) {
					var minusw = parseInt(totalxp) - pagewidth;
					xpos = xpos - minusw;
				} else if (xpos < 0) {					
					xpos = 0;
				}		
				if (totalyp >= pageheight) {				
					var minush = parseInt(totalyp) - pageheight;
					ypos = ypos - minush;
				}else if (ypos < 0) {					
					ypos = 0;
				}	
				
				/* Modulus for view */				
				var xmod = parseInt(xpos) / parseInt($xminus);				
				var $xpos = parseInt(xmod) * parseInt($xminus);
				var ymod = parseInt(ypos) / parseInt($yminus);				
				var $ypos = parseInt(ymod) * parseInt($yminus);
								
				$("#"+page).children('#'+box).css({top: $ypos , 
				                                   left: $xpos,});	
																		
				/* Allocated Area*/
				allocatedAreaValidate(event, ui, box, $xpos, $ypos, $response['box']['width'], $response['box']['height']);
				/* End Algo for x and y position */				
						
				updateBoxPosition(product, date, key, page, box, $xpos, $ypos)
															
				$(".draggablebox").draggable({
					opacity: 0.40,            
					cursor: "crosshair",					
					revert: "invalid",
					grid: [ $xminus, $yminus ],
					start: function(event,ui){
						dragposition = ui.position;
					},					
				});	 	
				
						// Allowing to unflow the ads
		
						$("#"+ $response['box']['layout_boxes_id'] +"").click(function(){		
							var boxid = $response['box']['layout_boxes_id'];		
							var selection = $(":input[name='selection']").val();							
							if (selection == "Unflow") {
								$.ajax({
									url: "<?php echo site_url('displaydummy/dummy/unflow') ?>",
									type: "post",
									data: {product: product, date: date, key: key, boxid: boxid, viewing: $("#viewing").val()},
									success: function(response){	
										var $response = $.parseJSON(response);		
										$("#"+ boxid).remove();
										//$("#content2-content").html($response['listad']);			
									}
								});																				
							}
						});	
								
				$boxlayout = "";								
			} else if ($response['todo'] == "U") {	
				/* Getting position */					
				ypos = $("#"+page).children('#'+box).css("top");
				xpos = $("#"+page).children('#'+box).css("left");	

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
				
				/* Start Algo for x and y position */	
				var totalxp = parseInt(xpos) + parseInt($response['box']['columnpixel']);		
				var totalyp = parseInt(ypos) + parseInt($response['box']['lenpixel']);		
				if (parseInt(totalxp) >= parseInt(pagewidth)) {					
					var minusw = parseInt(totalxp) - parseInt(pagewidth);
					xpos = parseInt(xpos) - parseInt(minusw);				
				} else if (parseInt(xpos) < parseInt(0)) {					
					xpos = 0;
				}		
				if (parseInt(totalyp) >= parseInt(pageheight)) {				
					var minush = parseInt(totalyp) - parseInt(pageheight);
					ypos = parseInt(ypos) - parseInt(minush);
				}else if (parseInt(ypos) < parseInt(0)) {					
					ypos = 0;
				}	
				/* End Algo for x and y position */	
								
				
				/* Modulus for view */				
				var xmod = parseInt(xpos) / parseInt($xminus);				
				var $xpos = parseInt(xmod) * parseInt($xminus);
				var ymod = parseInt(ypos) / parseInt($yminus);				
				var $ypos = parseInt(ymod) * parseInt($yminus);
								
				$("#"+page).children('#'+box).css({top: $ypos , 
				                                   left: $xpos,});	
																		
				/* Allocated Area*/
				allocatedAreaValidate(event, ui, box, $xpos, $ypos, $response['box']['width'], $response['box']['height']);
				/* End Algo for x and y position */				
						
				updateBoxPosition(product, date, key, page, box, $xpos, $ypos)
									
			} else if ($response['todo'] == "N") {	
				$('.draggablebox').closest("#"+box).remove();					
				var $title = $response['box']['ao_num']+' '+ $response['box']['ao_payee'] +' '+ $response['box']['colx'] +' x '+ $response['box']['lenx'] ;                                             
				$boxlayout = "";								
				$boxlayout = "<div class='draggablebox' title='"+$title+"' id='" + $response['box']['layout_boxes_id'] + "' style='overflow:hidden;-width:100%;display:block;position:absolute;font-size:"+$font+" z-index: 50; width:" + $response['box']['columnpixel'] + "px;  height: " + $response['box']['lenpixel'] + "px; " + $divcolor + ";'><p>" + $response['box']['ao_num'] + " " + $response['box']['ao_payee'] + " " + $response['box']['colx'] + " x " + $response['box']['lenx']  +  "</p></div>";				
				$('#'+page).append($boxlayout);
				
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
				
				/* Start Algo for x and y position */	
				var totalxp = parseInt(xpos) + parseInt($response['box']['columnpixel']);		
				var totalyp = parseInt(ypos) + parseInt($response['box']['lenpixel']);										
				if (totalxp >= pagewidth) {
					var minusw = parseInt(totalxp) - pagewidth;
					xpos = xpos - minusw;
				} else if (xpos < 0) {					
					xpos = 0;
				}		
				if (totalyp >= pageheight) {				
					var minush = parseInt(totalyp) - pageheight;
					ypos = ypos - minush;
				}else if (ypos < 0) {					
					ypos = 0;
				}	
				/* Modulus for view */				
				var xmod = parseInt(xpos) / parseInt($xminus);				
				var $xpos = parseInt(xmod) * parseInt($xminus);
				var ymod = parseInt(ypos) / parseInt($yminus);				
				var $ypos = parseInt(ymod) * parseInt($yminus);
								
				$("#"+page).children('#'+box).css({top: $ypos , 
				                                   left: $xpos,});	
																		
				/* Allocated Area*/
				allocatedAreaValidate(event, ui, box, $xpos, $ypos, $response['box']['width'], $response['box']['height']);
				/* End Algo for x and y position */				
						
				updateBoxPosition(product, date, key, page, box, $xpos, $ypos)
				$(".draggablebox").draggable({
					opacity: 0.40,            
					cursor: "crosshair", 						
					revert: "invalid",
					grid: [ $xminus, $yminus ],
					start: function(event,ui){
						dragposition = ui.position;
					},
					
				});		
				
						// Allowing to unflow the ads
		
						$("#"+ $response['box']['layout_boxes_id'] +"").click(function(){		
							var boxid = $response['box']['layout_boxes_id'];				
							var selection = $(":input[name='selection']").val();
							if (selection == "Unflow") {
								$.ajax({
									url: "<?php echo site_url('displaydummy/dummy/unflow') ?>",
									type: "post",
									data: {product: product, date: date, key: key, boxid: boxid, viewing: $("#viewing").val()},
									success: function(response){	
										var $response = $.parseJSON(response);		
										$("#"+ boxid).remove();                                        
										//$("#content2-content").html($response['listad']);			
									}
								});																				
							}  else if (selection == "Material") {
                                alert('view material');
                            }
						});							
			}
		}
					
	});
}

function updateBoxPosition(product, date, key, page, box, xpos, ypos) {
	$.ajax({
		url: '<?php echo site_url('displaydummy/dummy/ajxUpdatePost') ?>',
		type: 'post',
		data: {product: product, date: date, key: key, page: page, box: box, xpos: xpos, ypos: ypos, viewing: $("#viewing").val()},
		success: function(response) {
			return true;
		}
	});
}

// TODO

function allocatedAreaValidate(event, ui, box, xpos, ypos, width, height) {
	$.ajax({
		url: '<?php echo site_url('displaydummy/dummy/ajxAllocAreaVal') ?>',
		type: 'post',
		data: {product: product, date: date, key: key, page: page, box: box, xpos: xpos, ypos: ypos, width: width, height: height, viewing: $("#viewing").val()},
		success: function(response) {
			var $response = $.parseJSON(response);
			if($response['allocValid'] == false) {
				/* Invalid */
				alert('The allocated area is being used by the other ads!.');		
                
                //alert(box);
                
                var boxid = box;        
                $.ajax({
                    url: "<?php echo site_url('displaydummy/dummy/unflow') ?>",
                    type: "post",
                    data: {product: product, date: date, key: key, boxid: boxid, viewing: $("#viewing").val()},
                    success: function(response){    
                        var $response = $.parseJSON(response);        
                        $("#"+ boxid).remove();
                        //$("#content2-content").html($response['listad']);            
                    }
                });                                                                                
                /*$("#"+ box +"").click(function(){        
                    var boxid = box;        
                    var selection = "Unflow";                            
                    if (selection == "Unflow") {
                        
                    }
                });	 */
				
                
                //$("#"+ box).draggable({ revert: true });
				/*$('.draggablebox').closest("#"+box).css({
					'left': dragposition.left,
					'top': dragposition.top,
                    'opacity' : 0.90
				}).draggable({ revert: true });*/
         			
			} else {
				/* Valid */									
				//alert('valid');        
				return true;				
				/*$("#"+page).children('#'+box).css({top: ypos , 
				                                   left: xpos,});					
				$(".draggablebox").draggable({
					opacity: 0.40,            
					cursor: "crosshair",					
					revert: "invalid",
					grid: [ 35,5 ],
					start: function(event,ui){
						dragposition = ui.position;
					},					
				});	 	*/
			}		
		}
	});
	
	return true;
}


			//$("#content1").html($response['pagelayout']);
			/*if ($response['todo'] == "U") {								
				$(".draggablebox #"+$response['box']['layout_boxes_id']).css('width', $response['box']['width']);
				$(".draggablebox #"+$response['box']['layout_boxes_id']).css('height', $response['box']['height']);
				$(".draggablebox #"+$response['box']['layout_boxes_id']).css('left', $response['box']['xaxis']);
				$(".draggablebox #"+$response['box']['layout_boxes_id']).css('top', $response['box']['yaxis']);
			} else if ($response['todo'] == "I") {
				var $box = "<div class='draggablebox' id='" + $response['box']['layout_boxes_id'] + "' style='width:" + $response['box']['width'] + "px;  height: " + $response['box']['height'] + "px; left: " + $response['box']['xaxis'] + "px; top: " + $response['box']['yaxis'] + "px; position:relative; background: #D44B53; opacity: 0.8;'>" + $response['box']['ao_num'] + "</div>";
				$("#"+page).fadeIn('slow').append($box);																									
				$(".draggablebox").draggable({
					opacity: 0.40,            
					cursor: "crosshair", 
					start: function(event, ui) {                                    
						box =  $(this).attr("id"); 			
					}
				});	 
			} else if ($response['todo'] == "N") {
				alert('new page'); return false;
				/*alert($response['box']['layout_boxes_id']);
				var $box = "<div class='draggablebox' id='" + $response['box']['layout_boxes_id'] + "' style='width:" + $response['box']['width'] + "px;  height: " + $response['box']['height'] + "px; left: " + $response['box']['xaxis'] + "px; top: " + $response['box']['yaxis'] + "px; position:relative; background: #D44B53; opacity: 0.8;'>" + $response['box']['ao_num'] + "</div>";				
				$($box).fadeIn('slow').appendTo("#"+page);	
				//$("#"+box).fadeOut('slow').remove();
				//$("#"+page).fadeIn('slow').append($box);
				/*$(".draggablebox").draggable({
					opacity: 0.40,            
					cursor: "crosshair", 
					start: function(event, ui) {                                    
						box =  $(this).attr("id"); 			
					}
				});	 
			}*/


//$(".draggablebox").click(function(){
	//alert('test');
//});
</script>

