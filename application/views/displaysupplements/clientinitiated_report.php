<table cellpatding="0" cellspacing="0">

<thead>

    <tr style="white-space: nowrap;">
    
            <th style="text-align: center;">P.O. / Contract No.</th>
            <th style="width:120px">Agency</th>
            <th style="width:120px">Advertiser</th>
            <th style="width:90px">Issue Date</th>
            <th style="width:90px">Ctrl No.</th>
            <th style="width:90px">Size</th>
            <th style="width:90px">CCM</th>
            <th style="width:90px">Amount</th>
            <th style="width:90px">AI No.</th>
            <th style="width:130px">Remarks</th>
    
    </tr>

</thead>

<tbody>

    <?php $employee = ""; ?>  
<?php $employee2 = ""; ?> 
<?php $date = ""; ?>  
<?php $date2 = ""; ?> 
<?php $total_by_date = 0; ?>  
<?php $ccm_total_by_date = 0; ?>  
<?php $total_by_ae = 0; ?>  
<?php $ccm_total_by_ae = 0; ?>
<?php $grand_total = 0 ?>
<?php $ccm_grand_total = 0; ?>  
<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>

 
<?php $date1 = $result[$ctr]['issue_date'] ?>

<?php if($employee != $result[$ctr]['employee']) {  ?>

        <?php $employee = $result[$ctr]['employee'];  ?>
        
        <tr style="white-space: nowrap;">
        
                <td><b><?php echo $employee ?></b></td>
        
        </tr>
<?php } ?>

   <tr style="white-space: nowrap;">
        <td><?php echo $result[$ctr]['ao_ref'] ?></td>   
        <td style="width:250px"><span><?php echo $result[$ctr]['cmf_name'] ?></span></td>   
        <td style="width:250px"><span><?php echo $result[$ctr]['ao_payee'] ?></span></td>   
        <td><?php echo $result[$ctr]['issue_date'] ?></td>   
        <td><?php echo $result[$ctr]['ao_num'] ?></td>   
        <td><?php echo $result[$ctr]['size'] ?></td>   
        <td style="text-align:right"><?php echo $result[$ctr]['ccm'] ?></td>   
        <td style="text-align:right"><?php echo $result[$ctr]['amount'] ?></td>   
        <td ><?php echo $result[$ctr]['ao_sinum'] ?></td>   
        <td><span><?php echo $result[$ctr]['remarks'] ?></span></td>   
   </tr>
    <?php $total_by_date += $result[$ctr]['amount']   ?>
    <?php $ccm_total_by_date += $result[$ctr]['ccm'] ?>
    
    <?php $total_by_ae += $result[$ctr]['amount']   ?>
    <?php $ccm_total_by_ae += $result[$ctr]['ccm'] ?>
    
    <?php $grand_total += $result[$ctr]['amount']   ?>
    <?php $ccm_grand_total += $result[$ctr]['ccm'] ?>
    
   <?php if(!empty($result[$ctr+1]['employee'])){
       
         $employee2 = $result[$ctr+1]['employee'];
   }
   else
   {
         $employee2 = $result[$ctr]['employee']; 
   }
   
    ?>
    
   <?php if($date1 != $date2 and $ctr != 0) { ?> 
    
       <tr style="white-space: nowrap;">
             
                <td style="border-right:none">&nbsp;</td>
                <td style="width:250px;border-right:none">&nbsp;</td>
                <td style="width:250px;border-right:none">&nbsp;</td>
                <td style="border-right:none">&nbsp;</td>
                <td style="border-right:none">&nbsp;</td>
                <td style="margin-top:10px;margin-bottom:10px;border-right:none"><b>Total</b></td>
                <td style="text-align:right;margin-top:10px;margin-bottom:10px;border-right:none"><b><?php echo number_format($ccm_total_by_date ,2,'.',',')?></b></td>
                <td style="text-align:right;margin-top:10px;margin-bottom:10px;border-right:none"><b><?php echo number_format($total_by_date,2,'.',',') ?></b></td>
        
        </tr>
        
  <?php $total_by_date = 0; $ccm_total_by_date = 0; }  ?>  
  
  <?php $date2 =  $result[$ctr]['issue_date'];  ?>
 
  <?php if(  $employee2 != $employee or $ctr == (count($result)-1) ) {  ?> 

            <tr style="white-space: nowrap;">
                <td style="border-right:none">&nbsp;</td>
                <td style="width:250px;border-right:none">&nbsp;</td>
                <td style="width:250px;border-right:none">&nbsp;</td>
                <td style="border-right:none">&nbsp;</td>
                <td style="border-right:none">&nbsp;</td>
                <td style="margin-top:20px;margin-bottom:20px;border-right:none"><b>Sub Total</b></td>
                <td style="text-align:right;margin-top:20px;margin-bottom:20px;border-right:none"><b><?php echo number_format($ccm_total_by_ae ,2,'.',',')?></b></td>
                <td style="text-align:right;margin-top:20px;margin-bottom:20px;border-right:none"><b><?php echo number_format($total_by_ae,2,'.',',') ?></b></td>
        
        </tr>
   
  <?php $total_by_ae = 0; $ccm_total_by_ae = 0; }  ?>
  
   
<?php } ?>

     <tr  style="white-space: nowrap;">
     
                <td style="border-right:none">&nbsp;</td>
                <td style="width:250px;border-right:none">&nbsp;</td>
                <td style="width:250px;border-right:none">&nbsp;</td>
                <td style="border-right:none">&nbsp;</td>
                <td style="border-right:none">&nbsp;</td>
                <td style="margin-top:20px;margin-bottom:20px;border-right:none"><b>Grand Total</b></td>
                <td style="text-align:right;margin-top:20px;margin-bottom:20px;border-right:none"><b><?php echo number_format($ccm_grand_total ,2,'.',',')?></b></td>
                <td style="text-align:right;margin-top:20px;margin-bottom:20px;border-right:none"><b><?php echo number_format($grand_total,2,'.',',') ?></b></td>
        
        </tr>



</tbody>

</table>


