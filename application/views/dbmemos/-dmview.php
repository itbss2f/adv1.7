<div class="block-fluid table-sorting">         
<table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable2" style="white-space: nowrap;">
    <thead>
        <tr>                            
            <td style="width:10px"></td> 
            <td style="width:15px">DC Num</td> 
            <td style="width:15px">DC Date</td>
            <td style="width:15px">Client Name</td>        
            <td style="width:15px">Ad Type</td>
            <td style="width:20px">Balance</td>     
        </tr>
    </thead>
    <tbody>
    <?php foreach ($dmlist as $row) : ?>
        <tr>
            <td style="height: 10px;"><input type="checkbox" class="importdmchck" value="<?php echo $row['dc_num'] ?>"></td> 
            <td style="height: 10px;"><?php echo $row['dc_num'] ?></td> 
            <td style="height: 10px;"><?php echo $row['dc_date'] ?></td> 
            <td style="height: 10px;"><?php echo $row['dc_payee'].' '.$row['dc_payeename'] ?></td> 
            <td style="height: 10px;"><?php echo $row['adtype_name'] ?></td> 
            <td style="height: 10px;"><?php echo $row['bal'] ?></td> 
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<div class="row-form-booking">                            
    <div class="span2" align="center">
      <button class="btn btn-block" type="button" id="b_importdm" name="b_importdm">Import DM</button>
    </div>    
    <div class="clear"></div>
</div>    
<script> 
$("#b_importdm").click(function() {
    var $ids = Array();
    $('.importdmchck:checked').each(function(){$ids.push($(this).val());});
    if ($ids == null || $ids == '') {
        alert('No data been selected to be import!.');    
    } else {
        $.ajax({
            url: '<?php echo site_url('dbmemo/loadimportdm')?>',
            type: 'post',
            data: {ids: $ids},
            success:function(response) {
                var $response = $.parseJSON(response);
                                
                $('.assignment_list').append($response['assignview']);
                $('#model_importdm').dialog('close');  
            }
        });
    }
});
$("#tSortable2").dataTable();   
</script>