<?php 
foreach ($result as $row) :
?>
<tr data-value="<?php echo $row['id'] ?>">
    <td width="20px"><input type="radio" name="issuedate" class="issuedate" value="<?php echo $row['id'] ?>"></td>
    <td style='width:75px'><?php echo $row['ao_num'] ?></td>   
    <td style='width:100px'><?php echo $row['ao_issuefrom'] ?></td>  
    <td style='width:75px'><?php echo $row['ao_rfa_num'] ?></td>    
    <td style='width:100px'><?php echo $row['ao_rfa_date'] ?></td>     
    <td class="span_limit" style='width:100px'><span><?php echo $row['ao_payee'] ?></span></td>
    <td class="span_limit" style='width:100px'><span><?php echo $row['cmf_name'] ?></span></td>    
    <td class="span_limit" style='width:100px'><span><?php echo $row['ae'] ?></span></td>    
    <td style='width:75px'><?php echo $row['ao_sinum'] ?></td>     
    <td style='width:100px'><?php echo $row['ao_sidate'] ?></td> 
    <td class="span_limit" style='width:150px'><span><?php echo $row['ao_rfa_findings'] ?></span></td>    
    <td class="span_limit" style='width:150px'><span><?php echo $row['rfatype_name'] ?></span></td>    
</tr>
<?php 
endforeach;
?>

<script>

$('#aiform_rfa').unbind('click').click(function(){
    var aoptmid = $(".issuedate:checked").val();
    if (aoptmid == "" || aoptmid == null) {
        alert('No Issue Date Detailed!.'); return false;
    } else {
        
        $.ajax({
            url: "<?php echo site_url('aiform/rfa_view') ?>",
            type: 'post',
            data:{aoptmid:aoptmid},
            success: function(response) {
                var $response = $.parseJSON(response);
                 $('#ai_rfa_view').html($response['rfa_index']).dialog('open');      
                               
            }
        });
    }
});
/*$('.tbody').hoverable({
    select: function(el) {
            aoptmid = $(el).attr('data-value');
            
            $.ajax({
            url: "<?php echo site_url('aiform/rfa_view') ?>",
            type: 'post',
            data:{aoptmid:aoptmid},
            success: function(response) {
                var $response = $.parseJSON(response);
                $('#ai_rfa_view').html($response['rfa_index']).dialog('open');                                      
            }
        })
    }    

});*/
</script>
