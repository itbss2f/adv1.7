<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Billing Report (Sales Adtype / Charges w/o Invoice / Cash / Unpaginated/ Booking Counter)</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:70px;margin-top:12px">Date Retrieval:</div>
                <div class="span1" style="margin-top:12px"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" class="datepicker"/></div>   
                <div class="span1" style="margin-top:12px"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker"/></div>   
                <div class="span1" style="width:65px;margin-top:12px">Booking Type</div>                                                                                                                            
                <div class="span1" style="width:70px;margin-top:12px">
                    <select name="bookingtype" id="bookingtype">                    
                        <option value="0">All</option>                        
                        <option value="1">Display</option>                        
                        <option value="2">Classifieds</option>                        
                        <option value="3">Superced</option>                        
                    </select>
                </div>                                                                                                                            
                <div class="span2" style="width:55px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:75px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="">--</option>                        
                        <option value="1">Sales Adtype</option>                        
                        <option value="2">Charges w/o Invoice</option>                        
                        <option value="3">Zero Amount</option>                        
                        <option value="4">Cash / Credit Card / Check</option>                        
                        <option value="5">Unpaginated</option>                        
                        <option value="6">Booking Counter</option>                        
                        <option value="7">Billing Sales Adtype</option>                        
                        <option value="8">Movies Classification</option>                        
                        <option value="9">All Classification</option>                        
                    </select>
                </div>
                <div class="span2" style="width:40px;margin-top:12px">AE Filter</div>
                <div class="span2" style="width:75px;margin-top:12px">
                    <select name="aefilter" id="aefilter">                    
                        <option value="">All</option>                        
                        <option value="1">(AAD/AP/KFP)</option>                        
                        <option value="2">w/o (AAD/AP/KFP)</option>                                                                       
                    </select>
                </div>
  
                <div class="span1" style="width:80px;margin-top:12px; margin-left: 10px;"><input type="checkbox" value="1" name="nosect" id="nosect">Exclude NS</div>   
                <div class="span1" style="width:80px;margin-top:12px; margin-left: 10px;"><input type="checkbox" value="1" name="winvno" id="winvno">W/Invoice</div>   
                <div class="span1" style="width:80px;margin-top:12px; margin-left: 10px"><input type="checkbox" value="1" name="agonly" id="agonly">AG Only</div>   
                <div class="span1" style="width:80px;margin-top:12px; margin-left: 10px"><input type="checkbox" value="1" name="daonly" id="daonly">DA Only</div>   
                
                <div class="clear"></div>
            </div> 
            
            <div class="row-form" style="padding: 2px 2px 2px 10px;">      
                <div class="span1" style="width:130px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>               
                <div class="span1" style="width:130px;margin-top:12px;"><button style="width:130px;" class="btn btn-success" id="exportreport" type="button">Export V</button></div> 
                <div class="span1" style="width:130px;margin-top:12px;"><button style="width:130px;" class="btn btn-success" id="exportreport2" type="button">Export H</button></div> 
                <div class="span1" style="width:130px;margin-top:12px;"><button style="width:130px;" class="btn btn-success" id="exportreport3" type="button">ACCT EXPORT</button></div> 
                <div class="span1" style="width:130px;margin-top:12px;"><button style="width:130px;" class="btn btn-success" id="exportsales_summary" type="button">Sales Summary</button></div> 
                <div class="clear"></div>   
            </div>
            
             

            <div class="report_generator" style="height:800px;;">
                <div class="block-fluid table-sorting">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_2">
                        <thead>
                            <tr id="default">
                                <th width="7%">Product Title</th>                                                                     
                                <th width="9%">Client Name</th>                                                                     
                                <th width="8%">Agency Name</th>                                                                     
                                <th width="3%">AE</th>                                                                     
                                <th width="3%">Rate</th>                                                                     
                                <th width="3%">Prem %</th>                                                                     
                                <th width="3%">Disc %</th>                                                                     
                                <th width="5%">Size</th>                                                                     
                                <th width="5%">Amount</th>                                                                     
                                <th width="3%">CCM</th>                                                                     
                                <th width="3%">Color</th>                                                                     
                                <th width="4%">AO No.</th>                                                                     
                                <th width="6%">PO Number</th>                                                                     
                                <th width="3%">User</th>                                                                     
                                <th width="7%">Billing Remarks</th>                                                                     
                                <th width="3%">Paytype</th> 
                                <th width="3%">Adtype</th>                                                                                                                                         
                                <th width="3%">Billing Adtype</th>                                                                                                                                         
                                <th width="3%">Subtype</th>                                                                                                                                         
                                <th width="2%">AI No.</th>                                                                     
                            </tr>
                            <tr id="default2" style="width: 100%; display: none;">                                                                     
                                <th>Section</th>                                                                     
                                <th>Client Name</th>                                                                     
                                <th>Agency Name</th>                                                                     
                                <th>AE</th>                                                                                                                                         
                                <th>Size</th>                                                                                                              
                                <th>CCM</th>                                             
                                <th>Amount</th>                                                                                             
                                <th>AO No.</th>                                                                                                                                                    
                                <th>Classification</th> 
                                <th>Adtype</th>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
                                <th>Billing Adtype</th>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
                            </tr>
                            <tr id="default3" style="width: 100%; display: none;">                                                                     
                                <th>ID #</th>
                                <th>AO No.</th>
                                <th>Section</th>                                                                     
                                <th>Client Name</th>                                                                     
                                <th>Agency Name</th>                                                                                                                                                                                                            
                                <th>Product Title</th>                                                                                                              
                                <th>Classification</th> 
                                <th>Adtype</th>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
                                <th>Billing Adtype</th>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
                            </tr>
                        </thead>
                        <tbody id='datalist' style="min-height: 800px; font-size: 11px">
                            
                        </tbody>
                    </table>
                    <div class="clear"></div>
                </div>
            </div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
    
