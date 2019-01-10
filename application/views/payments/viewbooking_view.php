<div class="block-fluid">

    <div class="row-form-booking">
        <div class="span1" style="width:50px">AO No.</div>
        <div class="span1"><input type="text" id="b_aono" name="b_aono"/></div>    
        <div class="span1" style="width:60px">Payee</div>             
        <div class="span1"><input type="text" placeholder="Code" id="b_payeecode" name="b_payeecode"/></div>    
        <div class="span3" style="width: 200px;"><input type="text" placeholder="Name" id="b_payeename" name="b_payeename"/></div>    
        <div class="clear"></div>
    </div>
    
    <div class="row-form-booking">
        <div class="span1" style="width:50px">Invoice#</div>
        <div class="span1"><input type="text" id="b_invoiceno" name="b_invoiceno"/></div>    
        <div class="span1" style="width:60px">Agency</div>             
        <div class="span1"><input type="text" placeholder="Code" id="b_agencycode" name="b_agencycode"/></div>    
        <div class="span3" style="width: 200px;"><input type="text" placeholder="Name" id="b_agencyname" name="b_agencyname"/></div>    
        <div class="clear"></div>
    </div>
    
    <div class="row-form-booking">    
        <div class="span1" style="width:60px">Issue Date</div>
        <div class="span1"><input type="text" placeholder="From" class='b_datepicker' id="b_issuefrom" name="b_issuefrom" style="width:80px"/></div>    
        <div class="span1"><input type="text" placeholder="To" class='b_datepicker' id="b_issueto" name="b_issueto" style="width:80px"/></div>    
        <div class="span1" style="width:70px">Client Type</div>  
        <div class="span2">
            <select id="b_clienttype" name="b_clienttype">
                <option value="">All</option>
                <option value="D">Display</option>
                <option value="C">Classifieds</option>
                <option value="M">Superced</option>
            </select>
        </div>  
        <div class="clear"></div>
    </div>
    
    <div class="row-form-booking">  
        <div class="span1" style="width:50px">PO No.</div>   
        <div class="span2"><input type="text" placeholder="Po No. / Contract / Ref No." id="b_pono" name="b_pono"/></div>       
        <div class="span1" style="width:70px">Pay Type</div>  
        <div class="span2">
            <select id="b_paytype" name="b_paytype">
                <option value=""> -- </option>
                <?php foreach ($paytype as $paytype) : ?>
                    <?php #if ($paytype['id'] == '2' || $paytype['id'] == '1') : ?>
                    <option value="<?php echo $paytype['id'] ?>"><?php echo $paytype['paytype_name'] ?></option>   
                    <?php #endif; ?>
                <?php endforeach; ?>
            </select>
        </div>  
        <div class="clear"></div>
    </div>

</div>
<div class="row-form-booking">                            
    <div class="span2">
      <button class="btn btn-block" type="button" id="search_booking" name="search_booking">Search</button>
    </div>
    <div class="span2">
      <button class="btn btn-block" type="button" id="load_booking" name="load_booking">Load as Client</button>
    </div>   
    <div class="span2">                             
      <button class="btn btn-block" type="button" id="load_booking2" name="load_booking2">Load as Agency</button>
    </div>                           
    <div class="clear"></div>
</div>  
<div class="block-fluid">    
    <div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:350px"> 
        <table cellpadding="0" cellspacing="0" style="white-space:nowrap;width:100%" class="table" id="viewbookingtable">
            <thead>
                <tr>                        
                    <th width="10px"></th>                    
                    <th width="40px">AO No.#</th>
                    <th width="40px">Invoice #</th>                                                                                    
                    <th width="40px">Issue Date</th> 
                    <th width="80px">Client</th>                                    
                    <th width="80px">Agency</th>                                   
                    <th width="40px">Size</th>                                                          
                    <th width="40px">PO No.</th>    
                    <th width="40px">Pay Type</th>                                                                                                                        
                </tr>
            </thead>
            <tbody class="booklist">                                                                             
            </tbody>
        </table>
        <div class="clear"></div>
    </div>
</div>

<script>

$(".b_datepicker").datepicker({dateFormat: 'yy-mm-dd'});  

