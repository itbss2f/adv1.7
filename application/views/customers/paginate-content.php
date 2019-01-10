<div id="paginate-content">
    <div class="pages"><?php echo $pages ?></div>    
   
    <div class="block-fluid table-sorting">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
            <thead>
            <tr>
                <th width="05%">ID</th>
                <th width="10%">Code</th>
                <th width="35%">Name</th>
                <th width="10%">Credit Limit</th>
                <th width="10%">Credit Status</th>                                                                
                <th width="15%">Date Created</th>    
                <th width="15%">Date Edited</th>    
                <th width="15%">Account Executive</th>    
                <th width="15%">Action</th>       
            </tr>
            </thead>
            <tbody>
                <?php foreach ($customer_list as $customer) : ?>
            <tr>
                <td><?php echo $customer['id'] ?></td>    
                <td><?php echo strtoupper($customer['cmf_code']) ?></td>
                <td><?php echo strtoupper($customer['cmf_name']) ?></td>
                <td style="text-align:right"><?php echo number_format($customer['cmf_crlimit'], 2, '.',',') ?></td>
                <td style="text-align:center"><?php echo strtoupper($customer['cmf_crstatusname']) ?></td>
                <td><?php echo strtoupper($customer['firstname'].' '.$customer['lastname']) ?></td>  
                <td><?php echo $customer['user_d'] ?></td>    
                <td><?php echo $customer['edited_d'] ?></td>       
                <td>
                    <?php if ($canEDIT) : ?>   <span class="icon-pencil edit" id="<?php echo $customer['id'] ?>" title="Edit"></span> <?php endif; ?>
                    <?php if ($canCUSTOMERCCCONTACT) : ?>   <span class="icon-book cc_contact" id="<?php echo $customer['id'] ?>" title="Collection Contact Details"></span>  <?php endif; ?>  
                    <?php if ($canVIEW) : ?>   <span class="icon-search view" id="<?php echo $customer['id'] ?>" title="View"></span> <?php endif; ?>      
                    <?php if ($canDELETE) : ?>   <span class="icon-trash remove" id="<?php echo $customer['id'] ?>" title="Remove"></span>  <?php endif; ?>
                 </td>       
            </tr>
            <?php endforeach; ?>    
            </tbody>
        </table>
        <div class="clear"></div>
    
    </div>
    <div class="pages"><?php echo $pages ?></div>         
</div>      

<script>
$(function() {
    
    $page = $(".pages > ul > li").click(function(){
            
        $.ajax({
             type:'post',
             url:'<?php echo site_url('customer/pageselect'); ?>',
             data:{page:$(this).val()},
             success: function(response)
             {
                    $("#paginate-content").html($.parseJSON(response)); 
             }
        });    
        
    });
    
    $('#modal_newdata, #modal_editdata, #modal_searchdata, #modal_viewcust').dialog({
        autoOpen: false, 
        closeOnEscape: false,
        draggable: true,
        width: 680,    
        height:'auto',
        modal: true,
        resizable: false
    });     
    
    $('.view').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('customer/viewCustData') ?>",
          type: "post",
          data: {id: $id},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_viewcust").html($response['view']).dialog('open');    
          }    
       });          
    });  
    
    $('#newdata').click(function(){    
        $.ajax({
            url: "<?php echo site_url('customer/newdata') ?>",
            type: "post",
            data: {},
            success:function(response) {
                $response = $.parseJSON(response);
                $("#modal_newdata").html($response['newdata_view']).dialog('open');    
            }    
        });        
    });  
    
    $('.edit').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('customer/editdata') ?>",
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
        var ans = confirm("Are you sure you want to remove this Customer?");    

        if (ans) {
            window.location = "<?php echo site_url('customer/removeData') ?>/"+$id;
        }
    });
    
    $('#searchdata').click(function(){
        //$('#modal_searchdata').dialog('open'); return 0;
        $.ajax({
          url: "<?php echo site_url('customer/searchdata') ?>",
          type: "post",
          data: {},
          success:function(response) {
             $response = $.parseJSON(response);
             $('#modal_searchdata').html($response['searchdata_view']).dialog('open');
          }
        });
    });
});
</script>