<?php 
if (!empty($ads)) {
$totalads = count($ads);
?>
	<table>
		<thead>
			<tr>
				<th>Boxes</th>
				<th>AONum</th>				
				<th>Code</th>		
				<th>Advertiser</th>				
				<th>Class</th>		
				<th>Width</th>				
				<th>Length</th>											
				<th>Color</th>		
				<th>Position</th>				
				<th>Page-Min</th>				
				<th>Page-Max</th>								
				<th>Status</th>								
			</tr>
		</thead>
		<tbody>
			<?php 
			for ($a = 0; $a < $totalads; $a++) {
				if ($ads[$a]['color_html'] == "") {
					$divcolor = "background: #D2CCCC";					
				} else {					
					$divcolor = "background: #".$ads[$a]['color_html'];					
				}
			?>
			<tr <?php if (@$ads[$a]['status'] == "F") { echo "style='background: gray'"; } if (@$ads[$a]['is_flow'] == "2") { echo "style='background: #F78181'"; } ?>>
				<td>
					<?php 
					if (@$ads[$a]['status'] != "F" && @$ads[$a]['is_flow'] != "2") {					
					?>
					<div class="draggablebox" style="<?php echo $divcolor ?>" id="<?php echo $ads[$a]['id']?>"></div>					
					<?php
					}					
					?>
				</td>
				<td><?php echo @$ads[$a]['ao_num'] ?></td>
				<td><?php echo @$ads[$a]['ao_cmf'] ?></td>
				<td><?php echo @$ads[$a]['ao_payee'] ?></td>
				<td><?php echo @$ads[$a]['class_code'] ?></td>
				<td><?php echo @$ads[$a]['ao_width'] ?></td>
				<td><?php echo @$ads[$a]['ao_length'] ?></td>				
				<td><?php echo @$ads[$a]['color_code'] ?></td>
				<td><?php echo @$ads[$a]['ao_position'] ?></td>
				<td><?php echo @$ads[$a]['ao_pagemin'] ?></td>
				<td><?php echo @$ads[$a]['ao_pagemax'] ?></td>				
				<td><?php echo @$ads[$a]['status'] ?></td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
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
	var box = "";
	$(".draggablebox").draggable({
		opacity: 0.40,            
        cursor: "crosshair",
		start: function(event,ui){
			dragposition = ui.position;
		},    	
		revert: "invalid",
		
	});		
		
</script>