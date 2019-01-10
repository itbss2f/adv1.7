<?php  
$clientcode = '';
$clientname = '';
foreach ($invoice as $x => $rowdata) : 
      foreach ($rowdata as $row) : 
      $clientcode = $row['ao_cmf'];
      $clientname = $row['ao_payee'];
      ?>                                                                                        
        <tr style="height: 10px;">
            <td style="height: 10px;"><input type="radio" id="importchck" name="importchck" value="<?php echo $row['id'] ?>"></td> 
            <td style="height: 10px;"><?php echo $row['prod_code'] ?></td> 
            <td style="height: 10px;"><?php echo $row['ao_issuefrom'] ?></td>
            <td style="height: 10px;"><?php echo $row['ao_sinum'] ?></td>
            <td style="height: 10px;"><?php echo $row['ao_cmf'].' '.$row['ao_payee'] ?></td>
            <td style="height: 10px;"><?php echo $row['agencycode'].' '.$row['agencyname'] ?></td>
            <td style="height: 10px;"><?php echo $row['adtype_name'] ?></td>
            <td style="height: 10px;"><?php echo number_format($row['bal'], 2, '.',',') ?></td>     
        </tr>
<?php endforeach;
endforeach;  ?>

<script>
$("#bb_importinvoice").one("click", function() {
    var $ids = Array();
    
    var $ccode = "";
    $('#importchck:checked').each(function(){$ids.push($(this).val());});
    if ($ids == null || $ids == '') {
        alert('No data been selected to be import!.');    
    } else {
        $.ajax({
            url: '<?php echo site_url('dbmemo/loadimportinvoiceINV')?>',
            type: 'post',
            data: {ids: $ids},
            success:function(response) {

                var $response = $.parseJSON(response);
                $('#clientcode').val("<?php echo @$clientcode ?>").attr('readonly', 'readonly');                
                $('#clientname').val("<?php echo @$clientname ?>").attr('readonly', 'readonly');                
                $('.assignment_list').append($response['assignview']);
                //$ccode = "<?php echo @$clientcode ?>";
                $ccode = $response['customercode'];
                
                $.ajax({
                    url: "<?php echo site_url('dbmemo/ajaxAgency') ?>",
                    type: 'post',
                    data: {cust_id: $ccode},
                    success: function(response)
                    {
                        var $xponse = $.parseJSON(response);
                        $('#agency').empty();
                        $('#agency').append($('<option>').val('').text('--'));
                        $.each($xponse['agency'], function(i)
                        {
                            var xitem = $xponse['agency'][i];
                            var option = $('<option>').val(xitem['id']).text(xitem['cmf_code'] + ' - ' +xitem['cmf_name']);
                            $('#agency').append(option);                            
                        });     
                    }
                });                                 
               
                $('#modal_findinvoice').dialog('close');                                  
            }
        });
    }  
    

});
</script>