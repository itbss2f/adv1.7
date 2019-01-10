<div id='booking_main' style='width:400px'>   
    <div id='booking_menu'>    
        <ul class='booking_buttons'>                        
            <li><button title='Search RFA' style='margin-top: 2px;' name='search' id='search'><span class='x-icon x-icon-search'>Search</span></button></li>                            
        </ul>                    
    </div>     
    <div class='booking_field left'>  
        <div class='booking_field_group'>
            <dl>
                <dt style='width:100px'>Complaint</dt>
                <dd style='width:100px'><input type='text' name='complaint' id='complaint' style='width:240px;'></dd>
            </dl>     
            <dl>
                <dt style='width:100px'>Advertiser Name</dt>
                <dd><input type='text' name='advertisername' id='advertisername' style='width:240px;'></dd>
            </dl>
            <dl>
                <dt style='width:100px'>Agency Name</dt>
                <dd><input type='text' name='agencyname' id='agencyname' style='width:240px;'></dd>
            </dl>
            <dl>
                <dt style='width:100px'>Account Executive</dt>
                <dd><select class='select' name='accountexec' id='accountexec' style='width:240px;'>
                <option value=''>--</option>
                    <?php
                    foreach ($aelist as $aelist) : ?>
                    <option value='<?php echo $aelist['user_id']?>'><?php echo $aelist['employee'] ?></option>
                    <?php
                    endforeach;
                    ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt style='width:100px'>Invoice No.</dt>
                <dd><input type='text' name='invoiceno' id='invoiceno' style='width:80px;'></dd>
            </dl>
            <dl>
                <dt style='width:62px'>Issue Date</dt>
                <dd style='width:100px'>Form: <input type='text' name='issuedatefrom' id='issuedatefrom' style='width:80px;'></dd>
                <dd style='width:100px'>To: <input type='text' name='issuedateto' id='issuedateto' style='width:80px;'></dd>                
            </dl>
            <dl>
                <dt style='width:100px'>RFA No.</dt>
                <dd><input type='text' name='rfano' id='rfano' style='width:80px;'></dd>
            </dl>
            <dl>
                <dt style='width:62px'>RFA Date</dt>
                <dd style='width:100px'>Form: <input type='text' name='rfadatefrom' id='rfadatefrom' style='width:80px;'></dd>
                <dd style='width:100px'>To: <input type='text' name='rfadateto' id='rfadateto' style='width:80px;'></dd>                
            </dl>
            <dl>
                <dt style='width:250px'>Person / Agency / Client Responsible</dt>
            </dl>
            <dl>
                <dd style='width:80px'><select name='searchperson' id='searchperson' style='width:80px'>
                                            <option value=''>--</option>                       
                                            <option value='P'>Person</option>
                                            <option value='A'>Agency</option>
                                            <option value='C'>Client</option>
                                            <option value='O'>Others</option>
                                       </select>
                </dd>
                <dd style='width:150px'><input type='text' name='responsible' id='responsible' style='width:250px;'></dd>
            </dl>
        </div>    
    </div>
</div>
<div id='booking_main' style='width:700px;'>     
    <div class='booking_field_group'> 
        <div id="raftabs" style="min-height: 450px;">
            <ul>
                <li><a href="#searchresult">Search Result</a></li>                                
            </ul>
            <div id="rfatable">
                <div style='white-space: nowrap;overflow:auto; height: 410px;'>
                    <dl class='thead' style='font-size:12px;'>
                        <dt style='width:80px'>AO No.</dt>     
                        <dt style='width:80px'>Issue Date</dt>     
                        <dt style='width:80px'>RFA No.</dt>     
                        <dt style='width:80px'>RFA Date</dt>     
                        <dt style='width:200px'>Advertiser Name</dt>
                        <dt style='width:200px'>Agency Name</dt>    
                        <dt style='width:150px'>AE</dt>    
                        <dt style='width:80px'>Invioce No.</dt>     
                        <dt style='width:80px'>Invoice Date</dt> 
                        <dt style='width:200px'>RFA Findings</dt>    
                    </dl>
                    <div id='searchresult'>
                         <span class='error' style='font-size:15px'>No Result</span>
                    </div>
                </div>                
            </div>        
        </div>
    </div>
</div>
<div id='ai_rfa_view' title='Request For Adjustment'></div>    
<script>
$(function() {
    $('#ai_rfa_view').dialog({
        autoOpen: false, 
        closeOnEscape: false,
        draggable: true,
        width: 650,    
        height:600,
        modal: true,
        resizable: false
    }); 
    
    $('#issuedatefrom, #issuedateto, #rfadatefrom, #rfadateto').datepicker({dateFormat: 'yy-mm-dd'});
    
    $( "#raftabs" ).tabs();
    
    $('#search').click(function(){
         $.ajax({
            url: '<?php echo site_url('rfa/searchRFA') ?>',
            type: 'post',
            data: { complaint: $(":input[name='complaint']").val(),
                    advertisername: $(":input[name='advertisername']").val(),   
                    agencyname: $(":input[name='agencyname']").val(),   
                    accountexec: $(":input[name='accountexec']").val(),   
                    invoiceno: $(":input[name='invoiceno']").val(),   
                    issuedatefrom: $(":input[name='issuedatefrom']").val(),   
                    issuedateto: $(":input[name='issuedateto']").val(),   
                    rfano: $(":input[name='rfano']").val(),   
                    rfadatefrom: $(":input[name='rfadatefrom']").val(),   
                    rfadateto: $(":input[name='rfadateto']").val(),
                    person: $(":input[name='searchperson']").val(),
                    responsible: $(":input[name='responsible']").val()
                  },
            success: function(response) {
                var $response = $.parseJSON(response);
                
                $('#searchresult').html($response['searchresult']);    
            }
        }) 
    });
    
    
});
</script>