<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<form action="<?php echo site_url('payment/saveORPayment') ?>" method="post" name="form_savepayment" id="form_savepayment">
<div class="workplace">
	<?php 
	$msg = $this->session->flashdata('msg');
	if ($msg != '') :
	?>
	<script>
	$.gritter.add({
		title: 'Success!',
		text: "<?php echo $msg ?>"

	});
	</script>
	<?php endif; ?>
	<div class="row-fluid">

		<div class="span8">
			<div class="head">
			    <div class="isw-grid"></div>
			    <h1 style="font-size:14px">Official Reciept</h1>
			    <ul class="buttons">				  
				    <?php if ($canADD) : ?>              
                    <li><a href="#" class="isw-archive" id="orsave" name="orsave" title="Save OR Payment"></a></li>     
                    <?php endif; ?>                           
                    <li><a href="#" class="isw-refresh" id="action_neworpayment" title="New OR Payment"></a></li>     
                    <?php if ($canORVIEWBOOKING) : ?>
                    <li><a href="#" class="isw-folder" id="orviewbooking" title="View Booking"></a></li>        
                    <?php endif; ?>            
                    <li><a href="#" class="isw-chats" id="orviewprcheckdue" title="View PR Check Due"><a href="#" class="caption red" style="background: none repeat scroll 0 0 #CB2C1A;border: 1px solid #AF2D1C;color: #FFFFFF;"><?php echo $prDue ?></a></a></li>              
                    <li><a href="#" class="isw-zoom" id="orlookup" name="orlookup"></a></li> 
		         </ul>       
			    <div class="clear"></div>
			</div>
			<div class="block-fluid">  				
				<input type="hidden" name="mykeyid" id="mykeyid" value="<?php echo $hkey ?>">
                <input type="text" id="aonumrev" name="aonumrev" style="text-align:right; display: none;"/>    
                <input type="text" id="ortranstype" name="ortranstype" value="M" style="text-align:right; display: none;"/>  
				<div class="row-form-booking">
					<div class="span1" style="width:45px">OR No.#</div>			
					<div class="span2"><input type="text" id="orno" name="orno" value="" style="text-align:right"/></div>                 
					<div class="span1" style="width:45px">OR Date</div>			
					<div class="span2"><input type="text" id="ordate" name="ordate" value="<?php echo Date('Y-m-d'); ?>"/></div>	
					<div class="span1" style="width:30px">Type</div>			
					<div class="span2">
						<select style='width:180px;' class='select' name='ortype' id='ortype'>
						<?php foreach ($ortype as $ortype) : ?>
						<?php if ($ortype['id'] == $data['pr_type'] ) : ?>
						<option value="<?php echo $ortype['id']?>" selected="seleted"><?php echo $ortype['torf_name'] ?></option>
						<?php endif; ?>
						<?php endforeach; ?>
						</select>
					</div>
					<div class="span1">Ad Type</div>			
					<div class="span2">
						<select style='width:180px;' class='select' name='adtype' id='adtype'>
						<?php foreach ($adtype as $adtype) : ?>
						<?php if ($adtype['id'] == $data['pr_adtype']) :?>
						<option value="<?php echo $adtype['id'] ?>" selected="selected"><?php echo $adtype['adtype_name'] ?></option>
						<?php else: ?>
						<option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_name'] ?></option>
						<?php endif; ?>
						<?php endforeach;?>
						</select>
					</div>
					<div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span1" style="width:45px">PR No.#</div>			
					<div class="span2"><input type="text" id="prno" name="prno" value="<?php echo $data['pr_num'] ?>" readonly="readonly" style="text-align:right"/></div>                 
					<div class="span1" style="width:45px">PR Date</div>			
					<div class="span2"><input type="text" id="prdate" name="prdate" value="<?php echo $data['pr_date'] ?>" readonly="readonly"/></div>					
					<div class="span4">
                    <?php if ($canIMPORTPR) : ?>
                    <a href="#" id="action_importpr"><span class="label label-success" style="color:#FFFFFF;">IMPORT FROM PROVISIONAL RECIEPT</span></a>
                    <?php endif; ?>
                    </div>
					<div class="clear"></div>
				</div>
				<div class="head" style="border:2px solid #335A85;height:20px">						   
					<span style="color:#fff"><strong>&nbsp;&nbsp;&nbsp;&nbsp;Client Information</strong></span>
					<div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span1"><input type="radio" name="prchoose" id="prchoose" <?php if ($data['pr_amf'] != '') { echo "checked='checked'";} ?> value="1" style="width:10px;">A</div>
					<div class="span1"><input type="radio" name="prchoose" id="prchoose" <?php if ($data['pr_cmf'] != '') { echo "checked='checked'";} ?> value="2" checked="checked" style="width:10px;">C</div>																
					<div class="span1">Payee</div>			
					<div class="span2"><input type="text" id="payeecode" name="payeecode" value="<?php echo $data['pr_cmf'].''.$data['pr_amf'] ?>" placeholder="Code"/></div>
					<div class="span1">Name</div>			
					<div class="span6"><input type="text" id="payeename" name="payeename" value="<?php echo $data['pr_payee'] ?>" placeholder="Name"/></div>
					<div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span1">Address</div>			
					<div class="span4"><input type="text" id="address1" name="address1" value="<?php echo $data['pr_add1'] ?>"/></div>					               
					<div class="span1">TIN</div>			
					<div class="span2"><input type="text" id="tin" name="tin" value="<?php echo $data['pr_tin'] ?>"/></div>
					<div class="span1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ZIP</div>			
					<div class="span3">
						<select name="zipcode" id="zipcode"> 
						<option value="" selected="selected">--</option>
						<?php 
						foreach ($zip as $zip) : ?>
						<?php if ($zip['id'] == $data['pr_zip']) :?>
						<option value="<?php echo $zip['id'] ?>" selected="selected"><?php echo $zip['zip_code'] ?> - <?php echo $zip['zip_name'] ?></option>
						<?php else: ?>
						<option value="<?php echo $zip['id'] ?>"><?php echo $zip['zip_code'] ?> - <?php echo $zip['zip_name'] ?></option>
						<?php endif; ?>
						<?php endforeach; ?>
						</select> 
					</div>
					<div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span1"></div>			
					<div class="span4"><input type="text" id="address2" name="address2" value="<?php echo $data['pr_add2'] ?>"/></div>					               	
					<div class="span3" style="width:180px">Tel 1&nbsp;&nbsp;<input type="text" id="tel1prefix" name="tel1prefix" value="<?php echo $data['pr_telprefix1'] ?>" style="width:40px"/> 
									    <input type="text" id="tel1" name="tel1" value="<?php echo $data['pr_tel1'] ?>" style="width:90px"/>
					</div>		
					<div class="span3" style="width:180px">Tel 2&nbsp;&nbsp;<input type="text" id="tel2prefix" name="tel2prefix" value="<?php echo $data['pr_telprefix2'] ?>" style="width:40px"/> 
									    <input type="text" id="tel2" name="tel2" value="<?php echo $data['pr_tel2'] ?>" style="width:90px"/>
					</div>		
					<div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span1"></div>			
					<div class="span4"><input type="text" id="address3" name="address3" value="<?php echo $data['pr_add3'] ?>"/></div>					               	
					<div class="span3" style="width:180px">Cel&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="celprefix" name="celprefix" value="<?php echo $data['pr_celprefix'] ?>" style="width:40px"/> 
									    <input type="text" id="cel" name="cel" value="<?php echo $data['pr_cel'] ?>" style="width:90px"/>
					</div>		
					<div class="span3" style="width:180px">Fax&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="faxprefix" name="faxprefix" value="<?php echo $data['pr_faxprefix'] ?>" style="width:40px"/> 
									    <input type="text" id="fax" name="fax" value="<?php echo $data['pr_fax'] ?>" style="width:90px"/>
					</div>		
					<div class="clear"></div>
				</div>
				<div class="head" style="border:2px solid #335A85;height:20px">						   
				   	<span style="color:#fff"><strong>&nbsp;&nbsp;&nbsp;&nbsp;Cashier / Collector Information</strong></span>
				   	<div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span2">Collector / Cashier</div>			
					<div class="span3">
						<select name='collector' id='collector'>
						<option value=''>--</option>   
						<?php foreach ($collect_cashier as $colcash) : ?> 
						<?php if ($colcash['user_id'] == $data["pr_ccf"]) : ?>
						<option value="<?php echo $colcash['user_id']?>" selected="selected"><?php echo str_pad($colcash['empprofile_code'], 8,' ',STR_PAD_RIGHT).' - '.$colcash['firstname'].' '.$colcash['middlename'].' '.$colcash['lastname'] ?></option>
						<?php else: ?>
						<option value="<?php echo $colcash['user_id']?>"><?php echo str_pad($colcash['empprofile_code'], 8,' ',STR_PAD_RIGHT).' - '.$colcash['firstname'].' '.$colcash['middlename'].' '.$colcash['lastname'] ?></option>
						<?php endif; ?>											
						<?php endforeach;?>
						</select>
					</div>					               
					             <div class="span1">Bank</div>            
                    <div class="span2">
                        <select name='bank' id='bank'>
                        <option value=''>--</option>
                        <?php foreach ($banks as $banks) :?>
                        <?php if ($banks['id'] == $data['pr_bnacc']) :?>
                        <option value="<?php echo $banks['id'] ?>" selected="selected"><?php echo $banks['baf_acct'].' - '.$banks['bmf_name'].' '.$banks['bbf_bnch'] ?></option>
                        <?php else: ?>
                        <option value="<?php echo $banks['id'] ?>"><?php echo $banks['baf_acct'].' - '.$banks['bmf_name'].' '.$banks['bbf_bnch'] ?></option>
                        <?php endif; ?>
                        <?php endforeach;?>
                        </select>
                    </div>
                    <div class="span1">Branch</div>            
                    <div class="span3">
                        <select name='branch' id='branch'>
                        <option value=''>--</option>
                        <?php foreach($branch as $branch) :?>
                        <?php if ($branch['id'] == $data['pr_branch']) :?>
                        <option value="<?php echo $branch['id']?>" selected="selected"><?php echo $branch['branch_code'] ?></option>
                        <?php else :?>
                        <option value="<?php echo $branch['id']?>"><?php echo $branch['branch_code'] ?></option>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        </select>
                    </div>
					<div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span2">Amount Paid</div>			
					<div class="span2"><input type="text" id="amountpaid" name="amountpaid" value="<?php echo number_format($data['pr_amt'], 2, '.', ',') ?>" style="text-align:right;" value="0.00" readonly="readonly"/></div>					               
					<div class="span2">Assigned Amount</div>			
					<div class="span2"><input type="text" id="assignedamount" name="assignedamount" value="<?php echo $summaryassign['totalappliedamt'] ?>" style="text-align:right;" value="0.00" readonly="readonly"/></div>
					<div class="span2" style="width:70px">Notarial Fee</div>			
					<div class="span1" style="width:70px"><input type="text" id="notarialfee" name="notarialfee" value="<?php echo number_format($data['pr_notarialfee'], 2, '.', ',') ?>" style="text-align:right;"/></div>
					<div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span2">Amount In Words</div>			
					<div class="span10"><input type="text" id="amountinwords" name="amountinwords" value="<?php echo $data['pr_amtword'] ?>" readonly="readonly"/></div>	
					<div class="clear"></div>				               					
				</div>
				<div class="row-form-booking">
					<div class="span2">Particulars</div>			
					<div class="span10"><input type="text" id="particulars" name="particulars" value="<?php echo $data['pr_part'] ?>"/></div>	
					<div class="clear"></div>				               					
				</div>
				<div class="row-form-booking">
					<div class="span2">Comments</div>			
					<div class="span10"><input type="text" id="comments" name="comments" value="<?php echo $data['pr_comment'] ?>"/></div>	
					<div class="clear"></div>				               					
				</div>
				<div class="head">
				    <h1 style="font-size:14px;height:22px">Payment Type Detailed</h1>
				    <ul class="buttons">				  
					   <li><a href="#" class="isw-plus" id="add_paymenttype" title="Add Payment Type"></a></li> 	          
				    </ul>       
				    <div class="clear"></div>
				</div>
				<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:145px"> 
				<table cellpadding="0" cellspacing="0" style="white-space:nowrap;width:1000px" class="table" id="tSortable_2">
					<thead>
						<tr>						
							<th width="20px"></th>
							<th width="40px">Type</th>
							<th width="40px">Bank</th>
							<th width="40px">Branch</th>                                    
							<th width="40px">Check No.</th>       
							<th width="40px">Check Date</th>      
							<th width="40px">Credit Card</th> 
							<th width="40px">Credit Card No.</th>
							<th width="40px">Authorization No.</th>
							<th width="40px">Expiry Date</th>       
							<th width="60px">Remarks</th> 
							<th width="40px">Amount</th> 							
						</tr>
					</thead>
					<tbody class="paymenttype_list"><?php echo $paymenttype_list ?></tbody>
				</table>
				<div class="clear"></div>
				</div>
				<div class="head">
				    <h1 style="font-size:14px;height:22px">Payment Detailed Application</h1>	
				    <ul class="buttons">				  
					   <!--<li><a href="#" class="isw-target" id="single_invoce" title="Single Invoice"></a></li>--> 	          
				    </ul>			    
				    <div class="clear"></div>
				</div>
				<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:300px"> 
				<table cellpadding="0" cellspacing="0" style="white-space:nowrap;width:1400px" class="table" id="PaymentDetailedApplication">
					<thead>
						<tr>						
							<th width="20px">ID</th>
							<th width="40px">Product</th>
							<th width="40px">Issuedate</th>
							<th width="40px">Type</th>                                    
							<th width="40px">Invoice No.</th>       
							<th width="60px">Description</th>      
							<th width="40px">Size</th>      
							<th width="60px">PO/Contract/Ref No.</th>      
							<th width="40px">Invoice Amount</th>
							<th width="40px">Amount Due</th> 
							<th width="40px">Applied</th> 
							<th width="40px">W/VAT Amt</th> 							
							<th width="40px">W/VAT %</th> 							
							<th width="40px">W/TAX Amt</th> 							
							<th width="40px">W/TAX %</th> 
							<th width="40px">PPD Amt</th> 							
							<th width="40px">PPD %</th> 					
						</tr>
					</thead>
					<tbody id="paymentapplied_list"><?php echo $paymentapplied_list ?></tbody>
				</table>
				<div class="clear"></div>
				</div>
			</div>
		 </div>		

		<div class="span4">		
			<div class="head">
			    <div class="isw-ok"></div>
			    <h1>Billing Information</h1>
			    <div class="clear"></div>
			</div>
			<div class="block-fluid">
			<div class="row-form-booking">
				<div class="span4">VATable Sale</div>
				<div class="span5"><input type="text" id="s_vatablesale" name="s_vatablesale" value="<?php echo number_format($data['pr_vatsales'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span4">VAT-Exempt Sale</div>			
				<div class="span5"><input type="text" id="s_vatexemptsale" name="s_vatexemptsale" value="<?php echo number_format($data['pr_vatexempt'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span4">VAT Zero-Rated Sale</div>			
				<div class="span5"><input type="text" id="s_vatzeroratedsale" name="s_vatzeroratedsale" value="<?php echo number_format($data['pr_vatzero'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span4">TOTAL Sale</div>			
				<div class="span5"><input type="text" id="s_totalsale" name="s_totalsale" value="<?php echo number_format($data['pr_grossamt'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span4">Value-Added Tax</div>			
				<div class="span5"><input type="text" id="s_valueaddedtax" name="s_valueaddedtax" value="<?php echo number_format($data['pr_vatamt'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span4">Witholding & PPD</div>			
				<div class="span5"><input type="text" id="s_withholdingtax" name="s_withholdingtax" value="<?php echo number_format($data['pr_wtaxamt'] + $data['pr_wvatamt'] + $data['pr_ppdamt'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span4">TOTAL Payment</div>			
				<div class="span5"><input type="text" id="s_totalpayment" name="s_totalpayment" value="<?php echo number_format($data['pr_amt'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>	
			<div class="head" style="border:2px solid #335A85;height:20px">						   
			   	<span style="color:#fff"><strong>&nbsp;&nbsp;&nbsp;&nbsp;</strong></span>
			   	<div class="clear"></div>
			</div>
			<div class="row-form-booking">
				<div class="span4">VAT Code</div>			
				<div class="span3">
					<select style='width:100px' name='vatcode' id='vatcode'>                                                   
					<?php foreach($vats as $vats) :?>
					<?php if($vats['id'] == $data['pr_cmfvatcode']) :?>
					<option value="<?php echo $vats['id']?>" selected="selected"><?php echo $vats['vat_name'] ?></option>
					<?php else: ?>
					<option value="<?php echo $vats['id']?>"><?php echo $vats['vat_name'] ?></option>
					<?php endif; ?>
					<?php endforeach; ?>
					</select>
				</div>
				<div class="span1">Gov't</div>
                <div class="span2">
                    <select name='govt' id='govt'>
                        <option value='1' <?php if ($data['pr_gov'] == 1) { echo "selected='selected'";} ?>>Gov</option>
                        <option value='0' <?php if ($data['pr_gov'] == 0) { echo "selected='selected'";} ?>>N-Gov</option>
                        <option value='2' <?php if ($data['pr_gov'] == 2) { echo "selected='selected'";} ?>>Mixed</option>
                    </select>
                </div>
				<div class="clear"></div>				               					
			</div>	
			<div class="row-form-booking">
				<div class="span4">VATable Sale</div>			
				<div class="span5"><input type="text" id="vatablesale" name="vatablesale" value="<?php echo number_format($data['pr_vatsales'], 2, '.', ',') ?>" readonly="readonly" value="0.00" style="text-align:right;"/></div>	
				
				<div class="clear"></div>				               					
			</div>	
			<div class="row-form-booking">
				<div class="span4">VAT-Exempt</div>			
				<div class="span5"><input type="text" id="vatexempt" name="vatexempt" value="<?php echo number_format($data['pr_vatexempt'], 2, '.', ',') ?>" readonly="readonly" value="0.00" style="text-align:right;"/></div>	
				
				<div class="clear"></div>				               					
			</div>	
			<div class="row-form-booking">
				<div class="span4">VAT Zero-Rated</div>			
				<div class="span5" style="width:90px"><input type="text" id="vatzerorated" value="<?php echo number_format($data['pr_vatzero'], 2, '.', ',') ?>" name="vatzerorated" value="0.00" readonly="readonly" style="text-align:right;"/></div>	
				<div class="span1"><input type="checkbox" name="wtaxrec" id="wtaxrec" <?php if ($data['pr_wtaxcertificate'] == 1) { echo "checked='checked'";} ?> value='1'></div>
				<div class="span2" style="width:70px">W/Tax Rec'd</div>
				<div class="clear"></div>				               					
			</div>
			<div class="head" style="border:2px solid #335A85;height:20px">						   
			   	<span style="color:#fff"><strong>&nbsp;&nbsp;&nbsp;&nbsp;</strong></span>
			   	<div class="clear"></div>
			</div>
			<div class="row-form-booking">
				<div class="span3"><input type="checkbox" id="evatstatus" <?php if ($data['pr_vatstatus'] == 1) { echo "checked='checked'";} ?> name="evatstatus" value="1">E-VAT</div>			
				<div class="span3"><input type="text" id="evatamount" name="evatamount" class="radio_evat" value="<?php echo number_format($data['pr_vatamt'], 2, '.', ',') ?>" style="text-align:right"/></div>	
				<div class="span2"><input type="text" id="evatpercent" name="evatpercent" class="radio_evat" value="<?php echo number_format($data['pr_cmfvatrate'], 2, '.', ',') ?>" style="text-align:right"/></div>
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span3"><input type="checkbox" id="wtaxstatus" name="wtaxstatus" <?php if ($data['pr_wtaxstatus'] == 1) { echo "checked='checked'";} ?> value="1">W/TAX</div>			
				<div class="span3"><input type="text" id="wtaxamount" name="wtaxamount" class="radio_wtax" value="<?php echo number_format($data['pr_wtaxamt'], 2, '.', ',') ?>" readonly="readonly" style="text-align:right"/></div>	
				<div class="span2"><input type="text" id="wtaxpercent" name="wtaxpercent" class="radio_wtax" value="<?php echo number_format($data['pr_wtaxpercent'], 2, '.', ',') ?>" readonly="readonly" style="text-align:right"/></div>
				<div class="span3"><input type="text" id="wtaxassign" name="wtaxassign" value="<?php echo $summaryassign['totalwtax'] ?>" readonly="readonly" style="text-align:right"/></div>
				<div class="clear"></div>				               					
			</div>	
			<div class="row-form-booking">
				<div class="span3"><input type="checkbox" id="wvatstatus" name="wvatstatus" <?php if ($data['pr_wvatstatus'] == 1) { echo "checked='checked'";} ?> value="1">W/VAT</div>			
				<div class="span3"><input type="text" id="wvatamount" name="wvatamount" class="radio_wvat" value="<?php echo number_format($data['pr_wvatamt'], 2, '.', ',') ?>" readonly="readonly" style="text-align:right"></div>	
				<div class="span2"><input type="text" id="wvatpercent" name="wvatpercent" class="radio_wvat" value="<?php echo number_format($data['pr_wvatpercent'], 2, '.', ',') ?>" readonly="readonly" style="text-align:right"></div>
				<div class="span3"><input type="text" id="wvatassign" name="wvatassign" value="<?php echo $summaryassign['totalwvat'] ?>" readonly="readonly" style="text-align:right"/></div>
				<div class="clear"></div>				               					
			</div>				
			<div class="row-form-booking">
				<div class="span3"><input type="checkbox" id="ppdstatus" name="ppdstatus" <?php if ($data['pr_ppdstatus'] == 1) { echo "checked='checked'";} ?> value="1">PPD</div>			
				<div class="span3"><input type="text" id="ppdamount" name="ppdamount" class="radio_ppd" value="<?php echo number_format($data['pr_ppdamt'], 2, '.', ',') ?>" readonly="readonly" style="text-align:right"/></div>	
				<div class="span2"><input type="text" id="ppdpercent" name="ppdpercent" class="radio_ppd" value="<?php echo number_format($data['pr_ppdpercent'], 2, '.', ',') ?>" readonly="readonly" style="text-align:right"/></div>
				<div class="span3"><input type="text" id="ppdassign" name="ppdassign" value="<?php echo $summaryassign['totalppd'] ?>" readonly="readonly" style="text-align:right"/></div>
				<div class="clear"></div>				               					
			</div>
			<div class="head" style="border:2px solid #335A85;height:20px">						   
			   	<span style="color:#fff"><strong>&nbsp;&nbsp;&nbsp;&nbsp;</strong></span>
			   	<div class="clear"></div>
			</div>	
			<div class="row-form-booking">
				<div class="span4">Application Type</div>			
				<div class="span5">
				<select name="applicationtype" id="applicationtype">                    
					<option value='C'>Client</option>
					<option value='A'>Agency</option>
					<option value='MC'>Main Client</option>
				</select>
				</div>						
				<div class="span3"><button class="btn btn-mini btn-info" type="button" id="retrieve">Retrieve</button></div>
				<div class="clear"></div>				               					
			</div>		
			<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:282px"> 
				<table cellpadding="0" cellspacing="0" style="white-space:nowrap;width:250px" class="table" id="tSortable_2">
					<thead>
						<tr>						
							<th width="40px">Product</th>
							<th width="40px">Issuedate</th>
							<th width="40px">Type</th>                                    
							<th width="40px">Invoice No.</th>       
							<th width="20px">Action</th>      													
						</tr>
					</thead>
					<tbody class="search_list">					  					  			                     
					</tbody>
				</table>
				<div class="clear"></div>
				</div>
                <div class="row-form-booking">
                    <div class="span4"><b>Filter Invoice Number</b></div>    
                    <div class="span5"><input type="text" name="f_invno" id="f_invno"></div>                    
                    <div class="span3"><button class="btn btn-mini btn-info" type="button" id="filterinvoice">Filter Invoice</button></div>
                <div class="clear"></div>                                                   
			</div>
		</div>
		</div>		       
		<div class="dr"><span></span></div>		
	</div>
</div>
</form>

<?php include('script.php'); ?>
