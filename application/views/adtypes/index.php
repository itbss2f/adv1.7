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
                    <h1>Ad Types</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <?php if ($canADD) : ?>
                                <li><a href="#" id="newdata"><span class="isw-plus"></span> New Ad Type</a></li>                                                          
                                <?php endif; ?>
                                <li><a href="#" id="searchdata"><span class="isw-zoom"></span> Search Ad Type</a></li>                                                          
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
                    <th width="10%">Adtype Code </th>
                    <th width="10%">Adtype Name </th>
                     <th width="10%">Adtype Type </th>
                     <th width="15%">Adtype Catad </th>
                     <th width="20%">Adtype Class </th>                   
                     <th width="30%">Adtype AR Account </th>                   
                    <th width="10%">Action</th>                                                                
                </tr>
            </thead>
            <tbody>        
                <?php foreach ($adtype as $row) : ?>    
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['adtype_code'] ?></td>
                    <td><?php echo $row['adtype_name'] ?></td>
                    <td><?php if ($row['adtype_type'] == 'D') { echo 'Displayed';} else { echo 'Classified'; }  ?></td>
                    <td><?php echo $row['catad'] ?></td>
                    <td><?php echo $row['class'] ?></td>
                    <td><?php echo $row['des'] ?></td>
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
<div id="modal_newdata" title="New Data Adtype"></div>
<div id="modal_editdata" title="Edit Data Adtype"></div>
<div id="modal_searchdata" title="Search Data Adtype"></div>
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
          url: "<?php echo site_url('adtype/editdata') ?>",
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
        var ans = confirm("Are you sure you want to remove this Adtype?");    

        if (ans) {
            window.location = "<?php echo site_url('adtype/removeData') ?>/"+$id;
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('adtype/newdata') ?>",
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
          url: "<?php echo site_url('adtype/searchdata') ?>",
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

