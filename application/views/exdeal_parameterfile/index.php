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
                    <h1>Exdeal - Parameters</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <?php if ($canADD) : ?>    
                                <li><a href="#" id="newdata"><span class="isw-plus"></span> New Parameters</a></li>  
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
                    <th style='white-space:nowrap;'>Code</th>
                    <th style='white-space:nowrap;'>Name</th>
                    <th style='white-space:nowrap;'>Recommended By</th>
                    <th style='white-space:nowrap;'>Position</th>
                    <th style='white-space:nowrap;'>Approved By</th>
                    <th style='white-space:nowrap;'>Position</th>                                                                
                    <th style='white-space:nowrap;'>Contract No. B</th>                                                                
                    <th style='white-space:nowrap;'>Contract No. N</th>                                                                
                    <th style='white-space:nowrap;' >Action</th>                                                                
                </tr>
            </thead>
            <tbody>        
                <?php   foreach ($result as $result) : ?>    
                <tr>
                    <td><?php echo $result->company_code ?></td>
                    <td><?php echo $result->company_name ?></td>
                    <td><?php echo $result->recommended_by ?></td>
                    <td><?php echo $result->rec_position ?></td>
                    <td><?php echo $result->approved_by ?></td>
                    <td><?php echo $result->app_position ?></td>
                    <td><?php echo $result->b_last_contract_no ?></td>
                    <td><?php echo $result->n_last_contract_no ?></td>
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
          url: "<?php echo site_url('exdeal_parameterfile/openform') ?>",
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
        var ans = confirm("Are you sure you want to remove this parameter?");    

        if (ans) {
            window.location = "<?php echo site_url('exdeal_parameterfile/delete') ?>/"+$id;
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('exdeal_parameterfile/openform') ?>",
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
