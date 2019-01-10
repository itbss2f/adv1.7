<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">

	<div class="row-fluid">

	<div class="span9">
		<div class="head">
		    <div class="isw-grid"></div>
		    <h1 style="font-size:14px">Display Booking</h1>
		    <ul class="buttons">
		        <li><a href="#" class="isw-refresh" title="New Booking"></a></li> 		
                  <li><a href="#" class="isw-ok" title="Credit Approved"></a></li>                                                        
                  <li><a href="#" class="isw-cancel" title="Kill Booking"></a></li>                  
                  <li><a href="#" class="isw-target" title="Duplication"></a></li> 
                  <li><a href="#" class="isw-zoom" title="Lookup Booking"></a></li> 
              </ul>       
		    <div class="clear"></div>
		</div>
		<div class="block-fluid">   
		     <input type="hidden" name="mykeyid" id="mykeyid" value="<?php echo $hkey ?>">  
			<div class="row-form-booking">
			   <div class="span1">AO No.#</div>					
			   <div class="span2"><input type="text" id="aono" name="aono" readonly="readonly"/></div>
			   <div class="span1">AO Date</div>						
			   <div class="span2"><input type="text" id="aodate" name="aodate" readonly="readonly"/></div>
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
			   <div class="span1">AR Type</div>					
			   <div class="span2">
				<select id="artype" name="artype">
					<option value='1' selected='selected'>ADVERTISING</option>     
				</select>
			   </div>                            
			   <div class="clear"></div>
			</div>  	
			                     
			<div class="row-form-booking">
			   <div class="span1">Code</div>					
			   <div class="span2"><input type="text" id="code" name="code"/></div>
			   <div class="span1"><input type="text" id="credit_status" name="credit_status" readonly="readonly"/></div>
			   <div class="span1">Payee</div>					
			   <div class="span5"><input type="text" id="payee" name="payee"/></div>
			   <div class="span1" style="width:30px">Title</div>					
			   <div class="span1"><input type="text" id="title" name="title"/></div>                            
			   <div class="clear"></div>
			</div>                                                               

			<div class="row-form-booking">
			   <div class="span1">Country</div>			
			   <div class="span3">
				<select name="country" id="country">
					<option value="0">...</option>
					<?php foreach ($country as $country) :?>
					<option value="<?php echo $country['id'] ?>"><?php echo $country['country_name'] ?></option>
                         <?php endforeach; ?>
				</select>	
			   </div>
			   <div class="span1">Zip Code</div>	
			   <div class="span3">
				<select id="zipcode" name="zipcode">
					<option value="0">...</option>
					<?php foreach ($zip as $zip) :?>
					<option value="<?php echo $zip['id'] ?>"><?php echo $zip['zip_name'] ?></option>
                         <?php endforeach; ?>
				</select>
			   </div>
			   <div class="span1">TIN</div>	
			   <div class="span3"><input type="text" id="tin" name="tin"/></div>                            
			   <div class="clear"></div>
			</div>                                                                             

			<div class="row-form-booking">
			   <div class="span1">Address</div>			
			   <div class="span3"><input type="text" id="address1" name="address1"/></div>
			   <div class="span1">Address</div>	
			   <div class="span3"><input type="text" id="address2" name="address2"/></div>
			   <div class="span1">Address</div>	
			   <div class="span3"><input type="text" id="address3" name="address3"/></div>                            
			   <div class="clear"></div>
			</div>  
			<div class="head" style="border:2px solid #335A85;height:20px">			
			   <div class="isw-empty_document"></div>
			   <span style="color:#fff"><strong>Other Information</strong></span>
			   <div class="clear"></div>
			</div>	
						                                                   		    	
			<div class="row-form-booking">			   
			   <div class="span1">Agency</div>	
			   <div class="span2">
			     <select id="agency" name="agency">
					<option value="0">...</option>				
				</select>
                  </div>  
			   <div class="span1">Acct Exec</div>	
			   <div class="span2">
			     <select id="acctexec" name="acctexec">
					<option value="0">...</option>
					<?php foreach ($empAE as $empAE) :?>
					<option value="<?php echo $empAE['user_id'] ?>"><?php echo $empAE['empprofile_code'].' | '.$empAE['firstname'].' '.$empAE['lastname'] ?></option>
                         <?php endforeach; ?>
				</select>
			   </div>  
			   <div class="span1">Ad Type</div>	
			   <div class="span2">
				<select id="adtype" name="adtype">
					<option value="0">...</option>
					<?php foreach ($adtype as $adtype) :?>
					<option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_name'] ?></option>
                         <?php endforeach; ?>				
				</select>
                  </div> 
			   <div class="span1">Pay Type</div>	
			   <div class="span2">
	 		   	<select id="paytype" name="paytype">
					<option value="0">...</option>
					<?php foreach ($paytype as $paytype) :?>
					<option value="<?php echo $paytype['id'] ?>"><?php echo $paytype['paytype_name'] ?></option>
                         <?php endforeach; ?>				
				</select>
			   </div>                            
			   <div class="clear"></div>
			</div>                                                                                                     

			<div class="row-form-booking">			   			    
			   <div class="span1">Sub Type</div>	
			   <div class="span3">
			   	<select id="subtype" name="subtype">
					<option value="0">...</option>
					<?php foreach ($subtype as $subtype) :?>
					<option value="<?php echo $subtype['id'] ?>"><?php echo $subtype['aosubtype_name'] ?></option>
                         <?php endforeach; ?>				
				</select>
                  </div>  
			   <div class="span2" style="width:70px">Various Type</div>	
			   <div class="span3">
			   	<select id="varioustype" name="varioustype">
					<option value="0">...</option>
					<?php foreach ($varioustype as $varioustype) :?>
					<option value="<?php echo $varioustype['id'] ?>"><?php echo $varioustype['aovartype_name'] ?></option>
                         <?php endforeach; ?>				
				</select>
			   </div>                            
			   <div class="span1" style="width:70px">Ad Source</div>	
			   <div class="span2">
                  	<select id="adsource" name="adsource">
					<option value="0">...</option>
					<?php foreach ($adsource as $adsource) :?>
					<option value="<?php echo $adsource['id'] ?>"><?php echo $adsource['adsource_name'] ?></option>
                         <?php endforeach; ?>			
				</select>
                  </div>  
			   <div class="clear"></div>
			</div> 

			<div class="row-form-booking">			   			   
			   <div class="span1">Branch</div>	
			   <div class="span2">
			   	<select id="branch" name="branch">
					<option value="0">...</option>
					<?php foreach ($branch as $branch) :?>
					<?php if ($this->session->userdata('authsess')->sess_branch == $branch['branch_code']) : ?>
					<option value="<?php echo $branch['id'] ?>" selected="selected"><?php echo $branch['branch_name'] ?></option>
					<?php else : ?>
					<option value="<?php echo $branch['id'] ?>"><?php echo $branch['branch_name'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
			   </div>  
			   <div class="span2" style="width:70px">PO/Contract/Ref.#</div>	
			   <div class="span4"><input type="text" id="refno" name="refno"/></div> 
			   <div class="span2" style="width:70px">Credit Term</div>	
			   <div class="span2">
			   	<select id="creditterm" name="creditterm">
					<option value="0">...</option>
					<?php foreach ($creditterm as $creditterm) :?>
					<option value="<?php echo $creditterm['id'] ?>"><?php echo $creditterm['crf_name'] ?></option>
                         <?php endforeach; ?>				
				</select>
			   </div>                           
			   <div class="clear"></div>
			</div> 

			<div class="row-form-booking">
			   <div class="span2" style="width:70px">Authorize By</div>	
			   <div class="span4"><input type="text" id="authotizeby" name="authotizeby"/></div> 
			   <div class="span2" style="width:40px">Adtext</div>	
			   <div class="span4"><input type="text" id="adtext" name="adtext" readonly="readonly"/></div>
			   <div class="span2" style="width:30px"><button class="btn ttRC" type="button" title="Adtext Editor"><span class="icon-edit"></span></button></div>		
			   <div class="clear"></div>
			</div> 

			<div class="head" style="border:2px solid #335A85;height:20px">			
			   <div class="isw-empty_document"></div>
			   <span style="color:#fff"><strong>Product Information</strong></span>
			   <div class="clear"></div>
			</div>	

			<div class="row-form-booking">			   			   
			   <div class="span1">Product</div>	
			   <div class="span2">
				<select id="product" name="product">
					<option value="0">...</option>
					<?php foreach ($product as $product) :?>
					<?php if ($product['id'] == 15) : ?>
					<option value="<?php echo $product['id'] ?>" selected="selected"><?php echo $product['prod_name'] ?></option>
					<?php else: ?>
					<option value="<?php echo $product['id'] ?>"><?php echo $product['prod_name'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
			   </div>  
			   <div class="span2" style="width:60px">Classification</div>	
			   <div class="span3">
			   	<select id="classification" name="classification">
					<option value="0">...</option>
					<?php foreach ($class as $class) :?>
					<option value="<?php echo $class['id'] ?>"><?php echo $class['class_name'] ?></option>
                         <?php endforeach; ?>			
				</select>
			   </div> 
			   <div class="span2" style="width:90px">Sub Classification</div>	
			   <div class="span3">
			   	<select id="subclassification" name="subclassification">
					<option value="0">...</option>
					<?php foreach ($subclass as $subclass) :?>
					<option value="<?php echo $subclass['id'] ?>"><?php echo $subclass['class_name'] ?></option>
                         <?php endforeach; ?>				
				</select>
			   </div>                           
			   <div class="clear"></div>
			</div> 	

			<div class="row-form-booking">			   			   
			   <div class="span1" style="width:50px">Rate Code</div>	
			   <div class="span1">
			  	<select id="ratecode" name="ratecode">
					<option value="0">...</option>
					<?php foreach ($adtyperate as $adtyperate) :?>
					<option value="<?php echo $adtyperate['adtyperate_code'] ?>"><?php echo $adtyperate['adtyperate_code'] ?></option>
                         <?php endforeach; ?>				
				</select>
                  </div>  
			   <div class="span1"><input type="text" id="raterate" name="raterate" readonly="readonly" style="width:60px"/></div>
			   <div class="span1">Ad Size</div>	
			   <div class="span2">
                  	<select id="adsize" name="adsize">
					<option value="0">...</option>
					<?php foreach ($adsize as $adsize) :?>
					<?php  if ($adsize['adsize_code'] == 'CUSTOM') : ?>
					<option value="<?php echo $adsize['id'] ?>" selected="selected"><?php echo $adsize['adsize_code'] ?></option>
					<?php else: ?>
					<option value="<?php echo $adsize['id'] ?>"><?php echo $adsize['adsize_code'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
                  </div> 
			   <div class="span1" style="width:40px">Width</div>	
			   <div class="span1"><input type="text" value="1"/></div>   
			   <div class="span1" style="width:40px">Length</div>	
			   <div class="span1"><input type="text" value="1"/></div> 
			   <div class="span1" style="width:50px">Total Size</div>	
			   <div class="span1"><input type="text" value="1" readonly="readonly"/></div>                         
			   <div class="clear"></div>
			</div>	

			<div class="row-form-booking">			   			   
			   <div class="span1" style="width:50px">VAT Code</div>	
			   <div class="span2">
				<select id="vatcode" name="vatcode">
					<option value="0">...</option>
					<?php foreach ($vat as $vat) :?>
					<option value="<?php echo $vat['id'] ?>"><?php echo $vat['vat_name'] ?></option>
                         <?php endforeach; ?>			
				</select>
			   </div>  
			   <div class="span1"><input type="text" id="vatrate" name="vatrate" readonly="readonly"/></div>
			   <div class="span1">Color</div>	
			   <div class="span2">
				<select id="color" name="color">
					<option value="0">...</option>
					<?php foreach ($color as $color) :?>
					<option value="<?php echo $color['id'] ?>"><?php echo $color['color_code'] ?></option>
                         <?php endforeach; ?>				
				</select></div> 
			   <div class="span1">Position</div>	
			   <div class="span2">
				<select id="position" name="position">
					<option value="0">...</option>
					<?php foreach ($position as $position) :?>
					<option value="<?php echo $position['id'] ?>"><?php echo $position['pos_name'] ?></option>
                         <?php endforeach; ?>			
				</select></div>   	             	                            
			   <div class="clear"></div>
			</div>     

			<div class="row-form-booking">			   			   
			   <div class="span1">EPS</div>	
			   <div class="span4"><input type="text" id="eps" name="eps"/></div>  
			   <div class="span1" style="width:50px">Page Min</div>	
			   <div class="span2"><input type="text" id="pagemin" name="pagemin"/></div> 
			   <div class="span1" style="width:50px">Page Max</div>	
			   <div class="span2"><input type="text" id="pagemax" name="pagemax"/></div>   	             	                            
			   <div class="clear"></div>
			</div>   

		</div>
	</div>

	<div class="span3">
		<div class="head">
		    <div class="isw-grid"></div>
		    <h1 style="font-size:14px">Detailed</h1>
		    <div class="clear"></div>
		</div>
		<div class="block-fluid">  
			<div class="row-form-booking">
			   <div class="span2">Tel 1</div>			
			   <div class="span3"><input type="text" id="tel1prefix" name="tel1prefix"/></div>
			   <div class="span7"><input type="text" id="tel1" name="tel1"/></div>
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span2">Tel 2</div>			
			   <div class="span3"><input type="text" id="tel2prefix" name="tel2prefix"/></div>
			   <div class="span7"><input type="text" id="tel2" name="tel2"/></div>		                      
			   <div class="clear"></div>
			</div> 	                                                                                     

			<div class="row-form-booking">
			   <div class="span2">Cel</div>			
			   <div class="span3"><input type="text" id="celprefix" name="celprefix"/></div>
			   <div class="span7"><input type="text" id="cel" name="cel"/></div>	
	  		<div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span2">Fax </div>			
			   <div class="span3"><input type="text" id="faxprefix" name="faxprefix"/></div>
			   <div class="span7"><input type="text" id="fax" name="fax"/></div>	                      
			   <div class="clear"></div>
			</div>
			<div class="head" style="border:2px solid #335A85;height:20px">			
			   <div class="isw-empty_document"></div>
			   <span style="color:#fff"><strong>Miscellaneous Charges</strong></span>
			   <div class="clear"></div>
			</div> 
 
			<div class="row-form-booking">
			   <div class="span12">                                
			   <select name="select" id="misc_charges" name="misc_charges" style="width: 100%;" multiple="multiple">					
				 <?php foreach ($misccharges as $misccharges) : ?>    
				 <option value="<?php echo $misccharges['adtypecharges_code'] ?>"><?php echo $misccharges['adtypecharges_code'] ?></option>
			      <?php endforeach; ?>
			   </select>
			   </div>	
			   <div class="clear"></div>		  
			</div>
			<div class="row-form-booking">
			   <div class="span3">Total Prem</div>	
			   <div class="span3"><input type="text" id="totalprem" name="totalprem"/></div> 
			   <div class="span3">Total Disc</div>	
			   <div class="span3"><input type="text" id="totaldisc" name="totaldisc"/></div>   	
			   <div class="clear"></div>	
		     </div>  
			<div class="head" style="border:2px solid #335A85;height:20px">			
			   <div class="isw-empty_document"></div>
			   <span style="color:#fff"><strong>Remarks Information</strong></span>
			   <div class="clear"></div>
			</div> 

			<div class="row-form-booking">
			   <div class="span3">Records</div>	
			   <div class="span9"><input type="text" id="records" name="records"/></div> 
			   <div class="clear"></div>		
			</div>
			<div class="row-form-booking">
			   <div class="span3">Production</div>	
			   <div class="span9"><input type="text" id="production" name="production"/></div> 	
			   <div class="clear"></div>		
			</div>	    
			<div class="row-form-booking">
			   <div class="span3">Followup</div>	
			   <div class="span9"><input type="text" id="followup" name="followup"/></div> 
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span3">Billing</div>	
			   <div class="span9"><input type="text" id="billing" name="billing"/></div> 	
			   <div class="clear"></div>		
			</div>	  
		</div>
					                   
	</div>

		<div class="row-fluid">
		 
		 <div class="span9">
 			
			<div class="head">
			    <div class="isw-calendar"></div>
			    <h1>Issue Dates</h1>
			    <div class="clear"></div>
			</div> 
			<div class="block-fluid table-sorting">
			    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_2">
				   <thead>
					  <tr>						
						 <th width="80px">Issue Date</th>
						 <th width="80px">Rate Amount</th>
						 <th width="80px">Width</th>                                    
						 <th width="80px">Length</th>       
						 <th width="80px">Total Size</th>      
						 <th width="80px">Amount Due</th>      
					  </tr>
				   </thead>
				   <tbody class="date_list">					  					  			                     
				   </tbody>
			    </table>
			    <div class="clear"></div>
			</div>
		 </div>
		 
		 <div class="span3">
			<div class="block-fluid">                        
			    <div id="calendarpicker"></div>
			    
			    <div class="head" style="border:2px solid #335A85;height:20px">			
				   <div class="isw-empty_document"></div>
				   <span style="color:#fff"><strong>Billing Information</strong></span>
				   <div class="clear"></div>
			    </div>  

			    <div class="row-form-booking">
				   <div class="span5">Computed Amount</div>	
				   <div class="span6"><input type="text" id="computedamount" name="computedamount"/></div> 
				   <div class="clear"></div>		
			    </div>
			    <div class="row-form-booking">
				   <div class="span5">Total Cost</div>	
				   <div class="span6"><input type="text" id="totalcost" name="totalcost"/></div> 	
				   <div class="clear"></div>		
			    </div>	    
			    <div class="row-form-booking">
				   <div class="span1">Due</div>	
				   <div class="span3"><input type="text" id="duepercent" name="duepercent"/></div> 
				   <div class="span1">%</div>	
				   <div class="span6"><input type="text" id="agencycomm" name="agencycomm"/></div> 
				   <div class="clear"></div>	
			    </div>
			    <div class="row-form-booking">
				   <div class="span5">Net Vatable Sales</div>	
				   <div class="span6"><input type="text" id="netvatsales" name="netvatsales"/></div> 	
				   <div class="clear"></div>		
			    </div>	 
			    <div class="row-form-booking">
				   <div class="span5">VAT-Exempt</div>	
				   <div class="span6"><input type="text" id="vatexempt" name="vatexempt"/></div> 	
				   <div class="clear"></div>		
			    </div>	
			    <div class="row-form-booking">
				   <div class="span5">Vat Zero Rated</div>	
				   <div class="span6"><input type="text" id="vatzero" name="vatzero"/></div> 	
				   <div class="clear"></div>		
			    </div>	
			    <div class="row-form-booking">
				   <div class="span1">Plus</div>	
				   <div class="span3"><input type="text" id="pluspercent" name="pluspercent"/></div> 
				   <div class="span2">% VAT</div>	
				   <div class="span5"><input type="text" id="vatableamt" name="vatableamt"/></div> 
				   <div class="clear"></div>		
			    </div>	
			    <div class="row-form-booking">
				   <div class="span5">Amount Due</div>	
				   <div class="span6"><input type="text" id="amountdue" name="amountdue"/></div> 	
				   <div class="clear"></div>		
			    </div>		  
			</div>                           
		 </div>		
		          
	</div>
</div>

<?php include('script.php'); ?>
<?php include('script_issuedate.php'); ?>
