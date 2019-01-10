<div class="block-fluid">      
    <form action="<?php echo site_url('status/save') ?>" method="post" name="formsave" id="formsave"> 
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Code</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="status_code" id="status_code"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="status_name" id="status_name"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Agency</b></div>    
        <div class="span2" style="width:190px">
        <select name="status_agency" id="status_agency">
        <option value = "">--</option>
        <option value = "Y">YES</option>
        <option value = "N">NO</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Client</b></div>    
        <div class="span2" style="width:190px">
        <select name="status_client" id="status_client">
        <option value = "">--</option>
        <option value = "Y">YES</option>
        <option value = "N">NO</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Agent</b></div>    
        <div class="span2" style="width:190px">
        <select name="status_agent" id="status_agent">
        <option value = "">--</option>
        <option value = "Y">YES</option>
        <option value = "N">NO</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Subscriber</b></div>    
        <div class="span2" style="width:190px">
        <select name="status_subscriber" id="status_subscriber">
        <option value = "">--</option>
        <option value = "Y">YES</option>
        <option value = "N">NO</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Supplier</b></div>    
        <div class="span2" style="width:190px">
        <select name="status_supplier" id="status_supplier">
        <option value = "">--</option>
        <option value = "Y">YES</option>
        <option value = "N">NO</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Employee</b></div>    
        <div class="span2" style="width:190px">
        <select name="status_employee" id="status_employee">
        <option value = "">--</option>
        <option value = "Y">YES</option>
        <option value = "N">NO</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
     
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Status button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#edition_totalccm").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#status_code', '#status_name'];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) {
          $('#formsave').submit();  
    } else {            
        return false;
    }    
});
</script>
