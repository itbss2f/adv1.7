<table cellpadding="0" cellspacing="0">

<thead>

    <tr>
        
        <th style="width:60px;">Sec #</th>
        
        <th style="width:60px;">Page #</th>
        
        <th style="width:60px;">Size</th>
        
        <th style="width:60px;">Class</th>
        
        <th style="width:160px;">Advertiser</th>
        
        <th style="width:160px;">Agency</th>
        
        <th style="width:60px;">Color</th>
        
        <th style="width:60px;">RN #</th>
        
        <th style="width:60px;">AE</th>
            
        <th style="width:160px;">Production Notes</th>
        
        <th style="width:160px;">Comments</th>
        
        <th style="width:180px;">Remarks</th>
        
    </tr>

</thead>
              
<tbody>
       
<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>

        <tr>
            
            <td><?php echo $result[$ctr]['sec_no'] ?></td>
            
            <td><?php echo $result[$ctr]['page_no'] ?></td>
            
            <td><?php echo $result[$ctr]['size'] ?></td>
            
            <td><?php echo $result[$ctr]['class_code'] ?></td>
            
            <td><?php echo $result[$ctr]['advertiser'] ?></td>
            
            <td><?php echo $result[$ctr]['agencyname'] ?></td>
            
            <td><?php echo $result[$ctr]['color'] ?></td>
            
            <td><?php echo $result[$ctr]['rn_number'] ?></td>
            
            <td><?php echo $result[$ctr]['AE'] ?></td>
            
            <td><?php echo $result[$ctr]['remarks'] ?></td>
            
            <td><?php echo $result[$ctr]['description'] ?></td>
            
            <td><?php echo $result[$ctr]['comments'] ?></td>

            
        </tr>
        
 <?php } ?> 
 
 
 <?php if(count($result) <= 0) { ?>

    <tr>
    
        <td colspan="12" style='text-align:center'>NO RESULTS FOUND</td>
        
    </tr>

<?php } ?> 

</tbody>

</table>