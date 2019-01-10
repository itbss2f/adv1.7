<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">

    <div class="row-fluid">

        <div class="span6">
        <div class="head">
            <div class="isw-list"></div>
            <h1>Client with No Agency</h1>
            <div class="clear"></div>
        </div>
        <div class="block-fluid">                        
            
            <div class="row-form">
               <div class="span2">Agency:</div>
               <div class="span9">
                <select class='select' name='agency' id='agency' style="width: 100%;">                
                    <option value=''>--</option>
                    <?php
                    foreach ($agency as $agency) : ?>
                    <option value="<?php echo $agency['id']?>"><?php echo $agency['cmf_code'].' | '.$agency['cmf_name'] ?></option>
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
            <h1>Client under this Agency</h1>
            <div class="clear"></div>
        </div>
        <div class="block-fluid">                        
            <div class="row-form">
               <div class="span2">Search Client</div>
               <div class="span2">
                    <select name="s_type" id="s_type">
                        <option value="1">NA - No Agency</option>
                        <option value="2">UA - Under Agency</option>
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
                       <th width="10%">PPD Percent</th>                                                              
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
<div id="modal_ppddata" title="Agency Client PPD Data"></div>  
<script> 

$('#modal_ppddata').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: 500,    
    height: 'auto',
    modal: true,
    resizable: false
});       

$("#agency").select2();
$("#agency").change(function(){
   var agencyid = $('#agency').val();
   
   $.ajax({
       url: '<?php echo site_url('agencyclient/clientlist') ?>',
       type: 'post',
       data: {agencyid: agencyid},
       success: function(response) {
           var $response = $.parseJSON(response);
           $('#clientnoagency').html($response['clientnoagency']);
           $('#underclient').html($response['underclient']);           
       }
   })
});

function doAgencyClient(id, event) {
    var agencyid = $('#agency').val();
    $.ajax({
        url: '<?php echo site_url('agencyclient/ajxDoAgencyClient')?>',
        type: 'post',
        data: {id: id, event:event, agencyid: agencyid},
        success: function(response) {
            var $response = $.parseJSON(response);
            $('#clientnoagency').html($response['clientnoagency']);
            $('#underclient').html($response['underclient']);     
        }
    });
}

$('#s_search').click(function() {
    var agencyid = $('#agency').val();              
    $.ajax({
        url: "<?php echo site_url('agencyclient/search') ?>",
        type: "post",
        data: {id: agencyid,
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

