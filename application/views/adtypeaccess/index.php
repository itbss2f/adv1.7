<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">

    <div class="row-fluid">

        <div class="span6">
        <div class="head">
            <div class="isw-list"></div>
            <h1>Not In A Group</h1>
            <div class="clear"></div>
        </div>
        <div class="block-fluid">                        
            
            <div class="row-form">
               <div class="span2">Group:</div>
               <div class="span9">
                <select class='select' name='group' id='group'>                
                    <option value=''>--</option>
                    <?php foreach ($group as $grp) : ?>
                    <option value="<?php echo $grp['id'] ?>"><?php echo $grp['adtypegroup_code'].' | '.$grp['adtypegroup_name'] ?></option>
                    <?php endforeach; ?>
                </select>
               </div>
               <div class="clear"></div>
            </div>                                            
                       
            
            <!-- <div class="row-form">        
               <div class="span3"><button class="btn btn-success" type="button" name="mass_paginate" id="mass_paginate">Paginate</button></div>
               <div class="span3"><button class="btn btn-danger" type="button" name="clear_mass_paginate" id="clear_mass_paginate">Clear Date</button></div>       
               <div class="clear"></div>
            </div>      -->
            <div class="block-fluid table-sorting">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable1">
                    <thead>
                    <tr>                       
                       <th width="15%">Code</th>
                       <th width="50%">AdtypeGroup Name</th>                                                              
                       <th width="10%">Action</th>
                    </tr>
                    </thead>
                    <tbody id="notingroup"></tbody>
                </table>
                <div class="clear"></div>
            </div>          
                      
        </div>
        </div>

        <div class="span6">
        <div class="head">
            <div class="isw-list"></div>
            <h1>In A Group</h1>        
            <div class="clear"></div>
        </div>
        <div class="block-fluid">                        
            <div class="row-form">
               <div class="span2"></div>
               <div class="span9">                
               </div>
               <div class="clear"></div>
            </div>                                            

            <div class="block-fluid table-sorting">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable2">
                    <thead>
                    <tr>                       
                       <th width="15%">Code</th>
                       <th width="50%">AdtypeGroup Name</th>                                                              
                       <th width="10%">Action</th> 
                    </tr>
                    </thead>
                    <tbody id="ingroup"></tbody>
                </table>
                <div class="clear"></div>
            </div>     
            
        </div>
    </div>                

    <div class="dr"><span></span></div>                

</div> 

<script>
$('#group').change(function() {        
    if ($('#group').val() == '') {
        $('#notingroup').html('');
        $('#ingroup').html('');
    } else {
    $.ajax({
        url: "<?php echo site_url('adtypeaccess/groupings')?>",
        type: "post",
        data: {groupid: $('#group').val()},
        success:function(response) {
            $response = $.parseJSON(response);

            $('#notingroup').html($response['notingroup']);
            $('#ingroup').html($response['ingroup']);
        }
    });
    }
});

function doAdtypeAccessGroup($id, $event) {

    $.ajax({
        url: "<?php echo site_url('adtypeaccess/groupadtypeaccess') ?>",
        type: "post",
        data: {groupid: $('#group').val(), id: $id, event: $event},
        success:function(response) {
            $response = $.parseJSON(response);

            $('#notingroup').html($response['notingroup']);
            $('#ingroup').html($response['ingroup']);        
        }
    });
}
</script>


