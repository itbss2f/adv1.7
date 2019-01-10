<style>
	div {
		font-size: 12px;		
	}

</style>
<div id="newtabs" style="background: #A4A4A4">
	<h4>Add Page Dummy</h4>
	<ul>
		<li><a href="#newtab1">Pages</a></li>
		<!-- <li><a href="#newtab2">Template</a></li> -->
	</ul>
	<div id="newtab1">
		<p><select name="bookname" id="bookname" style="width: 200px;">
		   <?php 
		   foreach ($page as $page) {
		   ?>
			<option value="<?php echo $page['book_name'] ?>"><?php echo $page['book_name'] ?></option>
		   <?php
		   }
		   ?>
		   </select>
		</p>
		<p>Number of Page: <input type="text" name="numberofpage" id="numberofpage" style="width:50px;" value="2"></p>
		<p align="center"><button class="dummyflds" style="width:100px;" name="addpage" id="addpage">Add</button></p>
	</div>
</div>
 
<script>
	$(function() {
		$( "#newtabs" ).tabs();
	});
	
	$("#addpage").click(function(){
		var $numberofpage =  $(":input[name='numberofpage']").val();
		
		if (myprod == "" || mydate == "") {		
			alert('No product and issue date');
		} else {			
			if ($numberofpage == "" || $numberofpage == "0") {
				alert('Enter Number of Page');
				return false;
			} else {
				$.ajax({
					url : '<?php echo site_url('displaydummy/dummy/ajxAddPage') ?>',
					type: 'post',
					data: {bookname: $(":input[name='bookname']").val(), numberofpage: $(":input[name='numberofpage']").val(),  key: mykey, product: myprod, date: mydate, viewing: $("#viewing").val()},
					success: function(response) {
						var $response = $.parseJSON(response);
						if ($response['valid'] == "true") { 						
							$("#content1").html($response['pagelayout']);	
							jQuery.facebox.close(this);							
						}
					}
				});
			}
		}
	});
		
</script>