</div> 
<div id="viewmovieclass" title="Movie and Classification Information"></div> 
<script> 
$("#reporttype").change(function() {
    var id = $("#reporttype").val();
    
    if (id == 7) {
        $('#default2').show();
        $('#default').hide();
        $('#default3').hide();
        $('#datalist').html('');
    } else if (id == 8 || id == 9) {
        $('#default3').show();
        $('#default2').hide();
        $('#default').hide();
        $('#datalist').html('');
    } else {
        $('#default3').hide();
        $('#default2').hide();
        $('#default').show();
        $('#datalist').html('');
    }   
});      
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val();
    var aefilter = $("#aefilter").val();    
    var nosect = $("#nosect:checked").val();    
    var winvno = $("#winvno:checked").val();    
    var agonly = $("#agonly:checked").val();    
    var daonly = $("#daonly:checked").val();    

    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#reporttype'];
    
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
        url: '<?php echo site_url('billing_report/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, reporttype: reporttype, bookingtype: bookingtype, nosect: nosect, winvno: winvno, agonly: agonly, daonly: daonly, aefilter: aefilter},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            
            $('#datalist').html($response['datalist']);
            
            alert('Retrieving Done!');
            
        }
    })
    //$("#source").attr('src', "<?php #echo site_url('cmdm_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+reporttype);     

    }
    
    
    });
    
      
/* report for budget*/
$("#exportreport").click(function()
  {
     
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val(); 
    var aefilter = $("#aefilter").val();   
    var nosect = $("#nosect:checked").val();    
    var winvno = $("#winvno:checked").val();
    var agonly = $("#agonly:checked").val(); 
    var daonly = $("#daonly:checked").val();    



    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#reporttype'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
    
         {

            window.open("<?php echo site_url('billing_report/exportreport/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&bookingtype="+bookingtype+"&nosect="+nosect+"&winvno="+winvno+"&agonly="+agonly+"&daonly="+daonly+"&aefilter="+aefilter, '_blank');
            window.focus();
         }  
    }
       
 });
$("#exportreport2").click(function()
  {
     
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val(); 
    var nosect = $("#nosect:checked").val();    
    var winvno = $("#winvno:checked").val();
    var agonly = $("#agonly:checked").val(); 
    var daonly = $("#daonly:checked").val();     

    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#reporttype'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
    
         {

            window.open("<?php echo site_url('billing_report/exportreport2/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&bookingtype="+bookingtype+"&nosect="+nosect+"&winvno="+winvno+"&agonly="+agonly+"&daonly="+daonly, '_blank');
            window.focus();
         }  
    }
});        
$("#exportreport3").click(function()
  {
     
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val(); 
    var nosect = $("#nosect:checked").val();    
    var winvno = $("#winvno:checked").val();
    var agonly = $("#agonly:checked").val();  
    var daonly = $("#daonly:checked").val();
    
    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#reporttype'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
    
         {

            window.open("<?php echo site_url('billing_report/exportreport3/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&bookingtype="+bookingtype+"&nosect="+nosect+"&winvno="+winvno+"&agonly="+agonly+"&daonly="+daonly, '_blank');
            window.focus();
         }  
    }    
  
});  

$("#exportsales_summary").click(function()
  {
      
      //alert("hello"); exit();
     
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val(); 
    var nosect = $("#nosect:checked").val();    
    var winvno = $("#winvno:checked").val();
    var agonly = $("#agonly:checked").val(); 
    var daonly = $("#daonly:checked").val();    


    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#reporttype'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
    
         {

            window.open("<?php echo site_url('billing_report/exportsales_summary/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&bookingtype="+bookingtype+"&nosect="+nosect+"&winvno="+winvno+"&agonly="+agonly+"&daonly="+daonly, '_blank');
            window.focus();
         }  
    }
       
 });

      
</script>


