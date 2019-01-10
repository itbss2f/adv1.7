<style>

.ui-autocomplete {
    z-index: 10000 !important;
}

</style>

<div class="row-form-booking">                   
    <div class="span1">Account</div>                 
    <div class="span2">
        <select name="acct" id="acct" style="width: 330px;">
        <option value="">--</option>   
        <?php foreach ($acct as $acct) : ?>
        <option value="<?php echo $acct['id'] ?>"><?php echo $acct['caf_code'].' | '.$acct['acct_des'] ?></option>
        <?php endforeach; ?>
        </select>
    </div>    
    <div class="clear"></div>    
</div>      
<div class="row-form-booking hid" id="v_department" style="display:none">                       
    <div class="span1">Department</div>           
    <div class="span2">
        <select name="department" id="department">
        <option value="">--</option>   
        <?php foreach ($dept as $dept) : ?>
        <option value="<?php echo $dept['id'] ?>"><?php echo $dept['dept_code'].' | '.$dept['dept_name'] ?></option>
        <?php endforeach; ?>
        </select>
    </div>     
    <div class="clear"></div>        
</div>      
<div class="row-form-booking hid" id="v_branch" style="display:none">                          
    <div class="span1">Branch</div>               
    <div class="span2">
        <select name="branch" id="branch">
        <option value="">--</option>   
        <?php foreach ($brnch as $brnch) : ?>
        <option value="<?php echo $brnch['id'] ?>"><?php echo $brnch['branch_code'].' | '.$brnch['branch_name'] ?></option>
        <?php endforeach; ?>
        </select>
    </div>   
    <div class="clear"></div>    
</div>     
<div class="row-form-booking hid" id="v_bank" style="display:none">                          
    <div class="span1">Bank</div>         
    <div class="span2">
        <select name="bank" id="bank">
            <option value="0">--</option>
            <?php foreach ($bank as $bank) : ?>
            <option value="<?php echo $bank['id'] ?>"><?php echo $bank['baf_acct'].' - '.$bank['bmf_name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>            
    <div class="clear"></div>        
</div>  
<div class="row-form-booking hid" id="v_employee" style="display:none">                          
    <div class="span1">Employee#</div>         
    <div class="span1" style="width: 90px;"><input type="text" name="emp" id="emp" placeholder="#########"></div>            
    <div class="span1" style="width: 90px; display: none;"><input type="text" name="empname" id="empname" placeholder="#########"></div>            
    <div class="span2"><input type="text" name="searchxx" id="searchxx" placeholder="Search Employee"></div>  
    <div class="span1"></div>                   
    <div class="span1"><a href="#" style="color: blue;">[0011141100]</a></span></div>            
    <div class="clear"></div>        
</div>      
<div class="row-form-booking">     
    <div class="span1">Customer</div>                 
    <div class="span2"><input type="text" name="customer" id="customer"></div> 
    <div class="clear"></div>                               
</div>      
<div class="row-form-booking hidd" id="v_debit">   
    <div class="span1">Debit</div>                
    <div class="span2" style="width: 100px;"><input type="text" style="text-align: right;" value="0.00" name="mandebit" id="mandebit"></div>            
    <div class="clear"></div>     
</div>      
<div class="row-form-booking hidd" id="v_credit">   
    <div class="span1"></div> 
    <div class="span1">Credit</div>                 
    <div class="span2" style="width: 100px;"><input type="text" style="text-align: right;" value="0.00" name="mancredit" id="mancredit"></div> 
    <div class="clear"></div>                               
</div>
<input type="hidden" name="dcstatus" id="dcstatus">
<div class="row-form-booking">                            
    <div class="span2" style="width: 140px;" align="center">
      <button class="btn btn-block" type="button" id="b_addacctentry" name="b_addacctentry">Add Acct Entry</button>
    </div>   
    <div class="span2" style="width: 140px;" align="center">
      <button class="btn btn-block" type="button" id="b_closeentry" name="b_closeentry">Close</button>
    </div>    
    <div class="clear"></div>
</div>

<script>
$(document).ready(function() {    
$( "#searchxx" ).autocomplete({            
    source: function( request, response ) {
        $.ajax({
            //url: 'http://128.1.200.249/hris/public/api/json/employees',
            url: 'http://erm.inquirer.com.ph/api/json/employees/',
            dataType: "jsonp",
            data: {   search: request.term
                   },
            success: function(data) {
                
                response($.map(data.employees, function(item) {
                     return {
                            label: item.code + " " + item.first_name + " " + item.middle_name + " " + item.last_name,
                            //value: item.code,                                    
                            item: item                                     
                     }
                }))
            }
        });                
    },
    autoFocus: false,
    minLength: 2,
    delay: 300,
    select: function(event, ui) {
        $(':input[name=emp]').val(ui.item.item.code);                
        $(':input[name=empname]').val(ui.item.item.first_name + " " + ui.item.item.last_name);                
    }
});

$("#emp").mask('999999999');
$("#acct").select2();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#b_addacctentry").click(function() {
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
        var $acct = $("#acct").val();
        var $department = $("#department").val();
        var $branch = $("#branch").val();
        var $emp = $("#emp").val();
        var $empname = $("#empname").val();
        var $customer = $("#customer").val();
        var $mandebit = $("#mandebit").val();
        var $mancredit = $("#mancredit").val();
        var $dcsubtype = $("#dcsubtype").val();
        var $bank = $("#bank").val();
        var $hkey = $("#hkey").val();  
        $.ajax({
            url: "<?php echo site_url('dbmemo/saveManualAcountingEntry') ?>",
            type: "post",
            data: {acct: $acct, department: $department, branch: $branch, emp: $emp, empname: $empname,
                   customer: $customer, mandebit: $mandebit, mancredit: $mancredit, hkey: $hkey, dcsubtype: $dcsubtype, bank: $bank},
            success: function(response) {
                $response = $.parseJSON(response);
                var $response = $.parseJSON(response);                    
                $("#totaldebitamount").val($response['totaldebit']);                    
                $("#totalcreditamount").val($response['totalcredit']);                    
                $(".accounting_entry_list").html($response['acctentry_list']);    
                $("#manualaccountingentry").dialog('close');   
            }    
        });    
    }
});
$("#b_closeentry").click(function() {
    $("#manualaccountingentry").dialog('close');    
});
$("#department").change(function() {
    var $department = $("#department").val();
    
    $.ajax({
        url: "<?php echo site_url('dbmemo/ajxDeptStatBranch') ?>",
        type: "post",
        data: {department: $department},
        success: function(response) {
            $response = $.parseJSON(response);
            $("#v_branch").hide();
            
            if ($response["B"] == "Y") {
                $("#v_branch").show();    
            } 
        }
    });   
});
$("#acct").change(function(){
    var $acct = $("#acct").val();
    
    //alert('asd');
    
    //return false;

    $.ajax({
        url: "<?php echo site_url('dbmemo/ajxAccountValidation') ?>",
        type: "post",
        data: {acct: $acct},
        success: function(response) {
            $response = $.parseJSON(response);
            $(".hid").hide();
            
            if ($response["D"] == "Y") {
                $("#v_department").show();    
            } else if ($response["B"] == "Y") {
                $("#v_branch").show();
            } else if ($response["E"] == "Y") {
                $("#v_employee").show();    
            } else if ($acct == '20') {
                $('#v_bank').show();
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

});  
</script>                    
