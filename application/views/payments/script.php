<div id="paymenttype_view" title="Payment Type"></div>
<div id="paymentapplied_view" title="Payment Application"></div>
<div id="paymentapplieddm_view" title="Payment Debit Memo Application"></div>
<div id="updatepaymentapplied_view" title="Payment Application"></div>
<div id="updatepaymentapplieddm_view" title="Payment Debit Memo Application"></div>
<div id="revenue_view" title="Revenue Import"></div>
<div id="lookup_view" title="OR Payment Lookup"></div>
<div id="importpr_view" title="Import Provisional Payment Lookup"></div>
<div id="singleinvoice_view" title="Single Invoice"></div>
<div id="viewbooking_view" title="View All Booking Transaction"></div>
<div id="prcheckdue_view" title="View All PR Check Due for OR"></div>
<div id="orappsingleinv_view" title="OR Application for Single Invoice"></div>

<script> 

//$('#viewbooking_view').bind('dialogclose', function(event) {
    
    //console.debug(event);
    //alert(event);
    //$('#ortype').val(1);
//});

/*$( "#viewbooking_view" ).dialog({
    autoOpen: false,
    close : function(){
      $('#ortype').val(1); 
    }  
});*/

$("#ordate").datepicker({   
    dateFormat: 'yy-mm-dd',      
    minDate: -5, maxDate: "0D"
    //minDate: new Date(<?php echo $monthend['last_yr'] ?>, <?php echo $monthend['last_mon'] ?> - 1, <?php echo $monthend['last_d']?> + 1)               
});

$("#changetype").click(function() {
    var con = confirm("Are you sure you want to change payment type?");
    
    if (con) {
        $.ajax({
            url: "<?php echo site_url('payment/orchangetype'); ?>",
            type: "post",
            data: {ornum : '<?php echo $this->uri->segment(3); ?>', paymenttypeid: $('#paymenttypeid').val() },
            success: function(response) {
                $response = $.parseJSON(response);
                
                alert($response['msg']);  
                
                if ($response['stat'] == 'T') {
                window.location.href = "<?php echo base_url().'payment/load_orpayment/'.$this->uri->segment(3); ?>";          
                }
            }    
        });   
    }
});


$("#orchangetype").click(function() {
    $('#changepaymenttypeview').dialog('open');   
});

$('#importao').click(function() {
    
    if ($('#imaonum').val() == '') {
        alert('AO # must not be empty');       
    } else {
        $.ajax({
            url: "<?php echo site_url('payment/importaonum') ?>",  
            type: 'post',
            data: {imaonum: $('#imaonum').val(), ornum: $('#orno').val()},
            success: function(response){
                $response = $.parseJSON(response);   
                if ($response['invalid']) {
                    alert('Invalid AO Number');
                } else{
                    $('#importedaonum').val($('#imaonum').val());
                    $("#particulars").val($response['part']);
                    $("#comments").val($response['comment']);           
                    $("#assignedamount").val($response['assignamt']); 
                    $('#importaonumrevenue').dialog('close'); 
                    $('#add_newrevenueao').hide();
                }       
            }    
        });
    }    
});

$('#add_newrevenueao').click(function() {
    $('#imaonum').val('');
    $('#importaonumrevenue').dialog('open');        
});

  
$("#printor").click(function() {
    window.open("<?php echo site_url('payment/printOR/'.@$data['or_num']) ?>","","menubar=yes, scrollbars=yes, resizable=yes, top=50%, left=50%, width=800, height=600");           
});             
       
