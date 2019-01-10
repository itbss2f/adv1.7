<div class="row-form-booking">
   <div class="span1">DC Type</div>
   <div class="span1">
    <select name="look_dctype" id="look_dctype">
        <option value="C">Credit</option>
        <option value="D">Debit</option>
    </select>
   </div>
   <div class="span1">DC No.</div>
   <div class="span1"><input type="text" name="look_dcnumber" id="look_dcnumber"/></div>
   <div class="span1">DC Date</div>
   <div class="span1" style="width:100px"><input type="text" name="look_dcdate" id="look_dcdate"/></div>
   <div class="clear"></div>
</div>
<div class="row-form-booking">
   <div class="span1">DC Subtype</div>
   <div class="span2">
    <select name="look_dcsubtype" id="look_dcsubtype">
        <option value="">--</option>
        <?php 
        foreach ($dcsubtype as $dcsubtype) : ?>
        <option value="<?php echo $dcsubtype['id']?>"><?php echo $dcsubtype['dcsubtype_name'] ?></option>
        <?php                                                                                  
        endforeach;
        ?>   
    </select>
   </div>
   <div class="span1">Adtype</div>
   <div class="span2">
    <select name="look_adtype" id="look_adtype">
        <option value=''>--</option>
        <?php 
        foreach ($adtype as $adtype) : ?>
        <option value="<?php echo $adtype['id']?>"><?php echo $adtype['adtype_name'] ?></option>
        <?php                                                                                  
        endforeach;
        ?>                       
    </select>
   </div>   
   <div class="clear"></div>
</div>
<div class="row-form-booking">
   <div class="span1">Client</div>
   <div class="span2"><input type="text" placeholder="Code" name="look_clientcode" id="look_clientcode"/></div>   
   <div class="span3"><input type="text" placeholder="Name" name="look_clientname" id="look_clientname"/></div>   
   <div class="clear"></div>
</div>
<div class="row-form-booking">
   <div class="span1">DC Amount</div>
   <div class="span2"><input type="text" name="look_dcamount" id="look_dcamount"/></div>
   <div class="span1">Branch</div>
   <div class="span2">
    <select name="look_branch" id="look_branch">
        <option value="">--</option>
        <?php 
        foreach ($branch as $branch) { 
        ?>                                         
        <option value='<?php echo $branch['id']?>'><?php echo $branch['branch_name']?></option>
        <?php    
        }
        ?>
    </select>
    </div>   
   <div class="clear"></div>
</div>
<div class="row-form-booking">                            
    <div class="span3" style="padding-left:30px">
      <button class="btn btn-block" type="button" id="lookup_search_btn" name="lookup_search_btn">Search Debit/Credit Memo</button>
    </div>
    <div class="span3">
      <button class="btn btn-block" type="button" id="lookup_loaddetailed" name="lookup_loaddetailed">Load Debit/Credit Memo</button>
    </div>                           
    <div class="clear"></div>
</div>  
<div class="dr" style="margin-top:-10px"><span></span></div>     
<div class="block-fluid" style="margin-top:-10px">                        
    <div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:240px"> 
        <table cellpadding="0" cellspacing="0" style="white-space:nowrap" class="table" id="tSortable_2">
           <thead>
                <tr>                        
                    <th width="10px"></th>
                    <th width="30px">DC Type</th>
                    <th width="30px">DC No.</th>
                    <th width="60px">Client</th>                    
                    <th width="30px">DC Subtype</th>
                    <th width="30px">Adtype</th>                                    
                    <th width="30px">Amount</th>       
                    <th width="30px">Branch</th>                                  
                </tr>
           </thead>
           <tbody class="lookup_list">                                                                             
           </tbody>
        </table>
        <div class="clear"></div>
    </div>                
</div>  

<script>

$("#look_dcdate").datepicker({dateFormat: 'yy-mm-dd'});  
     
$('#lookup_search_btn').click(function(){
    
    var $dctype = $('#look_dctype').val(); 
    var $dcnumber = $('#look_dcnumber').val();
    var $dcdate = $('#look_dcdate').val();
    var $dcsubtype = $('#look_dcsubtype').val();
    var $adtype = $('#look_adtype').val();
    var $clientcode= $('#look_clientcode').val();
    var $clientname = $('#look_clientname').val();
    var $dcamount = $('#look_dcamount').val();
    var $branch = $('#look_branch').val();
    
    $.ajax({
        url: "<?php echo site_url('dbmemo/find_lookup') ?>",
        type: "post",
        data: {dctype: $dctype, dcnumber: $dcnumber, dcdate: $dcdate, dcsubtype: $dcsubtype, adtype: $adtype, clientcode: $clientcode,
               clientname: $clientname, dcamount: $dcamount, branch: $branch},
        success: function(response) {
            $response = $.parseJSON(response);
            
            $('.lookup_list').html($response['lookup_list']);    
        }    
    });
});
</script>                                                                     