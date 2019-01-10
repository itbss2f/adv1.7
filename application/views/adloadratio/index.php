<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>
<div class="workplace">

    <div class="row-fluid">

        <div class="span6">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Ad Load Ratio</h1>
                    
                <div class="clear"></div>
            </div>
            <div class="block-fluid">
                <div class="row-form">
                    <div class="span2"  style="font-size: 12px;"><b>Product</b></div>    
                    <div class="span7">
                        <select name="product" id="product">
                            <?php foreach ($prod as $prod) : ?>
                                <?php if ($prod['prod_code'] == 'PD') : ?>
                                    <option value="<?php echo $prod['id'] ?>" selected="selected" data-ccm="<?php echo $prod['prod_ccm'] ?>"><?php echo $prod['prod_name'] ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $prod['id'] ?>" data-ccm="<?php echo $prod['prod_ccm'] ?>"><?php echo $prod['prod_name'] ?></option>       
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                    <div class="clear"></div>    
                </div>
                <div class="row-form">
                    <div class="span2"  style="font-size: 12px;"><b>Enter Issue Date</b></div>    
                    <div class="span2"><input type="text" name="issuedate" id="issuedate" class="datepicker"></div>        
                    <div class="span2"  style="font-size: 12px;"><b>Number of Page</b></div>    
                    <div class="span2"><input type="text" name="numberpage" id="numberpage" style="text-align: right;" value="0.00" class="numberfield"></div>        
                    <div class="clear"></div>    
                </div>  
                <div class="row-form">
                    <div class="span2"  style="font-size: 12px;"><b>Additional Col. cm</b></div>    
                    <div class="span2"><input type="text" name="additionalcm" id="additionalcm" style="text-align: right;" value="0.00" class="numberfield" readonly="readonly"></div>        
                    <div class="span2"  style="font-size: 12px;"><b>Total Col. cm</b></div>    
                    <div class="span2"  style="font-size: 12px;"><input type="text" name="totalcolcm" id="totalcolcm" style="text-align: right;" value="0.00" class="numberfield" readonly="readonly"></div>  
                    <div class="span2"  style="font-size: 12px;"><b>Ad Load Ratio</b></div>    
                    <div class="span2"><input type="text" name="adloadratio" id="adloadratio" style="text-align: right;" value="0.00" class="numberfield" readonly="readonly"></div>      
                    <div class="clear"></div>    
                </div>  
                <div class="row-form">
                    <div class="span2"><button class="btn btn-success" type="button" name="compute" id="compute">Compute</button></div>
                    <div class="span4"><button class="btn btn-info" type="button" name="printc" id="printc">Print Total CCM (Classified)</button></div>
                    <div class="span4"><button class="btn btn-info" type="button" name="printd" id="printd">Print Total CCM (Display)</button></div>
                    <div class="clear"></div>    
                </div>  
                
                <div class="clear"></div>
            </div>
        </div>
        
        <div class="span4">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>COMPUTATION</h1>
                    
                <div class="clear"></div>
            </div>
            <div class="block-fluid">
                <div class="row-form" style="background-color: #CCCCCC;">  
                    <div class="item" style="font-size: 12px;">
                        <p style="font-size: 15px; margin-bottom: -10px;">total ccm + additional ccm</p>
                        <p style="font-size: 25px;">_____________ X 100</p>
                        <p style="font-size: 15px;">no. of pages * <span id="sproductccm" style="font-size: 25px; color: red;">477</span></p>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>

    </div> 
    
    <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>
                
    </div>           

    <div class="dr"><span></span></div>
</div>  

<script>
$('.numberfield').autoNumeric();
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'}); 
$("#product").change(function(){
    var product = $("#product").val();
    var selected = $(this).find('option:selected');
    var productccm = selected.data('ccm'); 
    
    $('#sproductccm').html(productccm);
    
});
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$('#compute').click(function(){
    
    var countValidate = 0;  
    var validate_fields = ['#issuedate', '#numberpage', '#additionalcm', '#totalcolcm'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
    

        var issuedate = $('#issuedate').val();
        var numberpage = $('#numberpage').val();
        var selected = $('#product').find('option:selected');
        var productccm = selected.data('ccm');
        var product = $("#product").val();  
        
        $.ajax({
            url: '<?php echo site_url('adloadratio/computeadload') ?>',
            type: 'post',
            data: {issuedate: issuedate, numberpage: numberpage, productccm: productccm, product: product},
            success: function (response) {
                
                $response = $.parseJSON(response);    
                
                $('#totalcolcm').val($response['totalccm']); 
                $('#additionalcm').val($response['addccm']); 
                $('#adloadratio').val($response['adloadratio']); 
                
            }    
        });

    }
        
});

$("#printc").click(function() {
    
    var countValidate = 0;  
    var validate_fields = ['#issuedate', '#numberpage', '#additionalcm', '#totalcolcm'];
    
    var issuedate = $('#issuedate').val();
    var product = $("#product").val();  
    var productname = $("#product :selected").text();  

    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
    
    
    $("#source").attr('src', "<?php echo site_url('adloadratio/generatereport') ?>/"+issuedate+"/"+product+"/"+2+"/"+productname);       
    
    }
});


$("#printd").click(function() {
    
    var countValidate = 0;  
    var validate_fields = ['#issuedate', '#numberpage', '#additionalcm', '#totalcolcm'];
    
    var issuedate = $('#issuedate').val();
    var product = $("#product").val();  
    var productname = $("#product :selected").text();  

    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
    
    
    $("#source").attr('src', "<?php echo site_url('adloadratio/generatereport') ?>/"+issuedate+"/"+product+"/"+1+"/"+productname);       
    
    }
});

</script>
