<table cellpadding="0" cellspacing="0">

<thead>
        <tr style="white-space: nowrap;">
        
            <th style="width:100px">PR Number.</th> 
            
            <th style="width:150px">Particular</th>  
          
            <th style="width:100px">Gov Status</th>
             
            <th style="width:220px">Remarks</th>
            
            <th style="width:100px">Cash</th>
            
            <th style="width:100px">Cheque Info</th>
            
            <th style="width:100px">Check No.</th>
            
            <th style="width:100px">Type</th>

            <th style="width:100px;">Bank</th>    
   
       </tr>

</thead>

<tbody>

    <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
    
     <tr  style="white-space: nowrap;">  
     
            <td style="text-align: center;"><?php echo $result[$ctr]['pr_num'] ?></td> 
            
            <td style="text-align: left;"><?php echo $result[$ctr]['particulars'] ?></td> 
            
            <td style="text-align: center;"><?php echo $result[$ctr]['gov_status'] ?></td>
            
            <td style="text-align: left;"><?php echo $result[$ctr]['remarks'] ?></td>  
            
            <td><?php if($result[$ctr]['paytype'] == 'CH'){ echo number_format($result[$ctr]['amount'],2,'.',','); } ?></td>
            
            <td style="text-align: center;"><?php echo $result[$ctr]['check_no'] ?></td>
         
            <td style="text-align: center;"><?php //echo $result[$ctr]['check_no'] ?></td>

            <td style="text-align: center;"><?php echo $result[$ctr]['paytype'] ?></td>
            
            <td style="text-align: center;"><?php echo $result[$ctr]['bank_code'] ?></td>
            
    
     </tr>
    
    <?php } ?>


</tbody>

</table>