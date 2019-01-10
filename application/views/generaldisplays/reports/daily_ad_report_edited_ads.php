<table cellpadding="0" cellspacing="0">

<thead>

<tr style="white-space: nowrap;">

    <th style="width:75px;">RN #</th>
    <th style="width:75px;">PO #</th>
    <th style="width:150px;">Advertiser</th>
    <th style="width:150px;">Agency</th>
    <th style="width:75px;">AE</th>
    <th style="width:75px;">Size</th>
    <th style="width:60px;">Color</th>
    <th style="width:75px;">Status</th>
    <th style="width:150px;">Section / Charges</th>
    <th style="width:150px;">Position / Remarks</th>
    <th style="width:75px;">Items</th>
    <th style="width:75px;">User</th>
    <th style="width:85px;">Issue Date</th>

</tr>


</thead>

<tbody>


<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

        <tr style="white-space: nowrap;">
        
            <td><?php echo $result[$ctr]['ao_num'] ?></td>
            <td><?php echo $result[$ctr]['PONumber'] ?></td>
            <td><?php echo $result[$ctr]['advertiser'] ?></td>
            <td><?php echo $result[$ctr]['agency'] ?></td>
            <td><?php echo $result[$ctr]['AE'] ?></td>
            <td><?php echo $result[$ctr]['size'] ?></td>
            <td><?php echo $result[$ctr]['color_code'] ?></td>
            <td><?php echo $result[$ctr]['status'] ?></td>
            <td><?php echo $result[$ctr]['product'] ?></td>
            <td><?php echo $result[$ctr]['remarks'] ?></td>
            <td><?php echo $result[$ctr]['items'] ?></td>
            <td><?php echo $result[$ctr]['username'] ?></td>
            <td><?php echo $result[$ctr]['ao_date'] ?></td>
        
        </tr>

<?php } if(count($result) <= 0) { ?>   

       <tr>
        
            <td style="text-align: center;" colspan="13">NO RESULTS FOUND</td>
       
       </tr>

<?php } ?>

</tbody>

</table>