
<?php $name = ""; ?>

<?php foreach($subdiary as $sub) { ?>
     <page orientation='landscape' format='LEGAL' backtop='5mm' backbottom='7mm' backleft='0mm' backright='10mm'> 

      
         
       
         <page_header> 
           <div style='text-align:right; position:relative;top:-30px;right:0px' id='pages'><b> Pages : [[page_cu]] of [[page_nb]]</b> </div> 
          
    <!--               
        <div style='margin-bottom: 8px;font-size:30px;' id='company_name'><b>PHILIPPINE DAILY INQUIRER</b></div>     
        <div style='margin-bottom: 8px;font-size:20px;' id='report_name'><b>Monitoring of Booking</b></div>  
        <div style='margin-bottom: 8px;font-size:15px;' id='from_to'><b>From : <?php echo $from_date ?> To : <?php echo $to_date ?></b></div>  
      <div style='text-align:right; position:relative;top:-50px;right:10px;' id='dates'><b>Rundate : <?php echo DATE('d-m-Y h:i:s'); ?></b> </div>
        <div style='position:relative;top:50px;font-size:15px;' id='group_name'>Group Name : <b><?php echo $sub->group_name ?></b></div> 
        <div style='position:relative;top:10px;font-size:15px;' id='exdeal_amount'>Exdeal Amount : <b><?php echo number_format($sub->exdeal_contract_amount,2,'.',',') ?></b></div> 
        <div style='position:relative;top:-40px;right:805px;text-align: right;' id='cash_ratio'>Cash Ratio : <b><?php echo $sub->cash_ratio ?> % </b> </div> 
        <div style='position:relative;top:10px;right:800px;text-align: right;' id='barter_ratio'>Barter Ratio: <b> <?php echo $sub->barter_ratio ?> % </b></div>   
        <div style='position:relative;top:-40px;right:660px;text-align: right;' id='balance'>Balance: <b><?php echo number_format($sub->balance ,2,'.',',')?> </b></div>     
        <div style='position:relative;top:10px;right:630px;text-align: right;' id='contract_no'>Contract No.: <b><?php echo $sub->contract_no; ?> </b></div>     
            -->             </page_header>   
       

        
<table style="position:relative;top:-10px;width:100%;" class="print-friendly">
    <thead> 
        <tr>
            <th colspan="5" style="margin-bottom:10px;font-size:30px;border:none;text-align:left;"  id='company_name'><b>PHILIPPINE DAILY INQUIRER</b></th>
        </tr>
        <tr>    
            <th colspan="1" style="margin-bottom:10px;font-size:20px;border:none;text-align:left;"  id='report_name'><b>Monitoring of Booking</b></th>
            <th colspan="4" style="margin-bottom:10px;font-size:20px;border:none;text-align:left;"  id='group_name'>Group Name : <b><?php echo $sub->group_name ?></b></th>
        </tr>
        <tr>    
            <th style="margin-bottom:10px;font-size:20px;border:none;text-align:left;"  id='exdeal_amount'>Exdeal Amount : <b><?php echo number_format($sub->exdeal_contract_amount,2,'.',',') ?></b></th>
             <th style="margin-bottom:10px;font-size:20px;border:none;text-align:left;"  id='contract_no'>Contract No.: <b><?php echo $sub->contract_no; ?> </b></th>
        </tr>
        <tr>
            <th style="margin-bottom:10px;font-size:20px;border:none;text-align:left;"  id='cash_ratio'>Cash Ratio : <b><?php echo $sub->cash_ratio ?> % </b></th>
            <th style="margin-bottom:10px;font-size:20px;border:none;text-align:left;"  id='barter_ratio'>Barter Ratio: <b> <?php echo $sub->barter_ratio ?> % </b></th>
            <th style="margin-bottom:10px;font-size:20px;border:none;text-align:left;"  id='balance'>Balance: <b><?php echo number_format($sub->balance ,2,'.',',')?> </b></th>
        </tr>
      
        <tr style="margin-top:20px;">
            <th style="width:150px;font-size:20px;">Invoice Date</th>
            <th style="width:150px;font-size:20px;">Invoice No.</th>
            <th style="width:200px;font-size:20px;">Total Amount Due</th>
            <th style="width:150px;font-size:20px;">Cash</th>
            <th style="width:150px;font-size:20px;">Ex-Deal</th>
           <!-- <th style="width:150px;font-size:20px;">Consumption</th>            -->
        </tr>
    </thead>
    <tbody>
     <?php $balance = 0; ?>
     <?php $total_gross = 0; ?>
     <?php $total_agency_commission = 0; ?>
     <?php $total_vat = 0; ?>
     <?php $total_net = 0; ?>
     <?php $total_cash = 0; ?>
     <?php $total_exdeal_amount = 0; ?>
     <?php $total_balance = 0; ?>
     <?php foreach($result as $res) : ?>
       <?php if($sub->contract_no == $res->contract_no) { ?>
       <?php $balance += $res->exdeal_amount; ?>
       
       
         <?php $total_gross += $res->gross_amount;  ?>
         <?php $total_cash += $res->exdeal_cash;  ?>
         <?php $total_agency_commission += $res->agency_commission;  ?>
         <?php $total_vat += $res->vat_amount;  ?>
         <?php $total_net += $res->net_amount;  ?>
         <?php $total_exdeal_amount += $res->exdeal_amount;  ?>
         <?php $total_balance += $balance;  ?>
       
            <tr style="font-size: 20px;">
                <td style="text-align: center;font-size:15px;"><?php echo $res->sidate ?></td> 
                <td style="text-align: center;font-size:15px;"><?php echo $res->ao_sinum?></td>
                <td style="text-align: right;font-size:15px;"><?php echo number_format($res->net_amount,2,'.',',') ?></td> 
                <td style="text-align: right;font-size:15px;"><?php echo number_format($res->exdeal_cash,2,'.',',') ?></td> 
                <td style="text-align: right;font-size:15px;"><?php echo number_format($res->exdeal_amount,2,'.',',') ?></td> 
     <!--           <td style="text-align: right;font-size:15px;"><?php echo number_format($res->consumption,2,'.',',') ?></td>--> 
                <
            </tr>
        <?php } ?>    
     <?php endforeach; ?> 
     
        <tr >
            <td></td> 
            <td style="text-align: right;font-size:15px;"><b>TOTAL</b></td> 
             <td style="text-align: right;font-size:15px;"><b><?php echo number_format($total_net,2,'.',',') ?></b></td> 
            <td style="text-align: right;font-size:15px;"><b><?php echo number_format($total_cash,2,'.',',') ?></b></td> 
             <td style="text-align: right;font-size:15px;"><b><?php echo number_format($total_exdeal_amount,2,'.',',') ?></b></td> 
  <!--          <td style="text-align: right;font-size:15px;"><b><?php echo number_format($sub->consumption,2,'.',',') ?></b></td>   -->
        </tr>
   </tbody>
  </table>
 </page>

<?php } ?>
