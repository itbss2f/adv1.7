<script src="<?php echo base_url() ?>assets/js/ajaxfileupload.js"></script> 
<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/chosen.css">
<script src="<?php echo base_url() ?>assets/js/chosen.jquery.js" type="text/javascript"></script> 

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
                    <h1>Exdeal - Contracts</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                            <ul class="dd-list">
                                <?php if ($canADD) : ?>
                                <li><a href="#" id="newdata"><span class="isw-plus"></span> New Exdeal-Contract</a></li> 
                                <?php endif; ?>                                                         
                            </ul>
                        </li>    
                    </ul>                           
                <div class="clear"></div>
            </div>
            <div class="block-fluid table-sorting">
            <table class="table table-condensed" id="contract_list_table">
            <thead>
                <tr>
                    <th style='white-space:nowrap;'>Contract No.</th>
                    <th style='white-space:nowrap;'>Contract Type</th>
                    <th style='white-space:nowrap;'>Date</th>
                    <th style='white-space:nowrap;'>Client</th>
                    <th style='white-space:nowrap;'>Amount</th>
                    <th style='white-space:nowrap;'>Contact Person</th>                                                                
                    <th style='white-space:nowrap;' >Action</th>                                                                
                </tr>
            </thead>
            <tbody>        
            <?php   foreach ($results as $result) : ?>    
                <tr>
                    <td><?php echo $result->contract_no ?></td>
                    <td><?php echo $result->contract_type ?></td>
                    <td><?php echo $result->contract_date ?></td>
                    <td><?php echo $result->group_name ?></td>
                    <td><?php echo number_format($result->amount,2,'.',',') ?></td>
                    <td><?php echo $result->contact_person ?></td>
                    <td>
                        <?php if ($canEDIT) : ?>
                        <span class="icon-pencil edit"  id="<?php echo $result->id ?>" title="Edit"></span>
                        <?php endif; ?>
                        <?php if ($canDELETE) : ?>
                        <span class="icon-trash remove" id="<?php echo $result->id ?>" title="Remove"></span>
                        <?php endif; ?>
                        <?php if ($result->attachment_file != null OR $result->attachment_file != "") { ?>
                        <span class="picture" id="<?php echo $result->attachment_file ?>">
                         <a href="<?php echo base_url() ?>uploads/exdeal/<?php echo $result->attachment_file ?>">
                            <img  style="width:20px;height:20px" src="<?php echo base_url() ?>assets/images/pdf.png" >
                         </a>    
                         <?php  } ?>
                        </span>
                         <a href="<?php echo site_url('exdeal_contract/contract_form/'.$result->id) ?>">[FORM]</a>
                    </td>   
                </tr>
            <?php endforeach;   ?>   
            </tbody>
            </table>
            <div class="clear"></div>
            </div>
        </div>

    </div>            

    <div class="dr"><span></span></div>
</div>  
<div id="modal_newdata" title="New Exdeal Contract"></div>
<div id="modal_editdata" title="Edit Exdeal Contract"></div>
<script>

/*$("#tags").die().live("keyup",function(){
    
    $.ajax({
        
         url:"<?php echo site_url('exdeal_contract/search_customer'); ?>",
         type:"post",
         data:{search:$(this).val()},
         success:function (response)
         {
             $result =  $.parseJSON(response);
             $("#suggestion_box").html($result);
         }    
        
    });
    
});
               */
$(".customer_list").die().live("click",function(){
    
    $html =  $(this).html();
    $("#tags").val($html);
    $("#suggestion_box").html("");   
    
});

$(function() {
    $('#modal_newdata, #modal_editdata').dialog({
       autoOpen: false, 
       closeOnEscape: false,
       draggable: true,
       width: 800,    
       height: 'auto',
       modal: true,
       resizable: false
    });       

    $('.edit').die().live("click",function() {
        var $id = $(this).attr('id');
        $.ajax({
          url: "<?php echo site_url('exdeal_contract/openform') ?>",
          type: "post",
          data: {id: $id,action:'update'},
          success:function(response) {
             $response = $.parseJSON(response);
             $("#modal_newdata").html($response).dialog('open');    
          }    
       });      
    });

    $('.remove').die().live("click",function() {
        var $file = $(this).attr('id');
        var ans = confirm("Are you sure you want to remove this contract?");    

        if (ans) {

            $.ajax({
                  url: "<?php echo site_url('exdeal_contract/remove') ?>",
                  type: "post",
                  data: {id: $file},
                  success:function(response) {
                    
                      refresh();
                      
                  }    
            }); 
            
        }
    });

    $('#newdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('exdeal_contract/openform') ?>",
          type: "post",
          data: {action:'insert'},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_newdata").html($response).dialog('open');    
          }    
       });        
    });    
});

function refresh()
{
    $.ajax({
        url:"<?php echo site_url("exdeal_contract/refresh") ?>",
        type:"POST",
        success: function(response)
        {
          
            $("#contract_list_table > tbody").html($.parseJSON(response));
            
       
/*            
            var url = "<?php echo site_url("exdeal_contract/contract_form") ?>";
            
            window.open(url, '_blank');
             
            window.focus();
                              */
        }
    });
}


    $("#add").die().live("click",function(){
     
        var table = document.getElementById("dataTable");
        
                var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
 
            var colCount = table.rows[0].cells.length;
 
            for(var i=0; i<colCount; i++) {
 
                var newcell = row.insertCell(i);
 
                newcell.innerHTML = table.rows[0].cells[i].innerHTML;
                //alert(newcell.childNodes);
                switch(newcell.childNodes[0].type) {
                    case "text":
                            newcell.childNodes[0].value = "";
                            break;
                    case "checkbox":
                            newcell.childNodes[0].checked = false;
                            break;
                    case "select-one":
                            newcell.childNodes[0].selectedIndex = 0;
                            break;
                }
            }
    });

    $("#delete").die().live("click",function(){
        
      try {
            var table = document.getElementById("dataTable");
            var rowCount = table.rows.length;

            for(var i=0; i<rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if(null != chkbox && true == chkbox.checked) {
                    if(rowCount <= 1) {
                        alert("Cannot delete all the rows.");
                        break;
                    }
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }


            }
            }catch(e) {
                alert(e);
            }
            
    });
    
    
    $(".barter_chk").die().live("click",function(){
        
            var selectBarter = new Array();
            
            var n = jQuery(".barter_chk:checked").length;
                if (n > 0)
                {
                    $(".barter_chk:checked").each(function(){
                        selectBarter.push($(this).val());
                    });
                }
                                 
            $.ajax({
                url:"<?php echo site_url("exdeal_contract/retrieve_barter_condition"); ?>",
                type:"post",
                data:{id:selectBarter},
                success: function(response)
                {
                      $response = $.parseJSON(response);
                      
                      $("#dataTable").html($response);
                }
            });                     
                                 
    });
    


</script>

