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
                    <h1>Dummy  Unflow Utility</h1>
                    
                <div class="clear"></div>
            </div>
            <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width: 70px; margin-top:12px"><b>Issue Date:</b></div>
                <div class="span1" style="width: 60px; margin-top:12px"><input type="text" id="issuedate" class="datepicker" placeholder="####-##-##" name="issuedate"/></div>  
                <div class="span1" style="width:70px;margin-top:12px">Booking Type</div>                                                                                                                            
                <div class="span1" style="width:90px;margin-top:12px">
                    <select name="bookingtype" id="bookingtype">                                           
                        <option value="D">Display</option>                        
                        <option value="C">Classifieds</option>
                        <option value="3">Display hidden Ads</option>
                    </select>
                </div>  
                <div class="span1" style="width: 60px; margin-top:12px"><b>Product:</b></div>
                <div class="span2" style="width: 100px; margin-top:12px">
                <select name="prod" id="prod">
                <option value="0">--All--</option>  
                <?php foreach ($prod as $prod) : ?>
                    <option value="<?php echo $prod['id'] ?>"><?php echo $prod['prod_name'] ?></option>
                <?php endforeach; ?>
                </select>
                </div>   
                
                <div class="span1" style="width: 70px; margin-top:12px"><b>Ad Order #:</b></div>
                <div class="span1" style="width: 70px; margin-top:12px">
                    <input type="text" id="aonum" placeholder="#######" name="aonum"/>
                </div> 

                <div class="span2" style="width:40px;margin-top:12px">
                    <button class="btn btn-success" id="generatereport" type="button">Search</button>
                </div> 
                <div class="span2" style="width:50px;margin-top:12px;">
                    <button class="btn btn-success" id="search" type="button" style="display:none">Search</button>
                </div>    
                <div class="span2" style="width:100px;margin-top:12px;">
                    <button class="btn btn-success" id="restored" type="button" style="display:none">Restore Ads</button>
                </div>              
                <div class="clear"></div>
            </div> 
            <div class="block-fluid table-sorting" style="min-height: 450px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                    <thead>
                    <tr>                       
                       <th style="width:60px">Issuedate</th> 
                       <th style="width:50px">Sec #</th> 
                       <th style="width:40px">Page #</th> 
                       <th style="width:40px">Size</th> 
                       <th style="width:60px">Class</th> 
                       <th style="width:150px">Adcertiser Name</th> 
                       <th style="width:150px">Agency</th> 
                       <th style="width:60px">Color</th> 
                       <th style="width:60px">AO Number</th>
                       <th style="width:100px">Records</th>
                       <th style="width:80px">Action</th>                                                             
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
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'}); 

$('#restored').click(function() {
    
    var ans = confirm('Are you sure you want to restored hidden ads?');
    
    if (ans) {

        $.ajax({
          url: "<?php echo site_url('dummy_util/restored_ads') ?>",
          type: "post",
          data: {issuedate : $('#issuedate').val()},
          success:function(response) {
             $response = $.parseJSON(response);
             alert('Successfully Restored hidden ads');
             $('#dataresult').html($response['result2']);    
          }    
       });
    }
          
});

$('#search').click(function(){ 

    $.ajax({
        url: '<?php echo site_url('dummy_util/searchHiddenIssueDate') ?>',
        type: 'post',
        data: {issuedate : $('#issuedate').val(), prod: $('#prod').val(), aonum: $('#aonum').val()},
        success: function(response){
            $response = $.parseJSON(response);
            
            $('#dataresult').html($response['result2']);    
        }    
    });   
});


$('#generatereport').click(function(){

    $.ajax({
        url: '<?php echo site_url('dummy_util/searchIssueDate') ?>',
        type: 'post',
        data: {issuedate : $('#issuedate').val(), prod: $('#prod').val(), aonum: $('#aonum').val(), bookingtype: $('#bookingtype').val()},
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

$("#bookingtype").change(function() {
    var bookingtype = $(this).val();
    $("#search").hide();
    $("#restored").hide();
  
    if (bookingtype == "3") {
        $("#search").show();
        $("#restored").show();
        $("#generatereport").hide();

    } else {
        $("#search").hide();
        $("#restored").hide();
        $("#generatereport").show();
    }
    
});    

</script>

