<div class="block-fluid">      
    <form action="<?php echo site_url('departments/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Dept Code</b></div>    
        <div class="span1"><input type="text" name="dept_code" id="dept_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Description</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="dept_name" id="dept_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Dept Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="mdept_name" id="mdept_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Dept Branch Status</b></div>    
        <div class="span3" style="width:190px">
            <select name="dept_branchstatus" id="dept_branchstatus"></div>                        
                <option value="N">NO</option>                                           
                <option value="Y">YES</option>                
            </select>
        </div>  
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Section Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="sect_name" id="sect_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Expenses Type</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="exp_type" id="exp_type"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Department</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();
});
</script>
