<?php
foreach ($list as $list) :  

?>
    <tr>
        <td><?php echo $list['or_num'] ?></td>
        <td><?php echo $list['ordate'] ?></td>
        <td><?php echo $list['or_payee'] ?></td>
        <td style="text-align: right;"><?php echo number_format($list['or_amt'], 2, '.', ',') ?></td>
        <td><?php if ($list['status'] == 'O') { echo "<span style='color:red'>POSTED</span>"; } else if ($list['totalapplied'] > 0) { echo "<span style='color:red'>Remove Application</span>"; } else { echo "<span style='color:green'>Valid for datafixing</span>"; } ?></td> 
        <td>
        <?php /*if ($list['totalapplied'] < 1 || $list['status'] != 'O') :*/ ?>
        <?php if ($list['status'] != 'O') : ?>
        <button class="btn btn-mini editdata" id="<?php echo $list['or_num'] ?>" type="button">Edit Data</button>
        <?php endif; ?>
        </td>
    </tr>
<?php 
endforeach;
?>

<script>
//$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'}); 

$('.editdata').click(function() {
    var $id = $(this).attr('id');
    $.ajax({
      url: "<?php echo site_url('ordatafix/editdata') ?>",
      type: "post",
      data: {id: $id},
      success:function(response) {
         $response = $.parseJSON(response);
          $("#modal_edit").html($response['editdata_view']).dialog('open');    
      }    
   });      
});


</script>
