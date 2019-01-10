<table cellpadding="1" cellspacing="1">   
    <tbody>      
    <?php 

        for ($x = 0; $x < count($data); $x++) : 

            ?>    
            <tr>
                <td width="30" align="left"><?php echo $data[$x]['ao_num'] ?></td>
                <td width="70" align="left"><?php echo $data[$x]['bclass'] ?></td>   
                <td width="70" align="left"><?php echo $data[$x]['dclass'] ?></td>
                <td width="150" align="left"><?php echo $data[$x]['ao_payee'] ?></td>
                <td width="150" align="left"><?php echo $data[$x]['agency'] ?></td>
                <td width="80" align="left"><?php echo $data[$x]['size'] ?></td>
                <td width="80" align="left"><?php echo $data[$x]['adtype_name'] ?></td>
                <td width="130" align="left"><?php echo $data[$x]['ao_ref'] ?></td>
            </tr> 
            <?php 
        endfor;  
    ?>
    </tbody>       
</table>