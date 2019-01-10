<table cellpadding="0" cellspacing="0">

<thead>
        <tr style="white-space: nowrap;">
        
        <th style="width:100px">OR Number.</th> 
           
        <th style="width:150px">Particular</th>  
          
        <th style="width:100px">Gov Status</th>
        
        <th style="width:220px">Remarks</th>
        
        <th style="width:100px">Cash</th>
        
        <th style="width:100px">Cheque No.</th>
        
        <th style="width:100px">Check Amount</th>
        
        <th style="width:100px">W/Tax Amount</th>
        
        <?php if(in_array($report_type, $for_cashier)) { ?>   
             
        <th id="cashier_h" style="witdh:100px">Cashier</th>
        
        <?php } ?>
        
        <th style="width:100px">(%)</th>
        
        <th style="width:100px">Type</th>
        
        <th style="width:100px;">Bank</th>    
    </tr>

</thead>

<tbody>

<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>
      <tr  style="white-space: nowrap;">
      
           <td style="width:80px"><?php echo $result[$ctr]['or_num'] ?>&nbsp;</td>
           
           <td style="width:150px"><?php echo $result[$ctr]['particulars'] ?>&nbsp;</td>
           
           <td style="width:80px"><?php echo $result[$ctr]['gov_status'] ?>&nbsp;</td>
           
           <td style="width:150px"><?php echo $result[$ctr]['remarks'] ?>&nbsp;</td>
          
           <td style="text-align: right;width:80px;"><?php if($result[$ctr]['paytype'] == 'CH'){ echo $result[$ctr]['amount']; } ?>&nbsp;</td>
         
           <td style="width:80px"><?php echo $result[$ctr]['check_no'] ?>&nbsp;</td>
         
           <td style="text-align: right;width:80px;"><?php if($result[$ctr]['paytype'] == 'CK'){ echo $result[$ctr]['amount']; } ?>&nbsp;</td>
        
           <td style="width:80px;text-align:right"><?php echo number_format($result[$ctr]['wtax_amt'] ,2,'.',',')?>&nbsp;</td>
        
           <?php if(in_array($report_type, $for_cashier)) { ?>
           <td style="width:80px"><?php echo $result[$ctr]['empprofile_code'] ?>&nbsp;</td>
           <?php } ?>
         
           <td style="width:80px;text-align:right;"><?php echo number_format($result[$ctr]['wtax_percent'],2,',','.')  ?>&nbsp;</td>
         
           <td style="width:80px"><?php echo $result[$ctr]['adtype_code'] ?>&nbsp;</td>
         
           <td style="width:80px;"><?php echo $result[$ctr]['bank_code'] ?>&nbsp;</td>
           
      </tr>
<?php } ?>

<?php if(count($result) <= 0) { ?>
      
      <tr>
       
        <?php if(in_array($report_type, $for_cashier)) { ?>
     
        <td colspan="12" style="text-align: center;">NO RESULTS FOUND</td>
       
       <?php } else { ?>
      
        <td colspan="11" style="text-align: center;">NO RESULTS FOUND</td> 
      
       <?php } ?>    
      
      </tr>

<?php } ?>

</tbody>

</table>