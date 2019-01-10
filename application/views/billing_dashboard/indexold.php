<div class="workplace"> 
    <div class="row-fluid">
        <div class="span4">                                        
            
            <div class="wBlock">
                <div class="wSpace" style="display: block;">      
                    <div style="height: 200px;color:black" id="newbooking">
                        <?php echo $newbooking ?>    
                    </div>
                </div>
                <div id="newbookingreal">
                    <div class="dSpace">
                        <h3>Total Booking</h3>
                        <span class="number"><?php echo$totalbooking['totalbook'] ?></span>                    
                        <span><?php echo $totalbookingenu[0]['totalbook'] ?> <b>Display</b></span>
                        <span><?php echo $totalbookingenu[1]['totalbook'] ?> <b>Classified</b></span>
                        <span><?php echo $totalbookingenu[2]['totalbook'] ?> <b>Superced</b></span>
                        <span><?php echo $totalbookingenu[3]['totalbook'] ?> <b>Killed</b></span>
                    </div>
                    <div class="rSpace">
                        <h3>Today</h3>
                        <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
                        <span>&nbsp;</span>
                        <span><?php echo $totalbookingenutoday[0]['totalbook'] ?> <b>Display Book</b></span>
                        <span><?php echo $totalbookingenutoday[1]['totalbook'] ?> <b>Classified Book </b></span>
                        <span><?php echo $totalbookingenutoday[2]['totalbook'] ?> <b>Superced Book </b></span>
                        <span><?php echo $totalbookingenutoday[3]['totalbook'] ?> <b>Killed Book </b></span>
                    </div>
                </div>
            </div>                    
            
        </div>
        <div class="span4">   
            
            <div class="wBlock gray">
                <div class="dSpace">
                    <h3>Invoices</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--3,1,2,5,9,4,1,5,11,4,5--></span>
                    <span class="number">19,350</span>                                                
                </div>
                <div class="rSpace">
                    <span>981 <b>delivered</b></span>
                    <span>4 <b>in process</b></span>
                    <span>19 <b>returned</b></span>
                </div>
                <div class="clear"></div>
                <div class="dSpace">
                    <h3>Users</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--5,10,15,20,23,21,25,20,15,10,25,20,10--></span>
                    <span class="number">2,513</span>                    
                </div>
                <div class="rSpace">
                    <span>351 <b>active</b></span>
                    <span>2102 <b>passive</b></span>
                    <span>100 <b>removed</b></span>
                </div>                          
            </div>                    
            <div class="clear"></div>
            
        </div>   
        <div class="span4">
            
            <div class="wBlock red">                        
                <div class="dSpace">
                    <h3>Invoices statistics</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--130,190,260,230,290,400,340,360,390--></span>
                    <span class="number">60%</span>                    
                </div>
                <div class="rSpace">
                    <span>$1,530 <b>amount paid</b></span>
                    <span>$2,102 <b>in queue</b></span>
                    <span>$11,100 <b>total taxes</b></span>
                </div>                          
            </div>                        

            <div class="wBlock green">                        
                <div class="dSpace">
                    <h3>Tickets</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--28,25,40,32,24,34,26,26,41,36--></span>
                    <span class="number">131</span>                    
                </div>
                <div class="rSpace">
                    <span>15 <b>in process</b></span>
                    <span>99 <b>in queue</b></span>
                    <span>16 <b>total</b></span>
                </div>                          
            </div>                                                                                                     
            
            <div class="clear"></div>                    
        </div>
    </div> 
    
    <div class="row-fluid">
        <div class="span12" id="bookingpaytype">

            <div class="wBlock red auto">
                <div class="dSpace">
                    <h3>Billable AD</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--2,5,3,5,6,7,9,8,5,6,2,5--></span>
                    <span class="number"><?php echo $bookpaytype[0]['totalbook'] ?></span>                                                                                                       
                </div>
            </div>                    
            
            <div class="wBlock green auto">
                <div class="dSpace">
                    <h3>PTF</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--5,10,15,20,23,21,25,20,15,10,25,20,10--></span>
                    <span class="number"><?php echo $bookpaytype[1]['totalbook'] ?></span>                                                                                                  
                </div>
            </div>                    
            
            <div class="wBlock auto">
                <div class="dSpace">
                    <h3>CASH</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--3,1,2,5,9,4,1,5,11,4,5--></span>
                    <span class="number"><?php echo $bookpaytype[2]['totalbook'] ?></span>                                                                                              
                </div>
            </div>    
            
            <div class="wBlock yellow auto">
                <div class="dSpace">
                    <h3>CHECK</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--130,190,260,230,290,400,340,360,390--></span>
                    <span class="number"><?php echo $bookpaytype[3]['totalbook'] ?></span>                                                                                                  
                </div>
            </div>                        
            
            <div class="wBlock blue auto">
                <div class="dSpace">
                    <h3>CREDIT CARD</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--3,1,2,5,9,4,1,5,11,4,5--></span>
                    <span class="number"><?php echo $bookpaytype[4]['totalbook'] ?></span>                                                                                               
                </div>
            </div>  
            
            <div class="wBlock blue auto">
                <div class="dSpace">
                    <h3>NO CHARGE</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--3,1,2,5,9,4,1,5,11,4,5--></span>
                    <span class="number"><?php echo $bookpaytype[5]['totalbook'] ?></span>                                                                                                   
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

                $('#newbooking').html($response['newbooking']);    
                $('#newbookingreal').html($response['newbookingreal']);    
                $('#bookingpaytype').html($response['bookingpaytype']);    
            }
        });
         //calling the anonymous function after 10000 milli seconds
        setTimeout(request, 10000);  //second   180000
    })(); //self Executing anonymous function
});

</script>