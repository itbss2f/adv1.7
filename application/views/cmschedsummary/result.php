

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
