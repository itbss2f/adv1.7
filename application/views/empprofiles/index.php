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
                    <h1>Employee Profile</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <li><a href="#" id="newdata"><span class="isw-plus"></span> New Profile</a></li>                                                          
                                <li><a href="#" id="searchdata"><span class="isw-zoom"></span> Search Profile</a></li>                                                          
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
                    <th width="10%">Code</th>
                    <th width="25%">Name</th>                    
                    <th width="10%">Collector</th>                    
                    <th width="10%">Cashier</th>                    
                    <th width="10%">Acct Exec</th>                    
                    <th width="10%">Marketing</th>                    
                    <th width="10%">Classifieds</th>                    
                    <th width="10%">Credit</th>                    
                    <th width="10%">CollAsst</th>                    
                    <th width="10%">AEBilling</th>                    
                    <th width="10%">Action</th>                                                                
                </tr>
            </thead>
            <tbody>        
                <?php  foreach ($employeeprofile as $row) : ?>    
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['empprofile_code'] ?></td>
                    <td><?php echo $row['name'] ?></td>
                    <td><?php if ($row['empprofile_collector'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td> 
                    <td><?php if ($row['empprofile_cashier'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td> 
                    <td><?php if ($row['empprofile_acctexec'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td> 
                    <td><?php if ($row['empprofile_marketing'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td> 
                    <td><?php if ($row['empprofile_classifieds'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td> 
                    <td><?php if ($row['empprofile_creditasst'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td> 
                    <td><?php if ($row['empprofile_collasst'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td> 
                    <td><?php if ($row['empprofile_aebilling'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td> 
                    <td><span class="icon-pencil edit" id="<?php echo $row['id'] ?>" title="Edit"></span>
                        <span class="icon-trash remove" id="<?php echo $row['id'] ?>" title="Remove"></span>
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
<div id="modal_newdata" title="New Data Employee Profile"></div>
<div id="modal_editdata" title="Edit Data Empoyee Profile"></div>
<div id="modal_searchdata" title="Search Data Empoyee Profile"></div>
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
          url: "<?php echo site_url('employeeprofile/editdata') ?>",
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
        var ans = confirm("Are you sure you want to remove this Profile?");    

        if (ans) {
            window.location = "<?php echo site_url('employeeprofile/removeData') ?>/"+$id;
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('employeeprofile/newdata') ?>",
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
          url: "<?php echo site_url('employeeprofile/searchdata') ?>",
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
