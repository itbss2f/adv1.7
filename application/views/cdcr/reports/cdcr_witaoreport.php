<table cellpadding="0" cellspacing="0">

<thead>
 
     <tr style="white-space:nowrap;text-align:center">
        <th style="width:90px">OR Number.</th>    
        <th style="width:150px">Particular</th>    
        <th style="width:90px">Gov Status</th>
        <th style="width:90px">OR Amount</th>
        <th style="width:90px">Check Amount</th>
        <th style="width:90px">W/Tax Amount</th>
        <th style="width:50px">(%)</th>
        <th style="width:90px">Invoice No</th>
        <th style="width:50px">Ad Size</th>
        <th style="width:90px;">Issue Date</th>    
        <th style="width:90px;">Amount Due</th>    
        <th style="width:90px;">Amount Paid</th>    
        <th style="width:90px;">Amount for CM</th>    
        <th style="width:150px;">Remarks</th>    
    </tr>

</thead>

<tbody>

<?php $or_amt_total = 0; ?>
<?php $amt_due_total = 0; ?>
<?php $amt_paid_total = 0; ?>

<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>
      <tr  style="white-space:nowrap">
      
           <td style="width:100px"><?php echo $result[$ctr]['or_num'] ?>&nbsp;</td>
           <td style="width:150px"><?php echo $result[$ctr]['particulars'] ?>&nbsp;</td>
           <td style="width:60px"><?php echo $result[$ctr]['gov_status'] ?>&nbsp;</td>
           <td style="width:70px;text-align:right"><?php echo number_format($result[$ctr]['or_amt'] ,2,'.',',') ?>&nbsp;</td>
           <td style="width:70px"><?php // echo $result[$ctr]['or_amt'] ?>&nbsp;</td>
           <td style="width:70px;text-align:right;"><?php echo number_format($result[$ctr]['wtax_amt'],2,'.',',') ?>&nbsp;</td>
           <td style="width:80px;text-align:right;"><?php echo number_format($result[$ctr]['wtax_percent'],2,'.',',') ?>&nbsp;</td>
           <td style="width:50px;"><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</td>
           <td style="width:50px;"><?php echo $result[$ctr]['AdSize'] ?>&nbsp;</td>
           <td style="width:50px"><?php echo date("Y-m-d",strtotime($result[$ctr]['ao_issuefrom'])) ?>&nbsp;</td>
           <td style="width:70px;text-align:right"><?php echo number_format($result[$ctr]['amount_due'],2,'.',',')  ?>&nbsp;</td>
           <td style="width:70px;text-align:right"><?php echo number_format($result[$ctr]['amountpaid'],2,'.',',') ?>&nbsp;</td>
           <td style="width:70px"><?php //echo $result[$ctr]['gov_status'] ?>&nbsp;</td>
           <td style="width:80px"><?php //echo $result[$ctr]['gov_status'] ?>&nbsp;</td>


      </tr>
      
      <?php $or_amt_total += $result[$ctr]['or_amt']; ?>
      <?php $amt_due_total += $result[$ctr]['amount_due']; ?>
      <?php $amt_paid_total += $result[$ctr]['amountpaid']; ?>
      
<?php } ?>
     
 <?php if(count($result)  >  0) { ?>   
   
    <tr  style="width: 1450px;font-size: 10px;">
           <td style="width:100px">&nbsp;</td>
           <td style="width:150px">&nbsp;</td>
           <td style="width:60px">&nbsp;</td>
           <td style="width:70px;text-align:right;border-top:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($or_amt_total,2,'.',',') ?>&nbsp;</td>   
           <td style="width:80px">&nbsp;</td>
           <td style="width:80px">&nbsp;</td>
           <td style="width:50px">&nbsp;</td>
           <td style="width:50px">&nbsp;</td>
           <td style="width:50px">&nbsp;</td>
           <td style="width:50px">&nbsp;</td>
           <td style="width:70px;text-align:right;border-top:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($amt_due_total,2,'.',',') ?>&nbsp;</td>
           <td style="width:70px;text-align:right;border-top:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($amt_paid_total,2,'.',',')?>&nbsp;</td>
           <td style="width:70px">&nbsp;</td>
           <td style="width:80px">&nbsp;</td>
      </tr>
      
<?php } ?>

 <?php if(count($result) <= 0) { ?>
      
      <tr>

        <td colspan="14" style="text-align: center;">NO RESULTS FOUND</td>

      </tr>

<?php } ?>

</tbody>

</table>