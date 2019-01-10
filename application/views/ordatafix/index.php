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
                    <h1>Official Receipt Datafix Utility</h1>
                    
                <div class="clear"></div>
            </div>
            <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width: 100px; margin-top:12px"><b>OR Number:</b></div>
                <div class="span1" style="width: 100px; margin-top:12px"><input type="text" id="ornum" placeholder="########" name="aonum"/></div>   

                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Search button</button></div>               
                <div class="clear"></div>
            </div> 
            <div class="block-fluid table-sorting" style="min-height: 450px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                    <thead>
                    <tr>                       
                       <th width="8%">OR Number</th> 
                       <th width="8%">OR Date</th> 
                       <th width="20%">Payee Name</th> 
                       <th width="8%">OR Amount</th> 
                       <th width="8%">Status</th>
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
$('#ornum').mask('99999999');
$('#generatereport').click(function(){
    $.ajax({
        url: '<?php echo site_url('ordatafix/searchornum') ?>',
        type: 'post',
        data: {ornum : $('#ornum').val()},
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

