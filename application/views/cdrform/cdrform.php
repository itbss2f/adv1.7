  <form id="cdrform">          
   <table align="center"  cellspacing="0" cellpadding="0" border="1" style="width: 850px;">
        
                <tr >
                    <td style="text-align: center;" colspan="4">
                        <input type="checkbox"  name="for_discrepancy_report" id="for_discrepancy_report" checked=""> 
                        <span style="padding-top: 20px;">For Discrepancy Report</span>
                    </td>
                     <input  type="hidden" value="<?php echo $id ?>" name="ao_p_id" id="ao_p_id">
                </tr>
                <tr > 
                     <td style="text-align: center;">CDR NO.:</td>
                     <td> <input  style="width:100px; height: 30px;" readonly="readonly" type="text" value="<?php if(!empty($result->ao_cdr_num)){ echo $result->ao_cdr_num; } else { echo $cdr_num->cdr_num+1;} ?>" name="cdr_no" id="cdr_no"></td>
                     <td style="text-align: center;">CDR Date:</td>
                     <td> <input  style="width:100px; height: 30px;" type="text" name="cdrdate" id="cdrdate" value="<?php if(!empty($cdr_num->ao_cdr_date)){ $cdr_num->ao_cdr_date; } else { echo date("Y-m-d");}  ?>"></td>
                </tr>
                <tr>
                    <td style="text-align: center;">Advertiser</td>
                    <td><input type="text" readonly="readonly" value="<?php echo $result->client_name ?>" style="width:200px;height: 30px;" name="advertiser" id="advertiser"></td>
              
                    <td style="text-align: center;">Agency</td>
                    <td><input type="text"  readonly="readonly" value="<?php echo $result->agency_name ?>"  style="width:200px;height: 30px;" name="agency" id="agency"></td>
                </tr>
                <tr>
                    <td style="text-align: center;">A.E.</td>
                    <td><input type="text"  readonly="readonly" value="<?php echo $result->acct_exec ?>" style="width:200px;height: 30px;" name="ae" id="ae"></td> 
                    <td style="text-align: center;">Issue Date</td>
                    <td><input type="text" readonly="readonly" value="<?php echo $result->issue_date ?>" style="width:200px;height: 30px;" name="issue_date" id="issue_date"></td>
                </tr>
                <tr>
                    <td style="text-align: center;"> Size:</td>
                    <td><input type="text" readonly="readonly" value="<?php echo $result->size ?>" name="width" id="width" style="width:100px;height: 30px;"></td>
                    <td style="text-align: center;">Type of Ad</td>
                    <td><input type="text" readonly="readonly" value="<?php echo $result->adtype_name ?>" style="width:200px; height: 30px;" name="type_of_ad" id="type_of_ad"></td>
                </tr>
                <tr>
            
                </tr>
                <tr>
                  <td style="text-align: center;" colspan="2">Nature of Complaint:</td>
                  <td style="text-align: center;" >Type / Code:</td>
                  <td>
                      <select name="cdr_type" style="margin-top:10px;margin-left:10px;">
                            <option value=""></option>
                            <?php foreach($cdr_type as $cdr) { ?>
                                   <option <?php if($result->cdr_type == $cdr->id ) {echo "selected"; } ?> value="<?php echo $cdr->id ?>"><?php echo $cdr->cdrtype_name ?></option>              
                            <?php } ?>  
                      </select>
                </td>
                </tr>
                <tr>
                 <td colspan="4">
                    <textarea name="nature_of_complaint"  id="nature_of_complaint" style="width: 776px; height: 50px;"><?php echo $result->nature_of_complaint ?></textarea>
                 </td>
                </tr>
                 <tr>
                  <td colspan="4">Findings:</td>
                </tr>
                <tr>
                 <td colspan="4">
                    <textarea  id="findings" name="findings" style="width: 776px; height: 50px;"><?php echo $result->finding ?></textarea>
                 </td>
                </tr>
                <tr>
                  <td colspan="4">Responsible:</td>
                </tr>
                <tr>
                 <td colspan="4">
                    <textarea  id="responsible" name="responsible" style="width: 776px; height: 50px;"><?php echo $result->responsible ?></textarea>
                 </td>
                </tr>
            
        </table>
</form>
<script>

    $("#cdrdate").datepicker({dateFormat:"yy-mm-dd"});

    function submitcdrform()
    {
        $.ajax({
              url:"<?php echo site_url("cdrform/savecdr") ?>",
              type:"post",
              data:$("#cdrform").serialize(),
              success:function(response)
              {
                  alert("Success");
              }  
        });
    }
    
    function printCdrForm()
    {     
        $id =  $("#ao_p_id").val();
        myWindow = window.open('<?php echo site_url('cdrform/printCdr') ?>?ao_p_id='+$id,'','');
        myWindow.focus();  
    }

</script>