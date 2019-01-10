<div style="text-align: center;position:relative;top:10px;"><h3>PHILIPPNE DAILY INQUIRER</h3></div>
<div style="text-align: center;position:relative;top:-30px;text-decoration: underline;"><h3>ADVERTISING</h3></div>
<div style="text-align: center;position:relative;top:-20px;text-decoration: underline;"><h4>DISCREPANCY REPORT</h4></div>

<div style="text-align: right;position:relative;top:10px;font-size: 14px;"><b>Date :</b> <?php echo DATE('M d, Y',strtotime($result->ao_cdr_date)) ?></div>  
       
<table cellpadding="5" cellspacing="5" >
     <tr >
        <td style="font-size: 16px;"><b>Advertiser : </b></td>
        <td style="font-size: 14px;"><?php echo $result->client_name ?></td>
     </tr>
     <tr>
        <td style="font-size: 16px;"><b>Agency : </b></td>
        <td style="font-size: 14px;"><?php echo $result->agency_name ?></td>
     </tr>
       <tr>
        <td style="font-size: 16px;"><b>AE : </b></td>
        <td style="font-size: 14px;"><?php echo $result->acct_exec ?></td>
     </tr>
     <tr>
        <td style="font-size: 16px;"><b>RN No : </b></td>
        <td style="font-size: 14px;"><?php echo $result->PO ?></td>
     </tr>
     <tr>
        <td style="font-size: 16px;"><b>Issue Date : </b></td>
        <td style="font-size: 14px;"><?php echo $result->issue_date ?></td>
     </tr>
     <tr >
        <td style="font-size: 16px;"><b>Type of Ad : </b></td>
        <td style="font-size: 14px;"><?php echo $result->adtype_name ?></td>
     </tr>
     <tr>
        <td style="font-size: 16px;"><b>Size of Ad : </b></td>
        <td style="font-size: 14px;"><?php echo $result->size ?></td>
     </tr>
      <tr>
        <td style="font-size: 16px;"><b>Cost of Ad : </b></td>
        <td style="font-size: 14px;"><?php echo number_format($result->ao_amt,2,'.',','); ?></td>
     </tr>
</table>

<div style="margin-top:20px;"><b>Nature of Complaint : </b> <?php echo $result->nature_of_complaint ?></div>

<div style="margin-top:40px;"><b>Findings : </b> <?php echo $result->finding ?></div>

<div style="margin-top:40px;"><b>Person / Agency / Client Responsible : </b> <?php echo $result->responsible ?></div>

<div style="margin-top:50px;">Checked by : __________________________________</div>
<div style="margin-top:5px;margin-left:170px;">Arnel Francisco</div>

<!-- <div style="margin-top:50px;">Verified by : __________________________________</div>
<div style="margin-top:5px;margin-left:170px;">Lita Pascual</div> -->


<div style="margin-top:50px;">Approved by:</div>
<div style="margin-top:30px;text-decoration: underline;">Buenaventura R. Arcano Jr.</div>
<div style="margin-top:5px;">AVP - Advertising</div>


<div style="margin-top:50px;">Cc: VP-Advertising</div>
<div style="margin-top:5px;margin-left:25px;">Audit</div>
<div style="margin-top:5px;margin-left:25px;">Billing</div>


<div style="margin-top:50px;margin-right:50px;text-align: right;"><b>CDR No. <?php echo $result->ao_cdr_num ?></b></div>  