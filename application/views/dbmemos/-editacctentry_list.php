<div class="row-form-booking">                   
    <div class="span1">Account</div>                 
    <div class="span2">
        <select name="acct2" id="acct2" style="width: 330px;">      
        <option value="">--</option>   
        <?php foreach ($acct as $acct) : ?>
        <?php if ($acct['id'] == $data['cafid'] ) :?>
        <option value="<?php echo $acct['id'] ?>" selected="selected"><?php echo $acct['caf_code'].' | '.$acct['acct_des'] ?></option>
        <?php else: ?>
        <option value="<?php echo $acct['id'] ?>"><?php echo $acct['caf_code'].' | '.$acct['acct_des'] ?></option>
        <?php endif; ?>
        <?php endforeach; ?>
        </select>
    </div>    
    <div class="clear"></div>    
</div>    

<div class="row-form-booking hid" id="v_bank2" <?php if ($BN == "N") { echo ' style="display:none" ';} ?>>                       
    <div class="span1">Bank</div>           
    <div class="span2">
        <select name="bank2" id="bank2">
        <option value="">--</option>   
        <?php foreach ($bank as $bank) : ?>
        <?php if ($bank['id'] == $data['bank'] ) : ?>
        <option value="<?php echo $bank['id'] ?>" selected="selected"><?php echo $bank['baf_acct'].' - '.$bank['bmf_name'] ?></option>
        <?php else: ?>
        <option value="<?php echo $bank['id'] ?>"><?php echo $bank['baf_acct'].' - '.$bank['bmf_name'] ?></option>
        <?php endif; ?>
        <?php endforeach; ?>
        </select>
    </div>     
    <div class="clear"></div>        
</div> 
 
<div class="row-form-booking hid" id="v_department2" <?php if ($D == "N") { echo ' style="display:none" ';} ?>>                       
    <div class="span1">Department</div>           
    <div class="span2">
        <select name="department2" id="department2">
        <option value="">--</option>   
        <?php foreach ($dept as $dept) : ?>
        <?php if ($dept['id'] == $data['department'] ) : ?>
        <option value="<?php echo $dept['id'] ?>" selected="selected"><?php echo $dept['dept_code'].' | '.$dept['dept_name'] ?></option>
        <?php else: ?>
        <option value="<?php echo $dept['id'] ?>"><?php echo $dept['dept_code'].' | '.$dept['dept_name'] ?></option>
        <?php endif; ?>
        <?php endforeach; ?>
        </select>
    </div>     
    <div class="clear"></div>        
</div>     

<div class="row-form-booking hid" id="v_branch2" <?php if ($B == "N") { echo ' style="display:none" ';} ?>>                          
    <div class="span1">Branch</div>               
    <div class="span2">
        <select name="branch2" id="branch2">
        <option value="">--</option>   
        <?php foreach ($brnch as $brnch) : ?>
        <?php if ($brnch['id'] == $data['branch'] ) : ?>
        <option value="<?php echo $brnch['id'] ?>" selected="selected"><?php echo $brnch['branch_code'].' | '.$brnch['branch_name'] ?></option>
        <?php else: ?>
        <option value="<?php echo $brnch['id'] ?>"><?php echo $brnch['branch_code'].' | '.$brnch['branch_name'] ?></option>
        <?php endif; ?>
        <?php endforeach; ?>
        </select>
    </div>   
    <div class="clear"></div>    
</div>      
<div class="row-form-booking hid" id="v_employee2" <?php if ($E == "N") { echo ' style="display:none" ';} ?>>                          
    <div class="span1">Employee#</div>         
    <div class="span2"><input type="text" name="emp2" id="emp2" value="<?php echo $data['employee'] ?>" readonly="readonly"> [0011141100]</div>                   
    <div class="clear"></div>        
</div>      
<div class="row-form-booking">     
    <div class="span1">Customer</div>                 
    <div class="span2"><input type="text" name="customer2" id="customer2" value="<?php if ($data['customer'] == 0) { echo "";} else {echo $data['customer'];}  ?>"></div> 
    <div class="clear"></div>                               
</div>      
<?php 
$debit = 0;
$credit = 0;
if ($data['dcstatus'] == 'D') {
    $debit = number_format($data['amount'], 2, '.', ',');           
    $credit = '0.00';
} else if ($data['dcstatus'] == 'C') {
    $credit = number_format($data['amount'], 2, '.', ',');           
    $debit = '0.00';
}
?>
<div class="row-form-booking hidd" id="v_debit">   
    <div class="span1">Debit</div>                
    <div class="span2" style="width: 100px;"><input type="text" style="text-align: right;" value="<?php echo $debit ?>" <?php if ($data['dcstatus'] == 'C') { echo "readonly='readonly'"; }?> name="mandebit2" id="mandebit2"></div>            
    <div class="clear"></div>     
