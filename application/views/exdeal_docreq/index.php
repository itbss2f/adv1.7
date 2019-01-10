 <div class="workplace">      
         
        <div class="row-fluid">
                
        <div class="span12">                    
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Document Required</h1>      
                <ul class="buttons">
                <?php if ($canADD) : ?>
                 <li><a href="#" id="add_doc"><span class="isw-plus"></span></a></li>  
                 <?php endif; ?>
                </ul>                        
                <div class="clear"></div>
            </div>
            <div class="block-fluid">
                <table cellpadding="0" cellspacing="0" width="100%" id="l_table" class="table">
                    <thead>
                        <tr>                                    
                            <th width="15%">ID</th>
                            <th width="55%">Document</th>
                            <th width="25%">Action</th>
                               
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach($result as $result) : ?>
                        <tr class="tbody">                                    
                            <td class="tb_id"><?php echo $result->id ?></td>
                            <td><?php echo $result->doc_name ?></td>
                            <td>
                                <?php if ($canDELETE) : ?> 
                                <a href="#" class="icon-remove"></a>
                                <?php endif; ?> 
                                <?php if ($canEDIT) : ?>    
                                <a href="#" class="icon-edit"></a>   
                                <?php endif; ?>   
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>                                

        </div>     
            
</div>                   
<!--   <div class="dr"><span></span></div>   -->

<div class="dialog" id="b_popup_4" style="display: none;" title="Message"></div>

<script>

    $("#add_doc").die().live('click',function(){
        
        $.ajax({
            url:"<?php echo site_url("exdeal_docreq/openform") ?>",
            type:"post",
            data:{action:"save"},
            success: function(response){
                
                var options = {
                                buttons: {
                                    Submit: function () {
                                         save();
                                         $("#b_popup_4").dialog("close"); 
                                    },
                                     Close: function () {
                                        $(this).dialog('close');
                                    }
                                }
                };
                $("#b_popup_4").dialog('option', options);
                $("#b_popup_4").dialog("open").html($.parseJSON(response));
            }
        });
            
    });
   
   
    $(".icon-edit").die().live('click',function()
    {
         $id = $(this).parents('.tbody').find('.tb_id').html();              
         $.ajax({
            url:"<?php echo site_url("exdeal_docreq/openform") ?>",
            type:"post",
            data:{action:"update",id:$id},
            success: function(response){
                
                var options = {
                                buttons: {
                                    Submit: function () {
                                         update();
                                         $("#b_popup_4").dialog("close"); 
                                    },
                                     Close: function () {
                                        $(this).dialog('close');
                                    }
                                }
                };
                $("#b_popup_4").dialog('option', options);
                $("#b_popup_4").dialog("open").html($.parseJSON(response));
            }
        });
    });
    
    
    $(".icon-remove").die().live("click",function()
    {
 
         $id = $(this).parents('.tbody').find('.tb_id').html();   
   
         var options = {
                        buttons: {
                            Delete: function () {
                                 remove($id);
                                 $("#b_popup_4").dialog("close"); 
                            },
                             Close: function () {
                                $(this).dialog('close');
                            }
                        }
                        
                       };
        
            $("#b_popup_4").dialog('option', options);
            $("#b_popup_4").dialog("open").html("<p>Are you sure you want to remove?</p>");
    });
    
    
    function save()
    {
        $.ajax({
            url:"<?php echo site_url("exdeal_docreq/save") ?>",
            type:"post",
            data:$("#doc_form").serialize(),
            success: function(response)
            {
                refresh(); 
               // alert("<center><h5>Success</h5></center>");
            }
            
        });
    }
    
    function update()
    {
        $.ajax({
            url:"<?php echo site_url("exdeal_docreq/update") ?>",
            type:"post",
            data:$("#doc_form").serialize(),
            success: function(response)
            {
                refresh();
               // alert("<center><h3>Success</h3></center>");
            }
            
        });
    }
    
    function remove($id) 
    {
        $.ajax({
            url:"<?php echo site_url("exdeal_docreq/delete") ?>",
            type:"post",
            data:{id:$id},
            success: function(response)
            {
                refresh();
               // alert("<center><h3>Success</h3></center>");
            }
            
        });
    }
    
    function refresh()
    {
        $.ajax({
            url:"<?php echo site_url("exdeal_docreq/refresh") ?>",
            type:"post",
            success : function(response)
            {
                $("#l_table > tbody").html($.parseJSON(response)); 
            }
        });
    }

</script>
