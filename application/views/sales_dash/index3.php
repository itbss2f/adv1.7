

<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>


<div class="workplace">    



       <!-- Tables -->      

       <div class="row-fluid">
       
        <div class="span4">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Top Client</h1>
                <ul class="buttons">                            
                    <li>
                        <a href="#" class="isw-settings"></a>
                        <ul class="dd-list">
                            <li name="exceltopclient" id="exceltopclient"><a href="#"><span class="isw-list"></span>Export Excel</a></li>
                        </ul>
                    </li>
                </ul>                         
                <div class="clear"></div>
            </div>
            <div class="block-fluid">
                
                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                    <thead>
                        <tr>
                            <th width="70%">Advertiser Name</th>
                            <th width="30%">Amount</th>                                  
                        </tr>
                    </thead>
                    <tbody id="topclient">
                        <?php echo $topclient; ?>                               
                    </tbody>
                </table>    
                
            </div>
        </div>
                
        <div class="span4">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Top Agency</h1>
                <ul class="buttons">                            
                    <li>
                        <a href="#" class="isw-settings"></a>
                        <ul class="dd-list">
                            <li name="exceltopagency" id="exceltopagency"><a href="#"><span class="isw-list"></span>Export Excel</a></li>
                        </ul>
                    </li>
                </ul>                        
                <div class="clear"></div>
            </div>
            <div class="block-fluid">
                
                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                    <thead>
                        <tr>
                            <th width="70%">Advertiser Name</th>
                            <th width="30%">Amount</th>                                  
                        </tr>
                    </thead>
                    <tbody id="topagency">
                       <?php echo $topagency; ?>                               
                    </tbody>
                </table>
                
            </div>
        </div>                               

        <div class="span4">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Top Direct Ads</h1>
                <ul class="buttons">        
                    <li>
                        <a href="#" class="isw-settings"></a>
                        <ul class="dd-list">
                            <li name="exceltopdirect" id="exceltopdirect"><a href="#"><span class="isw-list"></span>Export Excel</a></li>
                        </ul>
                    </li>
                </ul> 
                <div class="clear"></div>
            </div>
            <div class="block-fluid">
                
                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                    <thead>
                        <tr>
                            <th width="70%">Advertiser Name</th>
                            <th width="30%">Amount</th>                                  
                        </tr>
                    </thead>
                    <tbody id="topdirectads">
                        <?php echo $topdirectads; ?>                               
                    </tbody>
                </table>
                
            </div>
        </div>                
                
    </div>
    <!-- End Tables -->
    <div class="dr"><span></span></div> 
      
    <div class="row-fluid">
            
        <div class="span4">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Top Adtype</h1>
                <ul class="buttons">                            
                    <li>
                        <a href="#" class="isw-settings"></a>
                        <ul class="dd-list">
                            <li name="exceltopadtype" id="exceltopadtype"><a href="#"><span class="isw-list"></span>Export Excel</a></li>
                        </ul>
                    </li>
                </ul>                         
                <div class="clear"></div>
            </div>
            <div class="block-fluid">
                
                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                    <thead>
                        <tr>
                            <th width="70%">Adtype Name</th>
                            <th width="30%">Amount</th>                                  
                        </tr>
                    </thead>
                     <tbody id="topadtype">
                        <?php echo $topadtype; ?>                               
                    </tbody>
                </table>    
                
            </div>
        </div>
                
        <div class="span4">
              <div class="wBlock red">                        
                    <div class="dSpace" style="width: 60%; font-size: 15px;">
                        <h3>Sales statistics</h3>
                        <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--130,190,260,230,290,400,340,360,390--></span>
                        <span class="number" id="totalsales" style="font-size: 18px"><?php echo number_format($totalsales, 2, '.',',') ?></span>                    
                    </div>
                    <div class="rSpace" style="width: 35%;">
                        <span>Date From : <span id="datefromf"><b><?php echo $datefrom ?></b></span></span>
                        <span>Date To : <span id="datetof"><b><?php echo $dateto ?></b></span></span>
                        <span></span>
                    </div>                          
                </div>                     
        </div>                               

        <div class="span4">
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Filter My Dashboard</h1>
                <ul class="buttons">                            
                    <li>
                        <a href="#" class="isw-settings"></a>
                        <ul class="dd-list">
                            <li name="viewmyclient" id="viewmyclient"><a href="#"><span class="isw-list"></span>My Client</a></li>
                        </ul>
                    </li>
                </ul>                         
                <div class="clear"></div>
            </div>
            <div class="block-fluid">
                
                <table cellpadding="0" cellspacing="0" width="100%" class="table">

                    <tbody>
                        <tr>
                            <td>Retrieve Type :</td>
                            <td><select name="rettype" id="rettype">
                                    <option value="0">-- All --</option>
                                    <option value="5">Total Sales</option>
                                    <option value="1">Top Client</option>
                                    <option value="2">Top Agency</option>
                                    <option value="3">Top Direct Ads</option>
                                    <option value="4">Top Adtype</option>
                                </select>
                            </td>
                        </tr>     
                        
                        <tr>
                        
                            <td>Date From :</td>
                            <td><input type="hidden" name="aeid" id="aeid" value="<?php echo $aeid ?>">        <input type="text" id="datefrom" placeholder="FROM" name="datefrom" value="<?php echo $datefrom ?>" class="datepicker"/>&nbsp;&nbsp;<input type="text" id="dateto" placeholder="TO" value="<?php echo $dateto ?>" name="dateto" class="datepicker"/></td>
                        </tr> 
                       
                        <tr>
                            <td>Top Rank :</td>
                            <td><input type="text" id="toprank" value="5" name="toprank" style="width: 50px"/></td>
                        </tr> 
                        
                        <tr>
                            <td>Actual / Forecast :</td>
                            <td>
                                <select name="actual" id="actual" style="width: 100px">
                                    <option value="A">Actual</option>
                                    <option value="F">Forecast</option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td><button class="btn btn-success" id="filter" type="button">Filter Data</button></td>
                        </tr>
                        
                    </tbody>
                </table>    
                 
            </div>
        </div>                
                
    </div> 
    
    <div class="dr"><span></span></div>    