</div>      
<div class="row-form-booking hidd" id="v_credit">   
    <div class="span1"></div>   
    <div class="span1">Credit</div>                 
    <div class="span2" style="width: 100px;"><input type="text" style="text-align: right;" value="<?php echo $credit ?>" <?php if ($data['dcstatus'] == 'D') { echo "readonly='readonly'"; }?> name="mancredit2" id="mancredit2"></div> 
    <div class="clear"></div>                               
</div>
<input type="hidden" name="dcstatus2" id="dcstatus2">
<div class="row-form-booking">                            
    <div class="span2" style="width: 140px;" align="center">
      <button class="btn btn-block" type="button" id="eb_addacctentry" name="eb_addacctentry">Save Acct Entry</button>
    </div>   
    <div class="span2" style="width: 140px;" align="center">
      <button class="btn btn-block" type="button" id="eb_closeentry" name="eb_closeentry">Close</button>
    </div>    
    <div class="clear"></div>
</div>

<script>


$("#acct2").select2();   
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#eb_addacctentry").click(function() {
    var validate_fields = ['#acct'];
    var countValidate = 0;          
    
    for (x = 0; x < validate_fields.length; x++) { 
        $(validate_fields[x]).css(errorcssobj2);                  
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    } 
    
    if (countValidate == 0) {     
        var $acct = $("#acct2").val();
        var $department = $("#department2").val();
        var $branch = $("#branch2").val();
        var $emp = $("#emp2").val();
        var $bank = $("#bank2").val();
        var $customer = $("#customer2").val();
        var $mandebit = $("#mandebit2").val();
        var $mancredit = $("#mancredit2").val();        
        var $dcsubtype = $("#dcsubtype").val();
        var $hkey = $("#hkey").val();  
        var $id = "<?php echo $data['id'] ?>";
        $.ajax({
            url: "<?php echo site_url('dbmemo/saveEditAcountingEntry') ?>",
            type: "post",
            data: {id: $id, acct: $acct, department: $department, branch: $branch, emp: $emp,
                   customer: $customer, mandebit: $mandebit, mancredit: $mancredit, hkey: $hkey, dcsubtype: $dcsubtype, bank: $bank},
            success: function(response) {
                $response = $.parseJSON(response);
                var $response = $.parseJSON(response);                    
                $("#totaldebitamount").val($response['totaldebit']);                    
                $("#totalcreditamount").val($response['totalcredit']);                    
                $(".accounting_entry_list").html($response['acctentry_list']);    
                $("#editaccountingentryview").dialog('close');   
            }    
        });   
    }
});
$("#eb_closeentry").click(function() {
    $("#editaccountingentryview").dialog('close');    
});
$("#department").change(function() {
    var $department = $("#department2").val();
    
    $.ajax({
        url: "<?php echo site_url('dbmemo/ajxDeptStatBranch') ?>",
        type: "post",
        data: {department: $department},
        success: function(response) {
            $response = $.parseJSON(response);
            $("#v_branch2").hide();
            
            if ($response["B"] == "Y") {
                $("#v_branch2").show();    
            } 
        }
    });   
});
$("#acct2").change(function(){
    var $acct = $("#acct2").val();
    $.ajax({
        url: "<?php echo site_url('dbmemo/ajxAccountValidation') ?>",
        type: "post",
        data: {acct: $acct},
        success: function(response) {
            $response = $.parseJSON(response);
            $(".hid").hide();
            
            if ($response["D"] == "Y") {
                $("#v_department2").show();  
            } else if ($response["B"] == "Y") {
                $("#v_branch2").show();
            } else if ($response["E"] == "Y") {
                $("#v_employee2").show();    
            } else if ($acct == '20') {
                $('#v_bank2').show();
            }
            
        }
    });    
}); 
$("#mandebit").keyup(function(){
    var mandebit = $("#mandebit").val();          
    
    var x = mandebit.replace(",","");    
    if (parseFloat(x) > 0) {        
        $("#mancredit").val("0.00").attr('disabled', true).removeData('autoNumeric');
    } else {
        $("#mancredit").val("0.00").removeAttr('disabled').autoNumeric();
    }
});

$("#mancredit").keyup(function(){
    var mancredit = $("#mancredit").val();          

    var x = mancredit.replace(",","");    
    if (parseFloat(x) > 0) {        
        $("#mandebit").val("0.00").attr('disabled', true).removeData('autoNumeric');
    } else {
        $("#mandebit").val("0.00").removeAttr('disabled').autoNumeric();
    }
});
$("#mancredit, #mandebit").autoNumeric();     
</script>                    
