
<div class="breadLine"> 
    <?php echo $breadcrumb; ?> 
</div>
<link rel='stylesheet' type='text/css' href='<?php echo base_url() ?>assets/css/style.css'/>      
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
                <h1>Invoice</h1>                    
                <ul class="buttons">
                    <!-- <li><a href="#" class="isw-download"></a></li>                                                        
                    <li><a href="#" class="isw-print"></a></li> -->
                    <li>
                        <a href="#" class="isw-settings"></a>
                        <ul class="dd-list">
                            <li><a href="#" id="searchdata"><span class="isw-zoom"></span> Search Invoice</a></li>                                                    
                        </ul>
                    </li>
                </ul>                        
                <div class="clear"></div>
            </div>
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
                  <?#php if($canUPLOAD) : ?>
                  <span class="icon-file upload" id="<?php echo $invoice_list['id']?>" title="uploading"><span>
                  <?#php endif; ?>
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
        </div>                                
        
    </div>            
    
    <div class="dr"><span></span></div>            
    
</div> 

<div id="modal_searchdata" title="Search"></div>

<script>

$(".upload").click(function () {
    
    var $id = $(this).attr('id');
    var ans = window.confirm("Are you sure you want to upload?")

    if (ans)
    {
    window.location = "<?php echo site_url('aiform/uploading_of_invoicedata') ?>/"+$id; 
    return true;
    }
    else
    {
    //window.alert("Are you sure you want to cancel?");
    return false;    
    }
    
});
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

    $('#modal_searchdata').dialog({
        autoOpen: false, 
        closeOnEscape: false,
        draggable: true,
        width: 680,    
        height:'auto',
        modal: true,
        resizable: false
    });

    $('#searchdata').click(function(){
        //$('#modal_searchdata').dialog('open'); return 0;
        $.ajax({
          url: "<?php echo site_url('aiform/searchdata') ?>",
          type: "post",
          data: {},
          success:function(response) {
             $response = $.parseJSON(response);
             $('#modal_searchdata').html($response['searchdata_view']).dialog('open');
          }
        });
    });
  
});
    
</script>

