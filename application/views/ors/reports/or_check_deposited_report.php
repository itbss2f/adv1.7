<table cellpadding="0" cellspacing="0">
    
    <thead>
             
       <tr  style="white-space:nowrap">
          
            <th style="width:180px">PDI Collector</th>   
            <th style="width:100px">OR #</th> 
            <th style="width:140px">Bank</th>    
            <th style="width:140px">Branch</th>
            <th style="width:100px">Cheque #</th>
            <th style="width:100px">Check Amount</th>
     
      </tr>
    
    </thead>
    
    <tbody>
       
    <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
        
      <tr class='thead' id="rdefault" style="font-size: 10px;">
          
            <td><?php echo $result[$ctr]['emp_name'] ?>&nbsp;</td>   
            <td><?php echo $result[$ctr]['or_num'] ?>&nbsp;</td> 
            <td><?php echo $result[$ctr]['bank'] ?>&nbsp;</td>    
            <td><?php echo $result[$ctr]['branch_name'] ?>&nbsp;</td>
            <td><?php echo $result[$ctr]['or_creditcardnumber'] ?>&nbsp;</td>
            <td style="text-align:right"><?php echo $result[$ctr]['or_amt'] ?>&nbsp;</td>
     
      </tr>
      
      <?php } ?>
      
      <?php if(count($result) == 0) { ?>
       
           <tr>   
          
            <td colspan="6" style="text-align: center;">NO RESULTS FOUND</td>
          
          </tr>
      
      <?php } ?>
    
    </tbody>


</table>

