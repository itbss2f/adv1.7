<div class="breadLine">
    <?php #print_r2($main); ?>
    <?php echo $breadcrumb; ?>
                        
</div>
<form action="<?php echo site_url('dbmemo/updateDBMemo/'.$main['dc_type'].'/'.$main['dc_num']) ?>" method="post" name="saveForm" id="saveForm">
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

    if ($main['status'] == 'O') {
        $readonly = "readonly = 'readonly';";
        $disable = "disabled = 'false';";
        $radiobut = "disabled = 'false';";
    }        
    ?>
    <div class="row-fluid">

        <div class="span3">
            <div class="head">
                <div class="isw-cloud"></div>
                <h1>Debit / Credit Memo</h1>
                <div class="clear"></div>
            </div>
            <div class="block-fluid">                        
                <input type="hidden" value="<?php echo $main['status'] ?>" name="poststat" id="poststat">
                <div class="row-form-booking">
                   <div class="span5">DC Type:</div>
                   <div class="span5">
                    <select name="dctype" id="dctype">                        
                        <?php if ($main['dc_type'] == 'C') {  ?>
                        <option value="C" <?php if ($main['dc_type'] == 'C') { echo "selected='selected'";} ?>>Credit</option>    
                        <?php } ?>
                        <?php if ($main['dc_type'] == 'D') {  ?>
                        <option value="D" <?php if ($main['dc_type'] == 'D') { echo "selected='selected'";} ?>>Debit</option>               
                        <?php } ?>                    
                    </select>
                   </div>
                   <?php if ($main['status'] != 'O') : ?>                    
                   <?php if ($canEDITDMTYPE) : ?>
                   <div class="span1">  
                    <button class="btn btn-success" type="button" name="b_dcmtype" id="b_dcmtype">Type</button>    
                   </div>         
                   <?php endif; ?>
                   <?php endif; ?>
                   
                   <div class="clear"></div>
                </div>                         
                
                <div class="row-form-booking">
                   <div class="span5">DC Number:</div>
                   <div class="span5"><input type="text" name="dcnumber" id="dcnumber"  value="<?php echo str_pad($main['dc_num'], 8, 0, STR_PAD_LEFT) ?>" disabled="disabled" /></div>
                   <?php if ($main['status'] != 'O') : ?>                    
                   <?php if ($canEDITDMTYPE) : ?>
                   <div class="span1">  
                    <button class="btn btn-success" type="button" name="b_changedcnum" id="b_changedcnum">Edit</button>    
                   </div>         
                   <?php endif; ?>
                   <?php endif; ?>   
                   <div class="clear"></div>
                </div>                                   

                <div class="row-form-booking">
                   <div class="span5">DC Date:</div>
                   <div class="span5"><input type="text" name="dcdate" id="dcdate"  <?php echo $readonly ?> value="<?php echo $main['dc_date'] ?>" /></div>
                   <div class="clear"></div>
                </div>                                                           

                <div class="row-form-booking">
                   <div class="span5">DC Subtype:</div>
                   <div class="span7">
                    <?php if ($main['status'] == 'O') : ?>
                    
                    <select name="dcsubtype" id="dcsubtype">
                        <option value="">--</option>
                        <?php   
                        foreach ($dcsubtype as $dcsubtype) : ?>     
                        <?php if ($dcsubtype['id'] == $main['dc_subtype']) : ?>
                        <option value="<?php echo $dcsubtype['id']?>" selected="selected"><?php echo$dcsubtype['dcsubtype_code'].' - '.$dcsubtype['dcsubtype_name'] ?></option>
                        <?php       
                        endif;                                                                           
                        endforeach;
                        ?>                       
                    </select>
                    
                    <?php else : ?>
                    <select name="dcsubtype" id="dcsubtype">
                        <option value="">--</option>
                        <?php   
                        foreach ($dcsubtype as $dcsubtype) : ?>     
                        <?php if ($dcsubtype['id'] == $main['dc_subtype']) : ?>
                        <option value="<?php echo $dcsubtype['id']?>" selected="selected"><?php echo$dcsubtype['dcsubtype_code'].' - '.$dcsubtype['dcsubtype_name'] ?></option>
                        <?php else: ?>
                        <option value="<?php echo $dcsubtype['id']?>"><?php echo$dcsubtype['dcsubtype_code'].' - '.$dcsubtype['dcsubtype_name'] ?></option>
                        <?php       
                        endif;                                                                           
                        endforeach;
                        ?>                       
                    </select>
                    <?php endif; ?>
                   </div>
                   <div class="clear"></div>
                </div>                         

                <div class="row-form-booking">
                   <div class="span5">Ad Type:</div>
                   <div class="span7">
                    <select name="dcadtype" id="dcadtype" <?php echo $disable ?> >
                        <option value=''>--</option>
                        <?php 
                        foreach ($adtype as $adtype) : ?>
                        <?php if ($adtype['id'] == $main['dc_adtype']) : ?>
                        <option value="<?php echo $adtype['id']?>" selected="selected"><?php echo $adtype['adtype_name'] ?></option>
                        <?php else: ?>
                        <option value="<?php echo $adtype['id']?>"><?php echo $adtype['adtype_name'] ?></option>
                        <?php       
                        endif;                                                                           
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
                    <?php if ($main['status'] == 'O' && $main['dc_amt'] != $main['dc_assignamt']) { ?>     
                    <?php if ($canEDIT) :?>                
                    <li><a href="#" class="isw-archive" id="b_save2" title="Save Debit/Credit Memo"></a></li>
                    <?php endif; ?>  
                    <?php } ?>
                    <?php if ($main['status'] != 'O') { ?>                    
                    <?php if ($canEDIT) :?>                
                    <li><a href="#" class="isw-archive" id="b_save2" title="Save Debit/Credit Memo"></a></li>
                    <?php endif; ?>  
                    <li><a href="#" class="isw-target" id="b_findinvoice" title="Lookup Using Invoice"></a></li>                                         
                    <?php } ?>            
                    <li><a href="#" class="isw-zoom" id="b_lookup" title="Lookup Debit/Credit Memo"></a></li> 
                    </ul> 
                <div class="clear"></div>
            </div>
            <div class="block-fluid">                                        
                  
                <div class="row-form-booking">
                   <div class="span2">Client Name:</div>
                   <div class="span2"><input type="text" name="clientcode" id="clientcode" value="<?php echo $main['dc_payee'] ?>" <?php if ($countapplied != 0) : ?> readonly="readonly" <?php endif; ?>></div>
                   <div class="span5"><input type="text" name="clientname" id="clientname" value="<?php echo str_replace('\\','',$main['dc_payeename']) ?>" <?php if ($countapplied != 0) : ?> readonly="readonly" <?php endif; ?>></div>
                   <div class="span1"><input id="habol" type="checkbox" style="width: 30px;" <?php echo $radiobut ?> <?php if ($main['dc_habol'] == 1)  { echo "checked='checked'"; } ?> value="1" name="habol">Habol</div>
                   <div class="span1"><input id="haboldate" type="text" style="width: 80px;" name="haboldate" placeholder="DATE" value="<?php echo $main['haboldate'] ?>" <?php echo $readonly ?>></div>
                   <div class="span1" style="display: none"><input id="dcchoose" type="radio" style="width: 10px;" <?php echo $radiobut ?> value="1" <?php if ($main['dc_payeetype'] == '1') { echo "checked='checked'"; }?> name="dcchoose">C</div>
                   <div class="span1" style="display: none"><input id="dcchoose" type="radio" style="width: 10px;" <?php echo $radiobut ?> value="2" <?php if ($main['dc_payeetype'] == '2') { echo "checked='checked'"; }?> name="dcchoose">A</div>
                   <div class="clear"></div>
                </div>                         
               
                <div class="row-form-booking">                    
                   <div class="span2">Agency:</div>
                   <div class="span7">
                    <select name="agency" id="agency">
                        <!--<option value="0">--</option>-->
                        <?php foreach ($dataagency as $darow) : ?>
                        <?php if ($darow['id'] == $main['dc_amf']) : ?>
                        <option value='<?php echo $darow['id'] ?>' selected="selected"><?php echo $darow['cmf_code'].' - '.$darow['cmf_name']?></option>
                        <?php else : ?>
                        <option value='<?php echo $darow['id'] ?>'><?php echo $darow['cmf_code'].' - '.$darow['cmf_name']?></option>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        
                    </select>
                   </div>   
                   <?php if ($main['status'] == 'O'): ?>   
                    <div class="span1" style="width:45px">Status:</div>
                    <div class="span2"><span class="label label-success" style="color:#FFFFFF; text-align: left;">POSTED</span></div>     
                    <?php endif; ?>                
                   <div class="clear"></div>
                </div> 
               
                <div class="row-form-booking">
                   <div class="span2">D / C Amount:</div>
                   <div class="span2"><input type="text" name="dcamount" id="dcamount" <?php echo $readonly ?> value="<?php echo number_format($main['dc_amt'], 2, '.', ',') ?>" style="text-align:right"></div>
                   <div class="span2">Assign Amount:</div>
                   <div class="span2"><input type="text" readonly="readonly" name="assigneamount" id="assigneamount" value="<?php echo number_format($main['dc_assignamt'], 2, '.', ',') ?>" style="text-align:right"></div>
                   <div class="span1">Branch:</div>
                   <div class="span3">
                    <select name="branch_m" id="branch_m" <?php echo $disable ?> >
                        <option value="">--</option>
                        <?php 
                        foreach ($branch as $branch) { 
                        if($branch['id'] == $main['dc_branch']) {
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
                   <div class="span6"><input type="text" name="dcamountinwords" readonly="readonly" id="dcamountinwords" value="<?php echo $main['dc_amtword'] ?>"></div>
                   <div class="span1">AR #:</div>
                   <div class="span3"><input type="text" name="refnum" id="refnum" value="<?php echo $main['dc_refnum'] ?>" <?php echo $readonly ?>></div>
                   <div class="clear"></div>
                </div> 
                <div class="row-form-booking">
                   <div class="span2">Particulars:</div>
                   <div class="span4"><textarea type="text" name="particulars" id="particulars" <?php echo $readonly ?> style="min-height: 30px;"><?php echo $main['dc_part'] ?></textarea></div>
                   <div class="span2">Comments:</div>
                   <div class="span4"><textarea name="comments" id="comments" <?php #echo $readonly ?> style="min-height: 30px;"><?php echo $main['dc_comment'] ?></textarea></div>    
                   <div class="clear"></div>
                </div>     
            </div>
                                      
        </div>
               
    </div>
    <?php 
    $hide = "";
    /*if ($main['dc_type'] == 'D') {  
        $hide = "style='display:none'";    
    }*/ 
    ?>           
    <div class="row-fluid" id="applied_view" <?php echo $hide ?>>
    
     <div class="span12" style="margin-top:-15px;">
        <div class="head">
        <div class="isw-grid"></div>
        <?php if ($main['dc_type'] == 'C') {  ?>  
        <h1>Assignment List of Invoices</h1>      
        <?php } else { ?>   
        <h1>Applies List of Sales Invoices / Official Reciepts</h1>      
        <?php } ?>                      
            <ul class="buttons">
                <?php if ($main['status'] != 'O') { ?>     
                <?php if ($main['dc_type'] == 'C') {  ?>
                <?php if ($canDCAPPINV) :?>                
                <li><a href="#" class="isw-pin" id="importdm" title="Import Debit Memo"></a></li>    
                <?php endif; ?>                     
                <?php if ($canDCAPPDM) :?>                                                                                    
                <li><a href="#" class="isw-download" id="importinvoice" title="Import Invoice"></a></li>  
                <?php endif; ?>                                                                                           
                <?php } ?>
                <?php } else if ($main['status'] == 'O' && $main['dc_amt'] > $main['dc_assignamt']) { ?>
                    <?php if ($main['dc_type'] == 'C') {  ?>
                        <?php if ($canDCAPPINV) :?>                
                        <li><a href="#" class="isw-pin" id="importdm" title="Import Debit Memo"></a></li>    
                    <?php endif; ?>                     
                                                                                           
                    <?php } ?>
                <?php } ?>
            </ul>                        
        <div class="clear"></div>
        </div>
        <div class="block-fluid">                                                
            <div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:240px;"> 
                <table cellpadding="0" cellspacing="0" style="white-space:nowrap" class="table" id="tSortable_2">
                   <thead>
                        <?php if ($main['dc_type'] == 'C') {  ?>           
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
                        <?php } else { ?>
                        <tr>                                                
                            <th width="40px">Applied Type</th>                                                        
                            <th width="40px">OR/ CM No.</th>  
                            <th width="60px">OR/DM Date</th>                                   
                            <th width="40px">Ad Type</th>                                  
                            <th width="40px">Gross Amount</th>
                            <th width="40px">Vatable Amount</th>                                                
                            <th width="40px">VAT Amount</th>    
                        </tr>
                        <?php } ?>
                   </thead>
                   <tbody class="assignment_list">               
                        <?php echo $assignview ?>                                                                                                                                           
                   </tbody>
                </table>
                <div class="clear"></div>
            </div>                                                                           
            <div class="row-form-booking mCSB_draggerContainer"> 
            <table cellpadding="0" cellspacing="0" class="table" style="white-space:nowrap">
               <thead>
                    <tr>                 
                        <th width="40px">Total Amount</th>                                                      
                        <th width="40px"><input type="text" id='totalamt' readonly='readonly' value="<?php echo number_format($totalassignamt, 2, '.', ',') ?>" style='width:100px;text-align: right'></th>      
                        <th width="40px">Total Gross</th>                                                      
                        <th width="40px"><input type="text" id='totalgrossamt' value="<?php echo number_format($totalgrossamt, 2, '.', ',') ?>" readonly='readonly' style='width:100px;text-align: right'></th> 
                        <th width="40px">Total VAT Amount</th>                                                      
                        <th width="40px"><input type="text" id='totalvatamt' value="<?php echo number_format($totalvatamt, 2, '.', ',') ?>" readonly='readonly' style='width:100px;text-align: right'></th>                        
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
                <?php if ($main['status'] != 'O') { ?>  
                <li><a href="#" class="isw-target" id="manualacctentry" title="Manual Accounting"></a></li>                                                                        
                <li><a href="#" class="isw-text_document" id="acctentry" title="Accounting Entry"></a></li>
                <?php } ?>
                                                                                   
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
                        <?php echo $acctentry_list ?> 
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
                        <th width="40px"><input type="text" id='totaldebitamount' readonly='readonly' value="<?php echo $totaldebit ?>" style='width:100px;text-align: right'></th> 
                        <th width="40px">Total Credit Amount</th>                                                      
                        <th width="40px"><input type="text" id='totalcreditamount' readonly='readonly' value="<?php echo $totalcredit ?>" style='width:100px;text-align: right'></th>                        
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
<?php include('script.php'); ?>
