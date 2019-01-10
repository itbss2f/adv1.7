<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>File Uploading</h1>
                    
                <div class="clear"></div>
            </div>
            <?php echo form_open_multipart('file_upload/upload_data');?>                          
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span2" style="width:40px;margin-top:12px">Product</div>
                <div class="span2" style="width:200px;margin-top:12px">
                    <select name="product" id="product">
                        <option value="">----</option>      
                        <?php foreach ($prod as $prod) : ?>  
                        <?php if ($product == $prod['id']) : ?>                                     
                        <option value="<?php echo $prod['id'] ?>" selected="selected"><?php echo $prod['prod_name'] ?></option>  
                        <?php else : ?>                      
                        <option value="<?php echo $prod['id'] ?>"><?php echo $prod['prod_name'] ?></option>     
                        <?php endif; ?>                   
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="span1" style="width:40px;margin-top:12px">Issuedate:</div>
                <div class="span1" style="margin-top:12px"><input type="text"  id="datefrom" placeholder="" name="datefrom" value="<?php echo $datefrom ?>" class="datepicker"/></div>  

                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="find" name="find" type="button">Find</button></div>               
                <div class="clear"></div>
            </div>
            <div class="block-fluid table-sorting" style="margin-top: 10px;min-height: 450px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                    <thead>
                        <tr>                       
                            <th width="3%">AO #</th>  
                            <th width="3%">Book #</th>  
                            <th width="3%">Folio #</th>  
                            <th style="text-align: center;" width="5%">Section</th>          
                            <th style="text-align: center;" width="8%">Size</th>          
                            <th width="5%">CCM</th>                       
                            <th width="5%">Color</th>                       
                            <th style="text-align: center;" width="10%">Client</th>                       
                            <th style="text-align: center;" width="10%">Agency</th>                       
                            <th style="text-align: center;" width="10%">AE</th>                      
                            <th colspan="3" style="text-align: center;" width="5%">Action</th>                                             
                        </tr>
                    </thead>
                    <tbody id="fileattachment">  
                    <?php if (empty($list)) : ?>
                        <tr>
                            <td colspan="12" style="text-align: center; color: red; font-size: 20px;">No Record</td>
                        </tr>
                    <?php else : ?>
                    <?php endif; ?>

                    <?php 
                    $atts = array(
                                  'width'      => '900',
                                  'height'     => '1000',
                                  'scrollbars' => 'yes',
                                  'status'     => 'yes',
                                  'resizable'  => 'yes',
                                  'screenx'    => '0',
                                  'screeny'    => '0'
                                );

                                //echo anchor_popup('news/local/123', 'Click Me!', $atts);        
                    ?>

                    <?php foreach ($list as $list) : ?>
                    <tr>
                        <?php if ($list['material_status'] == 'U') :?>
                        <td style="background-color: red;"><?php echo $list['ao_num'] ?></td>
                        <?php else: ?> 
                        <td><?php echo $list['ao_num'] ?></td>
                        <?php endif;?>  
                        <td><?php echo $list['book_name'] ?></td>
                        <td><?php echo $list['folio_number'] ?></td>
                        <td><?php echo $list['class_code'] ?></td>
                        <td><?php echo $list['size'] ?></td>
                        <td><?php echo $list['ao_totalsize'] ?></td>
                        <td><?php echo $list['color_code'] ?></td>
                        <td><?php echo $list['client_name'] ?></td>
                        <td><?php echo $list['agency'] ?></td>
                        <td><?php echo $list['ae'] ?></td>
                        <td width="5%"><?php echo anchor_popup('material_upload/viewAds/'.$list['layout_boxes_id'], 'View', $atts) ?></td>
                        <td width="5%"><a id="<?php echo $list['layout_boxes_id'] ?>" class="uploadx">Upload</a></td>
                        <td width="5%"><a id="<?php echo $list['layout_boxes_id'] ?>" class="remove">Remove</a></td>
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

<div id="modal_upload" title="Uploading"></div>   

<script>


var errorcssobj = {'background': '#EED3D7','border' : '1px solid #ff5b57'}; 
var errorcssobj2 = {'background': '#cee','border' : '1px solid #00acac'};

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});  

$('#find').click(function(){
    var $product = $("#product").val();  
    var $datefrom = $("#datefrom").val();  
    $.ajax({
        url: '<?php echo site_url('material_upload/findProduct') ?>',
        type: 'post',
        data: {product: $product, datefrom: $datefrom},  
        success: function(response){
            $response = $.parseJSON(response);   
            $('#fileattachment').html($response['fileattachment']);
            
        }    
    });   
});

$(function() {
    
    $('#modal_upload').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: 430,    
    height: 'auto',
    modal: true,
    resizable: false
    
    });   
     
    $('.uploadx').click(function() {
        
        var $layout_boxes_id = $(this).attr('id');
        var $product = $('#product').val();
        var $datefrom = $('#datefrom').val();

            $.ajax({
              url: "<?php echo site_url('material_upload/upload') ?>",
              type: "post",
              data: {layout_boxes_id: $layout_boxes_id, product: $product, datefrom: $datefrom},
              success:function(response) {
                  $response = $.parseJSON(response);
                  $("#modal_upload").html($response['upload_data_view']).dialog('open');    
              }    
           });      
    });
    
});    

$('.remove').click(function() {
        var $layout_boxes_id = $(this).attr('id');
        var $product = $('#product').val();
        var $datefrom = $('#datefrom').val();
        var ans = confirm("Are you sure you want to remove?");    

        if (ans) {
            window.location = "<?php echo site_url('material_upload/remove') ?>/"+$layout_boxes_id+"/"+$product+"/"+$datefrom;
        }   
});    




</script>

