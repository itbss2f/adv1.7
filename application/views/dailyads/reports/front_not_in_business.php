<table cellpadding="0" cellspacing="0">

<thead>

<tr style="font-size:10px">

     <th style="text-align:center;max-width:130px ;width:150px;">RN #</th>
    
    <th style="text-align:center;max-width:130px ;width:150px;">PO #</th>
    
    <th style="text-align:center;max-width:250px ;width:260px;">Advertiser</th>
    
    <th style="text-align:center;max-width:260px ;width:260px;">Agency</th>
    
    <th style="text-align:center;max-width:130px ;width:150px;">Ad Size</th>
    
    <th style="text-align:center;max-width:130px ;width:150px;">Color</th>
    
    <th style="text-align:center;max-width:130px ;width:150px;">Section</th>

</tr>

</thead>

<tbody>

 <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<tr style="font-size:10px">

    <td><?php echo $result[$ctr]['ao_num'] ?></td>
    
    <td><?php echo $result[$ctr]['PONumber'] ?></td>
       
    <td><?php echo $result[$ctr]['advertiser'] ?></td>
    
    <td><?php echo $result[$ctr]['agency'] ?></td>
    
    <td><?php echo $result[$ctr]['size'] ?></td>
    
    <td><?php echo $result[$ctr]['color_code'] ?></td>
    
    <td><?php echo $result[$ctr]['section']." ".$result[$ctr]['book_name']." ".$result[$ctr]['pagenum'] ?></td>

</tr>

<?php } ?>

<?php if(count($result) <= 0) { ?>
      <tr>
      
        <td colspan="7" style="text-align: center;">NO RESULTS FOUND</td>
        
      </tr>

<?php } ?>

</tbody>

</table>