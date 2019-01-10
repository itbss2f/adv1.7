<?php if (empty($list)) : ?>
    <tr>
        <td colspan="10" style="text-align: center; color: red; font-size: 20px;">No Record Found</td>
    </tr>

<?php endif; ?>

<?php foreach ($list as $sec => $list) : ?>

    <tr>
        <td><b><?php echo $sec ?></b></td>
        <?php foreach ($list as $row) : ?>
        <tr>
            <td><?php echo $row['issuedate'] ?></td>
            <td><?php echo $row['folio_number'] ?></td>
            <td><?php echo $row['page_number'] ?></td>
            <td><?php echo $row['boxsize'] ?></td>
            <td><?php echo $row['class_code'] ?></td>
            <td><?php echo $row['payeename'] ?></td>
            <td><?php echo $row['agency'] ?></td>
            <td><?php echo $row['color'] ?></td>
            <td><?php echo $row['ao_num'] ?></td>     
            <td><?php echo $row['ao_part_records'] ?></td>
          
        </tr>
        <?php endforeach; ?>
    </tr>

<?php endforeach; ?>
    

