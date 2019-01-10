<style>
	div {
		font-size: 12px;		
	}

</style>
<div id="newtabs" style="background: #A4A4A4">
	<h4>Setting Info</h4>
	<ul>
		<li><a href="#newtab1">Folio</a></li>
		<li><a href="#newtab2">Blockout</a></li>
		<li><a href="#newtab3">Center Spread</a></li>
		<!-- <li><a href="#newtab2">Template</a></li> -->
	</ul>
	<div id="newtab1">
		<p> <label>Folio Numbering:</label>
			<input type="text" name="folionumber" id="folionumber" size="5">
		</p>
		<p> <input type="checkbox" name="checkfolio" id="checkfolio" value="1" checked="checked">
			<label>Renumbering following pages</label>	
		</p>
		<p>
			<input type="button" name="okfolio" id="okfolio" value="OK">
			<input type="button" name="cancelfolio" id="cancelfolio" value="CANCEL">
		</p>
	</div>
	<div id="newtab2">
		<p>
			<label>Width</label>
			<input type="text" name="reservewidth" id="reservewidth" size="8">cols
			<label>Height</label>
			<input type="text" name="reserveheight" id="reserveheight" size="8">cm
		</p>
		<p><label>Description</label></p>
		<p>
			<textarea style="width: 273px; height: 67px;" name="reservedescription" id="reservedescription"></textarea>
		</p>
		<p>
        <input type="button" name="okblockout" id="okblockout" value="OK">
		<input type="button" name="cancelblockout" id="cancelblockout" value="CANCEL">
		</p>
	</div>
	<div id="newtab3">				
		<h4><?php if (empty($pageData['is_merge'])) { echo "Merge";} else { echo "Unmerge"; } ?> Page to be center spread page <?php echo $pageData['folio_number']; ?> and <?php echo $pageData['folio_number'] + 1; ?></h4>
		<?php 
		if (empty($pageData['is_merge'])) {
		?>
		<input type="button" name="mergepage" id="mergepage" value="Merge Page">
		<?php
		} else { ?>
		<input type="button" name="unmergepage" id="unmergepage" value="Unmerge Page">
		<?php
		}
		?>
	</div>	
</div>

<script>

	$("#okfolio").click(function(){
		if ($("#folionumber").val() != "") {
			$.ajax({
				url: '<?php echo site_url('displaydummy/dummy/ajxSetFolio') ?>',
				type: 'post',
				data: {key: '<?php echo $key ?>', product: '<?php echo $product ?>', date: '<?php echo $date ?>', 
				       pageID: '<?php echo $pageID ?>', checkfolio: $(":input[name='checkfolio']:checked").val(), 
				       folionumber: $(":input[name='folionumber']").val(), viewing: '<?php echo $viewing ?>'},
				success: function(response) {
					var $response = $.parseJSON(response);
					$("#content1").html($response['pagelayout']);
					jQuery.facebox.close(this);
				}
			});
		} else {
			alert('Please enter positive number!');
			return false;
		}
	});

	$("#cancelfolio, #cancelblockout").click(function(){
		jQuery.facebox.close(this);
	});
		
	
	$("#okblockout").click(function(){		
			var reservewidth = $(":input[name='reservewidth']").val();
			var reserveheight = $(":input[name='reserveheight']").val();
			var reservedescription = $(":input[name='reservedescription']").val();
			if (reservewidth == "" || reserveheight == "" || reservedescription == "") {
				alert("Filled up all fields");
			} else {
				$.ajax({
					url: '<?php echo site_url('displaydummy/dummy/addblockout') ?>',
					type: 'post',
					data: {key: '<?php echo $key ?>', product: '<?php echo $product ?>', date: '<?php echo $date ?>', 
				       	   pageID: '<?php echo $pageID ?>', viewing: $("#viewing").val(),
						   reservewidth: reservewidth, reserveheight: reserveheight, reservedescription: reservedescription
					},
					success: function(response){
						var $response = $.parseJSON(response);
						$("#content1").html($response['pagelayout']);						
						jQuery.facebox.close(this);
					}
				});
			}
	});
	
	$("#mergepage").click(function(){
        var xx = '<?php echo $pageID; ?>';
        var zz = $('#<?php echo $pageID; ?>').data('page');
        var zzx = $('#<?php echo $pageID; ?>').parent().next('div').find('.pagediv').attr("id");
        var zzxx = $('#'+zzx).data('page'); 
        

        if (zz == 0 || zzxx == 0) {
            alert('Page must be save first!. For validation of page merging purpose');
            return false;  
        }

		$.ajax({
			url: '<?php echo site_url('displaydummy/dummy/ajxmergePage') ?>',
			type: 'post',
			data: {key: '<?php echo $key ?>', product: '<?php echo $product ?>', date: '<?php echo $date ?>', 
				   pageID: '<?php echo $pageID ?>', viewing: '<?php echo $viewing ?>',
				   mergedID: zzx },
			success: function(response) {	
				var $response = $.parseJSON(response);
				if ($response['error'] == false) {
					alert('Cannot merged this page. Ads should be unflow!');
					return false;
				} else {
					$("#content1").html($response['pagelayout']);						
				}
				
				if ($response['error2'] == false) {
					alert('Cannot merged this page.Page should have same settings!');
					return false;
				}
				jQuery.facebox.close(this);
			}
		});
	});
	
	$("#unmergepage").click(function(){
		$.ajax({
			url: '<?php echo site_url('displaydummy/dummy/ajxUnmergePage') ?>',
			type: 'post',
			data: {key: '<?php echo $key ?>', product: '<?php echo $product ?>', date: '<?php echo $date ?>', 
				   pageID: '<?php echo $pageID ?>', viewing: '<?php echo $viewing ?>'},
			success: function(response) {	
				var $response = $.parseJSON(response);
				if ($response['error'] == false) {
					alert('Cannot unmerged this page. Ads should be unflow!');
					return false;
				} else {
					$("#content1").html($response['pagelayout']);						
				}
				jQuery.facebox.close(this);
			}
		});
	});
	
</script>