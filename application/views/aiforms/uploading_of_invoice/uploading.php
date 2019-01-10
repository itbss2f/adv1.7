
<div class="breadLine"> 
    <?php echo $breadcrumb; ?> 
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Invoice Data Uploading</h1>
                <div class="clear"></div>
            </div> 
            <?php echo form_open_multipart('aiform/uploading');?> 
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width: 80px; margin-top:12px"><b>Invoice Number:</b></div>
                <div class="span1" style="width: 80px; margin-top:12px"><input type="text" id="invnum" placeholder="Enter Invoice Number" name="invnum"/></div> 

                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="search" name="search" type="button">Search button</button></div>               
                <div class="clear"></div>
            </div>                         
            <div class="row-form-booking-form">
                <div style="width:30%;float:left">
                    <div class="row-form-booking-form">  
                        <div class="span1" style="width:100px;min-height: 10px;font-weight: bold;font-size: 12px;margin-top: 10px;">Invoice Number:</div>
                        <div class="span8" style="width:100px;min-height: 15px;margin-top: 10px;" id="ao_sinum"></div>
                        <div class="clear"></div>
                    </div>        
                </div>
                <div style="width:30%;float:left">
                    <div class="row-form-booking-form">
                        <div class="span1" style="width:100px;min-height: 10px;font-weight: bold;font-size: 12px;margin-top: 10px;">Client Name:</div> 
                        <div class="span8" style="width:120px;min-height: 10px;margin-top: 10px;" id="clientname"></div>
                        <div class="clear"></div>
                    </div>
                </div>     
                <div style="width:30%;float:left">
                    <div class="row-form-booking-form">
                        <div class="span1" style="width:100px;min-height: 10px;font-weight: bold;font-size: 12px;margin-top: 10px;">Agency Name:</div> 
                        <div class="span8" style="width:120px;min-height: 10px;margin-top: 10px;" id="agencyname"></div>
                        <div class="clear"></div>
                    </div>
                </div>   
                <div style="width:100%;float:left">
                    <div class="row-form-booking-form">
                        <div class="span1" style="width:100px;min-height: 10px;font-weight: bold;font-size: 12px;margin-top: 10px;">Invoice Date:</div> 
                        <div class="span8" style="width:100px;min-height: 15px;margin-top: 10px;" id="invdate"></div> 
                        <div class="clear"></div>
                    </div>
                    <?php if ($canADD) : ?>
                    <input type="file" name="userfile" id="userfile" size="20" style="margin-top: 20px;"/>
                    <input type="submit" value="upload" id="uploaddata" class="btn btn-danger uploaddata" style="margin-top: 1px;"/>
                    <span style="color: red"><blink><?php echo $this->session->flashdata('errorupload'); ?></blink></span>
                    <?php endif; ?>   
                </div>  
                <div class="clear"></div>
            </div>
            <div class="block-fluid table-sorting" style="margin-top: 10px;min-height: 450px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                    <thead>
                        <tr>                       
                            <th colspan="2" width="3%">Action</th>  
                            <th width="20%">File Name</th>  
                            <th width="10%">File Type</th>  
                            <th width="10%">Upload By</th>  
                            <th width="10%">Upload Date</th>         
                            <th width="10%">Re-Upload By</th>  
                            <th width="10%">Re-Upload Date</th>                       
                        </tr>
                    </thead>
                    <tbody id="invoiceattachment">  
                    </tbody>
                </table>
                <div class="clear"></div>
            </div>
        </div>
    </div>            
    <div class="dr"><span></span></div>
</div>  

<script>

var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});

$('#search').click(function(){
    var $invnum = $("#invnum").val();  
    $.ajax({
        url: '<?php echo site_url('aiform/searchFile') ?>',
        type: 'post',
        data: {invnum: $invnum},  
        success: function(response){
            $response = $.parseJSON(response);
            if ($response['invalid']) {
                $('#clientname').html($response['info']['clientname']);
                $('#agencyname').html($response['info']['agencyname']);
                $('#invdate').html($response['info']['invdate']);
                $('#ao_sinum').html($response['info']['ao_sinum']);
                $('#invoiceattachment').html($response['invoiceattachment']);
            } else {
                window.location.href = "<?php echo base_url('aiform/uploading_of_invoicedata') ?>";
            }
        }    
    });   
}); 

$(".delete").click(function () {
    
    var $id = $(this).attr('id');
    var ans = window.confirm("Are you sure you want to delete?")

    if (ans)
    {
    window.location = "<?php echo site_url('aiform/removeDataUpload') ?>/"+$id; 
    return true;
    }
    else
    {
    window.alert("Are you sure you want to cancel?");
    return false;    
    }
    
});

$(".uploaddata").click(function () {
    
    var invnum = $("#invnum").val();   
    var userfile = $("#userfile").val();   
    var ans = window.confirm("Are you sure you want to upload?");
    
    if (ans) {
    
        var countValidate = 0;  
        var validate_fields = ['#userfile', '#invnum']; 

        for (x = 0; x < validate_fields.length; x++) {            
            if($(validate_fields[x]).val() == "") {                        
                $(validate_fields[x]).css(errorcssobj);          
                  countValidate += 1;
            } else {        
                  $(validate_fields[x]).css(errorcssobj2);       
            }        
        }
     
        if (countValidate == 0) {
            
            //window.alert("Successfully upload.");                                                  
        } 
        else { 
            
            window.alert("Required fields must fill up");           
            return false;
        }   
    } else {
        return false;
    } 
    
});
    



</script>

