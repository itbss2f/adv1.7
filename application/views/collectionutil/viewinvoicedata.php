<div class="row-fluid">
                                 
    <div class="span12">

        <div class="block-fluid">
            
            <div class="block-fluid">

            </div>
                    <table cellpadding="0" cellspacing="0" width="100%" class="table">
                        <thead>
                            <tr>                                    
                                <th width="15%">Invoice #</th>
                                <th width="15%">Invoice Date</th>
                                <th width="15%">Run Date</th>
                                <th width="15%">Receive Billing Date</th>                                    
                                <th width="15%">Receive Client Date</th>                                    
                                <th width="40%">Receive Remarks</th>                                                                     
                                <th width="5%">Action</th>                                                                     
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $data) : ?>  
                            <tr>                                    
                                <td><?php echo $data['ao_sinum'] ?></td>
                                <td><?php echo $data['invdate'] ?></td>                            
                                <td><?php echo $data['rundate'] ?></td>                            
                                <td><?php echo $data['rcvbillingdate'] ?></td>                            
                                <td><?php echo $data['recvdate'] ?></td>                            
                                <td><?php echo $data['ao_receive_part'] ?></td>                            
                                <td><span class="icon-pencil edit" id="<?php echo $data['id'] ?>" title="Edit"></span>   </td>                            
                            </tr>
                            <?php endforeach; ?>      
                        </tbody>
                    </table>
                </div>
            </div>                                 

        </div>
    </div>                                
    
</div>  

<script>
  

$('.edit').click(function() {
    var $id = $(this).attr('id');
    $.ajax({
      url: "<?php echo site_url('collectionutility/editinvdata') ?>",
      type: "post",
      data: {id: $id},
      success:function(response) {
         $response = $.parseJSON(response);
         
         $("#modal_editdata").html($response['editdata_view']).dialog('open');    
      }    
   });      
});

</script>