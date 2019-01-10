<div class="block-fluid">      
    <?php echo form_open_multipart('material_upload/upload_data/'.$id);?>                          
        <div class="row-form-booking">
            <div class="span2" style="width:80px"><b>Materials</b></div>       
            <input type="file" name="userfile" size="20"/>
            <input type="hidden" name="product" value="<?php echo $data['product'] ?>"/>
            <input type="hidden" name="datefrom" value="<?php echo $data['datefrom'] ?>"/>
            <div class="clear"></div>
        </div>    
        <div class="row-form-booking">     
            <div class="span2" style="width:50px"><b>Remarks</b></div>        
            <div class="span2" style="width: 201px;"><textarea class="text" id="material_remarks" name="material_remarks" rows="8"><?php echo $data['material_remarks'] ?></textarea></div>                            
            <div class="clear"></div>
        </div>
        <div class="row-form-booking">    
            <input type="submit" value="upload" id="upload" class="upload" style="margin-left : 330px;"/>        
            <div class="clear"></div>    
        </div>
    </form>
</div>
<script>

var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

$(".upload").click(function () {
    
    var userfile = $("#userfile").val();   
    var material_remarks = $("#material_remarks").val();   
    var ans = window.confirm("Are you sure you want to upload?");
    
    if (ans) {
        var countValidate = 0;  
        var validate_fields = ['#userfile']; 

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
