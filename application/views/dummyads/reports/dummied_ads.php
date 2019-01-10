<table  cellpadding="0" cellspacing="0">

<thead>
 
    <tr>
        
        <th style="width:60px;text-align:center;">Sec #</th>
        
        <th style="width:60px;text-align:center;">Page #</th>
        
        <th style="width:60px;text-align:center;">Size</th>
        
        <th style="width:60px;text-align:center;">Class</th>
        
        <th style="width:170px;text-align:center;">Advertiser</th>
        
        <th style="width:170px;text-align:center;">Agency</th>
        
        <th style="width:60px;text-align:center;">Color</th>
        
        <th style="width:60px;text-align:center;">RN #</th>
        
        <th style="width:60px;text-align:center;">AE</th>
            
        <th style="width:170px;text-align:center;">Production Notes</th>
        
        <th style="width:170px;text-align:center;">Comments</th>

        <th style="width:170px;text-align:center;">Remarks</th>
        
    </tr>

</thead>
 
<tbody>

<?php $prod_code = ""; ?>
<?php $book_name = ""; ?>
<?php $ctr2 = 0 ?>

<?php $sec_no = 0; ?>
<?php $page = 1; ?>
<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>   

    

        <?php if($prod_code != $result[$ctr]['prod_code'] OR $book_name != $result[$ctr]['book_name']) { ?>
        
          <tr>
          
            <td style="font-size:10px; width:60px;"><b>SECTION : </b></td>
            
            <td style="font-size:10px;width:60px;"><b><?php echo $result[$ctr]['prod_code'] ?></b></td>
            
            <td style="font-size:10px;width:60px;" colspan="10"><b><?php echo $result[$ctr]['book_name'] ?></b></td>
          
          
          </tr> 
   
        <?php  } ?>
        
        <?php if($result[$ctr]['is_merge']=='x' and $ctr !=0) { ?>
         
                <?php $ctr2 = 1; ?>
         
         <?php } else { $ctr2 = 0; } ?>
         
         <?php $sec_no = $result[$ctr]['sec_no']; ?>
         
         <?php if($prod_code != $result[$ctr]['prod_code']) { ?>
        
              <?php $page = 1; ?>    
        
        <?php } ?>
                        
        <tr>
            
            <td style="font-size:10px; width:60px;"><?php echo $result[$ctr]['sec_no'] ?></td>
            
            <td style="font-size:10px;width:60px;"><?php echo $page ?></td>
            
            <td style="font-size:10px;width:60px;text-align:center"><?php echo $result[$ctr-$ctr2]['size'] ?></td>
            
            <td style="font-size:10px;width:60px;"><?php echo $result[$ctr-$ctr2]['class_code'] ?></td>
            
            <td style="font-size:10px;width:150px;"><?php echo $result[$ctr-$ctr2]['advertiser'] ?></td>
            
            <td style="font-size:10px;width:150px;"><?php echo $result[$ctr-$ctr2]['agencyname'] ?></td>
            
            <td style="font-size:10px;width:60px;"><?php echo $result[$ctr-$ctr2]['color'] ?></td>
            
            <td style="font-size:10px;width:60px;"><?php echo $result[$ctr-$ctr2]['rn_number'] ?></td>
            
            <td style="font-size:10px;width:60px;"><?php echo $result[$ctr-$ctr2]['AE'] ?></td>
            
            <td style="font-size:10px;width:150px;"><?php echo $result[$ctr-$ctr2]['remarks'] ?></td>
            
            <td style="font-size:10px;width:150px;"><?php echo $result[$ctr-$ctr2]['description'] ?></td>
            
            <td style="font-size:10px;width:150px;"><?php echo $result[$ctr-$ctr2]['comments'] ?></td>

            
        </tr>
        
     <?php if(isset($result[$ctr+1]['sec_no']) and $result[$ctr]['sec_no'] != $result[$ctr+1]['sec_no'] ) { ?>
                    
                    <?php $page += 1 ?>
        
        <?php } ?>  
         

        
        <?php $prod_code = $result[$ctr]['prod_code']; ?>
        
        <?php $book_name = $result[$ctr]['book_name']; ?>   
        
        <?php } ?> 
 
 
 <?php if(count($result) <= 0) { ?>

    <tr>
    
        <td colspan="12" style='text-align:center'>NO RESULTS FOUND</td>
        
    </tr>

<?php } ?> 

</tbody>

</table>
