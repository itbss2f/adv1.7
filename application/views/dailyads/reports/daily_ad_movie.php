<table cellpadding="0" cellspacing="0">

<thead>

<tr style="font-size:10px">

     <th style="text-align:center;max-width:80px ;width:80px;">RN #</th>
      
    <th style="text-align:center;max-width:250px ;width:250px;">Advertiser</th>
    
    <th style="text-align:center;max-width:250px ;width:250px;">AE</th>
    
    <th style="text-align:center;max-width:80px ;width:80px;">Size</th>
    
    <th style="text-align:center;max-width:80px ;width:80px;">CCM</th>
    
    <th style="text-align:center;max-width:80px ;width:80px;">Color</th>
    
    <th style="text-align:center;max-width:80px ;width:80px;">Status</th>
    
    <th style="text-align:center;max-width:150px ;width:150px;">Product</th>
    
    <th style="text-align:center;max-width:240px ;width:240px;">Position / Remarks</th>

</tr>

</thead>

<tbody>

<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<tr style="font-size:10px">

    <td><?php echo $result[$ctr]['ao_ref'] ?></td>
      
    <td><?php echo $result[$ctr]['advertiser'] ?></td>
    
    <td><?php echo $result[$ctr]['empprofile_code'] ?></td>
    
    <td><?php echo $result[$ctr]['size'] ?></td>
    
    <td><?php echo $result[$ctr]['ao_totalsize'] ?></td>
    
    <td><?php echo $result[$ctr]['ao_color'] ?></td>
    
    <td><?php echo $result[$ctr]['status'] ?></td>
    
    <td><?php echo $result[$ctr]['description'] ?></td>
    
    <td><?php echo $result[$ctr]['remarks'] ?></td>

</tr>

<?php } ?>

<?php if(count($result <= '0')) { ?>
      <tr>
            <td colspan="9" style="text-align: center;">NO RESULTS FOUND</td>      
      </tr>
<?php } ?>

</tbody>
</table>