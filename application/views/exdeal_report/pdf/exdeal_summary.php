
<?php $name = ""; ?>


     <page orientation='landscape' format='LEGAL' backtop='5mm' backbottom='7mm' backleft='0mm' backright='10mm'> 

      
         
       
         <page_header> 
           <div style='text-align:right; position:relative;top:-30px;right:0px' id='pages'><b> Pages : [[page_cu]] of [[page_nb]]</b> </div> 
          
         </page_header>   
       

        
<table style="position:relative;top:-10px;width:100%;" class="print-friendly">
    <thead> 
        <tr>
            <th colspan="7" style="margin-bottom:10px;font-size:30px;border:none;text-align:left;"  id='company_name'><b>PHILIPPINE DAILY INQUIRER</b></th>
        </tr>
        <tr>    
            <th colspan="7" style="margin-bottom:10px;font-size:20px;border:none;text-align:left;"  id='report_name'><b>Exdeal Summary</b></th>
         </tr>
            <tr>    
            <th colspan="7" style="margin-bottom:10px;font-size:20px;border:none;text-align:left;"  id='report_name'>Period of <b><?php echo $from_date." to ".$to_date ?></b></th>
         </tr>
      
        <tr style="margin-top:20px;">
            <th style="width:180px;font-size:20px;">Client Name</th>
            <th style="width:180px;font-size:20px;">Contract #</th>
            <th style="width:150px;font-size:20px;">Exdeal Amount</th>
            <th style="width:150px;font-size:20px;">Cash Ratio</th>
            <th style="width:150px;font-size:20px;">Barter Ratio</th>
          <!--  <th style="width:150px;font-size:20px;">Balance</th>          -->

           <!-- <th style="width:150px;font-size:20px;">Consumption</th>            -->
        </tr>
    </thead>
    <tbody>

       <?php foreach($result as $sub) { ?> 
      
         <tr style="font-size: 20px;">
                <td style="text-align: center;font-size:15px;"><?php echo $sub->group_name ?></td> 
                <td style="text-align: center;font-size:15px;"><?php echo $sub->contract_no; ?></td>
                <td style="text-align: right;font-size:15px;"><?php echo number_format($sub->exdeal_contract_amount,2,'.',',') ?></td> 
                <td style="text-align: center;font-size:15px;"><?php echo $sub->cash_ratio ?>%</td> 
                <td style="text-align: center;font-size:15px;"><?php echo $sub->barter_ratio ?>%</td> 
                <td style="text-align: right;font-size:15px;"><?php echo number_format($sub->balance ,2,'.',',')?></td> 

         </tr>
         
        <?php } ?>    


   </tbody>
  </table>
 </page>

