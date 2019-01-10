<?php       
    foreach ($data as $data) : ?>
<tr>     
    <input type='hidden' class='hiddenassigndidd' name='hiddenassigndidd[]' value='<?php if(empty($data['didd'])) { echo "x"; } else { echo @$data['didd'];} ?>'>                        
    <input type='hidden' class='hiddenacctid' name='hiddenacctid[]' value='<?php echo $data['cafid'] ?>'>          
    <input type='hidden' class='hiddenacctbranch' name='hiddenacctbranch[]' value='<?php echo $data['branchid'] ?>'>          
    <input type='hidden' class='hiddenacctdept' name='hiddenacctdept[]' value='<?php echo $data['deptid'] ?>'>          
    <input type='hidden' class='hiddenacctemp' name='hiddenacctemp[]' value='<?php echo $data['employee'] ?>'>          
    <input type='hidden' class='hiddenacctempname' name='hiddenacctempname[]' value='<?php echo $data['employeename'] ?>'>          
    <input type='hidden' class='hiddenacctbank' name='hiddenacctbank[]' value='<?php echo @$data['bank'] ?>'>          
    <input type='hidden' class='hiddenacctcmf' name='hiddenacctcmf[]' value='<?php echo $data['customer'] ?>'>          
    <input type='hidden' class='hiddenacctdcstatus' name='hiddenacctdcstatus[]' value='<?php echo $data['dcstatus'] ?>'>          
    <input type='hidden' class='hiddenacctamt' name='hiddenacctamt[]' value='<?php echo $data['amount'] ?>'>          
    <td width="20px">
        <?php if (@$status != 'O') : ?>
        <span class="removeaccountingentry icon-remove" title="Remove Accounting Entry" data-value="<?php echo $data['id']; ?>"></span>
        <span class="editaccountingentry icon-pencil" title="Edit Accounting Entry" data-value="<?php echo $data['id']; ?>"></span>
        <?php else : ?>
       <span class="icon-lock" title="Accounting Entry Posted"></span>      
        <?php endif; ?>
    </td>       
    <td width="50px"><?php echo $data['caf_code'].' | '.$data['acct_des'] ?></td>
    <?php 
        $branch = "";
        $bank = "";
        $department = "";
        if (substr($data['caf_code'], 0, 1) == '5') {
            $department = $data['department'];
            $branch = $data['branch_code'];    
            $substring = substr($data['caf_code'], 0, 2);                
        } else if (substr($data['caf_code'], 0, 1) == '4') {            
            $branch = $data['branch_code'];   
        } else if ($data['caf_code'] == '111200') {
            $bank = $data['baf_acct'];
        } 
    ?>
    <td width="40px"><?php echo $department ?></td>                                                 
    <td width="40px"><?php echo $branch ?></td>                                                                                                         
    <td width="40px"><?php echo $bank ?></td>                                                                                                         
    <td width="40px"><?php if ($data['employee'] == '0') { echo ""; } else { echo $data['employee'].' '.@$data['employeename']; } ?></td>       
    <td width="40px"><?php if ($data['customer'] == '0') { echo ""; } else { echo $data['customer']; } ?></td>  
    
    <?php if ($data['dcstatus'] == 'D') : ?>        
    <td width="50px"><input type="text" style="text-align: right;width:100px" class="debittext" value="<?php echo number_format($data['amount'], 2, '.', ',') ?>" disabled="disabled"></td> 
    <td width="50px"><input type="text" style="text-align: right;width:100px;backgroubnd:#CCCCCC" disabled="disabled"></td>
    <?php endif; ?>
    <?php if ($data['dcstatus'] == 'C') : ?>                                               
    <td width="50px"><input type="text" style="text-align: right;width:100px;backgroubnd:#CCCCCC" disabled="disabled"></td>      
    <td width="50px"><input type="text" style="text-align: right;width:100px" class="credittext" value="<?php echo number_format($data['amount'], 2, '.', ',') ?>" disabled="disabled"></td> 
    <?php endif; ?>                                                                        
</tr>
<?php endforeach; ?>

<script>
$('.debittext, .credittext').autoNumeric({});
$('.editaccountingentry').click(function() {     
    var tempid = $(this).data('value');    
    var hkey = $("#hkey").val();
    var dcsubtype = $("#dcsubtype").val();
    
    $.ajax({
        url: "<?php echo site_url('dbmemo/editAccountingEntryView') ?>",
        type: "post",
        data: {id: tempid, hkey: hkey, dcsubtype: dcsubtype},
        success: function(response) {
            
            var $response = $.parseJSON(response);                    
            $('#editaccountingentryview').html($response['editacctentry_list']).dialog('open');        
        }    
    });
        
});
$('.removeaccountingentry').click(function() {     
    var tempid = $(this).data('value');    
    var hkey = $("#hkey").val();
    var dcsubtype = $("#dcsubtype").val();             
    var ans = confirm("Are you sure you want to delete this accounting entry?");
    if (ans) {
        $.ajax({
            url: "<?php echo site_url('dbmemo/removeAccountingEntry') ?>",
            type: "post",
            data: {id: tempid, hkey: hkey, dcsubtype: dcsubtype},
            success: function(response) {
                
                var $response = $.parseJSON(response);                    
                $("#totaldebitamount").val($response['totaldebit']);                    
                $("#totalcreditamount").val($response['totalcredit']);                    
                $(".accounting_entry_list").html($response['acctentry_list']);    

            }    
        });
    }
});
</script>