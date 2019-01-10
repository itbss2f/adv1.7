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
                    <h1>Exdeal - Contact Person</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <?php if ($canADD) : ?> 
                                <li><a href="#" id="newdata"><span class="isw-plus"></span> New Condition</a></li>  
                                <?php endif; ?>                                                        
                            </ul>
                        </li>    
                    </ul>                      
                <div class="clear"></div>
            </div>
            <div class="block-fluid table-sorting">
            <table class="table table-condensed">
            <thead>
                <tr>
                    <th style='white-space:nowrap;'>Condition</th>
                    <th style='white-space:nowrap;'>Category</th>
                    <th style='white-space:nowrap;'>Status</th>
                    <th style='white-space:nowrap;'></th>                                                                
                </tr>
            </thead>
            <tbody>        
                <?php   foreach ($result as $result) : ?>    
                <tr>
                    <td><?php echo $result->barter_condition ?></td>
                    <td><?php echo $result->category ?></td>
                    <td><?php echo $result->con_status ?></td>
                    <td>
                        <?php if ($canEDIT) : ?>
                        <span class="icon-pencil edit"  id="<?php echo $result->id ?>" title="Edit"></span>
                        <?php endif; ?>
                        <?php if ($canDELETE) : ?> 
                        <span class="icon-trash remove" id="<?php echo $result->id ?>" title="Remove"></span>
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
<div id="modal_newdata" title="Barter Condition"></div>
<div id="modal_editdata" title="Barter Condition"></div>
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
          url: "<?php echo site_url('exdeal_bartercondition/openform') ?>",
          type: "post",
          data: {id: $id,action:'update'},
          success:function(response) {
             $response = $.parseJSON(response);
             $("#modal_newdata").html($response).dialog('open');    
          }    
       });      
    });

    $('.remove').click(function() {
        var $id = $(this).attr('id');
        var ans = confirm("Are you sure you want to remove this contact person?");    

        if (ans) {
            window.location = "<?php echo site_url('exdeal_bartercondition/delete') ?>/"+$id;
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('exdeal_bartercondition/openform') ?>",
          type: "post",
          data: {action:'insert'},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_newdata").html($response).dialog('open');    
          }    
       });        
    });    
});
</script>
