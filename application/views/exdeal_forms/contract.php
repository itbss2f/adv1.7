<style>

.ui-autocomplete {
    z-index: 9999999 !important;
}

</style>
<div class="block-fluid">   

    <form id="contract_form" enctype='multipart/form-data'  action="<?php echo site_url("exdeal_contract/".$action."/".$id) ?>" method="post" >
    
           <input type="hidden" name="contract_id" id="contract_id" value="<?php echo $id ?>">
           <input type="hidden" name="form_action" id="form_action" value="<?php echo $action ?>">

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Contract Type</b></div>    
            <div class="span2"><select name="contract_type" id="contract_type">
                                <option value=""></option>
                                <option  <?php if($action=='update' and $result->contract_type == "B" ) { echo "selected"; } ?> value="B">Billable Non-Realty</option>
                                <option  <?php if($action=='update' and $result->contract_type == "R" ) { echo "selected"; } ?> value="R">Billable Realty</option>
                                <option  <?php if($action=='update' and $result->contract_type == "N" ) { echo "selected"; } ?> value="N">Non-Billable</option>
                                <!--<option  <?php #if($action=='update' and $result->contract_type == "O" ) { echo "selected"; } ?> value="O">Non-Realty</option>-->
                               </select></div>        
            <div class="span2" style="width:120px"><b>Contract No</b></div>    
            <div class="span2"><input type="text" <?php if($action=='update') { echo "readonly"; } ?>  readonly="readonly" name="contract_no" id="contract_no" value="<?php if($action=='update') { echo $result->contract_no; } ?>"></div>        
            <div class="clear"></div>    
            </div>
            
            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Contract Date</b></div>    
            <div class="span2"><input type="text" <?php if($action=='update') { echo "readonly"; } ?>  name="contract_date" id="contract_date" value="<?php if($action=='update') { echo $result->contract_date; } else { echo date("Y-m-d"); } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Group Code</b></div>    
            <div class="span2"><select name="group_id" id="group_id">
                                <option value=""></option>
                                <?php  foreach($advertiser_group as $advertiser) : ?>
                                    <option <?php if($action=='update' and $result->advertiser_group_id == $advertiser->id ) { echo "selected"; } ?>  value="<?php echo $advertiser->id ?>"><?php echo $advertiser->group_name ?></option> 
                                <?php  endforeach; ?>
                                </select></div>        
            
            <div class="span2" style="width:120px"><b>Group Name</b></div>    
            <div class="span2"><input type="text" readonly name="advertiser" id="advertiser" value="<?php if($action=='update') { echo $result->advertiser; } ?>"></div>        
            <div class="clear"></div>    
            </div>  
            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Advertiser</b></div>    
            <div class="span2"><select name="advertiser_id" id="advertiser_id">    
                                <option value=""></option>
                                <?php if($action=='update') { ?>
                                       
                                        <?php  foreach($agency as $agency) : ?>
                                        <option <?php if($agency->id == $result->advertiser_id){ echo "selected"; } ?> value="<?php echo $agency->id ?>"><?php echo $agency->cmf_name ?></option> 
                                        <?php  endforeach; ?>
                                        
                                <?php } ?>
                               
                               </select>
            </div>
            <div class="clear"></div>    
            </div>
            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Advertising Agency</b></div>    
            <div class="span5"><input type="text" name="advertising_agency" id="tags" value="<?php if($action=='update') { echo $result->advertising_agency; } ?>"></div>        
            <div class="span5" style="position: relative; left:150px;"><div style="position: fixed;background: #A9A9A9;" id="suggestion_box"></div></div>                 
            <div class="clear"></div>          
            </div>
           
            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Amount</b></div>    
            <div class="span2"><input type="text" class="numericMask" name="amount" id="amount" value="<?php if($action=='update') { echo number_format($result->amount,2,'.',','); } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Contact Person</b></div>    
            <div class="span2"><input type="text"  name="contact_person" id="contact_person" value="<?php if($action=='update') { echo $result->contact_person; } ?>"></div>        
            <div class="span2" style="width:120px"><b>Telephone No.</b></div>    
            <div class="span2"><input type="text"  name="telephone" id="telephone" value="<?php if($action=='update') { echo $result->telephone; } ?>"></div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Cash Ratio %</b></div>    
            <div class="span2"><input type="text" name="cash_ratio" maxlength="3"  <?php if($action=='update' and $result->status == "2" ) { echo "readonly='readonly'"; } ?> id="cash_ratio" value="<?php if($action=='update') { echo $result->cash_ratio; } ?>" style="text-align: right;"></div>        
            <div class="span2" style="width:120px"><b>Barter Ratio %</b></div>    
            <div class="span2"><input type="text" name="barter_ratio" maxlength="3" id="barter_ratio" value="<?php if($action=='update') { echo $result->barter_ratio; } ?>" style="text-align: right;" readonly="readonly"></div>        
            <div class="clear"></div>    
            </div>  
            <div class="row-form-booking"> 
            <div class="span2" style="width:120px"><b>Barter Requested By</b></div>    
            <div class="span2">
                <label class="checkbox inline"><input type="radio" <?php if($action=='update' and $result->barter_request == "1" ) { echo "checked"; } ?> name="barter_request" value="1" value="1">Client</label>
                <label class="checkbox inline"><input type="radio" <?php if($action=='update' and $result->barter_request == "2" ) { echo "checked"; } ?> name="barter_request" value="2" value="1">PDI</label> 
             </div>        
            <div class="clear"></div>    
            </div>
            <div class="row-form-booking"> 
            <div class="span2" style="width:120px"><b>Upload Doc.</b></div>
            <div class="span2">  <?php if($action=='update') { echo $result->attachment_file;}  ?>        
            <input type="file" name="fileToUpload" id="fileToUpload" value="<?php if($action=='update') { echo $result->attachment_file; } ?>">  
            </div>
            <div class="clear"></div>    
            </div>
            <div class="row-form-booking">
            <div class="span3" style="width:120px"><b>Documents</b></div>    
            <div class="span5">
             
               <?php if($action=='update') { $doc_list = explode(",",$result->doc_con_id); }; ?>
             
                    <ul style="list-style: none;">     
                      <?php foreach($doc_req as $doc_req) : ?> 
                         <li><input type="checkbox" name="doc_req[]" <?php if($action=='update' and in_array($doc_req->id,$doc_list)) { echo "checked"; }?>  value="<?php echo $doc_req->doc_name ?>" > <?php echo $doc_req->doc_name ?></li> 
                      <?php endforeach; ?> 
                    </ul>
                        
             </div>        
            <div class="clear"></div>    
            </div>
            <div class="row-form-booking">    
                 <div class="span2"><b>Barter Conditions</b></div>
                 <div class="span5" style="position:relative;left:-45px;">
                   <input type="button" value="Add Row" id="add" />
                   <input type="button" value="Delete Row" id="delete" />
                   <span style="margin-left:20px">Commodities <input class="barter_chk" name="barter_chk[]" type="checkbox" value="1"/></span>
                   <span style="margin-left:20px">Gift Certificates <input class="barter_chk" name="barter_chk[]" type="checkbox" value="2"/> </span>
                   <span style="margin-left:20px">Hotels/Resorts<input class="barter_chk" name="barter_chk[]" type="checkbox" value="3"/></span>
                </div>    
                
                <table style="margin-left:180px" id="dataTable" width="500px" border="0">
   
                <?php if($action=='update') { ?>
                   
                <?php if($barter_con != null) { ?>
                
                    <?php foreach($barter_con as $barter) { ?>
                           
                         <tr>
                            <td><input type="checkbox" name="chk"/></td>
                            <td><input type="text" value="<?php echo $barter->barter_condition ?>" name="barter_condition[]" style="width:100%" name="txt"/></td>
                            <td style="text-align: center;" class="opts">
                                    <select name="condition_type[]" style="width:100px">
                                            <option value="0"> -- </option>
                                            <option <?php if($barter->condition_type == 1){ echo "selected";} ?> value="1">Commodities</option>
                                            <option <?php if($barter->condition_type == 2){ echo "selected";} ?> value="2">Gift Check</option>
                                            <option <?php if($barter->condition_type == 3){ echo "selected";} ?>  value="3">Hotel/Resort</option>
                                    </select>
                            </td>    
                           
                        </tr>  
                        
                    <?php }  ?>
                
                <?php } else { ?>
                
                     <tr>
                        <td><input type="checkbox" name="chk"/></td>
                        <td><input type="text" name="barter_condition[]" style="width:100%" name="txt"/></td>
                        <td><select name="condition_type[]" style="width:100px">
                                        <option value="0"> -- </option>
                                        <option value="1">Commodities</option>
                                        <option value="2">Gift Check</option>
                                        <option value="3">Hotel/Resort</option>
                        </select>
                        </td>
                      </tr>
                
                <?php } ?>

                
                <?php }else { ?>
                
                         <tr>
                            <td><INPUT type="checkbox" name="chk"/></td>
                            <td><INPUT type="text" name="barter_condition[]" style="width:100%" name="txt"/></td>
                            <td>
                                <select name="condition_type[]" style="width:100px">
                                                <option value="0"> -- </option>
                                                <option value="1">Commodities</option>
                                                <option value="2">Gift Check</option>
                                                <option value="3">Hotel/Resort</option>
                                 </select> 
                                 </td>
                          </tr>
                
                <?php } ?>
                
                </table>
                <div class="clear"></div>                    
            </div>  

            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Remarks</b></div>    
            <div class="span5"><textarea name="remarks" style="resize: none;" ><?php if($action=='update') { echo $result->remarks; } ?></textarea></div>        
            <div class="clear"></div>          
            </div>
            
            <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Status</b></div>    
            <div class="span5">       
                <label class="checkbox inline"><input type="radio" checked="checked" <?php if($action=='update' and $result->status == "1" ) { echo "checked"; } ?> name="status" value="1">Pending</label>
                <label class="checkbox inline"><input type="radio" <?php if($action=='update' and $result->status == "2" ) { echo "checked"; } ?> name="status" value="2">Approved</label> 
                <label class="checkbox inline"><input type="radio" <?php if($action=='update' and $result->status == "3" ) { echo "checked"; } ?> name="status" value="3">Disapproved</label> 
             </div>        
            <div class="clear"></div>    
            </div>
             <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Approver(s)</b></div>    
            <div class="span5">       
             <select id="chosen-select" name="approver[]" class="chosen-select" multiple style="width:550px;height:300px;" tabindex="4">
             <option value=""></option>
                
                    <?php foreach($approver as $approver) { ?>
                    
                        <option  <?php if($action=='update' and $result->approver_id == $approver->id ) { echo "selected"; } ?>   value="<?php echo $approver->id ?>"><?php echo $approver->company_code  ?></option> 
                    
                    <?php } ?>
                
             </select> 
            </div>        
            <div class="clear"></div>    
            </div>

            <div class="row-form-booking">
            <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">SUBMIT</button></div>        
            <div class="clear"></div>        
            </div>

    </form>
   
