<style>
	div {
		font-size: 12px;		
	}

</style>
<div id="newtabs" style="background: #A4A4A4">
	<h4>Create New Dummy</h4>
	<ul>
		<li><a href="#newtab1">Issue</a></li>
		<!-- <li><a href="#newtab2">Template</a></li> -->
	</ul>
	<div id="newtab1">
		<p>Product: <select name="product" id="product">
						<?php 
						foreach ($prod as $prod) {
						?>
						<option value="<?php echo $prod['id'] ?>"><?php echo $prod['prod_code'] ?></option>
						<?php
						}
						?>
					</select>
		</p>
		<p>Date: <input type="text" name="dateissue" id="dateissue"></p>
		<p align="center"><button class="dummyflds" style="width:200px;" name="load" id="load">Load</button></p>
	</div>
	<!-- <div id="newtab2">
		<p>Master: <select>
						<option>--- Select Master ---</option>
						<option></option>
						<option></option>
						<option></option>
				   </select>		
		</p>
		<p align="center"><button class="dummyflds" style="width:200px;">Load</button></p>
	</div>	-->
</div>
<script>
	$(function() {
		$( "#newtabs" ).tabs();
		$( "#dateissue" ).datepicker({dateFormat: 'M d, yy'});
	});
	
	$("#load").click(function(){	
		if ($(":input[name='dateissue']").val() === "") {
			alert('Date must not be empty or NULL!.');
			return false;
		} else {
			$.ajax({
				url: '<?php echo site_url('displaydummy/dummy/ajxRtAds')?>',
				type: 'post',
				data: {product: $(":input[name='product']").val(),
					   dateissue: $(":input[name='dateissue']").val()},
				success: function(response) {
					var $response = $.parseJSON(response);
					$("#dateofissue").html($(":input[name='dateissue']").val());
					$("#content2-content").html($response['listad']);
					jQuery.facebox.close(this);
				}
			});
		}
	});	
</script>
