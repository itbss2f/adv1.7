<?php if (empty($list)) : ?>
    <tr>
        <td colspan="12" style="text-align: center; color: red; font-size: 20px;">No Record Found</td>
    </tr>

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

<script>
    
$(function() {
     

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
    
     $('.remove').click(function() {
        var $layout_boxes_id = $(this).attr('id');
        var $product = $('#product').val();
        var $datefrom = $('#datefrom').val();
        var ans = confirm("Are you sure you want to remove?");    

        if (ans) {
            window.location = "<?php echo site_url('material_upload/remove') ?>/"+$layout_boxes_id+"/"+$product+"/"+$datefrom;
        }
    });
    
});

    
</script>        

