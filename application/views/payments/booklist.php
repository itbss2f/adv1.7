<?php if (empty($booking_list)) : ?>
    <tr>
        <td colspan="9" style="text-align: center; color: red; font-size: 20px;">No Record Found</td>
    </tr>

<?php else : ?>
<?php foreach ($booking_list as $row) : ?>
<tr>                        
    <td width="10px"><input type="radio" name="bookid" class="bookid" value="<?php echo $row["id"]?>"></td>                    
    <td width="40px"><?php echo $row["ao_num"] ?></td>                              
    <td width="40px"><?php echo $row["ao_sinum"] ?></td>                                                                                    
    <td width="40px"><?php echo $row["issuedate"] ?></td>
    <td width="80px"><?php echo $row["clientname"] ?></td>                                    
    <td width="80px"><?php echo $row["agencyname"] ?></td>                                   
    <td width="40px"><?php echo $row["size"] ?></td>                                                          
    <td width="40px"><?php echo $row["ao_ref"] ?></td>    
    <td width="40px"><?php echo $row["paytype_name"] ?></td>                                                                                                                        
</tr>
<?php endforeach; ?>
<?php endif; ?>    