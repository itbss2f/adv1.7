<table cellpadding="0" cellspacing="0">

<thead>


<tr style="white-space: nowrap;vertical-align: middle;">
    
    
    <th colspan="5"  style="text-align:center;vertical-align: middle; border-bottom:none;"></th>
    
    <th colspan="4" style="text-align:center;vertical-align: middle;border-bottom:none;">BOOKING INFORMATION</th>
    
    <th colspan="3" style="text-align:center;vertical-align: middle;border-bottom:none;">PAGINATION INFORMATION</th>   


</tr>



<tr style="white-space: nowrap;">
    
    <th style="text-align:center;max-width:100px ;width:100px;">RN #</th>
    
    <th style="text-align:center;max-width:100px ;width:100px;">PO #</th>
    
    <th style="text-align:center;max-width:150px ;width:150px;">Product Title</th>
    
    <th style="text-align:center;max-width:150px ;width:150px;">Advertiser</th>
    
    <th style="text-align:center;max-width:150px ;width:150px;">Agency</th>
    
    <th style="text-align:center;max-width:70px ;width:70px;">Ad Size</th>
    
    <th style="text-align:center;max-width:70px ;width:70px;">Color</th>
    
    <th style="text-align:center;max-width:70px ;width:70px;">Section</th>
    
    <th style="text-align:center;max-width:70px ;width:70px;">Page</th>
    
    <th style="text-align:center;max-width:70px ;width:70px;">Section</th>
    
    <th style="text-align:center;max-width:70px ;width:70px;">Page</th>
    
    <th style="text-align:center;max-width:70px ;width:70px;">Color</th>   


</tr>


</thead>

<tbody>

<?php $ctr2 = 0; ?>
<?php $ctr3 = 0; ?>

<?php for($ctr=0;$ctr<count($result);$ctr++,$ctr2++) { ?>

                   
        <tr style="font-size: 10px;">
            
            <td style="max-width:62px ;width:62px"><?php echo $result[$ctr]['ao_num'] ?></td>
            
            <td style="max-width:68px ;width:68px"><?php echo $result[$ctr]['PONumber'] ?></td>
            
            <td style="max-width:62px ;width:180px"><?php echo $result[$ctr]['product_title'] ?></td>
            
            <td style="max-width:62px ;width:180px"><?php echo $result[$ctr]['advertiser'] ?></td>
            
            <td style="max-width:62px ;width:180px"><?php if(!empty($result[$ctr]['agency'])) { echo $result[$ctr]['agency'] ;} else { echo $result[$ctr]['adtype_name']; }  ?></td>
            
            <td style="text-align:center;max-width:100px ;width:100px"><?php echo $result[$ctr]['size'] ?></td>
            
            <td style="text-align:center;max-width:68px ;width:68px"><?php echo $result[$ctr]['color_code'] ?></td>
            
            <td style="text-align:center;max-width:68px ;width:68px"><?php echo $result[$ctr]['section'] ?></td>
            
            <td style="text-align:center;max-width:68px ;width:68px"><?php echo $result[$ctr]['book_name'] ?></td>
            
            <td style="text-align:center;max-width:68px ;width:68px"><?php echo $result[$ctr]['pagesection'] ?></td>
            
            <td style="text-align:center;max-width:68px ;width:68px"><?php if($ctr3 == '0') {  echo $result[$ctr]['pagenum']; } else { echo $result[$ctr]['pagenum']+1;  } ?></td>
            
            <td style="text-align:center;max-width:65px ;width:65px"><?php echo $result[$ctr]['pagecolor'] ?></td>   


        </tr> 


 <?php if(!empty($result[$ctr]['is_merge'])) { ?>

     <?php $ctr = $ctr2 - 1; ?>
     
     <?php $ctr3 = 1; ?>

<?php } else { $ctr3 = 0;} ?>
        
        <?php // $ctr = $ctr - $ctr2; ?>
        
<?php } ?>

<?php if(count($result) <= 0) { ?>

    <tr>
    
        <td colspan="12" style='text-align:center'>NO RESULTS FOUND</td>
        
    </tr>

<?php } ?>

</tbody>

</table>