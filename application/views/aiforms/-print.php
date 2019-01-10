<!DOCTYPE html >
<html>
<head>
<style>
body {
    font-size: 13px;
    font-family: "Courier New",Georgia,Serif;
    letter-spacing: 3px;
}
p {
    margin: 5px;
    height: 13px
}

#datehandler {
    font-size: 20px;
    height: 40px;
    margin-top: -13px;    
    width: 100%;
}

#payeehandle {
    float: left;
    width: 100%;
    margin-top: -8px;
}

#paymentdetail {
    float: left;     
    width: 100%;
    margin-top: 25px;
}

#paymentremarks {
    float: left;
    width: 60%;    
}

#paymentremarks p {
    text-indent: 40px;
}

#paymenttype {
    float: left;
    width: 39%;        
}

#paymentbank {
    margin-top: 16px;
    height: 75px;
    width: 98%;
}

#payeemain {
    float: left;
    width: 38%;
    margin-left: 30px;
    
}

#payeemain p {
    text-indent: 90px;
    padding: 0px;
    height: 12px;
}

#payeetin {
    float: left;
    width: 18%;
    text-indent: -70px;  
    
}

#payeedetail {
    float: left;
    width: 40%;  
    text-indent: -65px;  
}  
#paymentbilling {
    float: left;
    width: 100%;      
}

#billname {
    float: left;
    width: 65%;          
}

#billamount {
    float: left;
    width: 35%;          
}

#billamount p {
    text-align: right;
    margin-right: 30px;
}  
#paymentfooter1, #paymentfooter2 {
    float: left;
    width: 100%;              
}

#datehandler table tr td {
    height: 10px;
    padding-bottom: 0px;
}

#invoicedata {
    float: left;
    width: 95%;
    height: 270px;
}