$("#load_booking").click(function() {
    var loadid = $('.bookid:checked').val();
    if(typeof loadid != 'undefined') {
        $.ajax({
            url: "<?php echo site_url('payment/loaddefaultdata'); ?>",
            type: "post",
            data: {id: loadid},
            success: function(response) {
                
                $response = $.parseJSON(response);

                $("#aonumrev").val($response['data']['ao_num']);      
                $("#adtype").val($response['data']['ao_adtype']).attr('disabled');           
                $("#payeecode").val($response['data']['ao_cmf']);
                $("#payeename").val($response['data']['ao_payee']);
                $("#address1").val($response['data']['ao_add1']);
                $("#address2").val($response['data']['ao_add2']);
                $("#address3").val($response['data']['ao_add3']);
                $("#tin").val($response['data']['ao_tin']);
                $("#zipcode").val($response['data']['ao_zip']);
                $("#tel1prefix").val($response['data']['ao_telprefix1']);
                $("#tel1").val($response['data']['ao_tel1']);
                $("#tel2prefix").val($response['data']['ao_telprefix2']);
                $("#tel2").val($response['data']['ao_tel2']);
                $("#celprefix").val($response['data']['ao_celprefix']);
                $("#cel").val($response['data']['ao_cel']);
                $("#faxprefix").val($response['data']['ao_faxprefix']);
                $("#fax").val($response['data']['ao_fax']);
                $("#particulars").val($response['part']['part']);
                
                $("#vatcode").val($response['data']['ao_cmfvatcode']);
                
                
                $("#comments").val('AO#'+$response['data']['ao_num']+' Amount: '+$response['data']['totalamt']+' '+'VAT: '+$response['data']['ao_cmfvatrate']);      
                //$("#amountpaid").val($response['data']['ao_amt']);      
                      
                
                if ($response['data']['ao_paytype'] == 3 || $response['data']['ao_paytype'] == 4 || $response['data']['ao_paytype'] == 5) {
                    
                    //alert($response['data']['ao_paytype']);
                    $('#ortype').val(2);
                    $("#assignedamount").val($response['data']['totalamt']);
                } else {
                    $('#ortype').val(1);     
                }
                
                $("#viewbooking_view").dialog('close');
                
                      
                
            }   
        });
    } else { alert("Select to load client!"); return false;}  
});

$("#load_booking2").click(function() {
    var loadid = $('.bookid:checked').val();
    if(typeof loadid != 'undefined') {
        $.ajax({
            url: "<?php echo site_url('payment/loaddefaultdata'); ?>",
            type: "post",
            data: {id: loadid},
            success: function(response) {
                
                $response = $.parseJSON(response);

                $("#aonumrev").val($response['data']['ao_num']);      
                $("#adtype").val($response['data']['ao_adtype']);
                $("#payeecode").val($response['data']['agencycode']);
                $("#payeename").val($response['data']['agencyname']);
                $("#address1").val($response['data']['ao_add1']);
                $("#address2").val($response['data']['ao_add2']);
                $("#address3").val($response['data']['ao_add3']);
                $("#tin").val($response['data']['ao_tin']);
                $("#zipcode").val($response['data']['ao_zip']);
                $("#tel1prefix").val($response['data']['ao_telprefix1']);
                $("#tel1").val($response['data']['ao_tel1']);
                $("#tel2prefix").val($response['data']['ao_telprefix2']);
                $("#tel2").val($response['data']['ao_tel2']);
                $("#celprefix").val($response['data']['ao_celprefix']);
                $("#cel").val($response['data']['ao_cel']);
                $("#faxprefix").val($response['data']['ao_faxprefix']);
                $("#fax").val($response['data']['ao_fax']);
                $("#particulars").val($response['part']['part']);
                
                $("#vatcode").val($response['data']['ao_cmfvatcode']);
                
                
                $("#comments").val('AO#'+$response['data']['ao_num']+' Amount: '+$response['data']['totalamt']+' '+'VAT: '+$response['data']['ao_cmfvatrate']);      
                //$("#amountpaid").val($response['data']['ao_amt']);      
                      
                
                if ($response['data']['ao_paytype'] == 3 || $response['data']['ao_paytype'] == 4 || $response['data']['ao_paytype'] == 5) {
                    
                    //alert($response['data']['ao_paytype']);
                    $('#ortype').val(2);
                    $("#assignedamount").val($response['data']['totalamt']);
                } else {
                    $('#ortype').val(1);     
                }
                
                $("#viewbooking_view").dialog('close');
                //location.reload();
                
            }   
        });
    } else { alert("Select to load agency!"); return false;}  
});

$("#search_booking").click(function(){
    $.ajax({
        url: "<?php echo site_url('payment/viewbooking_list') ?>",
        type: 'post',
        data: {
            aono: $('#b_aono').val(),
            payeecode: $('#b_payeecode').val(),
            payeename: $('#b_payeename').val(),
            invoiceno: $('#b_invoiceno').val(),
            issuefrom: $('#b_issuefrom').val(),
            issueto: $('#b_issueto').val(),
            agencycode: $('#b_agencycode').val(),
            agencyname: $('#b_agencyname').val(),
            clienttype: $('#b_clienttype').val(),
            pono: $('#b_pono').val(),
            paytype: $('#b_paytype').val()
                      
        },
        success: function(response){
        
            $response = $.parseJSON(response);
            
            $(".booklist").html($response['booklist']);    
        }
    });  
});
</script>         
