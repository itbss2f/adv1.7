<table cellpadding="0" cellspacing="0">

<thead>

    <tr style="white-space: nowrap;">

        <th style=" width:107px;">Invoice #</th>
        <th style="width:107px;">Invoice Date</th>
        <th style="width:107px;">Issue Dates</th>
        <th style="width:107px;">Size</th>
        <th style="width:107px;">Ad Type</th>
        <th style="width:107px;">Total Billing</th>
        <th style="width:107px;">Plus : VAT</th>
        <th style="width:107px;">Amount Due</th>
        <th style="width:107px;">Amount Paid</th>
        <th style="width:107px;">OR #</th>
        <th style="width:107px;">OR Date</th>
        <th style="width:107px;">OR Amount</th>

    </tr>

</thead>

<tbody>

<?php $total_billing_total = 0 ?>
<?php $ao_oramt = 0 ?>
<?php $ao_vatamt = 0 ?>
<?php $amount_due = 0 ?>
<?php $or_assignamt = 0 ?>
<?php $customer = ""; ?>


<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

    <?php if($customer != $result[$ctr]['cmf_name']) { ?>

    <tr style="white-space: nowrap;">
    
        <td colspan="10"><b><?php echo $result[$ctr]['cmf_name'] ?></b></td>
    
    </tr>
    
    <?php } ?>
    
    <?php $customer  = $result[$ctr]['cmf_name'];  ?> 

    <tr style="white-space: nowrap;">
    
        <td><?php echo $result[$ctr]['ao_sinum'] ?></td>
        
        <td><?php echo $result[$ctr]['ao_sidate'] ?></td>
         
        <td><?php echo $result[$ctr]['adtype_code'] ?></td>
        
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['total_billing'],2,'.',',') ?></td>
        
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['ao_oramt'],2,'.',',') ?></td>   
        
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['ao_vatamt'],2,'.',',') ?></td>
        
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['amount_due'],2,'.',',') ?></td>
        
        <td><?php echo $result[$ctr]['ao_ornum'] ?></td>
        
        <td><?php echo $result[$ctr]['ao_ordate'] ?></td>
        
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['or_assignamt'],2,'.',',') ?></td>
    
    </tr>
    
    <?php $total_billing_total += $result[$ctr]['total_billing'] ?>
    
    <?php $ao_oramt += $result[$ctr]['ao_oramt'] ?>
    
    <?php $ao_vatamt += $result[$ctr]['ao_vatamt'] ?>
    
    <?php $amount_due += $result[$ctr]['amount_due'] ?>
    
    <?php $or_assignamt += $result[$ctr]['or_assignamt'] ?>


<?php } ?>

<?php if(count($result) > 0) { ?>

  <tr style="white-space: nowrap;">
    
        <td colspan="3"><b>TOTAL</b></td>
         
        <td style="text-align: right;"><?php echo number_format($total_billing_total,2,'.',',') ?></td>
        
        <td style="text-align: right;"><?php echo number_format($ao_oramt,2,'.',',') ?></td>   
        
        <td style="text-align: right;"><?php echo number_format($ao_vatamt,2,'.',',') ?></td>
        
        <td style="text-align: right;"><?php echo number_format($amount_due,2,'.',',') ?></td>
        
        <td></td>
        
        <td></td>
        
        <td style="text-align: right;"><?php echo number_format($or_assignamt,2,'.',',') ?></td>
    
    </tr>

<?php } else { ?>

     <tr>
     
        <td colspan="12" style="text-align: center;">NO RESULTS FOUND</td>
     
     </tr>

<?php } ?>

</tbody>

</table>
 