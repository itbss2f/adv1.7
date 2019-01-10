<?php 
    $color = ""; $colorpaid = "";
foreach ($list as $list) : 
if ($list['ao_coll_pickupadd'] != 0) {
    $color = "style='color: red'";   
} 

if ($list['is_payed'] == 1) {
    $colorpaid = "style='color: red;text-align: right;'";          
} else {
    $colorpaid = "style='text-align: right;'";               
}

?>
<tr>
    <td><input type="checkbox" class="checkselect" style="margin-left: 3px;" id="<?php echo $list['id'] ?>" value="<?php echo $list['ao_sinum'] ?>"></td>
    <td><a href="#<?php echo $list['ao_sinum'] ?>" class="xsinum" id="<?php echo $list['ao_sinum'] ?>" ><?php echo $list['ao_sinum'] ?></a></td>
    <td><?php echo $list['invdate'] ?></td>
    <td <?php if ($list['ao_coll_pickupadd'] == 2) { echo $color;  } ?>><?php echo $list['agencyname'] ?></td>
    <td <?php if ($list['ao_coll_pickupadd'] == 1) { echo $color;  } ?>><?php echo $list['clientname'] ?></td>
    <td><?php echo $list['collasst'] ?></td>
    <td><?php echo $list['collector'] ?></td>
    <td><?php echo $list['pickupdate'] ?></td>
    <td><?php echo $list['ao_coll_rem'] ?></td>
    <td><?php echo $list['ao_coll_returnrem'] ?></td>
    <td><?php echo $list['followupdate'] ?></td>
    <td><?php echo $list['ao_ref'] ?></td>
    <td <?php echo $colorpaid; ?> ><?php echo number_format($list['totalbilling'], 2, '.', ',') ?></td>
</tr>

<?php endforeach; ?>

<script>
$('.xsinum').click(function() {

    var id = $(this).attr('id');
    
    $.ajax({
        url: "<?php echo site_url('collectionutility/viewThisInvoice') ?>",  
        type: 'post',
        data: {id: id},
        success: function(response) {
            var $response = $.parseJSON(response);
                
            $('#assignview').html($response['assignview']).dialog('open'); 
        }    
    });

});

</script>
