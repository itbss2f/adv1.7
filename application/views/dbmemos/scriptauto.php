<div id="manualaccountingentry" title="Manual Accounting Entry"></div>
<div id="editaccountingentryview" title="Edit Accounting Entry"></div>      
<div id="changedcnumview" title="Edit Debit / Credit Number">
                                                                                                                                                                  
    <div class="row-form-booking">
       <div class="span1" style="width: 100px;">New DC Number:</div>
       <div class="span1" style="width: 100px;"><input type="text" name="newdcdate" id="newdcdate"  /></div>
        <div class="span1">  
        <button class="btn btn-success" type="button" name="b_updatedcnum" id="b_updatedcnum">Update</button>    
        </div>         
       <div class="clear"></div>
    </div>                                                           

</div>      
<script>  
     
$(document).ready(function() {
        $('#comments').focus(function() {
            $('#comments').css("height", "75px");
        });
        $('#comments').blur(function() {
            $('#comments').css("height", "24px");
        });
        
        $('#particulars').focus(function() {
            $('#particulars').css("height", "75px");
        });
        $('#particulars').blur(function() {
            $('#particulars').css("height", "24px");
        });
    });
$(function() {
var th = ['','Thousand','Million', 'Billion','Trillion'];    

var dg = ['Zero','One','Two','Three','Four', 'Five','Six','Seven','Eight','Nine']; 
var tn = ['Ten','Eleven','Twelve','Thirteen', 'Fourteen','Fifteen','Sixteen', 'Seventeen','Eighteen','Nineteen']; 
var tw = ['Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety']; 
var ng = ['0','1','2','3','4','5','6','7','8','9']; 

var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

$('#b_updatedcnum').click(function() {

    $.ajax({
        url: "<?php echo site_url ('dbmemo/validateDCNumber') ?>",
        type: 'post',
        data: {num : $('#newdcdate').val()},
        success: function (response) {
            if (response == true) {
                alert('DC Number already exist!');
                $('#newdcdate').val('').focus();
            } else {
                //$("#form_savepayment").submit();
                //alert('save');
                var old = '<?php echo @$main['dc_num']; ?>';
                var typ = $("#dctype").val();       
                var num = $('#newdcdate').val();
                $.ajax({
                    url: "<?php echo site_url ('dbmemo/saveNEWDCNUmber') ?>",
                    type: 'post',
                    data: {num : $('#newdcdate').val(), old: old, typ: typ},
                    success: function (response) {
                        
                        window.location.href = "<?php echo base_url()?>dbmemo/view/"+typ+"/"+num;               
                    }    
                });
            }
                  
        }
    });   
    
});


$('#b_changedcnum').click(function(){
    $('#changedcnumview').dialog('open');   
}); 

$("#b_dcmtype").click(function() {
    var typ = $("#dctype").val();
    var dcnumber = $("#dcnumber").val();
    var tt = '';
    
    if (typ == 'C') {
        var text = "Are you sure you want to change Credit to Debit?. Make sure no applied";  
        tt = 'D'  
    } else {
        var text = "Are you sure you want to change Debit to Credit?";      
        tt = 'C'  
    } 
    
    var ans = confirm(text);
    
    if (ans) {
        $.ajax({
            url: '<?php echo site_url('dbmemo/changetype') ?>',
            type: 'post',
            data: {dcnumber:dcnumber, tt: tt, typ: typ},
            success: function (response) {
                window.location.href = "<?php echo base_url()?>dbmemo/view/"+tt+"/"+dcnumber;           
            }
        });
    }
});

$("#b_save").click(function(){
   
    var countValidate = 0;    
    var dcamount = $("#dcamount").val();
    var assignedamount = $("#assigneamount").val();
    var dcsubtype = $("#dcsubtype").val();

    var dcamt = dcamount.replace(/,/g,"");      
    var assamt = assignedamount.replace(/,/g,"");      

    var dctype = $("#dctype").val();
    var validate_fields = new Array();  
    if (dctype == "C") {  

       
        if (parseFloat(assamt) < parseFloat(dcamt)) {         
            if (dcsubtype == 6 || dcsubtype == 10) {
                var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#clientname', '#dcamount', '#dcadtype', '#particulars', '#comments'];
            } else {
            var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#clientname', '#dcamount', '#dcadtype', '#totalamt', '#totalgrossamt' , '#totalvatamt', '#totaldebitamount', '#totalcreditamount', '#particulars', '#comments'];     
            }
        } else {
            
            if (parseFloat(assamt) > parseFloat(dcamt)) {         
            alert("Amount must be greater than or equal to assign amount!");
            return false;
            } else {  
                if (dcsubtype == 6 || dcsubtype == 10) {
                    var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#clientname', '#dcamount', '#particulars', '#comments'];
                } else {          
                    var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#clientname', '#dcamount', '#totalamt', '#totalgrossamt' , '#totalvatamt', '#totaldebitamount', '#totalcreditamount', '#particulars', '#comments']; 
                }
            }
        }    
    } else { 
            if (parseFloat(assamt) > parseFloat(dcamt)) {         
            alert("Amount must be greater than or equal to assign amount!");
            return false;
            } else {  
                if (dcsubtype == 6 || dcsubtype == 10) {
                    var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#clientname', '#dcamount', '#dcadtype', '#particulars', '#comments'];
                } else {var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#clientname', '#dcamount', '#dcadtype', '#totaldebitamount', '#totalcreditamount', '#particulars', '#comments'];}    
            }
    }      
    
    var chck = $("#habol").prop("checked");  
    if (chck) {
        validate_fields.push("#haboldate");     
    } else {
        $("#haboldate").val('');  
    } 
    
    //console.debug(validate_fields); 
    //return false;
    
    
    for (x = 0; x < validate_fields.length; x++) { 
        $(validate_fields[x]).css(errorcssobj2);                  
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }     
    if (countValidate == 0) {
        $.ajax({
        url: "<?php echo site_url ('dbmemo/validateDCNumber') ?>",
        type: 'post',
        data: {num : $('#dcnumber').val()},
        success: function (response) {
            if (response == true) {
                alert('DC Number already exist!');
                $('#dcnumber').val('').focus();
            } else {
                
                var tdebit = $('#totaldebitamount').val();
                var tcredit = $('#totalcreditamount').val();
                
                if (tdebit != tcredit) {
                    alert('Total Debit Amount must be equal to Total Credit Amount');
                    
                    return false;
                } else {
                    $("#saveForm").submit();      
                }
                 
            }
                  
        }
    });   
        
    }                                           
});

$("#b_save2").click(function(){

    var countValidate = 0;    
    var dcamount = $("#dcamount").val();
    var assignedamount = $("#assigneamount").val();
    var dcsubtype = $("#dcsubtype").val();

    var dcamt = dcamount.replace(/,/g,"");      
    var assamt = assignedamount.replace(/,/g,"");      

    var dctype = $("#dctype").val();
    
    if (dctype == "C") {  

       
        if (parseFloat(assamt) < parseFloat(dcamt)) {         
            if (dcsubtype == 6 || dcsubtype == 10) {
                var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#clientname', '#dcamount', '#dcadtype', '#particulars', '#comments'];
            } else {
            var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#clientname', '#dcamount', '#dcadtype', '#totalamt', '#totalgrossamt' , '#totalvatamt', '#totaldebitamount', '#totalcreditamount', '#particulars', '#comments'];     
            }
        } else {
            if (parseFloat(assamt) > parseFloat(dcamt)) {         
            alert("Amount must be greater than or equal to assign amount!");
            return false;
            } else {  
                if (dcsubtype == 6 || dcsubtype == 10) {
                    var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#clientname', '#dcamount', '#particulars', '#comments'];
                } else {          
                    var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#clientname', '#dcamount', '#totalamt', '#totalgrossamt' , '#totalvatamt', '#totaldebitamount', '#totalcreditamount', '#particulars', '#comments']; 
                }
            }
        }    
    } else { 
            if (parseFloat(assamt) > parseFloat(dcamt)) {       
            alert("Amount must be greater than or equal to assign amount!");
            return false;
            } else {  
                if (dcsubtype == 6 || dcsubtype == 10) {
                    var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#clientname', '#dcamount', '#dcadtype', '#particulars', '#comments'];
                } else {var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#clientname', '#dcamount', '#dcadtype', '#totaldebitamount', '#totalcreditamount', '#particulars', '#comments'];}    
            }
    }      
        
    
    
    for (x = 0; x < validate_fields.length; x++) { 
        $(validate_fields[x]).css(errorcssobj2);                  
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }     
    if (countValidate == 0) {
        var tdebit = $('#totaldebitamount').val();
        var tcredit = $('#totalcreditamount').val();

        if (tdebit != tcredit) {
            alert('Total Debit Amount must be equal to Total Credit Amount');
            
            return false;
        } else {
            $("#saveForm").submit();      
        }
    }                                           
});

$('#manualaccountingentry, #editaccountingentryview, #changedcnumview').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: '500px',    
    height: 'auto',
    modal: true,
    resizable: false
}); 


    
    /* Manual Accounting Entry */
    $("#manualacctentry").click(function() {
        var countValidate = 0;  

        var dcamount = $("#dcamount").val();
        var assignedamount = $("#assigneamount").val();
        
        var dcamt = dcamount.replace(/,/g,"");      
        var assamt = assignedamount.replace(/,/g,"");         
        
        if (parseFloat(assamt) < parseFloat(dcamt)) {            
             var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#dcamount', '#dcadtype'];                                            
        } else {            
            var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#dcamount'];          
        }
         
        for (x = 0; x < validate_fields.length; x++) { 
            $(validate_fields[x]).css(errorcssobj2);                  
            if($(validate_fields[x]).val() == "") {                        
                $(validate_fields[x]).css(errorcssobj);          
                  countValidate += 1;
            } else {        
                  $(validate_fields[x]).css(errorcssobj2);       
            }        
        } 
               
        if (countValidate == 0) {
            if (parseFloat(dcamt) <= 0) {
                alert('DC amount must not be less than or equal to zero!'); return false;
            } else if (parseFloat(assamt) > parseFloat(dcamt)) {
                alert('Assign Amount must not be greater than DC amount!'); return false;
            }  
            var $dcsubtype = $("#dcsubtype").val();
            var $dcadtype = $("#dcadtype").val();
            var $dcamount = $("#dcamount").val();
            var $dcassamt = $("#assigneamount").val();
            var $hkey = $("#hkey").val();
            var $ids = Array();
            var $ass_amts = Array();
            var $gross_amts = Array();     
            var $vat_amts = Array();     
            $('.assign').each(function(){$ids.push($(this).attr('id'));});              
            $('.ass_amt').each(function(){$ass_amts.push($(this).val());});              
            $('.gross_amt').each(function(){$gross_amts.push($(this).val());});              
            $('.vat_amt').each(function(){$vat_amts.push($(this).val());});              
            $.ajax({
                url: "<?php echo site_url('dbmemo/setManualAcountingEntry') ?>",
                type: "post",
                data: {dcsubtype: $dcsubtype, dcadtype: $dcadtype, ids: $ids, ass_amts: $ass_amts, 
                       gross_amts: $gross_amts, vat_amts: $vat_amts, dcamount: $dcamount, dcassamt: $dcassamt, hkey: $hkey},
                success: function(response) {
                    var $response = $.parseJSON(response);
                    $("#manualaccountingentry").html($response["manualacctentry_list"]).dialog("open");
                     
                }    
            });   
        } else {            
            return false;
        }            
    });
    
    /* Find using invoice */
    $("#b_findinvoice").click(function() {
        $.ajax({
            url: '<?php echo site_url('dbmemo/view_lookupinv') ?>',
            type: 'post',
            data: {},
            success: function(response) {
                var $response = $.parseJSON(response);                    
                $('#modal_findinvoice').html($response['view']).dialog('open');        
            }    
        });
        
    });
    
    /* Find using ao number */
    $("#b_findao").click(function() {
        $.ajax({
            url: '<?php echo site_url('dbmemo/view_lookupao') ?>',
            type: 'post',
            data: {},
            success: function(response) {
                var $response = $.parseJSON(response);                    
                $('#modal_findao').html($response['view']).dialog('open');        
            }    
        });
        
    });
    
    /* Find using or / cm number */
    $("#b_orcm").click(function() {
        $.ajax({
            url: '<?php echo site_url('dbmemo/view_lookuporcm') ?>',
            type: 'post',
            data: {},
            success: function(response) {
                var $response = $.parseJSON(response);                    
                $('#modal_findao2').html($response['view']).dialog('open');        
            }    
        });
        
    });
    
    /* Single Invoice */
    $("#singleinv").click(function() {
        var countValidate = 0;  
        var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#dcamount']; 
        for (x = 0; x < validate_fields.length; x++) { 
            $(validate_fields[x]).css(errorcssobj2);                  
            if($(validate_fields[x]).val() == "") {                        
                $(validate_fields[x]).css(errorcssobj);          
                  countValidate += 1;
            } else {        
                  $(validate_fields[x]).css(errorcssobj2);       
            }        
        } 
        
        if (countValidate == 0) {    
            $.ajax({
                url: '<?php echo site_url('dbmemo/view_singleinve') ?>',
                type: 'post',
                data: {},
                success: function(response) {
                    var $response = $.parseJSON(response);                    
                    $('#modal_singleinvoice').html($response['view']).dialog('open');        
                }    
            });
        }
    });
    

    /* Accounting Entry */    
    $("#acctentry").click(function(){      

        var countValidate = 0;  

        var dcamount = $("#dcamount").val();
        var assignedamount = $("#assigneamount").val();
        
        var dcamt = dcamount.replace(/,/g,"");      
        var assamt = assignedamount.replace(/,/g,"");      
        
        if (parseFloat(assamt) < parseFloat(dcamt)) {            
             var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#dcamount', '#dcadtype'];                                            
        } else {            
            var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#dcamount'];          
        }
         
        for (x = 0; x < validate_fields.length; x++) { 
            $(validate_fields[x]).css(errorcssobj2);                  
            if($(validate_fields[x]).val() == "") {                        
                $(validate_fields[x]).css(errorcssobj);          
                  countValidate += 1;
            } else {        
                  $(validate_fields[x]).css(errorcssobj2);       
            }        
        } 
               
        if (countValidate == 0) {
            if (parseFloat(dcamt) <= 0) {
                alert('DC amount must not be less than or equal to zero!'); return false;
            } else if (parseFloat(assamt) > parseFloat(dcamt)) {
                alert('Assign Amount must not be greater than DC amount!'); return false;
            }  
            var $dctype = $("#dctype").val();
            var $dcsubtype = $("#dcsubtype").val();
            var $dcadtype = $("#dcadtype").val();
            var $dcamount = $("#dcamount").val();
            var $dcassamt = $("#assigneamount").val();
            var $hkey = $("#hkey").val();   
            var $ids = Array();
            var $ids2 = Array();
            var $ass_amts = Array();
            var $ass_amts2 = Array();
            var $gross_amts = Array();     
            var $gross_amts2 = Array();     
            var $vat_amts = Array();     
            var $vat_amts2 = Array();     

            $('.assign').each(function(){
                 
                if ($(this).attr('datatype') == 'SI') {                    
                    $ids.push($(this).attr('id'));                    
                } else {
                    $ids2.push($(this).attr('id'));             
                }           
                
            });
            $('.ass_amt').each(function(){
                if ($(this).attr('datatype') == 'SI') {                                         
                    $ass_amts.push($(this).val());
                } else {
                    $ass_amts2.push($(this).val());                    
                }
            });    
            $('.gross_amt').each(function(){
                if ($(this).attr('datatype') == 'SI') {                                         
                    $gross_amts.push($(this).val());
                } else {
                    $gross_amts2.push($(this).val());    
                }
            });  
            $('.vat_amt').each(function(){
                if ($(this).attr('datatype') == 'SI') {                                         
                    $vat_amts.push($(this).val());
                } else {
                    $vat_amts2.push($(this).val());                
                }
            });                                              
            
            var ans = confirm("Are you sure you want to run this automatic accounting entry if yes! Existing Accounting Entry will be deleted!");
            if (ans) {
                $.ajax({
                    url: "<?php echo site_url('dbmemo/setAcountingEntry') ?>",
                    type: "post",
                    data: {dctype: $dctype, dcsubtype: $dcsubtype, dcadtype: $dcadtype, ids: $ids, ass_amts: $ass_amts, 
                           gross_amts: $gross_amts, vat_amts: $vat_amts, dcamount: $dcamount, dcassamt: $dcassamt, hkey: $hkey,
                           ids2: $ids2, ass_amts2: $ass_amts2, gross_amts2: $gross_amts2, vat_amts2: $vat_amts2},
                    success: function(response) {
                        var $response = $.parseJSON(response);                    
                        $("#totaldebitamount").val($response['totaldebit']);                    
                        $("#totalcreditamount").val($response['totalcredit']);                    
                        $(".accounting_entry_list").html($response['acctentry_list']);    
                    }    
                }); 
            }  
        } else {            
            return false;
        }   
        
    });
    
    
    
    /* Import Invoice */
    $("#importinvoice").click(function(){        
        var countValidate = 0;  
        var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#dcamount'];

        for (x = 0; x < validate_fields.length; x++) {            
            if($(validate_fields[x]).val() == "") {                        
                $(validate_fields[x]).css(errorcssobj);          
                  countValidate += 1;
            } else {        
                  $(validate_fields[x]).css(errorcssobj2);       
            }        
        }   
        if (countValidate == 0) {
            var $cmf_code = $("#clientcode").val();  
            var $amf_id = $("#agency").val();    
            var $ids = Array();
            $('.assign').each(function(){
                
                if ($(this).attr('datatype') == 'SI') {
                    
                    $ids.push($(this).attr('id'));
                }            
                
            });              
            $.ajax({
                url: "<?php echo site_url('dbmemo/importinvoice') ?>",
                type: "post",
                data: {cmf_code: $cmf_code, amf_id: $amf_id, ids: $ids},
                success: function(response) {
                    $response = $.parseJSON(response);
                    $("#model_importinvoice").html($response['importinvoice']).dialog("open");
                }
            });
        } else {            
            return false;
        }    
        
    });
    
    /* Debit Memo */
    $('#importdm').click(function() {
        
        var countValidate = 0;  
        var validate_fields = ['#dctype', '#dcnumber', '#dcdate', '#dcsubtype', '#clientcode', '#dcamount'];

        for (x = 0; x < validate_fields.length; x++) {            
            if($(validate_fields[x]).val() == "") {                        
                $(validate_fields[x]).css(errorcssobj);          
                  countValidate += 1;
            } else {        
                  $(validate_fields[x]).css(errorcssobj2);       
            }        
        }   
        if (countValidate == 0) {            
  
            var $ids = Array();
            $('.assign').each(function(){
                
                if ($(this).attr('datatype') == 'DM') {
                    
                    $ids.push($(this).attr('id'));
                }            
                
            });              
            var $payee = $('#clientcode').val();
            $.ajax({
                url: "<?php echo site_url('dbmemo/importdm') ?>",
                type: "post",
                data: {payee: $payee, ids: $ids},
                success: function(response) {
                    var $response = $.parseJSON(response);
                    
                    $('#model_importdm').html($response['dmview']).dialog('open');
                }    
            });
        } else {            
            return false;
        }    
                
    });
    
    /* New */
    $('#b_new').click(function() {
        var ans = confirm("Are you sure you want to create new Debit / Credit memo?");         
        if (ans) {
            window.location.href = "<?php echo base_url()?>dbmemo";
        }
    });
      
    /* Lookup */
    $('#b_lookup').click(function(){
        $.ajax({
            url: "<?php echo site_url('dbmemo/lookup') ?>",
            type: "post",
            data: {},
            success: function(response){
                var $response = $.parseJSON(response);
                $('#modal_lookup').html($response['lookup_view']).dialog('open');        
            }
        });        
    });
    
    /* Dialog Modal */
    $('#model_importinvoice, #modal_lookup, #model_importdm, #modal_findinvoice, #modal_singleinvoice, #modal_findao, #modal_findao2').dialog({
       autoOpen: false, 
       closeOnEscape: false,
       draggable: true,
       width: 850,    
       height: 'auto',
       modal: true,
       resizable: false
    });   
    /* Datepicker */
    //if ()
    $("#dcdate").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: new Date(<?php echo $monthend['last_yr'] ?>, <?php echo $monthend['last_mon'] ?> - 1, <?php echo $monthend['last_d']?> + 1),
        maxDate: "0D"                
    });
    
    /* Amount to Words */
    $('#dcamount').autoNumeric({});      
    $('#dcamount').keyup(function(){    
        var x = $('#dcamount').val();

        var words = toWords(x);            
        $('#dcamountinwords').val(words);            
    }).keyup();
    
    /* Customer autocomplete */
    $("#clientcode").autocomplete({                        
        source: function( request, response ) {
            $.ajax({
                url: '<?php echo site_url('dbmemo/autocustomer') ?>',
                type: 'post',
                data: {cust_code: $(':input[name=clientcode]').val(),
                       cust_name: ''                           
                       },
                success: function(data) {
                    var $data = $.parseJSON(data);
                    
                    response($.map($data, function(item) {
                         return {
                                label: item.cmf_name + ' - ' + item.cmf_code,
                                value: item.cmf_code,
                                item: item                                                                                                      
                         }
                    }));                                                
                }
            });                
        },
        autoFocus: false,
        minLength: 1,
        delay: 300,
        select: function(event, ui) {                                
            $('#clientname').val(ui.item.item.cmf_name);    
            var $cust_id = ui.item.item.id; 
            $.ajax({
                url: "<?php echo site_url('dbmemo/ajaxAgency') ?>",
                type: 'post',
                data: {cust_id: $cust_id},
                success: function(response)
                {
                    var $xponse = $.parseJSON(response);
                    $('#agency').empty();
                    $('#agency').append($('<option>').val('').text('--'));
                    $.each($xponse['agency'], function(i)
                    {
                        var xitem = $xponse['agency'][i];
                        var option = $('<option>').val(xitem['id']).text(xitem['cmf_code'] + ' - ' +xitem['cmf_name']);
                        $('#agency').append(option);                            
                    });     
                }
            });  
            $("#totalamt").val("0.00");
            $("#totalgrossamt").val("0.00");
            $("#totalvatamt").val("0.00");
            $("#assigneamount").val("0.00");
            $(".assignment_list").empty();  
            $(".accounting_entry_list").empty();   
        }
    });  
    
    $("#clientname").autocomplete({                        
        source: function( request, response ) {
            $.ajax({
                url: '<?php echo site_url('dbmemo/autocustomer') ?>',
                type: 'post',
                data: {cust_code: '',
                       cust_name: $(':input[name=clientname]').val(),  
                       },
                success: function(data) {
                    var $data = $.parseJSON(data);
                    
                    response($.map($data, function(item) {
                         return {
                                label: item.cmf_name + ' - ' + item.cmf_code,
                                value: item.cmf_name,
                                item: item                                                                                                      
                         }
                    }));                                                
                }
            });                
        },
        autoFocus: false,
        minLength: 1,
        delay: 300,
        select: function(event, ui) {                                
            $('#clientcode').val(ui.item.item.cmf_code);    
            var $cust_id = ui.item.item.id; 
            $.ajax({
                url: "<?php echo site_url('dbmemo/ajaxAgency') ?>",
                type: 'post',
                data: {cust_id: $cust_id},
                success: function(response)
                {
                    var $xponse = $.parseJSON(response);
                    $('#agency').empty();
                    $('#agency').append($('<option>').val('').text('--'));
                    $.each($xponse['agency'], function(i)
                    {
                        var xitem = $xponse['agency'][i];
                        var option = $('<option>').val(xitem['id']).text(xitem['cmf_code'] + ' - ' +xitem['cmf_name']);
                        $('#agency').append(option);                            
                    });     
                }
            });  
            $("#totalamt").val("0.00");
            $("#totalgrossamt").val("0.00");
            $("#totalvatamt").val("0.00");
            $("#assigneamount").val("0.00");
            $(".assignment_list").empty();  
            $(".accounting_entry_list").empty();   
        }
    });  
    $("#haboldate").datepicker({
        dateFormat: 'yy-mm-dd',        
    });
    $("#habol").click(function(){
        var chck = $("#habol").prop("checked");  
        if (chck) {
            //$("#haboldate").val('xx');      
            // Do nothing
        } else {
            $("#haboldate").val('');  
        }
    });
    
    /* Debit / Credit */
    $("#dctype").change(function(){
        var $dctype = $("#dctype").val();
        
        if ($dctype == "D") {
            $("#applied_view").fadeOut(100).hide();
            $("#totalamt").val("");
            $("#totalgrossamt").val("");
            $("#totalvatamt").val("");
            $("#assigneamount").val();
            $(".assignment_list").empty();   
        } else { $("#applied_view").fadeIn(100).show(); }    
    });
    
    function toWords(s){
    s = s.toString(); 
    s = s.replace(/[\, ]/g,''); 
    if (s != parseFloat(s)) return ''; 
    var x = s.indexOf('.'); if (x == -1) x = s.length; 
    if (x > 15) return 'too big'; 
    var n = s.split(''); 
    var str = ''; var sk = 0;     
    var    str2 = '';
    var point = '0';
    for (var i=0; i < x; i++) {if ((x-i)%3==2) 
    {
        if (n[i] == '1') {
           str += tn[Number(n[i+1])] + ' '; i++; 
           sk=1;                
        } else if (n[i]!=0) {
           str += tw[n[i]-2] + ' ';
           sk=1; }
        } else if (n[i]!=0) {
           str += dg[n[i]] +' '; 
           if ((x-i)%3==0) str += 'Hundred '; sk=1;} 
           if ((x-i)%3==1) {if (sk) str += th[(x-i-1)/3] + ' ';sk=0;}} if (x != s.length) {
           var y = s.length;                 
           
           for (var i=x+1; i<y; i++) str2 += ng[n[i]];                
           }                    
           if (y === undefined) {
               str = str + 'Pesos Only';        
           } else {
               str = str + 'Pesos';                    
               if (n[y-1] == "." || str2 === "0" || str2 === "00" || str2 === "000" || str2 === "0000") {
                   str2 = 'Only';
               } else {
                   str2 = 'and ' + str2 + '/100 Only';                    
               }    

               if(str2 === 0 ) {}
               str = str +' '+ str2;
           }                
           return str.replace(/\s+/g,' ');                 
    }  
    
     
});





</script>