$("#orappsingleinv").click(function() {
    $.ajax({
        url: "<?php echo site_url('payment/orappsingleinv') ?>",
        type: "post",
        data: {},
        success: function(response) {
            $response = $.parseJSON(response);
            $("#orappsingleinv_view").html($response['orappsingleinv_view']).dialog('open');
        }
    });
    
});
$("#orviewprcheckdue").click(function() {

    $.ajax({
        url: "<?php echo site_url('payment/prcheckdue') ?>",
        type: "post",
        data: {},
        success: function(response) {
            $response = $.parseJSON(response);
            $("#prcheckdue_view").html($response['prcheckdue_view']).dialog('open');
            //$("$prcheckdue_view").dialog('open');
        }
    });
});                                                   
$("#orviewbooking").click(function() {
    $.ajax({
        url: "<?php echo site_url('payment/viewbookingview') ?>",
        type: "post",
        data: {},
        success: function(response) {
            $response = $.parseJSON(response);            
            $("#viewbooking_view").html($response['viewbooking']).dialog('open');
            
        }
    });
});
$("#orcancelled").click(function() {
    var con = confirm("Are you sure you want to cancel this official reciept?");
    
    if (con) {
        $.ajax({
            url: "<?php echo site_url('payment/orcancelled'); ?>",
            type: "post",
            data: {ornum : '<?php echo $this->uri->segment(3); ?>'},
            success: function(response) {
                $response = $.parseJSON(response);
                
                alert($response['msg']);  
                if ($response['stat'] == 'T') {
                window.location.href = "<?php echo base_url().'payment/load_orpayment/'.$this->uri->segment(3); ?>";          
                }
            }    
        });   
    }
});


$("#ordelete").click(function() {
    var con = confirm("Are you sure you want to delete this official reciept?");
    
    if (con) {
        $.ajax({
            url: "<?php echo site_url('payment/ordelete'); ?>",
            type: "post",
            data: {ornum : '<?php echo $this->uri->segment(3); ?>'},
            success: function(response) {
                $response = $.parseJSON(response);
                
                alert($response['msg']);  
                window.location.href = "<?php echo site_url('payment') ?>";          
                if ($response['stat'] == 'T') {
                window.location.href = "<?php echo base_url().'payment/load_orpayment/'.$this->uri->segment(3); ?>";          
                }
            }    
        });   
    }
});

$("#single_invoce").click(function() {
	
	$.ajax({
		url: "<?php echo site_url('payment/singleinvoice') ?>",
		type: "post",
		data: {mykeyid: $("#mykeyid").val(),
			  wtaxp: $("#wtaxpercent").val(),
			  wvatp: $("#wvatpercent").val(),
			  ppdp: $("#ppdpercent").val(),
			  vatcode: $("#vatcode").val(),},
		success: function(response) {
			$response = $.parseJSON(response);			
			$("#singleinvoice_view").html($response['singleinvoice_view']).dialog('open');
		}
	});
});
$("#action_importpr").click(function() {
	$.ajax({
		url: "<?php echo site_url('payment/prlookup') ?>",
		type: "post",
		data: {},
		success: function(response) {
			$response = $.parseJSON(response);
			$("#importpr_view").html($response['lookup_view']).dialog('open');
		}
	});
});


$("#action_neworpayment").click(function() {
	var confirmnew = confirm("Are you sure you want new or payment?.");

		if (confirmnew) {
			window.location.href = "<?php echo base_url()?>payment";
		}
	});		
    
    
    
$("#orlookup").click(function() {
	$.ajax({
		url: "<?php echo site_url('payment/lookup') ?>",
		type: "post",
		data: {},
		success: function(response) {
			$response = $.parseJSON(response);
			$("#lookup_view").html($response['lookup_view']).dialog('open');
		}
	});
});
$("#ortype").change(function(){
	var $prtype = $("#ortype").val();
    $('#assignedamount').val(0);   
    $('#aonumrev').val('');      
	if ($prtype == 2) {		
        
        $.ajax({
            url: "<?php echo site_url('payment/viewbookingview') ?>",
            type: "post",
            data: {},
            success: function(response) {
                $response = $.parseJSON(response);            
                $("#viewbooking_view").html($response['viewbooking']).dialog('open');
                
            }
        });
        
		/*$.ajax({
			url: "<?php #echo site_url('payment/revenueBooking') ?>",
			type: "post",
			data: {},
			success: function(response) {
				$response = $.parseJSON(response);
				$("#revenue_view").html($response['revenue_view']).dialog('open');
			}
		});*/
	} else if ($prtype == 3) {
		$("#payeecode").val('SUNDRIES').attr('readonly', 'readonly');
	} else {
		$("#payeecode").val('').removeAttr('readonly');	
	}
});

