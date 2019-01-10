<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">

	<div class="row-fluid">

	<div class="span8">
		<div class="head">
		    <div class="isw-grid"></div>
		    <h1 style="font-size:14px;">
            <?php 
            if ($type == "D") : 
                echo "ADVERTISING BOOKING";
            elseif ($type == "C")  :
                echo "CLASSIFIEDS BOOKING";       
            elseif ($type == "M") :
                echo "SUPERCED BOOKING";       
            endif;  
            ?>
            </h1>
            <ul class="buttons">
                <?php if ($canSAVE && $type != "M") : ?>
                <li><a href="#" class="isw-archive" id="action_savebooking" title="Save Booking"></a></li> 	
                <?php endif; ?>	
                <?php if ($type == "M") : ?>	
                <li><a href="#" class="isw-download" id="action_importsuperced" title="Import Superceded"></a></li> 		             	
                <?php endif; ?>
                <li><a href="#" class="isw-refresh" id="action_newbooking" title="New Booking"></a></li> 		             
                <li><a href="#" class="isw-zoom" id="action_lookupbooking" title="Lookup Booking"></a></li> 
            </ul>       
		    <div class="clear"></div>
		</div>
		<form action="<?php echo site_url('booking/saveBooking') ?>" method="post" name="form_saveBooking" id="form_saveBooking">
		<div class="block-fluid">    
			<input type="hidden" name="mykeyid" id="mykeyid" value="<?php echo $hkey ?>">  
			<input type="hidden" name="mor_ornum" id="mor_ornum">   
			<input type="hidden" name="mor_oramt" id="mor_oramt" value='0'>   
			<input type="hidden" name="mor_oramtwords" id="mor_oramtwords" value="Pesos Only">   

			<input type="hidden" name="mor_checknum" id="mor_checknum">   
            <input type="hidden" name="mor_checkdate" id="mor_checkdate">
            <input type="hidden" name="mor_checkbank" id="mor_checkbank">
			<input type="hidden" name="mor_checkbankbranch" id="mor_checkbankbranch">

			<input type="hidden" name="mor_cardholder" id="mor_cardholder">   
			<input type="hidden" name="mor_cardtype" id="mor_cardtype">   
			<input type="hidden" name="mor_cardnum" id="mor_cardnum">   
			<input type="hidden" name="mor_authorization" id="mor_authorization">    
			<input type="hidden" name="mor_expirydate" id="mor_expirydate">    

			<input type="hidden" name="mor_wtaxstat" id="mor_wtaxstat" value='0'>   
			<input type="hidden" name="mor_wtaxamt" id="mor_wtaxamt">   
			<input type="hidden" name="mor_wtaxper" id="mor_wtaxper">   
			<input type="hidden" name="mor_wtaxrem" id="mor_wtaxrem">   
			<input type="hidden" name="mor_wvatstat" id="mor_wvatstat" value='0'>   
			<input type="hidden" name="mor_wvatamt" id="mor_wvatamt">   
			<input type="hidden" name="mor_wvatper" id="mor_wvatper">   
			<input type="hidden" name="mor_wvatrem" id="mor_wvatrem">   
			<input type="hidden" name="mor_otherstat" id="mor_otherstat" value='0'>   
			<input type="hidden" name="mor_otheramt" id="mor_otheramt">   
			<input type="hidden" name="mor_otherper" id="mor_otherper">   
			<input type="hidden" name="mor_otherrem" id="mor_otherrem">   			
			<div class="row-form-booking">
			   <div class="span1">AO No#</div>					
			   <div class="span2"><input type="text" id="aono" name="aono" readonly="readonly"/></div>				
			   <div class="span1" style="border-left:1px solid #DDDDDD;">Code</div>					
			   <div class="span2"><input type="text" id="code" name="code"/></div>
			   <div class="span2"><input type="text" id="credit_status" name="credit_status" readonly="readonly"/></div>
			   <div class="span1">Title</div>					
			   <div class="span2"><input type="text" id="title" name="title"/></div>   	
			   <div class="clear"></div>				
		     </div>
			<div class="row-form-booking">
			   <div class="span1">AODate</div>						
			   <div class="span2"><input type="text" id="aodate" name="aodate" readonly="readonly"/></div>
			   <div class="span1" style="border-left:1px solid #DDDDDD;">Payee</div>					
			   <div class="span4"><input type="text" id="payee" name="payee"/></div>		
			   <div class="span1">Country</div>			
			   <div class="span3">
				<select name="country" id="country">
					<option value="">...</option>
					<?php foreach ($country as $country) :?>
					<option value="<?php echo $country['id'] ?>"><?php echo $country['country_name'] ?></option>
                         <?php endforeach; ?>
				</select>	
			   </div>	   
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
			   <div class="span1" style="border-left:1px solid #DDDDDD;">Zip</div>	
			   <div class="span4">
				<select id="zipcode" name="zipcode">
					<option value="">...</option>
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
			   <div class="span1">Artype</div>					
			   <div class="span2">
				<select id="artype" name="artype">
					<option value='1' selected='selected'>ADVERTISING</option>     
				</select>
			   </div>      
			   <div class="span1" style="border-left:1px solid #DDDDDD;">Tel 1</div>			
			   <div class="span1"><input type="text" id="tel1prefix" name="tel1prefix"/></div>
			   <div class="span2"><input type="text" id="tel1" name="tel1"/></div>                      
			   <div class="span1">Tel 2</div>			
			   <div class="span1"><input type="text" id="tel2prefix" name="tel2prefix"/></div>
			   <div class="span2"><input type="text" id="tel2" name="tel2"/></div>	
			   <div class="clear"></div>
			</div>  	   

			<div class="row-form-booking">
			   <div class="span1"></div>					
			   <div class="span2"></div>  	
			   <div class="span1" style="border-left:1px solid #DDDDDD;">Cel</div>			
			   <div class="span1"><input type="text" id="celprefix" name="celprefix"/></div>
			   <div class="span2"><input type="text" id="cel" name="cel"/></div>
			   <div class="span1">Fax </div>			
			   <div class="span1"><input type="text" id="faxprefix" name="faxprefix"/></div>
			   <div class="span2"><input type="text" id="fax" name="fax"/></div>	         
			   <div class="clear"></div>			
			</div>                                                        

			<div class="head" style="border:2px solid #335A85;height:20px">			
			   <div class="isw-empty_document"></div>
			   <span style="color:#fff"><strong>Other Information</strong></span>
			   <div class="clear"></div>
			</div>	
						                                                   		    	
			<div class="row-form-booking">		
            
                <div class="span1">Adtype</div>    
                <div class="span3">            
                <select id="adtype_dummy" name="adtype_dummy" style="display:none"></select>                              
                <select id="adtype" name="adtype">
                    <option value="">...</option>
                    <?php foreach ($adtype as $adtype) :?>
                    <option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_name'] ?></option>
                    <?php endforeach; ?>                
                </select>
                </div> 
            	   
			   <div class="span1">Agency</div>	
			   <div class="span3">
                 <select id="agency_dummy" name="agency_dummy" style="display:none"></select>                              
			     <select id="agency" name="agency">
					<option value="0">...</option>				
				</select>
                  </div>  
			   <div class="span1">A.E</div>	
			   <div class="span3">
			     <select id="acctexec" name="acctexec">
					<option value="">...</option>
					<?php foreach ($empAE as $empAE) :?>
					<option value="<?php echo $empAE['user_id'] ?>"><?php echo $empAE['empprofile_code'].' | '.$empAE['firstname'].' '.$empAE['lastname'] ?></option>
                         <?php endforeach; ?>
				</select>
			   </div>      
			   			                  
			   <div class="clear"></div>
			</div>                                                                                                     

			<div class="row-form-booking">	
			   <div class="span1">Paytype</div>	
			   <div class="span3">
				<select id="paytype_dummy" name="paytype_dummy" style="display:none"></select>
	 		   	<select id="paytype" name="paytype">
					<option value="">...</option>
					<?php foreach ($paytype as $paytype) :?>
					<option value="<?php echo $paytype['id'] ?>"><?php echo $paytype['paytype_name'] ?></option>
                         <?php endforeach; ?>				
				</select>
			   </div>             			   			    
			   <div class="span1">Subtype</div>	
			   <div class="span3">
			   	<select id="subtype" name="subtype">
					<option value="">...</option>
					<?php foreach ($subtype as $subtype) :?>
                        <?php if ($subtype['id'] == 45) : ?>
                        <option value="<?php echo $subtype['id'] ?>" selected="selected"><?php echo $subtype['aosubtype_name'] ?></option>
                        <?php else: ?>
					    <option value="<?php echo $subtype['id'] ?>"><?php echo $subtype['aosubtype_name'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>				
				</select>
                  </div>  
			   <div class="span1">Branch</div>	
			   <div class="span3">
			   	<select id="branch" name="branch">
					<option value="">...</option>
					<?php foreach ($branch as $branch) :?>
					<?php if ($this->session->userdata('authsess')->sess_branch == $branch['branch_code']) : ?>
					<option value="<?php echo $branch['id'] ?>" selected="selected"><?php echo $branch['branch_name'] ?></option>
					<?php else : ?>
					<option value="<?php echo $branch['id'] ?>"><?php echo $branch['branch_name'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
			   </div>  
			   <div class="clear"></div>
			</div> 

			<div class="row-form-booking">			   			   
			   <div class="span1">Varioustype</div>	
			   <div class="span3">
			   	<select id="varioustype" name="varioustype">
					<option value="">...</option>
					<?php foreach ($varioustype as $varioustype) :?>
					<option value="<?php echo $varioustype['id'] ?>"><?php echo $varioustype['aovartype_name'] ?></option>
                         <?php endforeach; ?>				
				</select>
			   </div>                            
			   <div class="span1">Adsource</div>	
			   <div class="span3">
                  	<select id="adsource" name="adsource">
					<option value="">...</option>
					<?php foreach ($adsource as $adsource) :?>
					<option value="<?php echo $adsource['id'] ?>"><?php echo $adsource['adsource_name'] ?></option>
                         <?php endforeach; ?>			
				</select>
                  </div>  			   
			   <div class="span1">Creditterm</div>	
			   <div class="span3">
			   	<select id="creditterm" name="creditterm">
					<option value="">...</option>
					<?php foreach ($creditterm as $creditterm) :?>
					<option value="<?php echo $creditterm['id'] ?>"><?php echo $creditterm['crf_name'] ?></option>
                         <?php endforeach; ?>				
				</select>
			   </div>                           
			   <div class="clear"></div>
			</div> 

			<div class="row-form-booking">
			   <div class="span1">PO/Contract</div>	
			   <div class="span3"><input type="text" id="refno" name="refno"/></div> 
               <div class="span1" style="width: 50px;">PO Date</div>    
               <div class="span1" style="width: 80px;"><input type="text" class="datepicker" id="refdate" name="refdate"/></div> 
               <div class="span1" style="width: 40px;">CE #</div>    
               <div class="span4"><input type="text" id="ceno" name="ceno" placeholder="CE #"/></div> 
			   <?php if ($type == 'C') : ?>	
			   <div class="span1" style="display: none;">Auth By</div>	
			   <div class="span1" style="display: none;"><input type="text" id="authotizeby" name="authotizeby"/></div> 
			   <?php endif; ?>	
			   <div class="clear"></div>
			</div>	

			
			<?php if ($type == 'C') : ?>
			<!-- TPA for Classified -->
			<!-- MASTERFILE (mistpa)-->
			<!-- Build 2018-09-05 -->
			<div class="row-form-booking">
				<div class="span2" style="width:50px">TPA</div>					
				<div class="span10"><input type="text" id="tpa" name="tpa"/></div>
				 <div class="clear"></div>
			</div>
			<!-- END -->


			<div class="row-form-booking">
			   <div class="span2" style="width:40px">Adtext</div>	
			   <div class="span10"><input type="text" id="adtext" name="adtext" readonly="readonly"/></div>
			   <div class="span2" style="width:30px"><button class="btn ttRC" type="button" id="adtext_btn" title="Adtext Editor"><span class="icon-edit"></span></button></div>		
			   <div class="clear"></div>
			</div> 
			<?php endif; ?>
			<div class="head" style="border:2px solid #335A85;height:20px">			
			   <div class="isw-empty_document"></div>
			   <span style="color:#fff"><strong>Product Information</strong></span>
			   <div class="clear"></div>
			</div>	

			<!-- Default Production foR PDI-->
			<div class="row-form-booking">			   			   
			   <div class="span2">Product</div>	
			   <div class="span3">
				<select id="product_dummy" name="product_dummy" style="display:none"></select>
				<select id="product" name="product">
					<option value="">...</option>	
			     	<?php foreach ($product as $product) :?>
					<option value="<?php echo $product['id'] ?>"><?php echo $product['prod_name'] ?></option>
                 	<?php endforeach; ?>				
				</select>
			   </div>  
			   <div class="span2" style="width:70px">Rate code</div>	
			   <div class="span2">
				<select id="ratecode_dummy" name="ratecode_dummy" style="display:none"></select>
			  	<select id="ratecode" name="ratecode">
					<option value="">...</option>
					<?php foreach ($adtyperate as $adtyperate) :?>
					<option value="<?php echo $adtyperate['adtyperate_code'] ?>"><?php echo $adtyperate['adtyperate_code'] ?></option>
                         <?php endforeach; ?>				
				</select>
                  </div>  
			   <div class="span2"><input type="text" id="raterate" name="raterate" readonly="readonly"/></div>
			   <div class="clear"></div>
			</div>
			<div class="row-form-booking">
			   <div class="span2">Classification</div>	
			   <div class="span3">
				<select id="classification_dummy" name="classification_dummy" style="display:none"></select>
			   	<select id="classification" name="classification">
					<option value="">...</option>
					<?php foreach ($class as $class) :?>
					<option value="<?php echo $class['id'] ?>"><?php echo $class['class_name'] ?></option>
                         <?php endforeach; ?>			
				</select>
			   </div> 
                  <div class="span2" style="width:70px">VAT Code</div>	
			   <div class="span2">
                <select id="vatcodedum" name="vatcodedum"><option value="">...</option>   </select>
				<select id="vatcode_dummy" name="vatcode_dummy" style="display:none"></select>
				<select id="vatcode" name="vatcode" style="display:none">
					<option value="">...</option>
					<?php foreach ($vat as $vat) :?>
					<option value="<?php echo $vat['id'] ?>"><?php echo $vat['vat_name'] ?></option>
                    <?php endforeach; ?>			
				</select>
			   </div>  
			   <div class="span2"><input type="text" id="vatrate" name="vatrate" readonly="readonly"/></div>				
  			   <div class="clear"></div>
			</div>
			<div class="row-form-booking">
			   <div class="span2">Sub Classification</div>	
			   <div class="span3">
	 			<select id="subclassification_dummy" name="subclassification_dummy" style="display:none"></select>
			   	<select id="subclassification" name="subclassification">
					<?php if($type == 'D') :?>
					<option value="">N/A</option>
					<?php else : ?>
					<option value="">...</option>
					<?php foreach ($subclass as $subclass) :?>
					<option value="<?php echo $subclass['id'] ?>"><?php echo $subclass['class_name'] ?></option>
                         <?php endforeach; ?>				
					<?php endif; ?>
				</select>
			   </div>    
			   <div class="span2" style="width:70px">Color</div>	
			   <div class="span2">           
				<select id="color" name="color">
					<option value="">BW</option>
					<?php foreach ($color as $color) :?>
					<option value="<?php echo $color['id'] ?>"><?php echo $color['color_code'] ?></option>
                         <?php endforeach; ?>				
				</select>
			   </div> 
			   <div class="span1">Position</div>	
			   <div class="span2">
				<select id="position" name="position">
					<option value="">...</option>
					<?php foreach ($position as $position) :?>
					<option value="<?php echo $position['id'] ?>"><?php echo $position['pos_name'] ?></option>
                         <?php endforeach; ?>			
				</select>
                  </div>   	                       
			   <div class="clear"></div>
			</div> 	

			<div class="row-form-booking">			   			   
			   
			   <div class="span2">Ad Size</div>	
			   <div class="span3">
                  	<select id="adsize" name="adsize">
					<option value="">...</option>
					<?php foreach ($adsize as $adsize) :?>
					<?php  if ($adsize['adsize_code'] == 'CUSTOM') : ?>
					<option value="<?php echo $adsize['id'] ?>" selected="selected"><?php echo $adsize['adsize_code'].' - '.$adsize['adsize_name'] ?></option>
					<?php else: ?>
					<option value="<?php echo $adsize['id'] ?>"><?php echo $adsize['adsize_code'].' - '.$adsize['adsize_name'] ?></option>
					<?php endif; ?>
                         <?php endforeach; ?>				
				</select>
                  </div> 
			   <div class="span1" style="width:30px">Width</div>	
			   <div class="span2" style="width:60px"><input type="text" id="width" name="width" value="1.00"/></div>   
			   <div class="span1" style="width:30px">Length</div>	
			   <div class="span2" style="width:60px"><input type="text" id="length" name="length" value="1.00"/></div> 
			   <div class="span1" style="width:60px">Total Size</div>	
			   <div class="span2" style="width:60px"><input type="text" id="totalsize" name="totalsize"  value="1.00" readonly="readonly"/></div>                         
			   <div class="clear"></div>
			</div>	

			<div class="row-form-booking">			   			   
			   <div class="span1">EPS/M.Ver</div>	
			   <div class="span4"><input type="text" id="eps" name="eps"/></div>  
			   <div class="span1" style="width:60px">Page Min</div>	
			   <div class="span2"><input type="text" id="pagemin" name="pagemin"/></div> 
			   <div class="span1" style="width:60px">Page Max</div>	
			   <div class="span2"><input type="text" id="pagemax" name="pagemax"/></div>   	             	                            
			   <div class="clear"></div>
			</div>   

			<div class="head" style="border:2px solid #335A85;height:20px">			
			   <div class="isw-calendar"></div>
			   <span style="color:#fff"><strong>Issue Details</strong></span>
			   <div class="clear"></div>
			</div>
			<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:265px"> 
				<table cellpadding="0" cellspacing="0" style="white-space:nowrap;width:3200px" class="table" id="tSortable_2">
				   <thead>
						<tr>						
							<th width="40px"></th>
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
				   <tbody class="date_list">					  					  			                     
				   </tbody>
			    </table>
			    <div class="clear"></div>
			</div>
			<div class="row-form-booking">
			   <div class="span2">Start Date</div>	
			   <div class="span2"><input type="text" id="startdate" name="startdate" readonly="readonly"/></div> 	
			   <div class="span2">End Date</div>	
			   <div class="span2"><input type="text" id="enddate" name="enddate" readonly="readonly"/></div> 
			   <div class="span2">Total Issue #</div>	
			   <div class="span2"><input type="text" id="totalissueno" name="totalissueno" readonly="readonly" value="0"/></div>		
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
			<div class="row-form-booking">
			   <div class="span3">Address 1</div>			
			   <div class="span9"><input type="text" id="address1" name="address1"/></div>
			   <div class="clear"></div>
			</div>	
               <div class="row-form-booking">
			   <div class="span3">Address 2</div>	
			   <div class="span9"><input type="text" id="address2" name="address2"/></div>
			   <div class="clear"></div>
			</div>
               <div class="row-form-booking">
			   <div class="span3">Address 3</div>	
			   <div class="span9"><input type="text" id="address3" name="address3"/></div>                            
			   <div class="clear"></div>
			</div> 
			<div class="head" style="border:2px solid #335A85;height:20px">			
			   <div class="isw-empty_document"></div>
			   <span style="color:#fff"><strong>Miscellaneous Charges</strong></span>
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
			   <input type="hidden" id="misc_charges" name="misc_charges" style="width:100%"/>	
			   </div>				   	
			   <div class="clear"></div>		  
			</div>
			<div class="row-form-booking">
			   <div class="span2"><input type="text" id="misc1" name="misc1" style="font-size:10px" readonly="readonly"/></div>
			   <div class="span2"><input type="text" id="misc2" name="misc2" style="font-size:10px" readonly="readonly"/></div>  
			   <div class="span2"><input type="text" id="misc3" name="misc3" style="font-size:10px" readonly="readonly"/></div> 
			   <div class="span2"><input type="text" id="misc4" name="misc4" style="font-size:10px" readonly="readonly"/></div> 
			   <div class="span2"><input type="text" id="misc5" name="misc5" style="font-size:10px" readonly="readonly"/></div> 
			   <div class="span2"><input type="text" id="misc6" name="misc6" style="font-size:10px" readonly="readonly"/></div> 
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span2"><input type="text" id="miscper1" name="miscper1" readonly="readonly" value="0.00" style="text-align:right;"/></div>
			   <div class="span2"><input type="text" id="miscper2" name="miscper2" readonly="readonly" value="0.00" style="text-align:right;"/></div>  
			   <div class="span2"><input type="text" id="miscper3" name="miscper3" readonly="readonly" value="0.00" style="text-align:right;"/></div> 
			   <div class="span2"><input type="text" id="miscper4" name="miscper4" readonly="readonly" value="0.00" style="text-align:right;"/></div> 
			   <div class="span2"><input type="text" id="miscper5" name="miscper5" readonly="readonly" value="0.00" style="text-align:right;"/></div> 
			   <div class="span2"><input type="text" id="miscper6" name="miscper6" readonly="readonly" value="0.00" style="text-align:right;"/></div> 
			   <div class="clear"></div>	
			</div>
			<div class="row-form-booking">
			   <div class="span3">Total Prem</div>	
			   <div class="span3"><input type="text" id="totalprem" name="totalprem" readonly="readonly" value="0.00" style="text-align:right;"/></div> 
			   <div class="span3">Total Disc</div>	
			   <div class="span3"><input type="text" id="totaldisc" name="totaldisc" readonly="readonly" value="0.00" style="text-align:right;"/></div>   	
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
			   <div class="span7"><input type="text" id="production" name="production" readonly="readonly"/></div> 	
			   <div class="span2" style="width:30px"><button class="btn ttLT" type="button" id="prod_remarks_btn" title="Production Remarks"><span class="icon-edit"></span></button></div>		
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
            <div id="autoor_view" title="Automatic Official Reciept"></div>   
			<div class="block-fluid">                        
				   <div id="calendar_div"><?php echo $calendar; ?></div>
			</div>
			<div class="head" style="border:2px solid #335A85;height:20px">							
			   <div class="isw-empty_document"></div>
				   <span style="color:#fff"><strong>Billing Information</strong></span>
				   <div class="clear"></div>
			    </div>  

			    <div class="row-form-booking">
				   <div class="span5">Computed Amount</div>	
				   <div class="span6"><input type="text" id="computedamount" name="computedamount" readonly="readonly" value="0.00" style="text-align:right;"/></div> 
				   <div class="clear"></div>		
			    </div>
			    <div class="row-form-booking">
				   <div class="span5">Total Cost</div>	
				   <div class="span6"><input type="text" id="totalcost" name="totalcost" readonly="readonly" value="0.00" style="text-align:right;"/></div> 	
				   <div class="clear"></div>		
			    </div>	    
			    <div class="row-form-booking">
				   <div class="span1">Due</div>	
				   <div class="span3"><input type="text" id="duepercent" name="duepercent" <?php if (!$canOVERRIDEAGENCYCOM) { ?> readonly='readonly' <?php } ?> value="0.00" style="text-align:right;"/></div> 
				   <div class="span1">%</div>	
				   <div class="span6"><input type="text" id="agencycomm" name="agencycomm" readonly="readonly" value="0.00" style="text-align:right;"/></div> 
				   <div class="clear"></div>	
			    </div>
			    <div class="row-form-booking">
				   <div class="span5">Net Vatable Sales</div>	
				   <div class="span6"><input type="text" id="netvatsales" name="netvatsales" readonly="readonly" value="0.00" style="text-align:right;"/></div> 	
				   <div class="clear"></div>		
			    </div>	 
			    <div class="row-form-booking">
				   <div class="span5">VAT-Exempt</div>	
				   <div class="span6"><input type="text" id="vatexempt" name="vatexempt" readonly="readonly" value="0.00" style="text-align:right;"/></div> 	
				   <div class="clear"></div>		
			    </div>	
			    <div class="row-form-booking">
				   <div class="span5">Vat Zero Rated</div>	
				   <div class="span6"><input type="text" id="vatzero" name="vatzero" readonly="readonly" value="0.00" style="text-align:right;"/></div> 	
				   <div class="clear"></div>		
			    </div>	
			    <div class="row-form-booking">
				   <div class="span1">Plus</div>	
				   <div class="span3"><input type="text" id="pluspercent" name="pluspercent" readonly="readonly" value="0.00" style="text-align:right;"/></div> 
				   <div class="span2">% VAT</div>	
				   <div class="span5"><input type="text" id="vatableamt" name="vatableamt" readonly="readonly" value="0.00" style="text-align:right;"/></div> 
				   <div class="clear"></div>		
			    </div>	
			    <div class="row-form-booking">
				   <div class="span5">Amount Due</div>	
				   <div class="span6"><input type="text" id="amountdue" name="amountdue" readonly="readonly" value="0.00" style="text-align:right;"/></div> 	
				   <div class="clear"></div>		
			    </div>			       			    				    
		</div>
					                   
	</div>
</form>
</div>

<?php include('script.php'); ?>
