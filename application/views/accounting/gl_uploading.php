<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">

    <div class="row-fluid">

        <div class="span4">
                <div class="head">
                    <div class="isw-target"></div>
                    <h1>General Ledger Uploading Data</h1>
                    <div class="clear"></div>
                </div>
                <?php #echo form_open_multipart('gl_datauploading/uploaddata');?>     
                <form action="<?php echo site_url('gl_datauploading/uploaddata') ?>" id="glproc" name="glproc" method="post" enctype="multipart/form-data">                     
                <div class="block-fluid">                        
                    <div class="row-form">
                       <div class="span3">Upload Type</div>
                       <div class="span3">
                       <select id="uploadtype" name="uploadtype">
                        <option value="OR">OR</option>
                       </select>
                       </div>
                       <div class="clear"></div>      
                    </div>   
                    
                    <div class="row-form">     
                        <div class="span3">Upload Date</div>
                        <div class="span3"><input type="text" class="text" name="uploaddate" id="uploaddate"></div>
                        <div class="clear"></div>      
                    </div>   
                    
                    <div class="row-form">     
                        <div class="span3">Path File</div>
                        <div class="span8"><input type="file" name="userfile" id="userfile"/></div>
                        <div class="clear"></div>      
                    </div>       

                    <div class="row-form">        
                       <div class="span4"><button class="btn btn-success" type="button" name="processgl" id="processgl">Process GL</button></div>
                       <div class="span3" style="color: green"><blink><?php echo $this->session->flashdata('process'); ?></blink></div>
                       <div class="clear"></div>
                    </div> 
                </div>
                </form>
        </div>   
        <div class="span4">
                <div class="head">
                    <div class="isw-target"></div>
                    <h1>Subsidiary Ledger Uploading Data</h1>
                    <div class="clear"></div>
                </div>
                <?php #echo form_open_multipart('gl_datauploading/uploaddata');?>     
                <form action="<?php echo site_url('gl_datauploading/uploadsl') ?>" id="slproc" name="slproc" method="post">                     
                <div class="block-fluid">                        
                    <div class="row-form">
                       <div class="span3">Upload Type</div>
                       <div class="span3">
                       <select id="uploadtypesl" name="uploadtypesl">
                        <option value="OR">OR</option>
                       </select>
                       </div>
                       <div class="clear"></div>      
                    </div>   
                    
                    <div class="row-form">     
                        <div class="span3">Upload Date</div>
                        <div class="span3"><input type="text" class="text" name="uploaddatesl" id="uploaddatesl"></div>
                        <div class="clear"></div>      
                    </div>   
                    
                    <div class="row-form">     
                        <div class="span3"></div>
                        <div class="span8"></div>
                        <div class="clear"></div>      
                    </div>       

                    <div class="row-form">        
                       <div class="span4"><button class="btn btn-success" type="button" name="processsl" id="processsl">Process SL</button></div>
                       <div class="span3" style="color: green"><blink><?php echo $this->session->flashdata('process2'); ?></blink></div>
                       <div class="clear"></div>
                    </div> 
                </div>
                
        </div>                             
    </div>
    <div class="dr"><span></span></div>                

</div>  

<script>
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$('#processgl').click(function(){
    var countValidate = 0;  
    var validate_fields = ['#uploaddate', '#uploaddate', '#userfile'];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) {
        $('#glproc').submit();    
    } else {            
        return false;
    }        
});

$('#processsl').click(function(){
    var countValidate = 0;  
    var validate_fields = ['#uploaddatesl', '#uploaddatesl'];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) {
        $('#slproc').submit();    
        
    } else {            
        return false;
    }        
});

$("#uploaddate").datepicker({dateFormat: 'yy-mm-dd'});  
$("#uploaddatesl").datepicker({dateFormat: 'yy-mm-dd'});  
</script>
