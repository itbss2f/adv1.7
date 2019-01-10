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
                    <h1>Debit Credit</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <li><a href="#" id="newdata"><span class="isw-plus"></span> New Debit Credit</a></li>                                                          
                                <li><a href="#" id="searchdata"><span class="isw-zoom"></span> Search Debit Credit</a></li>                                                          
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
                    <th width="25%">Debit Credit Code</th>                    
                    <th width="25%">Debit Credit Name</th>                    
                    <th width="25%">Debit Credit Apply</th>                    
                    <th width="10%">Action</th>                                                                
                </tr>
            </thead>
            <tbody>        
                <?php  foreach ($debitcredit as $row) : ?>    
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['tdcf_code'] ?></td>
                    <td><?php echo $row['tdcf_name'] ?></td>
                    <td><?php if ($row['tdcf_apply'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td>
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
<div id="modal_newdata" title="New Data Debit Credit"></div>
<div id="modal_editdata" title="Edit Data Debit Credit"></div>
<div id="modal_searchdata" title="Search Data Debit Credit"></div>
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
          url: "<?php echo site_url('debitcredit/editdata') ?>",
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
        var ans = confirm("Are you sure you want to remove this Debit Credit?");    

        if (ans) {
            window.location = "<?php echo site_url('debitcredit/removeData') ?>/"+$id;
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('debitcredit/newdata') ?>",
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
          url: "<?php echo site_url('debitcredit/searchdata') ?>",
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