</div>
    
<!--   <div class="dr"><span></span></div>   -->

<div class="dialog" id="b_popup_4" style="display: none;" title="Message"></div>


<script> 
 
$('#cash_ratio').keypress(validateNumber); 
$('#barter_ratio').keypress(validateNumber); 

$('#cash_ratio').keyup(function(){
    
    
    var val = $(this).val();
    var max = 100;
    var cash = 0;
    var barter = 0;
    if (val > 100) {
         $('#cash_ratio').val(0);      
         $('#barter_ratio').val(0);  
         
         return false;    
    } 
    
    //cash = max - val;
    barter = max - val;
    
/*    console.debug(val);
    console.debug(cash); */    
    console.debug(barter);
                             
    $('#barter_ratio').val(barter); 
    
});

$(function() {
    function log( message ) {
    $( "<div>" ).text( message ).prependTo( "#log" );
    $( "#log" ).scrollTop( 0 );
    }
    
     $('#tags').autocomplete({
        
        autoFocus: true,
        
        source: function( request, response ) {
            
            $.post('<?php echo site_url('exdeal_contract/search_customer'); ?>', {
                
                search : request.term
            
                }, function(data) {

                response($.map(data,function(item) {
                    
                    return {
                        
                        label: item.label,
                    
                        value: item.value
                        
                     //   item : id
                    }
                }));
                
            }, 'json');
        },
        
        minLength: 2,
        
        select: function(event, ui) {
            
            //location.href = '<?php echo current_url(); ?>'+'/?employee='+ui.item.item.code;
        }
     });
});
       

 $( "#contract_date" ).datepicker( { dateFormat: 'yy-mm-dd' } ); 

