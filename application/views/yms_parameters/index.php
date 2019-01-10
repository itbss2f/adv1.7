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
					<h1>Yield Management System - Parameters</h1>
					<ul class="buttons">
						<li>
							<a href="#" class="isw-settings"></a>
							<ul class="dd-list">
                                <?php if ($canADD) : ?>
								<li><a href="#" id="newdata"><span class="isw-plus"></span> New YMS-Parameters</a></li>                    					                  
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
					<th width="25%">Name</th>
					<th width="10%">VAT inclusive</th>
					<th width="10%">Net Return Rate</th>
					<th width="10%">Insert Rate</th>
					<th width="10%">Ave. Daily Circ.</th>
					<th width="10%">Fixed Exp.</th>
					<th width="25%">Action</th>                                                                
				</tr>
			</thead>
			<tbody>		
				<?php   foreach ($parameter as $row) : ?>	
				<tr>
					<td><?php echo $row['company_code'] ?></td>
					<td><?php echo $row['company_name'] ?></td>
					<td><?php echo number_format($row['vat_inclusive'], 2, '.', ',') ?></td>
					<td><?php echo number_format($row['net_returns_rate'], 2, '.', ',') ?></td>
					<td><?php echo number_format($row['insert_rate'], 2, '.', ',') ?></td>
					<td><?php echo number_format($row['ave_daily_circ'], 2, '.', ',') ?></td>
					<td><?php echo number_format($row['fixed_expenses'], 2, '.', ',') ?></td>
					<td>
                        <?php if ($canEDIT) : ?>
                        <span class="icon-pencil edit" id="<?php echo $row['id'] ?>" title="Edit"></span>
                        <?php endif; ?>
                        <?php if ($canDELETE) : ?>
					    <span class="icon-trashremove" id="<?php echo $row['id'] ?>" title="Remove"></span>
                        <?php endif; ?>
					</td>   
				</tr>
				<?php endforeach;   ?>
			</tbody>
			</table>
			<div class="clear"></div>
			</div>
		</div>

	</div>            

	<div class="dr"><span></span></div>
</div>  
<div id="modal_newdata" title="New Data Parameter"></div>
<div id="modal_editdata" title="Edit Data Parameter"></div>
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
		  url: "<?php echo site_url('yms_parameter/editdata') ?>",
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
		var ans = confirm("Are you sure you want to remove this YMS parameter?");	

		if (ans) {
			window.location = "<?php echo site_url('yms_parameter/removeData') ?>/"+$id;
		}
	});

	$('#newdata').click(function(){        
	   $.ajax({
		  url: "<?php echo site_url('yms_parameter/newdata') ?>",
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
