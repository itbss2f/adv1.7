<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>
<div class="workplace">
    <?php 
    $msg = $this->session->flashdata('msg');
    if ($msg != '') :
    ?>
    <script>
    $.gritter.add({
        title: 'Success!',
        text: "<?php echo $msg ?>"

    });
    </script>
    <?php endif; ?>
    <div class="row-fluid">

        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Classified Cash/Check/Credit Card Datafix Utility</h1>
                    
                <div class="clear"></div>
            </div>
            <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width: 100px; margin-top:12px"><b>Ad Order Number:</b></div>
                <div class="span1" style="margin-top:12px"><input type="text" id="aonum" placeholder="########" name="aonum"/></div>   

                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Search button</button></div>               
                <div class="clear"></div>
            </div> 
            <div class="block-fluid table-sorting" style="min-height: 500px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                    <thead>
                    <tr>                       
                       <th width="8%">AO Number</th>
                       <th width="8%">Issue Date</th>
                       <th width="8%">Width</th>
                       <th width="8%">Length</th>
                       <th width="8%">Totalsize</th>   
                       <th width="12%">Class</th>
                       <th width="12%">Sub-Class</th>                                                           
                       <th width="8%">OR Number</th> 
                       <th width="8%">OR Date</th> 
                       <th width="8%">OR Amount</th> 
                       <th width="10%">Action</th>                                                             
                    </tr>
                    </thead>
                    <tbody id="dataresult">  
                    </tbody>
                </table>
            <div class="clear"></div>
            </div>
        </div>

    </div>            

    <div class="dr"><span></span></div>
</div>  
<div id="modal_edit" title="Edit Data"></div> 
<script>
$('#generatereport').click(function(){
    $.ajax({
        url: '<?php echo site_url('class_datafix/searchaonum') ?>',
        type: 'post',
        data: {aonum : $('#aonum').val()},
        success: function(response){
            $response = $.parseJSON(response);
            
            $('#dataresult').html($response['result']);    
        }    
    });   
});
$('#modal_edit').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: 350,    
    height: 'auto',
    modal: true,
    resizable: false
}); 

</script>

