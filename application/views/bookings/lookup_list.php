<?php if (empty($list)) : ?>
    <tr>
        <td colspan="9" style="text-align: center; color: red; font-size: 20px;">No Record Found</td>
    </tr>

<?php else : ?>
<?php foreach($list as $list) : ?>
<tr>						
	<td width="20px"><input type="radio" name="lookuplist" class="lookuplist" value="<?php echo $list['ao_num'] ?>"></td>
	<td width="40px"><?php echo $list['ao_num'] ?></td>
	<td width="60px" class="span_limit"><?php echo $list['ao_cmf'].' '.$list['ao_payee'] ?></td>                                    
	<td width="60px" class="span_limit"><?php echo $list['agencycode'].' '.$list['agencyname'] ?></td>       
	<td width="40px"><?php echo $list['prod_name'] ?></td>      													
	<td width="40px"><?php echo $list['ao_width'] ?></td>      													
	<td width="40px"><?php echo $list['ao_length'] ?></td>      													
	<td width="40px"><?php echo $list['empprofile_code'] ?></td>      													
	<td width="40px"><?php echo $list['stat'] ?></td>      													
	<td width="40px"><?php echo $list['paytype_name'] ?></td>      													
	<td width="40px"><?php echo $list['adtype_name'] ?></td>      													
	<td width="40px"><?php echo $list['class_name'] ?></td>      													
	<td width="40px"><?php echo $list['branch_name'] ?></td>      													
	<td width="100px" class="span_limit"><?php echo $list['ao_ref'] ?></td> 
    <td width="40px"><?php echo $list['startdate'] ?></td>    
    <td width="40px"><?php echo $list['enddate'] ?></td>         													 													
</tr>
<?php endforeach; ?>

<?php endif; ?>
