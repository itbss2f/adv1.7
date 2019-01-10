<style>
	div {
		font-size: 12px;		
	}

</style>
<div id="newtabs" style="background: #A4A4A4">
	<h4>Section Page</h4>
	<ul>
		<li><a href="#newtab1">List</a></li>		
	</ul>
	<div id="newtab1">
		<p>
			<select style="width:200px;" name="section" id="section">
				<?php 
				foreach ($sect as $sect) {
				?>
				<option value="<?php echo $sect['class_code'] ?>"><?php echo $sect['class_code'] ?></option>
				<?php 
				}
				?>
			</select>
		</p>
		<p align="center"><button class="dummyflds" style="width:200px;" name="setsection" id="setsection">Set</button></p>
	</div>	
</div>
<script>
	$(function() {
		$( "#newtabs" ).tabs();
		$( "#datepicker" ).datepicker();
	});
	
	$("#setsection").click(function(){
		alert('Setting Section. Please refer to the selection field at the top-right corner of your tools!.');
		var $section = $(":input[name='section']").val();
		$("#selection").val($section);
		jQuery.facebox.close(this);
	});
</script>
