<table cellpadding="1" cellspacing="1">   
    <tbody>      
    <?php 

        for ($x = 0; $x < count($data); $x++) : 

            ?>    
            <tr>
                <td width="50" align="left"><?php echo $data[$x]['ao_num'] ?></td>
                <td width="80" align="left"><?php echo $data[$x]['ao_ref'] ?></td>   
                <td width="150" align="left"><?php echo $data[$x]['ao_billing_prodtitle'] ?></td>
                <td width="170" align="left"><?php echo $data[$x]['payeename'] ?></td>
                <td width="170" align="left"><?php echo $data[$x]['agency'] ?></td>
                <td width="50" align="center"><?php echo $data[$x]['boxsize'] ?></td>
                <td width="50" align="center"><?php echo $data[$x]['color'] ?></td>
                <td width="50" align="center"><?php echo $data[$x]['class_code'] ?></td>
                <td width="80" align="center"><?php echo $data[$x]['pagesection'] ?></td>
                <td width="50" align="right"><?php echo $data[$x]['page_number'] ?></td>
                <td width="50" align="center"><?php echo $data[$x]['pcolor'] ?></td>
            </tr> 
            <?php 
        endfor;  
    ?>
    </tbody>       
</table>