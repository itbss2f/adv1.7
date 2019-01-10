
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Sales Billing Report</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:80px;margin-top:12px">Date Retrieval:</div>
                <div class="span1" style="width:60px;margin-top:12px"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" class="datepicker"/></div>   
                <div class="span1" style="width:60px;margin-top:12px"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker"/></div>   
                <div class="span1" style="width:70px;margin-top:12px">Booking Type</div>
                <div class="span2" style="width:70px;margin-top:12px">
                    <select name="bookingtype" id="bookingtype">                    
                        <option value="1">--All--</option>                           
                        <option value="D">Display</option>                        
                        <option value="C">Classifieds</option>                        
                        <option value="M">Superceding</option>                        
                                             
                    </select>
                </div>
                <div class="span1" style="width:60px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:70px;margin-top:12px">
                
                    <select name="reporttype" id="reporttype">
                        <option value="0">----</option>                   
                        <option value="1">Agency</option>                           
                        <option value="2">Direct Ads</option>                        
                        <option value="3">Client</option>                        
                        <option value="4">Adtype</option>                        
                        <option value="5">Product</option>                        
                        <option value="6">Branch</option>                        
                                             
                    </select>
                </div>
                <div class="span2" style="width:60px;margin-top:12px">Pay Type</div>
                <div class="span2" style="width:70px;margin-top:12px">
                    <select name="paytype" id="paytype">       
                        <option value="0">--All--</option>                                                          
                        <?php foreach ($paytype as $paytype) : ?>
                        <option value="<?php echo $paytype['id'] ?>"><?php echo $paytype['paytype_name'] ?></option>                                 
                        <?php endforeach; ?>                             
                    </select>
                </div>
                <div class="span2" style="width:60px;margin-top:12px">Class Type</div>
                <div class="span2" style="width:70px;margin-top:12px">
                    <select name="xclass" id="xclass">       
                        <option value="0">--All--</option>                                                          
                        <?php foreach ($class as $row) : ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['class_name'] ?></option>                                 
                        <?php endforeach; ?>                             
                    </select>
                </div>
                           
                <div class="clear"></div>
            </div>   
            
            <div class="row-form" style="padding: 2px 2px 2px 10px;"> 
                <div class="span2" style="width:70px;margin-top:12px">Top Rank List</div>
                <div class="span2" style="width:50px;margin-top:12px"><input type="text" id="toprank" value="15" name="toprank" style="text-align: center;"/></div>
                <div class="span2" style="width:120px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>
                <div class="span2" style="width:120px;margin-top:12px"><button class="btn btn-success" id="exportreport" type="button">Export button</button></div>    
                <div class="clear"></div>    
            </div>   
            
            <div class="row-form" style="padding: 2px 2px 2px 10px;">  
                <div class="span2 vadtype" style="width:70px;margin-top:12px;display: none;">Adtype List</div>
                <div class="span2 vadtype" style="width:150px;margin-top:12px;display: none;">
                    <select name="adtypefrom" id="adtypefrom">                    
                        <?php foreach ($adtype as $adtype1) : ?>
                        <option value="<?php echo $adtype1['adtype_name'] ?>"><?php echo $adtype1['adtype_code'].' - '.$adtype1['adtype_name'] ?></option>                                 
                        <?php endforeach; ?>                             
                    </select>
                </div>
                <div class="span2 vadtype" style="width:150px;margin-top:12px;display: none;">
                    <select name="adtypeto" id="adtypeto">                    
                        <?php foreach ($adtype as $adtype2) : ?>
                        <option value="<?php echo $adtype2['adtype_name'] ?>"><?php echo $adtype2['adtype_code'].' - '.$adtype2['adtype_name'] ?></option>                                 
                        <?php endforeach; ?>    
                    </select>
                </div>
                
                <div class="span2 vproduct" style="margin-left:0px;width:70px;margin-top:12px;display: none;">Product List</div>
                <div class="span2 vproduct" style="width:150px;margin-top:12px;display: none;">
                    <select name="productfrom" id="productfrom">                    
                        <?php foreach ($product as $product1) : ?>
                        <option value="<?php echo $product1['prod_name'] ?>"><?php echo $product1['prod_code'].' - '.$product1['prod_name'] ?></option>                                 
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="span2 vproduct" style="width:150px;margin-top:12px;display: none;">
                    <select name="productto" id="productto">                    
                        <?php foreach ($product as $product2) : ?>
                        <option value="<?php echo $product2['prod_name'] ?>"><?php echo $product2['prod_code'].' - '.$product2['prod_name'] ?></option>                                 
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="span2 vbranch" style="margin-left:0px;width:70px;margin-top:12px;display: none;">Branch List</div>
                <div class="span2 vbranch" style="width:150px;margin-top:12px;display: none;">
                    <select name="branchfrom" id="branchfrom">                    
                        <?php foreach ($branch as $branch1) : ?>
                        <option value="<?php echo $branch1['branch_name'] ?>"><?php echo $branch1['branch_code'].' - '.$branch1['branch_name'] ?></option>                                 
                        <?php endforeach; ?>                             
                    </select>
                </div>
                <div class="span2 vbranch" style="width:150px;margin-top:12px;display: none;">
                    <select name="branchto" id="branchto">                    
                        <?php foreach ($branch as $branch2) : ?>
                        <option value="<?php echo $branch2['branch_name'] ?>"><?php echo $branch2['branch_code'].' - '.$branch2['branch_name'] ?></option>                                 
                        <?php endforeach; ?>                                                              
                    </select>
                </div>
                <div class="clear"></div>
            </div>   
             
            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>
                
            </div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>     
