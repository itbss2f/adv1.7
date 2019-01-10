<?php if (empty($list)) : ?>
<tr>
    <td colspan="10" style="text-align: center; color: red; font-size: 20px;">No Record Found</td>
</tr>

<?php else : ?>
<?php foreach($list as $list) : ?>
<tr>						
	<td width="20px"><input type="radio" name="lookuplist" class="lookuplist" value="<?php echo $list['or_num'] ?>"></td>
	<td width="40px"><?php echo $list['or_num'] ?></td>
	<td width="40px" class="span_limit"><?php echo $list['or_cmf'].''.$list['or_amf'] ?></td>                                    
	<td width="60px" class="span_limit"><?php echo $list['or_payee'] ?></td>         												
    <td width="40px" style="text-align:right"><?php echo number_format($list['or_amt'], 2, '.', ',') ?></td>    
    <td width="40px" style="text-align:right"><?php echo number_format($list['or_assignamt'], 2, '.', ',') ?></td>      
	<td width="40px"><?php echo $list['ccf'] ?></td>      													
	<td width="40px"><?php echo $list['bank'] ?></td>      													
	<td width="40px"><?php echo $list['branch'] ?></td>      													
	<td width="180px" class="span_limit"><?php echo $list['or_part'] ?></td>      													
	<td width="180px" class="span_limit"><?php echo $list['or_comment'] ?></td>      					
</tr>
<?php endforeach; ?>

<?php endif; ?>