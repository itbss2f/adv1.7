
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Desk Report (Dummy / Premium)</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:80px;margin-top:12px">Date as of:</div>
                <div class="span1" style="margin-top:12px"><input type="text" id="dateasof" name="dateasof" class="datepicker"/></div>                                                                
                <div class="span1" style="width:80px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:150px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="">--</option>                        
                        <option value="1">Dummied Ads</option>                        
                        <option value="2">Undummied Ads</option>
                        <option value="6">Blockout Ads</option>
                        <option value="3">Premium Ads</option>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
                        <option value="4">Miscellaneous Charges</option>                                                                                                                                                                                                                                                                                                                                                       
                        <option value="5">Classification Ads</option>                                                                                                                                      
                        <option value="7">Front Not Business</option>                                                                                                                                      
                        <option value="8">Prem Not Main</option>                                                                                                                                      
                        <option value="9">Booking and Dummy Class Discrepancy</option>                                                                                                                                      
                    </select>
                </div>
                <div class="span1" style="width:80px;margin-top:12px">Product</div>
                <div class="span2" style="margin-top:12px">
                    <select name="product" id="product">                    
                        <?php foreach ($product as $product) : 
                            if ($product['id'] == 15) { ?>
                            <option value="<?php echo $product['id'] ?>" selected="selected"><?php echo $product['prod_name'] ?></option>                            
                            <?php } else {  ?>
                            <option value="<?php echo $product['id'] ?>"><?php echo $product['prod_name'] ?></option>                            
                            <?php    
                            }
                        ?>
                        
                        <?php endforeach; ?>                                                                                                                                                                                                                                                                                                                                                                            
                    </select>
                </div>
                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>                
                <div class="clear"></div>
            </div> 
            
            <div class="row-form classi" style="padding: 2px 2px 2px 10px; display: none;">
                <div class="span1 classi" style="width:80px;margin-top:12px">Classification</div>
                <div class="span2 classi" style="margin-top:12px">
                    <select name="classification" id="classification">                  
                        <?php foreach ($class as $class) :  ?>
                            <option value="<?php echo $class['id'] ?>"><?php echo $class['class_code'] ?></option>                            
                        <?php endforeach; ?>                                                                                                                                                                                                                                                                                                                                                                            
                    </select>
                </div>
                <div class="clear"></div>  
            </div>
             

            <div class="report_generator" style="height:800px;padding-left:7px;"><iframe style="width:99%;height:99%" id="source"></iframe></div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>
$("#reporttype").change(function(){
    var $report = $(this).val();
    $('.classi').hide();

    if ($report == 5) {
        $('.classi').show();

    } else {
        $('.classi').hide(); 
    } 
}); 

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(response) {
    

    var dateasof = $("#dateasof").val();
    var reporttype = $("#reporttype").val();
    var productcode = $("#product").val();
    var productname = $("#product :selected").text();
    var classification = $("#classification").val();   
    var classificationname = $("#classification :selected").text();

    
    var countValidate = 0;  
    var validate_fields = ['#dateasof', '#reporttype'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
    
    //window.location
    $("#source").attr('src', "<?php echo site_url('desk_report/generatereport') ?>/"+dateasof+"/"+reporttype+"/"+productcode+"/"+productname+"/"+classification+"/"+classificationname);     

    }
});       
</script>


