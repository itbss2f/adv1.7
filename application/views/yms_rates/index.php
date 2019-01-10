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
				<h1>Yield Management System - Rates</h1>
				<ul class="buttons">
				<li>
				    <a href="#" class="isw-settings"></a>
				    <ul class="dd-list">
                       <?php if ($canADD) : ?>
					   <li><a href="#" id="newdata"><span class="isw-plus"></span> New Rate</a></li>                    					                  
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
					<th width="20%">Edition</th>
					<th width="25%">Printing Press</th>
					<th width="25%">Period Covered</th>
					<th width="15%">Circulation Copies</th>
					<th width="25%">Action</th>                                                                
				</tr>
			</thead>
			<tbody>		
				<?php foreach ($rates as $row) : ?>	
				<tr>
					<td><?php echo $row['editioncode'].' - '.$row['editionname'] ?></td>
					<td><?php echo $row['printingcode'].' - '.$row['printingname'] ?></td>
					<td><?php echo "From: ".$row['period_covered_from'].' To: '.$row['period_covered_to'] ?></td>
					<td style="text-align:right"><?php echo number_format($row['circulation_copies'], 2, '.', ','); ?></td>
					<td>
                        <?php if ($canEDIT) : ?>
                        <span class="icon-pencil edit" id="<?php echo $row['id'] ?>" title="Edit"></span>
                        <?php endif; ?>
                        <?php if ($canDELETE) : ?>
					    <span class="icon-trash remove" id="<?php echo $row['id'] ?>" title="Remove"></span>
                        <?php endif; ?>
                        <?php if ($canEDIT) : ?>
                        <span class="icon-book duplicate" id="<?php echo $row['id'] ?>" title="Duplicate"></span>
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
<div id="modal_duplicatedata" title="Duplicate Data Rate"></div>

<script>
$(function() {
	$('#modal_newdata, #modal_editdata, #modal_duplicatedata').dialog({
	   autoOpen: false, 
	   closeOnEscape: false,
	   draggable: true,
	   width: 430,    
	   height: 'auto',
	   modal: true,
	   resizable: false
	});       

	$('#newdata').click(function(){        
	   $.ajax({
		  url: "<?php echo site_url('yms_rates/newdata') ?>",
		  type: "post",
		  data: {},
		  success:function(response) {
			 $response = $.parseJSON(response);
		      $("#modal_newdata").html($response['newdata_view']).dialog('open');    
		  }    
	   });        
	});    
    
    $('.duplicate').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('yms_rates/duplicate') ?>",
          type: "post",
          data: {id: $id},
          success:function(response) {
             $response = $.parseJSON(response);
             $("#modal_duplicatedata").html($response['duplicate_view']).dialog('open');    
          }    
       });      
    });

	$('.edit').click(function() {
		var $id = $(this).attr('id');
		$.ajax({
		  url: "<?php echo site_url('yms_rates/editdata') ?>",
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
		var ans = confirm("Are you sure you want to remove this YMS rates?");	

		if (ans) {
			window.location = "<?php echo site_url('yms_rates/removeData') ?>/"+$id;
		}
	});
});
</script>
