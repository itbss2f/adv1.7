<script>
	<!-- important variable -->
	var page  = "";
	var key = <?php echo "'".$key."'" ?>;
	var product = <?php echo "'".$product."'" ?>;
	var date = <?php echo "'".$date."'" ?>;
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
$totalpages = count($pages);
		switch ($viewing)
		{
			case 1:
				$grid = "gridlarge.png";
				$gridsnap = "grid: [ 35,5 ],";
				$font = "font-size: 15px;";
			break;
			case 2:
				$grid = "gridmedium.png";
				$gridsnap = "grid: [ 29,4 ],";
				$font = "font-size: 14px;";
			break;
			case 3:				
				$grid = "gridsmall.png";
				$gridsnap = "grid: [ 23,3 ],";
				$font = "font-size: 13px;";
			break;
			case 4:
				$grid = "gridxsmall.png";
				$gridsnap = "grid: [ 17,2 ],";
				$font = "font-size: 10px;";
			break;
			case 5:
				$grid = "gridxxsmall.png";
				$gridsnap = "grid: [ 8,1 ],";
				$font = "font-size: 8px;";
			break;
			case 6:
				$grid = "gridxxxsmall.png";
				$gridsnap = "grid: [ 4,.5 ],";
				$font = "font-size: 5px;";
			break;
		}
for ($a = 0 ; $a < $totalpages; $a++) {
	if (empty($pages[$a]['color_html'])) { $pages[$a]['color_html'] = "EDEDED";}
	$color = "border:2px solid #".$pages[$a]['color_html']."";
	$colorcode = $pages[$a]['color_code'];			
	if ($pages[$a]['is_merge'] != "x") {		
?>
<div class="pagecon" id="pageconpagex<?php echo $pages[$a]['layout_id'] ?>" align="center" style="width: <?php echo $pages[$a]['columnpixel'] ?>px; height: <?php echo $pages[$a]['lenpixel'] + 20 ?>px;">
	<div class="pagediv" id="pagex<?php echo $pages[$a]['layout_id'] ?>" style="position: relative; width: <?php echo $pages[$a]['columnpixel'] ?>px; height: <?php echo $pages[$a]['lenpixel']?>px; <?php echo $color ?>; background: url(<?php echo base_url(); ?>images/dummy/<?php echo $grid ?>);">
		<!-- Boxes inside -->
		<script>			
			$.ajax({
				url: '<?php echo site_url('dummy/ajxRetMyOwnBox') ?>',
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
						if ($item['component_type'] == "blockout") {
							$divcolor = "background: #918F8F";							
						} else {
							if ($item['color_html'] == "") {
								$divcolor = "background: #E6E6E6";
							} else {
								$divcolor = "background: #"+$item['color_html'];
							}
						}
																									
						var $box = "<div class='draggablebox' id='" + $item['layout_boxes_id'] + "' style='position: absolute; z-index: 20; width:" + $item['columnpixel'] + "px;  height: " + $item['lenpixel'] + "px; left: " + $xpos + "px; top: " + $ypos + "px;" + $divcolor +";<?php echo $font ?>'>"+$item['ao_num']+"</div>";
						$("#pagex<?php echo $pages[$a]['layout_id'] ?>").fadeIn('slow').append($box);
						
						$("#"+ $item['layout_boxes_id'] +"").click(function(){		
							var boxid = $item['layout_boxes_id'];					
							var selection = $(":input[name='selection']").val();							
							if (selection == "Unflow") {
								$.ajax({
									url: "<?php echo site_url('dummy/unflow') ?>",
									type: "post",
									data: {product: product, date: date, key: key, boxid: boxid},
									success: function(response){	
										var $response = $.parseJSON(response);		
										$("#"+ boxid).remove();
										$("#content2-content").html($response['listad']);			
									}
								});																				
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
	<div class="pagedivname" align="center" style="width: <?php echo $pages[$a]['columnpixel'] + 2 ?>px; height: 20px;">
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
					url: '<?php echo site_url('dummy/ajxPgBxVal') ?>',
					type: 'post',
					data: {product: product, date: date, key: key, page: page, box: box},
					success: function (response) {
						var $response = $.parseJSON(response);
						if($response['isColorValid'] == false) {
							/* Invalid */
							alert('The box color not valid to be dummy in this page!.');	
							
							alert(dragposition.left);
							alert(dragposition.top);
																			
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
		
		if (selection == "" || selection == "Unflow") {			
			return false;
		} else {
			$.ajax({
				url: '<?php echo site_url('dummy/ajxtSetValue') ?>',
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
						$("#name"+pageID).html($response['section']);
						$("#mergename"+pageID).html($response['section']);
					}
					if ($response['validdelete'] == true) {
						alert("Cannot delete page there are box ads you must unflow all the box in this page!.");
					}		
					if ($response['validdelete'] == false) {
						//$(this).remove();			
						$(".pagecon").remove("#pagecon"+pageID);
					}								
				}
			});		
		}
		
	});
	
	$(".pagediv").dblclick(function() {
		var pageID = $(this).attr("id");
		$.ajax({
			url: '<?php echo site_url('dummy/openFolio') ?>',
			type: 'post',
			data: {key: key, product: product, date: date, pageID: pageID, viewing: $("#viewing").val()},
			success: function(response) {
				var $response = $.parseJSON(response);
				jQuery.facebox($response['folio']);
			}
		});
	});
						
</script>
