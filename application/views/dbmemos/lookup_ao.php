<div class="row-form-booking">                   
    <div class="span1"><b>AO Number</b></div>                 
    <div class="span2"><input type='text' name='s_inv' id='s_inv'></div>    
    <div class="span1"><input type='button' value='Search' name='b_invs' id='b_invs'></div>    
    <div class="clear"></div>    
</div>  
<div class="block-fluid table-sorting">         
<table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable" style="white-space: nowrap;">
    <thead>
        <tr>                            
            <td style="width:10px"></td> 
            <td style="width:15px">Product</td> 
            <td style="width:15px">Issue Date</td>
            <td style="width:15px">AO No.</td>
            <td style="width:20px">Client Name</td>
            <td style="width:20px">Agency Name</td>
            <td style="width:15px">Ad Type</td>
            <td style="width:20px">Balance</td>     
        </tr>
    </thead>
    <tbody style="max-height: 400px;" class="resultlist">
        
    </tbody>
</table>
</div>
<div class="row-form-booking">                            
    <div class="span2" align="center">
      <button class="btn btn-block" type="button" id="bb_importinvoice" name="bb_importinvoice">Import AO</button>
    </div>    
    <div class="clear"></div>
</div>
<script>
$("#b_invs").click(function() {
    var $ao = $('#s_inv').val(); 
    if ($ao == null || $ao == '') {
        alert('AO Number required!.');    
        return false;
    }
    
    $.ajax({
        url: '<?php echo site_url('dbmemo/searchaolist')?>',    
        type: 'post',
        data: {ao: $ao},
        success: function(response) {
            var $response = $.parseJSON(response);
            
            $('.resultlist').html($response['result']);    
        }
    })
});

$("#tSortable").dataTable();
</script>