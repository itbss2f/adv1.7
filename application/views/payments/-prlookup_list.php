<?php foreach($list as $list) : ?>
<tr>						
	<td width="20px"><input type="radio" name="lookuplist" class="lookuplist" value="<?php echo $list['pr_num'] ?>"></td>
	<td width="40px"><?php echo $list['pr_num'] ?></td>
	<td width="40px" class="span_limit"><?php echo $list['pr_cmf'].''.$list['pr_amf'] ?></td>                                    
	<td width="60px" class="span_limit"><?php echo $list['pr_payee'] ?></td>         												
	<td width="40px" style="text-align:right"><?php echo number_format($list['pr_amt'], 2, '.', ',') ?></td>    
	<td width="40px"><?php echo $list['ccf'] ?></td>      													
	<td width="40px"><?php echo $list['bank'] ?></td>      													
	<td width="40px"><?php echo $list['branch'] ?></td>      													
	<td width="180px" class="span_limit"><?php echo $list['pr_part'] ?></td>      													
	<td width="180px" class="span_limit"><?php echo $list['pr_comment'] ?></td>      					
</tr>
<?php endforeach; ?>
