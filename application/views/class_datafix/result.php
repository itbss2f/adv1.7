<?php
foreach ($list as $list) :  
?>
    <tr>
        <td><?php echo $list['ao_num'] ?></td>
        <td><?php echo $list['issuedate'] ?></td>
        <td><?php echo $list['ao_width'] ?></td>
        <td><?php echo $list['ao_length'] ?></td>
        <td><?php echo $list['ao_totalsize'] ?></td>
        <td><?php echo $list['class_name'] ?></td>
        <td><?php echo $list['subclass'] ?></td>
        <td><?php echo $list['ao_ornum'] ?></td>
        <td><?php echo $list['ordate'] ?></td>
        <td style="text-align: right;"><?php echo number_format($list['ao_oramt'], 2, '.', ',') ?></td>
        <?php if ($list['ao_paginated_status'] == 1) : ?>
        <td>Paginated</td>
        <?php else: ?>
        <td><button class="btn btn-mini editdata" id="<?php echo $list['id'] ?>" type="button">Edit Data</button></td>
        <?php endif; ?>
    </tr>
<?php 
endforeach;
?>

<script>
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'}); 



$('.editdata').click(function() {
    var $id = $(this).attr('id');
    $.ajax({
      url: "<?php echo site_url('class_datafix/editdata') ?>",
      type: "post",
      data: {id: $id},
      success:function(response) {
         $response = $.parseJSON(response);
          $("#modal_edit").html($response['editdata_view']).dialog('open');    
      }    
   });      
});


</script>
