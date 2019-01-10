<div class="block-fluid table-sorting">
    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable1">
        <thead>
        <tr>                       
           <th width="5%"># Counter</th>
           <th width="10%">AO Number</th>
           <th width="10%">Issue Date</th>                                                              
           <th width="20%">Client Name</th>                                                              
           <th width="10%">PO CONTRACT</th>                                                              
           <th width="10%">Invoice #</th>                                                              
           <th width="10%">Invoice Date</th>                                                              
           <th width="10%">Section</th>                                                              
        </tr>
        </thead>
        <tbody id="mass_result">
            <?php 
            $counter = 1;
            foreach ($tempinvoice as $temp) : ?>
            <tr>
                <td><?php echo $counter?></td>
                <td><?php echo $temp['aonum']?></td>
                <td><?php echo $temp['issuedate']?></td>
                <td><?php echo $temp['ao_payee']?></td>
                <td><?php echo $temp['ao_ref']?></td>
                <td><?php echo $temp['sinum']?></td>
                <td><?php echo $temp['sidate']?></td>
                <td><?php echo $temp['ao_billing_section']?></td>
            </tr>
            <?php $counter += 1;
            endforeach; 
             ?>
        </tbody>
    </table>
    <div class="clear"></div>
</div>  
<div class="row-form-booking" align="center">
    <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save to Live Data</button></div>        
    <div class="clear"></div>        
</div>    
<script>    
$("#save").click(function() {
    var $hkey = '<?php echo $hkey ?>';
    $.ajax({
        url: "<?php echo site_url('invoicing/autoSaveLive') ?>",
        type: "post",
        data: {hkey : $hkey},
        success: function(response) {
            
            var $response = $.parseJSON(response);           
            
            alert('Save to live successful');
            
            $('#autoview').dialog('close');
            
            $('#lastinvno').text($response['lastinv']);      
            $('#auto_startinvoice').val('');      
            $('#invoicingdate').val('');      
            
            $('#prevTempInvoice').html('');   
        }
    });        
}); 
</script>