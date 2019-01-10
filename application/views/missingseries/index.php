<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Missing Series Report</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:80px;margin-top:12px">Series Type</div>                                                                                                                            
                <div class="span2" style="width:150px;margin-top:12px">
                    <select name="seriestype" id="seriestype">                    
                        <option value="AI">Advertising Invoice</option>                        
                        <option value="CM">Credit Memo</option>                        
                        <option value="DM">Debit Memo</option>                        
                        <option value="OR (M)">Official Receipt (Manual)</option>                        
                        <option value="OR (A)">Official Receipt (Auto)</option>                        
                    </select>
                </div>
                <div class="span1" style="width:80px;margin-top:12px">Start Number:</div>
                <div class="span1" style="width:100px;margin-top:12px"><input type="text" id="startnumber" name="startnumber"/></div>
                <div class="span1" style="width:80px;margin-top:12px">End Number:</div>
                <div class="span1" style="width:100px;margin-top:12px"><input type="text" id="endnumber" name="endnumber"/></div>
                <div class="span1" style="width:130px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>               
                <div class="clear"></div> 
           </div> 
           <div class="report_generator" style="height:800px;;">
                <div class="block-fluid table-sorting">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_2">
                        <thead>
                            <tr>
                               <th width="5">#</th>
                               <th width="10">Series Type</th>
                               <th width="20">Series Number</th>
                            </tr>                                                                 
                        </thead>
                        <tbody id='datalist' style="min-height: 800px; font-size: 11px">
                        </tbody>
                    </table>
                    <div class="clear"></div>
                </div>
            </div>        
   
        </div> 
 
    <div class="dr"><span></span></div>
</div> 

<script> 
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$('#generatereport').click(function() {  
    var seriestype = $('#seriestype').val();
    var startnumber = $('#startnumber').val();
    var endnumber = $('#endnumber').val();
    
    var countValidate = 0;  
    var validate_fields = ['#startnumber', '#endnumber'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
        $.ajax({
            url: '<?php echo site_url('missingseries/getdata') ?>',
            type: 'post',
            data: {seriestype: seriestype, startnumber: startnumber, endnumber: endnumber},
            success: function (response) {
                var $response = $.parseJSON(response);
                
                $('#datalist').html($response['result']);
            }    
        });
    } 
});     
</script>


