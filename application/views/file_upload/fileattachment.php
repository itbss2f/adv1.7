<?php if (empty($list)) : ?>
    <tr>
        <td colspan="8" style="text-align: center; color: red; font-size: 20px;">No Record Found</td>
    </tr>

<?php endif; ?>

<?php 
$atts = array(
              'width'      => '3000',
              'height'     => '3000',
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
    <td><?php echo anchor_popup('file_upload/viewfile/'.$list['id'], 'View', $atts) ?></td>
    <td>
    <?php if ($canDELETE) : ?>
    <a href="<?php echo site_url('file_upload/removedata/'.$list['id'].'/'.$list['ao_num']) ?>" class="delete" id="delete" name="delete">Delete</a>
    <?php endif; ?>
    </td>
    <td><?php echo $list['filename'] ?></td>
    <td><?php echo $list['filetype'] ?></td>
    <td><?php echo $list['username'] ?></td>
    <td><?php echo $list['uploaddate'] ?></td>
    <td><?php echo $list['username'] ?></td>
    <td><?php echo $list['reuploaddate'] ?></td>
</tr>
<?php endforeach; ?>


<script>

$(".delete").click(function () {
    
    var ans = window.confirm("Are you sure you want to delete?")

    if (ans)
    {
    window.alert("Successfully delete.");
    return true;
    }
    else
    {
    //window.alert("Are you sure you want to cancel?");
    return false;    
    }
    
});


</script>

