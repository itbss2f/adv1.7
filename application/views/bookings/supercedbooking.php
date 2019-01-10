<div class="breadLine">    
	<?php echo $breadcrumb; ?>
	                      
</div>
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
		    <h1 style="font-size:14px">Superceding Booking</h1>
		    <ul class="buttons">			   
                <li><a href="#" class="isw-archive" id="action_savebookingsuperced" title="Save superced Booking"></a></li> 	
                <li><a href="#" class="isw-download" id="action_importsuperced" title="Import Superceded"></a></li> 		             	
                <li><a href="<?php echo base_url().'booking/booktype/M' ?>" class="isw-refresh" id="action_newbooking22" title="New Booking"></a></li> 
                <li><a href="#" class="isw-zoom" id="action_lookupbooking" title="Lookup Booking"></a></li> 
              </ul>       
		    <div class="clear"></div>
		</div>
<form action="<?php echo site_url('booking/saveSupercedingBooking/'.$refinvoice) ?>" method="post" name="form_saveBooking" id="form_saveBooking">
		<div class="block-fluid">   
			<div class="row-form-booking" style="display:none">
		     <input type="hidden" name="mykeyid" id="mykeyid" value="<?php echo $hkey ?>">  
			<div class="span1"><input type="text" id="title" name="title" value="<?php echo $data['ao_title'] ?>"/></div>
			<div class="span1"><input type="text" id="tin" name="tin" value="<?php echo $data['ao_tin'] ?>"/></div>
			<div class="span1"><input type="text" id="tel1prefix" name="tel1prefix" value="<?php echo $data['ao_telprefix1'] ?>"/></div>	
			<div class="span1"><input type="text" id="tel1" name="tel1" value="<?php echo $data['ao_tel1'] ?>"/></div>
			<div class="span1"><input type="text" id="tel2prefix" name="tel2prefix" value="<?php echo $data['ao_telprefix2'] ?>"/></div>
			<div class="span1"><input type="text" id="tel2" name="tel2" value="<?php echo $data['ao_tel2'] ?>"/></div>
			<div class="span1"><input type="text" id="celprefix" name="celprefix" value="<?php echo $data['ao_celprefix'] ?>"/></div>
			<div class="span1"><input type="text" id="cel" name="cel" value="<?php echo $data['ao_cel'] ?>"/></div>
			<div class="span1"><input type="text" id="faxprefix" name="faxprefix" value="<?php echo $data['ao_faxprefix'] ?>"/></div>
			<div class="span1"><input type="text" id="fax" name="fax" value="<?php echo $data['ao_fax'] ?>"/></div>
			<div class="span4">
			<select id="zipcode" name="zipcode">
				<option value="">...</option>
				<?php foreach ($zip as $zip) :?>
				<?php if ($zip['id'] == $data['ao_zip']) : ?>
				<option value="<?php echo $zip['id'] ?>" selected="selected"><?php echo $zip['zip_name'] ?></option>
				<?php else : ?>
				<option value="<?php echo $zip['id'] ?>"><?php echo $zip['zip_name'] ?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
			</div>	
			<div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span1">AO No#</div>					
			   <div class="span2"><input type="text" id="aono" name="aono" readonly="readonly"/></div>				
			   <div class="span2" style="border-left:1px solid #DDDDDD;"><strong>Reference Invoice:</strong></div>					
			   <div class="span2"><span class="label label-info" style="color:#FFFFFF"><strong><?php echo $refinvoice ?></span></strong></div>
			   <div class="clear"></div>				
		     </div>
			<div class="row-form-booking">
			   <div class="span1">AODate</div>						
			   <div class="span2"><input type="text" id="aodate" name="aodate" readonly="readonly"/></div>
			   <div class="span1" style="border-left:1px solid #DDDDDD;">Code</div>					
			   <div class="span2"><input type="text" id="code" name="code" value="<?php echo $data['ao_cmf'] ?>"/></div>
			   <div class="span1">Payee</div>					
			   <div class="span5"><input type="text" id="payee" name="payee" value="<?php echo $data['ao_payee'] ?>"/></div>   				   
	   		   <div class="clear"></div>				
		     </div>			
			<div class="row-form-booking">	
			   <div class="span1">Type</div>					
			   <div class="span2">
			   	<select id="type" name="type">
				    <?php 
                        if ($type == 'D'){?>
                            <option value="D" selected="selected" style="background-color:#D8D8D8;" >DISPLAY</option>
                        <?php
                        } else if ($type == 'C') {?>
                            <option value="C" selected="selected" style="background-color:#D8D8D8;" >CLASSIFIED</option>
                        <?php
                        } else if ($type == 'M') {?>
                            <option value="M" selected="selected" style="background-color:#D8D8D8;" >SUPERCEDING</option>
                        <?php
                        }
                        ?>
				</select>
                  </div>
			   <div class="span1" style="border-left:1px solid #DDDDDD;">Agency</div>					
			   <div class="span4">
				<select id="agencysuperced" name="agencysuperced">
					<option value="0">...</option>	
					<?php foreach ($agency as $agency) : ?>	
					<?php if ($agency['id'] == $data['ao_amf'] ) : ?>		
					<option value="<?php echo $agency['id']?>" selected="selected"><?php echo $agency['cmf_code']." - ".$agency['cmf_name'] ?></option>
					<?php else : ?>
					<option value="<?php echo $agency['id']?>"><?php echo $agency['cmf_code']." - ".$agency['cmf_name'] ?></option>
					<?php endif; ?>
					<?php endforeach; ?>
				</select>
			   </div>		
			   <div class="span1">AE</div>
			   <div class="span3">
				<select id="acctexec" name="acctexec">
					<option value="">...</option>
					<?php foreach ($empAE as $empAE) :?>
					<?php if ($empAE['user_id'] == $data['ao_aef']) : ?>	
					<option value="<?php echo $empAE['user_id'] ?>" selected="selected"><?php echo $empAE['empprofile_code'].' | '.$empAE['firstname'].' '.$empAE['lastname'] ?></option>
					<?php else : ?>
					<option value="<?php echo $empAE['user_id'] ?>"><?php echo $empAE['empprofile_code'].' | '.$empAE['firstname'].' '.$empAE['lastname'] ?></option>
					<?php endif; ?>
	                    <?php endforeach; ?>
				</select>
			    </div>  		                          
			   <div class="clear"></div>	
			</div>			
			<div class="row-form-booking">
			   <div class="span1">Artype</div>					
			   <div class="span2">
				<select id="artype" name="artype">
					<option value='1' selected='selected'>ADVERTISING</option>     
				</select>
			   </div>      
			   <div class="span1"  style="border-left:1px solid #DDDDDD;">PO/Ref</div>	
			   <div class="span2"><input type="text" id="refno" name="refno" value="<?php echo $data['ao_ref'] ?>"/></div> 
               <div class="span1"  style="border-left:1px solid #DDDDDD; display: none;">PO Date</div>    
               <div class="span2" style="display: none;"><input type="text" id="refdate" name="refdate" value="<?php echo $data['ao_refdate'] ?>"/></div> 
               <div class="span1">CE#</div>    
               <div class="span2"><input type="text" id="ceno" name="ceno" value="<?php echo $data['ao_ce'] ?>"/></div> 
			   <div class="span1">Adtype</div>	
			   <div class="span2">
				<select id="adtype" name="adtype">
					<option value="">...</option>
					<?php foreach ($adtype as $adtype) :?>
					<?php if ($adtype['id'] == $data['ao_adtype']) : ?>
					<option value="<?php echo $adtype['id'] ?>" selected="selected"><?php echo $adtype['adtype_name'] ?></option>
					<?php else: ?>
					<option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_name'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
                  </div> 	
			   <div class="clear"></div>
			</div>  	   

			<div class="row-form-booking" style="height:40px">
			   <div class="span3" style="height:40px">				
			   </div>					 	
			   <div class="span1" style="border-left:1px solid #DDDDDD;">Branch</div>	
			   <div class="span4">
			   	<select id="branch" name="branch">
					<option value="">...</option>
					<?php foreach ($branch as $branch) :?>
					<?php if ($branch['id'] == $data['ao_branch']) : ?>
					<option value="<?php echo $branch['id'] ?>" selected="selected"><?php echo $branch['branch_name'] ?></option>
					<?php else : ?>
					<option value="<?php echo $branch['id'] ?>"><?php echo $branch['branch_name'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
			   </div>      
			   <div class="span1">VAT</div>	
			   <div class="span3">
				<select id="vatcode" name="vatcode">
					<?php foreach ($vat as $vat) :?>
					<?php if ($vat['id'] == $data['ao_cmfvatcode']) : ?>
					<option value="<?php echo $vat['id'] ?>" selected="selected"><?php echo $vat['vat_name'] ?></option>					
					<?php else: ?>
                    <option value="<?php echo $vat['id'] ?>"><?php echo $vat['vat_name'] ?></option>                                
                    <?php endif; ?>
					<?php endforeach; ?>			
				</select>
			   </div>   
			   <div class="clear"></div>			
			</div>                                                                                                                                       
			<div class="row-form-booking" style="display:none">	
			   <div class="span1">Paytype</div>	
			   <div class="span3">
				<select id="paytype_dummy" name="paytype_dummy">
					<?php foreach ($paytype as $paytype_dum) :?>
					<?php if($paytype_dum['id'] == $data['ao_paytype']) : ?>
					<option value="<?php echo $paytype_dum['id'] ?>" selected="selected"><?php echo $paytype_dum['paytype_name'] ?></option>				
					<?php endif; ?>
                         <?php endforeach; ?>
				</select>
	 		   	<select id="paytype" name="paytype" style="display:none">
					<option value="">...</option>
					<?php foreach ($paytype as $paytype) :?>
					<?php if($paytype['id'] == $data['ao_paytype']) : ?>
					<option value="<?php echo $paytype['id'] ?>" selected="selected"><?php echo $paytype['paytype_name'] ?></option>
					<?php else: ?>
					<option value="<?php echo $paytype['id'] ?>"><?php echo $paytype['paytype_name'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
			   </div>             			   			    
			   <div class="span1">Subtype</div>	
			   <div class="span3">
			   	<select id="subtype" name="subtype">
					<option value="">...</option>
					<?php foreach ($subtype as $subtype) :?>
					<?php if($subtype['id'] == $data['ao_subtype']) : ?>
					<option value="<?php echo $subtype['id'] ?>" selected="selected"><?php echo $subtype['aosubtype_name'] ?></option>
					<?php else: ?>
					<option value="<?php echo $subtype['id'] ?>"><?php echo $subtype['aosubtype_name'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
                  </div>  
			   <div class="clear"></div>
		     </div>
			<div class="row-form-booking" style="display:none">			   			   
			   <div class="span1">Varioustype</div>	
			   <div class="span3">
			   	<select id="varioustype" name="varioustype">
					<option value="">...</option>
					<?php foreach ($varioustype as $varioustype) :?>
					<?php if ($varioustype['id'] == $data['ao_vartype']) : ?>
					<option value="<?php echo $varioustype['id'] ?>" selected="selected"><?php echo $varioustype['aovartype_name'] ?></option>
					<?php else: ?>
					<option value="<?php echo $varioustype['id'] ?>"><?php echo $varioustype['aovartype_name'] ?></option>		
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
			   </div>                            
			   <div class="span1">Adsource</div>	
			   <div class="span3">
                  	<select id="adsource" name="adsource">
					<option value="">...</option>
					<?php foreach ($adsource as $adsource) :?>
					<?php if ($adsource['id'] == $data['ao_adsource'] ) : ?>
					<option value="<?php echo $adsource['id'] ?>" selected="selected"><?php echo $adsource['adsource_name'] ?></option>
					<?php else: ?>
					<option value="<?php echo $adsource['id'] ?>"><?php echo $adsource['adsource_name'] ?></option>				
					<?php endif; ?>
                         <?php endforeach; ?>			
				</select>
                  </div>  			   
			   <div class="span1">Creditterm</div>	
			   <div class="span3">
			   	<select id="creditterm" name="creditterm">
					<option value="">...</option>
					<?php foreach ($creditterm as $creditterm) :?>
					<?php if($creditterm['id'] == $data['ao_crf']) : ?>
					<option value="<?php echo $creditterm['id'] ?>" selected="selected"><?php echo $creditterm['crf_name'] ?></option>
					<?php else: ?>
					<option value="<?php echo $creditterm['id'] ?>"><?php echo $creditterm['crf_name'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
			   </div>                           
			   <div class="clear"></div>
			</div> 

			<div class="row-form-booking" style="display:none">
			   
			   <?php if ($type == 'C') : ?>	
			   <div class="span2" style="width:70px">Authorize By</div>	
			   <div class="span4"><input type="text" id="authotizeby" name="authotizeby" value="<?php echo $data['ao_authorizedby'] ?>"/></div> 
			   <?php endif; ?>
			   <div class="clear"></div>
			</div>	
			<?php if ($type == 'C') : ?>
			<div class="row-form-booking">
			   <div class="span2" style="width:40px">Adtext</div>	
			   <div class="span10"><input type="text" id="adtext" name="adtext" value="<?php echo $data['ao_adtext'] ?>" readonly="readonly"/></div>
			   <div class="span2" style="width:30px"><button class="btn ttRC" type="button" id="adtext_btn" title="Adtext Editor"><span class="icon-edit"></span></button></div>		
			   <div class="clear"></div>
			</div> 
		     <?php endif; ?>

			<div class="row-form-booking" style="display:none">			   			   
			   <div class="span2">Product</div>	
			   <div class="span3">
				<select id="product_dummy" name="product_dummy">
					<?php foreach ($product as $product_dum) :?>		
					<?php if($product_dum['id'] == $data['ao_prod']) : ?>			
					<option value="<?php echo $product_dum['id'] ?>" selected="selected"><?php echo $product_dum['prod_name'] ?></option>					
					<?php endif; ?>
                         <?php endforeach; ?>
				</select>
				<select id="product" name="product" style="display:none">
				     <option value="">...</option>
					<?php foreach ($product as $product) :?>		
					<?php if($product['id'] == $data['ao_prod']) : ?>			
					<option value="<?php echo $product['id'] ?>" selected="selected"><?php echo $product['prod_name'] ?></option>
					<?php else: ?>
					<option value="<?php echo $product['id'] ?>"><?php echo $product['prod_name'] ?></option>	
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
			   </div>  
			   <div class="span2" style="width:70px">Rate code</div>	
			   <div class="span2">
				<select id="ratecode_dummy" name="ratecode_dummy" style="display:none"></select>
			  	<select id="ratecode" name="ratecode">
					<option value="">...</option>
					<?php foreach ($adtyperate as $adtyperate) :?>
					<?php if ($adtyperate['adtyperate_code'] == $data['ao_adtyperate_code']) :?>
					<option value="<?php echo $adtyperate['adtyperate_code'] ?>" selected="selected"><?php echo $adtyperate['adtyperate_code'] ?></option>
					<?php else: ?>
					<option value="<?php echo $adtyperate['adtyperate_code'] ?>"><?php echo $adtyperate['adtyperate_code'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
                  </div>  
			   <div class="span2"><input type="text" id="raterate" name="raterate" value="<?php echo $data['ao_adtyperate_rate'] ?>" readonly="readonly"/></div>
			   <div class="clear"></div>
			</div>
			<div class="row-form-booking" style="display:none">
			   <div class="span2">Classification</div>	
			   <div class="span3">
				<select id="classification_dummy" name="classification_dummy">
					<?php foreach ($class as $class_dum) :?>
					<?php if ($class_dum['id'] == $data['ao_class']) : ?>
					<option value="<?php echo $class_dum['id'] ?>" selected="selected"><?php echo $class_dum['class_name'] ?></option>					
					<?php endif; ?>					
                         <?php endforeach; ?>
				</select>
			   	<select id="classification" name="classification" style="display:none">
					<option value="">...</option>
					<?php foreach ($class as $class) :?>
					<?php if ($class['id'] == $data['ao_class']) : ?>
					<option value="<?php echo $class['id'] ?>" selected="selected"><?php echo $class['class_name'] ?></option>
					<?php else: ?>
					<option value="<?php echo $class['id'] ?>"><?php echo $class['class_name'] ?></option>
					<?php endif; ?>					
                         <?php endforeach; ?>			
				</select>
			   </div> 
                  
			   <div class="span2"><input type="text" id="vatrate" name="vatrate" value="<?php echo $data['ao_cmfvatrate'] ?>" readonly="readonly"/></div>				
  			   <div class="clear"></div>
			</div>
			<div class="row-form-booking" style="display:none">
			   <div class="span2">Sub Classification</div>	
			   <div class="span3">
	 			<select id="subclassification_dummy" name="subclassification_dummy">
					<?php if($type == 'D') :?>
					<option value="">N/A</option>
					<?php else : ?>
					<?php foreach ($subclass as $subclass_dum) :?>
					<?php if ($subclass_dum['id'] == $data['ao_subclass']) : ?>
					<option value="<?php echo $subclass_dum['id'] ?>" selected="selected"><?php echo $subclass_dum['class_name'] ?></option>					
					<?php endif; ?>
                         <?php endforeach; ?>	
					<?php endif; ?>
				</select>
			   	<select id="subclassification" name="subclassification" style="display:none">
					<option value="">...</option>
					<?php foreach ($subclass as $subclass) :?>
					<?php if ($subclass['id'] == $data['ao_subclass']) : ?>
					<option value="<?php echo $subclass['id'] ?>" selected="selected"><?php echo $subclass['class_name'] ?></option>
					<?php else: ?>
					<option value="<?php echo $subclass['id'] ?>"><?php echo $subclass['class_name'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
			   </div>    
			   <div class="span2" style="width:70px">Color</div>	
			   <div class="span2">
				<select id="color" name="color">
					<option value="">BW</option>      
					<?php foreach ($color as $color) :?>
					<?php if($color['id'] == $data['ao_color']) :?>
					<option value="<?php echo $color['id'] ?>" selected="selected"><?php echo $color['color_code'] ?></option>
					<?php else: ?>
					<option value="<?php echo $color['id'] ?>"><?php echo $color['color_code'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
			   </div> 
			   <div class="span1">Position</div>	
			   <div class="span2">
				<select id="position" name="position">
					<option value="">...</option>
					<?php foreach ($position as $position) :?>
					<?php if($position['id'] == $data['ao_position']) :?>
					<option value="<?php echo $position['id'] ?>" selected="selected"><?php echo $position['pos_name'] ?></option>					
					<?php else: ?>
					<option value="<?php echo $position['id'] ?>"><?php echo $position['pos_name'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>			
				</select>
                  </div>   	                       
			   <div class="clear"></div>
			</div> 	

			<div class="row-form-booking" style="display:none">			   			   
			   
			   <div class="span2">Ad Size</div>	
			   <div class="span3">
                  	<select id="adsize" name="adsize">
					<option value="">...</option>
					<?php foreach ($adsize as $adsize) :?>
					<?php  if ($adsize['id'] == $data['ao_adsize']) : ?>
					<option value="<?php echo $adsize['id'] ?>" selected="selected"><?php echo $adsize['adsize_code'] ?></option>
					<?php else: ?>
					<option value="<?php echo $adsize['id'] ?>"><?php echo $adsize['adsize_code'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
                  </div> 
			   <div class="span1" style="width:30px">Width</div>	
			   <div class="span2" style="width:60px"><input type="text" id="width" name="width" value="<?php echo $data['ao_width'] ?>"/></div>   
			   <div class="span1" style="width:30px">Length</div>	
			   <div class="span2" style="width:60px"><input type="text" id="length" name="length" value="<?php echo $data['ao_length'] ?>"/></div> 
			   <div class="span1" style="width:60px">Total Size</div>	
			   <div class="span2" style="width:60px"><input type="text" id="totalsize" name="totalsize" value="<?php echo $data['ao_totalsize'] ?>" readonly="readonly"/></div>                         
			   <div class="clear"></div>
			</div>	

			<div class="row-form-booking" style="display:none">			   			   
			   <div class="span1">EPS</div>	
			   <div class="span4"><input type="text" id="eps" name="eps" value="<?php echo $data['ao_eps'] ?>"/></div>  
			   <div class="span1" style="width:60px">Page Min</div>	
			   <div class="span2"><input type="text" id="pagemin" name="pagemin" value="<?php echo $data['ao_pagemin'] ?>"/></div> 
			   <div class="span1" style="width:60px">Page Max</div>	
			   <div class="span2"><input type="text" id="pagemax" name="pagemax" value="<?php echo $data['ao_pagemax'] ?>"/></div>   	             	                            
			   <div class="clear"></div>
			</div>   

			<div class="head" style="border:2px solid #335A85;height:20px">			
			   <div class="isw-calendar"></div>
			   <span style="color:#fff"><strong>Superceding Issue Details</strong></span>
			   <div class="clear"></div>
			</div>
			<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:265px"> 
				<table cellpadding="0" cellspacing="0" style="white-space:nowrap;width:3200px" class="table" id="tSortable_2">
				   <thead>
						<tr>						
							<th width="40px"></th>
							<th width="40px">AO Num</th>																	    
							<th width="40px">New Invoice</th>
							<th width="40px">New Invoice Date</th>
							<th width="60px">Issue Date</th>
							<th width="40px">Rate Amount</th>
							<th width="40px">Width</th>                                    
							<th width="40px">Length</th>       
							<th width="40px">Total Size</th>      
							<th width="40px">Prem Amount</th> 
							<th width="40px">Disc Amount</th>
							<th width="40px">Base Amount</th>
						     <th width="40px">Computed Amount</th>       
							<th width="40px">Total Cost</th> 
							<th width="40px">Agency Comm</th> 
							<th width="40px">Net Vatable Sales</th> 
							<th width="40px">VAT Exempt</th>
							<th width="40px">VAT Zero Rated</th>
							<th width="40px">VAT Amount</th>
							<th width="40px">Amount Due</th>
							<th width="50px">Classification</th>
							<th width="50px">Sub Classification</th>
							<th width="50px">Color</th>
							<th width="50px">Position</th>
							<th width="40px">Page Min</th>
							<th width="40px">Page Max</th>
							<th width="80px">Billing</th>
							<th width="80px">Records</th>
							<th width="80px">Production</th>
							<th width="80px">Follow Up</th>
							<th width="80px">EPS</dt>
							<th width="40px">Misc 1</th>
							<th width="40px">Misc 2</th>
							<th width="40px">Misc 3</th>
							<th width="40px">Misc 4</th>
							<th width="40px">Misc 5</th>
							<th width="40px">Misc 6</th>
							<th width="40px">MiscPercent 1 </th>
							<th width="40px">MiscPercent 2 </th>
							<th width="40px">MiscPercent 3 </th>
							<th width="40px">MiscPercent 4 </th>
							<th width="40px">MiscPercent 5 </th>
							<th width="40px">MiscPercent 6 </th>
							<th width="40px">Premium</th>
							<th width="40px">Discount</th>
						</tr>
				   </thead>
				   <tbody class="superceddate_list"><?php echo $superceddate_list ?></tbody>
			    </table>
			    <div class="clear"></div>
			</div>
			<div class="row-form-booking">
			   <div class="span2">Start Date</div>	
			   <div class="span2"><input type="text" id="startdate" name="startdate" value="<?php echo date('Y-m-d', strtotime($summarydate['startdate'])); ?>" readonly="readonly"/></div> 	
			   <div class="span2">End Date</div>	
			   <div class="span2"><input type="text" id="enddate" name="enddate" value="<?php  echo date('Y-m-d', strtotime($summarydate['enddate'])); ?>" readonly="readonly"/></div> 
			   <div class="span2">Total Issue #</div>	
			   <div class="span2"><input type="text" id="totalissueno" name="totalissueno" readonly="readonly" value="<?php echo $summarydate['totalissuenum'] ?>"/></div>		
			   <div class="clear"></div>		
		     </div>			
		</div>
	</div>

	<div class="span4">
		<div class="head">
		    <div class="isw-grid"></div>
		    <h1 style="font-size:14px">Detailed</h1>
		    <div class="clear"></div>
		</div>
		<div class="block-fluid"> 
			<div class="row-form-booking" style="display:none">
			   <div class="span3">Address 1</div>			
			   <div class="span9"><input type="text" id="address1" name="address1" value="<?php echo $data['ao_add1'] ?>"/></div>
			   <div class="clear"></div>
			</div>	
               <div class="row-form-booking" style="display:none">
			   <div class="span3">Address 2</div>	
			   <div class="span9"><input type="text" id="address2" name="address2" value="<?php echo $data['ao_add2'] ?>"/></div>
			   <div class="clear"></div>
			</div>
               <div class="row-form-booking" style="display:none">
			   <div class="span3">Address 3</div>	
			   <div class="span9"><input type="text" id="address3" name="address3" value="<?php echo $data['ao_add3'] ?>"/></div>                            
			   <div class="clear"></div>
			</div> 

			<div class="row-form-booking">
			   <div class="span12">                                
			   <?php 
			   $newmisc = "";
			   foreach ($misccharges as $misccharges) : 	               
			   	$newmisc .= "'".$misccharges['adtypecharges_code']."',";	
			   endforeach; 
                  ?>
			   <input type="hidden" id="misc_charges" name="misc_charges" value="<?php echo $charges ?>" style="width:100%"/>	
			   </div>				   	
			   <div class="clear"></div>		  
			</div>
			<div class="row-form-booking">
			   <div class="span2"><input type="text" id="misc1" name="misc1" style="font-size:10px" value="<?php echo $data['ao_mischarge1'] ?>" readonly="readonly"/></div>
			   <div class="span2"><input type="text" id="misc2" name="misc2" style="font-size:10px" value="<?php echo $data['ao_mischarge2'] ?>" readonly="readonly"/></div>  
			   <div class="span2"><input type="text" id="misc3" name="misc3" style="font-size:10px" value="<?php echo $data['ao_mischarge3'] ?>" readonly="readonly"/></div> 
			   <div class="span2"><input type="text" id="misc4" name="misc4" style="font-size:10px" value="<?php echo $data['ao_mischarge4'] ?>" readonly="readonly"/></div> 
			   <div class="span2"><input type="text" id="misc5" name="misc5" style="font-size:10px" value="<?php echo $data['ao_mischarge5'] ?>" readonly="readonly"/></div> 
			   <div class="span2"><input type="text" id="misc6" name="misc6" style="font-size:10px" value="<?php echo $data['ao_mischarge6'] ?>" readonly="readonly"/></div> 
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span2"><input type="text" id="miscper1" name="miscper1" readonly="readonly" value="<?php echo $data['ao_mischargepercent1'] ?>" style="text-align:right;"/></div>
			   <div class="span2"><input type="text" id="miscper2" name="miscper2" readonly="readonly" value="<?php echo $data['ao_mischargepercent2'] ?>" style="text-align:right;"/></div>  
			   <div class="span2"><input type="text" id="miscper3" name="miscper3" readonly="readonly" value="<?php echo $data['ao_mischargepercent3'] ?>" style="text-align:right;"/></div> 
			   <div class="span2"><input type="text" id="miscper4" name="miscper4" readonly="readonly" value="<?php echo $data['ao_mischargepercent4'] ?>" style="text-align:right;"/></div> 
			   <div class="span2"><input type="text" id="miscper5" name="miscper5" readonly="readonly" value="<?php echo $data['ao_mischargepercent5'] ?>" style="text-align:right;"/></div> 
			   <div class="span2"><input type="text" id="miscper6" name="miscper6" readonly="readonly" value="<?php echo $data['ao_mischargepercent6'] ?>" style="text-align:right;"/></div> 
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span3">Total Prem</div>	
			   <div class="span3"><input type="text" id="totalprem" name="totalprem" readonly="readonly" value="<?php echo $data['ao_surchargepercent'] ?>" style="text-align:right;"/></div> 
			   <div class="span3">Total Disc</div>	
			   <div class="span3"><input type="text" id="totaldisc" name="totaldisc" readonly="readonly" value="<?php echo $data['ao_discpercent'] ?>" style="text-align:right;"/></div>   	
			   <div class="clear"></div>	
		     </div>  
			<div class="row-form-booking" style="display:none">
			   <div class="span3">Records</div>	
			   <div class="span9"><input type="text" id="records" name="records" value="<?php echo $data['ao_part_records'] ?>"/></div> 
			   <div class="clear"></div>		
			</div>
			<div class="row-form-booking" style="display:none">
			   <div class="span3">Production</div>	
			   <div class="span7"><input type="text" id="production" name="production" value="<?php echo $data['ao_part_production'] ?>" readonly="readonly"/></div> 	
			   <div class="span2" style="width:30px"><button class="btn ttLT" type="button" id="prod_remarks_btn" title="Production Remarks"><span class="icon-edit"></span></button></div>		
			   <div class="clear"></div>		
			</div>	    
			<div class="row-form-booking" style="display:none">
			   <div class="span3">Followup</div>	
			   <div class="span9"><input type="text" id="followup" name="followup" value="<?php echo $data['ao_part_followup'] ?>"/></div> 
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking" style="display:none">
			   <div class="span3">Billing</div>	
			   <div class="span9"><input type="text" id="billing" name="billing" value="<?php echo $data['ao_part_billing'] ?>"/></div> 	
			   <div class="clear"></div>		
			</div>	
			<div class="block-fluid" style="display:none">                        
				<div id="calendar_div"><?php echo $calendar; ?></div>
			</div>
			<div class="head" style="border:2px solid #335A85;height:20px">							
			   <div class="isw-empty_document"></div>
				   <span style="color:#fff"><strong>Billing Information</strong></span>
				   <div class="clear"></div>
			    </div>  

			    <div class="row-form-booking">
				   <div class="span5">Computed Amount</div>	
				   <div class="span6"><input type="text" id="computedamount" name="computedamount" readonly="readonly" value="<?php echo $summarydata['total_computedamt'] ?>" style="text-align:right;"/></div> 
				   <div class="clear"></div>		
			    </div>
			    <div class="row-form-booking">
				   <div class="span5">Total Cost</div>	
				   <div class="span6"><input type="text" id="totalcost" name="totalcost" readonly="readonly" value="<?php echo $summarydata['total_totalcost'] ?>" style="text-align:right;"/></div> 	
				   <div class="clear"></div>		
			    </div>	    
			    <div class="row-form-booking">
				   <div class="span1">Due</div>	
				   <div class="span3"><input type="text" id="duepercent" name="duepercent" readonly="readonly" value="<?php echo number_format($data['ao_agycommrate'], 2, '.', ',') ?>" style="text-align:right;"/></div> 
				   <div class="span1">%</div>	
				   <div class="span6"><input type="text" id="agencycomm" name="agencycomm" readonly="readonly" value="<?php echo $summarydata['total_agencycom'] ?>" style="text-align:right;"/></div> 
				   <div class="clear"></div>	
			    </div>
			    <div class="row-form-booking">
				   <div class="span5">Net Vatable Sales</div>	
				   <div class="span6"><input type="text" id="netvatsales" name="netvatsales" readonly="readonly" value="<?php echo $summarydata['total_nvs'] ?>" style="text-align:right;"/></div> 	
				   <div class="clear"></div>		
			    </div>	 
			    <div class="row-form-booking">
				   <div class="span5">VAT-Exempt</div>	
				   <div class="span6"><input type="text" id="vatexempt" name="vatexempt" readonly="readonly" value="<?php echo $summarydata['total_vatexempt'] ?>" style="text-align:right;"/></div> 	
				   <div class="clear"></div>		
			    </div>	
			    <div class="row-form-booking">
				   <div class="span5">Vat Zero Rated</div>	
				   <div class="span6"><input type="text" id="vatzero" name="vatzero" readonly="readonly" value="<?php echo $summarydata['total_vatzerorate'] ?>" style="text-align:right;"/></div> 	
				   <div class="clear"></div>		
			    </div>	
			    <div class="row-form-booking">
				   <div class="span1">Plus</div>	
				   <div class="span3"><input type="text" id="pluspercent" name="pluspercent" readonly="readonly" value="<?php echo number_format($data['ao_cmfvatrate'], 2, '.', ',') ?>" style="text-align:right;"/></div> 
				   <div class="span2">% VAT</div>	
				   <div class="span5"><input type="text" id="vatableamt" name="vatableamt" readonly="readonly" value="<?php echo $summarydata['total_vatamt'] ?>" style="text-align:right;"/></div> 
				   <div class="clear"></div>		
			    </div>	
			    <div class="row-form-booking">
				   <div class="span5">Amount Due</div>	
				   <div class="span6"><input type="text" id="amountdue" name="amountdue" readonly="readonly" value="<?php echo $summarydata['total_amtdue'] ?>" style="text-align:right;"/></div> 	
				   <div class="clear"></div>		
			    </div>			       			    				    
		</div>
					                   
	</div>
</form>
</div>

<?php include('script.php'); ?>
