<?php if (empty($data)) : ?>
    <tr>
        <td colspan="9" style="text-align: center; color: red; font-size: 20px;">No Record Found</td>
    </tr>

<?php else : ?>
<?php foreach ($data as $row) : ?>
<tr>
    <td width="10px"><input type="radio" class="dcidd" name="dcid" id="dcid" value="<?php echo $row['dc'].'/'.$row['dc_num'] ?>"></td>
    <td width="30px"><?php echo $row['dc_type'] ?></td>
    <td width="30px"><?php echo $row['dc_num'] ?></td>
    <td width="60px" class='span_limit'><?php echo $row['clientname'] ?></td>
    <td width="30px"><?php echo $row['dcsubtype_name'] ?></td>
    <td width="30px"><?php echo $row['adtype_name'] ?></td>
    <td width="30px" style="text-align: right;"><?php echo number_format($row['dc_amt'], 2, '.', ',') ?></td>
    <td width="30px"><?php echo $row['branch_name'] ?></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>  

<script>
$('#lookup_loaddetailed').click(function() {
    var dcidd = $('#dcid:checked').val();
    if(typeof dcidd != 'undefined') {
        window.location.href = "<?php echo base_url()?>dbmemo/view/"+dcidd;
    } else { alert("Select Debit / Credit to load!"); return false;}
});
</script>