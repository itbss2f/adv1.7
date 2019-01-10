<?php foreach($notingroup as $row) : ?>
<tr>                       
    <td><?php echo $row['adtype_code'] ?></td>
    <td><?php echo $row['adtype_name'] ?></td>                                           
    <td>    
        <?php if ($canADD) : ?>    
        <span class="icon-ok addto" id="<?php echo $row['id'] ?>" data-value='A' title="Add"></span>
        <?php endif; ?>
    </td>   
</tr>
<?php endforeach; ?>

<script>
$(document).ready(function() {
    
    $('.addto').click(function() {
        var id = $(this).attr('id');
        var event = $(this).attr('data-value');      

        var ans = confirm('Are you sure you want this adtype to be under this group?.');
            if (ans) {                
                doAdtypeAccessGroup(id, event);
            }
    });

});
</script>
