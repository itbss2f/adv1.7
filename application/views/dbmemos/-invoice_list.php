<?php 
$count = 1;
foreach ($invoice_list as $row) : 
?>
<tr>                        
    <td><input type="checkbox" class="check_singleinvoice" id="<?php echo $row['id'] ?>"></td>
    <td><?php echo $row['ao_issuefrom'] ?></td>
    <td style="text-align:right"><?php echo number_format($row['bal'], 2, '.',',') ?></td>                         
    
</tr>
<?php 
$count += 1;
endforeach;
?>

<script>
/*$(".sapplied").keyup(function() {
    var $amt = $(this).val();
    var $inputid = $(this).attr('id');
    var n=$inputid.replace("sapplied",""); 
    sapplied_recomputePercent($amt, n);
});
function sapplied_recomputePercent($amt, $inputid)
{
    var $amountpaid = $amt;
    var $vatcode = "<?php echo $vatcode ?>";
    var $wvatpercent = "<?php echo $wvat ?>";
    var $wtaxpercent = "<?php echo $wtax ?>";
    var $ppdpercent = "<?php echo $ppd ?>";
    $.ajax({
        url: "<?php echo site_url('payment/getRecomputeValuePercent') ?>",
        type: "post",
        data: {amountpaid: $amountpaid, vatcode: $vatcode, wvatpercent: $wvatpercent, wtaxpercent: $wtaxpercent, ppdpercent: $ppdpercent},
        success: function(response) {
            $response = $.parseJSON(response);
            $("#swvat"+$inputid).val($response['wvatamount']);        
            $("#swtax"+$inputid).val($response['wtaxamount']);
            $("#sppd"+$inputid).val($response['ppdamount']);            
        }
    });
}
$(".sapplied, .swtax, .swvat, .sppd").autoNumeric({}); */
</script>
