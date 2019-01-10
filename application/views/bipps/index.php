<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">    

    <div class="row-fluid">
                
        <div class="span7">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>BIPPS INVOICE LIST</h1>
                <ul class="buttons">                            
                    <!--<li>
                        <a href="#" class="isw-settings"></a>
                        <ul class="dd-list">
                            <li><a href="#"><span class="isw-list"></span> Show all</a></li>
                            <li><a href="#"><span class="isw-ok"></span> Approved</a></li>
                            <li><a href="#"><span class="isw-minus"></span> Unapproved</a></li>
                            <li><a href="#"><span class="isw-refresh"></span> Refresh</a></li>
                        </ul>
                    </li>-->
                </ul>                         
                <div class="clear"></div>
            </div>
            <form action="<?php echo site_url('bipps/exportBIPPSInvoiceData') ?>" method="post" name="exportform" id="exportform">  
            <div class="block-fluid">

                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                    <thead>
                        <tr>
                            <th width="5%"><input type="checkbox" name="checkallbox" id="checkallbox"></th>
                            <th width="10%">Invoice No</th>
                            <th width="10%">Invoice Date</th>
                            <th width="60%">Payee Name</th>
                            <th width="15%">Account Code</th>                                  
                        </tr>
                    </thead>
                    <tbody id="invoicelist"> 
                    </tbody>
                </table>    
                
            </div>
            </form>
        </div>
        
        
        <div class="span5">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Filter Data</h1>
                <ul class="buttons">                            
                </ul>                         
                <div class="clear"></div>
            </div>
            <div class="block-fluid">
            
                <div class="row-form">
                    <div class="span3">Retrieve Type:</div>
                    <div class="span6">
                        <select name="rettype" id="rettype">
                            <option value="1">By Invoice Date</option>
                            <option value="2">By Invoice Number</option>
                        </select>
                    </div>    
                    <div class="clear"></div>  
                </div>
            
                <div class="row-form">
                    <div class="span3">Invoice Date:</div>
                    <div class="span3"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" class="datepicker"/></div>   
                    <div class="span3"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker"/></div>   
                    <div class="clear"></div>  
                </div>
                
                <div class="row-form">
                    <div class="span3">Invoice Number:</div>
                    <div class="span3"><input type="text" id="invoicefrom" placeholder="FROM" name="invoicefrom" class="numberfield"/></div>   
                    <div class="span3"><input type="text" id="invoiceto" placeholder="TO" name="invoiceto" class="numberfield"/></div>   
                    <div class="clear"></div>  
                </div>
                <div class="row-form">
                    <div class="span5"><button style="width:130px;" class="btn btn-success" id="generatereport" type="button">Retrieve Invoice</button></div>               
                    <div class="span2"><button style="width:130px;" class="btn btn-success" id="exportreport" type="button">Export</button></div> 
                    <div class="clear"></div>
                </div>   
                
            </div>

            <div class="block-fluid">   
            
                <div class="wBlock red">                        
                    <div class="dSpace" style="width: 60%;">
                        <h3>Total Invoice Amount</h3>
                        <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--130,190,260,230,290,400,340,360,390--></span>
                        <span class="number" id="totalsales">0.00</span>                    
                    </div>
                    <div class="rSpace" style="width: 30%; text-align: center;">
                        <span><b>Total Invoice Count</b><span id="totalinvoicecount" style="font-size: 25px; text-align: center; margin-top: 10px;"><b><?php echo 0 ?></b></span></span>
                    </div>                          
                </div>                     
                
            </div>
        </div>
    
    </div> 

</div>

<script>
$('.numberfield').mask('99999999');
$('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});  

$('#exportreport').click(function() {
    var count = 0;  
    $('.checkboxes:checked').each(function(){
        count += 1;
    });
    
    if (count > 0) {     
        $('#exportform').submit();   
    } else {
        alert('No Invoice Data to Export!');
    }
            
});
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$('#generatereport').click(function() {
    
    var ret = $('#rettype').val(); 
    var invoicefrom = $('#invoicefrom').val();
    var invoiceto = $('#invoiceto').val();
    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();
    
    var countValidate = 0;  
    if (ret == 1) {
        var validate_fields = ['#datefrom', '#dateto'];         
    } else {
        var validate_fields = ['#invoicefrom', '#invoiceto'];     
    }
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) {    
    $.ajax({
        url: '<?php echo site_url('bipps/getBIPPSInvoiceData') ?>',
        type: 'post',
        data: {invoicefrom: invoicefrom, invoiceto: invoiceto, datefrom: datefrom, dateto: dateto, ret: ret},
        success: function(response) {
            
            var $response = $.parseJSON(response);

            $('#invoicelist').html($response['invoicelist']);
        }
    }); 
    }
});
$('#checkallbox').click(function() {
    
    var count = 0;
    var amount = 0;
    if ($('#checkallbox').is(':checked')) {
        $('.checkboxes').attr('checked', 'true');   
        $('.checkboxes:checked').each(function(){
            //alert(count);
            count += 1;
            amount += parseFloat($(this).attr('data-amt'));        
        });
        $('#totalinvoicecount').html(count);
        $('#totalsales').html(amount.formatMoney(2,',','.'));   
    } else{
        $('.checkboxes').removeAttr('checked');    
        $('#totalinvoicecount').html(0)  
        amount = 0; 
        $('#totalsales').html(amount.formatMoney(2,',','.')); 
    }

    
});


Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this,
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSeparator = decSeparator == undefined ? "." : decSeparator,
    thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
    sign = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

</script>