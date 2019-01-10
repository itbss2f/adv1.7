<div class="block-fluid table-sorting">    
    <div class="block-fluid table-sorting">
        <table cellpadding="0" cellspacing="0" width="100%" class="table" id="mSortable3">
            <thead>
            <tr>                       
               <th width="2%"></th>
               <th width="6%">Invoice No.</th>
               <th width="6%">Invoice Date</th>
               <th width="6%">AO Number</th>
               <th width="6%">Issue Date</th>                                                              
               <th width="6%">Paginated Date</th> 
               <th width="5%">Agency Code</th> 
               <th width="10%">Agency Name</th> 
               <th width="5%">Client Code</th> 
               <th width="10%">Client Name</th> 
               <th width="5%">PO Number</th> 
               <th width="5%">AE</th> 
               <th width="5%">Branch</th>                                                                
            </tr>
            </thead>
            <tbody>
            
            <?php
            if (empty($invoice)) { ?>
                <tr>
                    <td>No</td>        
                    <td>Record</td>                
                    <td> </td>                
                    <td> </td>                
                    <td> </td>                
                    <td><span> </span></td>                
                    <td></td>                
                    <td><span> </span></td>                
                    <td> </td>                
                    <td> </td>                
                    <td> </td>                
                    <td> </td>                
                    <td> </td>                              
                </tr>
            <?php    
            } else { 
                foreach ($invoice as $invoice) { ?>
                <tr class='prev'>   
                    <td><span class="icon-th-list invoice_edit" id="<?php echo $invoice['id'] ?>"></span></td>                         
                    <td><?php if ($invoice['ao_sinum'] == 0) { echo ""; } else { echo $invoice['ao_sinum']; } ?></td>                
                    <td><?php echo $invoice['ao_sidate']?></td>                
                    <td><?php echo $invoice['ao_num']?></td>                
                    <td><?php echo $invoice['ao_issuefrom']?></td>                
                    <td><?php echo $invoice['ao_paginated_date']?></td>                
                    <td><?php echo $invoice['ao_amf']?></td>                
                    <td><span><?php echo $invoice['agencyname']?></span></td>                
                    <td><?php echo $invoice['ao_cmf']?></td>                
                    <td><span><?php echo $invoice['ao_payee']?></span></td>                
                    <td><?php echo $invoice['ao_ref']?></td>                
                    <td><?php echo $invoice['username']?></td>                
                    <td><?php echo $invoice['branch_code']?></td>                
                </tr>
                <?php
                }     
            }
            ?>

            </tbody>
        </table>
        <div class="clear"></div>
    </div>     
    <div class="clear"></div>
</div>   




<script>
$(document).ready(function()
{
	$(".invoice_edit").click(function() {
		var id = $(this).attr('id');
		$.ajax({
			url: "<?php echo site_url('invoicing/manualview') ?>",
			type: 'post',
			data: {id: id},
			success: function(response) {
			var $response = $.parseJSON(response);

			$('#manualview').html($response['manualview']).dialog('open');
			}
		});
	});
    
    $('#mSortable3').dataTable( {
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,
    } );
	
}); 
</script>
