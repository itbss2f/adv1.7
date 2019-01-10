<style>

 .theads > th {
      white-space:nowrap;
      border-top: 2px solid #000;
      border-bottom: 2px solid #000;
  }
  
  tr.sub_agency > td
 {
  padding-bottom: 3px;
  padding-top: 3px;
 }  


    @media print 
    {
        
       .header, .menu,.head, h4,.row-form-booking,.block-fluid
       {
         display:none;  
       }
       
       .theads > th 
       {
         white-space:nowrap;
         border-top: none;
         border-bottom: none;  
         font-size:12px;
         padding:10px;
       }
  
       #report_space
       {
          border:none;
       }
       
       #report_table
       {
         width:500%;
         position:absolute;
         left:-200px;
         top:-30px;
       } 
       
       #header1
       {
          font-size:15px;   
       } 
       #header2
       {
          font-size:12px;   
       }
       
        #footer
       {
          font-size:12px;   
       }      
      
    }

</style>
<table style="width:100%;background-color: white;" id="report_table">
    <thead>
        <tr>
           <th colspan="11" style="text-align: left;" id="header1"><b>PHILIPPINE DAILY INQUIRER, INC.</b></td>
        </tr>
        <tr>      
           <th colspan="11" style="text-align: left;" id="header2"><b>SUMMARY OF INVOICES - PROMPT PAYMENT DISCOUNT</b></td>
        </tr>
        <tr>   
           <th colspan="11" style="text-align: left;" id="header3"><b>From <?php echo $from_date ?> To <?php echo $to_date ?></b></td>
        </tr>
        <tr class="theads">
            <th >CM Number</th>
            <th>Client</th>
            <th>Invoice No.</th>
            <th>Invoice Date</th>
            <th>Received Date</th>
            <th>Invoice Amount</th>
            <th>PPD Amount</th>
            <th style="width:30px">%</th>
            <th>Applied Amount</th>
            <th>OR Number</th>
            <th>OR Date</th>
            <th>No. Of Days</th>
        </tr>
    </thead>
    <tbody>
   <?php $agency = ""; ?>
   <?php $client = ""; ?>
   <?php $subtotal_client_invoice_amount = 0; ?>
   <?php $subtotal_client_ppd_amount = 0; ?>
   <?php $subtotal_client_applied_amount = 0; ?>
   
   <?php $subtotal_agency_invoice_amount = 0; ?>
   <?php $subtotal_agency_ppd_amount = 0; ?>
   <?php $subtotal_agency_applied_amount = 0; ?>
   
   <?php $total_invoice_amount = 0; ?>
   <?php $total_ppd_amount = 0; ?>
   <?php $total_applied_amount = 0; ?>
   
   <?php $subtotal_agency = ""; ?>
         <?php for($ctr=0;$ctr<count($result);$ctr++) { ?> 
          <?php if($agency != $result[$ctr]['agency_name']) { ?>
                 <tr>
                    <td colspan="11"><b><?php echo $result[$ctr]['agency_name'] ?></b></td>
                 </tr>
          <?php } ?>
                   <tr>
                        <td style="text-align: center;">___________</td>
                        <td style="white-space: nowrap;"><?php echo $result[$ctr]['client_name'] ?></td>
                        <td style="text-align: center;"><?php echo $result[$ctr]['ao_sinum'] ?></td>
                        <td style="text-align: center;"><?php echo $result[$ctr]['ao_sidate'] ?></td>
                        <td style="text-align: center;"><?php echo $result[$ctr]['ao_receive_date'] ?></td>
                        <td style="text-align: right;"><?php echo number_format($result[$ctr]['invoice_amount'],2,'.',',') ?></td>
                        <td style="text-align: right;"><?php echo number_format($result[$ctr]['ppd_amount'],2,'.',',') ?></td>
                        <td style="text-align: center;"><?php echo number_format($result[$ctr]['acmf_ppd'],0,'','') ?>%</td>    
                        <td style="text-align: right;"><?php echo number_format($result[$ctr]['applied_amount'],2,'.',',') ?></td>
                        <td style="text-align: center;"><?php echo $result[$ctr]['ao_ornum'] ?></td>
                        <td style="text-align: center;"><?php echo $result[$ctr]['ao_ordate'] ?></td>
                        <td style="text-align: center;"><?php echo $result[$ctr]['date_diff'] ?></td>
                   </tr>
              <?php $subtotal_client_invoice_amount += $result[$ctr]['invoice_amount'];  ?>
              <?php $subtotal_client_ppd_amount += $result[$ctr]['ppd_amount'];  ?>
              <?php $subtotal_client_applied_amount += $result[$ctr]['applied_amount'];  ?>
              
              <?php $subtotal_agency_invoice_amount += $result[$ctr]['invoice_amount']; ?>
              <?php $subtotal_agency_ppd_amount += $result[$ctr]['ppd_amount']; ?>
              <?php $subtotal_agency_applied_amount += $result[$ctr]['applied_amount']; ?>
              
              <?php $total_invoice_amount += $result[$ctr]['invoice_amount']; ?>
              <?php $total_ppd_amount += $result[$ctr]['ppd_amount'];; ?>
              <?php $total_applied_amount += $result[$ctr]['applied_amount']; ?>
              
              <?php if(isset($result[$ctr+1]['client_name']) AND ($result[$ctr+1]['client_name'] != $result[$ctr]['client_name']) ) { ?>
                 <tr>
                    <td></td>    
                    <td colspan="4"> Sub Total - <b><?php echo $result[$ctr]['client_name'] ?></b></td>
                    <td style="text-align: right;border-top:1.5px solid #000;border-bottom:1px solid #000;"><?php echo number_format($subtotal_client_invoice_amount,2,'.',',') ?></td>
                    <td style="text-align: right;border-top:1.5px solid #000;border-bottom:1px solid #000;"><?php echo number_format($subtotal_client_ppd_amount,2,'.',',') ?></td>
                    <td></td>
                    <td style="text-align: right;border-top:1.5px solid #000;border-bottom:1px solid #000;"><?php echo number_format($subtotal_client_applied_amount,2,'.',',') ?></td>
                    <?php $subtotal_client_invoice_amount = 0; ?>
                    <?php $subtotal_client_ppd_amount = 0; ?>
                    <?php $subtotal_client_applied_amount = 0; ?>
                 </tr>
              <?php } ?>  
                <?php if(isset($result[$ctr+1]['agency_name']) AND ($result[$ctr+1]['agency_name'] != $result[$ctr]['agency_name']) ) { ?>
                 <tr class="sub_agency" >
                    <td style="margin-top:30px;padding-bottom:30px;"></td>    
                    <td colspan="4"> Total - <b><?php echo $result[$ctr]['agency_name'] ?></b></td>
                    <td style="text-align: right;border-top:2px solid #000;border-bottom:1px solid #000;"><?php echo number_format($subtotal_agency_invoice_amount,2,'.',',') ?></td>
                    <td style="text-align: right;border-top:2px solid #000;border-bottom:1px solid #000;"><?php echo number_format($subtotal_agency_ppd_amount,2,'.',',') ?></td>
                    <td></td>
                    <td style="text-align: right;border-top:2px solid #000;border-bottom:1px solid #000;"><?php echo number_format($subtotal_agency_applied_amount,2,'.',',') ?></td>
                    <?php $subtotal_agency_invoice_amount = 0; ?>
                    <?php $subtotal_agency_ppd_amount = 0; ?>
                    <?php $subtotal_agency_applied_amount = 0; ?>
                 </tr>
              <?php } ?>        
              <?php $agency = $result[$ctr]['agency_name']; ?>
              <?php $client = $result[$ctr]['client_name']; ?>
         <?php } ?>
         
            <tr>
                    <td></td>    
                    <td colspan="4"> Sub Total - <b><?php echo $client ?></b></td>
                    <td style="text-align: right;border-top:1.5px solid #000;border-bottom:1px solid #000;"><?php echo number_format($subtotal_client_invoice_amount,2,'.',',') ?></td>
                    <td style="text-align: right;border-top:1.5px solid #000;border-bottom:1px solid #000;"><?php echo number_format($subtotal_client_ppd_amount,2,'.',',') ?></td>
                    <td></td>
                    <td style="text-align: right;border-top:1.5px solid #000;border-bottom:1px solid #000;"><?php echo number_format($subtotal_client_applied_amount,2,'.',',') ?></td>
                  </tr>
         
           <tr class="sub_agency" >
                    <td style="margin-top:30px;padding-bottom:30px;"></td>    
                    <td colspan="4"> Total - <b><?php echo $agency ?></b></td>
                    <td style="text-align: right;border-top:2px solid #000;border-bottom:1px solid #000;"><?php echo number_format($subtotal_agency_invoice_amount,2,'.',',') ?></td>
                    <td style="text-align: right;border-top:2px solid #000;border-bottom:1px solid #000;"><?php echo number_format($subtotal_agency_ppd_amount,2,'.',',') ?></td>
                    <td></td>
                    <td style="text-align: right;border-top:2px solid #000;border-bottom:1px solid #000;"><?php echo number_format($subtotal_agency_applied_amount,2,'.',',') ?></td>
                   </tr>
                 
                  <tr class="sub_agency" >
                    <td style="margin-top:30px;padding-bottom:30px;"></td>    
                    <td colspan="4"> Grand Total </b></td>
                    <td style="text-align: right;border-top:2px solid #000;border-bottom:1px solid #000;"><?php echo number_format($total_invoice_amount,2,'.',',') ?></td>
                    <td style="text-align: right;border-top:2px solid #000;border-bottom:1px solid #000;"><?php echo number_format($total_ppd_amount,2,'.',',') ?></td>
                    <td></td>
                    <td style="text-align: right;border-top:2px solid #000;border-bottom:1px solid #000;"><?php echo number_format($total_applied_amount,2,'.',',') ?></td>
                  </tr>  
              
          <tr  >
            <td  style="padding-top: 150px;white-space: nowrap;" colspan="3">Prepared By : ____________________________________</td>
            <td  style="padding-top: 150px;padding-left:20px;white-space: nowrap;" colspan="3">Noted By : ____________________________________</td>
            <td  style="padding-top: 150px;white-space: nowrap;" colspan="3">Approved By : _____________________________________</td>
          </tr>
          <tr>
            <td colspan="3"> <b style="margin-left:90px"><?php echo ucfirst($this->session->userdata('authsess')->sess_fullname);?></b></td>
            <td colspan="3"> <b style="margin-left:90px">Jaime C. Ramos</b></td>
            <td colspan="3"> <b style="margin-left:90px">Atty. Victor S. Leal</b></td>
          </tr>
    </tbody>
</table>



