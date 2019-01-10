<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">
    <div class="row-fluid">
        
        <div class="span12">                    
            <div class="head">
                <div class="isw-grid"></div>
                <h1>User Lists</h1>                    
                <ul class="buttons">
                    <?php if ($canEXPORT) : ?>
                    <li><a href"#" id="exportreport" class="isw-download" title="Export"></a></li>  
                    <?php endif; ?>                                                       
                    <!-- <li><a href="#" class="isw-print"></a></li> -->
                    <li>
                        <a href="#" class="isw-settings"></a>
                        <ul class="dd-list">
                            <?php if ($canADD) : ?>
                            <li><a href="#" id="newdata"><span class="isw-plus"></span> New User</a></li> 
                            <?php endif; ?>                                                        
                            <li><a href="#" id="searchdata"><span class="isw-zoom"></span> Search User</a></li>                                                              
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
                        <th width="10%">Employee ID</th>
                        <th width="25%">Employee Name</th>
                        <th width="25%">Username</th>
                        <th width="25%">Email</th>
                        <th width="25%">Action</th>                                                                
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($user_list)) : ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: red; font-size: 20px;">No Record Found</td>
                        </tr>

                    <?php endif; ?>
                    <?php 
                        $no = 1;
                        foreach ($user_list as $user) : ?>
                    <tr>
                        <td><?php echo $no ?></td>    
                        <td><?php echo $user['emp_id'] ?></td>    
                        <td><?php echo $user['fullname'] ?></td>
                        <td><?php echo $user['username'] ?></td>
                        <td><?php echo $user['email'] ?></td>
                        <td style="text-align:center">
                            <?php if ($canEDIT) : ?>
                            <span class="icon-pencil edit" id="<?php echo $user['id'] ?>" title="Edit User"></span>
                            <?php endif; ?>     
                            <?#php if ($canDELETE) : ?>  
                            <!-- <span class="icon-trash remove" title="Remove User"></span> -->
                            <?#php endif; ?> 
                            <?php if ($canSETUSERACCESS) : ?> 
                            <span class="icon-wrench user_access" name="user_access" id="<?php echo $user['id'] ?>" title="User Access"></span>
                            <?php endif; ?>
                            <!-- <span class="icon-magnet duplicate" id="<?#php echo $user['id'] ?>" title="Duplicate User by"></span> -->
                            <span class="icon-refresh" title="Reset Password"></span>
                        </td>
                    </tr>
                    <?php $no += 1; ?>
                    <?php endforeach; ?>    
                    </tbody>
                </table>
                <div class="clear"></div>
            </div>
        </div>                                
        
    </div>            
    
    <div class="dr"><span></span></div>            
    
</div>  
<div id="modal_access" title="User Access Setting"></div>
<div id="modal_newdata" title="New User"></div>
<div id="modal_editdata" title="Edit User"></div>
<div id="modal_searchdata" title="Search User"></div>
<div id="modal_duplicate" title="Duplicate User by"></div>
<script>
$(function() {
    $('#modal_access, #modal_newdata, #modal_editdata, #modal_searchdata, #modal_duplicate').dialog({
        autoOpen: false, 
        closeOnEscape: false,
        draggable: true,
        width: 500,    
        height:'auto',
        modal: true,
        resizable: false
    });       
    
    $('.user_access').click(function(){   
       var $userid = $(this).attr('id');    
        $.ajax({
            url: "<?php echo site_url('user/access_view') ?>",
            type: "post",
            data: {userid: $userid},
            success:function(response) {
            $response = $.parseJSON(response);            
               $("#modal_access").html($response['access_view']).dialog('open');    
            }    
        });        
    }); 

    $('.edit').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('user/editdata') ?>",
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
        var ans = confirm("Are you sure you want to remove this User?");    

        if (ans) {
            window.location = "<?php echo site_url('user/removeData') ?>/"+$id;
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('user/newdata') ?>",
          type: "post",
          data: {},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_newdata").html($response['newdata_view']).dialog('open');    
          }    
       });        
    });  
    
    $('#searchdata').click(function(){
        //$('#modal_searchdata').dialog('open'); return 0;
        $.ajax({
          url: "<?php echo site_url('user/searchdata') ?>",
          type: "post",
          data: {},
          success:function(response) {
             $response = $.parseJSON(response);
             $('#modal_searchdata').html($response['searchdata_view']).dialog('open');
          }
        });
    }); 

    $('.duplicate').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('user/duplicate') ?>",
          type: "post",
          data: {id: $id},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_duplicate").html($response['duplicate_view']).dialog('open');
              }
           });
    });
    //return false;

});

$("#exportreport").click(function()
  {
     
    
         {

            window.open("<?php echo site_url('user/generateexcel/') ?>", '_blank');
            window.focus();
         }  
    


});
</script>
