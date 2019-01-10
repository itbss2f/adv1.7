<div id="tabs_lookup" class="block-fluid" style="margin-top:-5px;">
	<ul>
	<li><a href="#tabs-1">Basic Search</a></li>
	<li><a href="#tabs-2">Category Search</a></li>
	<li><a href="#tabs-3">Type Search</a></li>
	</ul>
	<div id="tabs-1">
		<div class="row-form-booking">			   		
			<div class="span1" style="width:50px">AO No#</div>
			<div class="span1"><input type="text" placeholder="AO No#" id="lookup_aonum" name="lookup_aonum"/></div>	
			<div class="span3" style="width:302px">
						    <input type="radio" style="width:30px" name="lookup_type" id="look_type" value="1"> Issue&nbsp; 
						    <input type="radio" style="width:30px" name="lookup_type"  id="lookup_type" checked="checked" value="2"> Enter&nbsp;
						    <input type="text" placeholder="date from" id="lookup_datefrom" name="lookup_datefrom" style="width:75px"/>	 
						    <input type="text" placeholder="date to" id="lookup_dateto" name="lookup_dateto" style="width:75px"/>
			</div>
			<div class="clear"></div>
		</div>
		<div class="row-form-booking">
			<div class="span1" style="width:50px">Payee</div>
			<div class="span1"><input type="text" placeholder="Code" id="lookup_clientcode" name="lookup_clientcode"/></div>	
			<div class="span3"><input type="text" placeholder="Name" id="lookup_clientname" name="lookup_clientname"/></div>	
			<div class="clear"></div>
		</div>
		<div class="row-form-booking">
			<div class="span1" style="width:50px">Agency</div>
			<div class="span1"><input type="text" placeholder="Code" id="lookup_agencycode" name="lookup_agencycode"/></div>	
			<div class="span3"><input type="text" placeholder="Name" id="lookup_agencyname" name="lookup_agencyname"/></div>	
			<div class="clear"></div>
		</div>	
		<div class="row-form-booking">
			<div class="span1" style="width:50px">Size</div>
			<div class="span1"><input type="text" placeholder="Width" id="lookup_width" name="lookup_width"/></div>	
			<div class="span1"><input type="text" placeholder="Length" id="lookup_length" name="lookup_length"/></div>	
			<div class="span1"><input type="text" placeholder="Total Size" id="lookup_totalsize" name="lookup_totalsize"/></div>	
			<div class="clear"></div>
		</div>
	</div>
	<div id="tabs-2">
		<div class="row-form-booking">
			<div class="span1">Product</div>
			<div class="span3">
			<select id="lookup_product" name="lookup_product">
				<option value="">...</option>
				<?php foreach ($product as $lookup_product) :?>					
				<option value="<?php echo $lookup_product['id'] ?>"><?php echo $lookup_product['prod_name'] ?></option>
				<?php endforeach; ?>				
			</select>
			</div>	
			<div class="clear"></div>
		</div>
		<div class="row-form-booking">
			<div class="span1">Classification</div>
			<div class="span3">
			<select id="lookup_classification" name="lookup_classification">
				<option value="">...</option>
				<?php foreach ($class as $lookup_class) :?>
				<option value="<?php echo $lookup_class['id'] ?>"><?php echo $lookup_class['class_name'] ?></option>
				<?php endforeach; ?>			
			</select>
			</div>	
			<div class="clear"></div>
		</div>
		<div class="row-form-booking">
			<div class="span1">Acct Exec</div>
			<div class="span3">
		     <select id="lookup_acctexec" name="lookup_acctexec">
				<option value="">...</option>
				<?php foreach ($empAE as $lookup_empAE) :?>
				<option value="<?php echo $lookup_empAE['user_id'] ?>"><?php echo $lookup_empAE['empprofile_code'].' | '.$lookup_empAE['firstname'].' '.$lookup_empAE['lastname'] ?></option>
                    <?php endforeach; ?>
			</select>
			</div>	
			<div class="clear"></div>
		</div>
		<div class="row-form-booking">
			<div class="span1">PO/AC/Ref.#</div>
			<div class="span3"><input type="text" id="lookup_ref" name="lookup_ref"/></div>	
			<div class="clear"></div>
		</div>
	</div>
	<div id="tabs-3">
		<div class="row-form-booking">
			<div class="span1">Ad Type</div>
			<div class="span3">
			<select id="lookup_adtype" name="lookup_adtype">
				<option value="">...</option>
				<?php foreach ($adtype as $lookup_adtype) :?>
				<option value="<?php echo $lookup_adtype['id'] ?>"><?php echo $lookup_adtype['adtype_name'] ?></option>
				<?php endforeach; ?>				
			</select>
			</div>	
			<div class="clear"></div>
		</div>
		<div class="row-form-booking">
			<div class="span1">Pay Type</div>
			<div class="span3">
			<select id="lookup_paytype" name="lookup_paytype">
				<option value="">...</option>
				<?php foreach ($paytype as $lookup_paytype) :?>
				<option value="<?php echo $lookup_paytype['id'] ?>"><?php echo $lookup_paytype['paytype_name'] ?></option>
				<?php endforeach; ?>				
			</select>
			</div>	
			<div class="clear"></div>
		</div>
		<div class="row-form-booking">
			<div class="span1">Status</div>
			<div class="span3">
			<select name="lookup_status" id="lookup_status">   
                <option value="">All</option>
				<option value="A">Active</option>
				<option value="C">Cancelled</option>
				<option value="F">Credit Fail</option>
				<option value="O">Posted</option>
				<option value="P">Printed</option>
			</select>
			</div>	
			<div class="clear"></div>
		</div>
		<div class="row-form-booking">
			<div class="span1">Branch</div>
			<div class="span3">
			<select id="lookup_branch" name="lookup_branch">
				<option value="">...</option>
				<?php foreach ($branch as $lookupbranch) :?>
				<option value="<?php echo $lookupbranch['id'] ?>"><?php echo $lookupbranch['branch_name'] ?></option>       
				<?php endforeach; ?>				
			</select>
			</div>	
			<div class="clear"></div>
		</div>
	</div>
