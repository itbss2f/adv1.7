<!DOCTYPE html >
<html>
<head>
<style>
body {
    font-size: 13px;
    font-family: "Courier New",Georgia,Serif;
    letter-spacing: 2px;
}
p {
    margin: 5px;
    height: 13px
}

#datehandler {
    font-size: 20px;
    height: 40px;
    margin-top: 33px;    
    width: 100%;
}

#payeehandle {
    float: left;
    width: 100%;
    margin-top: -7px;
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
    width: 70%;
}

#payeemain p {
    text-indent: 70px;
}
#payeedetail {
    float: left;
    width: 29%;  
    text-indent: 50px;  
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
#paymentfooter1, paymentfooter2 {
    float: left;
    width: 100%;              
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

    <div style="height: 480px; width: 1280px;">
    
    
          <div id='datehandler'><p style="text-align: right; margin-right: 160px;"><b><?php echo $data['ordate'] ?></b></p></div>
          
          <div id='payeehandle'>
            <div id='payeemain'>
                <p><?php echo $data['payee'] ?> </p>
                <p style="text-indent: 50px;"><?php echo substr($data['address'], 0, 50) ?> </p>
                <p><?php echo $data['or_amtword'] ?> </p>
            </div>
            <div id='payeedetail'>
                <p><?php echo $data['or_tin'] ?> </p>
                <p style="text-indent: 115px;"></p>
            </div>
          </div>
          
          <div id='paymentdetail'>
          
              <div id='paymentremarks'>
                <p><?php echo $data['or_part'] ?> </p>
              </div>
              
              <div id='paymenttype'>
                <div id='paymentbank'>
                
                    <table width="97%">
                        <?php foreach ($pdata as $pdata) : ?>
                        <tr>
                            <td><?php echo $pdata['typename'] ?></td>
                            <td><?php echo $pdata['cno'] ?></td>
                            <td style="text-align: right; margin-right: 20px;"><?php echo number_format($pdata['or_amt'], 2, '.',',') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    
                </div>
                <div id='paymentbilling'>
                    <div id='billname'>
                        <p>Vatable Sales: </p>
                        <p>VAT Exempt Sales: </p> 
                        <p>VAT Zero Rated: </p> 
                        <p>Total Sales: </p> 
                        <p>VAT: </p> 
                        <p>Withholding Tax: </p> 
                        <p>Total Amount Due: </p> 
                    </div>
                    <div id='billamount'>
                        <p><?php echo number_format($data['or_vatsales'], 2, '.',',') ?></p>
                        <p><?php echo number_format($data['or_vatexempt'], 2, '.',',') ?></p> 
                        <p><?php echo number_format($data['or_vatzero'], 2, '.',',') ?></p> 
                        <p style="border-top: 2px solid #000;"><?php echo number_format($data['or_grossamt'], 2, '.',',') ?></p> 
                        <p><?php echo number_format($data['or_vatamt'], 2, '.',',') ?></p> 
                        <p>(<?php echo number_format($data['or_wtaxamt'] + $data['or_wvatamt'], 2, '.',',') ?>)</p> 
                        <p style="border-top: 2px solid #000; border-bottom: 3px solid #000;"><?php echo number_format($data['or_amt'], 2, '.',',') ?></p> 
                    </div>
                </div>
              </div>
              <div id='paymentfooter1' style="margin-top:20px"> 
                <p style="text-align: right; margin-right: 160px;"><b><?php echo $data['cashiername'] ?></b></p> 
              </div>
              <div id='paymentfooter2' style="margin-top: 5px;">
                <p style="text-align: center;"><?php echo $data['or_num'] ?></p>
              </div>
          </div>

    </div>

</div>


</body>
</html>
