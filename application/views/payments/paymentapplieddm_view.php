<div class="block-fluid">       
    <div class="row-form-booking">
        <div class="span1">DC No.</div>    
        <div class="span1" style="width:80px"><input type="text" value="<?php echo $data['dc_num'] ?>" readonly="readonly"></div>
        <div class="span1">DC Date</div>    
        <div class="span1" style="width:80px"><input type="text" value="<?php echo $data['dc_date'] ?>" readonly="readonly"></div>
        <div class="clear"></div>    
    </div>    
    <div class="row-form-booking">        
        <div class="span1">DC Amt</div>    
        <div class="span1" style="width:80px"><input type="text" style="text-align:right" value="<?php echo number_format(@$data['dc_amt'], 2, '.', ','); ?>" readonly="readonly"></div>
        <div class="span1">Amount Due</div>    
        <div class="span1" style="width:80px"><input type="text" style="text-align:right" value="<?php echo number_format(@$data['bal'], 2, '.', ','); ?>" readonly="readonly"></div>
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span1">Applied</div>    
        <div class="span1" style="width:80px"><input type="text" name="a_appliedamt" id="a_appliedamt" style="text-align:right"></div>
        <div class="span1">W/TAX</div>    
        <div class="span1" style="width:80px"><input type="text" name="a_wtax" id="a_wtax" style="text-align:right"></div>
        <div class="span1" style="width:50px">W/TAX%</div>    
        <div class="span1" style="width:80px"><input type="text" name="a_wtaxp" id="a_wtaxp" value="<?php echo $wtaxpercent ?>" style="text-align:right"></div>        
        <div class="clear"></div>    
    </div> 
    <div class="row-form-booking">
        <div class="span1" style="width:180px"><button class="btn btn-block btn-success" type="button" id="btn_applied2" name="btn_applied2">Applied DM</button></div>    
        <div class="span1">W/VAT</div>    
        <div class="span1" style="width:80px"><input type="text" name="a_wvat" id="a_wvat" style="text-align:right"></div>
        <div class="span1" style="width:50px">W/VAT%</div>    
        <div class="span1" style="width:80px"><input type="text" name="a_wvatp" id="a_wvatp" value="<?php echo $wvatpercent ?>" style="text-align:right"></div>
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">    
        <div class="span1" style="width:180px"></div>    
        <div class="span1">PPD</div>    
        <div class="span1" style="width:80px"><input type="text" name="a_ppd" id="a_ppd" style="text-align:right"></div>
        <div class="span1" style="width:50px">PPD%</div>    
        <div class="span1" style="width:80px"><input type="text" name="a_ppdp" id="a_ppdp" value="<?php echo $ppdpercent ?>" style="text-align:right"></div>
        <div class="clear"></div>    
    </div>  
</div>
<script>
$("#a_appliedamt , #a_wtaxp, #a_wvatp, #a_ppdp").keyup(function(){
    applied_recomputePercent();    
});

function applied_recomputePercent()
{
    var $amountpaid = $("#a_appliedamt").val();
    var $vatcode = $("#vatcode").val();
    var $wvatpercent = $("#a_wvatp").val();
    var $wtaxpercent = $("#a_wtaxp").val();
    var $ppdpercent = $("#a_ppdp").val();
    
    $.ajax({
        url: "<?php echo site_url('payment/getRecomputeValuePercent') ?>",
        type: "post",
        data: {amountpaid: $amountpaid, vatcode: $vatcode, wvatpercent: $wvatpercent, wtaxpercent: $wtaxpercent, ppdpercent: $ppdpercent},
        success: function(response) {
            $response = $.parseJSON(response);
            $("#a_wvat").val($response['wvatamount']);        
            $("#a_wtax").val($response['wtaxamount']);
            $("#a_ppd").val($response['ppdamount']);            
        }
    });
}

var validate_fields = ['#a_appliedamt'];
$("#btn_applied2").click(function(){
    var countValidate = 0;  
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }  
    if (countValidate == 0) {
        var $a_appliedamt = $("#a_appliedamt").val();             
        var $a_wvat = $("#a_wvat").val();
        var $a_wvatp = $("#a_wvatp").val();
        var $a_wtax = $("#a_wtax").val();
        var $a_wtaxp = $("#a_wtaxp").val();
        var $a_ppd = $("#a_ppd").val();
        var $a_ppdp = $("#a_ppdp").val();
        
        var $totalpayable = "<?php echo $data['bal'] ?>"; 
        var $xx = $a_appliedamt.replace(',','');
        if (parseFloat($xx) > parseFloat($totalpayable)) {
            alert("Applied amount must not be greather than Total Payable Amount available "+$totalpayable); 

            return false;
        }
        var $doctype = "<?php echo $doctype ?>";         
        $.ajax({
            url: "<?php echo site_url('payment/saveTempORPaymentApplied') ?>",
            type: "post",
            data: {
                 id: "<?php echo $id ?>",
                 mykeyid: "<?php echo $mykeyid ?>",
                 type: "<?php echo $type ?>",
                 code: "<?php echo $code ?>",
                 choose: "<?php echo $choose ?>",
                 doctype: $doctype, 
                 a_appliedamt: $a_appliedamt,
                 a_wvat: $a_wvat,
                 a_wvatp: $a_wvatp,
                 a_wtax: $a_wtax,
                 a_wtaxp: $a_wtaxp,
                 a_ppd: $a_ppd,
                 a_ppdp: $a_ppdp    
                   },
            success:function(response) {
                $response = $.parseJSON(response); 
                $('#paymentapplieddm_view').dialog('close');       
                $('.search_list').html($response['search_list']);
                $('#paymentapplied_list').html($response['applied_list']);
                
                $('#wvatassign').val($response['summaryassign']['totalwvat']);
                $('#wtaxassign').val($response['summaryassign']['totalwtax']);
                $('#ppdassign').val($response['summaryassign']['totalppd']);
                $('#assignedamount').val($response['summaryassign']['totalappliedamt']);                
                
            }
        });
    } else {            
        return false;
    }   
});
$("#a_appliedamt, #a_wvat, #a_wvatp, #a_wtax, #a_wtaxp, #a_ppd, #a_ppdp").autoNumeric();
</script>
