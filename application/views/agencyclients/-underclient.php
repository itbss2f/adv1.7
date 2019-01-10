<?php foreach($underclient as $row) : ?>
<tr>                       
    <td><?php echo $row['cmf_code'] ?></td>
    <td><?php echo $row['cmf_name'] ?></td>                                           
    <td style="text-align: center;"><b><?php echo $row['acmf_ppd'] ?></b></td>                                           
    <td>        
        <?php if ($canDELETE) : ?>     
        <span class="icon-trash remove" id="<?php echo $row['id'] ?>" data-value='D' title="Remove"></span>
        <?php endif; ?>
        <span class="icon-tasks ppdrem" id="<?php echo $row['mid'] ?>" title="PPD % and Remarks"></span>     
    </td>   
</tr>
<?php endforeach; ?>

<script>
$(document).ready(function() {
    $('.remove').click(function() {
        var id = $(this).attr('id');
        var event = $(this).attr('data-value');      

        var ans = confirm('Are you sure you want this client to be remove under this agency?.');
        if (ans) {        
            doAgencyClient(id, event);
        }    
    });
    
    
    $('.ppdrem').click(function() {
        var id = $(this).attr('id');   

        $.ajax({
            url: '<?php echo site_url('agencyclient/ppdremarks') ?>',
            type: 'post',
            data: {id : id},
            success: function(response) {
                $response = $.parseJSON(response);
                $("#modal_ppddata").html($response['ppddata_view']).dialog('open');        
            }    
        });
    });

});
</script>