<?php foreach ($BookingCounter as  $Booking):  ?>
<tr>
    <td><?php echo $Booking ['username'] ?></td>
    <td><?php echo $Booking ['totalaonum'] ?></td>
    <td style="text-align: right;"><?php echo  number_format($Booking['totalamount'], 2, '.', ',') ?></td>
</tr>
<?php endforeach; ?>