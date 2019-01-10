<?php  

foreach ($list as $row) : 

      ?>                                                                                        
        <tr style="height: 10px;">
            <td style="height: 10px;"><input type="radio" id="importchck" name="importchck" value="<?php echo $row['num'].'x'.$row['stype'] ?>"></td> 
            <td style="height: 10px;"><?php echo $row['num'] ?></td> 
            <td style="height: 10px;"><?php echo $row['ddate'] ?></td> 
            <td style="height: 10px;"><?php echo $row['payee'] ?></td> 
            <td style="height: 10px;"><?php echo $row['payeename'] ?></td> 
            <td style="height: 10px;"><?php echo $row['adtype_name'] ?></td> 
            <td style="height: 10px; text-align: right;"><?php echo number_format($row['unapplied'], 2, '.',',') ?></td>     
        </tr>
<?php
endforeach;  ?>

<script>
$("#bb_importorcm").one("click", function() {
    var $ids = $('#importchck:checked').val();
    
    if ($ids == null || $ids == '') {
        alert('No data been selected to be import!.');    
    } else {
        $.ajax({
            url: '<?php echo site_url('dbmemo/loadimportinvoiceORCM')?>',
            type: 'post',
            data: {ids: $ids},
            success:function(response) {
                var $response = $.parseJSON(response);
                                                 
                $('#clientcode').val($response['defaultdata']['payee']);
                $('#clientname').val($response['defaultdata']['payeename']);
                $('#dcamount').val($response['defaultdata']['unapplied']);
                $('#dcadtype').val($response['defaultdata']['adtype']);
                

                $( "#dcamount" ).trigger( "keyup" );


                $('#modal_findao').dialog('close');                                  
            }
        });
    }  
    

});
</script>