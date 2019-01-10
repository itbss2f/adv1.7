<?php foreach($ingroup as $row) : ?>
<tr>                       
    <td><?php echo $row['adtype_code'] ?></td>
    <td><?php echo $row['adtype_name'] ?></td>                                           
    <td>  
        <?php if ($canDELETE) : ?>      
        <span class="icon-trash remove" id="<?php echo $row['id'] ?>" data-value='D' title="Remove"></span>      
        <?php endif; ?>
    </td>   
</tr>
<?php endforeach; ?>

<script>
$(document).ready(function() {
    
    $('.remove').click(function() {
        var id = $(this).attr('id');
        var event = $(this).attr('data-value');      

        var ans = confirm('Are you sure you want this adtype to be remove under this group?.');
            if (ans) {                
                doAdtypeAccessGroup(id, event);
            }
    });

});
</script>
