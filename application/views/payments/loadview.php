<style>
#image {
/* the image you want to 'watermark' */
height: 40px; /* or whatever, equal to the image you want 'watermarked' */
width: 200px; /* as above */
/*background-image: url(path/to/image/to/be/watermarked.png);     */
background-position: 0 0;
background-repeat: no-repeat;
position: relative;                                                                                                                                                   
}

#image img {
/* the actual 'watermark' */
position: absolute;
top: 0; /* or whatever */
left: 100; /* or whatever, position according to taste */
opacity: 0.5; /* Firefox, Chrome, Safari, Opera, IE >= 9 (preview) */
filter:alpha(opacity=50); /* for <= IE 8 */
}
</style>
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<form action="<?php echo site_url('payment/saveupdateORPayment/'.$data['or_num'].'/'.$data['status']) ?>" method="post" name="form_savepayment" id="form_savepayment">
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
    
    <?php
    $readonly = "";
    $disable = "";
    $radiobut = "";
    if ($data['status'] == 'O') {
        $readonly = "readonly = 'readonly';";
        $disable = "disabled = 'false';";
        $radiobut = "disabled = 'false';";
    }  
    
    if ($canCHANGE) {
        $radiobut = "";     
    }      
    ?>
    
	<div class="row-fluid">
        
		<div class="span8">
			<div class="head">
			    <div class="isw-grid"></div>
			    <h1 style="font-size:14px">Official Receipt</h1>
			    <ul class="buttons">	
                   <?php if ($canEDIT && $data['status'] != 'C') : ?>              			  
                   <li><a href="#" class="isw-archive" id="orsaveupdate" name="orsaveupdate" title="Save update OR Payment"></a></li> 
                   <?php endif; ?>   
                   <li><a href="#" class="isw-refresh" id="action_neworpayment" title="New OR Payment"></a></li>                                 
                   <?php if ($canCANCELLEDOR && $data['status'] != 'C' && $data['status'] != 'O') :  ?>        
				   <li><a href="#" class="isw-cancel" id="orcancelled" name="orcancelled" title="Cancelled OR Payment"></a></li> 
                   <?php endif; ?>    
                   
                   <?php if ($canORCHANGETYPE && $data['status'] != 'C' && $data['status'] != 'O') :  ?>        
                   <li><a href="#" class="isw-sync" id="orchangetype" name="orchangetype" title="Change OR Payment Type"></a></li> 
                   <?php endif; ?>   
                   
                   <?php if ($canDELETEOR && $data['status'] != 'C' && $data['status'] != 'O') :  ?>        
                   <li><a href="#" class="isw-minus" id="ordelete" name="ordelete" title="Delete OR Payment"></a></li> 
                   <?php endif; ?>   
                   <?php /*if ($canIMPORTPR) : 
                   <li><a href="#" class="isw-chats" id="orviewprcheckdue" title="View PR Check Due"><a href="#" class="caption red" style="background: none repeat scroll 0 0 #CB2C1A;border: 1px solid #AF2D1C;color: #FFFFFF;"><?php echo $prDue ?></a></a></li>              
                   endif; */ ?>
                   <li><a href="#" class="isw-zoom" id="orlookup" name="orlookup"></a></li> 
                   
                   <?php if ($canPRINT) : ?>
                   <li><a href="#" class="isw-print" id="printor" name="printor" title="Print OR Payment"></a></li> 
                   <?php endif; ?>
                   
                   <?php if ($revenuebook) : ?>
                   <li><a href="<?php echo site_url('booking/load_booking/'.$aonum); ?>" class="isw-right_circle" id="returntobooking" name="returntobooking" title="View Booking of this OR"></a></li> 
                   <?php endif; ?>
                   
		         </ul>       
			    <div class="clear"></div>
			</div>
			<div class="block-fluid">  				
				<input type="hidden" name="mykeyid" id="mykeyid" value="<?php echo $hkey ?>">
                <input type="hidden" id="importedaonum" name="importedaonum" value="0">   
                <input type="text" id="ortranstype" name="ortranstype" value="M" style="text-align:right; display: none;"/>      
				<div class="row-form-booking">
                
					<div class="span1" style="width:45px">OR No.#</div>			
					<div class="span2"><input type="text" id="orno" name="orno" value="<?php echo str_pad($data['or_num'], 8, '0', STR_PAD_LEFT);  ?>" style="text-align:right" readonly="readonly"/></div>                 
					<div class="span1" style="width:45px">OR Date</div>			
					<div class="span2"><input type="text" id="ordate2" name="ordate2" value="<?php echo date('Y-m-d', strtotime($data['or_date'])); ?>" readonly="readonly"/></div>	
					<div class="span1" style="width:30px">Type</div>			
					<div class="span2">
						<select style='width:180px;' class='select' name='ortype' id='ortype'>
						<?php foreach ($ortype as $ortype) : ?>
						<?php if ($ortype['id'] == $data['or_type'] ) : ?>
						<option value="<?php echo $ortype['id']?>" selected="seleted"><?php echo $ortype['torf_name'] ?></option>
						<?php endif; ?>
						<?php endforeach; ?>
						</select>
					</div>
					<div class="span1">Ad Type</div>			
					<div class="span2">
						<select style='width:180px;' class='select' name='adtype' id='adtype' <?php echo $disable ?> >
						<?php foreach ($adtype as $adtype) : ?>
						<?php if ($adtype['id'] == $data['or_adtype']) :?>
						<option value="<?php echo $adtype['id'] ?>" selected="selected"><?php echo $adtype['adtype_code'].' - '.$adtype['adtype_name'] ?></option>
						<?php else: ?>
						<option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_code'].' - '.$adtype['adtype_name'] ?></option>
						<?php endif; ?>
						<?php endforeach;?>
						</select>
					</div>
					<div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span1" style="width:45px">PR No.#</div>			
					<div class="span2"><input type="text" id="prno" name="prno" value="<?php echo $data['or_prnum'] ?>" readonly="readonly" style="text-align:right"/></div>                 
					<div class="span1" style="width:45px">PR Date</div>			
					<div class="span2"><input type="text" id="prdate" name="prdate" value="<?php  echo $data['prdate']; ?>" readonly="readonly"/></div>					
                    <?php if ($data['status'] == 'C'): ?>
                    <div id="image">
                        <img src="<?php echo base_url() ?>assets/images/cancelled-watermark.jpg" alt="..." />
                    </div>
                    <?php endif; ?>
                    <?php if ($data['status'] == 'O'): ?>   
                    <div class="span1" style="width:45px">Status:</div>
                    <div class="span2"><span class="label label-success" style="color:#FFFFFF; text-align: left;">POSTED</span></div>     
                    <?php endif; ?>
					<div class="clear"></div>
				</div>
				<div class="head" style="border:2px solid #335A85;height:20px">						   
					<span style="color:#fff"><strong>&nbsp;&nbsp;&nbsp;&nbsp;Client Information</strong></span>
					<div class="clear"></div>
				</div>

				<div class="row-form-booking">
					<div class="span1"><input type="radio" name="prchoose" id="prchoose" <?php if ($data['or_amf'] != '') { echo "checked='checked'";} ?> value="1" style="width:10px;" <?php echo $radiobut ?> >A</div>
					<div class="span1"><input type="radio" name="prchoose" id="prchoose" <?php if ($data['or_cmf'] != '') { echo "checked='checked'";} ?> value="2" style="width:10px;" <?php echo $radiobut ?> >C</div>																
					<div class="span1">Payee</div>
					<div class="span2"><input type="text" id="payeecode" name="payeecode" <?php if (date( "Y-m-d", strtotime( date('Y-m-d', strtotime($data['or_date']))." +7 days" )) <= date('Y-m-d')) { echo 'readonly="readonly"';  }  ?> value="<?php echo $data['or_cmf'].''.$data['or_amf'] ?>" placeholder="Code"/></div>
					<div class="span1">Name</div>			
					<div class="span6"><input type="text" id="payeename" name="payeename" <?php if (date( "Y-m-d", strtotime( date('Y-m-d', strtotime($data['or_date']))." +7 days" )) <= date('Y-m-d')) { echo 'readonly="readonly"';  }  ?> value="<?php echo str_replace('\\','',$data['or_payee']) ?>" <?php echo $readonly ?> placeholder="Name"/></div>
					<div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span1">Address</div>			
					<div class="span4"><input type="text" id="address1" name="address1" value="<?php echo str_replace('\\','',$data['or_add1']) ?>" <?php echo $readonly ?>  /></div>					               
					<div class="span1">TIN</div>			
					<div class="span2"><input type="text" id="tin" name="tin" value="<?php echo $data['or_tin'] ?>" <?php echo $readonly ?> /></div>
					<div class="span1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ZIP</div>			
					<div class="span3">
						<select name="zipcode" id="zipcode" <?php echo $disable ?> > 
						<option value="" selected="selected">--</option>
						<?php 
						foreach ($zip as $zip) : ?>
						<?php if ($zip['id'] == $data['or_zip']) :?>
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
					<div class="span4"><input type="text" id="address2" name="address2" value="<?php echo str_replace('\\','',$data['or_add2']) ?>" <?php echo $readonly ?> /></div>					               	
					<div class="span3" style="width:180px">Tel 1&nbsp;&nbsp;<input type="text" id="tel1prefix" name="tel1prefix" value="<?php echo $data['or_telprefix1'] ?>" style="width:40px" <?php echo $readonly ?> /> 
									    <input type="text" id="tel1" name="tel1" value="<?php echo $data['or_tel1'] ?>" style="width:90px" <?php echo $readonly ?> />
					</div>		
					<div class="span3" style="width:180px">Tel 2&nbsp;&nbsp;<input type="text" id="tel2prefix" name="tel2prefix" value="<?php echo $data['or_telprefix2'] ?>" style="width:40px" <?php echo $readonly ?> /> 
									    <input type="text" id="tel2" name="tel2" value="<?php echo $data['or_tel2'] ?>" style="width:90px" <?php echo $readonly ?> />
					</div>		
					<div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span1"></div>			
					<div class="span4"><input type="text" id="address3" name="address3" value="<?php echo str_replace('\\','',$data['or_add3']) ?>" <?php echo $readonly ?> /></div>					               	
					<div class="span3" style="width:180px">Cel&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="celprefix" name="celprefix" value="<?php echo $data['or_celprefix'] ?>" style="width:40px" <?php echo $readonly ?> /> 
									    <input type="text" id="cel" name="cel" value="<?php echo $data['or_cel'] ?>" style="width:90px" <?php echo $readonly ?> />
					</div>		
					<div class="span3" style="width:180px">Fax&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="faxprefix" name="faxprefix" value="<?php echo $data['or_faxprefix'] ?>" style="width:40px" <?php echo $readonly ?> /> 
									    <input type="text" id="fax" name="fax" value="<?php echo $data['or_fax'] ?>" style="width:90px" <?php echo $readonly ?> />
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
						<select name='collector' id='collector' <?php echo $disable ?> >
						<option value=''>--</option>   
						<?php foreach ($collect_cashier as $colcash) : ?> 
						<?php if ($colcash['user_id'] == $data["or_ccf"]) : ?>
						<option value="<?php echo $colcash['user_id']?>" selected="selected"><?php echo str_pad($colcash['empprofile_code'], 8,' ',STR_PAD_RIGHT).' - '.$colcash['firstname'].' '.$colcash['middlename'].' '.$colcash['lastname'] ?></option>
						<?php else: ?>
						<option value="<?php echo $colcash['user_id']?>"><?php echo str_pad($colcash['empprofile_code'], 8,' ',STR_PAD_RIGHT).' - '.$colcash['firstname'].' '.$colcash['middlename'].' '.$colcash['lastname'] ?></option>
						<?php endif; ?>											
						<?php endforeach;?>
						</select>
					</div>					               
					<div class="span1">Bank</div>			
					<div class="span2">
						<select name='bank' id='bank' <?php echo $disable ?> >
						<option value=''>--</option>
						<?php foreach ($banks as $banks) :?>
						<?php if ($banks['id'] == $data['or_bnacc']) :?>
						<option value="<?php echo $banks['id'] ?>" selected="selected"><?php echo $banks['baf_acct'].' - '.$banks['bmf_name'].' '.$banks['bbf_bnch'] ?></option>
						<?php else: ?>
						<option value="<?php echo $banks['id'] ?>"><?php echo $banks['baf_acct'].' - '.$banks['bmf_name'].' '.$banks['bbf_bnch'] ?></option>
						<?php endif; ?>
						<?php endforeach;?>
						</select>
					</div>
					<div class="span1">Branch</div>			
					<div class="span3">
						<select name='branch' id='branch' <?php echo $disable ?> >
						<option value=''>--</option>
						<?php foreach($branch as $branch) :?>
						<?php if ($branch['id'] == $data['or_branch']) :?>
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
					<div class="span1" style="width:75px">Amount Paid</div>			
					<div class="span1" style="width:80px"><input type="text" id="amountpaid" name="amountpaid" value="<?php echo number_format($data['or_amt'], 2, '.', ',') ?>" style="text-align:right;" value="0.00" readonly="readonly"/></div>					               
					<div class="span1" style="width:90px">Assigned Amount</div>			
					<div class="span1" style="width:80px"><input type="text" id="assignedamount" name="assignedamount" value="<?php echo number_format($data['or_assignamt'], 2, '.', ',') ?>" style="text-align:right;" value="0.00" readonly="readonly"/></div>
					<div class="span1" style="width:60px">Notarial Fee</div>			
					<div class="span1" style="width:50px"><input type="text" id="notarialfee" name="notarialfee" value="<?php echo number_format($data['or_notarialfee'], 2, '.', ',') ?>" style="text-align:right;" <?php echo $readonly ?> /></div>
					<div class="span1" style="width:70px">Card Disc</div>            
                    <div class="span1" style="width:60px"><input type="text" id="ccdisc" name="ccdisc" value="<?php echo number_format($data['or_creditcarddisc'], 2, '.', ',') ?>" style="text-align:right;"/></div>
                    <div class="clear"></div>
				</div>
				<div class="row-form-booking">
					<div class="span2">Amount In Words</div>			
					<div class="span10"><input type="text" id="amountinwords" name="amountinwords" value="<?php echo $data['or_amtword'] ?>" readonly="readonly"/></div>	
					<div class="clear"></div>				               					
				</div>
				<div class="row-form-booking">
					<div class="span2">Particulars</div>			
					<div class="span10"><input type="text" id="particulars" name="particulars" value="<?php echo $data['or_part'] ?>" <?php echo $readonly ?> /></div>	
					<div class="clear"></div>				               					
				</div>
				<div class="row-form-booking">
					<div class="span2">Comments</div>			
					<div class="span10"><input type="text" id="comments" name="comments" value="<?php echo $data['or_comment'] ?>" /></div>	
					<div class="clear"></div>				               					
				</div>
                <?php if ($canACCTCOM) : ?>
                <div class="row-form-booking">
                    <div class="span2">Acct Comments</div>            
                    <div class="span10"><input type="text" id="acctcomments" name="acctcomments" value="<?php echo $data['or_acctcomment'] ?>" /></div>    
                    <div class="clear"></div>                                                   
                </div>
                <?php endif; ?>
                <?php if ($data['or_type'] == 2) : ?>
                <div class="row-form-booking">
                    <div class="span2">Ref. AO Number</div>            
                    <div class="span10"><input type="text" id="refaotext" name="refaotext" style="font-size: 15px; font-weight: 400; color: red" value="<?php echo $refaonum ?>" readonly="readonly" /></div>    
                    <div class="clear"></div>                                                   
                </div>
                <?php endif; ?>
				<div class="head">
                <input type="hidden" name="exdeal_note" id="exdeal_note" value="<?php echo $exdealnote ?>">  
				    <h1 style="font-size:14px;height:22px">Payment Type Detailed</h1>
				    <ul class="buttons">	
                            <?php if ($data['status'] != 'O') : ?>		 
                            <li><a href="#" class="isw-plus" id="add_paymenttype" title="Add Payment Type"></a></li> 
                           <?php if ($data['or_type'] == 2) : ?>
					       <li><a href="#" class="isw-up_circle" id="add_newrevenueao" title="Add New AO #"></a></li>
                           <?php endif; ?> 
                       <?php endif; ?>	          
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
                            <th width="40px">Adtype</th>
                            <th width="40px">Applied Date</th>
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
				<div class="span5"><input type="text" id="s_vatablesale" name="s_vatablesale" value="<?php echo number_format($data['or_vatsales'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span4">VAT-Exempt Sale</div>			
				<div class="span5"><input type="text" id="s_vatexemptsale" name="s_vatexemptsale" value="<?php echo number_format($data['or_vatexempt'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span4">VAT Zero-Rated Sale</div>			
				<div class="span5"><input type="text" id="s_vatzeroratedsale" name="s_vatzeroratedsale" value="<?php echo number_format($data['or_vatzero'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span4">TOTAL Sale</div>			
				<div class="span5"><input type="text" id="s_totalsale" name="s_totalsale" value="<?php echo number_format($data['or_grossamt'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span4">Value-Added Tax</div>			
				<div class="span5"><input type="text" id="s_valueaddedtax" name="s_valueaddedtax" value="<?php echo number_format($data['or_vatamt'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span4">Witholding & PPD</div>			
				<div class="span5"><input type="text" id="s_withholdingtax" name="s_withholdingtax" value="<?php echo number_format($data['or_wtaxamt'] + $data['or_wvatamt'] + $data['or_ppdamt'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span4">TOTAL Payment</div>			
				<div class="span5"><input type="text" id="s_totalpayment" name="s_totalpayment" value="<?php echo number_format($data['or_amt'], 2, '.', ',') ?>" style="text-align:right;" readonly="readonly"/></div>	
				<div class="clear"></div>				               					
			</div>	
			<div class="head" style="border:2px solid #335A85;height:20px">						   
			   	<span style="color:#fff"><strong>&nbsp;&nbsp;&nbsp;&nbsp;</strong></span>
			   	<div class="clear"></div>
			</div>
			<div class="row-form-booking">
				<div class="span4">VAT Code</div>			
				<div class="span3">
					<select style='width:100px' name='vatcode' id='vatcode' >                                                   
					<?php foreach($vats as $vats) :?>
					<?php if($vats['id'] == $data['or_cmfvatcode']) :?>
					<option value="<?php echo $vats['id']?>" selected="selected"><?php echo $vats['vat_name'] ?></option>
					<?php else:
                    if ($data['status'] != 'O') : ?>
					<option value="<?php echo $vats['id']?>"><?php echo $vats['vat_name'] ?></option>
                    <?php endif; ?>
					<?php endif; ?>
					<?php endforeach; ?>
					</select>
				</div>
                <div class="span1">Gov't</div>
                <div class="span2">
                    <select name='govt' id='govt'>
                        <option value='1' <?php if ($data['or_gov'] == 1) { echo "selected='selected'";} ?>>Gov</option>
                        <option value='0' <?php if ($data['or_gov'] == 0) { echo "selected='selected'";} ?>>N-Gov</option>
                        <option value='2' <?php if ($data['or_gov'] == 2) { echo "selected='selected'";} ?>>Mixed</option>
                    </select>
                </div>
				
				<div class="clear"></div>				               					
			</div>	
			<div class="row-form-booking">
				<div class="span4">VATable Sale</div>			
				<div class="span5"><input type="text" id="vatablesale" name="vatablesale" value="<?php echo number_format($data['or_vatsales'], 2, '.', ',') ?>" readonly="readonly" value="0.00" style="text-align:right;"/></div>	
				<div class="span1"></div>
				<div class="span2"></div>
				<div class="clear"></div>				               					
			</div>	
			<div class="row-form-booking">
				<div class="span4">VAT-Exempt</div>			
				<div class="span5"><input type="text" id="vatexempt" name="vatexempt" value="<?php echo number_format($data['or_vatexempt'], 2, '.', ',') ?>" readonly="readonly" value="0.00" style="text-align:right;"/></div>	
				<div class="span1"></div>
				<div class="span2"></div>
				<div class="clear"></div>				               					
			</div>	
			<div class="row-form-booking">
				<div class="span4">VAT Zero-Rated</div>			
				<div class="span5" style="width:90px"><input type="text" id="vatzerorated" value="<?php echo number_format($data['or_vatzero'], 2, '.', ',') ?>" name="vatzerorated" value="0.00" readonly="readonly" style="text-align:right;"/></div>	
				<div class="span1"><input type="checkbox" name="wtaxrec" id="wtaxrec" <?php if ($data['or_wtaxcertificate'] == 1) { echo "checked='checked'";} ?> value='1' <?php echo $radiobut ?> ></div>
				<div class="span2" style="width:70px">W/Tax Rec'd</div>
				<div class="clear"></div>				               					
			</div>
			<div class="head" style="border:2px solid #335A85;height:20px">						   
			   	<span style="color:#fff"><strong>&nbsp;&nbsp;&nbsp;&nbsp;</strong></span>
			   	<div class="clear"></div>
			</div>
			<div class="row-form-booking">
				<div class="span3"><input type="checkbox" id="evatstatus" <?php if ($data['or_vatstatus'] == 1) { echo "checked='checked'";} ?> name="evatstatus" value="1" <?php echo $radiobut ?> >E-VAT</div>			
				<div class="span3"><input type="text" id="evatamount" name="evatamount" class="radio_evat" <?php if ($data['status'] == 'O') { echo "readonly='readonly'";} ?> value="<?php echo number_format($data['or_vatamt'], 2, '.', ',') ?>" style="text-align:right"/></div>	
				<div class="span2"><input type="text" id="evatpercent" name="evatpercent" class="radio_evat" <?php if ($data['status'] == 'O') { echo "readonly='readonly'";} ?> value="<?php echo number_format($data['or_cmfvatrate'], 2, '.', ',') ?>" style="text-align:right"/></div>
				<div class="clear"></div>				               					
			</div>
			<div class="row-form-booking">
				<div class="span3"><input type="checkbox" id="wtaxstatus" name="wtaxstatus" <?php if ($data['or_wtaxstatus'] == 1) { echo "checked='checked'";} ?> value="1" <?php echo $radiobut ?> >W/TAX</div>			
				<div class="span3"><input type="text" id="wtaxamount" name="wtaxamount" class="radio_wtax" <?php if ($data['status'] == 'O') { echo "readonly='readonly'";} ?> value="<?php echo number_format($data['or_wtaxamt'], 2, '.', ',') ?>" <?php if ($data['or_wtaxstatus'] != 1) { echo "readonly='readonly'";} ?>  style="text-align:right"/></div>	
				<div class="span2"><input type="text" id="wtaxpercent" name="wtaxpercent" class="radio_wtax" <?php if ($data['status'] == 'O') { echo "readonly='readonly'";} ?> value="<?php echo number_format($data['or_wtaxpercent'], 2, '.', ',') ?>" <?php if ($data['or_wtaxstatus'] != 1) { echo "readonly='readonly'";} ?> style="text-align:right"/></div>
				<div class="span3"><input type="text" id="wtaxassign" name="wtaxassign" value="<?php echo number_format($data['or_assignwtaxamt'], 2, '.', ',') ?>" readonly="readonly" style="text-align:right"/></div>
				<div class="clear"></div>				               					
			</div>	
			<div class="row-form-booking">
				<div class="span3"><input type="checkbox" id="wvatstatus" name="wvatstatus" <?php if ($data['or_wvatstatus'] == 1) { echo "checked='checked'";} ?> value="1" <?php echo $radiobut ?> >W/VAT</div>			
				<div class="span3"><input type="text" id="wvatamount" name="wvatamount" class="radio_wvat" <?php if ($data['status'] == 'O') { echo "readonly='readonly'";} ?> value="<?php echo number_format($data['or_wvatamt'], 2, '.', ',') ?>" <?php if ($data['or_wvatstatus'] != 1) { echo "readonly='readonly'";} ?> style="text-align:right"></div>	
				<div class="span2"><input type="text" id="wvatpercent" name="wvatpercent" class="radio_wvat" <?php if ($data['status'] == 'O') { echo "readonly='readonly'";} ?> value="<?php echo number_format($data['or_wvatpercent'], 2, '.', ',') ?>" <?php if ($data['or_wvatstatus'] != 1) { echo "readonly='readonly'";} ?> style="text-align:right"></div>
				<div class="span3"><input type="text" id="wvatassign" name="wvatassign" value="<?php echo number_format($data['or_assignwvatamt'], 2, '.', ',') ?>" readonly="readonly" style="text-align:right"/></div>
				<div class="clear"></div>				               					
			</div>				
			<div class="row-form-booking">
				<div class="span3"><input type="checkbox" id="ppdstatus" name="ppdstatus" <?php if ($data['or_ppdstatus'] == 1) { echo "checked='checked'";} ?> value="1" <?php echo $radiobut ?> >PPD</div>			
				<div class="span3"><input type="text" id="ppdamount" name="ppdamount" class="radio_ppd" <?php if ($data['status'] == 'O') { echo "readonly='readonly'";} ?> value="<?php echo number_format($data['or_ppdamt'], 2, '.', ',') ?>" <?php if ($data['or_ppdstatus'] != 1) { echo "readonly='readonly'";} ?> style="text-align:right"/></div>	
				<div class="span2"><input type="text" id="ppdpercent" name="ppdpercent" class="radio_ppd" <?php if ($data['status'] == 'O') { echo "readonly='readonly'";} ?> value="<?php echo number_format($data['or_ppdpercent'], 2, '.', ',') ?>" <?php if ($data['or_ppdstatus'] != 1) { echo "readonly='readonly'";} ?> style="text-align:right"/></div>
				<div class="span3"><input type="text" id="ppdassign" name="ppdassign" value="<?php echo number_format($data['or_assignppdamt'], 2, '.', ',') ?>" readonly="readonly" style="text-align:right"/></div>
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
					<option value='MA'>Main Agency</option>    
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
                            <th width="10px">VAT</th>                 
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
		</div>		       
		<div class="dr"><span></span></div>	
        
        <div class="span7">
            <div class="row-form-booking">
                <table cellpadding="0" cellspacing="0" style="width:100%" class="table" id="tSortable_2">
                    <thead>
                        <tr>                        
                            <th width="40px">Entered By</th>
                            <th width="40px">Entered Date</th>
                            <th width="40px">Edited By</th>
                            <th width="40px">Edited Date</th>         
                        </tr>
                    </thead>
                    <tbody>
                        <tr>    
                            <td width="40px"><p class="text-success"><?php echo @$entered['username'] ?></p></td>
                            <td width="40px"><p class="text-success"><?php echo $data['user_d'] ?></p></td>
                            <td width="40px"><p class="text-success"><?php echo @$edited['username'] ?></p></td>
                            <td width="40px"><p class="text-success"><?php echo $data['edited_d'] ?></p></td>         
                        </tr>
                    </tbody>
                </table>     
             </div>
        </div>	
	</div>
