<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>
<div class="workplace">
	<?php 
	$msg = $this->session->flashdata('msg');
	if ($msg != '') :
	?>
	<script>
	$.gritter.add({
		title: 'Success!',
		text: "<?php echo $msg ?>"

	});
	</script>
	<?php endif; ?>
	<div class="row-fluid">

		<div class="span12">
			<div class="head">
				<div class="isw-grid"></div>
					<h1>Yield Management System - Product</h1>
					<ul class="buttons">
						<li>
							<a href="#" class="isw-settings"></a>
							<ul class="dd-list">
                                <?php if ($canADD) : ?>
								<li><a href="#" id="newdata"><span class="isw-plus"></span> New YMS - Product</a></li>                    					                  
                                <?php endif; ?>
							</ul>
						</li>	
					</ul>           			    
				<div class="clear"></div>
			</div>
			<div class="block-fluid table-sorting">
			<table cellpadding="0" cellspacing="0" width="100%" class="table">
			<thead>
				<tr>
					<th width="10%">Code</th>
					<th width="25%">Product</th>
					<th width="25%">Total CCM</th>
					<th width="25%">Edition</th>
					<th width="25%">Action</th>                                                                
				</tr>
			</thead>
			<tbody>		
				<?php foreach ($ymsproduct as $row) : ?>	
				<tr>
					<td><?php echo $row['code'] ?></td>
					<td><?php echo $row['name'] ?></td>
					<td><?php echo $row['total_ccm'] ?></td>
					<td><?php echo $row['editioncode'].' - '.$row['editionname'] ?></td>
					<td>
                        <?php if ($canEDIT) : ?>
                        <span class="icon-pencil edit" id="<?php echo $row['id'] ?>" title="Edit"></span>
                        <?php endif; ?>
                        <?php if ($canDELETE) : ?>
					    <span class="icon-trash remove" id="<?php echo $row['id'] ?>" title="Remove"></span>
                        <?php endif; ?>
					</td>   
				</tr>
				<?php endforeach; ?>
			</tbody>
			</table>
			<div class="clear"></div>
			</div>
		</div>

	</div>            

	<div class="dr"><span></span></div>
</div>  
<div id="modal_newdata" title="New Data Rate"></div>
<div id="modal_editdata" title="Edit Data Rate"></div>
<script>
$(function() {
	$('#modal_newdata, #modal_editdata').dialog({
	   autoOpen: false, 
	   closeOnEscape: false,
	   draggable: true,
	   width: 430,    
	   height: 'auto',
	   modal: true,
	   resizable: false
	});       

	$('.edit').click(function() {
		var $id = $(this).attr('id');
		$.ajax({
		  url: "<?php echo site_url('yms_product/editdata') ?>",
		  type: "post",
		  data: {id: $id},
		  success:function(response) {
			 $response = $.parseJSON(response);
		      $("#modal_editdata").html($response['editdata_view']).dialog('open');    
		  }    
	   });      
	});

	$('.remove').click(function() {
		var $id = $(this).attr('id');
		var ans = confirm("Are you sure you want to remove this YMS product?");	

		if (ans) {
			window.location = "<?php echo site_url('yms_product/removeData') ?>/"+$id;
		}
	});

	$('#newdata').click(function(){        
	   $.ajax({
		  url: "<?php echo site_url('yms_product/newdata') ?>",
		  type: "post",
		  data: {},
		  success:function(response) {
			 $response = $.parseJSON(response);
		      $("#modal_newdata").html($response['newdata_view']).dialog('open');    
		  }    
	   });        
	});    
});
</script>
