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
                    <h1>Departments</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <?php if ($canADD) : ?>
                                <li><a href="#" id="newdata"><span class="isw-plus"></span> New Department</a></li>                                                          
                                <?php endif; ?>
                                <li><a href="#" id="searchdata"><span class="isw-zoom"></span> Search Department</a></li>                                                          
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
                    <th width="15%">Dept Code</th>
                    <th width="25%">Dept Name</th>  
                    <th width="15%">Dept Branch</th>                     
                    <th width="10%">Section Name</th>                                     
                    <th width="10%">Action</th>                                                                
                </tr>
            </thead>
            <tbody>      
                <?php  foreach ($depart as $data) : ?>    
                    <tr>
                        <td><?php echo $data['id'] ?></td>
                        <td><?php echo $data['dept_code'] ?></td>
                        <td><?php echo $data['mdept_name'] ?></td>
                        <td><?php echo $data['dept_branchstatus'] ?></td>
                        <td><?php echo $data['sect_name'] ?></td>
                        <td>
                            <?php if ($canEDIT) : ?>
                            <span class="icon-pencil edit" id="<?php echo $data['id'] ?>" title="Edit"></span>
                            <?php endif; ?>
                            <?php if ($canDELETE) : ?>
                            <span class="icon-trash remove" id="<?php echo $data['id'] ?>" title="Remove"></span>
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
<div id="modal_newdata" title="New Department"></div>
<div id="modal_editdata" title="Edit Data Department"></div>
<div id="modal_searchdata_dept" title="Search Data Department"></div>
<script>

$(function() {
    $('#modal_newdata, #modal_editdata, #modal_searchdata_dept').dialog({
       autoOpen: false, 
       closeOnEscape: false,
       draggable: true,
       width: 460,    
       height: 'auto',
       modal: true,
       resizable: false
    });      

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('departments/newdata') ?>",
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
          url: "<?php echo site_url('departments/editdata') ?>",
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
        var ans = confirm("Are you sure you want to remove this Department?");    

        if (ans) {
            window.location = "<?php echo site_url('departments/removeData') ?>/"+$id;
        }
    }); 

    $('#searchdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('departments/searchdata') ?>",
          type: "post",
          data: {},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_searchdata_dept").html($response['searchdata_view']).dialog('open');    
          }    
       });        
    });    
 
});  
</script> 


