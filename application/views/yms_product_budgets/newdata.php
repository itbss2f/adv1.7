<div class="block-fluid">      
	<form action="<?php echo site_url('yms_product_budget/save') ?>" method="post" name="formsave" id="formsave"> 
	<div class="row-form-booking">
		<div class="span1"><b>Year</b></div>	
		<div class="span1"><input type="text" name="pb_year" id="pb_year"></div>	
		<div class="span1"><b>Product</b></div>	
		<div class="span3">
			<select name="pb_ymsproduct" id="pb_ymsproduct">
				<?php foreach ($ymsproduct as $ymsproduct) : ?>
				<option value="<?php echo $ymsproduct['id'] ?>"><?php echo $ymsproduct['code'].' - '.$ymsproduct['name'] ?></option>
				<?php endforeach; ?>
			</select>
		</div>			
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1"><b>Account</b></div>	
		<div class="span2">
			<select name="pb_adtype_account" id="pb_adtype_account">
				<?php foreach ($adtype_account as $adtype_account) : ?>
				<option value="<?php echo $adtype_account['acctid'] ?>"><?php echo $adtype_account['caf_code'].' - '.$adtype_account['adtype_name'] ?></option>
				<?php endforeach; ?>
			</select>
		</div>		
		<div class="span3"><input type="text" placeholder="Remarks" name="pb_remarks" id="pb_remarks"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1"><b>Formula</b></div>	
		<div class="span5"><input type="text" name="pb_formula" id="pb_formula"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span1"><b>Method</b></div>	
		<div class="span2" style="width:215px"><input type="text" name="pb_method1" id="pb_method1"></div>	
		<div class="span2" style="width:225px"><input type="text" name="pb_method2" id="pb_method2"></div>			
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span6" align="center"><h4>--------------------------- S A L E S ---------------------------</h4></div>	
	<div class="clear"></div>	
	</div>
	<div class="row-form-booking-form">
		<div class="span1" style="width:30px">Jan</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext salesamt" name="pbsjan" id="pbsjan" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">May</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext salesamt" name="pbsmay" id="pbsmay" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Sep</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext salesamt" name="pbssep" id="pbssep" value="0.00" style="text-align:right"></div>					
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-form">
		<div class="span1" style="width:30px">Feb</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext salesamt" name="pbsfeb" id="pbsfeb" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Jun</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext salesamt" name="pbsjun" id="pbsjun" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Oct</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext salesamt" name="pbsoct" id="pbsoct" value="0.00" style="text-align:right"></div>					
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-form">
		<div class="span1" style="width:30px">Mar</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext salesamt" name="pbsmar" id="pbsmar" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Jul</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext salesamt" name="pbsjul" id="pbsjul" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Nov</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext salesamt" name="pbsnov" id="pbsnov" value="0.00" style="text-align:right"></div>					
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-form">
		<div class="span1" style="width:30px">Apr</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext salesamt" name="pbsapr" id="pbsapr" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Aug</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext salesamt" name="pbsaug" id="pbsaug" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Dec</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext salesamt" name="pbsdec" id="pbsdec" value="0.00" style="text-align:right"></div>					
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span3" style="text-align:right"><b>Total Sale Budget for the year</b></div>	
		<div class="span2"><input type="text" name="pbstotal" id="pbstotal" value="0.00" readonly="readonly" style="text-align:right"></div>					
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span6" align="center"><h4>---------------- CONTRIBUTION MARGIN ----------------</h4></div>	
	<div class="clear"></div>	
	</div>
	<div class="row-form-booking-form">
		<div class="span1" style="width:30px">Jan</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext cmamt" name="pbcmjan" id="pbcmjan" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">May</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext cmamt" name="pbcmmay" id="pbcmmay" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Sep</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext cmamt" name="pbcmsep" id="pbcmsep" value="0.00" style="text-align:right"></div>					
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-form">
		<div class="span1" style="width:30px">Feb</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext cmamt" name="pbcmfeb" id="pbcmfeb" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Jun</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext cmamt" name="pbcmjun" id="pbcmjun" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Oct</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext cmamt" name="pbcmoct" id="pbcmoct" value="0.00" style="text-align:right"></div>					
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-form">
		<div class="span1" style="width:30px">Mar</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext cmamt" name="pbcmmar" id="pbcmmar" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Jul</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext cmamt" name="pbcmjul" id="pbcmjul" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Nov</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext cmamt" name="pbcmnov" id="pbcmnov" value="0.00" style="text-align:right"></div>					
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking-form">
		<div class="span1" style="width:30px">Apr</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext cmamt" name="pbcmapr" id="pbcmapr" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Aug</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext cmamt" name="pbcmaug" id="pbcmaug" value="0.00" style="text-align:right"></div>	
		<div class="span1" style="width:30px">Dec</div>	
		<div class="span1" style="width:110px"><input type="text" class="amttext cmamt" name="pbcmdec" id="pbcmdec" value="0.00" style="text-align:right"></div>					
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span3" style="text-align:right"><b>Contribution Margin Budget for the year</b></div>	
		<div class="span2"><input type="text" name="pbcmtotal" id="pbcmtotal" value="0.00" readonly="readonly" style="text-align:right"></div>					
		<div class="clear"></div>	
	</div>
		</form>
	<div class="row-form-booking">
		<div class="span2"><button class="btn btn-success" type="button" name="save" id="save">Save Product Budget button</button></div>		
		<div class="clear"></div>		
	</div>