//$("#parameter_vatinclusive, #parameter_netratereturn, #parameter_insertrate, #parameter_avedailycirc, #parameter_fixedexpenses").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#advertiser_id','#contract_type', '#contract_no','#contract_date',"#group_id","#amount"];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    } 
   
    if (countValidate == 0) {
        
        <?php if($action == 'insert') { ?>  
        
        $.ajax({
            url: "<?php echo site_url('exdeal_contract/validateContractNo') ?>",
            type: "post",
            data: {contract_no : $("#contract_no").val()},
            success: function(response) {
                if (response == "true") {                    
                       alert("Contract No. already existing");
                       $('#contract_no').val('');
                   } else {
                        
                            save(); 
                       uploadfile("<?php echo $action ?>");
                   }
            }
        }); 
       
       <?php } else { ?> 
       
                      save();     
       
       <?php } ?>
              
    } else {            
        return false;
    }    
});

function save()
{

    $.ajax({
        url:"<?php echo site_url("exdeal_contract/save") ?>",
        type:"post",
        data:$("#contract_form").serialize(),
        success: function(response)
        {
            $("#modal_newdata").dialog('close');
            $("#modal_editdata").dialog('close');
            refresh();  
            alert("Contract Save!");  
        }
    });
}

$("#group_id").die().live("change",function()
{

  $.ajax({
        url:"<?php echo site_url("exdeal_contract/getgroupdetails") ?>",
        type:"post",
        data:{id:$(this).val()},
        success: function(response)
        {
             $response =  $.parseJSON(response);
             
             $("#advertiser").val($response['group_details']['advertiser']); 
             $("#contact_person").val($response['group_details']['contact_person']); 
             $("#telephone").val($response['group_details']['telephone']); 
             $("#advertiser_id").html($response['html']); 
        }
    }); 
    
});

