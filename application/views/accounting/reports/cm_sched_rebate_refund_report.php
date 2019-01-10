<table cellpadding="0" cellspacing="0">

<thead>

<tr style="white-space:nowrap">

        <th style="width:81px">CM #</th>
        <th style="width:120px">Agency</th>
        <th style="width:120px">Client</th>
        <th style="width:120px">Disc. Allow.-Class</th>
        <th style="width:81px">A/R Class. Box</th>
        <th style="width:81px">A/R Class Line</th>
        <th style="width:81px">A/R Obituary</th>
        <th style="width:81px">A/R Job Market</th>
        <th style="width:81px">A/R Class Libre</th>
        <th style="width:81px">A/R Class Other</th>
        <th style="width:81px">Ad Type Others</th>
        <th style="width:81px">A/R Others</th>
        <th style="width:120px">Other Acct Title</th>
        <th style="width:81px">Amount</th>

        
</tr>


</thead>

<tbody>

<?php $amount_rec_classbox = 0 ?>
<?php $amount_rec_classlines = 0 ?>
<?php $amount_rec_obituary = 0 ?>
<?php $amount_rec_jobmarket = 0 ?>
<?php $amount_rec_classlibre = 0 ?>
<?php $amount_rec_classothers = 0 ?>
<?php $amount_rec_others = 0 ?>
<?php $other_expense_dept = 0 ?>
<?php $other_amount = 0 ?>


<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>

<tr style="white-space:nowrap">

        <td style="text-align: center;width:81px"><?php echo $result[$ctr]['payment_type'] ?>&nbsp;</td>
        <td style="text-align: center;width:81px"><?php echo $result[$ctr]['agency'] ?>&nbsp;</td>
        <td style="text-align: center;width:81px"><?php echo $result[$ctr]['client_name'] ?>&nbsp;</td>
        <td style="text-align: center;width:81px"><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</td>
        <td style="text-align: right;width:81px"><?php echo number_format($result[$ctr]['amount_rec_classbox'],2,'.',',')  ?>&nbsp;</td>
        <td style="text-align: right;width:81px"><?php echo number_format($result[$ctr]['amount_rec_classlines'],2,'.',',')  ?>&nbsp;</td>
        <td style="text-align: right;width:81px"><?php echo number_format($result[$ctr]['amount_rec_obituary'],2,'.',',')  ?>&nbsp;</td>
        <td style="text-align: right;width:81px"><?php echo number_format($result[$ctr]['amount_rec_jobmarket'],2,'.',',')  ?>&nbsp;</td>
        <td style="text-align: right;width:81px"><?php echo number_format($result[$ctr]['amount_rec_classlibre'],2,'.',',')  ?>&nbsp;</td>
        <td style="text-align: right;width:81px"><?php echo number_format($result[$ctr]['amount_rec_classothers'],2,'.',',')  ?>&nbsp;</td>
        <td style="text-align: center;width:81px"><?php echo $result[$ctr]['dc_acct'] ?>&nbsp;</td>
        <td style="text-align: right;width:81px"><?php echo number_format($result[$ctr]['amount_rec_others'],2,'.',',')  ?>&nbsp;</td>
        <td style="text-align: right;width:81px"><?php echo number_format($result[$ctr]['other_expense_dept'],2,'.',',')  ?>&nbsp;</td>
        <td style="text-align: right;width:81px"><?php echo number_format($result[$ctr]['other_amount'],2,'.',',')  ?>&nbsp;</td>

   
        <?php $amount_rec_classbox      += $result[$ctr]['amount_rec_classbox'] ?>
        <?php $amount_rec_classlines    += $result[$ctr]['amount_rec_classlines'] ?>
        <?php $amount_rec_obituary      += $result[$ctr]['amount_rec_obituary'] ?>
        <?php $amount_rec_jobmarket     += $result[$ctr]['amount_rec_jobmarket'] ?>
        <?php $amount_rec_classlibre    += $result[$ctr]['amount_rec_classlibre'] ?>
        <?php $amount_rec_classothers   += $result[$ctr]['amount_rec_classothers'] ?>
        <?php $amount_rec_others        += $result[$ctr]['amount_rec_others'] ?>
        <?php $other_expense_dept       += $result[$ctr]['other_expense_dept'] ?>
        <?php $other_amount             += $result[$ctr]['other_amount'] ?>
           
        
</tr>

<?php } ?>

<?php if(count($result) > 0) { ?>

    <tr style="white-space:nowrap">

            <td style="text-align: center;width:81px">&nbsp;</td>
            <td style="text-align: center;width:81px">&nbsp;</td>
            <td style="text-align: center;width:81px">&nbsp;</td>
            <td style="text-align: center;width:81px">TOTAL&nbsp;</td>
            <td style="text-align: right;width:81px"><?php echo number_format($amount_rec_classbox,2,'.',',')  ?>&nbsp;</td>
            <td style="text-align: right;width:81px"><?php echo number_format($amount_rec_classlines,2,'.',',')  ?>&nbsp;</td>
            <td style="text-align: right;width:81px"><?php echo number_format($amount_rec_obituary,2,'.',',')  ?>&nbsp;</td>
            <td style="text-align: right;width:81px"><?php echo number_format($amount_rec_jobmarket,2,'.',',')  ?>&nbsp;</td>
            <td style="text-align: right;width:81px"><?php echo number_format($amount_rec_classlibre,2,'.',',')  ?>&nbsp;</td>
            <td style="text-align: right;width:81px"><?php echo number_format($amount_rec_classothers,2,'.',',')  ?>&nbsp;</td>
            <td style="text-align: center;width:81px">&nbsp;</td>
            <td style="text-align: right;width:81px"><?php echo number_format($amount_rec_others,2,'.',',')  ?>&nbsp;</td>
            <td style="text-align: right;width:81px"><?php echo number_format($other_expense_dept,2,'.',',')  ?>&nbsp;</td>
            <td style="text-align: right;width:81px"><?php echo number_format($other_amount,2,'.',',')  ?>&nbsp;</td>

            
    </tr>



<?php } else {  ?>

     <tr style="white-space:nowrap">
        
        <td colspan="14" style="text-align:center">NO RESULTS FOUND</td>
    
    </tr>
    
<?php } ?>

</tbody>


</table>


