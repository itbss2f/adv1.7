
<table cellspacing="0" cellpadding="0">

<thead>
        
    <tr>
        
        <th style="width:70px">Sec #</th>
        
        <th style="width:70px;">Page #</th>
        
        <th style="width:70px;">Size</th>
        
        <th style="width:70px;">Class</th>
        
        <th style="width:150px;">Advertiser</th>
        
        <th style="width:150px;">Agency</th>
        
        <th style="width:70px;">Color</th>
        
        <th style="width:70px;">RN #</th>
        
        <th style="width:70px;">AE</th>
            
        <th style="width:160px;">Production Notes</th>
        
        <th style="width:160px;">Comments</th>
        
        <th style="width:170px;">Remarks</th>
        
    </tr>  

</thead>


<tbody>


<?php $prod_code = ""; ?>
<?php $book_name = ""; ?>

<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>   

        <?php if($prod_code != $result[$ctr]['prod_code'] OR $book_name != $result[$ctr]['book_name']) { ?>
        
          <tr>
          
            <td><b>SECTION : </b></td>
            
            <td><b><?php echo $result[$ctr]['prod_code'] ?></b></td>
            
            <td colspan="10"><b><?php echo $result[$ctr]['book_name'] ?></b></td>
          
          
          </tr>
   
        <?php  } ?>

         <?php $prod_code = $result[$ctr]['prod_code']; ?>
        
        <?php $book_name = $result[$ctr]['book_name']; ?>     


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
