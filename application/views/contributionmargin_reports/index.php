
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Contribition Margin Report</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:80px;margin-top:12px">Date Retrieval:</div>
                <div class="span1" style="margin-top:12px"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" class="datepicker"/></div>   
                <div class="span1" style="margin-top:12px"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker"/></div>   
                <div class="span1" style="width:80px;margin-top:12px">Booking Type</div>                                                                                                                            
                <div class="span1" style="width:100px;margin-top:12px">
                    <select name="bookingtype" id="bookingtype">
                        <option value="0">----</option>                
                        <option value="1">Display</option>                        
                        <option value="2">Classified </option>                        
                    </select>
                </div>  
                <div class="span2" style="width:60px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:150px;margin-top:12px">
                    <select name="reporttype" id="reporttype">      
                        <option value="">----</option>                                             
                        <option value="1">Ad Report(per Ad Type)</option>                                            
                        <option value="2">Ad Report(per Product)</option>                                           
                        <option value="3">Agency Commission(per Ad Type)</option>                                           
                        <option value="4">Agency Commission(per Product)</option>                                                                                   
                    </select>
                </div> 
                <div class="clear"></div>
            </div> 
            <div class="row-form" style="padding: 2px 2px 2px 10px;"> 
                <div class="span1" style="width:80px;margin-top:12px">Product</div>
                <div class="span1" style="width:130px;margin-top:12px">
                    <select name="product" id="product"> 
                         <option value="0">-- All --</option>    
                        <?php foreach ($editionname as $product) : ?>                                       
                        <option value="<?php echo $product['id'] ?>"><?php echo $product['name'] ?></option>                        
                        <?php endforeach; ?>
                    </select>
                </div> 
                <div class="span1" style="width:60px;margin-top:12px">Adtype</div>
                <div class="span2" style="width:100px;margin-top:12px">
                    <select name="adtype" id="adtype">                    
                        <option value="0">-- All --</option>  
                        <?php foreach ($adtype as $adtype) : ?> 
                        <option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_code'].' | '.$adtype['adtype_name'] ?></option>    
                        <?php endforeach; ?>                                                                                        
                    </select>
                   </div>  
                <div class="span1" style="width:180px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate Report</button></div>
                <div class="clear"></div>     
            </div>
            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>
            </div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>      
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'};
 
$("#generatereport").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val();
    var product = $("#product").val();
    var adtype = $("#adtype").val();
    

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
      
    
    if (countValidate == 0) 
    
    {
    
    $("#source").attr('src', "<?php echo site_url('contributionmargin_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+bookingtype+"/"+reporttype+"/"+product+"/"+adtype);     

    }
    
    
    
    }); 
    
         
</script>


