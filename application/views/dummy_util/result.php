<?php if (empty($list)) : ?>
    <tr>
        <td colspan="10" style="text-align: center; color: red; font-size: 20px;">No Record Found</td>
    </tr>

<?php endif; ?>

<?php foreach ($list as $sec => $list) : ?>

    <tr>
        <td><b><?php echo $sec ?></b></td>
        <?php foreach ($list as $row) : ?>
        <tr>
            <td><?php echo $row['issuedate'] ?></td>
            <td><?php echo $row['folio_number'] ?></td>
            <td><?php echo $row['page_number'] ?></td>
            <td><?php echo $row['boxsize'] ?></td>
            <td><?php echo $row['class_code'] ?></td>
            <td><?php echo $row['payeename'] ?></td>
            <td><?php echo $row['agency'] ?></td>
            <td><?php echo $row['color'] ?></td>
            <td><?php echo $row['ao_num'] ?></td>     
            <td><?php echo $row['ao_part_records'] ?></td>
            <td><?php 
            if ($row['ao_paginated_status'] == 1) {
                echo "<span style='color:red'>Paginated(Unpaginate to Billing)</span>";
            } else {
            ?>
                <button class="btn btn-mini unflow" id="<?php echo $row['id'] ?>" type="button">Unflow</button>           
            <?php   
            }
            
            ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tr>

<?php endforeach; ?>

<script>

$('.unflow').click(function() {
    
    var ans = confirm('Are you sure you want to unflow this box ads?');
    
    if (ans) {
        var $id = $(this).attr('id');

        $.ajax({
          url: "<?php echo site_url('dummy_util/unflowBox') ?>",
          type: "post",
          data: {id: $id, issuedate : $('#issuedate').val(), prod : $('#prod').val()},
          success:function(response) {
             $response = $.parseJSON(response);
             alert('Box successfully unflow');
             $('#dataresult').html($response['result']);    
          }    
       });
    }
          
});


</script>