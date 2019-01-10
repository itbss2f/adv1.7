<link rel='stylesheet' type='text/css' href='<?php echo base_url() ?>assets/css/jquery.treeview.css'/> 
<script type='text/javascript' src='<?php echo base_url() ?>assets/js/jquery.treeview.js'></script>        
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
                    <h1>Charges</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <?php if ($canADD) : ?>
                                <li><a href="#" id="newdata"><span class="isw-plus"></span> New Charges</a></li>                                                          
                                <?php endif; ?>
                            </ul>
                        </li>    
                    </ul>                           
                <div class="clear"></div>
            </div>                        
            <div class="block-fluid table-sorting">
            
            <ul id="browser" class="filetree">
            <?php foreach ( $charges as $type => $dataintype ) : ?>            
                <li class="closed">
                <span class="folder"><?php echo $type ?></span>      
                <?php foreach ( $dataintype as $group => $data ) : ?>
                <ul> 
                    <li class="closed"><span class="folder"><?php echo $group ?></span></a>
                        <ul id="folder21">
                        <?php foreach ( $data as $row ) : ?>
                            <li>
                                <div style="width: 250px; float: left;">
                                    <a href="#" class="edit" id="<?php echo $row['id'] ?>"><span class="file"><?php echo $row["adtypecharges_name"] ?></span></a>                                
                                </div>
                                <div style="width: 80px; float: left;">
                                    <span><?php echo $row["startdate"] ?></span>                                
                                </div>
                                <div style="width: 80px; float: left;">
                                    <span><?php echo $row["enddate"] ?></span>                                
                                </div>                                                
                                <div style="width: 120px; float: left;">
                                    <span align="text-align: right"><?php echo number_format($row["amt"], 2, '.', ',') ?></span>                                
                                </div>                                                
                                <div style="width: 80px; float: left;">
                                    <span><?php echo $row["rate"].'%' ?></span>                                
                                </div>                
                                <div style="width: 50px; float: left;">
                                    <span><?php echo $row["mon"] ?></span>                                
                                </div>                
                                <div style="width: 50px; float: left;">
                                    <span><?php echo $row["tue"] ?></span>                                
                                </div>                
                                <div style="width: 50px; float: left;">
                                    <span><?php echo $row["wed"] ?></span>                                
                                </div>                
                                <div style="width: 50px; float: left;">
                                    <span><?php echo $row["thu"] ?></span>                                
                                </div>                
                                <div style="width: 50px; float: left;">
                                    <span><?php echo $row["fri"] ?></span>                                
                                </div>                
                                <div style="width: 50px; float: left;">
                                    <span><?php echo $row["sat"] ?></span>                                
                                </div>                
                                <div style="width: 50px; float: left;">
                                    <span><?php echo $row["sun"] ?></span>                                
                                </div>                
                                <div class="clear"></div>
                            </li>                            
                        <?php endforeach; ?>
                        </ul>
                    </li>    
                </ul>
                
                <?php endforeach; ?>
            <?php endforeach; ?>
            </ul>
            <div class="clear"></div>             
        </div>

    </div>            

    <div class="dr"><span></span></div>
</div>  
<div id="modal_newdata" title="New Data Charges"></div>
<div id="modal_editdata" title="Edit Data Charges"></div>
<script>
$(function() {
    
    // first example
    $("#browser").treeview();
    
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
          url: "<?php echo site_url('conadtypecharges/editdata') ?>",
          type: "post",
          data: {id: $id},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_editdata").html($response['editdata_view']).dialog('open');    
          }    
       });      
    });


    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('conadtypecharges/newdata') ?>",
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
