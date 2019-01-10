<div class="block-fluid">       
    <div class="row-form-booking">
        <div class="span1">Invoice</div>    
        <div class="span1"><input type="text" name="inv_no" id="inv_no" style="text-align:right"></div>
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="margin-left:15%;">
            <button class="btn btn-block" type="button" id="btn_loadsingleinv" name="btn_loadrevenue">Load Invoice</button>
            </div> 
        <div class="clear"></div>
    </div>
</div>
<script>
$('#inv_no').mask('99999999');
$("#btn_loadsingleinv").click(function(){
    var mykeyid = $('#mykeyid').val();
    $.ajax({
        url: '<?php echo site_url('payment/orSingleInvoice') ?>',
        type: 'post',
        data: {inv: $('#inv_no').val(), mykeyid: mykeyid},
        success: function (response) {
            var $response = $.parseJSON(response); 
            if ($response['validate'] == "Valid") {
                alert('Please double check OR before saving.');                
                
                $("#payeecode").val($response['invdata']['clientcode']);
                $("#payeename").val($response['invdata']['clientname']);
                $("#address1").val($response['invdata']['ao_add1']);
                $("#address2").val($response['invdata']['ao_add2']);
                $("#address3").val($response['invdata']['ao_add3']);
                $("#tin").val($response['invdata']['ao_tin']);
                $("#zipcode").val($response['invdata']['ao_zip']);
                $("#tel1prefix").val($response['invdata']['ao_telprefix1']);
                $("#tel1").val($response['invdata']['ao_tel1']);
                $("#tel2prefix").val($response['invdata']['ao_telprefix2']);
                $("#tel2").val($response['invdata']['ao_tel2']);
                $("#celprefix").val($response['invdata']['ao_celprefix']);
                $("#cel").val($response['invdata']['ao_cel']);
                $("#faxprefix").val($response['invdata']['ao_faxprefix']);
                $("#fax").val($response['invdata']['ao_fax']);
                $("#branch").val($response['invdata']['ao_branch']);
                $("#amountpaid").val($response['invdata']['ao_amt']);
                $("#assignedamount").val($response['invdata']['ao_amt']);
                $("#particulars").val('#'+$response['invdata']['ao_sinum']);
                
                $("#s_vatablesale").val($response['invdata']['ao_vatsales']);
                $("#s_vatexemptsale").val($response['invdata']['ao_vatexempt']);
                $("#s_vatzeroratedsale").val($response['invdata']['ao_vatzero']);
                $("#s_totalsale").val($response['invdata']['ao_grossamt']);
                $("#s_valueaddedtax").val($response['invdata']['ao_vatamt']);
                $("#s_withholdingtax").val($response['invdata']['witholdingandpp']);
                $("#s_totalpayment").val($response['invdata']['ao_amt']);
                
                
                $("#vatcode").val($response['invdata']['ao_cmfvatcode']);
                $("#vatablesale").val($response['invdata']['ao_vatsales']);
                $("#vatexempt").val($response['invdata']['ao_vatexempt']);
                $("#vatzerorated").val($response['invdata']['ao_vatzero']);
                
                $("#evatamount").val($response['invdata']['ao_vatamt']);
                $("#evatpercent").val($response['invdata']['ao_cmfvatrate']);
                
                if ($response['invdata']['ao_wtaxstatus'] == 1) {
                    $("#wtaxstatus").addClass('checked');
                    $("#wtaxamount").val($response['invdata']['ao_wtaxamt']);       
                    $("#wtaxpercent").val($response['invdata']['ao_wtaxpercent']);       
                    $("#wtaxassign").val($response['invdata']['ao_wtaxamt']);       
                }
                
                if ($response['invdata']['ao_wvatstatus'] == 1) {
                    $("#wvatstatus").prop('checked', true);
                    $("#wvatamount").val($response['invdata']['ao_wvatamt']);       
                    $("#wvatpercent").val($response['invdata']['ao_wvatpercent']);       
                    $("#wvatassign").val($response['invdata']['ao_wvatamt']);       
                }
                
                if ($response['invdata']['ao_ppdstatus'] == 1) {
                    $("#ppdstatus").prop('checked', true);
                    $("#ppdamount").val($response['invdata']['ao_ppdamt']);       
                    $("#ppdpercent").val($response['invdata']['ao_ppdpercent']);       
                    $("#ppdassign").val($response['invdata']['ao_ppdamt']);       
                }
                var words = toWords($response['invdata']['ao_amtnotformat']);            
                $('#amountinwords').val(words);     
                /* Payment Type */
                $(".paymenttype_list").html($response['prpayments_list']);
                /* Payment Applied */ 
                $('#paymentapplied_list').html($response['applied_list']);     
                $('#orappsingleinv').hide();             
                $("#orappsingleinv_view").dialog('close');
            } else {
                alert($response['validate']);
                return false;
            }  
        }    
    });    
});

</script>
