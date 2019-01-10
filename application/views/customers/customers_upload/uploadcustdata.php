
<div class="breadLine"> 
    <?php echo $breadcrumb; ?> 
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Customer Data Uploading</h1>
                <div class="clear"></div>
            </div> 
            <?php echo form_open_multipart('customer/uploading');?>                          
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div style="width:100%;float:left">
                    <div class="row-form-booking-form">
                        <input type="hidden" id="custid" name="custid" value="<?php echo $info['id'] ?>"/>
                        <div class="clear"></div>
                    </div> 
                </div>
                <div style="width:40%;float:left">
                    <div class="row-form-booking-form">  
                        <div class="span1" style="width:80px;min-height: 10px;font-weight: bold;font-size: 12px;margin-top: 10px;">Code:</div>
                        <div class="span8 span_limit" style="min-height: 10px;margin-top: 10px;margin-bottom: 12px;font-size: 12px;"><?php echo $info['cmf_code']?></div>
                        <div class="clear"></div>
                    </div>
                    
                    <?#php if ($canADD) : ?>
                    <input type="file" name="userfile" id="userfile" size="20" style="margin-top: 12px;"/>
                    <input type="submit" value="upload" id="uploaddata" class="uploaddata" style="margin-top: 1px;"/>
                    <span style="color: red"><blink><?php echo $this->session->flashdata('errorupload'); ?></blink></span>
                    <?#php endif; ?> 
                    
                </div>
                <div style="width:40%;float:left">
                    <div class="row-form-booking-form">
                        <div class="span1" style="width:100px;min-height: 10px;font-weight: bold;font-size: 12px;margin-top: 10px;">Client Name:</div> 
                        <div class="span8 span_limit" style="min-height: 10px;margin-top: 10px;font-size: 12px;"><?php echo $info['cmf_name']?></div>
                        <div class="clear"></div>
                    </div> 
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
                    
                    <?php if (empty($list)) : ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: red; font-size: 20px;">No Record Found</td>
                        </tr>

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
                        <td><?php echo anchor_popup('customer/viewcustdatafile/'.$list['id'], 'View', $atts) ?></td>
                        <td>
                        <?php if ($canDELETE) : ?>
                        <a href="#" class="delete" id="<?php echo $list['id'].'/'.$list['custid'] ?>" name="delete">Delete</a>
                        <?php endif; ?>
                        </td>
                        <td><?php echo $list['filename'] ?></td>
                        <td><?php echo $list['filetype'] ?></td>
                        <td><?php echo $list['username'] ?></td>
                        <td><?php echo $list['uploaddate'] ?></td>
                        <td><?php echo $list['username'] ?></td>
                        <td><?php echo $list['reuploaddate'] ?></td>
                    </tr>
                    <?php endforeach; ?>
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

$(".delete").click(function () {
    
    var $id = $(this).attr('id');
    var ans = window.confirm("Are you sure you want to delete?")

    if (ans)
    {
    window.location = "<?php echo site_url('customer/removeDataUpload') ?>/"+$id; 
    return true;
    }
    else
    {
    window.alert("Are you sure you want to cancel?");
    return false;    
    }
    
});

$(".uploaddata").click(function () {
    
    var custid = $("#custid").val();   
    var userfile = $("#userfile").val();   
    var ans = window.confirm("Are you sure you want to upload?");
    
    if (ans) {
    
        var countValidate = 0;  
        var validate_fields = ['#custid', '#userfile']; 

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