</div>
<script>
$(".cmamt").keyup(function(){

	var $amt1 = $("#pbcmjan").val();
	var $amt2 = $("#pbcmfeb").val();
	var $amt3 = $("#pbcmmar").val();
	var $amt4 = $("#pbcmapr").val();
	var $amt5 = $("#pbcmmay").val();
	var $amt6 = $("#pbcmjun").val();
	var $amt7 = $("#pbcmjul").val();
	var $amt8 = $("#pbcmaug").val();
	var $amt9 = $("#pbcmsep").val();
	var $amt10 = $("#pbcmoct").val();
	var $amt11 = $("#pbcmnov").val();
	var $amt12 = $("#pbcmdec").val();
	

	$.ajax({
		url: "<?php echo site_url('yms_product_budget/compute') ?>",
		type: "post",
		data: {amt1: $amt1,amt2: $amt2,amt3: $amt3,amt4: $amt4,amt5: $amt5,amt6: $amt6,
                 amt7: $amt7,amt8: $amt8,amt9: $amt9,amt10: $amt10,amt11: $amt11,amt12: $amt12},
		success: function(response) {
			$response = $.parseJSON(response);
			$("#pbcmtotal").val($response['total']);
		}
	});
});
$(".salesamt").keyup(function(){

	var $amt1 = $("#pbsjan").val();
	var $amt2 = $("#pbsfeb").val();
	var $amt3 = $("#pbsmar").val();
	var $amt4 = $("#pbsapr").val();
	var $amt5 = $("#pbsmay").val();
	var $amt6 = $("#pbsjun").val();
	var $amt7 = $("#pbsjul").val();
	var $amt8 = $("#pbsaug").val();
	var $amt9 = $("#pbssep").val();
	var $amt10 = $("#pbsoct").val();
	var $amt11 = $("#pbsnov").val();
	var $amt12 = $("#pbsdec").val();
	

	$.ajax({
		url: "<?php echo site_url('yms_product_budget/compute') ?>",
		type: "post",
		data: {amt1: $amt1,amt2: $amt2,amt3: $amt3,amt4: $amt4,amt5: $amt5,amt6: $amt6,
                 amt7: $amt7,amt8: $amt8,amt9: $amt9,amt10: $amt10,amt11: $amt11,amt12: $amt12},
		success: function(response) {
			$response = $.parseJSON(response);
			$("#pbstotal").val($response['total']);
		}
	});
});

$(".amttext").autoNumeric();
$("#pb_year").mask('9999');
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
	var countValidate = 0;  
	var validate_fields = ['#pb_year', '#pb_remarks'];

	for (x = 0; x < validate_fields.length; x++) {			
		if($(validate_fields[x]).val() == "") {                        
			$(validate_fields[x]).css(errorcssobj);          
		  	countValidate += 1;
		} else {        
		  	$(validate_fields[x]).css(errorcssobj2);       
		}        
	}   
	if (countValidate == 0) {
		$.ajax({
			url: "<?php echo site_url('yms_product_budget/validateCode') ?>",
			type: "post",
			data: {pbyear : $("#pb_year").val(), pbproduct : $("#pb_ymsproduct").val(), pbaccount : $("#pb_adtype_account").val()},
			success: function(response) {
				if (response == "true") {                    
		               alert("Product Budget Year / Product / Account must be unique!.");
		               $("#pb_year").val('');
		               $("#pb_product").val('');
		               $("#pb_adtype_account").val('');
					return false;
		           } else {
					$('#formsave').submit();
		           }
			}
		});		
	} else {			
		return false;
	}	
});
</script>
