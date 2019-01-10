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
                    <h1>DC Subtypes</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <li><a href="#" id="newdata"><span class="isw-plus"></span> New DC Subtype</a></li>                                                          
                                <li><a href="#" id="searchdata"><span class="isw-zoom"></span> Search DC Subtype</a></li>                                                          
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
                    <th width="5%">Code</th>
                    <th width="30%">Description</th>                    
                    <th width="5%">Group</th>
                    <th width="5%">Apply</th>
                    <th width="5%">Voldisc</th>
                    <th width="5%">Vold_others</th>
                    <th width="5%">Vold_dmcm_cm</th>
                    <th width="5%">Vold_dmcm_dm</th>
                    <th width="5%">Collection</th>
                    <th width="5%">Debit1</th>
                    <th width="5%">Debit2</th>
                    <th width="5%">Credit1</th>
                    <th width="5%">Credit2</th>
                    <th width="15%">Particular</th>
                    <th width="10%">Action</th>                                                                
                </tr>
            </thead>
            <tbody>        
                <?php  foreach ($dcsubtype as $row) : ?>    
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['dcsubtype_code'] ?></td>
                    <td><?php echo $row['dcsubtype_name'] ?></td>
                    <td><?php if ($row['dcsubtype_group'] == 'D') { echo 'DEBIT';} else { echo 'CREDIT'; }  ?></td>
                    <td><?php if ($row['dcsubtype_apply'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td>
                    <td><?php if ($row['dcsubtype_voldisc'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td>
                    <td><?php if ($row['dcsubtype_vold_others'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td>
                    <td><?php if ($row['dcsubtype_vold_dmcm_cm'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td>
                    <td><?php if ($row['dcsubtype_vold_dmcm_dm'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td>
                    <td><?php if ($row['dcsubtype_collection'] == 'Y') { echo 'YES';} else { echo 'NO'; }  ?></td> 
                    <td><?php echo $row['debit1'] ?></td>
                    <td><?php echo $row['debit2'] ?></td>
                    <td><?php echo $row['credit1'] ?></td>
                    <td><?php echo $row['credit2'] ?></td>
                    <td><?php echo $row['dcsubtype_part'] ?></td>
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
<div id="modal_newdata" title="New Data DCSubtype"></div>
<div id="modal_editdata" title="Edit Data DCSubtype"></div>
<div id="modal_searchdata" title="Search Data DCSubtype"></div>
<script>
$(function() {
    $('#modal_newdata, #modal_editdata, #modal_searchdata').dialog({
       autoOpen: false, 
       closeOnEscape: false,
       draggable: true,
       width: 550,    
       height: 'auto',
       modal: true,
       resizable: false
    });       

    $('.edit').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('dcsubtype/editdata') ?>",
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
        var ans = confirm("Are you sure you want to remove this DCSubtype?");    

        if (ans) {
            window.location = "<?php echo site_url('dcsubtype/removeData') ?>/"+$id;
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('dcsubtype/newdata') ?>",
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
          url: "<?php echo site_url('dcsubtype/searchdata') ?>",
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