$("#reporttype").change(function(){
    var $report = $(this).val();
    $('.vadtype').hide();
    $('.vproduct').hide();
    $('.vbranch').hide();
    if ($report == 4) {
        $('.vadtype').show();
        $('.vproduct').hide();
        $('.vbranch').hide();
    } else if ($report == 5) {
        $('.vadtype').hide();
        $('.vproduct').show();
        $('.vbranch').hide();
    } else if ($report == 6) {
        $('.vadtype').hide();
        $('.vproduct').hide();
        $('.vbranch').show();
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
    var toprank = $("#toprank").val();
    var adtypefrom = $("#adtypefrom").val();
    var adtypeto = $("#adtypeto").val();
    var productfrom = $("#productfrom").val();
    var productto = $("#productto").val();
    var branchfrom = $("#branchfrom").val();
    var branchto = $("#branchto").val();
    var toprank = $("#toprank").val();
    var paytype = $("#paytype").val();
    var xclass = $("#xclass").val();


    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#toprank', '#reporttype'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
    

    $("#source").attr('src', "<?php echo site_url('salesbilling_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+reporttype+"/"+bookingtype+"/"+toprank+"/"+adtypefrom+"/"+adtypeto+"/"+productfrom+"/"+productto+"/"+branchfrom+"/"+branchto+"/"+toprank+"/"+paytype+"/"+xclass);     

    }
    
 });    
    
$("#exportreport").die().live("click",function() {
        
            var datefrom = $("#datefrom").val();
            var dateto = $("#dateto").val();
            var bookingtype = $("#bookingtype").val();
            var reporttype = $("#reporttype").val();
            var toprank = $("#toprank").val();
            var adtypefrom = $("#adtypefrom").val();
            var adtypeto = $("#adtypeto").val();
            var productfrom = $("#productfrom").val();
            var productto = $("#productto").val();
            var branchfrom = $("#branchfrom").val();
            var branchto = $("#branchto").val();
            var toprank = $("#toprank").val();
            var paytype = $("#paytype").val();
            var xclass = $("#xclass").val();
            
            var countValidate = 0;  
            var validate_fields = ['#datefrom', '#dateto', '#toprank', '#reporttype'];
            
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2); 
                
        }
    }         
              
              if (countValidate == 0) 
              
       {   
    window.open("<?php echo site_url('salesbilling_report/exportreport/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&bookingtype="+bookingtype+"&reporttype="+reporttype+"&toprank="+toprank+"&adtypefrom="+adtypefrom+"&adtypeto="+adtypeto+"&productfrom="+productfrom+"&productto="+productto+"&branchfrom="+branchfrom+"&branchto="+branchto+"&toprank="+toprank+"&paytype="+paytype+"&xclass="+xclass, '_blank');
            window.focus();     
        }      
               
});  
  
</script>


