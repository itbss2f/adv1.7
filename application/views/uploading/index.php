<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Material Uploading</h1>
                    
                <div class="clear"></div>
            </div>
            <?php echo form_open_multipart('file_upload/upload_data');?>                          
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span2" style="width:40px;margin-top:12px">Product</div>
                <div class="span2" style="width:200px;margin-top:12px">
                    <select name="product" id="product">
                        <option value="">----</option>      
                        <?php foreach ($prod as $prod) : ?>                                       
                        <option value="<?php echo $prod['id'] ?>"><?php echo $prod['prod_name'] ?></option>                        
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="span1" style="width:40px;margin-top:12px">Issuedate:</div>
                <div class="span1" style="margin-top:12px"><input type="text" id="datefrom" placeholder="" name="datefrom" class="datepicker"/></div>  

                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="find" name="find" type="button">Find</button></div>               
                <div class="clear"></div>
            </div>
            <div class="block-fluid table-sorting" style="margin-top: 10px;min-height: 450px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                    <thead>
                        <tr>          
                            <th width="3%">AO #</th>  
                            <th width="3%">Book #</th>  
                            <th width="3%">Folio #</th>  
                            <th style="text-align: center;" width="5%">Section</th>          
                            <th style="text-align: center;" width="8%">Size</th>          
                            <th width="5%">CCM</th>                       
                            <th width="5%">Color</th>                       
                            <th style="text-align: center;" width="10%">Client</th>                       
                            <th style="text-align: center;" width="10%">Agency</th>                       
                            <th style="text-align: center;" width="10%">AE</th>                      
                            <th colspan="3" style="text-align: center;" width="5%">Action</th>                      
                        </tr>
                    </thead>
                    <tbody id="fileattachment">  
                    </tbody>
                </table>
                <div class="clear"></div>
            </div>
        </div>
    </div>            
    <div class="dr"><span></span></div>
</div>  

<div id="modal_upload" title="Uploading"></div>

<script>


var errorcssobj = {'background': '#EED3D7','border' : '1px solid #ff5b57'}; 
var errorcssobj2 = {'background': '#cee','border' : '1px solid #00acac'};

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});  

$('#find').click(function(){
    var $product = $("#product").val();  
    var $datefrom = $("#datefrom").val();  
    $.ajax({
        url: '<?php echo site_url('material_upload/findProduct') ?>',
        type: 'post',
        data: {product: $product, datefrom: $datefrom},  
        success: function(response){
            $response = $.parseJSON(response);   
            $('#fileattachment').html($response['fileattachment']);
            
        }    
    });   
});

$('#modal_upload').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: 430,    
    height: 'auto',
    modal: true,
    resizable: false
});  

</script>