var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

$("#orsave").click(function() {
    var xapp = $('.xappliedproderr').text();  

    if (xapp != "") {
        alert('Please check the highlighted (red) OR application !. Remove application first then re-applied.');
        return false;
    }
    
	var countValidate = 0;  
    
    var checkexdeal = $('#exdeal_note').val();

    if (checkexdeal == 1) {
        var validate_fields = ['#orno', '#payeecode','#payeename', '#address1', 
                       '#amountpaid', '#amountinwords', '#assignedamount', '#collector', '#branch'];    
    } else {
        var validate_fields = ['#orno', '#payeecode','#payeename', '#address1', 
                       '#amountpaid', '#amountinwords', '#assignedamount', '#collector', '#bank', '#branch'];
    }
    
	

	for (x = 0; x < validate_fields.length; x++) {			
		if($(validate_fields[x]).val() == "") {                        
			$(validate_fields[x]).css(errorcssobj);          
		  	countValidate += 1;
		} else {        
		  	$(validate_fields[x]).css(errorcssobj2);       
		}        
	} 
	if (countValidate == 0) {
        var ppid = $('#ortype').val(); 
        var ccode = $('#payeecode').val();
                        
        if ((ppid == 1) && (ccode == 'REVENUE' || ccode == 'SUNDRIES')) {  
            alert('REVENUE and SUNDRIES cannot be used for A/R payment');
            return false;
        } else {
            
        var $orno = $("#orno").val();		
        validateORNumber($orno)	
          }				
	} else {			
		return false;
	}	
});

$("#orsaveupdate").click(function() {
	var countValidate = 0;  
    var xapp = $('.xappliedproderr').text();

    if (xapp != "") {
        alert('Please check the highlighted (red) OR application !. Remove application first then re-applied.');
        return false;
    }
    
             
    var stat = "<?php echo $data['status'] ?>";
    if (stat == 'O') {
        var validate_fields = ['orno', '#payeecode','#payeename', '#amountpaid'];    
    }
    
    var checkexdeal = $('#exdeal_note').val();  
    if (checkexdeal == 1) {
        $('#bank').val('');
    }
    
    if (checkexdeal == 1) {
        var validate_fields = ['#orno', '#payeecode','#payeename', '#address1', 
                       '#amountpaid', '#amountinwords', '#assignedamount', '#collector', '#branch'];    
    } else {
        var validate_fields = ['#orno', '#payeecode','#payeename', '#address1', 
                       '#amountpaid', '#amountinwords', '#assignedamount', '#collector', '#bank', '#branch'];
    }

	for (x = 0; x < validate_fields.length; x++) {			
		if($(validate_fields[x]).val() == "") {                        
			$(validate_fields[x]).css(errorcssobj);          
		  	countValidate += 1;
		} else {        
		  	$(validate_fields[x]).css(errorcssobj2);       
		}        
	} 
	if (countValidate == 0) {
		
		var amoutpaid = $("#amountpaid").val();
		var assignamount = $("#assignedamount").val();
		var x = amoutpaid.replace(/\,/g,'');
		var z = assignamount.replace(/\,/g,'');
		if (parseFloat(z) > parseFloat(x)) {
			alert("Assign amount must not be greather than amount paid!");
			return false;
		} else {	
		$("#form_savepayment").submit();				
		}
	} else {			
		return false;
	}	
});

function validateORNumber($orno) {

	var amoutpaid = $("#amountpaid").val();
	var assignamount = $("#assignedamount").val();
	var x = amoutpaid.replace(/\,/g,'');   
	var z = assignamount.replace(/\,/g,'');   
	if (parseFloat(z) > parseFloat(x)) {
		alert("Assign amount must not be greather than amount paid!");
		return false;
	} 
    var checkexdeal = $('#exdeal_note').val();

    if (checkexdeal == 1) {
        $('#bank').val('');
    }
	$.ajax({
		url: "<?php echo site_url('payment/validateORnumber') ?>",
		type: "post",
		data: {orno: $orno},
		success: function(response) {
			if (response == true) {
				alert('OR Number already exist!');
				$('#orno').val('').focus();
			} else {$("#form_savepayment").submit();}  
		}
	});
}
$("#orno").mask('99999999');
$("#ordate").datepicker({dateFormat: 'yy-mm-dd'});
$("#wvatpercent, #wtaxpercent, #ppdpercent").keyup(function(){
	recomputePercent();	
});

