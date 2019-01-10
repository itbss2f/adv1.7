<div class="block-fluid table-sorting">         
<table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable" style="white-space: nowrap;">
    <thead>
        <tr>                            
            <td style="width:10px"></td> 
            <td style="width:15px">Product</td> 
            <td style="width:15px">AO No.</td> 
            <td style="width:15px">Issue Date</td>
            <td style="width:15px">Invoice No.</td>
            <td style="width:20px">Client Name</td>
            <td style="width:20px">Agency Name</td>
            <td style="width:15px">Ad Type</td>
            <td style="width:20px">Balance</td>     
        </tr>
    </thead>
    <tbody style="max-height: 400px;">
        <?php 
        foreach ($available_invoice as $x => $rowdata) : 
              foreach ($rowdata as $row) : ?>                                                                                        
        <tr style="height: 10px;">
            <td style="height: 10px;"><input type="checkbox" class="importchck" value="<?php echo $row['id'] ?>"></td> 
            <td style="height: 10px;"><?php echo $row['prod_code'] ?></td> 
            <td style="height: 10px;"><?php echo $row['ao_num'] ?></td> 
            <td style="height: 10px;"><?php echo $row['ao_issuefrom'] ?></td>
            <td style="height: 10px;"><?php echo $row['ao_sinum'] ?></td>
            <td style="height: 10px;"><?php echo $row['ao_cmf'].' '.$row['ao_payee'] ?></td>
            <td style="height: 10px;"><?php echo $row['agencycode'].' '.$row['agencyname'] ?></td>
            <td style="height: 10px;"><?php echo $row['adtype_name'] ?></td>
            <td style="height: 10px;"><?php echo number_format($row['bal'], 2, '.',',') ?></td>     
        </tr>
        <?php endforeach;
        endforeach; ?>
    </tbody>
</table>
</div>
<div class="row-form-booking">                            
    <div class="span2" align="center">
      <button class="btn btn-block" type="button" id="b_importinvoice" name="b_importinvoice">Import Invoice</button>
    </div>    
    <div class="clear"></div>
</div>
<script>
$("#b_importinvoice").click(function() {
    var $ids = Array();
    $('.importchck:checked').each(function(){$ids.push($(this).val());});
    if ($ids == null || $ids == '') {
        alert('No data been selected to be import!.');    
    } else {
        $.ajax({
            url: '<?php echo site_url('dbmemo/loadimportinvoice')?>',
            type: 'post',
            data: {ids: $ids},
            success:function(response) {
                var $response = $.parseJSON(response);
                                
                $('.assignment_list').append($response['assignview']);
                $('#model_importinvoice').dialog('close');                                  
            }
        });
    }   
});
$("#tSortable").dataTable();
</script>