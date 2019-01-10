<style>
	#draggable { width: 100px; height: 40px; padding: 0.5em; float: left; margin: 10px 10px 10px 0;  border: 3px solid #FF6700}
</style>
<div style="width:100%; height: 450px; overflow: auto">
<table>
    <thead>
        <td>Ao Num</td>
        <td>Item ID</td>
        <td>Type</td>
        <td>Product</td>
        <td>Issue Date</td>
        <td>Class</td>
        <td>Width</td>
        <td>Length</td>
        <td>Color</td>
        <td>Position</td>
        <td>Page Min</td>
        <td>Page Max</td>        
    </thead>
    <?php 
    
    foreach ($flow as $flow) 
    {
    ?>
    <tr>
        <td><?php echo $flow['ao_num'] ?></td>
        <td><?php echo $flow['ao_item_id'] ?></td>
        <td><?php echo $flow['ao_type'] ?></td>
        <td><?php echo $flow['ao_prod'] ?></td>
        <td><?php echo $flow['ao_issuefrom'] ?></td>
        <td><?php echo $flow['ao_class'] ?></td>
        <td><?php echo $flow['ao_width'] ?></td>
        <td><?php echo $flow['ao_length'] ?></td>
        <td><?php echo $flow['ao_color'] ?></td>
        <td><?php echo $flow['ao_position'] ?></td>
        <td><?php echo $flow['ao_pagemin'] ?></td>
        <td><?php echo $flow['ao_pagemax'] ?></td>
    </tr>
    <?php
    }
    
    ?>
</table>
</div>
