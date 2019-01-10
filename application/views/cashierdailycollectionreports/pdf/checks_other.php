<table cellpadding="0" cellspacing="0">

<thead>
        <tr style="white-space: nowrap;">
        
            <th style="width:190px">PDI Collector</th> 
            
            <th style="width:115px">OR No.</th>  
          
            <th style="width:115px">Bank</th>
             
            <th style="width:115px">Branch</th>
            
            <th style="width:115px">Cheque No.</th>
            
            <th style="width:115px">Check Amount</th>
         
       </tr>

</thead>

<tbody> 

    <?php if(count($result) > 0 ) { ?>     

    <?php $total_amount = 0; ?>  
    
    <?php $employee_name = ""; ?>
    
    <?php $subtotal = 0; ?>

    <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>          
    
     <tr  style="white-space: nowrap;">   
     
            <td style="text-align: left;"><?php if($employee_name != $result[$ctr]['employee_name'] ) { echo $result[$ctr]['employee_name']; }  ?></td>     
            
            <td style="text-align: center;"><?php echo $result[$ctr]['or_number'] ?></td> 
            
            <td style="text-align: center;"><?php echo $result[$ctr]['bmf_code']." - ".DATE("m/d/y",strtotime($result[$ctr]['check_date']))?></td>
            
            <td style="text-align: center;"><?php echo $result[$ctr]['bbf_bnch'] ?></td>  
            
            <td style="text-align: center;"><?php echo $result[$ctr]['check_no'] ?></td>  
            
            <td style="text-align: right;"><?php  echo number_format($result[$ctr]['check_amount'],2,'.',','); ?></td>
                                                                                  
     </tr> 
     
     <?php $subtotal += $result[$ctr]['check_amount']; ?>
     
     <?php if($employee_name != $result[$ctr]['employee_name'] ) {   ?>
    
     <tr  style="white-space: nowrap;">   
     
            <td style="text-align: left;"></td>     
            
            <td style="text-align: center;"></td> 
            
            <td style="text-align: center;"></td>
            
            <td style="text-align: center;"></td>  
            
            <td style="text-align: center;"><b>SUBTOTAL</b></td>  
            
            <td style="text-align: right;padding-top:10px;padding-bottom:10px;border-top:1px solid #000;border-bottom:1px solid #000;"><b><?php  echo number_format($subtotal,2,'.',','); ?></b></td>
                                                                                  
     </tr> 
     
     <?php $subtotal = 0; ?>  
    
    <?php } ?>  
     
    <?php $total_amount += $result[$ctr]['check_amount']; ?>  
       
    <?php $employee_name = $result[$ctr]['employee_name'] ?>  
    
    <?php } ?>    
    
    <tr>
    
        <tr  style="white-space: nowrap;">  
     
            <td style="text-align: left;"></td> 
            
            <td style="text-align: left;"></td> 
            
            <td style="text-align: center;"></td>
            
            <td style="text-align: center;"></td>  
            
            <td style="text-align: center;"><b>GRAND TOTAL</b></td>  
            
            <td style="text-align: right;padding-top:10px;padding-bottom:10px;border-top:1px solid #000;border-bottom:2px solid #000;"><?php  echo number_format($total_amount,2,'.',','); ?></td>
                                                                                  
       </tr>
    
    </tr>   
    
    <?php } ?>                                                        
        
</tbody>

</table>