<div id="paginate-content">
    <div class="pages"><?php echo $pages ?></div>    
   
    <div class="block-fluid table-sorting">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
            <thead>
            <tr>
                <th width="05%">No.</th>
                <th width="10%">Invoice Number</th>
                <th width="10%">Invoice Date</th>
                <th width="20%">Client Name</th>
                <th width="20%">Agency Name</th>                                                                   
                <th width="5%">Action</th>      
            </tr>
            </thead>
            <tbody>
                <?php $no = 1;  ?>
                <?php foreach ($invoice_list as $invoice_list) : ?>
            <tr>
                <td><?php echo $no ?></td>  
                <td><?php echo strtoupper($invoice_list['ao_sinum']) ?></td>
                <td><?php echo strtoupper($invoice_list['invdate']) ?></td>
                <td><?php echo $invoice_list['clientname'] ?></td>
                <td><?php echo $invoice_list['agencyname'] ?></td>   
                <td>
                    <span class="icon-file upload" id="<?php echo $invoice_list['id']?>" title="uploading"><span>
                </td>          
            </tr>
            <?php $no += 1; ?>
            <?php endforeach; ?>    
            </tbody>
        </table>
        <div class="clear"></div>
    
    </div>
    <div class="pages"><?php echo $pages ?></div>         
</div>      

<script>
$(function() {
    
    $page = $(".pages > ul > li").click(function(){
            
        $.ajax({
             type:'post',
             url:'<?php echo site_url('aiform/pageselect'); ?>',
             data:{page:$(this).val()},
             success: function(response)
             {
                    $("#paginate-content").html($.parseJSON(response)); 
             }
        });    
        
    });
  
});
</script>