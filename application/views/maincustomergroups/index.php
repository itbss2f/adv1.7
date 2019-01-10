<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">

    <div class="row-fluid">

        <div class="span6">
        <div class="head">
            <div class="isw-list"></div>
            <h1>MAIN CUSTOMER GROUP</h1>
            <div class="clear"></div>
        </div>
        <div class="block-fluid">                        
            
            <div class="row-form">
               <div class="span2">Main Customer:</div>
               <div class="span9">
                <select class='select' name='maincustomer' id='maincustomer'>                
                    <option value=''>--</option>
                    <?php
                    foreach ($maincustomer as $maincustomer) : ?>
                    <option value="<?php echo $maincustomer['id']?>"><?php echo $maincustomer['cmfgroup_code'].' | '.$maincustomer['cmfgroup_name'] ?></option>
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
                       <th width="15%">Client Code</th>
                       <th width="50%">Client Name</th>                                                              
                       <th width="10%">Action</th>
                    </tr>
                    </thead>
                    <tbody id="clientnoagency"></tbody>
                </table>
                <div class="clear"></div>
            </div>          
                      
        </div>
        </div>

        <div class="span6">
        <div class="head">
            <div class="isw-list"></div>
            <h1>CLIENT UNDER THIS MAIN CUSTOMER</h1>
            <div class="clear"></div>
        </div>
        <div class="block-fluid">                        
            <div class="row-form">
               <div class="span2">Search Client</div>
               <div class="span2">
                    <select name="s_type" id="s_type">
                        <option value="1">NMC - No Main Customer</option>
                        <option value="2">UMC - Under Main Customer</option>
                    </select>
               </div>                 
               <div class="span2"><input type="text" name="s_code" id="s_code" placeholder="Code"></div>
               <div class="span4"><input type="text" name="s_name" id="s_name" placeholder="Name"></div>
               <div class="span2"><button name="s_search" id="s_search"  class="btn btn-success">Search</button></div>  
               <div class="clear"></div>
            </div>                                                                                       

            <div class="block-fluid table-sorting">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable2">
                    <thead>
                    <tr>                       
                       <th width="15%">Client Code</th>
                       <th width="50%">Client Name</th>                                                              
                       <th width="10%">Action</th> 
                    </tr>
                    </thead>
                    <tbody id="underclient"></tbody>
                </table>
                <div class="clear"></div>
            </div>     
            
        </div>
    </div>                

    <div class="dr"><span></span></div>                

</div> 

<script> 
$("#maincustomer").change(function(){
   var maincustomerid = $('#maincustomer').val();
   
   $.ajax({
       url: '<?php echo site_url('maincustomergroup/clientlist') ?>',
       type: 'post',
       data: {maincustomerid: maincustomerid},
       success: function(response) {
           var $response = $.parseJSON(response);
           $('#clientnoagency').html($response['clientnoagency']);
           $('#underclient').html($response['underclient']);           
       }
   })
});

function doAgencyClient(id, event) {
    var maincustomerid = $('#maincustomer').val();
    $.ajax({
        url: '<?php echo site_url('maincustomergroup/ajxDoAgencyClient')?>',
        type: 'post',
        data: {id: id, event:event, maincustomerid: maincustomerid},
        success: function(response) {
            var $response = $.parseJSON(response);
            $('#clientnoagency').html($response['clientnoagency']);
            $('#underclient').html($response['underclient']);     
        }
    });
}

$('#s_search').click(function() {
    var maincustomerid = $('#maincustomer').val();              
    $.ajax({
        url: "<?php echo site_url('maincustomergroup/search') ?>",
        type: "post",
        data: {id: maincustomerid,
               type: $('#s_type').val(),
               code: $('#s_code').val(),
               name: $('#s_name').val()},
        success: function (response) {
            var $response = $.parseJSON(response);
            $('#clientnoagency').html($response['clientnoagency']);
            $('#underclient').html($response['underclient']);         
        }    
    });
});
</script>

