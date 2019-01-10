<div class="block-fluid">       
	<div class="row-form-booking">
		<div class="span2" style="width:100px">Payment Type</div>	
		<div class="span2">
		<select name="payment_type" id="payment_type">    
			<?php if ($paytype == "3") : ?>
			<option value="3">Cash</option>
			<?php endif; ?>
			<?php if ($paytype == "5") : ?>
			<option value="5">Check</option>
			<?php endif; ?>
			<?php if ($paytype == "4") : ?>
			<option value="4">Credit Card</option>                
			<?php endif; ?>
		</select>
		</div>
		<div class="clear"></div>	
	</div>
	<?php if ($paytype == "5") : ?>
    <div class="row-form-booking-hidden" id="display_checkbank">
        <div class="span2" style="width:100px">Check Bank.</div>    
        <div class="span2">
            <select id="auto_checkbank" name="auto_checkbank">
                <option value="">--</option>
                <?php foreach ($checkbanks as $checkbanks) : ?>
                <option value="<?php echo $checkbanks['id'] ?>"><?php echo $checkbanks['bmf_name'] ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking-hidden" id="display_checkbankbranch">
        <div class="span2" style="width:100px">Check Branch.</div>    
        <div class="span2">
            <select id="auto_checkbankbranch" name="auto_checkbankbranch">
            </select>
        </div>
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking-hidden" id="display_checkno">
        <div class="span2" style="width:100px">Check No.</div>    
        <div class="span2"><input type="text" id="auto_checknum" name="auto_checknum"/></div>
        <div class="clear"></div>    
    </div>
	<div class="row-form-booking-hidden" id="display_checkdate">
		<div class="span2" style="width:100px">Check Date</div>	
		<div class="span2"><input type="text" id="auto_checkdate" name="auto_checkdate"/></div>
		<div class="clear"></div>	
	</div>
	<?php endif; ?>
	<?php if ($paytype == "4") : ?>
	<div class="row-form-booking-hidden" id="display_creditcard">
		<div class="span2" style="width:100px">Credit Card</div>	
		<div class="span2">
			<select name="auto_cardtype" id="auto_cardtype">
			<option value="">--</option>
			<?php foreach($creditcard as $creditcard) : ?>                   
			<option value="<?php echo $creditcard['id'] ?>"><?php echo $creditcard['creditcard_name'] ?></option>
			<?php endforeach; ?>    
			</select>    
		</div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-hidden" id="display_creditcardno">
		<div class="span2" style="width:100px">Credit Card No.</div>	
		<div class="span2"><input type="text" id="auto_cardnum" name="auto_cardnum"/></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-hidden" id="display_authorizationo">
		<div class="span2" style="width:100px">Authorization No.</div>	
		<div class="span2"><input type="text" id="auto_authorization" name="auto_authorization"/></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-hidden" id="display_expirydate">
		<div class="span2" style="width:100px">Expiry Date</div>	
		<div class="span2"><input type="text" id="auto_expirydate" name="auto_expirydate"/></div>
		<div class="clear"></div>	
	</div>
	<?php endif; ?>
	<div class="row-form-booking">
		<div class="span2" style="width:100px">OR No#</div>	
		<div class="span2"><input type="text" style="text-align:right" id="auto_ornum" name="auto_ornum"/></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:100px">Total Amount Due</div>	
		<div class="span2"><input type="text" style="text-align:right" id="auto_totaloramt" name="auto_totaloramt" value="<?php echo number_format($mainamt, 2, '.', ',') ?>" readonly="readonly"/></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:100px">Amount Paid</div>	
		<div class="span2"><input type="text" style="text-align:right" id="auto_oramt" name="auto_oramt" value="0.00"/></div>
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:160px"> 
		<table cellpadding="0" cellspacing="0" style="white-space:nowrap;" class="table" id="tSortable_2">
		   <thead>
			<tr>						
				<th width="10px"></th>
				<th width="40px">Details</th>
				<th width="40px">Amount</th>
				<th width="10px">Percent</th>
				<th width="70px">Remarks</th>                                 
			</tr>
		   </thead>
		   <tbody>
			<tr>
				<td width="10px"><input type="checkbox" name="auto_wtaxstat" id="auto_wtaxstat"/></td>
				<td width="40px">WTAX</td>
				<td width="40px"><input type="text" class="wtax_c text-right" readonly="readonly" name="auto_wtaxamt" id="auto_wtaxamt" style="width: 70px;"/></td>
				<td width="30px"><input type="text" class="wtax_c text-right" readonly="readonly" name="auto_wtaxper" id="auto_wtaxper" style="width: 40px;"/></td>
				<td width="60px"><input type="text" class="wtax_c" name="auto_wtaxrem" readonly="readonly" id="auto_wtaxrem" style="width: 100px;"/></td> 			
			</tr>
			<tr>
				<td width="10px"><input type="checkbox" name="auto_wvatstat" id="auto_wvatstat"/></td>
				<td width="40px">WVAT</td>
				<td width="40px"><input type="text" class="wvat_c text-right" readonly="readonly" name="auto_wvatamt" id="auto_wvatamt" style="width: 70px;"/></td>
				<td width="30px"><input type="text" class="wvat_c text-right" readonly="readonly" name="auto_wvatper" id="auto_wvatper" style="width: 40px;"/></td>
				<td width="60px"><input type="text" class="wvat_c" name="auto_wvatrem" readonly="readonly" id="auto_wvatrem" style="width: 100px;"/></td> 			
			</tr>	
			<tr>
				<td width="10px"><input type="checkbox" name="auto_otherstat" id="auto_otherstat"/></td>
				<td width="40px">Others</td>
				<td width="40px"><input type="text" class="other_c text-right" readonly="readonly" name="auto_otheramt" id="auto_otheramt" style="width: 70px;"/></td>
				<td width="30px"><input type="text" class="other_c text-right" readonly="readonly" name="auto_otherper" id="auto_otherper" style="width: 40px;"/></td>
				<td width="60px"><input type="text" class="other_c" name="auto_otherrem" readonly="readonly" id="auto_otherrem" style="width: 100px;"/></td> 			
			</tr>					  					  			                     
		   </tbody>
	    </table>
	    <div class="clear"></div>
	</div>
	<div class="row-form-booking">
		<div class="span3" style="margin-left:15%;">
            <button class="btn btn-block" type="button" id="btn_saveandautoor" name="btn_saveandautoor">Save Booking</button>
        	</div> 
		<div class="clear"></div>
	</div>
