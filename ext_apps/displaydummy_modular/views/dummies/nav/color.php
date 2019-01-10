<style>
	div {
		font-size: 12px;		
	}

</style>
<div id="newtabs" style="background: #A4A4A4">
	<h4>Color Page</h4>
	<ul>
		<li><a href="#newtab1">List</a></li>		
	</ul>
	<div id="newtab1">
		<p>
			<select style="width:200px;" name="color" id="color">
                <option value="NoCol">B/W</option> 
				<?php 
				foreach ($color as $color) {
				?>
				<option value="<?php echo $color['color_code'] ?>"><?php echo $color['color_code'] ?></option>
				<?php 
				}
				?>
				
			</select>
		</p>
		<p align="center"><button class="dummyflds" style="width:200px;" name="setcolor" id="setcolor">Set Color</button></p>
	</div>	
</div>
<script>
	$(function() {
		$( "#newtabs" ).tabs();
		$( "#datepicker" ).datepicker();
	});
	
	$("#setcolor").click(function(){
		alert('Setting Color. Please refer to the selection field at the top-right corner of your tools!.');
		var $color = $(":input[name='color']").val();		
		$("#selection").val($color);				
		jQuery.facebox.close(this);
	});
</script>
