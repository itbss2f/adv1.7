<table cellpadding="0" cellspacing="0">

<thead>


<tr style="white-space:nowrap;">

        <th style="width:80px;">OR #</th>
        <th style="width:80px;">OR Date</th>
        <th style="width:80px;">AI #</th>
        <th style="width:80px;">AI Date</th>
        <th style="width:100px;">Agency</th>
        <th style="width:100px;">Advertiser</th>
        <th style="width:80px;">AI Amount</th>
        <th style="width:80px;">Amount Paid</th>
        <th style="width:80px;">AI Balance</th>
          
</tr>

</thead>

<tbody>

<?php $adtype = ""; ?>

<?php $sub_billing_c = 0 ?>
<?php $sub_amt = 0 ?>
<?php $sub_balance_c    = 0 ?>

<?php $total_billing_c = 0 ?>
<?php $total_amt = 0 ?>
<?php $total_balance_c    = 0 ?>


<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<?php if($adtype !=  $result[$ctr]['adtype_name'] and $ctr !=0 ) { ?>

          <tr class="" style="font-size: 10px;"> 
          
                  <td colspan="9" style="text-align: center;"><b><?php echo $result[$ctr]['adtype_name'] ?></b>&nbsp;</td>
                  
          </tr>         
           
<?php } ?>


<tr class="" style="border-top:1px solid #ccc;margin-top:25px;width: 2400px;font-size: 10px;">
               
        <td style="text-align: center;"><?php echo $result[$ctr]['ao_num'] ?>&nbsp;</td>
        <td style="text-align: center;"><?php echo $result[$ctr]['ao_date'] ?>&nbsp;</td>
        <td style="text-align: center;"><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</td>
        <td style="text-align: center;"><?php echo $result[$ctr]['ao_sidate'] ?>&nbsp;</td>
        <td style="text-align: center;width:200px;;"><?php echo $result[$ctr]['agency_name'] ?>&nbsp;</td>
        <td style="text-align: center;width:200px;;"><?php echo $result[$ctr]['client_name'] ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['billing_c'],2,'.',',')  ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['ao_amt'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['balance_c'],2,'.',',') ?>&nbsp;</td>
          
</tr>

<?php $sub_billing_c += $result[$ctr]['billing_c'] ?>
<?php $sub_amt += $result[$ctr]['ao_amt'] ?>
<?php $sub_balance_c  += $result[$ctr]['balance_c'] ?>

<?php $total_billing_c += $result[$ctr]['billing_c'] ?>
<?php $total_amt += $result[$ctr]['ao_amt'] ?>
<?php $total_balance_c += $result[$ctr]['balance_c'] ?>

<?php if($adtype !=  $result[$ctr]['adtype_name'] and $ctr !=0 ) { ?>

          <tr class="" style="border-top:1px solid #ccc;margin-top:25px;width: 2400px;font-size: 10px;"> 
              
                    <td style="text-align: center;">&nbsp;</td>
                    <td style="text-align: center;">&nbsp;</td>
                    <td style="text-align: center;">&nbsp;</td>
                    <td style="text-align: center;">&nbsp;</td>
                    <td style="text-align: center;width:200px;;">&nbsp;</td>
                    <td style="text-align: center;width:200px;;">&nbsp;</td>
                    <td style="text-align: right;"><?php echo number_format($sub_billing_c,2,'.',',')  ?>&nbsp;</td>
                    <td style="text-align: right;"><?php echo number_format($sub_amt,2,'.',',') ?>&nbsp;</td>
                    <td style="text-align: right;"><?php echo number_format($sub_balance_c,2,'.',',') ?>&nbsp;</td>      
                  
          </tr>         

          <?php $adtype =  $result[$ctr]['adtype_name'] ?>
          
        <?php $sub_billing_c = 0 ?>
        <?php $sub_amt = 0 ?>
        <?php $sub_balance_c    = 0 ?>

          
<?php } ?>

<?php } ?>


<?php if(count($result) > 0) { ?>

          <tr class="" style="border-top:1px solid #ccc;margin-top:25px;width: 2400px;font-size: 10px;"> 
              
                    <td style="text-align: center;">&nbsp;</td>
                    <td style="text-align: center;">&nbsp;</td>
                    <td style="text-align: center;">&nbsp;</td>
                    <td style="text-align: center;">&nbsp;</td>
                    <td style="text-align: center;width:200px;;">&nbsp;</td>
                    <td style="text-align: center;width:200px;;">&nbsp;</td>
                    <td style="text-align: right;"><?php echo number_format($total_billing_c,2,'.',',')  ?>&nbsp;</td>
                    <td style="text-align: right;"><?php echo number_format($total_amt,2,'.',',') ?>&nbsp;</td>
                    <td style="text-align: right;"><?php echo number_format($total_balance_c,2,'.',',') ?>&nbsp;</td>      
                  
          </tr>     


<?php } else { ?>

    <tr>
    
        <td colspan="9" style="text-align: center;">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>

</tbody>

</table>