</div>
<div class="dr" style="margin-top:-10px"><span></span></div>
<div class="row-form-booking">                            
	<div class="span2" style="padding-left:50px">
	  <button class="btn btn-block" type="button" id="lookup_search_btn" name="lookup_search_btn">Search Ad Booking</button>
	</div>
	<div class="span2">
	  <button class="btn btn-block" type="button" id="lookup_loaddetailed" name="lookup_loaddetailed">Load Ad Booking</button>
	</div>		                   
	<div class="clear"></div>
</div>  
<div class="dr" style="margin-top:-10px"><span></span></div>
<div class="block-fluid">    
<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:240px"> 
	<table cellpadding="0" cellspacing="0" style="white-space:nowrap;width:1000px" class="table" id="tSortable_2">
		<thead>
			<tr>						
				<th width="20px"></th>
				<th width="40px">AO No.#</th>
				<th width="60px">Payee</th>                                    
				<th width="60px">Agency</th>       
				<th width="40px">Product</th>      													
				<th width="40px">Width</th>      													
				<th width="40px">Length</th>      													
				<th width="40px">Acct Exec</th>      													
				<th width="40px">Status</th>      													
				<th width="40px">Pay Type</th>      													
				<th width="40px">Ad Type</th>      													
				<th width="40px">Classification</th>      													
				<th width="40px">Branch</th>      													
                <th width="100px">PO/AC/Ref No.#</th>                                                                                                               
                <th width="60px">Start Date</th>                                                                                                               
				<th width="60px">End Date</th>      													 													
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
$("#lookup_loaddetailed").click(function() {
	var aonum = $('.lookuplist:checked').val();
	if(typeof aonum != 'undefined') {
		window.location.href = "<?php echo base_url()?>booking/load_booking/"+aonum;
	} else { alert("Select AOnum to load!"); return false;}
});
$("#tabs_lookup").tabs({});
$("#lookup_datefrom").datepicker({dateFormat: 'yy-mm-dd'});
$("#lookup_dateto").datepicker({dateFormat: 'yy-mm-dd'});
$("#lookup_search_btn").click(function(){    
    $.ajax({
        url: "<?php echo site_url('booking/lookup_search')?>",
        type: 'post',
        data: {
          type: '<?php echo $type ?>',
          look_aonum : $(":input[name='lookup_aonum']").val(),     
          look_dateby : $(":input[name='lookup_type']:checked").val(),     
          look_datefrom : $(":input[name='lookup_datefrom']").val(),     
          look_dateto : $(":input[name='lookup_dateto']").val(),     
          look_payeecode : $(":input[name='lookup_clientcode']").val(),     
          look_payeename : $(":input[name='lookup_clientname']").val(),     
          look_agencycode : $(":input[name='lookup_agencycode']").val(),     
          look_agencyname : $(":input[name='lookup_agencyname']").val(),      
          look_product : $(":input[name='lookup_product']").val(),     
          look_ae : $(":input[name='lookup_acctexec']").val(),     
          look_status : $(":input[name='lookup_status']").val(),     
          look_paytype : $(":input[name='lookup_paytype']").val(),     
          look_adtype: $(":input[name='lookup_adtype']").val(),     
          look_classification : $(":input[name='lookup_classification']").val(),     
          look_branch : $(":input[name='lookup_branch']").val(),     
          look_poref : $(":input[name='lookup_ref']").val(),     
          look_width : $(":input[name='lookup_width']").val(),     
          look_length : $(":input[name='lookup_length']").val(),   
          look_totalsize : $(":input[name='lookup_totalsize']").val()
          },
        success: function(response) {
            var $response = $.parseJSON(response);   
  
            $(".lookup_list").html($response['lookup_list']);                
        }
    });
    
});
</script>
