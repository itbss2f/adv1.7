<link href="<?php echo base_url() ?>themes/css/stylesheets.css" rel="stylesheet" type="text/css" />



<script type='text/javascript' src='<?php echo base_url() ?>themes/js/jquery1-7.min.js'></script>
<script type='text/javascript' src='<?php echo base_url() ?>themes/js/jqueryui1-8.min.js'></script>


<script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/dataTables/jquery.dataTables.min.js'></script>    


<style>
td {
    font-size: 12px;
}
input[type=text] {
    height: 30px;
}
</style>     
<table cellpadding="1" cellspacing="1" width="100%" class="table" id="clientable" border="1">

    <thead>
        <th>Type</th>   
        <th>Code</th>   
        <th>Name</th>   
        <th>Address</th>   
        <th>Contact #</th>   
        <th>TIN</th>   
        <th>VAT</th>   
        <th>Industry</th>   
        <th>Action</th>   
    </thead>
    
    <tbody>
        <?php foreach ($list as $list) : ?>
        <tr>
            <td><?php echo $list['catad_name'] ?></td>
            <td><?php echo $list['cmf_code'] ?></td>
            <td><?php echo $list['cmf_name'] ?></td>
            <td><?php echo $list['address'] ?></td>
            <td><?php echo $list['contacts'] ?></td>
            <td><?php echo $list['cmf_tin'] ?></td>
            <td><?php echo $list['vat_code'] ?> %</td>
            <td><?php echo $list['ind_name'] ?></td>
            <td>
                <a href="#" id="<?php echo $list['id'] ?>" class="edit" title="Edit">EDIT</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    
</table>
<div id="modal_editdata" title="Edit Data"></div> 
<script>
$('#modal_editdata').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: 680,    
    height:'auto',
    modal: true,
    resizable: false
});
//$('#clientable').dataTable();
$('.edit').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('sales_dash/editAEClient') ?>",
          type: "post",
          data: {id: $id},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_editdata").html($response['editdata_view']).dialog('open');    
          }    
       });      
    });  
</script>