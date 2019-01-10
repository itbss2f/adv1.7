<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>File Uploading</h1>
                    
                <div class="clear"></div>
            </div>
            <?php echo form_open_multipart('file_upload/upload_data');?>                          
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width: 60px; margin-top:12px"><b>AO #:</b></div>
                <div class="span1" style="width: 80px; margin-top:12px"><input type="text" id="aonum" placeholder="Enter AO #" name="aonum" value="<?php echo $aonum ?>"/></div> 

                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="search" name="search" type="button">Search button</button></div>               
                <div class="clear"></div>
            </div>
            <div class="row-form-booking-form">
                <div style="width:100%;float:left">
                    <div class="row-form-booking-form">
                        <div class="span1" style="width:150px;min-height: 10px;font-weight: bold;font-size: 14px;">Information Details</div>
                        <div class="clear"></div>
                    </div> 
                </div>
                <div style="margin-top: 10px;width:40%;float:left">
                    <div class="row-form-booking-form">
                        <div class="span1" style="width:80px;min-height: 10px;font-weight: bold;font-size: 12px;">Client Name:</div>
                        <div class="span8 span_limit" style="min-height: 10px;" id="clientname"><?php echo $info['clientname'] ?></div>
                        <div class="clear"></div>
                    </div> 
                </div>
                <div style="margin-top: 10px;width:40%;float:left">
                    <div class="row-form-booking-form">
                        <div class="span1" style="width:100px;min-height: 10px;font-weight: bold;font-size: 12px;">Agency Name:</div> 
                        <div class="span8 span_limit" style="min-height: 10px;" id="agencyname"><?php echo $info['agencyname'] ?></div>
                        <div class="clear"></div>
                    </div> 
                </div>            
                <div style="margin-top: 10px;width:40%;float:left">
                    <div class="row-form-booking-form">
                        <div class="span1" style="width:60px;min-height: 10px;font-weight: bold;font-size: 12px;"><b>Adtype:</b></div> 
                        <div class="span8 span_limit" style="min-height: 10px;" id="adtype"><?php echo $info['adtype'] ?></div> 
                        <div class="clear"></div>
                    </div> 
                </div>
                <div style="margin-top: 10px;width:40%;float:left">
                    <div class="row-form-booking-form">
                        <div class="span1" style="width:60px;min-height: 10px;font-weight: bold;font-size: 12px;"><b>Entered by:</b></div> 
                        <div class="span8 span_limit" style="min-height: 10px;" id="book_by"></div> 
                        <div class="clear"></div>
                    </div> 
                </div>
                <div style="margin-top: 10px;width:40%;float:left">
                    <div class="row-form-booking-form">
                        <div class="span1" style="width:60px;min-height: 10px;font-weight: bold;font-size: 12px;"><b>Paytype:</b></div>
                        <div class="span8 span_limit" style="min-height: 10px;" id="paytype"><?php echo $info['paytype'] ?></div>
                        <div class="clear"></div>
                    </div>
                   
                    <?php if ($canADD) : ?>     
                    <input type="file" name="userfile" size="20" style="margin-top: 12px;"/>
                    <input type="submit" value="upload" id="upload" class="upload" style="margin-top: 12px;"/>
                    <span style="color: red"><blink><?php echo $this->session->flashdata('errorupload'); ?></blink></span>
                    <?php endif; ?>
                </form>
                   
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
                    <tbody id="fileattachment">  
                    <?php if (empty($list)) : ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: red; font-size: 20px;">No Record Found</td>
                        </tr>
                    <?php else : ?>
                    <?php endif; ?>

                    <?php 
                    $atts = array(
                                  'width'      => '3000',
                                  'height'     => '3000',
                                  'scrollbars' => 'yes',
                                  'status'     => 'yes',
                                  'resizable'  => 'yes',
                                  'screenx'    => '0',
                                  'screeny'    => '0'
                                );

                                //echo anchor_popup('news/local/123', 'Click Me!', $atts);        
                    ?>

                    <?php foreach ($list as $list) : ?>
                    <tr>
                        <td><?php echo anchor_popup('file_upload/viewfile/'.$list['id'], 'View', $atts) ?></td>
                        <td>
                        <?php if ($canDELETE) : ?>
                        <a href="<?php echo site_url('file_upload/removedata/'.$list['id'].'/'.$list['ao_num']) ?>" class="delete" id="delete" name="delete">Delete</a></td>
                        <?php endif; ?>
                        <td><?php echo $list['filename'] ?></td>
                        <td><?php echo $list['filetype'] ?></td>
                        <td><?php echo $list['username'] ?></td>
                        <td><?php echo $list['uploaddate'] ?></td>
                        <td><?php echo $list['username'] ?></td>
                        <td><?php echo $list['reuploaddate'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="clear"></div>
            </div>
        </div>
    </div>            
    <div class="dr"><span></span></div>
</div>  

<script>


var errorcssobj = {'background': '#EED3D7','border' : '1px solid #ff5b57'}; 
var errorcssobj2 = {'background': '#cee','border' : '1px solid #00acac'};

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});  

$('#search').click(function(){
    var $aonum = $("#aonum").val();  
    $.ajax({
        url: '<?php echo site_url('file_upload/searchFile') ?>',
        type: 'post',
        data: {aonum: $aonum},  
        success: function(response){
            $response = $.parseJSON(response);
            if ($response['invalid']) {   
            $('#clientname').html($response['info']['clientname']);
            $('#agencyname').html($response['info']['agencyname']);
            $('#adtype').html($response['info']['adtype']);
            $('#paytype').html($response['info']['paytype']);
            $('#book_by').html($response['info']['book_by']);
            $('#fileattachment').html($response['fileattachment']);
            } else {
                window.location.href = "<?php echo base_url('file_upload') ?>";        
            }
        }    
    });   
});

$(".upload").click(function () {
    
    var aonum = $("#aonum").val();   
    var userfile = $("#userfile").val();   
    var ans = window.confirm("Are you sure you want to upload?");
    
    if (ans) {
        var countValidate = 0;  
        var validate_fields = ['#aonum', '#userfile']; 

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

$(".delete").click(function () {
    
    var ans = window.confirm("Are you sure you want to delete?")

    if (ans)
    {
    window.alert("Successfully delete.");
    return true;
    }
    else
    {
    //window.alert("Are you sure you want to cancel?");
    return false;    
    }
    
});



</script>

