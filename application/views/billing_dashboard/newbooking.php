<table cellpadding="0" cellspacing="0" width="100%" class="sOrders" style="font-size: 10px">
    <thead>
        <tr>
            <th width="100">TYPE</th>
            <th width="100">AO NUMBER</th>
            <th>USER</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($book as $book) : ?>
        <tr>
            <td><?php echo $book['typename'] ?></td>
            <td><?php echo $book['ao_num'] ?></td>
            <td><?php echo $book['firstname'].' '.$book['middlename'].'. '.$book['lastname'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>