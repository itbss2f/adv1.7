<style>                    
    h1.title {
        color: black;
        font-family: times;
        font-size: 16pt; 
        font-weight: bold; 
        text-align: center;                        
    }
    
    h1.title2 {
        color: black;
        font-family: times;
        font-size: 11pt; 
        font-weight: bold; 
        text-align: center;    
        
    }
                        
</style>
<h1 class="title"><?php echo $parameter['com_name'] ?></h1>
<h1 class="title"><u>ADVERTISING</u></h1>
<h1 class="title2"><u>REQUEST FOR ADJUSTMENT</u></h1>
<br />
<table cellpadding="1" cellspacing="1" style="font-size:11pt">
 <tr>
    <td width="380" align="right"><b> Date :</b></td>    
    <td width="200" align="left"><b><?php echo date('F d, Y', strtotime(@$print['ao_rfa_date'])) ?></b></td>    
 </tr>
</table>

<br></br>
<table cellpadding="1" cellspacing="1" style="font-size:11pt">
 <tr>
  <td width="80" align="left"><b>Advertiser</b></td>
  <td width="15" align="left"><b>:</b></td>
  <td width="280" align="left"><?php echo @$print['ao_payee'] ?></td>
  <td width="15" align="left"><b></b></td>
  <td width="100" align="left"><b></b></td>                  
 </tr>
 <tr>
  <td width="80" align="left"><b>Agency</b></td>
  <td width="15" align="left"><b>:</b></td>
  <td width="280" align="left"><?php echo @$print['agency'] ?></td>
  <td width="15" align="left"><b></b></td>
  <td width="100" align="left"><b></b></td>                  
 </tr>
 <tr>
  <td width="80" align="left"><b>A.E</b></td>
  <td width="15" align="left"><b>:</b></td>
  <td width="280" align="left"><?php echo @$print['ae'] ?></td>
  <td width="15" align="left"><b></b></td>
  <td width="100" align="left"><b></b></td>                  
 </tr>    
 <tr>
  <td width="80" align="left"><b>Invoice No.</b></td>
  <td width="15" align="left"><b>:</b></td>
  <td width="280" align="left"><?php echo @$print['ao_sinum'] ?></td>
  <td width="15" align="left"><b></b></td>
  <td width="100" align="left"><b></b></td>                  
 </tr>        
 <tr>
  <td width="80" align="left"><b>Issue Date</b></td>
  <td width="15" align="left"><b>:</b></td>
  <td width="230" align="left"><?php echo @$print['ao_issuefrom'] ?></td>
  <td width="250" align="center"><u><b>Amount</b></u></td>                  
 </tr>
 <tr>
  <td width="80" align="left"><b>Type of Ad</b></td>
  <td width="15" align="left"><b>:</b></td>
  <td width="250" align="left"><?php echo @$print['ao_sinum'] ?></td>                  
  <td width="100" align="left"><b>Per Invoice</b></td>                  
  <td width="15" align="left"><b>:</b></td>
  <td width="90" align="right"><?php echo number_format(@$print['perinvoice'], 2) ?></td>
 </tr>    
 <tr>
  <td width="80" align="left"><b>Size of Ad</b></td>
  <td width="15" align="left"><b>:</b></td>
  <td width="250" align="left"><?php echo @$print['ao_width'] ?> x <?php echo @$print['ao_length'] ?></td>                  
  <td width="100" align="left"><b>Adjusted</b></td>                  
  <td width="15" align="left"><b>:</b></td>
  <td width="90" align="right"><u><?php echo str_pad(number_format(@$print['adjusted'], 2),10, "&nbsp;", STR_PAD_LEFT) ?></u></td>
 </tr>    
 <tr>
  <td width="80" align="left"><b>Cost of Ad</b></td>
  <td width="15" align="left"><b>:</b></td>
  <td width="250" align="left"><?php echo @$print['ao_sinum'] ?></td>                  
  <td width="100" align="left"><b>Difference</b></td>    
  <td width="15" align="left"><b>:</b></td>        
  <td width="90" align="right"><u><?php echo str_pad(number_format(@$print['perinvoice'] - @$print['adjusted'], 2),10, "&nbsp;", STR_PAD_LEFT) ?></u></td>                  
 </tr>
</table>  
<br>
<table style="font-size:11pt">
 <tr>
    <td width="600" align="left"><b>Findings/Nature of complaint :</b> <?php echo @$print['ao_rfa_findings'] ?><br><br></td>
 </tr>
 <tr>
    <td width="600" align="left"><b>Possible Adjustments :</b> <?php echo @$print['ao_rfa_adjustment'] ?><br><br></td>
 </tr>
 <tr>
    <td width="600" align="left"><b>Person/Agency/Client Responsible :</b> <?php echo @$print['ao_rfa_reason'] ?><br></td>
 </tr>
 <tr>
    <td width="600" align="left">Checked by  : ____________________________</td>
 </tr>
 <tr>
    <td width="20"></td>
    <td width="300" align="center"><?php echo @$parameter['rfa_checkedby'] ?><br></td>
 </tr>
 <tr>
    <td width="600" align="left">Verified by &nbsp;&nbsp;: ____________________________</td>
 </tr>
 <tr>
    <td width="20"></td>
    <td width="300"  align="center"><?php echo @$parameter['rfa_verifiedby'] ?><br></td>
 </tr>
</table>
<table style="font-size:11pt">
 <tr>
  <td width="280" align="left">Approved by:<br><br></td>
  <td width="200" align="center">Noted by:<br><br></td>                           
 </tr>    
 <tr>
  <td width="320" align="left"><u><?php echo @$parameter['rfa_approver1_name'] ?></u></td>
  <td width="300" align="left"><u><?php echo @$parameter['rfa_approver3_name'] ?></u></td>                           
 </tr>        
 <tr>
  <td width="320" align="left">&nbsp;&nbsp;<?php echo @$parameter['rfa_approver1_title'] ?></td>
  <td width="300" align="left">&nbsp;&nbsp;<?php echo @$parameter['rfa_approver3_title'] ?><br><br></td>                           
 </tr>    
 <tr>
  <td width="320" align="left"><u><?php echo @$parameter['rfa_approver2_name'] ?></u></td>                  
 </tr>    
 <tr>
  <td width="300" align="left"><?php echo @$parameter['rfa_approver2_title'] ?><br><br><br></td>                  
 </tr>        
 <tr>
  <td width="50" align="left">Cc:</td>
  <td width="100" align="left">C & C</td>
 </tr>
 <tr>
  <td width="50" align="left"></td>
  <td width="100" align="left">Billing</td>
 </tr>
 <tr>
  <td width="50" align="left"></td>
  <td width="400" align="left">VP-Advertising</td>
  <td width="150" align="left"><b>R.F.A. No. <u><?php echo @$print['ao_rfa_num'] ?></u></b></td>
 </tr>
</table>                                                