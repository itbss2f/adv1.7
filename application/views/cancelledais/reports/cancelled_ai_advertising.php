<table cellpadding="0" cellspacing="0">

<thead>

    <tr style="white-space:nowrap;">

        <th style="width: 80px;"><b>Ad #</b></th>
        <th style="width: 80px;"><b>Invoice Date</b></th>
        <th style="width: 150px;"><b>Client</b></th>
        <th style="width: 150px;"><b>Agency</b></th>
        <th style="width: 80px;"><b>PO #</b></th>
        <th style="width: 80px;"><b>Ad Type</b></th>
        <th style="width: 80px;"><b>Acct Exec</b></th>
        <th style="width: 80px;"><b>Total Size</b></th>
        <th style="width: 80px;"><b>Total</b></th>
        <th style="width: 100px;"><b>Agency Comm</b></th>
        <th style="width: 100px;"><b>Net Adv Sales</b></th>
        <th style="width: 80px;"><b>Sup AI</b></th>
        <th style="width: 150px;"><b>Remarks</b></th>

    </tr>

</thead>

<tbody>

<?php $grand_total_size = 0; ?>
<?php $grand_total_amt = 0; ?> 
<?php $grand_total_agency_com = 0; ?>
<?php $grand_total_net = 0; ?>


<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

        <tr style="white-space:nowrap;">
        
            <td><?php echo $result[$ctr]['ao_sinum'] ?></td>
            <td><?php echo $result[$ctr]['ao_sidate'] ?></td>
            <td><?php echo $result[$ctr]['client_name'] ?></td>
            <td><?php echo $result[$ctr]['agency_name'] ?></td>
            <td><?php echo $result[$ctr]['ao_ref'] ?></td>
            <td><?php echo $result[$ctr]['adtype_code'] ?></td>
            <td><?php echo $result[$ctr]['empprofile_code'] ?></td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['ao_totalsize'],2,'.',',') ?></td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['total_amt'],2,'.',',') ?></td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['agency_com'],2,'.',',') ?></td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['net_adv_sales'],2,'.',',') ?></td>
            <td><?php echo $result[$ctr]['ao_rfa_supercedingai'] ?></td>
            <td><?php echo $result[$ctr]['remarks'] ?></td>
        
        </tr>
        
       
        <?php $grand_total_size += $result[$ctr]['ao_totalsize']; ?>
        <?php $grand_total_amt += $result[$ctr]['total_amt']; ?>
        <?php $grand_total_agency_com += $result[$ctr]['agency_com']; ?>
        <?php $grand_total_net += $result[$ctr]['net_adv_sales']; ?>
        
     


<?php } ?>

<?php if(count($result) <= 0) { ?>

    <tr style="white-space:nowrap;">
    
        <td colspan="13" style="text-align: center;">NO RESULTS FOUND</td>
    
    </tr>


<?php } else { ?>

          <tr style="white-space:nowrap;">

            <td colspan="7" style="text-align: right;"><b>TOTAL</b></td>
            <td style="text-align: right;"><b><?php echo number_format($grand_total_size,2,'.',',') ?></b></td>
            <td style="text-align: right;"><b><?php echo number_format($grand_total_amt,2,'.',',')  ?></b></td>
            <td style="text-align: right;"><b><?php echo number_format($grand_total_agency_com,2,'.',',')  ?></b></td>
            <td style="text-align: right;"><b><?php echo number_format($grand_total_net,2,'.',',')  ?></b></td>
            <td colspan="2"></td>  
        
        </tr>

<?php } ?>

</tbody>



</table>