$("#contract_type").die().live("change",function()
{
    $let = $(this).val();
    
    if ($let == "") {      
        $("#contract_no").val('');    
        return false;
    }
    
    
    $.ajax({
        url:"<?php echo site_url("exdeal_contract/autonumber") ?>",
        type:"post",
        data:{let:$let},
        success: function(response)
        {
             $response =  $.parseJSON(response);
             
             $val = $let+"-<?php echo date('Y') ?>-"+$response['contractnum']['nextcontractnumber'];
    
             $("#contract_no").val($val);    
        }    
    });
    
    
    
});

$("#amount").autoNumeric();
//$("#barter_ratio").autoNumeric();
//$("#cash_ratio").autoNumeric();

function uploadfile(action)
{    
    //can perform client side field required checking for "fileToUpload" field
    $.ajaxFileUpload
    ({
        url:'<?php echo site_url("exdeal_contract/upload_file"); ?>',
        fileElementId:'fileToUpload',
        dataType: 'json',
        type: 'post',
        data:{id:'<?php echo $id ?>',action:action},  
        success: function ()
        {
            alert("success");
        }
        });
         
    return false;
}


    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }  
    
        
  function validateNumber(event) {

    var key = window.event ? event.keyCode : event.which;

    if (event.keyCode == 8 || event.keyCode == 46
     || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9 ) {
        return true;
    }
    else if ( key < 48 || key > 57 ) {
        return false;
    }
    else return true;
};

    
  </script>

  