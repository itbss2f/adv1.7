<table cellpadding="0" cellspacing="0">

    <thead>
     
        <tr style="white-space:nowrap;">
          
            <th style="width:90px">OR #</th>   
            <th style="width:90px">OR Date</th> 
            <th style="width:120px">Collector/Cashier</th>    
            <th style="width:120px">Payee</th>
            <th style="width:120px">Remarks</th>
            <th style="width:90px">Amount Paid</th>
            <th style="width:90px">Payment To Ad</th>
            <th style="width:120px">Unapplied Amount</th>
            <th style="width:90px">Pay Type</th>
            <th style="width:90px">Bank</th>
            <th style="width:90px">Check Date</th>
            <th style="width:90px">Check #</th>
            <th style="width:90px">Bank</th>
     
         </tr>
    
    </thead>

    <tbody>
    
         <?php $total_net_payment = 0 ?>
         <?php $total_unapplied_amt = 0 ?>
         <?php $total_assign_amt = 0 ?>
         

        <?php for($ctr=0;$ctr<count($result);$ctr++){ ?> 

        <tr style="white-space: nowrap;;">
          
            <td ><?php echo $result[$ctr]['or_num'] ?></td>   
            <td ><?php echo $result[$ctr]['or_date'] ?>&nsbp;</td> 
            <td ><?php echo $result[$ctr]['employee_name'] ?></td>    
            <td ><?php echo $result[$ctr]['payee'] ?></td>
            <td ><?php echo $result[$ctr]['agency_client'] ?></td>
            <td ><?php echo $result[$ctr]['remarks'] ?></td>
            <td style="width:100px;text-align:right;"><?php echo number_format($result[$ctr]['net_payment'],2,'.',',')  ?></td>
            <td style="text-align:right;"><?php echo number_format($result[$ctr]['or_assignamt'],2,'.',',') ?></td>
            <td style="text-align:right;"><?php echo number_format($result[$ctr]['unapplied_amt'],2,'.',',') ?></td>
            <td ><?php echo $result[$ctr]['pay_type'] ?></td>
            <td ><?php echo $result[$ctr]['bmf_code'] ?></td>
            <td ><?php echo $result[$ctr]['or_creditcardnumber'] ?></td>
            <td ><?php echo $result[$ctr]['or_bnacc'] ?></td>

        </tr>

                 <?php $total_net_payment += $result[$ctr]['net_payment'] ?>
                 <?php $total_unapplied_amt += $result[$ctr]['unapplied_amt'] ?>
                 <?php $total_assign_amt += $result[$ctr]['or_assignamt'] ?>

        <?php } ?>



        <?php if(count($result)>0) { ?>

        <tr class='thead' id="rdefault" style="width: 1443px;font-size: 10px;">
          
            <td ></td>   
            <td ></td> 
            <td ></td>    
            <td ></td>
            <td ></td>
            <td ></td>
            <td style="border-top:1px solid #000 ; border-bottom: 1px solid #000;text-align:right;"><?php echo number_format($total_net_payment,2,'.',',')  ?></td>
            <td style="border-top:1px solid #000 ; border-bottom: 1px solid #000;text-align:right;"><?php echo number_format($total_assign_amt,2,'.',',')  ?></td>
            <td style="border-top:1px solid #000 ; border-bottom: 1px solid #000;text-align:right;"><?php echo number_format($total_unapplied_amt,2,',','.')  ?></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>

        </tr>



        <?php } else { ?>

            <tr>
            
                <td colspan="13" style="text-align:center">NO RESULTS FOUND</td>
            
            </tr>


        <?php } ?>
            
    </tbody>
    
</table>

 