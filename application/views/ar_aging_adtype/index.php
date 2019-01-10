<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>
<div class="workplace">
	<div class="row-fluid">

		<div class="span12">
			<div class="head">
				<div class="isw-grid"></div>
					<h1>Aging of Accounts Receivable - Agency / Direct Ads</h1>
					<!-- <ul class="buttons">
						<li>
							<a href="#" class="isw-sync"></a>						
						</li>	
					</ul> -->           			    
				<div class="clear"></div>
			</div>					
		</div>
		<div class="block-fluid">                        
			<div class="row-form" style="padding: 2px 2px 2px 10px;">
				<div class="span2" style="width:80px;margin-top:12px">Entered Date</div>
				<div class="span1" style="width:100px;margin-top:12px"><input type="text" placeholder="From" id="datefrom" name="datefrom" class="datepicker"/></div>
				<div class="span1" style="width:100px;margin-top:12px;border-right: 2px solid #CCCCCC"><input type="text" placeholder="To" id="dateto" name="dateto"  class="datepicker"/></div>
				<div class="span1" style="width:50px;margin-top:12px">Ad Type</div>
				<div class="span2" style="margin-top:12px">
					<select>
						<?php
						foreach ($adtype as $ad1) : ?>
						<option value="<?php echo $ad1['id'] ?>"><?php echo $ad1['adtype_name'] ?></option>
						<?php
						endforeach;
						?>
					</select>
				</div>
				<div class="span2" style="margin-top:12px">
					<select>
						<?php
						foreach ($adtype as $ad2) : ?>
						<option value="<?php echo $ad2['id'] ?>"><?php echo $ad2['adtype_name'] ?></option>
						<?php
						endforeach;
						?>
					</select>
				</div>
				<div class="span2" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>
				<div class="clear"></div>
			</div>   
			<div class="report_generator" style="height:500px;padding-left:7px"><iframe style="width:99%;height:99%" src="http://localhost/ies_beta/ar_aging_adtype/generatereport"></iframe></div>
		</div>		
	</div>            

	<div class="dr"><span></span></div>
</div>  

<script>
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(){
	var countValidate = 0;  
	var validate_fields = ['#datefrom', '#dateto'];

	for (x = 0; x < validate_fields.length; x++) {			
		if($(validate_fields[x]).val() == "") {                        
			$(validate_fields[x]).css(errorcssobj);          
		  	countValidate += 1;
		} else {        
		  	$(validate_fields[x]).css(errorcssobj2);       
		}        
	}   
	if (countValidate == 0) {
		alert('yes');	
	} else {			
		return false;
	}	
});
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
</script>

