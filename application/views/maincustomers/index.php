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
                    <h1>Main Customer</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <?php if ($canADD) : ?>
                                <li><a href="#" id="newdata"><span class="isw-plus"></span> New Main Customer</a></li>                                                          
                                <?php endif; ?>
                                <li><a href="#" id="searchdata"><span class="isw-zoom"></span> Search Main Customer</a></li>                                                          
                            </ul>
                        </li>    
                    </ul>                           
                <div class="clear"></div>
            </div>
            <div class="block-fluid table-sorting">
            <table cellpadding="0" cellspacing="0" width="100%" class="table">
            <thead>
                <tr>
                    <th width="10%">ID</th>
                    <th width="20%">Main Customer</th>
                    <th width="25%">Main Customer Name</th>                    
                    <th width="20%">Credit Limit</th>                    
                    <th width="10%">Action</th>                                                                
                </tr>
            </thead>
            <tbody>        
                <?php  foreach ($maincustomer as $row) : ?>    
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['cmfgroup_code'] ?></td>
                    <td><?php echo $row['cmfgroup_name'] ?></td>
                    <td><?php echo $row['cmfgroup_creditlimit'] ?></td>
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
<div id="modal_newdata" title="New Data Main Customer"></div>
<div id="modal_editdata" title="Edit Data Main Customer"></div>
<div id="modal_searchdata" title="Search Data Main Customer"></div>
<script>
$(function() {
    $('#modal_newdata, #modal_editdata, #modal_searchdata').dialog({
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
          url: "<?php echo site_url('maincustomer/editdata') ?>",
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
        var ans = confirm("Are you sure you want to remove this Main Customer?");    

        if (ans) {
            window.location = "<?php echo site_url('maincustomer/removeData') ?>/"+$id;
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('maincustomer/newdata') ?>",
          type: "post",
          data: {},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_newdata").html($response['newdata_view']).dialog('open');    
          }    
       });        
    });
    
        $('#searchdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('maincustomer/searchdata') ?>",
          type: "post",
          data: {},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_searchdata").html($response['searchdata_view']).dialog('open');    
          }    
       });        
    });    
    
});  
</script>
