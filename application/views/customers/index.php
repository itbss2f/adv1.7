
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
                <h1>Customer</h1>                    
                <ul class="buttons">
                    <!-- <li><a href="#" class="isw-download"></a></li>                                                        
                    <li><a href="#" class="isw-print"></a></li> -->
                    <li>
                        <a href="#" class="isw-settings"></a>
                        <ul class="dd-list">
                            <?php if ($canADD) : ?>
                            <li><a href="#" id="newdata"><span class="isw-plus"></span> New Customer</a></li>                        
                            <?php endif; ?>
                            <li><a href="#" id="searchdata"><span class="isw-zoom"></span> Search Customer</a></li>                                                    
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
						    <th width="05%">ID</th>
						    <th width="10%">Code</th>
						    <th width="35%">Name</th>
						    <th width="10%">Credit Limit</th>
						    <th width="10%">Credit Status</th>                                                                
                            <th width="15%">Account Executive</th>    
                            <th width="15%">Date Created</th>    
						    <th width="15%">Date Edited</th>    
                <th width="15%">Action</th>              
					    </tr>
                        </thead>
                        <tbody>
                    	    <?php foreach ($customer_list as $customer) : ?>
					    <tr>
						    <td><?php echo $customer['id'] ?></td>	
						    <td><?php echo strtoupper($customer['cmf_code']) ?></td>
						    <td><?php echo strtoupper($customer['cmf_name']) ?></td>
						    <td style="text-align:right"><?php echo number_format($customer['cmf_crlimit'], 2, '.',',') ?></td>
						    <td style="text-align:center"><?php echo strtoupper($customer['cmf_crstatusname']) ?></td>
						    <td><?php echo strtoupper($customer['firstname'].' '.$customer['lastname']) ?></td>   
                            <td><?php echo $customer['user_d'] ?></td>    
                            <td><?php echo $customer['edited_d'] ?></td>  
						                <td>
                                <?php if ($canEDIT) : ?>  <span class="icon-pencil edit" id="<?php echo $customer['id'] ?>" title="Edit"></span> <?php endif; ?>  
                                <?php if ($canCUSTOMERCCCONTACT) : ?>   <span class="icon-book cc_contact" id="<?php echo $customer['id'] ?>" title="Collection Contact Details"></span>  <?php endif; ?>     
                                <?php if ($canVIEW) : ?>   <span class="icon-search view" id="<?php echo $customer['id'] ?>" title="View"></span> <?php endif; ?>      
                                <?php if ($canDELETE) : ?>   <span class="icon-trash remove" id="<?php echo $customer['id'] ?>" title="Remove"></span>  <?php endif; ?>
                                <span class="icon-file upload" id="<?php echo $customer['id']?>" title="uploading"><span>
                            </td>       
					    </tr>
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
   
<div id="modal_newdata" title="New Data"></div>
<div id="modal_editdata" title="Edit Data"></div>
<div id="modal_searchdata" title="Search Data"></div>
<div id="modal_viewcust" title="Customer Data Info"></div>
<div id="modal_cccontact" title="Collection Contact Detail Data Info"></div>

<script>

$(".upload").click(function () {
    
    var $id = $(this).attr('id');
    var ans = window.confirm("Are you sure you want to upload?")

    if (ans)
    {
    window.location = "<?php echo site_url('customer/uploadcustdata') ?>/"+$id; 
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
             url:'<?php echo site_url('customer/pageselect'); ?>',
             data:{page:$(this).val()},
             success: function(response)
             {
                    $("#paginate-content").html($.parseJSON(response)); 
             }
        });    
        
    });
    
    $('#modal_newdata, #modal_editdata, #modal_searchdata, #modal_viewcust, #modal_cccontact').dialog({
        autoOpen: false, 
        closeOnEscape: false,
        draggable: true,
        width: 680,    
        height:'auto',
        modal: true,
        resizable: false
    });
    
    $('#newdata').click(function(){    
        $.ajax({
            url: "<?php echo site_url('customer/newdata') ?>",
            type: "post",
            data: {},
            success:function(response) {
                $response = $.parseJSON(response);
                $("#modal_newdata").html($response['newdata_view']).dialog('open');    
            }    
        });        
    });  
    
    $('.edit').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('customer/editdata') ?>",
          type: "post",
          data: {id: $id},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_editdata").html($response['editdata_view']).dialog('open');    
          }    
       });      
    });  
    
    $('.remove').click(function() {
        var $id = $(this).attr('id'); 
        var catad = $("#catad").val();
        var ans = confirm("Are you sure you want to remove this Customer?");  
		
        //var id = $('#id').val(); 
    		
    		$.ajax({
    			url: "<?php echo site_url('customer/validated_id')?>/"+$id,
    			type: "post",
    			data: {id: $id},
    			success: function(response) {
    				$response = $.parseJSON(response)

            if (ans) {
               if (response == "true") {  
               alert('This Customer cannot be deleted');
              } else {
                  alert('Successfully deleted');
                  window.location = "<?php echo site_url('customer/search') ?>";
                  return false;
                }

                return false;   

            }
    		
		      }
    			
    		});
			
    });
    
    $('.view').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('customer/viewCustData') ?>",
          type: "post",
          data: {id: $id},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_viewcust").html($response['view']).dialog('open');    
          }    
       });          
    });
    
    $('.cc_contact').click(function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('customer/ccContactCustData') ?>",
          type: "post",
          data: {id: $id},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_cccontact").html($response['view']).dialog('open');    
          }    
       });          
    });
    
    $('#searchdata').click(function(){
        //$('#modal_searchdata').dialog('open'); return 0;
        $.ajax({
          url: "<?php echo site_url('customer/searchdata') ?>",
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

