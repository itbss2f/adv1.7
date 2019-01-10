<div class="row-form-booking">			   		
   <div class="span3"><input type="text" placeholder="your production remarks..." id="prod_rem_text" name="prod_rem_text"/>
                      <input type="hidden" id="ext_prod_rem_text" name="ext_prod_rem_text" value="<?php echo $prod_remarks; ?>"/>
   </div>
   <div class="span1" style="width:20px;"><button class="btn" type="button" id="prodremark_btn">Ok</button></div>		
   <div class="clear"></div>
</div>	
<div class="span4">
	<div class="block messages scrollBox">
	   <table cellpadding="0" cellspacing="0" width="100%" class="sOrders">
		  <thead>
			<tr>
				<th width="60">Date</th><th>Production Remarks</th><th width="60">User</th><th width="40">Action</th>
			</tr>
		  </thead>
		  <tbody class="prod_remarks_view">		
		  <?php echo $prodremarks;  ?>	
		  </tbody>	  
	   </table>
	</div>      
</div>
<script>
$(function() {
	$("#prod_rem_text").focus();	
	$("#prodremark_btn").click(function(){
		var $prod_rem = $("#prod_rem_text").val();
		var $ext_prod_rem = $("#ext_prod_rem_text").val();
		if ($prod_rem == "") {
			alert("Field must not be empty!"); return false;
		}
		$.ajax({
			url: "<?php echo site_url('booking/set_productionremarks') ?>",
			type: "post",
			data: {prod_rem: $prod_rem, ext_prod_rem: $ext_prod_rem},
			success: function(response) {
				$response = $.parseJSON(response);
				
				$("#prod_rem_text").focus().val("");
				$("#production").val($response['prod_remarks']);
				$("#ext_prod_rem_text").val($response['prod_remarks']);
				$(".prod_remarks_view").html($response['prodremarks']);
			}
		});
	});
});	
</script>