</div>  
<script>
var aeid = $('#aeid').val();
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'}); 

$("#viewmyclient").click(function() {
    window.open("<?php echo site_url('sales_dash/viewmyclient') ?>","","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=600, height=600");        

});


$("#filter").click(function(){
    //alert('asd'); return false;
    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();
    var toprank = $('#toprank').val();
    var rettype = $('#rettype').val();
    var actual = $('#actual').val();
    
    $.ajax({
        url: '<?php echo site_url('sales_dash/filterData') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, toprank: toprank, rettype: rettype, actual: actual, aeid: aeid},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            
            
            if (rettype == 1) {
                $('#topclient').html($response['topclient']);       
            } else if (rettype == 2) {
                $('#topagency').html($response['topagency']);           
            } else if (rettype == 5) {
                $('#totalsales').html($response['totalsales']);           
                $('#datefromf').html($response['datefrom']);           
                $('#datetof').html($response['dateto']);           
            } else if (rettype == 3) {
                $('#topdirectads').html($response['topdirectads']);            
            }  else if (rettype == 4) {
                $('#topadtype').html($response['topadtype']);           
            } else if (rettype == 0) {
                $('#topclient').html($response['topclient']); 
                $('#topagency').html($response['topagency']);
                $('#totalsales').html($response['totalsales']);           
                $('#datefromf').html($response['datefrom']);           
                $('#datetof').html($response['dateto']);    
                $('#topdirectads').html($response['topdirectads']);    
                $('#topadtype').html($response['topadtype']);                               
            } 
        
        }
     
    }) 
         });

 $("#exceltopclient").die().live("click",function() {

    var reporttype = 1;
    var datefrom = $("#datefrom").val();  
    var dateto = $("#dateto").val();
    var toprank = $("#toprank").val();     
    var topclient = $("#topclient").val();
    var totalsales = $("#totalsales").val();     
     
     
    window.open("<?php echo site_url('sales_dash/exceltopclient') ?>?reporttype="+reporttype+"&datefrom="+datefrom+"&dateto="+dateto+"&toprank="+toprank+"&aeid="+aeid, '_blank'); 
    window.focus();
   
     });
   
  $("#exceltopagency").die().live("click",function() {

    var reporttype = 2;
    var datefrom = $("#datefrom").val();  
    var dateto = $("#dateto").val();
    var toprank = $("#toprank").val();     
    var topagency = $("#topagency").val();
    var totalsales = $("#totalsales").val(); 
    
    window.open("<?php echo site_url('sales_dash/exceltopagency') ?>?reporttype="+reporttype+"&datefrom="+datefrom+"&dateto="+dateto+"&toprank="+toprank+"&aeid="+aeid, '_blank'); 
    window.focus();
       });
       
    $("#exceltopdirect").die().live("click",function() {

    var reporttype = 3;
    var datefrom = $("#datefrom").val();  
    var dateto = $("#dateto").val();
    var toprank = $("#toprank").val();     
    var topdirect = $("#topdirect").val();
    var totalsales = $("#totalsales").val(); 
    
    window.open("<?php echo site_url('sales_dash/exceltopdirect') ?>?reporttype="+reporttype+"&datefrom="+datefrom+"&dateto="+dateto+"&toprank="+toprank+"&aeid="+aeid, '_blank');  
    window.focus();   
       
  });  
  
   $("#exceltopadtype").die().live("click",function() {

    var reporttype = 4;
    var datefrom = $("#datefrom").val();  
    var dateto = $("#dateto").val();
    var toprank = $("#toprank").val();     
    var topadtype = $("#topadtype").val();
    var totalsales = $("#totalsales").val();  
    
    window.open("<?php echo site_url('sales_dash/exceltopadtype') ?>?reporttype="+reporttype+"&datefrom="+datefrom+"&dateto="+dateto+"&toprank="+toprank+"&aeid="+aeid, '_blank');  
    window.focus();   
       
  }); 
            
   
</script>
   
   

 