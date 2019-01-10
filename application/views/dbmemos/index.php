<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>
<form action="<?php echo site_url('dbmemo/saveDBMemo') ?>" method="post" name="saveForm" id="saveForm">
<div class="workplace">
	<div class="row-fluid">

		<div class="span3">
			<div class="head">
			    <div class="isw-cloud"></div>
			    <h1>Debit / Credit Memo</h1>
			    <div class="clear"></div>
			</div>
			<div class="block-fluid">                        

			    <div class="row-form-booking">
				   <div class="span5">DC Type:</div>
				   <div class="span5">
					<select name="dctype" id="dctype">						
						<option value="C">Credit</option>
                        <option value="D">Debit</option>
					</select>
				   </div>
				   <div class="clear"></div>
			    </div>                         
			    
			    <div class="row-form-booking">
				   <div class="span5">DC Number:</div>
				   <div class="span5"><input type="text" name="dcnumber" id="dcnumber"/></div>
				   <div class="clear"></div>
			    </div>                                   

			    <div class="row-form-booking">
				   <div class="span5">DC Date:</div>
				   <div class="span5"><input type="text" name="dcdate" id="dcdate"/></div>
				   <div class="clear"></div>
			    </div>                                                           

			    <div class="row-form-booking">
				   <div class="span5">DC Subtype:</div>
				   <div class="span7">
					<select name="dcsubtype" id="dcsubtype">
						<option value="">--</option>
						<?php 
						foreach ($dcsubtype as $dcsubtype) : ?>
						<option value="<?php echo $dcsubtype['id']?>"><?php echo$dcsubtype['dcsubtype_code'].' - '.$dcsubtype['dcsubtype_name'] ?></option>
						<?php                                                                                  
						endforeach;
						?>                       
					</select>
				   </div>
				   <div class="clear"></div>
			    </div>                         

			    <div class="row-form-booking">
				   <div class="span5">Ad Type:</div>
				   <div class="span7">
					<select name="dcadtype" id="dcadtype">
						<option value=''>--</option>
						<?php 
						foreach ($adtype as $adtype) : ?>
						<option value="<?php echo $adtype['id']?>"><?php echo $adtype['adtype_name'] ?></option>
						<?php                                                                                  
						endforeach;
						?>                       
					</select>
				   </div>
				   <div class="clear"></div>
			    </div>                                                 
			    
			</div>
		</div>

		<div class="span9">
			<div class="head">
			    <div class="isw-ok"></div>
			    <h1>Client Information</h1>            
                <ul class="buttons">                    
                    <li><a href="#" class="isw-refresh" id="b_new" title="New Debit/Credit Memo"></a></li>     
                    <?php if ($canADD) :?>                
                    <li><a href="#" class="isw-archive" id="b_save" title="Save Debit/Credit Memo"></a></li>
                    <?php endif; ?>     
                    <li><a href="#" class="isw-target" id="b_findinvoice" title="Lookup Using Invoice"></a></li>                 
                    <li><a href="#" class="isw-list" id="b_findao" title="Lookup Ads Using AO Number"></a></li>                 
                    <li><a href="#" class="isw-zoom" id="b_lookup" title="Lookup Debit/Credit Memo"></a></li> 
                    </ul> 
			    <div class="clear"></div>
			</div>
			<div class="block-fluid">                        			    
				   
			    <div class="row-form-booking">
				   <div class="span2">Client Name:</div>
				   <div class="span2"><input type="text" name="clientcode" id="clientcode"></div>
                   <div class="span5"><input type="text" name="clientname" id="clientname"></div>
                   <div class="span1"><input id="habol" type="checkbox" style="width: 30px;" value="1" checked="checked" name="habol">Habol</div>
                   <div class="span1" style="display: none"><input id="dcchoose" type="radio" style="width: 10px;" value="1" checked="checked" name="dcchoose">C</div>
				   <div class="span1" style="display: none"><input id="dcchoose" type="radio" style="width: 10px;" value="2" name="dcchoose">A</div>
				   <div class="clear"></div>
			    </div>                         

			    <div class="row-form-booking">
				   <div class="span2">Agency:</div>
				   <div class="span7">
					<select name="agency" id="agency">
						<!--<option value="">--</option>-->
					</select>
				   </div>
				   <div class="clear"></div>
			    </div> 

			    <div class="row-form-booking">
				   <div class="span2">D / C Amount:</div>
				   <div class="span2"><input type="text" name="dcamount" id="dcamount" value="0.00" style="text-align:right"></div>
				   <div class="span2">Assign Amount:</div>
				   <div class="span2"><input type="text" readonly="readonly" name="assigneamount" id="assigneamount" value="0.00" style="text-align:right"></div>
				   <div class="span1">Branch:</div>
				   <div class="span3">
					<select name="branch_m" id="branch_m">
						<option value="">--</option>
						<?php 
						foreach ($branch as $branch) { 
						if($this->session->userdata('authsess')->sess_branch == $branch['branch_code']) {
						?>                                         
						<option value='<?php echo $branch['id']?>' selected='selected'><?php echo $branch['branch_name']?></option>
						<?php        
						} else {
						?>                                         
						<option value='<?php echo $branch['id']?>'><?php echo $branch['branch_name']?></option>
						<?php    
						}
						}
						?>
					</select>
	
				   </div>
				   <div class="clear"></div>
			    </div>  

			    <div class="row-form-booking">
				   <div class="span2">Amount In Words:</div>
				   <div class="span10"><input type="text" name="dcamountinwords" id="dcamountinwords"></div>
				   <div class="clear"></div>
			    </div> 
			    <div class="row-form-booking">
				   <div class="span2">Particulars:</div>
				   <div class="span4"><textarea type="text" name="particulars" id="particulars" style="min-height: 30px;"></textarea></div>
				   <div class="span2">Comments:</div>
				   <div class="span4"><textarea name="comments" id="comments" style="min-height: 30px;"></textarea></div>	
				   <div class="clear"></div>
			    </div> 	
			</div>
				                      
		</div>
		       
	</div>
            
	<div class="row-fluid" id="applied_view">
	 
	 <div class="span12" style="margin-top:-15px">
		<div class="head">
		<div class="isw-grid"></div>
		<h1>Assignment List of Invoices</h1>                    
			<ul class="buttons">
                <?php if ($canDCAPPDM) :?>                
                <li><a href="#" class="isw-pin" id="importdm" title="Import Debit Memo"></a></li>    
                <?php endif; ?>                     
                <?php if ($canDCAPPINV) :?>    
                <!--<li><a href="#" class="isw-target" id="singleinv" title="Import Single Invoice"></a></li> -->                                                                                                                                                       
                <li><a href="#" class="isw-download" id="importinvoice" title="Import Invoice"></a></li>  
                <?php endif; ?>  
			</ul>                        
		<div class="clear"></div>
		</div>
		<div class="block-fluid">                        
		
			<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:240px"> 
				<table cellpadding="0" cellspacing="0" style="white-space:nowrap" class="table" id="tSortable_2">
				   <thead>
						<tr>						
							<th width="40px">Action</th>
							<th width="60px">Product</th>
							<th width="40px">Issue Date</th>
							<th width="40px">Type</th>                                    
							<th width="40px">Invoice/DM</th>       
							<th width="40px">Ad Type</th>      
							<th width="40px">Balance</th> 
							<th width="40px">Applied Amount</th>
							<th width="40px">Vatable Amount</th>												
							<th width="40px">VAT Amount</th>	
						</tr>
				   </thead>
				   <tbody class="assignment_list">					  					  			                     
				   </tbody>
			    </table>
			    <div class="clear"></div>
			</div>                                                                           
            <div class="row-form-booking mCSB_draggerContainer"> 
            <table cellpadding="0" cellspacing="0" class="table" style="white-space:nowrap">
               <thead>
                    <tr>                 
                        <th width="40px">Total Amount</th>                                                      
                        <th width="40px"><input type="text" id='totalamt' readonly='readonly' value="0.00" style='width:100px;text-align: right'></th>      
                        <th width="40px">Total Gross</th>                                                      
                        <th width="40px"><input type="text" id='totalgrossamt' readonly='readonly' value="0.00" style='width:100px;text-align: right'></th> 
                        <th width="40px">Total VAT Amount</th>                                                      
                        <th width="40px"><input type="text" id='totalvatamt' readonly='readonly' value="0.00" style='width:100px;text-align: right'></th>                        
                    </tr>
               </thead>
            </table>
            <div class="clear"></div>
            </div>
		</div>
	 </div>
	 
	</div>            
	<div class="row-fluid">
	 
	 <div class="span12" style="margin-top:-15px">
		<div class="head">
		<div class="isw-grid"></div>
		<h1>Accounting Entry</h1>                    
			<ul class="buttons">
                <li><a href="#" class="isw-target" id="manualacctentry" title="Manual Accounting"></a></li>                                                                        
				<li><a href="#" class="isw-text_document" id="acctentry" title="Accounting Entry"></a></li>                                                        				
			</ul>                        
		<div class="clear"></div>
		</div>
		<div class="block-fluid">                        
		
			<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:265px"> 
				<table cellpadding="0" cellspacing="0" style="white-space:nowrap" class="table" id="tSortable_2">
				   <thead>
						<tr>						
							<th width="20px">Action</th>
							<th width="60px">Account</th>
							<th width="40px">Department</th>
                            <th width="40px">Branch</th>                                    
							<th width="40px">Bank</th>                                    
							<th width="40px">Employee No.</th>       
							<th width="40px">Customer</th>      
							<th width="50px">Debit</th> 
							<th width="50px">Credit</th>
						</tr>
				   </thead>
				   <tbody class="accounting_entry_list">					  					  			                     
				   </tbody>
			    </table>
			    <div class="clear"></div>
			</div>                                                                           
		    <div class="row-form-booking mCSB_draggerContainer"> 
            <input type='hidden' id='hkey' value='<?php echo $hkey ?>'>
            <table cellpadding="0" cellspacing="0" class="table" style="white-space:nowrap">
               <thead>
                    <tr>                                         
                        <th width="40px">Total Debit Amount</th>                                                      
                        <th width="40px"><input type="text" id='totaldebitamount' readonly='readonly' style='width:100px;text-align: right'></th> 
                        <th width="40px">Total Credit Amount</th>                                                      
                        <th width="40px"><input type="text" id='totalcreditamount' readonly='readonly' style='width:100px;text-align: right'></th>                        
                    </tr>
               </thead>
            </table>
            <div class="clear"></div>
            </div>
		</div>
	 </div>
	 
	</div>            

	<div class="dr"><span></span></div>
</div>
</form>
<div id="model_importinvoice" title="Import Invoice List"></div>
<div id="model_importdm" title="Import Debit Memo List"></div>
<div id="modal_lookup" title="Lookup Debit/Credit Memo"></div>   
<div id="modal_findinvoice" title="Import Using Invoice"></div> 
<div id="modal_findao" title="Import Using AO Number"></div> 
<div id="modal_findao2" title="Import Using OR/CM Number"></div>    
<!--<div id="modal_singleinvoice" title="Import Single Invoice"></div>    -->
<?php include('script.php'); ?>