</div>
</form>
<div id="importaonumrevenue" title="Import AO# (Cash/Credit Card/ Check)">
    <div class="row-form-booking">     
        <div style="width:280px">AO#: <input type="text" id="imaonum" name="imaonum" style="width:80px; text-align:right"/>
        <button type="button" id="importao" name="importao">Import</button>
        </div>            
        <div id='status'></div>                               
    </div>
</div>
<div id="changepaymenttypeview" title="Change Payment Type">
    <div class="row-form-booking">     
        <div style="width:280px">Type: 
        <select id="paymenttypeid" name="paymenttypeid">
            <?php foreach ($ortype1 as $ort) : ?>
            <?php if ($ort['id'] == $data['or_type'] ) : ?>
            <option value="<?php echo $ort['id']?>" selected="seleted"><?php echo $ort['torf_name'] ?></option>
            <?php else: ?>
            <option value="<?php echo $ort['id']?>"><?php echo $ort['torf_name'] ?></option>    
            <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <button type="button" id="changetype" name="changetype">Change</button>
        </div>            
        <div id='status'></div>                               
    </div>
</div>
<?php 
if ($data['status'] == 'O') {
?>
<script>

$( "#evatamount" ).removeClass( "autoNumeric" );
$( "#wtaxamount" ).removeClass( "autoNumeric" );
$( "#wvatamount" ).removeClass( "autoNumeric" );
$( "#ppdamount" ).removeClass( "autoNumeric" );
</script>
<?php    
}
?>
<?php include('script.php'); ?>
<script>
$('#add_paymenttype').ready(function(){
    var exx = '<?php echo $exdealnote; ?>';
    
    if (exx == 1) {
        $('#add_paymenttype').hide();     
    } else {
        $('#add_paymenttype').show(); 
    }
       
});
</script>

