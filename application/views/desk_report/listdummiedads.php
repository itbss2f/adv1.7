
<table cellpadding="1" cellspacing="1">   
    <tbody>      
    <?php 
    foreach ($data as $section => $list) : ?>
    
    <tr>
        <td width="30" align="center"><b>Section</b></td>
        <td width="30" align="center"><b><?php echo $section ?></b></td>
        <td width="50" align="center"></td>
        <td width="50" align="center"></td>
        <td width="120" align="center"></td>
        <td width="120" align="center"></td>
        <td width="50" align="center"></td>
        <td width="70" align="center"></td>
        <td width="130" align="center"></td>
        <td width="130" align="center"></td>
        <td width="100" align="center"></td>
        <td width="88" align="center"></td>
    </tr> 
    
        <?php 
        for ($x = 0; $x < count($list); $x++) :   
            if ($list[$x]['is_merge'] == 'x')  :  
            
            $stringrem = $list[$x - 1]['ao_eps'];
            /*$explode = explode("||", $prod_remarks);
            $explode2 = "";
            $stringrem = "";
            $count = 0;
            if ($prod_remarks != '') {     
            foreach ($explode as $exp) {
                //print_r2($exp);
                $explode2[] = explode("@*",$exp);
                //print_r2($explode2);
                $stringrem .= $explode2[$count][1]." ";
                
                $count += 1;
            }
            }*/
            ?>
            <tr>
                <td width="30" style="border: 1px solid #000;" align="right"><?php echo $list[$x ]['folio_number'] ?></td>
                <td width="30" style="border: 1px solid #000;" align="right"><?php echo $list[$x ]['page_number'] ?></td>
                <td width="50" style="border: 1px solid #000;" align="center"><?php echo $list[$x - 1]['boxsize'] ?></td>
                <td width="50" style="border: 1px solid #000;" align="left"><?php echo $list[$x]['class_code'] ?></td>
                <td width="120" style="border: 1px solid #000;" align="left"><?php if ($list[$x - 1]['component_type'] != 'blockout') { echo character_limiter($list[$x - 1]['payeename'], 15); } else { echo character_limiter($list[$x - 1]['box_description'], 15); } ?></td>
                <td width="120" style="border: 1px solid #000;" align="left"><?php echo character_limiter($list[$x - 1]['agency'], 15) ?></td>
                <td width="50" style="border: 1px solid #000;" align="left"><?php echo $list[$x - 1]['color'] ?></td>
                <td width="70" style="border: 1px solid #000;" align="left"><?php if ($list[$x - 1]['component_type'] != 'blockout') { echo $list[$x - 1]['ao_num']; } else { echo 'XX'.$list[$x - 1]['layout_boxes_id']; } ?></td>
                <td width="130" style="border: 1px solid #000;" align="left"><?php echo character_limiter($list[$x - 1]['ao_part_records'], 18) ?></td>
                <td width="130" style="border: 1px solid #000;" align="left"><?php echo character_limiter($stringrem, 20) ?></td>
                <td width="100" style="border: 1px solid #000;" align="left"><?php echo character_limiter($list[$x - 1]['ao_part_followup'],13) ?></td>
                <td width="88" style="border: 1px solid #000;" align="left"></td>
            </tr>
            <?php 
            else:
            
            $stringrem = $list[$x]['ao_eps'];
            /*$explode = explode("||", $prod_remarks);
            $explode2 = "";
            $stringrem = "";
            $count = 0;
            if ($prod_remarks != '') {     
            foreach ($explode as $exp) {
                //print_r2($exp);
                $explode2[] = explode("@*",$exp);
                //print_r2($explode2);
                $stringrem .= $explode2[$count][1]." ";
                
                $count += 1;
            }
            }*/
            ?>    
            <tr>
                <td width="30" style="border: 1px solid #000;" align="right"><?php echo $list[$x]['folio_number'] ?></td>
                <td width="30" style="border: 1px solid #000;" align="right"><?php echo $list[$x]['page_number'] ?></td>
                <td width="50" style="border: 1px solid #000;" align="center"><?php echo $list[$x]['boxsize'] ?></td>
                <td width="50" style="border: 1px solid #000;" align="left"><?php echo $list[$x]['class_code'] ?></td>
                <td width="120" style="border: 1px solid #000;" align="left"><?php if ($list[$x]['component_type'] != 'blockout') { echo character_limiter($list[$x]['payeename'], 15); } else { echo character_limiter($list[$x]['box_description'], 15); } ?></td>
                <td width="120" style="border: 1px solid #000;" align="left"><?php echo character_limiter($list[$x]['agency'], 15) ?></td>
                <td width="50" style="border: 1px solid #000;" align="left"><?php echo $list[$x]['color'] ?></td>
                <td width="70" style="border: 1px solid #000;" align="left"><?php if ($list[$x]['component_type'] != 'blockout') { echo $list[$x]['ao_num']; } else { echo 'XX'.$list[$x]['layout_boxes_id']; } ?></td>
                <td width="130" style="border: 1px solid #000;" align="left"><?php echo character_limiter($list[$x]['ao_part_records'], 18) ?></td>
                <td width="130" style="border: 1px solid #000;" align="left"><?php echo character_limiter($stringrem, 20) ?></td>
                <td width="100" style="border: 1px solid #000;" align="left"><?php echo character_limiter($list[$x]['ao_part_followup'],13) ?></td>
                <td width="88" style="border: 1px solid #000;" align="left"></td>
            </tr> 
            <?php 
            endif;
        endfor;  
    endforeach; 
    ?>
    </tbody>       
</table>