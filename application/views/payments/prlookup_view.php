<div class="block-fluid">
	<div class="row-form-booking">
		<div class="span1" style="width:50px">PR No.</div>
		<div class="span1"><input type="text" id="lookup_prno" name="lookup_prno"/></div>	
		<div class="span1" style="width:50px">Date</div>	
		<div class="span1" style="width:80px"><input type="text" placeholder="From" id="lookup_datefrom" name="lookup_datefrom"/></div>
		<div class="span1" style="width:80px"><input type="text" placeholder="To" id="lookup_dateto" name="lookup_dateto"/></div>
		<div class="clear"></div>
	</div>
	<div class="row-form-booking">
		<div class="span1" style="width:50px">Payee</div>
		<div class="span1"><input type="text" placeholder="Code" id="lookup_payeecode" name="lookup_payeecode"/></div>	
		<div class="span3"><input type="text" placeholder="Name" id="lookup_payeename" name="lookup_payeename"/></div>	
		<div class="clear"></div>
	</div>	
	<div class="row-form-booking">
		<div class="span2" style="width:150px">Collector / Cashier Person</div>
		<div class="span3">
			<select name='lookup_collector' id='lookup_collector'>
			<option value=''>--</option>   
			<?php foreach ($collect_cashier as $colcash) : ?> 
			<option value="<?php echo $colcash['user_id']?>"><?php echo str_pad($colcash['empprofile_code'], 8,' ',STR_PAD_RIGHT).' - '.$colcash['firstname'].' '.$colcash['middlename'].' '.$colcash['lastname'] ?></option>											
			<?php endforeach;?>
			</select>
		</div>	
		<div class="clear"></div>
	</div>
	<div class="row-form-booking">
		<div class="span1" style="width:50px">Bank</div>
		<div class="span1">
			<select name='lookup_bank' id='lookup_bank'>
			<option value=''>--</option>
			<?php foreach ($banks as $banks) :?>
			<option value="<?php echo $banks['id'] ?>"><?php echo $banks['bmf_code'] ?></option>
			<?php endforeach;?>
			</select>
		</div>	
		<div class="span1" style="width:50px">Branch</div>
		<div class="span2">
			<select name='lookup_branch' id='lookup_branch'>
			<option value=''>--</option>  
			</select>		
		</div>	
		<div class="clear"></div>
	</div>
	<div class="row-form-booking">
		<div class="span1" style="width:50px">Particulars</div>
		<div class="span2"><input type="text" id="lookup_particulars" name="lookup_particulars"/></div>	
		<div class="span1" style="width:50px">Amount</div>
		<div class="span1" style="width:90px"><input type="text" id="lookup_amount" name="lookup_amount" style="text-align:right"/></div>	
		<div class="clear"></div>
	</div>

</div>
<div class="dr" style="margin-top:-10px"><span></span></div>
<div class="row-form-booking">                            
	<div class="span2" style="padding-left:50px">
	  <button class="btn btn-block" type="button" id="lookup_search_btn_pr" name="lookup_search_btn_pr">Search PR</button>
	</div>
	<div class="span2">
	  <button class="btn btn-block" type="button" id="lookup_importpr" name="lookup_importpr">Import PR</button>
	</div>		                   
	<div class="clear"></div>
</div>  
<div class="dr" style="margin-top:-10px"><span></span></div>
<div class="block-fluid">    
<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:250px"> 
	<table cellpadding="0" cellspacing="0" style="white-space:nowrap;width:400px" class="table" id="tSortable_2">
		<thead>
			<tr>						
				<th width="20px"></th>
				<th width="40px">PR No.#</th>
				<th width="40px">Code</th>                                    
				<th width="60px">Payee</th>     
				<th width="40px">PR Amount</th>                                    												
				<th width="40px">Collector/Cashier</th>      													
				<th width="40px">Bank</th>      													
				<th width="40px">Branch</th>      													
				<th width="180px">Particulars</th>      													
				<th width="180px">Comments</th> 
			</tr>
		</thead>
		<tbody class="lookup_list">					  					  			                     
		</tbody>
	</table>
	<div class="clear"></div>
	</div>
</div>
</div>
<script>
$("#lookup_amount").autoNumeric();
$("#lookup_importpr").click(function() {
	var prnum = $('.lookuplist:checked').val();
	if(typeof prnum != 'undefined') {
	window.location.href = "<?php echo base_url()?>payment/load_importpr/"+prnum;
	} else { alert("Select PR Number to load!"); return false;}
});
$("#lookup_prno").mask('99999999');
$("#lookup_datefrom").datepicker({dateFormat: 'yy-mm-dd'});
$("#lookup_dateto").datepicker({dateFormat: 'yy-mm-dd'});

$("#lookup_search_btn_pr").click(function() {
	var $prnumber = $('#lookup_prno').val();
	var $prdatefrom = $('#lookup_datefrom').val();
	var $prdateto = $('#lookup_dateto').val();
	var $prpayeecode = $('#lookup_payeecode').val();
	var $prpayeename = $('#lookup_payeename').val();
	var $prcollectorcashier = $('#lookup_collector').val();
	var $prbank = $('#lookup_bank').val();
	var $prbranch = $('#lookup_branch').val();
	var $prparticulars = $('#lookup_particulars').val();
	var $pramount = $('#lookup_amount').val();	

	$.ajax({
		url: "<?php echo site_url('payment/prlookupimport')?>",
		type: 'post',
		data: {prnumber: $prnumber,
		       prdatefrom: $prdatefrom,
		       prdateto: $prdateto,
		       prpayeecode: $prpayeecode,
		       prpayeename: $prpayeename,
		       prcollectorcashier: $prcollectorcashier,
		       prbank: $prbank,
		       prbranch: $prbranch,
		       prparticulars: $prparticulars,
			  pramount: $pramount     
		},
		success:function (response) {
		  var $response = $.parseJSON(response);
		  
		  $('.lookup_list').html($response['lookup_list'])    
		}
	});
});
$("#lookup_bank").change(function(){
    $.ajax({
        url: "<?php echo site_url('payment/ajxGetBranch') ?>",
        type: 'post',
        data: {bank: $(':input[name=lookup_bank]').val()},
        success: function(response){
        
            var $response = $.parseJSON(response);
            $('#lookup_branch').empty();    
            if ($response['lookup_branch'] == "") {
                $('#lookup_branch').append("<option value=''>--</option>");    
            } else {
                $.each($response['branch'], function(i)
                {
                    var item = $response['branch'][i];
                    var option = $('<option>').val(item['id']).text(item['bbf_bnch']);
                    $('#lookup_branch').append(option);                            
                });    
            }
        }
    });
});  
</script>
