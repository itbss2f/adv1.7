<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">

    <div class="row-fluid">

        <div class="span6">
        <div class="head">
            <div class="isw-list"></div>
            <h1>Official Receipt Posting</h1>
            <div class="clear"></div>
        </div>
        <div class="block-fluid">                        
            
            <div class="row-form">
               <div class="span2">Date</div>
               <div class="span3"><input type="text" class="datepicker" placeholder="From" name="fromdate" id="fromdate"></div>
               <div class="span3"><input type="text" class="datepicker" placeholder="To" name="todate" id="todate"></div>
               <div class="clear"></div>
            </div>                         
            
            <div class="row-form">        
               <div class="span2"><button class="btn btn-success" type="button" name="mass_posted" id="mass_posted">Post OR</button></div> 
               <div class="clear"></div>
            </div>     
            <div class="block-fluid table-sorting">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable1">
                    <thead>
                    <tr>                       
                       <th width="15%"># Counter</th>
                       <th width="20%">OR Number</th>
                       <th width="20%">OR Date</th>                                                              
                       <th width="20%">Amount</th>                                                              
                       <th width="20%">Assign Amount</th>                                                              
                    </tr>
                    </thead>
                    <tbody id="mass_result"></tbody>
                </table>
                <div class="clear"></div>
            </div>          
                      
        </div>
        </div>
        <div class="span4">
        <div class="head">
            <div class="isw-list"></div>
            <h1>Official Receipt Posting</h1>
            <div class="clear"></div>
        </div>
        <div class="block-fluid">                        
            <div class="row-form">
               <div class="span3">OR Number:</div>
               <div class="span3"><input type="text" placeholder="#######" name="ornumber" id="ornumber"></div>
               <div class="clear"></div>
            </div>  
            <div class="row-form">        
               <div class="span3"><button class="btn btn-success" type="button" name="posted" id="posted">Post OR</button></div> 
               <div class="span4"><button class="btn btn-success" type="button" name="unposted" id="unposted">Unpost OR</button></div> 
               <div class="clear"></div>
            </div>  
            <div class="block-fluid table-sorting">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable1">
                    <thead>
                    <tr>                       
                       <th width="20%">OR Number</th>                                                           
                       <th width="20%">Remarks</th>                                                                                                                       
                    </tr>
                    </thead>
                    <tbody id="rest"></tbody>
                </table>
                <div class="clear"></div>
            </div>                                    
        </div>
        </div>

        <!--<div class="span6">
        <div class="head">
            <div class="isw-target"></div>
            <h1>Single Pagination / Unpagination</h1>
            <div class="clear"></div>
        </div>
        <div class="block-fluid">                        
            <div class="row-form">
               <div class="span10">
                <span class="label label-info" style="color:#fff">Use this module to unpaginate</span>
               </div>
               <div class="clear"></div>
            </div> 
            <div class="row-form">
               <div class="span2">AO Number</div>
               <div class="span3"><input type="text" class="text" name="adnumber" id="adnumber"></div>
               <div class="span2">Issue Date</div>
               <div class="span3"><input type="text" class="text" name="issuedate" id="issuedate"></div>
               <div class="clear"></div>
            </div>                          
            
            <div class="row-form">        
               <div class="span3"><button class="btn btn-success" type="button" name="single_paginate" id="single_paginate">Paginate</button></div>
               <div class="span3"><button class="btn btn-danger" type="button" name="single_unpaginate" id="single_unpaginate">UnPaginate</button></div>       
               <div class="clear"></div>
            </div> 

            <div class="block-fluid table-sorting">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable2">
                    <thead>
                    <tr>                       
                       <th width="25%">AO Number</th>
                       <th width="30%">Issue Date</th>                                                              
                       <th width="50%">Remarks</th> 
                    </tr>
                    </thead>
                    <tbody id="single_result"></tbody>
                </table>
                <div class="clear"></div>
            </div>     
            
        </div> -->
    </div>                

    <div class="dr"><span></span></div>                

</div>  

<script>
//$('#tSortable1, #tSortable2').dataTable({});

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});

$("#posted").click(function() {
    var ans = confirm('Are you sure you want to post this OR?');

    if (ans) {
        var ornum = $("#ornumber").val();
        
        if (ornum == "") {
            alert('OR Number must not be empty!');
            return true;
        } else {
            
            $.ajax({
                url: "<?php echo site_url('orposting/postthissingleor') ?>",
                type: "post",
                data: {ornum: ornum},
                success: function (response) {
                    var $response =$.parseJSON(response);
                    
                    alert($response['msg']);   
                    $('#rest').html($response['view']); 
                }    
            });                     
        }
    }    
});

$("#unposted").click(function() {
    var ans = confirm('Are you sure you want to unposted this OR?');

    if (ans) {
        var ornum = $("#ornumber").val();
        
        if (ornum == "") {
            alert('OR Number must not be empty!');
            return true;
        } else {
            
            $.ajax({
                url: "<?php echo site_url('orposting/unpostthissingleor') ?>",
                type: "post",
                data: {ornum: ornum},
                success: function (response) {
                    var $response =$.parseJSON(response);
                    
                    alert($response['msg']);   
                    $('#rest').html($response['view']); 
                }    
            });                     
        }
    }    
});

$("#mass_posted").click(function(){
    var $fromdate = $("#fromdate").val();
    var $todate = $("#todate").val();

    var ans = confirm ("Are you sure you want this OR list to be posted?");
    
    if (ans) {

        if ($fromdate == "" || $todate == "") {
            alert ("Date must not be empty!");
            return false;
        } else {
        
            if ($fromdate > $todate) {
                    alert ("The to date must be greater than your from date");
                    return false;
            }    
            $.ajax({
                url: "<?php echo site_url('orposting/postthisor') ?>",
                type: "post",
                data: {fromdate: $fromdate, todate: $todate},
                success: function(response) {
                    var $response = $.parseJSON(response);
                    
                    $('#mass_result').html($response['viewresult']);             
                }
            });  
        }
    }

});

</script>
