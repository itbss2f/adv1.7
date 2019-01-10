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
                    <h1>Module</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <li><a href="#" id="newdata"><span class="isw-plus"></span>New Module</a></li>                                                          
                               <li><a href="#" id="searchdata"><span class="isw-zoom"></span>Search Module</a></li>                                                          
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
                    <th width="12%">Main Module</th>
                    <th width="20%">Module</th>
                    <th width="30%">Description</th>
                    <th width="20%">Segment</th>
                    <th width="15%">Action</th>                                                                
                </tr>
            </thead>
            <tbody>        
                <?php  foreach ($module as $row) : ?>    
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['mainmodule'] ?></td>
                    <td><?php echo $row['modulename'] ?></td>
                    <td><?php echo $row['description'] ?></td>
                    <td><?php echo $row['segment_path'] ?></td>
                    <td><span class="icon-pencil edit" id="<?php echo $row['id'] ?>" title="Edit"></span>
                        <span class="icon-wrench setfunction" id="<?php echo $row['id'] ?>" title="Set Function"></span>
                        <span class="icon-trash remove" id="<?php echo $row['id'] ?>" title="Remove"></span>
                    </td>   
                </tr>
                <?php endforeach;  ?>
            </tbody>
            </table>
            <div class="clear"></div>
            </div>
        </div>

    </div>            

    <div class="dr"><span></span></div>
</div>  
<div id="modal_newdata" title="New Data Module"></div>
<div id="modal_editdata" title="Edit Data Module"></div>
<div id="modal_setfunc" title="Set Data Module Function"></div>
<div id="modal_searchdata" title="Search Data Module"></div>
<script>
$(function() {
    $('#modal_newdata, #modal_editdata, #modal_setfunc, #modal_searchdata').dialog({
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
          url: "<?php echo site_url('module/setfunc') ?>",
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
          url: "<?php echo site_url('module/editdata') ?>",
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
        var ans = confirm("Are you sure you want to remove this module?");    

        if (ans) {
            window.location = "<?php echo site_url('module/removeData') ?>/"+$id;
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('module/newdata') ?>",
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
          url: "<?php echo site_url('module/searchdata') ?>",
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
