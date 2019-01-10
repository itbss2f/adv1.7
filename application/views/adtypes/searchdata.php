<div class="block-fluid">      
    <form action="<?php echo site_url('adtype/search') ?>" method="post" name="formsearch" id="formsearch"> 
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype Code</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="adtype_code" id="adtype_code"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="adtype_name" id="adtype_name"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype Type</b></div>    
        <div class="span2" style="width:190px">
        <select name="adtype_type" id="adtype_type">
        <option value = "">--</option>
        <option value = "D">Displayed</option>
        <option value = "C">Classified</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype Catad</b></div>    
        <div class="span2" style="width:190px">
        <select name="adtype_catad" id="adtype_catad">
             <option value="">--</option>
                <?php foreach ($caf as $caf1) : ?>
                <option value="<?php echo $caf1['id'] ?>"><?php echo $caf1['catad_name'] ?></option>
                <?php endforeach; ?>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Class</b></div>    
        <div class="span2" style="width:190px">
        <select name="adtype_class" id="adtype_class">
            <option value="">--</option>
            <?php foreach ($row as $row) : ?>
            <option value="<?php echo $row['id'] ?>"><?php echo $row['class_name'] ?></option>
            <?php endforeach; ?>
        </select>
        </div>        
        <div class="clear"></div>    
    </div> 
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>AR Account</b></div>    
        <div class="span2" style="width:190px">
        <select name="adtype_araccount" id="adtype_araccount">
            <option value="">--</option>
            <?php foreach ($des as $des) : ?>
            <option value="<?php echo $des['id'] ?>"><?php echo $des['acct_des'] ?></option>
            <?php endforeach; ?>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>    
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Ad Type button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>                                                                    
 
$("#search").click(function() {
          $('#formsearch').submit();      
});

</script>
