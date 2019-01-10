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
					<h1>Yield Management System - Product Budget</h1>
					<ul class="buttons">
						<li>
							<a href="#" class="isw-settings"></a>
							<ul class="dd-list">
                                <?php if ($canADD) : ?>
								<li><a href="#" id="newdata"><span class="isw-plus"></span> New Product Budget</a></li>                    					                  
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
					<th width="5%">Year</th>
					<th width="15%">Product</th>
					<th width="20%">Account</th>
					<th width="25%">Remarks</th>                                                                
					<th width="10%">Sales Budget</th>
					<th width="10%">C M Budget</th>
					<th width="15%">Action</th>                                                                
				</tr>
			</thead>
			<tbody>		
				<?php foreach ($budget as $row) : ?>	
				<tr>
					<td><?php echo $row['budget_year'] ?></td>
					<td><?php echo $row['productname'] ?></td>
					<td><?php echo $row['caf_code'].' - '.$row['adtype_name'] ?></td>
					<td><?php echo $row['remarks'] ?></td>
					<td style="text-align:right"><?php echo number_format($row['sales_total'], 2, '.', ',') ?></td>
					<td style="text-align:right"><?php echo number_format($row['cm_total'], 2, '.', ',') ?></td>
					<td>
                        <?php if ($canEDIT) : ?>
                        <span class="icon-pencil edit" id="<?php echo $row['id'] ?>" title="Edit"></span>
                        <?php endif; ?>
                        <?php if ($canDELETE) : ?>
					    <span class="icon-trash remove" id="<?php echo $row['id'] ?>" title="Remove"></span>
                        <?php endif; ?>
					</td>   
				</tr>
				<?php endforeach;  ?>
			</tbody>
			</table>
			<div class="clear"></div>
			</div>
		</div>

	</div>            

	<div class="dr"><span></span></div>
</div>  
<div id="modal_newdata" title="New Data Product Budget"></div>
<div id="modal_editdata" title="Edit Data Product Budget"></div>
<div id="modal_detaildata" title="Edit Data Product Budget Detailed"></div>
<script>
$(function() {
	$('#modal_newdata, #modal_editdata, #modal_detaildata').dialog({
	   autoOpen: false, 
	   closeOnEscape: false,
	   draggable: true,
	   width: 680,    
	   height: 'auto',
	   modal: true,
	   resizable: false
	});       

	$('.edit').click(function() {
		var $id = $(this).attr('id');
		$.ajax({
		  url: "<?php echo site_url('yms_product_budget/editdata') ?>",
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
		var ans = confirm("Are you sure you want to remove this YMS product budget?");	

		if (ans) {
			window.location = "<?php echo site_url('yms_product_budget/removeData') ?>/"+$id;
		}
	});

	$('#newdata').click(function(){        
	   $.ajax({
		  url: "<?php echo site_url('yms_product_budget/newdata') ?>",
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
