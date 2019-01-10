<div class="workplace"> 

    <div class="row-fluid">
        <div class="span4">                                        
            <div class="wBlock">
                  <div class="wSpace" style="display: block;">   
                    <table cellpadding="0" cellspacing="0" width="100%" class="sOrders" style="font-size: 10px"> 
                        <thead>
                            <tr>
                                <th width="100">Adtype</th>
                                <th width="100">Amount</th>
                            </tr>
                        </thead>   
                    </table>   
                    <div class="block messages scrollBox">
                        <div class="scroll" style="height: 350px;">
                        <table  width="100%" class="sOrders" style="font-size: 10px; color: #000000; padding: 0px; margin: 0px;">
                            <tbody id='salesadtype'>
                                 <?php echo $salesadtype ?>
                            </tbody>       
                        </table>
                        </div>
                    </div>
                </div>      
                <div class="dSpace" style="width: 60%; font-size: 15px;">
                    <h3></h3>
                    <span class="number" style="font-size: 20px"><?php echo number_format($totalsalesadtype, 2, '.', ','); ?></span>                    
                </div>
                <div class="rSpace" style="width: 35%;">
                    <h4 style="font-size: 18px">Sales Adtype Monthly</h4>        
                    <span><b>Date From: <?php echo $datefrom ?></b></span> 
                    <span><b>Date To: <?php echo $dateto ?></b></span> 
                </div>
            </div>                    
        </div>
        <!--  SALES ADTYPE       -->
        <div class="span6">                                        
            <div class="wBlock">
                <div class="wSpace" style="display: block;">   
                    <table cellpadding="0" cellspacing="0" width="100%" class="sOrders" style="font-size: 10px"> 
                        <thead>
                            <tr>
                                <th width="100">Code</th>
                                <th width="150">Advertiser</th>
                                <th width="100">AO Number</th>
                                <th width="100">Amount</th>
                            </tr>
                        </thead>   
                    </table>   
                    <div class="block messages scrollBox">
                        <div class="scroll" style="height: 350px;">
                        <table  width="100%" class="sOrders" style="font-size: 10px; color: #000000; padding: 0px; margin: 0px;">
                            <tbody id='chargeswoinv'>
                                 <?php echo $chargeswoinv ?>
                            </tbody>       
                        </table>
                        </div>
                    </div>
                </div>      
                <div class="dSpace">
                    
                </div>
                <div class="rSpace">
                    <h4 style="font-size: 18px">Charge Without Invoice Monthly</h4>   
                    <span><b>Date From: <?php echo $datefrom ?></b></span> 
                    <span><b>Date To: <?php echo $dateto ?></b></span> 
                </div>
            </div>                    
        </div>
    </div>
    
    <div class="row-fluid">    
        <div class="span4">                                        
            <div class="wBlock">
                  <div class="wSpace" style="display: block;">   
                    <table cellpadding="0" cellspacing="0" width="100%" class="sOrders" style="font-size: 10px"> 
                        <thead>
                            <tr>
                                <th width="100">User</th>
                                <th width="100">Count Book Ads</th>
                                <th width="100">Amount</th>
                            </tr>
                        </thead>   
                    </table>   
                    <div class="block messages scrollBox">
                        <div class="scroll" style="height: 350px;">
                        <table  width="100%" class="sOrders" style="font-size: 10px; color: #000000; padding: 0px; margin: 0px;">
                            <tbody id='userbookingcounter'>
                                 <?php echo $userbookingcounter ?>
                            </tbody>       
                        </table>
                        </div>
                    </div>
                </div>      
                <div class="dSpace">
                    <h3></h3>
                    <span class="number" id="totalcountbook"><?php echo $totalcountbook ?></span>                    
                </div>
                <div class="rSpace">
                    <h4 style="font-size: 18px">Booking Counter Monthly</h4>        
                    <span><b>Date From: <?php echo $datefrom ?></b></span> 
                    <span><b>Date To: <?php echo $dateto ?></b></span> 
                </div>
            </div>                    
        </div>
        <div class="span6">                                        
            <div class="wBlock">
                <div class="wSpace" style="display: block;">   
                    <table cellpadding="0" cellspacing="0" width="100%" class="sOrders" style="font-size: 10px"> 
                        <thead>
                            <tr>
                                <th width="100">Pay Type</th>
                                <th width="100">Issue Date</th>
                                <th width="150">Advertiser</th>
                                <th width="100">AO Number</th>
                                <th width="100">Amount</th>
                                <th width="100">Product</th>
                                <th width="60">Book Type</th>
                            </tr>
                        </thead>   
                    </table>   
                    <div class="block messages scrollBox">
                        <div class="scroll" style="height: 350px;">
                        <table  width="100%" class="sOrders" style="font-size: 10px; color: #000000; padding: 0px; margin: 0px;">
                            <tbody id='unpaginated'>
                                 <?php echo $unpaganate ?>
                            </tbody>       
                        </table>
                        </div>
                    </div>
                </div>      
                <div class="dSpace">
                    <h3>Total Count</h3>
                    <span class="number" id="unpaginatetotalcount"><?php echo $totalcountunpag ?></span>                    
                </div>
                <div class="rSpace">
                    <h4 style="font-size: 18px">Unpaginate Booking Monthly</h4>   
                    <span><b>Date From: <?php echo $datefrom ?></b></span> 
                    <span><b>Date To: <?php echo $dateto ?></b></span> 
                </div>
            </div>                    
        </div>  
    </div>   
</div>
<script>
$(function () {
    
    (function request() {
        $.ajax({
            url: '<?php echo site_url('billing_dash/realTimeRetrieve') ?>',
            type: 'post',
            data: {},
            success:function(response) {   
                
                var $response = $.parseJSON(response);

                $('#salesadtype').html($response['salesadtype']);    
                $('#chargeswoinv').html($response['chargeswoinv']);    
                $('#unpaginated').html($response['unpaganate']);    
                $('#chargewoinvtotalcount').html($response['chargewoinvtotalcount']);    
                $('#unpaginatetotalcount').html($response['totalcountunpag']);    
                //$('#totalcountbook').html($response['totalcountbook']);    
                $('#userbookingcounter').html($response['userbookingcounter']);    
                /* $('#newbookingreal').html($response['newbookingreal']);    
                $('#bookingpaytype').html($response['bookingpaytype']);          */
            }
        });
         //calling the anonymous function after 10000 milli seconds
        setTimeout(request, 10000);  //second   10000
    })(); //self Executing anonymous function
});

</script>