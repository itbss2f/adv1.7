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
                    <h1>Advertising Parameter</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <?php if ($canADD) : ?>
                                <li><a href="#" id="newdata"><span class="isw-plus"></span> New Ad Parameter</a></li>                                                          
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
                    <th width="5%">ID</th>
                    <th width="10%">Ad Parameter Code</th>
                    <th width="25%">Ad Parameter Name</th>                    
                    <th width="5%">Action</th>                                                                
                </tr>
            </thead>
            <tbody>        
                <?php  foreach ($adparameter as $row) : ?>    
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['com_code'] ?></td>
                    <td><?php echo $row['com_name'] ?></td>
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
<div id="modal_newdata" title="New Data Ad Parameter"></div>
<div id="modal_editdata" title="Edit Data Ad Parameter"></div>
<script>
$(function() {
    $('#modal_newdata, #modal_editdata').dialog({
       autoOpen: false, 
       closeOnEscape: false,
       draggable: true,
       width: 850,    
       height: 'auto',
       modal: true,
       resizable: false
    });       

    $('.edit').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('adparameter/editdata') ?>",
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
        var ans = confirm("Are you sure you want to remove this Ad Parameter?");    

        if (ans) {
            window.location = "<?php echo site_url('adparameter/removeData') ?>/"+$id;
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('adparameter/newdata') ?>",
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

