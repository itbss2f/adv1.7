

<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>


<div class="workplace">    
     
        <div class="row-fluid">    
        <div class="input-append" style="margin-left:30px;">
        <input id="datefrom" type="text" style="width: 118px;" class="datepicker" value="<?php echo date('Y-m-01') ?>">
        <button class="btn" type="button" style="width: 118px;">Date From</button>  
        <input id="dateto" type="text" style="width: 118px;" class="datepicker" value="<?php echo date('Y-m-d') ?>">
        <button class="btn" type="button" style="width: 118px;margin-right: 50px;">Date To</button>
        <button class="btn  btn-success" style="width: 118px;" type="button" id="retrieve">Retrieve Data</button>       
        </div>
            <div class="input-append">   
        </div>
        <div class="clear"></div>  
    </div>   
        <div class="span3">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Sales Per A.E & BM</h1>                
                <div class="clear"></div>
            </div> 
            <div class="block news scrollBox">
            <table class="sOrders" width="100%" cellspacing="0" cellpadding="0" style="margin-left: -5px; margin-top: -10px;">
                <thead>
                    <th>User</th>
                    <th style="text-align:right;">Amount</th>
                </thead>
            </table>
                <div class="scroll" style="height: 270px;" >
                     
                    <table class="sOrders" width="100%" cellspacing="0" cellpadding="0">   
                        <tbody id="salesaebm">
                            <?php echo $salesaebmview ?>      
                        </tbody>
                    </table>
                    
                </div>
                
            </div>
        </div>   
        <div class="span4">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Booking Counter (Classified Only)</h1>                
                <div class="clear"></div>
            </div> 
            <div class="block news scrollBox">
               <table class="sOrders" width="100%" cellspacing="0" cellpadding="0" style="margin-left: -5px; margin-top: -10px;">
                <thead>
                    <th>User</th>
                    <th style="text-align:right;">Book Ads</th>
                    <th style="text-align:right;">Amount</th>
                </thead>
            </table>
                <div class="scroll" style="height: 270px;"> 
                    <table class="sOrders" width="100%" cellspacing="0" cellpadding="0">   
                        <tbody id="bookingcounterclass">
                            <?php echo $BookingCounterview ?>       
                        </tbody>
                    </table>
                    
                </div>
                
            </div>
        </div>      
        <div class="span3">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Supplement (Display)</h1>                
                <div class="clear"></div>
            </div> 
            <div class="block news scrollBox">
            <table class="sOrders" width="100%" cellspacing="0" cellpadding="0" style="margin-left: -5px; margin-top: -10px;">
                <thead>
                    <th>Supplement type</th>
                    <th style="text-align:right;">Total Amount</th>
                </thead>
            </table>
                <div class="scroll" style="height: 270px;">
                     
                    <table class="sOrders" width="100%" cellspacing="0" cellpadding="0">   
                        <tbody id="supplement">
                            <?php echo $supplementview ?>      
                        </tbody>
                    </table>
                    
                </div>
                
            </div>
        </div>                                                        
    <div class="dr"><span></span></div>    
    
    <div class="span4">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Sales Per Branch</h1>                
                <div class="clear"></div>
            </div> 
            <div class="block news scrollBox">
            <table class="sOrders" width="100%" cellspacing="0" cellpadding="0" style="margin-left: -5px; margin-top: -10px;">
                <thead>
                    <th>Branch</th>
                    <th style="text-align:right;">Rate Code</th>
                    <th style="text-align:right;">Amount</th>
                </thead>
            </table>
                <div class="scroll" style="height: 270px;">
                     
                    <table class="sOrders" width="100%" cellspacing="0" cellpadding="0">   
                        <tbody id="salesperbranch">
                            <?php echo $salesperbranchview ?>   
                        </tbody>
                    </table>
                    
                </div>
                
            </div>
        </div>   
        <div class="span4">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>AE's Production (Display)</h1>                
                <div class="clear"></div>
            </div> 
            <div class="block news scrollBox">
            <table class="sOrders" width="100%" cellspacing="0" cellpadding="0" style="margin-left: -5px; margin-top: -10px;">
                <thead>
                    <th>AE</th>
                    <th style="text-align:right;">Adtype</th>
                    <th style="text-align:right;">Amount</th>
                </thead>
            </table>
                <div class="scroll" style="height: 270px;">
                     
                    <table class="sOrders" width="100%" cellspacing="0" cellpadding="0">   
                        <tbody id="aeprodisplay">
                            <?php echo $aeproddisplayview ?>       
                        </tbody>
                    </table>
                    
                </div>
                
            </div>
        </div>
                                                                   
    <div class="dr"><span></span></div>  

     <div class="span4">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Superceding</h1>                
                <div class="clear"></div>
            </div> 
            <div class="block news scrollBox">
            <table class="sOrders" width="100%" cellspacing="0" cellpadding="0" style="margin-left: -5px; margin-top: -10px;">
                <thead>
                    <th>Adtype</th>
                    <th style="text-align:right;">Total Amount</th>
                </thead>
            </table>
                <div class="scroll" style="height: 270px;">
                     
                    <table class="sOrders" width="100%" cellspacing="0" cellpadding="0">   
                        <tbody id="superced">
                            <?php echo $superceedview ?>      
                        </tbody>
                    </table>
                    
                </div>
                
            </div>
        </div>   
        <div class="span4">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>AE's Production (Libre)</h1>                
                <div class="clear"></div>
            </div> 
            <div class="block news scrollBox">
            <table class="sOrders" width="100%" cellspacing="0" cellpadding="0" style="margin-left: -5px; margin-top: -10px;">
                <thead>
                    <th>AE</th>
                    <th style="text-align:right;">Adtype</th>
                    <th style="text-align:right;">Amount</th>
                </thead>
            </table>
                <div class="scroll" style="height: 270px;">
                     
                    <table class="sOrders" width="100%" cellspacing="0" cellpadding="0">   
                        <tbody id="aeprodlibre">
                            <?php echo $libreview ?>       
                        </tbody>
                    </table>
                    
                </div>
                
            </div>
        </div>
                                                                   
    <div class="dr"><span></span></div>  


     
<script>         
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});        
$('#retrieve').click(function() {
    
    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();
    
    $.ajax({
        url: "<?php echo site_url('billing_dashp/getRealtimeDash') ?>",
        type: "post",
        data: {datefrom: datefrom, dateto: dateto},
        success: function(response) {
            $response = $.parseJSON(response);    
            
            alert('Retrieving data done!.');
            
            $('#salesaebm').html($response['salesaebmview']);
            $('#salesperbranch').html($response['salesperbranchview']);
            $('#bookingcounterclass').html($response['BookingCounterview']);
            $('#aeprodisplay').html($response['aeproddisplayview']);
            $('#aeprodlibre').html($response['libreview']);
            $('#superced').html($response['superceedview']); 
            $('#supplement').html($response['supplementview']); 
        }    
    });   
});    
</script>