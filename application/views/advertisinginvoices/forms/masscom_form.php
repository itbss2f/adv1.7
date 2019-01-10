<table cellpadding="0" cellspacing="0">

<tbody>


<?php if(!empty($result)) { 
    
    $switch = true;
     
 } else { 

    $switch = false;    
     
 } ?>
  
      <tr style="border:1px solid #000">
      
            <td style="width:200px;"> <b>Advertising Invoice :</b> </td> 
            <td id="ao_sinum"><?php if($switch) { echo $result[0]['invoice_number']; }else { echo $invoice; } ?></td> 
            
            <td style="width:200px;"><b>Date :</b> </td>
            <td><?php if($switch) { echo $result[0]['invoice_date']; } ?></td> 
            
            <td style="width:200px;"><b>Type :</b> </td>
            <td><?php if($switch) { echo $result[0]['adtype_code']; } ?></td>
            
            <td style="width:200px;"><b>Pay Type :</b> </td>
            <td><?php if($switch) { echo $result[0]['paytype_name']; } ?></td>    
    
     </tr>
     
     <tr style="border:1px solid #000">
     
          <td><b>Agency :</b> </td>
          <td><?php if($switch) { echo $result[0]['agency_name']; } ?></td>
          
          <td><b>Advertiser :</b> </td>
          <td><?php if($switch) { echo $result[0]['client_name']; } ?></td>
          
          <td><b>Total Billing :</b> </td>
          <?php $total_biling ?>
             <?php for($ctr=0;$ctr<count($result);$ctr++)  { ?>     
                       <?php $total_biling += $result[$ctr]['total_billing'] ?>
             <?php } ?>
          <td colspan="3"><?php if($switch) { echo number_format($total_biling,2,'.',','); } ?></td>
     
     </tr>
     
     <tr style="border:1px solid #000">
     
        <td><b>Acct Exec :</b> </td>
        <td colspan="7"><?php if($switch) { echo $result[0]['employee_name']; } ?></td>
     
     </tr>
     
     <tr style="border:1px solid #000">
     
        <td><b>Remarks : </b></td>
        <td colspan="3"></td>
        
        
        <td><b>PO # : </b></td>
        <td colspan="3"><?php if($switch) { echo $result[0]['ao_num']; } ?></td>
     
     </tr>
     
     <tr style="border:1px solid #000">
        
        <td><b>Telephone : </b></td>
        <td colspan="3"><?php if($switch) { echo $result[0]['client_fone1']; } ?></td>
        
        
        <td><b>Telephone : </b></td>
        <td colspan="3"><?php if($switch) { echo $result[0]['agency_fone1']; } ?></td>
     
     </tr>  
     


</tbody>

</table>

<table cellpadding="0" cellspacing="0">

<thead>
 
 <tr style="text-align: center;">

    <th style="width:390px"><b>Particulars</b></th> 

    <th style="width:370px"><b>Total Amount</b></th> 
 
 </tr>
     
</thead>

<tbody>
 
 <?php if(count($result) > 0 ) { ?>
 <?php for($ctr=0;$ctr<count($result);$ctr++)  { ?>   
    <tr>
        <td><?php echo $result[$ctr]['particulars'] ?></td>
        <td><?php echo number_format($result[$ctr]['total_amount'],2,'.',',') ?></td>
    </tr>
<?php } ?>
<?php } else { ?>
    <tr>
    
        <td colspan="2" style="text-align: center;">NO RESULTS FOUND</td>
        
    </tr>

<?php } ?>
  
</tbody>

</table>
