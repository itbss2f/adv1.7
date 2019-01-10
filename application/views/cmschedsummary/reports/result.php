 <table cellpadding="0" cellspacing="0">

    <thead>
          <tr>
                <th colspan="2" style="text-align: center;width:670px;font-size:13px;border-bottom:none;border-left:1px solid #000;border-right:1px solid #000;"><b>General Ledger</b></th>
                <th colspan="2" style="text-align: center;width:300px;font-size:13px;border-bottom:none;border-left:1px solid #000;border-right:1px solid #000;"><b>Subsidiary Ledger</b></th>
                <th colspan="2" style="text-align: center;width:300px;font-size:13px;border-bottom:none;border-left:1px solid #000;border-right:1px solid #000;"><b>Amount</b></th>
         </tr>
         <tr>
              <th style="text-align: center;font-size:13px"><b>Acct Code</b></th>
              <th style="text-align: center;font-size:13px"><b>Acct Title</b></th>
              <th style="text-align: center;font-size:13px"><b>Code</b></th>
              <th style="text-align: center;font-size:13px"><b>Particulars</b></th>
              <th style="text-align: center;font-size:13px"><b>Debit</b></th>
              <th style="text-align: center;font-size:13px"><b>Credit</b></th>
         </tr>
    
    </thead>
    
     <tbody>
       
       <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

        <tr>
            
            <td><?php echo $result[$ctr]['caf_code'] ?>&nbsp;</td>
            
            <td><?php echo str_replace("'","`",$result[$ctr]['acct_des'] ) ?>&nbsp;</td>
            
            <td><?php echo $result[$ctr]['branch_code'] ?>&nbsp;</td>
            
            <td><?php echo $result[$ctr]['dc_part'] ?>&nbsp;</td>
            
            <td style='text-align:right'><?php if($result[$ctr]['dc_code'] == 'D'){echo number_format($result[$ctr]['debit_credit'],2,'.',','); }  ?>&nbsp;</td>
           
            <td style='text-align:right'><?php if($result[$ctr]['dc_code'] == 'C'){echo number_format($result[$ctr]['debit_credit'],2,'.',','); }  ?>&nbsp;</td>

        </tr>

    <?php } ?>

    <?php if(count($result) <= 0) { ?>

        <tr>  
          
          <td colspan="6" style="text-align: center;">NO RESULTS FOUND</td>
          
        </tr>
    <?php } ?>

   
     </tbody>


</table>



