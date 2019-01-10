<table cellpadding="0" cellspacing="0">

<thead>

<tr style="font-size:10px">

     <th style="text-align:center;width:125px;">Edition / Section</th>
    
    <th style="text-align:center;width:105px;">N / P</th>
    
    <th style="text-align:center;width:105px;">B / W</th>
    
    <th style="text-align:center;width:105px;">Spot 1</th>
    
    <th style="text-align:center;width:105px;">Spot 2</th>
    
    <th style="text-align:center;width:105px;">Full Color</th>
    
    <th style="text-align:center; width:105px;">Total</th>

</tr>

</thead>

<tbody>

<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<tr style="font-size:10px">

     <td><?php echo $result[$ctr]['book_name'] ?></td>
     <td style="text-align: right;"><?php echo number_format($result[$ctr]['np'],2,'.',',')  ?></td>
     <td style="text-align: right;"><?php echo number_format($result[$ctr]['b_w'],2,'.',',') ?></td>
     <td style="text-align: right;"><?php echo number_format($result[$ctr]['spo'],2,'.',',') ?></td>
     <td style="text-align: right;"><?php echo number_format($result[$ctr]['sp2'],2,'.',',') ?></td>
     <td style="text-align: right;"><?php echo number_format($result[$ctr]['full_color'],2,'.',',') ?></td>
     <td style="text-align: right;"><?php echo number_format($result[$ctr]['total_'],2,'.',',') ?></td>

</tr>

<?php } ?>

<?php if(count($result) <= 0 ) { ?>
     <tr>
        
        <td colspan="7" style="text-align: center;">NO RESULTS FOUND</td>
     
     </tr>


<?php } ?>

</tbody>

</table>