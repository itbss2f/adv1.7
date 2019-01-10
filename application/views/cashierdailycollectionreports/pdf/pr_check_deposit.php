<table cellpadding="0" cellspacing="0">

<thead>
        <tr style="white-space: nowrap;">
        
            <th style="width:100px">PDI Collector</th> 
            
            <th style="width:150px">PR No.</th>  
          
            <th style="width:100px">Bank</th>
             
            <th style="width:220px">Branch</th>
            
            <th style="width:100px">Cheque No.</th>
            
            <th style="width:100px">Check Amount</th>
         
       </tr>

</thead>

<tbody>

    <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
    
     <tr  style="white-space: nowrap;">  
     
            <td style="text-align: center;"><?php echo $result[$ctr]['employee_name'] ?></td> 
            
            <td style="text-align: left;"><?php echo $result[$ctr]['pr_number'] ?></td> 
            
            <td style="text-align: center;"><?php echo $result[$ctr]['bmf_code'] ?></td>
            
            <td style="text-align: left;"><?php echo $result[$ctr]['branch_code'] ?></td>  
            
            <td><?php  echo number_format($result[$ctr]['check_amt'],2,'.',','); ?></td>
                                                                                  
     </tr>
    
    <?php } ?>


</tbody>

</table>