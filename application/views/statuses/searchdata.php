<div class="block-fluid">      
    <form action="<?php echo site_url('status/search') ?>" method="post" name="formsearch" id="formsearch"> 
    
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
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Save Status button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();     
});
</script>
