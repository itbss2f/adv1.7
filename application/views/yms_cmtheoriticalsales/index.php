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
                    <h1>YMS THEORITICAL SALES</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                            <?php if ($canADD) : ?> 
                                <li><a href="#" id="newdata"><span class="isw-plus"></span>Add</a></li>
                               <?php endif; ?>                                                         
                            <!--   <li><a href="#" id="searchdata"><span class="isw-zoom"></span> Search</a></li>  -->                                                    
                            </ul>
                        </li>    
                    </ul>                           
                <div class="clear"></div>
            </div>
            <div class="block-fluid table-sorting">
            <table cellpadding="0" cellspacing="0" width="100%" class="table">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="25%">Date From</th>
                    <th width="25%">Date To</th>
                    <th width="25%">Product</th>                    
                    <th width="25%">Rate</th>                                                                
                    <th width="5%">Action</th>                                                                
                </tr>
            </thead>
            <tbody>
               <?php $no = 1;  ?>
                <?php foreach ($list as $row) : ?>
                    <tr> 
                        <td><?php echo $no ?></td>                    
                        <td><?php echo $row['datefrom'] ?></td>
                        <td><?php echo $row['dateto'] ?></td>
                        <td><?php echo $row['product'] ?></td>
                        <td><?php echo @number_format ($row['rateamount'], 2, '.',',' )?></td>  
                        <td>
                        <?php if ($canEDIT) : ?> 
                        <span class="icon-pencil edit" id="<?php echo $row['id'] ?>" title="Edit"></span>
                        <?php endif; ?>
                        <?php if ($canDELETE) : ?>  
                        <span class="icon-trash remove" id="<?php echo $row['id'] ?>" title="Remove"></span>
                        <?php endif; ?>
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
<div id="modal_newdata" title="New YMS THEORITICAL SALES"></div>
<div id="modal_editdata" title="Edit YMS THEORITICAL SALES"></div>
 <!-- <div id="modal_searchdata" title="Search THEORITICAL SALES"></div>  -->
<script>
$(function() {
    $('#modal_newdata, #modal_editdata, #modal_searchdata').dialog({
       autoOpen: false, 
       closeOnEscape: true,
       draggable: true,
       width: 430,    
       height: 'auto',
       modal: true,
       resizable: false,
    });       

    $('.edit').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('yms_cmtheoriticalsales/editdata') ?>",
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
        var ans = confirm("Are you sure you want to remove this YMS THEORITICAL SALES?");    

        if (ans) {
            window.location = "<?php echo site_url('yms_cmtheoriticalsales/removedata') ?>/"+$id;
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('yms_cmtheoriticalsales/newdata') ?>",
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

