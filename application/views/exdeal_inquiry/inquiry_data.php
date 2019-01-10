 <form id="inquiry_update_form"  action="<?php echo site_url('exdeal_inquiry/update_inquiry') ?>" method="POST">             
<table style="width: 100%;">
    
<thead>
   <tr>
        <th>Invoice No.</th>
        <th>Issue Date</th>
        <th>Item No.</th>
        <th>Amount</th>
        <th>Status</th>
        <th>%</th>
        <th>Exdeal Amount</th>
        <th>Cash</th>
        <th>Contract No.</th>  
        <th>Remarks</th>
        <th>Action</th>
   </tr>
</thead>
    
    <tbody>
    
    <?php $total_exdeal_amount = 0 ?>
    <?php $total_percent = 0 ?>
    <?php $total_amount = 0 ?>
   
      <?php foreach($result as $result) : ?>
         <tr class="client_name" val="<?php  echo $result->cmf_name ?>"> 
           <input type="hidden" name="ao_id[]" class="ao_id" value="<?php  echo $result->id.":".$result->ao_ref ?>">
            <td class="upd_td"><?php echo $result->ao_sinum ?></td>
            <td class="upd_td"><?php echo $result->ao_issuefrom ?></td>
            <td class="upd_td"><?php echo $result->item_id ?></td>
            <td style="text-align: right;"  class='inq_amount_due upd_td'><?php echo number_format($result->amount,2,'.',',') ?></td>
            <td class="upd_td" style="text-align: center"><input type="checkbox" name="exdeal_status[]" class="exdeal_status" <?php if( $result->exdeal_status == '1' ) { echo "checked";}?> value="1" ></td>
            <td class="upd_td"><input type="text" style="height:40px" name="exdeal_percent[]" amount_due='<?php echo $result->amount; ?>' ao_id="<?php echo $result->id ?>" class="exdeal_percent" style="width:70px;text-align:right" value="<?php echo number_format($result->ao_exdealpercent,0,'','') ?>" ></td>
            <td class="upd_td"><input type="text" style="width: 70px;height:40px;text-align:right;" readonly="" name="exdeal_amount[]" id="ex_amount<?php echo $result->id ?>" class="exdeal_amount" value="<?php echo number_format($result->ao_exdealamt,2,'.',',') ?>"></td>
             <td class="upd_td"><input type="text" style="width: 70px;height:40px;text-align:right;" readonly="" name="exdeal_cash[]" id="ex_cash<?php echo $result->id ?>" class="exdeal_cash" value="<?php echo number_format($result->ao_exdealcash,2,'.',',') ?>"></td>
            <td class="upd_td"><input type="text" style="width: 80px;height:40px;text-align:right;" id="" name="contract_no[]" class="contract_no" value="<?php echo $result->ao_exdealcontractno ?>"></td>
            <td class="upd_td"><input type="text" style="width: 150px;height:40px;text-align:right;" name="exdeal_remarks[]" class="exdeal_remarks" value="<?php echo $result->ao_exdealpart ?>"></td>
            <td class="upd_td"><input type="button" class="update_btn btn" value="Update"></td>
         </tr>
         <?php $total_amount += $result->amount ?>  
         <?php $total_exdeal_amount += $result->ao_exdealamt ?>  
         <?php $total_percent += $result->ao_exdealpercent ?>  
         
      <?php endforeach; ?>
      
      <tr>
            <td></td>
            <td></td>
            <td><b>TOTAL</b></td>
            <td style="text-align: right;"><?php echo number_format($total_amount,2,'.',',') ?></td> 
            <td></td>            
            <td style="text-align: right;" id="to_percent"><?php echo number_format($total_percent,0,'','') ?></td> 
            <td style="text-align: right;" id="to_exdeal_amount"><?php echo number_format($total_exdeal_amount,2,'.',',') ?></td>
            <td></td>
      
      </tr>
      <tr>
           
      </tr>
 
   </tbody>  
   
</table>

</form> 
<script>

  $(".update_btn").die().live("click",function()
  {
      
    
        xhr && xhr.abort(); 
       var str =  $(this).parents('.client_name').children(".ao_id").val(); 
       $ao_id = str.split(":"); 
       $exdeal_status = $(this).closest('tr').find(".exdeal_status:checked").val();
       $exdeal_percent = $(this).closest('tr').find(".exdeal_percent").val();
       $exdeal_cash = $(this).closest('.client_name').find(".exdeal_cash").val();
       $exdeal_amount = $(this).closest('.client_name').find(".exdeal_amount").val();
       $contract_no =  $(this).closest('.client_name').find(".contract_no").val();
       $exdeal_remarks = $(this).closest('.client_name').find(".exdeal_remarks").val();
       
            
        xhr = $.ajax({
              url:"<?php echo site_url('exdeal_inquiry/update_inquiry'); ?>",
              type:"POST",
              cache:false,
              data:{ao_id:$ao_id[0],ao_ref:$ao_id[1],exdeal_status:$exdeal_status,exdeal_cash:$exdeal_cash,
                    exdeal_percent:$exdeal_percent,exdeal_amount:$exdeal_amount,
                    contract_no:$contract_no,exdeal_remarks:$exdeal_remarks},
              success : function(response)
              {       
                     // $("#b_popup_4").dialog("close");
                      alert("Updated");
                      generatewithfilters();  
              }
       });              
       
  });

 $( ".exdeal_date" ).datepicker( { dateFormat: 'yy-mm-dd' } );  
 $('.exdeal_percent').keypress(validateNumber);
 
// $(".exdeal_percent").autoNumeric(); 
 $(".exdeal_amount").autoNumeric(); 

 $(".exdeal_percent").die().live("keyup",function(){ 
        
        $ao_id = $(this).attr('ao_id');
        $amount_due = $(this).attr('amount_due');
        $value = $(this).val();
        
        $("#ex_amount"+$ao_id).val((($value/100) * $amount_due).toFixed(2));
        $("#ex_cash"+$ao_id).val($amount_due - ((($value/100) * $amount_due).toFixed(2)) );
      
        
    //    alert();
           
     
 });
 
 function validateNumber(event) {
    var key = window.event ? event.keyCode : event.which;

    if (event.keyCode == 8 || event.keyCode == 46
     || event.keyCode == 37 || event.keyCode == 39) {
        return true;
    }
    else if ( key < 48 || key > 57 ) {
        return false;
    }
    else return true;
};

$('.contract_no').die().live("focus",function(){
          
   $(this).autocomplete({
        
        autoFocus: true,
        
        source: function( request, response ) {
            
            $.post('<?php echo site_url('exdeal_contract/search_contract'); ?>', {
                
                search : request.term,
                
                advertiser : $('.client_name').attr('val')
            
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


</script>