</style>
<script>
function printContent(el){
    var restorepage = document.body.innerHTML;
    var printcontent = document.getElementById(el).innerHTML;
    document.body.innerHTML = printcontent;
    window.print();
    document.body.innerHTML = printcontent;
}
</script>
</head>
<body>
<button onclick="printContent('printdata')">Print</button>        
<div id="printdata">

    <div style="height: 680px; width: 1280px;">
    
    
          <div id='datehandler'>
            <table align="right" style="margin-right: 130px; font-size: 12px;">
                <tr>
                    <td style="width: 40px; text-align: right;">No:</td>
                    <td style="width: 50px; font-size: 15px;"><b><?php echo $main['ao_sinum'] ?></b></td>
                </tr>
                <tr>
                    <td style="width: 40px;">Date:</td>
                    <td style="width: 50px;"><?php echo $main['invdate'] ?></td>
                </tr>
            </table>
          </div>
          
          <div id='payeehandle'>
            <div id='payeemain'>
                <p><?php echo $main['ao_payee'] ?> </p>
                <p><?php echo $main['ao_add1'] ?> </p>
                <p><?php echo $main['ao_add2'] ?> </p>
                <p><?php echo $main['ao_add3'] ?> </p>
                <p><?php echo strtoupper($main['aename'] )?> </p>
                <p><?php echo $main['ao_billing_remarks']?> </p>
            </div> 
            <div id='payeetin'>
                <p></p>
                <p></p>
                <p></p>
                <p><?php if ($main['tin'] != '') : ?>TIN: <?php echo $main['tin']?> <?php endif; ?></p>
                <p><?php echo $main['adtype_name'].'-'.$main['branch_code']?> </p>

            </div>
            <div id='payeedetail'>
                <p><?php echo $main['cmf_name'] ?> </p>
                <p><?php echo $main['cmf_add1'] ?> </p>
                <p><?php echo $main['cmf_add2'] ?> </p>
                <p><?php echo $main['cmf_add3'] ?> </p>
                <p style="text-indent: 10px;"><?php echo $main['ao_ref'] ?> </p>
            </div>
          </div>
          
          <div id="invoicedata">
            <table style="margin-top: 55px; margin-left: 10px;">
                <?php
                 $vatrate = 0; $vatsales = 0; $vatexempt = 0; $vatzerorated = 0; $vatamt = 0; $totalamt = 0;
                 foreach ($detail as $row) : 
                 $vatrate = $row['vatrate'];
                 $vatsales += $row['ao_vatsales']; $vatexempt += $row['ao_vatexempt']; $vatzerorated += $row['ao_vatzero']; $vatamt += $row['ao_vatamt'];  $totalamt += $row['ao_amt'];                            
                 ?>
                <tr>
                    <td width="08%"><?php echo $row['rundate'] ?></td>
                    <td width="41%" style="text-indent: 10px;"><?php echo $row['ao_billing_prodtitle'] ?></td>
                    <td width="13%" style="text-align: right;"><?php echo $row['size'] ?></td>
                    <td width="07%" style="text-align: right;"><?php echo $row['ao_totalsize'] ?></td>
                    <td width="06%" style="text-align: right;"><?php echo $row['baserate'] ?></td>
                    <td width="07%" style="text-align: center;"><?php echo $row['ao_surchargepercent'] ?></td>
                    <td width="07%" style="text-align: center;"><?php echo $row['ao_discpercent'] ?></td>
                    <td width="20%" style="text-align: right;"><?php echo number_format($row['ao_grossamt'], 2, '.', ',') ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
          </div>
          
          <div id='paymentdetail'>
          
              <div id='paymentremarks'>
                <p style="font-size: 11px;"><?php if (@$ppd['stat'] != 0) : ?>NOTE: PPD Payment should be made withind 30 days from receipt of invoice. <?php endif; ?></p>
                <p align="center"><img src="<?php echo base_url() ?>assets/images/lcayubit.bmp" style="width: 180px; height: 50px; margin-top: -8px;"></p>
                <p align="center">LIMWELL M. CAYUBIT</p>
                <p align="center">BILLING SUPERVISOR</p>
              </div>
              
              <div id='paymenttype' style="font-size: 15px;">
                <div id='paymentbilling'>
                    <div id='billname'>
                        <?php if ($main['ao_amf'] != 0) : ?>
                        <p>NET OF ASF </p>
                        <?php else: ?>
                        <p>VATABLE SALES </p> 
                        <?php endif; ?>
                        <p>PLUS: <?php if ($vatrate != 0) echo $vatrate.'%' ?>  VAT </p> 
                        <?php if ($main['ao_cmfvatcode'] == 4) : ?>
                        <p>VAT EXEMPT </p> 
                        <?php else: ?>
                        <p>VAT ZERO RATED </p> 
                        <?php endif; ?>
                        <p>TOTAL AMOUNT DUE</p> 
                    </div>
                    <div id='billamount'>
                        <?php if ($main['ao_cmfvatcode'] == 4 || $main['ao_cmfvatcode'] == 5) : ?>
                        <p>- o -</p>            
                        <p>- o -</p>            
                        <p><?php if ($main['ao_cmfvatcode'] == 4) { echo number_format($vatexempt, 2, '.', ','); } else { echo number_format($vatzerorated, 2, '.', ','); } ?></p>     
                        <p style="border-top: 2px solid #000;"><?php if ($main['ao_cmfvatcode'] == 4) { echo number_format($vatexempt, 2, '.', ','); } else { echo number_format($vatzerorated, 2, '.', ','); } ?></p> 
                        <?php else : ?>
                        <p><?php echo number_format($vatsales, 2, '.', ',') ?></p>
                        <p><?php echo number_format($vatamt, 2, '.', ',') ?></p> 
                        <p>- o -</p> 
                        <p style="border-top: 2px solid #000;"><?php echo number_format($totalamt, 2, '.', ',') ?></p> 
                        <?php endif; ?>
                    </div>
                </div>
              </div>
              <div id='paymentfooter1' style="margin-top:20px"> 
                <p style="text-align: right; margin-right: 60px;"><b><?php if (@$ppd['stat'] != 0) : $ppdamt = ($totalamt  - ($totalamt * ($ppd['stat'] / 100)));?>AMOUNT DUE IF W/IN 30 DAYS (<?php echo $ppd['stat'] ?>%) <?php echo number_format($ppdamt, 2, '.', ','); ?></b><?php endif; ?></p> 
              </div>
              <div id='paymentfooter2' style="margin-top: 50px;">
                <p style="text-align: center; font-size: 8px;"><?php echo $main['ao_sinum'] ?></p>
              </div>
             
          </div>

    </div>

</div>


</body>
</html>
