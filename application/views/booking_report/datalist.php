
<?php
$no = 1;    
foreach ($list as $list) : ?>
<tr>
    <td><?php echo $no ?></td>
    <td><?php echo $list['issuedate']?></td>
    <td><?php echo $list['ao_num'] ?></td>
    <td><?php echo $list['ao_ref'] ?></td>
    <td><?php echo $list['clientname'] ?></td>
    <td><?php echo $list['agencyname'] ?></td>
    <td><?php echo $list['empprofile_code'] ?></td>
    <td><?php echo $list['size'] ?></td>
    <td style="text-align: right;"><?php echo $list['ao_adtyperate_rate'] ?></td>
    <td><?php echo $list['charges'] ?></td>
    <td style="text-align: right;"><?php echo $list['amount'] ?></td>
    <td><?php echo $list['class_code'] ?></td>
    <td><?php echo $list['color'] ?></td>
    <td><?php echo $list['records'] ?></td>
    <td><?php echo $list['paytype_name'] ?></td>
    <td><?php echo $list['ao_type'].' - '.$list['status'] ?></td>            
</tr>
<?php 
$no += 1;
endforeach; ?>