function recomputePercent() {
	var $amountpaid = $("#amountpaid").val();
	var $vatcode = $("#vatcode").val();
	var $wvatpercent = $("#wvatpercent").val();
	var $wtaxpercent = $("#wtaxpercent").val();
    var $ppdpercent = $("#ppdpercent").val();
	
	$.ajax({
		url: "<?php echo site_url('payment/getRecomputeValuePercent') ?>",
		type: "post",
		data: {amountpaid: $amountpaid, vatcode: $vatcode, wvatpercent: $wvatpercent, wtaxpercent: $wtaxpercent, ppdpercent: $ppdpercent},
		success: function(response) {
			$response = $.parseJSON(response);
			$("#wvatamount").val($response['wvatamount']);		
			$("#wtaxamount").val($response['wtaxamount']);
			$("#ppdamount").val($response['ppdamount']);
			recompute();
		}
	});
}
$("#vatcode").change(function(){
	var $amountpaid = $("#amountpaid").val();
	if ($amountpaid == "0.00") {
		alert("Amount paid must not equal to zero!"); $("#vatcode").val(''); return false;
	} else {
		recompute();	
	}
});
$("#wvatamount, #wtaxamount, #ppdamount").keyup(function(){
	recompute();
});
function recompute()
{
	var $amountpaid = $("#amountpaid").val();
	var $vatcode = $("#vatcode").val();
	var $wvatamount = $("#wvatamount").val();
	var $wtaxamount = $("#wtaxamount").val();
	var $ppdamount = $("#ppdamount").val();
 
	$.ajax({
		url: "<?php echo site_url('payment/getRecomputeValue') ?>",
		type: "post",
		data: {amountpaid: $amountpaid, vatcode: $vatcode, wvatamount: $wvatamount, wtaxamount: $wtaxamount, ppdamount: $ppdamount},
		success: function(response) {
			$response = $.parseJSON(response);
			$("#vatablesale").val($response['netvatablesale']);	
			$("#vatexempt").val($response['vatexempt']);
			$("#vatzerorated").val($response['vatzero']);
			$("#evatamount").val($response['evat']);	
			$("#evatpercent").val($response['evatpercent']);	

			$("#s_vatablesale").val($response['netvatablesale']);	
			$("#s_vatexemptsale").val($response['vatexempt']);
			$("#s_vatzeroratedsale").val($response['vatzero']);
			$("#s_totalsale").val($response['totalsale']);
			$("#s_valueaddedtax").val($response['evat']);	
			$("#s_withholdingtax").val($response['withholding']);	
			$("#s_totalpayment").val($response['totalpayment']);	
			
			var words = toWords($amountpaid);            
    			$('#amountinwords').val(words);     
		}
	});
}
$('#retrieve').click(function(){
   $.ajax({
      url: "<?php echo site_url('payment/retrieveApplied') ?>",
      type: 'post',
      data: {artype: $('#ortype').val(), vatcode: $('#vatcode').val(),   type: $('#applicationtype').val(), code: $('#payeecode').val(), choose:$('#prchoose:checked').val(), mykeyid: "<?php echo $hkey ?>"},
      success:function(response) {
          var $response = $.parseJSON(response);
          if ($response['empty'] == '0') {
              alert('No record found!');
          }
          $('.search_list').html($response['search_list']);
      } 
   }); 
});
$('#filterinvoice').click(function(){
   var $f_invno = $('#f_invno').val();
   $.ajax({
      url: "<?php echo site_url('payment/retrieveAppliedFilter') ?>",
      type: 'post',
      data:{artype: $('#ortype').val(), vatcode: $('#vatcode').val(), type: $('#applicationtype').val(), code: $('#payeecode').val(), choose:$('#prchoose:checked').val(), mykeyid: "<?php echo $hkey ?>", f_invno: $f_invno},
      success:function(response) {
          var $response = $.parseJSON(response);
          if ($response['empty'] == '0') {
              alert('No record found!');
          }
          $('.search_list').html($response['search_list']);
      } 
   }); 
});
$("#notarialfee, #ccdisc").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#add_paymenttype").click(function(){
	$.ajax({
		url: "<?php echo site_url('payment/paymenttype_view') ?>",
		type: "post",
		data: {mykeyid: "<?php echo $hkey ?>"},
		success:function(response){
			$response = $.parseJSON(response);
			$('#paymenttype_view').html($response['paymenttype_view']).dialog('open');
		}
	});
});
$('#paymentapplied_view, #updatepaymentapplied_view, #singleinvoice_view, #paymentapplieddm_view, #updatepaymentapplieddm_view, #viewbooking_view, #prcheckdue_view').dialog({
	autoOpen: false, 
	closeOnEscape: false,
	draggable: true,
	width: 670,    
	height:'auto',
	modal: true,
	resizable: false
});
$('#lookup_view, #importpr_view').dialog({
	autoOpen: false, 
	closeOnEscape: false,
	draggable: true,
	width: 550,    
	height:'auto',
	modal: true,
	resizable: false
});
$('#revenue_view, #orappsingleinv_view, #importaonumrevenue, #changepaymenttypeview').dialog({
	autoOpen: false, 
	closeOnEscape: false,
	draggable: true,
	width: 300,    
	height: 'auto',
	modal: true,
	resizable: false
});
$('#paymenttype_view').dialog({
	autoOpen: false, 
	closeOnEscape: false,
	draggable: true,
	width: 400,    
	height: 'auto',
	modal: true,
	resizable: false
});
/*$("#bank").change(function(){
    $.ajax({
        url: "<?php #echo site_url('payment/ajxGetBranch') ?>",
        type: 'post',
        data: {bank: $(':input[name=bank]').val()},
        success: function(response){
        
            var $response = $.parseJSON(response);
            $('#branch').empty();    
            if ($response['branch'] == "") {
                $('#branch').append("<option value=''>--</option>");    
            } else {
                $.each($response['branch'], function(i)
                {
                    var item = $response['branch'][i];
                    var option = $('<option>').val(item['id']).text(item['bbf_bnch']);
                    $('#branch').append(option);                            
                });    
            }
        }
    });
});*/   
$("#ppdstatus").click(function() {
	var $stat = $("#ppdstatus:checked").val();
	if ($stat == 1) {
		$(".radio_ppd").removeAttr("readonly").autoNumeric();	
	} else { $(".radio_ppd").attr("readonly", "readonly").val('0.00').removeData('autoNumeric'); recomputePercent();	}
});
$("#wtaxstatus").click(function() {
	var $stat = $("#wtaxstatus:checked").val();
	if ($stat == 1) {
		$(".radio_wtax").removeAttr("readonly").autoNumeric();		
	} else { $(".radio_wtax").attr("readonly", "readonly").val('0.00').removeData('autoNumeric'); recomputePercent();	}
});
$("#wvatstatus").click(function() {
	var $stat = $("#wvatstatus:checked").val();
	if ($stat == 1) {
		$(".radio_wvat").removeAttr("readonly").autoNumeric();		
	} else { $(".radio_wvat").attr("readonly", "readonly").val('0.00').removeData('autoNumeric'); recomputePercent();	}
});
$("#evatstatus").click(function() {
    var $stat = $("#evatstatus:checked").val();
	var $vatcode = $("#vatcode").val();
    if ($vatcode == 3) {
        //alert('EVAT Status cannot be remove!');
        $('#evatstatus').attr('checked', 'checked');        
    } 
	if ($stat == 1) {
	    //	$(".radio_evat").removeAttr("readonly").autoNumeric();	
	} else { 
        //$(".radio_evat").attr("readonly", "readonly").val('0.00').removeData('autoNumeric'); recomputePercent();    
        $(".radio_evat").val('0.00').removeData('autoNumeric'); recomputePercent();	
    }
});
var th = ['','Thousand','Million', 'Billion','Trillion'];    

