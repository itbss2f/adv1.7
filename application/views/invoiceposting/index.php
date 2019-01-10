<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">

    <div class="row-fluid">

        <div class="span6">
        <div class="head">
            <div class="isw-list"></div>
            <h1>Invoice Posting</h1>
            <div class="clear"></div>
        </div>
        <div class="block-fluid">                        
            
            <div class="row-form">
               <div class="span2">Date</div>
               <div class="span3"><input type="text" class="datepicker" placeholder="From" name="fromdate" id="fromdate"></div>
               <div class="span3"><input type="text" class="datepicker" placeholder="To" name="todate" id="todate"></div>
               <div class="clear"></div>
            </div>                         
            
            <div class="row-form">        
               <div class="span3"><button class="btn btn-success" type="button" name="mass_posted" id="mass_posted">Post Invoice</button></div> 
               <div class="clear"></div>
            </div>     
            <div class="block-fluid table-sorting">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable1">
                    <thead>
                    <tr>                       
                       <th width="15%"># Counter</th>
                       <th width="25%">Client Name</th>                                                              
                       <th width="25%">Agency Name</th> 
                       <th width="15%">Invoice Number</th>
                       <th width="15%">Invoice Date</th>                                                                                                                
                    </tr>
                    </thead>
                    <tbody id="mass_result"></tbody>
                </table>
                <div class="clear"></div>
            </div>          
                      
        </div>
        </div>

    </div>                

    <div class="dr"><span></span></div>                

</div>  

<script>
//$('#tSortable1, #tSortable2').dataTable({});

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});

$("#mass_posted").click(function(){
    var $fromdate = $("#fromdate").val();
    var $todate = $("#todate").val();

    var ans = confirm ("Are you sure you want this Invoice list to be posted?");
    
    if (ans) {

        if ($fromdate == "" || $todate == "") {
            alert ("Date must not be empty!");
            return false;
        } else {
        
            if ($fromdate > $todate) {
                    alert ("The to date must be greater than your from date");
                    return false;
            }    
            $.ajax({
                url: "<?php echo site_url('invoiceposting/postthisor') ?>",
                type: "post",
                data: {fromdate: $fromdate, todate: $todate},
                success: function(response) {
                    var $response = $.parseJSON(response);
                    
                    $('#mass_result').html($response['viewresult']);             
                }
            });  
        }
    }

});

</script>
