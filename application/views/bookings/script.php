<style>
#classifiedsline_view {width:400px;}
</style>

<div id="superced_import" title="Superced Booking"></div>      
<div id="view_clientinfo" title="Client Information"></div>      
<div id="view_paymentinfo" title="Booking Payment Info"></div>
<script>
$(function() {
    
    $("#link_adtype").click(function(){
        var counterissue = "<?php echo @count($calendarlist) ?>";
        if (counterissue == 0) {
            $('#adtype').show();
            $('#adtype_dummy').hide();
            $('#agency').show();
            $('#agency_dummy').hide();
        }
    });
    $(".datepicker").datepicker({dateFormat: 'yy-mm-dd'}); 
    $('#adtype_dummy').change(function(){
        var xx = $('#adtype').val();
        
        alert('Adtype has been changed. This will reset your agency and agency commision fields');
        $('#agency').val('0');
        $('#duepercent').val('');
    });
    
    $('#adtype').change(function(){
        var xx = $('#adtype').val();
        var xxx = $('#adtype :selected').text();
        var findx = xxx.indexOf("Agency");

        if (findx >= 0) {
           // Do Nothing
           $('#agency').show();  
           $('#agency_dummy').hide();         
        } else {
            $('#agency').val('0'); 
            $('#agency').hide();    
            $('#agency_dummy').show();    
        }
        
        //return false;
        
        /*$.ajax({
            url: "<?php #echo site_url('booking/getAdtypeClass') ?>", 
            type: 'post',
            data: {xx: xx},
            success: function(response) {
                $response = $.parseJSON(response);
                $('#classification').val($response['adtype']['adtype_class']);
            }    
        });*/
          
        alert('Adtype has been changed. This will reset your agency and agency commision fields');
        $('#agency').val('0');
        $('#duepercent').val('');
    });
    
    $('#paytype_dummy').change(function(){
        var pp = $('#paytype_dummy').val();
        
        $('#paytype').val(pp);      
    });
    
    $('#adtype_dummy').change(function(){
        var pp = $('#adtype_dummy').val();
        
        $('#adtype').val(pp);      
    });
    
    //$('#tin').mask('999999999999');
    
    $("#action_paymentinfo").click(function() {
        var $aonum = "<?php echo $data['ao_num'] ?>"; 
        
        $.ajax({
            url: "<?php echo site_url('booking/view_paymentinfo') ?>", 
            type: 'post',
            data: {aonum: $aonum},
            success: function(response) {
                $response = $.parseJSON(response);
                $('#view_paymentinfo').html($response['view_paymentinfo']).dialog('open');    
            }    
        });
        $('#view_paymentinfo').dialog('open');    
    });
    
    
    $("#action_clientinfo").click(function() {
        
        var $ccode = $('#code').val();
        $.ajax({
            url: "<?php echo site_url('booking/clientInfo') ?>",
            type: "post",
            data: {code: $ccode},
            success: function (response) {
                $response = $.parseJSON(response);      
                $('#view_clientinfo').html($response['view_clientinfo']).dialog('open');    
            }    
        }); 
        
    });
    
    $("#adtext_btn").click(function(){
        var ewidth = $("#width").val();
        var elength = $("#length").val();
        var adtext = $("#adtext").val();
        $.ajax({
            url: "<?php echo site_url('booking/classifieds_editor') ?>",
            type: "post",
            data: {ewidth: ewidth, elength: elength, adtext: adtext},
            success: function(response) {
                $response = $.parseJSON(response);
                $('#classifiedsline_view').html($response['editor_view']).dialog('open');
            }
        });
    });

    $('#view_paymentinfo,  #view_clientinfo').dialog({
        autoOpen: false, 
        closeOnEscape: false,
        draggable: true,
        width: 700,    
        height:'auto',
        modal: true,
        resizable: false    
    });
    
	$('#superced_import, #autoor_view').dialog({
		autoOpen: false, 
		closeOnEscape: false,
		draggable: true,
		width: 400,    
		height:'auto',
		modal: true,
		resizable: false
	}); 

	var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
	var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

	$("#action_importsuperced").click(function() {
		$.ajax({
		   url: "<?php echo site_url('booking/supercedimport') ?>",
		   type: 'post',
		   data: {},
		   success: function(response){
			  var $response = $.parseJSON(response);	
			  $('#superced_import').html($response['supercedimport']).dialog('open');      
		   }
		});
	});	

	$("#action_duplicatebooking").click(function() {
		var confirmnew = confirm("Are you sure you want to duplicate this booking?.");
		var $aonum = "<?php echo $data['ao_num'] ?>";

		if (confirmnew) {
			window.location.href = "<?php echo base_url()?>booking/duplicate_booking/"+$aonum;
		}
	});	

	$("#action_newbooking").click(function() {
		var confirmnew = confirm("Are you sure you want new booking?.");

		if (confirmnew) {
			window.location.href = "<?php echo base_url()?>booking/booktype/<?php echo $type ?>";
		}
	});		
    
    $("#action_supercedkillbooking").click(function() {
        var confirmkill = confirm("Are you sure you want to kill this superceding booking?.");

        if (confirmkill) {
            
            var $aonum = "<?php echo $data['ao_num'] ?>";

            $.ajax({
               url: "<?php echo site_url('booking/supercedbookingKilled') ?>",
               type: 'post',
               data: {aonum: $aonum},
               success: function(response){
                  location.reload();            
               }
            });
        }    
    });

	$("#action_killbooking").click(function() {
		var confirmkill = confirm("Are you sure you want to kill this booking?.");

		if (confirmkill) {
            
		    var $aonum = "<?php echo $data['ao_num'] ?>";

		    $.ajax({
			   url: "<?php echo site_url('booking/bookingKilled') ?>",
			   type: 'post',
			   data: {aonum: $aonum},
			   success: function(response){
				  location.reload();            
			   }
		    });
		}	
	});
	
	$("#action_creditapproved").click(function() {
		var confirmcapp = confirm("Are you sure you want to approved this booking?.");

		if (confirmcapp) {
		    var $aonum = "<?php echo $data['ao_num'] ?>";

		    $.ajax({
			   url: "<?php echo site_url('booking/bookingCreditApproved') ?>",
			   type: 'post',
			   data: {aonum: $aonum},
			   success: function(response){
				  location.reload(true);
			   }
		    });
		}
	});
    
    
    $("#action_savebookingsuperced").click(function() {
        var type = "<?php echo $type ?>";        
        var adtype = $("#adtype").val();    
        
       
        var pamount = $('#amountdue').val();

        if (pamount == '0.00') {
            alert('Amount Due must not be zero');
            return false;
        }

        var countValidate = 0;  
        var validate_fields = ['#code', '#payee','#acctexec', '#adtype', 
                            '#branch', '#vatcode', '#startdate', '#enddate'];
        
        for (x = 0; x < validate_fields.length; x++) {            
            if($(validate_fields[x]).val() == "") {                        
                $(validate_fields[x]).css(errorcssobj);          
                  countValidate += 1;
            } else {        
                  $(validate_fields[x]).css(errorcssobj2);       
            }        
        } 
       
        $.ajax({
            url: "<?php echo site_url('booking/validateCode') ?>",
            type: "post",            
            data: {code : $("#code").val()},
            success: function(response) {
                if (response == "true") {   
                
                    var ppid = $('#paytype').val(); 
                    var ccode = $('#code').val();
                    
                    if ((ppid == 1 || ppid == 2) && (ccode == 'REVENUE' || ccode == 'SUNDRIES')) {
                        alert('REVENUE and SUNDRIES cannot be used for billable ad');
                        return false;
                    } else {
                                     
                    if (countValidate == 0) {         
                            var url = "<?php echo $this->uri->segment(2);?>";
                            
                            if (url == "booktype") {
                                var $type = $("#type").val();
                                var $paytype = $("#paytype").val();
                                var $mainamt = $("#amountdue").val();
                                var $bran = $("#branch").val();            
                                if ($type == "C" && $bran != "5"  && $bran != "9"  && ($paytype == "3" || $paytype == "4" || $paytype == "5")) {                
                                //if ($type == "C" &&  ($paytype == "3" || $paytype == "4" || $paytype == "5")) {     
                                
                                    $.ajax({
                                        url: "<?php echo site_url('booking/autoor') ?>",
                                        type: "post",
                                        data: {type: $type, paytype: $paytype, mainamt: $mainamt},
                                        success: function(response) {
                                            $response = $.parseJSON(response);
                                            $("#autoor_view").html($response['autoor_view']).dialog('open');
                                        }
                                    }); 
                                } else { $("#form_saveBooking").submit(); }
                            } else { $("#form_saveBooking").submit(); }
                        } else {            
                            return false;
                        }  
                    }            

                } else {
                   alert("Customer code must exist!.");  
                   $("#code").val('');        
                   return false;
                }
            }
        });  
        return false;
        
        
    });
    
   
	$("#action_savebooking").click(function() {
		var type = "<?php echo $type ?>";		
		var adtype = $("#adtype").val();		

		var countValidate = 0; 
        var aaa = $('#agency').val();
        var bbb = $('#agencycomm').val();
        var ccc = $('#paytype').val();
        //alert(aaa);    return false;
        if (aaa == "" || aaa == null) {
            aaa = 0;
        }
        
        if (aaa != 0 && bbb == 0 && ccc != 6) {
            alert('Check your agency commision');    
            return false;     
        }
        
        var validate_fields = new Array();
        
        var adtypename = $("#adtype").find('option:selected').text();
        
        var findagency = adtypename.search('Agency');
        
		if (type == "C" && adtype == "4") {
		    validate_fields = ['#code', '#payee','#acctexec', '#classification', 
						        '#adtype', '#paytype', '#adsource', '#branch', '#ratecode',  
						        '#product', '#vatcode', '#startdate', '#enddate', "#adtext", "#refno"];
		} else {			
		    validate_fields = ['#code', '#payee','#acctexec', '#classification', 
						        '#adtype', '#paytype', '#adsource', '#branch', '#ratecode',  
						        '#product', '#vatcode', '#startdate', '#enddate', "#refno"];
			$("#adtext").css(errorcssobj2);     
		}
		
        if (findagency > 1) {
            validate_fields.push("#agency"); 
            
            if ($("#agency").val() == 0) {
                alert("Please fill in the agency");
            }
             
            //$("#agency").val("");          
        } else {
            $("#agency").css(errorcssobj2);
        } 
        
        //console.debug(validate_fields);   
        //alert(validate_fields); //return false;
        //return false;
		for (x = 0; x < validate_fields.length; x++) {			
			if($(validate_fields[x]).val() == "" || $(validate_fields[x]).val() == 0) {                        
				$(validate_fields[x]).css(errorcssobj);          
			  	countValidate += 1;
                
			} else {        
			  	$(validate_fields[x]).css(errorcssobj2);  
                //alert($(validate_fields[x]).val());     
			}        
		} 
        //return false;
        $.ajax({
            url: "<?php echo site_url('booking/validateCode') ?>",
            type: "post",            
            data: {code : $("#code").val()},
            success: function(response) {
                if (response == "true") {                    
                    if (countValidate == 0) {     

                        var ppid = $('#paytype').val(); 
                        var ccode = $('#code').val();
                        
                        if ((ppid == 1 || ppid == 2) && (ccode == 'REVENUE' || ccode == 'SUNDRIES')) {  
                            alert('REVENUE and SUNDRIES cannot be used for billable ad');
                            return false;
                        } else {
                        
                            var url = "<?php echo $this->uri->segment(2);?>";
                            
                            var url2 = "<?php echo $this->uri->segment(2);?>";           
                            
                            if (url == "booktype") {
                                var $type = $("#type").val();
                                var $paytype = $("#paytype").val();
                                var $mainamt = $("#amountdue").val();
                                var $bran = $("#branch").val();     

                                if ($type == "C" && $bran != "5" && $bran != "9"  && ($paytype == "3" || $paytype == "4" || $paytype == "5")) {                
                                //if ($type == "C" &&  ($paytype == "3" || $paytype == "4" || $paytype == "5")) {              
                                
                                    $.ajax({
                                        url: "<?php echo site_url('booking/autoor') ?>",
                                        type: "post",
                                        data: {type: $type, paytype: $paytype, mainamt: $mainamt},
                                        success: function(response) {
                                            $response = $.parseJSON(response);
                                            $("#autoor_view").html($response['autoor_view']).dialog('open');
                                        }
                                    }); 
                                } else { 
                                    $("#form_saveBooking").submit(); 
                                }
                            } else if (url2 == "duplicate_booking") {
                                var $type = $("#type").val();
                                var $paytype = $("#paytype").val();
                                var $mainamt = $("#amountdue").val();
                                var $bran = $("#branch").val();     

                                if ($type == "C" && $bran != "5" && $bran != "9" && ($paytype == "3" || $paytype == "4" || $paytype == "5")) {                
                                //if ($type == "C" &&  ($paytype == "3" || $paytype == "4" || $paytype == "5")) {              
                                
                                    $.ajax({
                                        url: "<?php echo site_url('booking/autoor') ?>",
                                        type: "post",
                                        data: {type: $type, paytype: $paytype, mainamt: $mainamt},
                                        success: function(response) {
                                            $response = $.parseJSON(response);
                                            $("#autoor_view").html($response['autoor_view']).dialog('open');
                                        }
                                    }); 
                                } else { 
                                    $("#form_saveBooking").submit(); 
                                }        
                            }
                            
                            else { $("#form_saveBooking").submit(); }
                        }
                        } else {           
                            return false;
                  
                        
                    }          

                } else {
                   alert("Customer code must exist!.");  
                   $("#code").val('');        
                   return false;
                }
            }
        });  
        return false;
        
		
	});

	$("#width, #length").autoNumeric({});  
    	$("#misc_charges").select2({maximumSelectionSize: 6,tags:[<?php echo substr($newmisc, 0, -1); ?>]});

	$("#misc_charges").change(function(){ 
		var $charges = $("#misc_charges").val();
		var $product = $('#product').val();
		var $classification = $('#classification').val();
		if ($charges == null) {
			$("#misc1").val('');$("#miscper1").val('');
			$("#totalprem").val('');$("#totaldisc").val('');
			return false;		
		}
        
		if ($product != "" && $classification != "") {
			$.ajax({
				url: "<?php echo site_url('booking/ajaxmisc_charges') ?>",
				type: "post",
				data: {charges: $charges, product: $product, classification: $classification, type: "<?php echo $this->uri->segment(3); ?>"},
				success: function(response) {
					$response = $.parseJSON(response);
					$("#misc1").val($response['misc1']);$("#misc2").val($response['misc2']);$("#misc3").val($response['misc3']);					
					$("#misc4").val($response['misc4']);$("#misc5").val($response['misc5']);$("#misc6").val($response['misc6']);					
					$("#miscper1").val($response['miscper1']);$("#miscper2").val($response['miscper2']);$("#miscper3").val($response['miscper3']);					
					$("#miscper4").val($response['miscper4']);$("#miscper5").val($response['miscper5']);$("#miscper6").val($response['miscper6']);					
					$("#totalprem").val($response['totalprem']);$("#totaldisc").val($response['totaldisc']);
				}
			});
		}else { $('#warning_msg').html('Product, Classification must not be empty!').dialog('open'); $("#misc_charges").select2('data', null); return false; }
	}); 

	$('#product').change(function(){	
		$.ajax({
			url: "<?php echo site_url('bookingissue/getdaysofproduct') ?>",
			type: "post",
			data: {aonum: "<?php echo $aonum ?>", product: $('#product').val(), type: "<?php echo $this->uri->segment(3); ?>"},
			success:function(response) {
				$response = $.parseJSON(response);
				$("#calendar_div").html($response['calendar']);
			}
		});		
		$('#classification').focus();
	});


	$('#classification').change(function(){	
		ratecmmajax();
		$('#ratecode').focus();			
	});
	$('#ratecode').change(function(){	
		ratecmmajax();		
		$('#vatcode').focus();	
	});


	function ratecmmajax() {
		$.ajax({
		   url: "<?php echo site_url('booking/ajaxrateCCM')?>",
		   type: 'post',
		   data: {product: $('#product').val(), classx: $('#classification').val(),ratecode : $('#ratecode').val()},
		   success: function(response) {            
				$response = $.parseJSON(response);

				$('#raterate').val($response['ratevalue']['adtyperate_rate']);
		   }
		});

		return true;
	}

	$( "#code" ).autocomplete({            
	    source: function( request, response ) {
		   $.ajax({
			  url: "<?php echo site_url('booking/autocustomer') ?>",
			  type: 'post',
			  data: {cust_name: "",
				    cust_code: $(':input[name=code]').val()                           
				    },
			  success: function(data) {
				 var $data = $.parseJSON(data);
				 response($.map($data, function(item) {
				      return {
				             label: item.cmf_code + ' - ' + item.cmf_name,
				             value: item.cmf_code,
				             item: item                                     
				      }
				 }))
			  }
		   });                
	    },
	    autoFocus: false,
	    minLength: 1,
	    delay: 300,
	    select: function(event, ui) {
		   $('#credit_status').val(ui.item.item.cmf_crstatusname);
		   $(':input[name=payee]').val(ui.item.item.cmf_name);
		   $(':input[name=title]').val(ui.item.item.cmf_title);
		   $(':input[name=address1]').val(ui.item.item.cmf_add1);
		   $(':input[name=address2]').val(ui.item.item.cmf_add2);
		   $(':input[name=address3]').val(ui.item.item.cmf_add3);    
		   $(':input[name=tin]').val(ui.item.item.cmf_tin);    
		   $(':input[name=tel1prefix]').val(ui.item.item.cmf_telprefix1);
		   $(':input[name=tel2prefix]').val(ui.item.item.cmf_telprefix2);    
		   $(':input[name=tel1]').val(ui.item.item.cmf_tel1);        
		   $(':input[name=tel2]').val(ui.item.item.cmf_tel2);        
		   $(':input[name=celprefix]').val(ui.item.item.cmf_celprefix);
		   $(':input[name=faxprefix]').val(ui.item.item.cmf_faxprefix);
		   $(':input[name=cel]').val(ui.item.item.cmf_cel);
		   $(':input[name=fax]').val(ui.item.item.cmf_fax);    
		   $(':input[name=country]').val(ui.item.item.cmf_country);    
		   $(':input[name=zipcode]').val(ui.item.item.cmf_zip);                    
		   
           var optionx = $('<option>').val(ui.item.item.cmf_vatcode).text(ui.item.item.vat_name);    
           $(':input[name=vatcode]').val(ui.item.item.cmf_vatcode);     
           $(':input[name=vatcodedum]').empty();      
           $(':input[name=vatcodedum]').append(optionx);                            
           
              
		   $(':input[name=vatrate]').val(ui.item.item.cmf_vatrate);    
		   $(':input[name=acctexec]').val(ui.item.item.cmf_aef);    
		   $(':input[name=creditterm]').val(ui.item.item.cmf_crf);        
		   $(':input[name=paytype]').val(ui.item.item.cmf_paytype);    
		   $(':input[name=pluspercent]').val(ui.item.item.cmf_vatrate);       
           
		   var $cust_id = ui.item.item.id;
		   $.ajax({
			  url: "<?php echo site_url('booking/ajaxAgency') ?>",
			  type: 'post',
			  data: {cust_id: $cust_id},
			  success: function(response)
			  {
				 var $xponse = $.parseJSON(response);
                 $('#agency').empty();      
				 $('#agencysuperced').empty();      
                 $('#agency').append($('<option>').val('').text('---'));
				 $('#agencysuperced').append($('<option>').val('').text('---'));
				 $.each($xponse['agency'], function(i)
				 {
				     var xitem = $xponse['agency'][i];
				     var option = $('<option>').val(xitem['id']).text(xitem['cmf_code'] + ' - ' +xitem['cmf_name']);
                     $('#agency').append(option);                            
				     $('#agencysuperced').append(option);                            
				 });     
				 
				 var type = '<?php echo $type ?>';
				 if ($xponse['agency'] == null || $xponse['agency'] == "") {
				     var adt = "2";
				     if (type != "M") {
				     adtypeofcustomer(adt, type);                            
				     }
				 } else {                            
				     var adt = "1";                    
				     if (type != "M") {
				     adtypeofcustomer(adt, type);                            
				     }
				 }                 
			  }
		   });                        
	    }
	});  
	
	$( "#payee" ).autocomplete({            
	    source: function( request, response ) {
		   $.ajax({
			  url: "<?php echo site_url('booking/autocustomer') ?>",
			  type: 'post',
			  data: {cust_code: "",
				    cust_name: $(':input[name=payee]').val()                           
				    },
			  success: function(data) {
				 var $data = $.parseJSON(data);
				 response($.map($data, function(item) {
				      return {
				             label: item.cmf_code + ' - ' + item.cmf_name,
				             value: item.cmf_name,
				             item: item                                     
				      }
				 }))
			  }
		   });                
	    },
	    autoFocus: false,
	    minLength: 1,
	    delay: 300,
	    select: function(event, ui) {
		   $('#credit_status').val(ui.item.item.cmf_crstatusname);
		   $(':input[name=code]').val(ui.item.item.cmf_code);
		   $(':input[name=title]').val(ui.item.item.cmf_title);
		   $(':input[name=address1]').val(ui.item.item.cmf_add1);
		   $(':input[name=address2]').val(ui.item.item.cmf_add2);
		   $(':input[name=address3]').val(ui.item.item.cmf_add3);    
		   $(':input[name=tin]').val(ui.item.item.cmf_tin);    
		   $(':input[name=tel1prefix]').val(ui.item.item.cmf_telprefix1);
		   $(':input[name=tel2prefix]').val(ui.item.item.cmf_telprefix2);    
		   $(':input[name=tel1]').val(ui.item.item.cmf_tel1);        
		   $(':input[name=tel2]').val(ui.item.item.cmf_tel2);        
		   $(':input[name=celprefix]').val(ui.item.item.cmf_celprefix);
		   $(':input[name=faxprefix]').val(ui.item.item.cmf_faxprefix);
		   $(':input[name=cel]').val(ui.item.item.cmf_cel);
		   $(':input[name=fax]').val(ui.item.item.cmf_fax);    
		   $(':input[name=country]').val(ui.item.item.cmf_country);    
		   $(':input[name=zipcode]').val(ui.item.item.cmf_zip);                    
		   
           var optionx = $('<option>').val(ui.item.item.cmf_vatcode).text(ui.item.item.vat_name);    
           $(':input[name=vatcode]').val(ui.item.item.cmf_vatcode);     
           $(':input[name=vatcodedum]').empty();      
           $(':input[name=vatcodedum]').append(optionx);                            
           
		   $(':input[name=vatrate]').val(ui.item.item.cmf_vatrate);    
		   $(':input[name=acctexec]').val(ui.item.item.cmf_aef);    
		   $(':input[name=creditterm]').val(ui.item.item.cmf_crf);        
		   $(':input[name=paytype]').val(ui.item.item.cmf_paytype);    
		   $(':input[name=pluspercent]').val(ui.item.item.cmf_vatrate);                        
		   var $cust_id = ui.item.item.id;
		   $.ajax({
			  url: "<?php echo site_url('booking/ajaxAgency') ?>",
			  type: 'post',
			  data: {cust_id: $cust_id},
			  success: function(response)
			  {
				 var $xponse = $.parseJSON(response);
                 $('#agency').empty();
				 $('#agencysuperced').empty();
                 $('#agency').append($('<option>').val('').text('---'));
				 $('#agencysuperced').append($('<option>').val('').text('---'));
				 $.each($xponse['agency'], function(i)
				 {
				     var xitem = $xponse['agency'][i];
				     var option = $('<option>').val(xitem['id']).text(xitem['cmf_code'] + ' - ' +xitem['cmf_name']);
                     $('#agency').append(option);                            
				     $('#agencysuperced').append(option);                            
				 });     
				 
				 var type = '<?php echo $type ?>';
				 if ($xponse['agency'] == null || $xponse['agency'] == "") {
				     var adt = "2";
				     if (type != "M") {
				     adtypeofcustomer(adt, type);                            
				     }
				 } else {                            
				     var adt = "1";                    
				     if (type != "M") {
				     adtypeofcustomer(adt, type);                            
				     }
				 }                 
			  }
		   });                        
	    }
	});  

	function adtypeofcustomer(adt, type)
	{
	    $.ajax({
		   url: '<?php echo site_url('booking/ajaxAdtype') ?>',
		   type: 'post',
		   data: {type: type, adt: adt},
		   success: function(response){
		       var $xponse = $.parseJSON(response);
		       $('#adtype').empty();
		       $('#adtype').append($('<option>').val('').text('--'));                        
		       $.each($xponse['adtype'], function(i)
		       {
		           var xitem = $xponse['adtype'][i];
		           var option = $('<option>').val(xitem['id']).text(xitem['adtype_name']);
		           $('#adtype').append(option);                            
		       });        
		   }
	    });
	}

	$("#vatcode").change(function(){
		$.ajax({
		   url: '<?php echo site_url('vat/ajaxVat') ?>',
		   type: 'post',
		   data: {vat : $(":input[name='vatcode']").val()},
		   success: function(response) {
		       $("#vatrate").val(response);             
		       $("#pluspercent").val(response);
		   } 
	    });                     
	});

	$("#agency").change(function(){
        var $agencyid = $("#agency").val();   
        //alert($agencyid);    
	    $.ajax({
		   url: "<?php echo site_url('booking/ajxAgencyAE') ?>",
		   type: 'post',
		   data: {agencyid: $agencyid},
		   success: function(response) {
		        if ($agencyid != 0) {
                   $("#duepercent").val('15');
                   $("#acctexec").val(response);
               } else {
                   $("#duepercent").val('0');
               }
		       
		   }
	    })
	});  
    
    $("#agencysuperced").change(function(){
        var $agencyid = $(":input[name='agencysuperced']").val();    
        //alert($agencyid); 
        $.ajax({
           url: "<?php echo site_url('booking/ajxAgencyAE') ?>",
           type: 'post',
           data: {agencyid: $agencyid},
           success: function(response) {
               if ($agencyid != 0) {
                   $("#duepercent").val('15');
                   $("#acctexec").val(response);
               } else {
                   $("#duepercent").val('0');
               }
               
           }
        })
    });  

	$('#width, #length').keyup(function(){    
	    $w = parseFloat($('#width').val());
	    $l = parseFloat($('#length').val());    
	    $total = $w * $l;     
	    if (isNaN($total)) { $total = 0; }       
	    $('#totalsize').val($total.toFixed(2));    
	}).keyup(); 

	$("#adsize").change(function(){
		$.ajax({
		   url: "<?php echo site_url('adsize/ajaxSize') ?>",
		   type: 'post',
		   data: {adsize : $(":input[name='adsize']").val()},
		   success: function(response) {
			  var $response = $.parseJSON(response);
			  $('#width').val($response['adsize']['adsize_width']);
			  $('#length').val($response['adsize']['adsize_length']);    
			  $w = parseFloat($response['adsize']['adsize_width']);
			  $l = parseFloat($response['adsize']['adsize_length']);
			  $total = $w * $l; 
			  if (isNaN($total)) { $total = 0; }                  
			  $('#totalsize').val($total.toFixed(2));  
		   } 
		});                 
	});

});
</script>