</div>
<script>
$("#auto_ornum").mask('99999999');  
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#FFFFFF','border' : '1px solid #BBBBBB'};  
$('#auto_expirydate').datepicker({dateFormat: 'yy-mm-dd'});
$('#auto_checkdate').datepicker({dateFormat: 'yy-mm-dd'});
//$("#").mask('99999999');

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
$(document).ready(function(){

    $('#auto_oramt').autoNumeric();
    
    //$('#auto_wtaxamt').autoNumeric();
    //$('#auto_wvatamt').autoNumeric();
    //$('#auto_otheramt').autoNumeric();
    
    $('#auto_wtaxstat').click(function() {
       var wtax_s = $('#auto_wtaxstat:checked').val();
       
       if (wtax_s == "on") {           
           $('.wtax_c').removeAttr('readonly'); 
           $('#auto_wtaxamt').autoNumeric();       
       } else {           
           $('.wtax_c').attr('readonly', 'readonly').val('');   
           //$('#auto_wtaxamt').autoNumeric('destroy');
       }
    });
    
    $('#auto_wvatstat').click(function() {
       var wvat_s = $('#auto_wvatstat:checked').val();
       
       if (wvat_s == "on") {           
           $('.wvat_c').removeAttr('readonly'); 
           $('#auto_wvatamt').autoNumeric();  
       } else {           
           $('.wvat_c').attr('readonly', 'readonly').val('');   
           //$('#auto_wvatamt').removeClass('autoNumeric');        
       }
    });      
    
     $('#auto_otherstat').click(function() {
       var other_s = $('#auto_otherstat:checked').val();
       
       if (other_s == "on") {           
           $('.other_c').removeAttr('readonly');   
           $('#auto_otheramt').autoNumeric();             
       } else {           
           $('.other_c').attr('readonly', 'readonly').val(''); 
           //$('#auto_otheramt').removeClass('autoNumeric');        
       }
    });


    $('#btn_saveandautoor').click(function(){
        var paytype = "<?php echo $paytype; ?>";
        
        var c_oramt = $('#auto_oramt').val();
        var c_wvatamt = $('#auto_wvatamt').val();
        var c_wtaxamt = $('#auto_wtaxamt').val();
        var c_otheramt = $('#auto_otheramt').val();
        var cc_oramt = c_oramt.replace(',','');   
        var cc_wvatamt = c_wvatamt.replace(',','');   
        var cc_wtaxamt = c_wtaxamt.replace(',','');   
        var cc_otheramt = c_otheramt.replace(',','');   
        
        if (cc_oramt == '') {cc_oramt = 0;}                
        if (cc_wvatamt == '') {cc_wvatamt = 0;}                
        if (cc_wtaxamt == '') {cc_wtaxamt = 0;}                
        if (cc_otheramt == '') {cc_otheramt = 0;}                
        
        var cc_totalpayment = parseFloat(cc_oramt) + parseFloat(cc_wvatamt) + parseFloat(cc_wtaxamt) + parseFloat(cc_otheramt);
        
        var c_amtdue = '<?php echo $mainamt ?>';
        var cc_amtdue = c_amtdue.replace(',','');
        if (cc_amtdue == '') {cc_amtdue = 0;}   
        //alert (cc_totalpayment);
        //alert (cc_totalpayment);    return false;
        
/*        if (cc_totalpayment < cc_amtdue) {
            alert('Amount due must not be less than totalpayment amount!.');            
            return false;
        } else if (cc_totalpayment > cc_amtdue) {
            alert('Amount due must not be greater than totalpayment amount!.');            
            return false;
        } else {
            alert("equal");
        }
        
        return false;  */           
        if (cc_totalpayment < cc_amtdue) {
            alert('Amount due must not be less than totalpayment amount!.');            
            return false;
        } else if (cc_amtdue > cc_totalpayment) {
            alert('Totalpayment must not be greather than amount due!.');            
            return false;
        } else {
        
            var countValidate = 0;  
            
            var $$paytype = '<?php echo $paytype ?>';

            if ($$paytype == 3) {
                var validate_fields = ['#auto_ornum', '#auto_oramt'];    
            } else if ($$paytype == 4) {
                var validate_fields = ['#auto_ornum', '#auto_oramt', '#auto_cardtype', '#auto_cardnum', '#auto_authorization', '#auto_expirydate'];
            } else if ($$paytype == 5) {
                var validate_fields = ['#auto_ornum', '#auto_ornum', '#auto_checknum', '#auto_checkdate', '#auto_checkbank', '#auto_checkbankbranch'];
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
                if ($$paytype == 3) { 
                    // Cash
                    $('#mor_ornum').val($('#auto_ornum').val());
                    $('#mor_oramt').val($('#auto_oramt').val());
                } else if ($$paytype == 4) {   
                    // Credit Card
                    $('#mor_ornum').val($('#auto_ornum').val());
                    $('#mor_oramt').val($('#auto_oramt').val());
                    
                    $('#mor_cardholder').val($('#auto_carholder').val());
                    $('#mor_cardtype').val($('#auto_cardtype').val());
                    $('#mor_cardnum').val($('#auto_cardnum').val());
                    $('#mor_authorization').val($('#auto_authorization').val());
                    $('#mor_expirydate').val($('#auto_expirydate').val());
                } else if ($$paytype == 5) {      
                    // Check
                    $('#mor_ornum').val($('#auto_ornum').val());
                    $('#mor_oramt').val($('#auto_oramt').val());
                    
                    $('#mor_checknum').val($('#auto_checknum').val());
                    $('#mor_checkdate').val($('#auto_checkdate').val());
                    $('#mor_checkbank').val($('#auto_checkbank').val());
                    $('#mor_checkbankbranch').val($('#auto_checkbankbranch').val());
                }
                var wtax_s = $('#auto_wtaxstat:checked').val();   
                var wvat_s = $('#auto_wvatstat:checked').val();    
                var other_s = $('#auto_otherstat:checked').val();         
				
			 var words = toWords(cc_totalpayment);            
                $('#mor_oramtwords').val(words);    
                if (wtax_s == "on") {           
                $('#mor_wtaxstat').val('1');
                }else {$('#mor_wtaxstat').val('0');}                
                $('#mor_wtaxamt').val($('#auto_wtaxamt').val());                
                $('#mor_wtaxper').val($('#auto_wtaxper').val());
                $('#mor_wtaxrem').val($('#auto_wtaxrem').val());
                
                if (wvat_s == "on") {           
                    $('#mor_wvatstat').val('1');
                } else {$('#mor_wvatstat').val('0');};
                $('#mor_wvatamt').val($('#auto_wvatamt').val());     
                $('#mor_wvatper').val($('#auto_wvatper').val());
                $('#mor_wvatrem').val($('#auto_wvatrem').val());
                
                if (other_s == "on") {           
                    $('#mor_otherstat').val('1');
                } else {$('#mor_otherstat').val('0');}
                $('#mor_otheramt').val($('#auto_otheramt').val());
                $('#mor_otherper').val($('#auto_otherper').val());
                $('#mor_otherrem').val($('#auto_otherrem').val());                                
      
			 $.ajax({
				url: "<?php echo site_url('booking/validateORnumber') ?>",
				type: "post",
				data: {orno: $('#auto_ornum').val()},
				success: function(response) {

					if (response > 0) {
						alert("Official Reciept Number already exist");
						$('#auto_ornum').val("");
					} else {
               			$("#form_saveBooking").submit();   
					}

			     }
			 });
                                               
            }      
                        
        }
                
    });

    
});

    
$("#auto_checkbank").change(function(){
    $.ajax({
        url: "<?php echo site_url('booking/ajxGetBranch') ?>",
        type: 'post',
        data: {bank: $(':input[name=auto_checkbank]').val()},
        success: function(response){
        
            var $response = $.parseJSON(response);
            $('#auto_checkbankbranch').empty();    
            if ($response['branch'] == "") {
                $('#payment_branch').append("<option value=''>--</option>");    
            } else {
                $.each($response['branch'], function(i)
                {
                    var item = $response['branch'][i];
                    var option = $('<option>').val(item['id']).text(item['bbf_bnch']);
                    $('#auto_checkbankbranch').append(option);                            
                });    
            }
        }
    });
});  

</script>
