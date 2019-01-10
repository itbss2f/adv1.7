<div class="block-fluid">      
    <form action="<?php echo site_url('adparameter/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    
    <div id="tabs">
        <ul>
            <li><a href="#basic">Basic Info</a></li>
            <li><a href="#detailed">Detailed Info</a></li>
            <li><a href="#more">More Info</a></li>
            <li><a href="#advance">Advance Info</a></li>
            <li><a href="#other1">Other Info 1</a></li> 
            <li><a href="#other2">Other Info 2</a></li>
            <li><a href="#other3">Other Info 3</a></li>
            <li><a href="#other4">Other Info 4</a></li>
            <li><a href="#other5">Other Info 5</a></li>  
        </ul>
        <div id="basic">
            <div class="row-form-booking">
                <div class="span1"><b>Code</b></div>    
                <div class="span1"><input type="text" name="com_code" id="com_code" value="<?php echo $data['com_code'] ?>" readonly="readonly"></div>   
                <div class="span1"><b>Name</b></div>    
                <div class="span7"><input type="text" name="com_name" id="com_name" value="<?php echo $data['com_name'] ?>"></div>             
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Address</b></div>    
                <div class="span9"><input type="text" name="com_add1" id="com_add1" value="<?php echo $data['com_add1'] ?>"></div>             
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Address</b></div>    
                <div class="span9"><input type="text" name="com_add2" id="com_add2" value="<?php echo $data['com_add2'] ?>"></div>             
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Address</b></div>    
                <div class="span9"><input type="text" name="com_add3" id="com_add3" value="<?php echo $data['com_add3'] ?>"></div>             
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Tel No.</b></div>    
                <div class="span1"><input type="text" name="com_tel1" id="com_tel1" value="<?php echo $data['com_tel1'] ?>"></div>
                <div class="span3"><input type="text" name="com_tel1" id="com_tel1" value="<?php echo $data['com_tel1'] ?>"></div>
                <div class="span1"><b>Tel No.</b></div>    
                <div class="span1"><input type="text" name="com_tel2" id="com_tel2" value="<?php echo $data['com_tel2'] ?>"></div>               
                <div class="span3"><input type="text" name="com_tel2" id="com_tel2" value="<?php echo $data['com_tel2'] ?>"></div>               
                <div class="clear"></div>    
            </div>
                 <div class="row-form-booking">        
                <div class="span1"><b>Fax No.</b></div>    
                <div class="span1"><input type="text" name="com_fax1" id="com_fax1" value="<?php echo $data['com_fax1'] ?>"></div>
                <div class="span3"><input type="text" name="com_fax1" id="com_fax1" value="<?php echo $data['com_fax1'] ?>"></div>  
                <div class="span1"><b>Fax No.</b></div>    
                <div class="span1"><input type="text" name="com_fax2" id="com_fax2" value="<?php echo $data['com_fax2'] ?>"></div>  
                <div class="span3"><input type="text" name="com_fax2" id="com_fax2" value="<?php echo $data['com_fax2'] ?>"></div>               
                <div class="clear"></div>    
            </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Contact Person1</b></div>    
                <div class="span3"><input type="text" name="com_cp1" id="com_cp1" value="<?php echo $data['com_cp1'] ?>"></div>             
                <div class="span2"><b>Contact Person2</b></div>    
                <div class="span3"><input type="text" name="com_cp2" id="com_cp2" value="<?php echo $data['com_cp2'] ?>"></div> 
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>Contact Person3</b></div>    
                <div class="span3"><input type="text" name="com_cp3" id="com_cp3" value="<?php echo $data['com_cp3'] ?>"></div>
                <div class="span2"><b>Contact Person4</b></div>    
                <div class="span3"><input type="text" name="com_cp4" id="com_cp4" value="<?php echo $data['com_cp4'] ?>"></div>              
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>SSS No.</b></div>    
                <div class="span4"><input type="text" name="com_sss" id="com_sss" value="<?php echo $data['com_sss'] ?>"></div>
                <div class="span1"><b>TIN No.</b></div>    
                <div class="span4"><input type="text" name="com_tin" id="com_tin" value="<?php echo $data['com_tin'] ?>"></div>               
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>RSN</b></div>    
                <div class="span2"><input type="text" name="com_rsn" id="com_rsn" value="<?php echo $data['com_rsn'] ?>"></div>
                <div class="span1"><b>RSP</b></div>    
                <div class="span2"><input type="text" name="com_rsp" id="com_rsp" value="<?php echo $data['com_rsp'] ?>"></div>
                <div class="span1"><b>RSD</b></div>    
                <div class="span3"><input type="text" name="com_rsd" id="com_rsd" value="<?php echo $data['com_rsd'] ?>"></div>               
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>Ad Parameter Zip</b></div>    
                <div class="span4"><input type="text" name="com_zip" id="com_zip" value="<?php echo $data['com_zip'] ?>"></div>               
                <div class="span2"><b>Ad Parameter Type</b></div>    
                <div class="span2"><input type="text" name="com_type" id="com_type" value="<?php echo $data['com_type'] ?>"></div>
                <div class="clear"></div>                
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>Authorized Name</b></div>    
                <div class="span8"><input type="text" name="cert_authorized_name" id="cert_authorized_name" value="<?php echo $data['cert_authorized_name'] ?>"></div>
                <div class="clear"></div>
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>Authorized TIN</b></div>    
                <div class="span3"><input type="text" name="cert_authorized_tin" id="cert_authorized_tin" value="<?php echo $data['cert_authorized_tin'] ?>" ></div>
                <div class="span2"><b>Authorized Title</b></div>    
                <div class="span3"><input type="text" name="cert_authorized_title" id="cert_authorized_title" value="<?php echo $data['cert_authorized_title'] ?>"></div>
                <div class="clear"></div>
            </div>            
        </div>
        <div id="detailed">
            <div class="row-form-booking">        
                <div class="span1"><b>Log-In</b></div>    
                <div class="span4"><input type="text" name="p_login" id="p_login" value="<?php echo $data['p_login'] ?>"></div>
                <div class="span1"><b>Logo</b></div>    
                <div class="span4"><input type="text" name="p_logo" id="p_logo" value="<?php echo $data['p_logo'] ?>"></div>
                <div class="clear"></div>
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Path</b></div>    
                <div class="span9"><input type="text" name="ini_path" id="p_logo" value="<?php echo $data['p_logo'] ?>"></div>                   
                <div class="clear"></div>     
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>ACCTG Status</b></div>    
                <div class="span3"><input type="text" name="acctg_status" id="acctg_status" value="<?php echo $data['acctg_status'] ?>"></div>
                <div class="span2"><b>Credit Status</b></div>    
                <div class="span3"><input type="text" name="credit_status" id="credit_status" value="<?php echo $data['credit_status'] ?>"></div>               
                <div class="clear"></div>                
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>DC Status</b></div>    
                <div class="span3"><input type="text" name="dc_stauts" id="dc_status" value="<?php echo $data['dc_status'] ?>"></div>
                <div class="span2"><b>Last Number Status</b></div>    
                <div class="span3"><input type="text" name="lastnum_status" id="lastnum_status" value="<?php echo $data['lastnum_status'] ?>"></div>               
                <div class="clear"></div>                
            </div>
                <div class="row-form-booking">        
                <div class="span2"><b>Begin Date</b></div>    
                <div class="span8"><input type="text" name="beg_date" id="beg_date" value="<?php echo $data['beg_date'] ?>"></div>
                <div class="clear"></div>
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Assets</b></div>    
                <div class="span4"><input type="text" name="asset" id="asset" value="<?php echo $data['asset'] ?>"></div>
                <div class="span1"><b>Liability</b></div>    
                <div class="span4"><input type="text" name="liability" id="liability" value="<?php echo $data['liability'] ?>"></div>               
                <div class="clear"></div>
            </div>     
            <div class="row-form-booking">        
                <div class="span1"><b>Equity</b></div>    
                <div class="span4"><input type="text" name="equity" id="equity" value="<?php echo $data['equity'] ?>"></div>
                <div class="span1"><b>Revenues</b></div>    
                <div class="span4"><input type="text" name="revenues" id="revenues" value="<?php echo $data['revenues'] ?>"></div>               
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Expenses</b></div>    
                <div class="span2"><input type="text" name="expenses" id="expenses" value="<?php echo $data['expenses'] ?>"></div>
                <div class="span1"><b>Retained</b></div>    
                <div class="span2"><input type="text" name="retained" id="retained" value="<?php echo $data['retained'] ?>"></div>
                <div class="span1"><b>Current</b></div>    
                <div class="span3"><input type="text" name="current_e" id="current_e" value="<?php echo $data['current_e'] ?>"></div>               
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Inc Tax P</b></div>    
                <div class="span4"><input type="text" name="inc_tax_p" id="inc_txt_p" value="<?php echo $data['inc_tax_p'] ?>"></div>
                <div class="span1"><b>Inc Tax V</b></div>    
                <div class="span4"><input type="text" name="inc_tax_v" id="inc_tax_v" value="<?php echo $data['inc_tax_v'] ?>"></div>               
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>Cash Bank</b></div>    
                <div class="span3"><input type="text" name="cashbank" id="cashbank" value="<?php echo $data['cashbank'] ?>"></div>
                <div class="span2"><b>Cash Hand</b></div>    
                <div class="span3"><input type="text" name="cashhand" id="cashhand" value="<?php echo $data['cashhand'] ?>"></div>               
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>ACCT Rec</b></div>    
                <div class="span3"><input type="text" name="acct_rec" id="acct_rec" value="<?php echo $data['acct_rec'] ?>"></div>
                <div class="span2"><b>ACCT Rec Adv</b></div>    
                <div class="span3"><input type="text" name="acct_rec_adv" id="acct_rec_adv" value="<?php echo $data['acct_rec_adv'] ?>"></div>              
                <div class="clear"></div>         
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>ACCT Rec CIR</b></div>    
                <div class="span3"><input type="text" name="acct_rec_cir" id="acct_rec_cir" value="<?php echo $data['acct_rec_cir'] ?>"></div>
                <div class="span2"><b>ACCT Rec Subs</b></div>    
                <div class="span3"><input type="text" name="acct_rec_subs" id="acct_rec_subs" value="<?php echo $data['acct_rec_subs'] ?>"></div>             
                <div class="clear"></div>         
            </div>
            <div class="row-form-booking">   
                <div class="span2"><b>ACCT Rec Nontrade</b></div>    
                <div class="span3"><input type="text" name="acct_rec_nontrade" id="acct_rec_nontrade" value="<?php echo $data['acct_rec_nontrade'] ?>"></div> 
                <div class="span2"><b>ACCT Pay Nontrade</b></div>    
                <div class="span3"><input type="text" name="acct_pay_nontrade" id="acct_pay_nontrade" value="<?php echo $data['acct_pay_nontrade'] ?>"></div>
                <div class="clear"></div>         
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Notarial1</b></div>    
                <div class="span4"><input type="text" name="notarial" id="notarial" value="<?php echo $data['notarial'] ?>"></div>
                <div class="span1"><b>Notarial2</b></div>    
                <div class="span4"><input type="text" name="notarial2" id="notarial2" value="<?php echo $data['notarial2'] ?>"></div>               
                <div class="clear"></div>         
            </div>   
        </div> 
        <div id="more">
            <div class="row-form-booking">        
                <div class="span1"><b>Income</b></div>    
                <div class="span2"><input type="text" name="income" id="income" value="<?php echo $data['income'] ?>"></div>
                <div class="span2"><b>Account Pay</b></div>    
                <div class="span2"><input type="text" name="acct_pay" id="acct_pay" value="<?php echo $data['acct_pay'] ?>"></div>
                <div class="span1"><b>ADV Emp</b></div>    
                <div class="span2"><input type="text" name="adv_emp" id="avd_emp" value="<?php echo $data['adv_emp'] ?>"></div>               
                <div class="clear"></div>         
            </div>
           <div class="row-form-booking">        
                <div class="span2"><b>UE Subscription</b></div>    
                <div class="span3"><input type="text" name="ue_subscription" id="ue_subscription" value="<?php echo $data['ue_subscription'] ?>"></div>
                <div class="span2"><b>UE Advertising</b></div>    
                <div class="span3"><input type="text" name="ue_advertising" id="ue_advertising" value="<?php echo $data['ue_advertising'] ?>"></div>              
                <div class="clear"></div>         
            </div> 
           <div class="row-form-booking">        
                <div class="span1"><b>CR WTax</b></div>    
                <div class="span2"><input type="text" name="cr_wtax" id="cr_wtax" value="<?php echo $data['cr_wtax'] ?>"></div>
                <div class="span2"><b>AR WTax</b></div>    
                <div class="span2"><input type="text" name="ar_wtax" id="ar_wtax" value="<?php echo $data['ar_wtax'] ?>"></div>
                <div class="span1"><b>EXP WTax</b></div>    
                <div class="span2"><input type="text" name="exp_wtax" id="exp_wtax" value="<?php echo $data['exp_wtax'] ?>"></div>               
                <div class="clear"></div>         
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>WH Vat</b></div>    
                <div class="span2"><input type="text" name="withholding_vat" id="withholding_vat" value="<?php echo $data['withholding_vat'] ?>"></div>
                <div class="span2"><b>Output Vat</b></div>    
                <div class="span2"><input type="text" name="output_vat" id="output_vat" value="<?php echo $data['output_vat'] ?>"></div>
                <div class="span1"><b>Input Vat</b></div>    
                <div class="span2"><input type="text" name="input_vat" id="intput_vat" value="<?php echo $data['input_vat'] ?>"></div>               
                <div class="clear"></div>         
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>Fixed Asset</b></div>    
                <div class="span3"><input type="text" name="fixed_asset" id="fixed_asset" value="<?php echo $data['fixed_asset'] ?>"></div>
                <div class="span2"><b>Supplies</b></div>    
                <div class="span3"><input type="text" name="supplies" id="supplies" value="<?php echo $data['supplies'] ?>"></div>               
                <div class="clear"></div>         
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>Transpo Exp</b></div>    
                <div class="span3"><input type="text" name="transpo_exp" id="transpo_exp" value="<?php echo $data['transpo_exp'] ?>"></div>
                <div class="span2"><b>AP Nontrade</b></div>    
                <div class="span3"><input type="text" name="ap_nontrade" id="ap_nontrade" value="<?php echo $data['ap_nontrade'] ?>"></div>               
                <div class="clear"></div>         
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>Rev Subs CDN</b></div>    
                <div class="span3"><input type="text" name="rev_subs_cdn" id="rev_subs_cdn" value="<?php echo $data['rev_subs_cdn'] ?>"></div>
                <div class="span2"><b>AP Trade PDI</b></div>    
                <div class="span3"><input type="text" name="ap_trade_pdi" id="ap_trade_pdi" value="<?php echo $data['ap_trade_pdi'] ?>"></div>               
                <div class="clear"></div>         
            </div>
           <div class="row-form-booking">        
                <div class="span2"><b>Bank Charges</b></div>    
                <div class="span3"><input type="text" name="bank_charges" id="bank_charges" value="<?php echo $data['bank_charges'] ?>"></div>
                <div class="span2"><b>Disc Allowed</b></div>    
                <div class="span3"><input type="text" name="disc_allowed" id="disc_allowed" value="<?php echo $data['disc_allowed'] ?>"></div>               
                <div class="clear"></div>         
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>EXP Basic</b></div>    
                <div class="span3"><input type="text" name="exp_basic" id="exp_basic" value="<?php echo $data['exp_basic'] ?>"></div>
                <div class="span2"><b>EXP SLConv</b></div>    
                <div class="span3"><input type="text" name="exp_slconv" id="exp_slconv" value="<?php echo $data['exp_slconv'] ?>"></div>               
                <div class="clear"></div>         
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>EXP VLConv</b></div>    
                <div class="span3"><input type="text" name="exp_vlconv" id="exp_vlconv" value="<?php echo $data['exp_vlconv'] ?>"></div>
                <div class="span2"><b>EXP Bonus</b></div>    
                <div class="span3"><input type="text" name="exp_bonus" id="exp_bonus" value="<?php echo $data['exp_bonus'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>EXP 13thmo</b></div>    
                <div class="span3"><input type="text" name="exp_13thmo" id="exp_13thmo" value="<?php echo $data['exp_13thmo'] ?>"></div>
                <div class="span2"><b>EXP Retfund</b></div>    
                <div class="span3"><input type="text" name="exp_retfund" id="exp_retfund" value="<?php echo $data['exp_retfund'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>ACCR Basic</b></div>    
                <div class="span3"><input type="text" name="accr_basic" id="accr_basic" value="<?php echo $data['accr_basic'] ?>"></div>
                <div class="span2"><b>ACCR SLConv</b></div>    
                <div class="span3"><input type="text" name="accr_slconv" id="accr_slconv" value="<?php echo $data['accr_slconv'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>ACCR VLConv</b></div>    
                <div class="span3"><input type="text" name="accr_vlconv" id="accr_vlconv" value="<?php echo $data['accr_vlconv'] ?>"></div>
                <div class="span2"><b>ACCR Bonus</b></div>    
                <div class="span3"><input type="text" name="accr_bonus" id="accr_bonus" value="<?php echo $data['accr_bonus'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>ACCR 13thmo</b></div>    
                <div class="span3"><input type="text" name="accr_13thmo" id="accr_13thmo" value="<?php echo $data['accr_13thmo'] ?>"></div>
                <div class="span2"><b>ACCR Retfund</b></div>    
                <div class="span3"><input type="text" name="accr_retfund" id="accr_retfund" value="<?php echo $data['accr_retfund'] ?>"></div>               
                <div class="clear"></div> 
            </div>
        </div>
        <div id="advance">
            <div class="row-form-booking">        
                <div class="span1"><b>Last JV</b></div>    
                <div class="span4"><input type="text" name="last_jv" id="last_jv" value="<?php echo $data['last_jv'] ?>"></div>
                <div class="span1"><b>Last CV</b></div>    
                <div class="span4"><input type="text" name="last_cv" id="last_cv" value="<?php echo $data['last_cv'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Last AP</b></div>    
                <div class="span4"><input type="text" name="last_ap" id="last_ap" value="<?php echo $data['last_ap'] ?>"></div>
                <div class="span1"><b>Last OR</b></div>    
                <div class="span4"><input type="text" name="last_or" id="last_or" value="<?php echo $data['last_or'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Last SI</b></div>    
                <div class="span4"><input type="text" name="last_si" id="last_si" value="<?php echo $data['last_si'] ?>"></div>
                <div class="span1"><b>Last DC</b></div>    
                <div class="span4"><input type="text" name="last_dc" id="last_dc" value="<?php echo $data['last_dc'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Last AO</b></div>    
                <div class="span4"><input type="text" name="last_ao" id="last_ao" value="<?php echo $data['last_ao'] ?>"></div>
                <div class="span1"><b>Last AR</b></div>    
                <div class="span4"><input type="text" name="last_ar" id="last_ar" value="<?php echo $data['last_ar'] ?>"></div>               
                <div class="clear"></div> 
            </div>        
            <div class="row-form-booking">        
                <div class="span1"><b>Last DR</b></div>    
                <div class="span4"><input type="text" name="last_dr" id="last_dr" value="<?php echo $data['last_dr'] ?>"></div>
                <div class="span1"><b>Last RS</b></div>    
                <div class="span4"><input type="text" name="last_rs" id="last_rs" value="<?php echo $data['last_rs'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>Last Pinum</b></div>    
                <div class="span3"><input type="text" name="last_pinum" id="last_pinum" value="<?php echo $data['last_pinum'] ?>"></div>
                <div class="span2"><b>Last Subsdr</b></div>    
                <div class="span3"><input type="text" name="last_subsdr" id="last_subsdr" value="<?php echo $data['last_subsdr'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Last PO</b></div>    
                <div class="span4"><input type="text" name="last_po" id="last_po" value="<?php echo $data['last_po'] ?>"></div>
                <div class="span1"><b>Last IR</b></div>    
                <div class="span4"><input type="text" name="last_ir" id="last_ir" value="<?php echo $data['last_ir'] ?>"></div>               
                <div class="clear"></div> 
            </div>       
            <div class="row-form-booking">        
                <div class="span1"><b>Last MR</b></div>    
                <div class="span4"><input type="text" name="last_mr" id="last_mr" value="<?php echo $data['last_mr'] ?>"></div>
                <div class="span1"><b>Last RI</b></div>    
                <div class="span4"><input type="text" name="last_ri" id="last_ri" value="<?php echo $data['last_ri'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Last IA</b></div>    
                <div class="span4"><input type="text" name="last_ia" id="last_ia" value="<?php echo $data['last_ia'] ?>"></div>
                <div class="span1"><b>Last CCC</b></div>    
                <div class="span4"><input type="text" name="last_ccc" id="last_ccc" value="<?php echo $data['last_ccc'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Last CTA</b></div>    
                <div class="span4"><input type="text" name="last_cta" id="last_cta" value="<?php echo $data['last_cta'] ?>"></div>
                <div class="span1"><b>Last SU</b></div>    
                <div class="span4"><input type="text" name="last_su" id="last_su" value="<?php echo $data['last_su'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span1"><b>Last PO D</b></div>    
                <div class="span4"><input type="text" name="last_po_d" id="last_po_d" value="<?php echo $data['last_po_d'] ?>"></div>
                <div class="span1"><b>Last IR D</b></div>    
                <div class="span4"><input type="text" name="last_ir_d" id="last_ir_d" value="<?php echo $data['last_ir_d'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>Last MR D</b></div>    
                <div class="span3"><input type="text" name="last_mr_d" id="last_mr_d" value="<?php echo $data['last_mr_d'] ?>"></div>
                <div class="span2"><b>Last RI D</b></div>    
                <div class="span3"><input type="text" name="last_ri_d" id="last_ri_d" value="<?php echo $data['last_ri_d'] ?>"></div>               
                <div class="clear"></div> 
            </div>
            <div class="row-form-booking">        
                <div class="span2"><b>Last IA D</b></div>    
                <div class="span3"><input type="text" name="last_ia_d" id="last_ia_d" value="<?php echo $data['last_ia_d'] ?>"></div>
                <div class="span2"><b>Last SA ADV</b></div>    
                <div class="span3"><input type="text" name="last_sa_adv" id="last_sa_adv" value="<?php echo $data['last_sa_adv'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Last SA CIR</b></div>    
                <div class="span3"><input type="text" name="last_sa_cir" id="last_sa_cir" value="<?php echo $data['last_sa_cir'] ?>"></div>
                <div class="span2"><b>Last SA EXDEAL</b></div>    
                <div class="span3"><input type="text" name="last_sa_exdeal" id="last_sa_exdeal" value="<?php echo $data['last_sa_exdeal'] ?>"></div>               
                <div class="clear"></div>
             </div>  
        </div>
        <div id="other1">
             <div class="row-form-booking">        
                <div class="span1"><b>Last CK</b></div>    
                <div class="span4"><input type="text" name="last_ck" id="last_ck" value="<?php echo $data['last_ck'] ?>"></div>
                <div class="span1"><b>Last YR</b></div>    
                <div class="span4"><input type="text" name="last_yr" id="last_yr" value="<?php echo $data['last_yr'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span1"><b>Last MON</b></div>    
                <div class="span4"><input type="text" name="last_mon" id="last_mon" value="<?php echo $data['last_mon'] ?>"></div>
                <div class="span1"><b>Last SA</b></div>    
                <div class="span4"><input type="text" name="last_sa" id="last_sa" value="<?php echo $data['last_sa'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span3"><b>Last Riderpayroll SU From</b></div>    
                <div class="span2"><input type="text" name="last_riderpayroll_su_from" id="last_riderpayroll_su_from" value="<?php echo $data['last_riderpayroll_su_from'] ?>"></div>
                <div class="span3"><b>Last Riderpayroll SU To</b></div>    
                <div class="span2"><input type="text" name="last_riderpayroll_su_to" id="last_riderpayroll_su_to" value="<?php echo $data['last_riderpayroll_su_to'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span3"><b>Last Riderpayroll CIR From</b></div>    
                <div class="span2"><input type="text" name="last_riderpayroll_cir_from" id="last_riderpayroll_cir_from" value="<?php echo $data['last_riderpayroll_cir_from'] ?>"></div>
                <div class="span3"><b>Last Riderpayroll CIR To</b></div>    
                <div class="span2"><input type="text" name="last_riderpayroll_cir_to" id="last_riderpayroll_cir_to" value="<?php echo $data['last_riderpayroll_cir_to'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span1"><b>Tax Rate</b></div>    
                <div class="span4"><input type="text" name="tax_rate" id="tax_rate" value="<?php echo $data['tax_rate'] ?>"></div>
                <div class="span1"><b>Vat Rate</b></div>    
                <div class="span4"><input type="text" name="vat_rate" id="vat_rate" value="<?php echo $data['vat_rate'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Reb SMFATCCODE</b></div>    
                <div class="span3"><input type="text" name="reb_smfatccode" id="reb_smfatccode" value="<?php echo $data['reb_smfatccode'] ?>"></div>
                <div class="span2"><b>Reb SMFTRATE</b></div>    
                <div class="span3"><input type="text" name="reb_smftrate" id="reb_smftrate" value="<?php echo $data['reb_smftrate'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>CCC SMFATCCODE</b></div>    
                <div class="span3"><input type="text" name="ccc_smfatccode" id="ccc_smfatccode" value="<?php echo $data['ccc_smfatccode'] ?>"></div>
                <div class="span2"><b>CCC SMFTRATE</b></div>    
                <div class="span3"><input type="text" name="ccc_smftrate" id="ccc_smftrate" value="<?php echo $data['ccc_smftrate'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Target Sale</b></div>    
                <div class="span3"><input type="text" name="target_sales" id="target_sales" value="<?php echo $data['target_sales'] ?>"></div>
                <div class="span2"><b>Target Sales2</b></div>    
                <div class="span3"><input type="text" name="target_sales2" id="target_sales2" value="<?php echo $data['target_sales2'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Target CM</b></div>    
                <div class="span3"><input type="text" name="target_cm" id="target_cm" value="<?php echo $data['target_cm'] ?>"></div>
                <div class="span2"><b>Target CM2</b></div>    
                <div class="span3"><input type="text" name="target_cm2" id="target_cm2" value="<?php echo $data['target_cm2'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>CMF Various</b></div>    
                <div class="span3"><input type="text" name="cmf_various" id="cmf_various" value="<?php echo $data['cmf_various'] ?>"></div>
                <div class="span2"><b>AGNT Various</b></div>    
                <div class="span3"><input type="text" name="agnt_various" id="agnt_various" value="<?php echo $data['agnt_various'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>SUBS Various</b></div>    
                <div class="span3"><input type="text" name="subs_various" id="subs_various" value="<?php echo $data['subs_various'] ?>"></div>
                <div class="span2"><b>SMF Various</b></div>    
                <div class="span3"><input type="text" name="smf_various" id="smf_various" value="<?php echo $data['smf_various'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>EMP Various</b></div>    
                <div class="span3"><input type="text" name="emp_various" id="emp_various" value="<?php echo $data['emp_various'] ?>"></div>
                <div class="span2"><b>Bank Account</b></div>    
                <div class="span3"><input type="text" name="bank_account" id="bank_account" value="<?php echo $data['bank_account'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span1"><b>CV Type</b></div>    
                <div class="span4"><input type="text" name="cv_type" id="cv_type" value="<?php echo $data['cv_type'] ?>"></div>
                <div class="span1"><b>AP Type</b></div>    
                <div class="span4"><input type="text" name="ap_type" id="ap_type" value="<?php echo $data['ap_type'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span1"><b>SI Type</b></div>    
                <div class="span4"><input type="text" name="si_type" id="si_type" value="<?php echo $data['si_type'] ?>"></div>
                <div class="span1"><b>DR Type</b></div>    
                <div class="span4"><input type="text" name="dr_type" id="dr_type" value="<?php echo $data['dr_type'] ?>"></div>               
                <div class="clear"></div>
             </div>
        </div>
        <div id="other2"> 
             <div class="row-form-booking">        
                <div class="span1"><b>RS Type</b></div>    
                <div class="span4"><input type="text" name="rs_type" id="rs_type" value="<?php echo $data['rs_type'] ?>"></div>
                <div class="span2"><b>AGY Comm</b></div>    
                <div class="span3"><input type="text" name="agy_comm" id="agy_comm" value="<?php echo $data['agy_comm'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Points Per Unit</b></div>    
                <div class="span3"><input type="text" name="pointsperunit" id="pointsperunit" value="<?php echo $data['pointsperunit'] ?>"></div>
                <div class="span2"><b>Country Code</b></div>    
                <div class="span3"><input type="text" name="country_code" id="country_code" value="<?php echo $data['country_code'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Vat Rate 1</b></div>    
                <div class="span3"><input type="text" name="vat_rate1" id="vat_rate1" value="<?php echo $data['vat_rate1'] ?>"></div>
                <div class="span2"><b>Vat Rate 2</b></div>    
                <div class="span3"><input type="text" name="vat_rate2" id="vat_rate2" value="<?php echo $data['vat_rate2'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Vat Rate 3</b></div>    
                <div class="span3"><input type="text" name="vat_rate3" id="vat_rate3" value="<?php echo $data['vat_rate3'] ?>"></div>
                <div class="span2"><b>Vat Rate 4</b></div>    
                <div class="span3"><input type="text" name="vat_rate4" id="vat_rate4" value="<?php echo $data['vat_rate4'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Vat Rate 5</b></div>    
                <div class="span3"><input type="text" name="vat_rate5" id="vat_rate5" value="<?php echo $data['vat_rate5'] ?>"></div>
                <div class="span2"><b>Valid From 1</b></div>    
                <div class="span3"><input type="text" name="valid_from_1" id="valid_from_1" value="<?php echo $data['valid_from_1'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Valid From 2</b></div>    
                <div class="span3"><input type="text" name="valid_from_2" id="valid_from_2" value="<?php echo $data['valid_from_2'] ?>"></div>
                <div class="span2"><b>Valid From 3</b></div>    
                <div class="span3"><input type="text" name="valid_from_3" id="valid_from_3" value="<?php echo $data['valid_from_3'] ?>"></div>               
                <div class="clear"></div>
             </div>                      
             <div class="row-form-booking">        
                <div class="span2"><b>Valid From 4</b></div>    
                <div class="span3"><input type="text" name="valid_from_4" id="valid_from_4" value="<?php echo $data['valid_from_4'] ?>"></div>
                <div class="span2"><b>Valid From 5</b></div>    
                <div class="span3"><input type="text" name="valid_from_5" id="valid_from_5" value="<?php echo $data['valid_from_5'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Valid To 1</b></div>    
                <div class="span3"><input type="text" name="valid_to_1" id="valid_to_1" value="<?php echo $data['valid_to_1'] ?>"></div>
                <div class="span2"><b>Valid To 2</b></div>    
                <div class="span3"><input type="text" name="valid_to_2" id="valid_to_2" value="<?php echo $data['valid_to_2'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Valid To 3</b></div>    
                <div class="span3"><input type="text" name="valid_to_to_3" id="valid_to_3" value="<?php echo $data['valid_to_3'] ?>"></div>
                <div class="span2"><b>Valid To 4</b></div>    
                <div class="span3"><input type="text" name="valid_to_4" id="valid_to_4" value="<?php echo $data['valid_to_4'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>Valid TO 5</b></div>    
                <div class="span8"><input type="text" name="valid_to_5" id="valid_to_5" value="<?php echo $data['valid_to_5'] ?>"></div>             
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">
                <div class="span2"><b>JV Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="jv_approver1_name" id="jv_approver1_name" value="<?php echo $data['jv_approver1_name'] ?>"></div>          
                <div class="span2"><b>JV Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="jv_approver1_title" id="jv_approver1_title" value="<?php echo $data['jv_approver1_title'] ?>"></div>              
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">
                <div class="span2"><b>JV Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="jv_approver2_name" id="jv_approver2_name" value="<?php echo $data['jv_approver2_name'] ?>"></div>         
                <div class="span2"><b>JV Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="jv_approver2_title" id="jv_approver2_title" value="<?php echo $data['jv_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>CV Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="cv_approver1_name" id="cv_approver1_name" value="<?php echo $data['cv_approver1_name'] ?>"></div>
                <div class="span2"><b>CV Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="cv_approver1_title" id="cv_approver1_title" value="<?php echo $data['cv_approver1_title'] ?>"></div>              
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>CV Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="cv_approver2_name" id="cv_approver2_name" value="<?php echo $data['cv_approver2_name'] ?>"></div> 
                <div class="span2"><b>CV Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="cv_approver2_title" id="cv_approver2_title" value="<?php echo $data['cv_approver2_title'] ?>"></div>            
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>AP Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="ap_approver1_name" id="ap_approver1_name" value="<?php echo $data['ap_approver1_name'] ?>"></div>   
                <div class="span2"><b>AP Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="ap_approver1_title" id="ap_approver1_title" value="<?php echo $data['ap_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
        </div>
        <div id="other3">
            <div class="row-form-booking">        
                <div class="span2"><b>AP Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="ap_approver2_name" id="ap_approver2_name" value="<?php echo $data['ap_approver2_name'] ?>"></div>   
                <div class="span2"><b>AP Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="ap_approver2_title" id="ap_approver2_title" value="<?php echo $data['ap_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>OR Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="or_approver1_name" id="or_approver1_name" value="<?php echo $data['or_approver1_name'] ?>"></div>   
                <div class="span2"><b>OR Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="or_approver1_title" id="or_approver1_title" value="<?php echo $data['or_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>   
             <div class="row-form-booking">        
                <div class="span2"><b>OR Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="or_approver2_name" id="or_approver2_name" value="<?php echo $data['or_approver2_name'] ?>"></div>   
                <div class="span2"><b>OR Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="or_approver2_title" id="or_approver2_title" value="<?php echo $data['or_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>SI Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="si_approver1_name" id="si_approver1_name" value="<?php echo $data['si_approver1_name'] ?>"></div>   
                <div class="span2"><b>SI Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="si_approver1_title" id="si_approver1_title" value="<?php echo $data['si_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>SI Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="si_approver2_name" id="si_approver2_name" value="<?php echo $data['si_approver2_name'] ?>"></div>   
                <div class="span2"><b>SI Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="si_approver2_title" id="si_approver2_title" value="<?php echo $data['si_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>SI Approver3 Name</b></div>    
                <div class="span3"><input type="text" name="si_approver3_name" id="si_approver3_name" value="<?php echo $data['si_approver3_name'] ?>"></div>   
                <div class="span2"><b>SI Approver3 Title</b></div>    
                <div class="span3"><input type="text" name="si_approver3_title" id="si_approver3_title" value="<?php echo $data['si_approver3_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>SI Approver4 Name</b></div>    
                <div class="span3"><input type="text" name="si_approver4_name" id="si_approver4_name" value="<?php echo $data['si_approver4_name'] ?>"></div>   
                <div class="span2"><b>SI Approver4 Title</b></div>    
                <div class="span3"><input type="text" name="si_approver4_title" id="si_approver4_title" value="<?php echo $data['si_approver4_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>SI DRapprover1 Name</b></div>    
                <div class="span3"><input type="text" name="si_drapprover1_name" id="si_drapprover1_name" value="<?php echo $data['si_drapprover1_name'] ?>"></div>   
                <div class="span2"><b>SI DRapprover1 Title</b></div>    
                <div class="span3"><input type="text" name="si_drapprover1_title" id="si_drapprover1_title" value="<?php echo $data['si_drapprover1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>SI DRapprover2 Name</b></div>    
                <div class="span3"><input type="text" name="si_drapprover2_name" id="si_drapprover2_name" value="<?php echo $data['si_drapprover2_name'] ?>"></div>   
                <div class="span2"><b>SI DRapprover2 Title</b></div>    
                <div class="span3"><input type="text" name="si_drapprover2_title" id="si_drapprover2_title" value="<?php echo $data['si_drapprover2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>DC Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="dc_approver1_name" id="dc_approver1_name" value="<?php echo $data['dc_approver1_name'] ?>"></div>   
                <div class="span2"><b>DC Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="dc_approver1_title" id="dc_approver1_title" value="<?php echo $data['dc_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>DC Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="dc_approver2_name" id="dc_approver2_name" value="<?php echo $data['dc_approver2_name'] ?>"></div>   
                <div class="span2"><b>DC Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="dc_approver2_title" id="dc_approver2_title" value="<?php echo $data['dc_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>AO Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="ao_approver1_name" id="ao_approver1_name" value="<?php echo $data['ao_approver1_name'] ?>"></div>   
                <div class="span2"><b>AO Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="ao_approver1_title" id="ao_approver1_title" value="<?php echo $data['ao_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>AO Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="ao_approver2_name" id="ao_approver2_name" value="<?php echo $data['ao_approver2_name'] ?>"></div>   
                <div class="span2"><b>AO Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="ao_approver2_title" id="ao_approver2_title" value="<?php echo $data['ao_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>AR Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="ar_approver1_name" id="ar_approver1_name" value="<?php echo $data['ar_approver1_name'] ?>"></div>   
                <div class="span2"><b>AR Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="ar_approver1_title" id="ar_approver1_title" value="<?php echo $data['ar_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
              <div class="row-form-booking">        
                <div class="span2"><b>AR Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="ar_approver2_name" id="ar_approver2_name" value="<?php echo $data['ar_approver2_name'] ?>"></div>   
                <div class="span2"><b>AR Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="ar_approver2_title" id="ar_approver2_title" value="<?php echo $data['ar_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
                     <div class="row-form-booking">        
                <div class="span2"><b>DR Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="dr_approver1_name" id="dr_approver1_name" value="<?php echo $data['dr_approver1_name'] ?>"></div>   
                <div class="span2"><b>DR Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="dr_approver1_title" id="dr_approver1_title" value="<?php echo $data['dr_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>DR Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="dr_approver2_name" id="dr_approver2_name" value="<?php echo $data['dr_approver2_name'] ?>"></div>   
                <div class="span2"><b>DR Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="dr_approver2_title" id="dr_approver2_title" value="<?php echo $data['dr_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
        </div>
        <div id="other4">
             <div class="row-form-booking">        
                <div class="span2"><b>RS Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="rs_approver1_name" id="rs_approver1_name" value="<?php echo $data['rs_approver1_name'] ?>"></div>   
                <div class="span2"><b>RS Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="rs_approver1_title" id="rs_approver1_title" value="<?php echo $data['rs_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>RS Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="rs_approver2_name" id="rs_approver2_name" value="<?php echo $data['rs_approver2_name'] ?>"></div>   
                <div class="span2"><b>RS Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="rs_approver2_title" id="rs_approver2_title" value="<?php echo $data['rs_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>PO Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="po_approver1_name" id="po_approver1_name" value="<?php echo $data['po_approver1_name'] ?>"></div>   
                <div class="span2"><b>PO Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="po_approver1_title" id="po_approver1_title" value="<?php echo $data['po_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>PO Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="po_approver2_name" id="po_approver2_name" value="<?php echo $data['po_approver2_name'] ?>"></div>   
                <div class="span2"><b>PO Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="po_approver2_title" id="po_approver2_title" value="<?php echo $data['po_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>IR Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="ir_approver1_name" id="ir_approver1_name" value="<?php echo $data['ir_approver1_name'] ?>"></div>   
                <div class="span2"><b>IR Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="ir_approver1_title" id="ir_approver1_title" value="<?php echo $data['ir_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>IR Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="ir_approver2_name" id="ir_approver2_name" value="<?php echo $data['ir_approver2_name'] ?>"></div>   
                <div class="span2"><b>IR Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="ir_approver2_title" id="ir_approver2_title" value="<?php echo $data['ir_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>MR Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="mr_approver1_name" id="mr_approver1_name" value="<?php echo $data['mr_approver1_name'] ?>"></div>   
                <div class="span2"><b>MR Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="mr_approver1_title" id="mr_approver1_title" value="<?php echo $data['mr_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>MR Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="mr_approver2_name" id="mr_approver2_name" value="<?php echo $data['mr_approver2_name'] ?>"></div>   
                <div class="span2"><b>MR Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="mr_approver2_title" id="mr_approver2_title" value="<?php echo $data['mr_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>RI Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="ri_approver1_name" id="ri_approver1_name" value="<?php echo $data['ri_approver1_name'] ?>"></div>   
                <div class="span2"><b>RI Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="ri_approver1_title" id="ri_approver1_title" value="<?php echo $data['ri_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>RI Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="ri_approver2_name" id="ri_approver2_name" value="<?php echo $data['ri_approver2_name'] ?>"></div>   
                <div class="span2"><b>RI Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="ri_approver2_title" id="ri_approver2_title" value="<?php echo $data['ri_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>IA Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="ia_approver1_name" id="ia_approver1_name" value="<?php echo $data['ia_approver1_name'] ?>"></div>   
                <div class="span2"><b>IA Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="ia_approver1_title" id="ia_approver1_title" value="<?php echo $data['ia_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>IA Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="ia_approver2_name" id="ia_approver2_name" value="<?php echo $data['ia_approver2_name'] ?>"></div>   
                <div class="span2"><b>IA Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="ia_approver2_title" id="ia_approver2_title" value="<?php echo $data['ia_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>CCC Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="ccc_approver1_name" id="ccc_approver1_name" value="<?php echo $data['ccc_approver1_name'] ?>"></div>   
                <div class="span2"><b>CCC Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="ccc_approver1_title" id="ccc_approver1_title" value="<?php echo $data['ccc_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>CCC Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="ccc_approver2_name" id="ccc_approver2_name" value="<?php echo $data['ccc_approver2_name'] ?>"></div>   
                <div class="span2"><b>CCC Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="ccc_approver2_title" id="ccc_approver2_title" value="<?php echo $data['ccc_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>CTA Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="cta_approver1_name" id="cta_approver1_name" value="<?php echo $data['cta_approver1_name'] ?>"></div>   
                <div class="span2"><b>CTA Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="cta_approver1_title" id="cta_approver1_title" value="<?php echo $data['cta_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>CTA Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="cta_approver2_name" id="cta_approver2_name" value="<?php echo $data['cta_approver2_name'] ?>"></div>   
                <div class="span2"><b>CTA Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="cta_approver2_title" id="cta_approver2_title" value="<?php echo $data['cta_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span3"><b>CPAY Approver11 Name</b></div>    
                <div class="span2"><input type="text" name="cpay_approver11_name" id="cpay_approver11_name" value="<?php echo $data['cpay_approver11_name'] ?>"></div>   
                <div class="span2"><b>CPAY Approver11 Title</b></div>    
                <div class="span3"><input type="text" name="cpay_approver11_title" id="cpay_approver11_title" value="<?php echo $data['cpay_approver11_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span3"><b>CPAY Approver12 Name</b></div>    
                <div class="span2"><input type="text" name="cpay_approver12_name" id="cpay_approver12_name" value="<?php echo $data['cpay_approver12_name'] ?>"></div>   
                <div class="span2"><b>CPAY Approver12 Title</b></div>    
                <div class="span3"><input type="text" name="cpay_approver12_title" id="cpay_approver12_title" value="<?php echo $data['cpay_approver12_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
        </div>
        <div id="other5">
             <div class="row-form-booking">        
                <div class="span3"><b>CPAY Approver21 Name</b></div>    
                <div class="span2"><input type="text" name="cpay_approver21_name" id="cpay_approver21_name" value="<?php echo $data['cpay_approver21_name'] ?>"></div>   
                <div class="span2"><b>CPAY Approver21 Title</b></div>    
                <div class="span3"><input type="text" name="cpay_approver21_title" id="cpay_approver21_title" value="<?php echo $data['cpay_approver21_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span3"><b>CPAY Approver22 Name</b></div>    
                <div class="span2"><input type="text" name="cpay_approver22_name" id="cpay_approver22_name" value="<?php echo $data['cpay_approver22_name'] ?>"></div>   
                <div class="span2"><b>CPAY Approver22 Title</b></div>    
                <div class="span3"><input type="text" name="cpay_approver22_title" id="cpay_approver22_title" value="<?php echo $data['cpay_approver22_title'] ?>"></div>               
                <div class="clear"></div>
             </div>                       
             <div class="row-form-booking">        
                <div class="span3"><b>CPAY Approver31 Name</b></div>    
                <div class="span2"><input type="text" name="cpay_approver31_name" id="cpay_approver31_name" value="<?php echo $data['cpay_approver31_name'] ?>"></div>   
                <div class="span2"><b>CPAY Approver31 Title</b></div>    
                <div class="span3"><input type="text" name="cpay_approver31_title" id="cpay_approver31_title" value="<?php echo $data['cpay_approver31_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span3"><b>CPAY Approver32 Name</b></div>    
                <div class="span2"><input type="text" name="cpay_approver32_name" id="cpay_approver32_name" value="<?php echo $data['cpay_approver32_name'] ?>"></div>   
                <div class="span2"><b>CPAY Approver32 Title</b></div>    
                <div class="span3"><input type="text" name="cpay_approver32_title" id="cpay_approver32_title" value="<?php echo $data['cpay_approver32_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span3"><b>SA ADV Approver1 Name</b></div>    
                <div class="span2"><input type="text" name="sa_adv_approver1_name" id="sa_adv_approver1_name" value="<?php echo $data['sa_adv_approver1_name'] ?>"></div>   
                <div class="span3"><b>SA ADV Approver1 Title</b></div>    
                <div class="span2"><input type="text" name="sa_adv_approver1_title" id="sa_adv_approver1_title" value="<?php echo $data['sa_adv_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span3"><b>SA ADV Approver2 Name</b></div>    
                <div class="span2"><input type="text" name="sa_adv_approver2_name" id="sa_adv_approver2_name" value="<?php echo $data['sa_adv_approver2_name'] ?>"></div>   
                <div class="span3"><b>SA ADV Approver2 Title</b></div>    
                <div class="span2"><input type="text" name="sa_adv_approver2_title" id="sa_adv_approver2_title" value="<?php echo $data['sa_adv_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span3"><b>SA CIR Approver1 Name</b></div>    
                <div class="span2"><input type="text" name="sa_cir_approver1_name" id="sa_cir_approver1_name" value="<?php echo $data['sa_cir_approver1_name'] ?>"></div>   
                <div class="span3"><b>SA CIR Approver1 Title</b></div>    
                <div class="span2"><input type="text" name="sa_cir_approver1_title" id="sa_cir_approver1_title" value="<?php echo $data['sa_cir_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span3"><b>SA CIR Approver2 Name</b></div>    
                <div class="span2"><input type="text" name="sa_cir_approver2_name" id="sa_cir_approver2_name" value="<?php echo $data['sa_cir_approver2_name'] ?>"></div>   
                <div class="span3"><b>SA CIR Approver2 Title</b></div>    
                <div class="span2"><input type="text" name="sa_cir_approver2_title" id="sa_cir_approver2_title" value="<?php echo $data['sa_cir_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>SU Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="su_approver1_name" id="su_approver1_name" value="<?php echo $data['su_approver1_name'] ?>"></div>   
                <div class="span2"><b>SU Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="su_approver1_title" id="su_approver1_title" value="<?php echo $data['su_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>SU Approver1 Email</b></div>    
                <div class="span8"><input type="text" name="su_approver1_email" id="su_approver1_email" value="<?php echo $data['su_approver1_email'] ?>"></div>   
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>SU Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="su_approver2_name" id="su_approver2_name" value="<?php echo $data['su_approver2_name'] ?>"></div>   
                <div class="span2"><b>SU Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="su_approver2_title" id="su_approver2_title" value="<?php echo $data['su_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>SU Approver2 Email</b></div>    
                <div class="span8"><input type="text" name="su_approver2_email" id="su_approver2_email" value="<?php echo $data['su_approver2_email'] ?>"></div>   
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>SU COMPL Email</b></div>    
                <div class="span8"><input type="text" name="su_compl_approver" id="su_compl_approver" value="<?php echo $data['su_compl_approver'] ?>"></div>   
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>RFA Approver1 Name</b></div>    
                <div class="span3"><input type="text" name="rfa_approver1_name" id="rfa_approver1_name" value="<?php echo $data['rfa_approver1_name'] ?>"></div>   
                <div class="span2"><b>RFA Approver1 Title</b></div>    
                <div class="span3"><input type="text" name="rfa_approver1_title" id="rfa_approver1_title" value="<?php echo $data['rfa_approver1_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>RFA Approver2 Name</b></div>    
                <div class="span3"><input type="text" name="rfa_approver2_name" id="rfa_approver2_name" value="<?php echo $data['rfa_approver2_name'] ?>"></div>   
                <div class="span2"><b>RFA Approver2 Title</b></div>    
                <div class="span3"><input type="text" name="rfa_approver2_title" id="rfa_approver2_title" value="<?php echo $data['rfa_approver2_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>RFA Approver3 Name</b></div>    
                <div class="span3"><input type="text" name="rfa_approver3_name" id="rfa_approver3_name" value="<?php echo $data['rfa_approver3_name'] ?>"></div>   
                <div class="span2"><b>RFA Approver3 Title</b></div>    
                <div class="span3"><input type="text" name="rfa_approver3_title" id="rfa_approver3_title" value="<?php echo $data['rfa_approver3_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>RFA Approver4 Name</b></div>    
                <div class="span3"><input type="text" name="rfa_approver4_name" id="rfa_approver4_name" value="<?php echo $data['rfa_approver4_name'] ?>"></div>   
                <div class="span2"><b>RFA Approver4 Title</b></div>    
                <div class="span3"><input type="text" name="rfa_approver4_title" id="rfa_approver4_title" value="<?php echo $data['rfa_approver4_title'] ?>"></div>               
                <div class="clear"></div>
             </div>
             <div class="row-form-booking">        
                <div class="span2"><b>RFA Checked By</b></div>    
                <div class="span3"><input type="text" name="rfa_checkedby" id="rfa_checkedby" value="<?php echo $data['rfa_checkedby'] ?>"></div>   
                <div class="span2"><b>RFA Verified By</b></div>    
                <div class="span3"><input type="text" name="rfa_verifiedby" id="rfa_verifiedby" value="<?php echo $data['rfa_verifiedby'] ?>"></div>               
                <div class="clear"></div>
             </div>
        </div>
        <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Ad Parameter button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$(function() {
    $( "#tabs" ).tabs();
});
$("#edition_totalccm").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#com_code', '#com_name'];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) {
        $('#formsave').submit();         
    } else {            
        return false;
    }    
});
</script>
