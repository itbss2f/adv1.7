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
                    <h1>Main Module</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <li><a href="#" id="newdata"><span class="isw-plus"></span> New Main Module</a></li>                                                          
                            </ul>
                        </li>    
                    </ul>                           
                <div class="clear"></div>
            </div>
            <div class="block-fluid table-sorting">
            <table cellpadding="0" cellspacing="0" width="100%" class="table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="25%">Name</th>                    
                    <th width="30%">Description</th>                    
                    <th width="10%">Order</th>                    
                    <th width="15%">Icon</th>                    
                    <th width="15%">Action</th>                                                                
                </tr>
            </thead>
            <tbody>        
                <?php  
                $no = 1;
                foreach ($mainmodule as $row) : ?>    
                <tr>
                    <td><?php echo $no ?></td>
                    <td><?php echo $row['name'] ?></td>                    
                    <td><?php echo $row['description'] ?></td>                    
                    <td><?php echo $row['order'] ?></td>                    
                    <td><?php echo $row['icon'] ?></td>                    
                    <td><span class="icon-pencil edit" id="<?php echo $row['id'] ?>" title="Edit"></span>
                        <span class="icon-wrench setfunction" id="<?php echo $row['id'] ?>" title="Set Function"></span>   
                        <span class="icon-trash remove" id="<?php echo $row['id'] ?>" title="Remove"></span>
                    </td>   
                </tr>
                <?php $no +=1 ?>
                <?php endforeach; ?>
            </tbody>
            </table>
            <div class="clear"></div>
            </div>
        </div>

    </div>            

    <div class="dr"><span></span></div>
</div>  
<div id="modal_newdata" title="New Data Main Module"></div>
<div id="modal_editdata" title="Edit Data Main Module"></div>
<div id="modal_setfunc" title="Set Data Main Module Function"></div>
<script>
$(function() {
    $('#modal_newdata, #modal_editdata, #modal_setfunc').dialog({
       autoOpen: false, 
       closeOnEscape: false,
       draggable: true,
       width: 430,    
       height: 'auto',
       modal: true,
       resizable: false
    });    
    
    $('.setfunction').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('mainmodule/setfunc') ?>",
          type: "post",
          data: {id: $id},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_setfunc").html($response['setfunc_view']).dialog('open');    
          }    
        });       
    });   

    $('.edit').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('mainmodule/editdata') ?>",
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
        var ans = confirm("Are you sure you want to remove this Main Module?");    

        if (ans) {
            window.location = "<?php echo site_url('mainmodule/removeData') ?>/"+$id;
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('mainmodule/newdata') ?>",
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
