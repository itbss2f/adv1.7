<div class="row-form-booking">                   
    <div class="span2"><b>OR / CM Number</b></div>                 
    <div class="span1"><input type='text' name='s_inv' id='s_inv'></div>    
    <div class="span2"><input type='radio' name='s_type' id='s_type' value="1" checked="checked"> OR <input type='radio' name='s_type' id='s_type' value="2"> CM</div>    
    <div class="span1"><input type='button' value='Search' name='b_invs' id='b_invs'></div>    
    <div class="clear"></div>    
</div>  
<div class="block-fluid table-sorting">         
<table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable" style="white-space: nowrap;">
    <thead>
        <tr>                            
            <td style="width:10px"></td> 
            <td style="width:15px">OR/CM Number</td> 
            <td style="width:15px">OR/CM Date</td>
            <td style="width:15px">Payee Code</td>
            <td style="width:20px">Payee Name</td>
            <td style="width:10px">Adtype</td>
            <td style="width:20px">Unapplied Amount</td>     
        </tr>
    </thead>
    <tbody style="max-height: 400px;" class="resultlist">
        
    </tbody>
</table>
</div>
<div class="row-form-booking">                            
    <div class="span2" align="center">
      <button class="btn btn-block" type="button" id="bb_importorcm" name="bb_importorcm">Import OR / CM</button>
    </div>    
    <div class="clear"></div>
</div>
<script>
$("#b_invs").click(function() {
    var $orcm = $('#s_inv').val(); 
    var $type = $('#s_type:checked').val(); 
    if ($orcm == null || $orcm == '') {
        alert('OR / CM Number required!.');    
        return false;
    }
    
    $.ajax({
        url: '<?php echo site_url('dbmemo/searchorcmlist')?>',    
        type: 'post',
        data: {orcm: $orcm, type: $type},
        success: function(response) {
            var $response = $.parseJSON(response);
            
            $('.resultlist').html($response['result']);    
        }
    })
});

$("#tSortable").dataTable();
</script>