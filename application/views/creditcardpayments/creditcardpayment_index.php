<div>
<form id="filters" >
    <dl class="tbody" style="width:1250px;border-bottom: 1px solid #CCC;">
        <dd style="width:60px;border-right:none;">Date From</dd>
        <dd style="border-right:none;"><input type="text" class="datepicker" style="width:100%" id="from_date" name="from_date" ></dd>
        <dd style="width:60px;border-right:none;">Date To</dd>
        <dd style="border-right:none;"><input type="text" class="datepicker" style="width:100%" id="to_date" name="to_date" ></dd>
      
    </dl>
    <dl class="tbody" style="width:1250px;border-bottom: 1px solid #CCC;">
    
        <dd style="width:60px;border-right:none;">Type</dd>
        <dd style="width:110px;border-right:none;">
            <select name="cdcr_type" id="cdcr_type">
            
                         <?php for($ctr=0;$ctr<count($cdcr_type);$ctr++) { ?>
                                <option value="<?php echo $cdcr_type[$ctr]['type'] ?>"><?php echo $cdcr_type[$ctr]['type'] ?></option>
                         <?php } ?>
            </select>
        </dd>
        <dd class="branch" style="text-align:center;width:50px;border-right:none">Branch</dd>
        <dd class="branch" style="width:210px;border-right:none;">
            <select name="branch_select" id="branch_select">
                          <option value=""></option>
                         <?php for($ctr=0;$ctr<count($branches);$ctr++) { ?>
                                <option value="<?php echo $branches[$ctr]['branch_name'] ?>"><?php echo $branches[$ctr]['branch_name'] ?></option>
                         <?php } ?>
            </select>
        </dd>
        <dd style="width:100px;border-right:none;">Cashier Code</dd>
        <dd style="width:160px;border-right:none;padding-left:10px;"><input type="text"  style="width:100%" id="cashier_code" name="cashier_code" ></dd>

    
    </dl>
    <dl class="tbody" style="width:1250px;border-bottom: 1px solid #CCC;padding-top: 10px;">
       <dd style="border-right:none;width:100px;cursor: pointer;"><span class='x-icon x-icon-generate' name='generate' id='generate'>Generate</span></dd>
       <dd style="border-right:none;width:100px;cursor: pointer;"><span class='x-icon x-icon-export' name='export' id='export'>Export</span></dd> 
       <dd style="border-right:none;width:100px;cursor: pointer;"><span class='x-icon x-icon-filter' name='filter' id='filter'>Filter</span></dd>
       <dd style="float:right;border-right:none;width:290px"><span class='x-icon x-icon-search' name='search' id='search'>Search &nbsp; <input type="text" name="searchfigure" id="searchfigure"></span></dd>
    </dl>
</div>
 </form>

 <div style="overflow-x:scroll;min-height: 520px;;">
    <dl class='thead' style="width: 2300px;">
        <dt>OR Number.</dt>    
        <dt style="width:250px">Partticular</dt>    
        <dt style="width:250px">Gov Status</dt>
        <dt>Remarks</dt>
        <dt>Cash</dt>
        <dt>Cheque No.</dt>
        <dt>Check Amount</dt>
        <dt>W/Tax Amount</dt>
        <dt>(%)</dt>
        <dt>Type</dt>
        <dt>Bank</dt>    
    </dl>
    
 <div id="dataTable" >
    
    
 </div> 
 
 <!--/*************DIALOG BOX***************/-->
 
  <div id="ExportDialogBox" title="Export"></div>
  <div id="FilterDialogBox" title="Filter"></div>  

 <!--/*************DIALOG BOX***************/--> 
 
 
 </div>

 
  
 <script type='text/javascript'>
    $(document).ready(function() {
        $('.tbody').die().live('hoverable',{
            select: function(el) {
            //    alert($(el).attr('data-value'));
            }    
      
        });
        
        $('.branch').hide();
        
        $(".datepicker").datepicker({ dateFormat: "yy-mm-dd" });
      
      });  
     $fd =  $("#ExportDialogBox").dialog({
                autoOpen: false, 
                closeOnEscape: false,
                draggable: true,
                width: 500,
                maxHeight: 100,
                modal: true,
                buttons: {
                            "Close":function()
                            {
                              $(this).dialog('close');  
                            }
                         } 
        });
        
    $ft =  $("#FilterDialogBox").dialog({
                autoOpen: false, 
                closeOnEscape: false,
                draggable: true,
                width: 650,
                height: 500,
                modal: true,
                buttons: {
                            "Close":function()
                            {
                              $(this).dialog('close');  
                            }
                         } 
        });
        
        
      $export = $("#export").die().live('click',function(){

                $.ajax({
                    type:'post',
                    url:'<?php echo site_url('cdcr/exportdialog') ?>',
                    data:null,
                    success:function (response)
                    {
                         $("#ExportDialogBox").html($.parseJSON(response)).dialog('open');
                    }
                    
                });
        });
      
       
      $filter =  $("#filter").die().live('click',function(){

                $.ajax({
                    type:'post',
                    url:'<?php echo site_url('cdcr/exportdialog') ?>',
                    data:null,
                    success:function (response)
                    {
                         $("#FilterDialogBox").html($.parseJSON(response)).dialog('open');
                    }
                    
                });
        });
      
     $cdcr_type = $("#cdcr_type").die().live('change',function(){
          
          if($(this).val() == 'PR-Branch' || $(this).val() == 'Branch' )
          {
             $('.branch').show();   
          }
          else
          {
             $('.branch').hide();  
          }
          
      });
                   
    $exportBtn = $(".exportBtn").die().live('click',function(){
        
            $export_type = $(this).find("span").html();
            $export_data = $("#dataTable").html();
            
            sData = "<form name='exportForm' id='exportForm' action='<?php echo site_url('export/generate') ?>' method='post'>";
            sData = sData + "<input type='hidden' name='export_type' id='export_type' value='" + $export_type + "' />";
            sData = sData + "<input type='hidden' name='export_data' id='export_data' value='" + $export_data + "' />";
            sData = sData + "</form>";

            sData = sData + "<script type='text/javascript'>";
            sData = sData + "document.exportForm.submit();</sc" + "ript>";
            
            OpenWindow=window.open("", "newwin");
            OpenWindow.document.write(sData);
            window.close();
        
    });  
         
</script>

