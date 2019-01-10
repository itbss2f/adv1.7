<table cellpadding="0" cellspacing="0">

    <thead>
  
    <tr  style="white-space:nowrap;">
      
        <th style="width:200px">PDI Collector</th>   
        <th style="width:120px">OR #</th> 
        <th style="width:150px">Bank</th>    
        <th style="width:150px">Branch</th>
        <th style="width:110px">Cheque #</th>
        <th style="width:120px">Check Amount</th>
        <th style="width:200px">Cashier</th>
        <th style="width:100px">Type</th>
        <th style="width:130px">Bank</th>
 
     </tr>
    
    </thead>

    <tbody>
    
      <?php $cash_total = 0 ?>
      <?php $amount_total = 0 ?>
 
        <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
 
        <tr style="white-space:nowrap;">
          
            <td><?php echo $result[$ctr]['or_num'] ?>&nbsp;</dt>   
            <td><?php echo $result[$ctr]['or_date'] ?>&nbsp;</td> 
            <td><?php echo $result[$ctr]['bmf_name'] ?>&nbsp;</td>    
            <td><?php echo $result[$ctr]['branch_name']  ?>&nbsp;</td>
            <td style="width:100px;text-align:right;"><?php echo number_format($result[$ctr]['cash'],2,'.',',')?>&nbsp;</td>
            <td style="width:100px;text-align:right;"><?php echo number_format($result[$ctr]['check_amount'],2,'.',',') ?>&nbsp;</td>
            <td><?php echo $result[$ctr]['empprofile_code'] ?>&nbsp;</td>
            <td><?php echo $result[$ctr]['adtype_code'] ?>&nbsp;</td>
            <td><?php echo $result[$ctr]['bmf_code'] ?>&nbsp;</td>
     
       </tr>
        <?php $cash_total += $result[$ctr]['cash'] ?>
        <?php $amount_total += $result[$ctr]['check_amount']; ?>
  
        <?php } ?>

        <?php if(count($result)>0){ ?>

        <tr style="white-space:nowrap;">
          
            <td>&nbsp;</td>   
            <td>&nbsp;</td> 
            <td>&nbsp;</td>    
            <td>&nbsp;</td>
            <td style="width:100px;text-align:right;"><?php echo number_format($cash_total,2,'.',',') ?>&nbsp;</td>
            <td style="width:100px;text-align:right;"><?php echo number_format($amount_total,2,'.',',') ?>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
     
       </tr>


        <?php } else { ?>

                <tr>
                
                    <td colspan="9" style="white-space:nowrap;">NO RESULT FOUND</td>
                    
                </tr>

        <?php } ?>
    
    </tbody>

</table>

 