var dg = ['Zero','One','Two','Three','Four', 'Five','Six','Seven','Eight','Nine']; 
var tn = ['Ten','Eleven','Twelve','Thirteen', 'Fourteen','Fifteen','Sixteen', 'Seventeen','Eighteen','Nineteen']; 
var tw = ['Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety']; 
var ng = ['0','1','2','3','4','5','6','7','8','9']; 
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

$("#payeecode").autocomplete({                        
    source: function( request, response ) {
        $.ajax({
            url: "<?php echo site_url('payment/autocustomer') ?>",
            type: 'post',
            data: {cust_code: $(':input[name=payeecode]').val(),
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
        $(':input[name=payeename]').val(ui.item.item.cmf_name);
        $(':input[name=tin]').val(ui.item.item.cmf_tin);
        $(':input[name=zipcode]').val(ui.item.item.cmf_zip);
        $(':input[name=title]').val(ui.item.item.cmf_title);
        $(':input[name=address1]').val(ui.item.item.cmf_add1);
        $(':input[name=address2]').val(ui.item.item.cmf_add2);    
        $(':input[name=address3]').val(ui.item.item.cmf_add3);    
        $(':input[name=tel1prefix]').val(ui.item.item.cmf_telprefix1);
        $(':input[name=tel2prefix]').val(ui.item.item.cmf_telprefix2);    
        $(':input[name=tel1]').val(ui.item.item.cmf_tel1);        
        $(':input[name=tel2]').val(ui.item.item.cmf_tel2);        
        $(':input[name=celprefix]').val(ui.item.item.cmf_celprefix);                
        $(':input[name=cel]').val(ui.item.item.cmf_cel);
        $(':input[name=faxprefix]').val(ui.item.item.cmf_faxprefix);
        $(':input[name=fax]').val(ui.item.item.cmf_fax);                        
        $(':input[name=govt]').val(ui.item.item.cmf_gov);  
	    $('#vatcode').val(ui.item.item.cmf_vatcode);  
    }
});    

$("#payeename").autocomplete({     
                
    source: function( request, response ) {
        
        var $payeecode = $("#payeecode").val();
        if ($payeecode == "SUNDRIES" || $payeecode == "REVENUE") {
            // Do Nothing    
        } else {
            $.ajax({
                url: "<?php echo site_url('payment/autocustomer') ?>",
                type: 'post',
                data: {cust_code: '',
                       cust_name: $(':input[name=payeename]').val(),
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
        }             
    },
    autoFocus: false,
    minLength: 1,
    delay: 300,
    select: function(event, ui) {                                
        $(':input[name=payeecode]').val(ui.item.item.cmf_code);
        $(':input[name=tin]').val(ui.item.item.cmf_tin);
        $(':input[name=zipcode]').val(ui.item.item.cmf_zip);
        $(':input[name=title]').val(ui.item.item.cmf_title);
        $(':input[name=address1]').val(ui.item.item.cmf_add1);
        $(':input[name=address2]').val(ui.item.item.cmf_add2);    
        $(':input[name=address3]').val(ui.item.item.cmf_add3);    
        $(':input[name=tel1prefix]').val(ui.item.item.cmf_telprefix1);
        $(':input[name=tel2prefix]').val(ui.item.item.cmf_telprefix2);    
        $(':input[name=tel1]').val(ui.item.item.cmf_tel1);        
        $(':input[name=tel2]').val(ui.item.item.cmf_tel2);        
        $(':input[name=celprefix]').val(ui.item.item.cmf_celprefix);                
        $(':input[name=cel]').val(ui.item.item.cmf_cel);
        $(':input[name=faxprefix]').val(ui.item.item.cmf_faxprefix);
        $(':input[name=fax]').val(ui.item.item.cmf_fax);  
	   $(':input[name=govt]').val(ui.item.item.cmf_gov);  
       $('#vatcode').val(ui.item.item.cmf_vatcode);                           
    }
}); 
</script>

