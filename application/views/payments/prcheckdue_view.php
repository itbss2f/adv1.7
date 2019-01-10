<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:250px"> 
    <table cellpadding="0" cellspacing="0" style="white-space:nowrap;width:400px" class="table" id="tSortable_2">
        <thead>
            <tr>                        
                <th width="20px"></th>
                <th width="40px">PR No.#</th>
                <th width="40px">Code</th>                                    
                <th width="60px">Payee</th>     
                <th width="40px">PR Amount</th>                                                                                    
                <th width="40px">Collector/Cashier</th>                                                          
                <th width="40px">Bank</th>                                                          
                <th width="40px">Branch</th>                                                          
                <th width="100px">Particulars</th>                                                          
            </tr>
        </thead>
        <tbody class="prcheckdue_list">  
            <?php foreach ($prlist as $list) : ?>
            <tr>                        
                <td width="20px"><input type="radio" name="blookuplist" class="blookuplist" value="<?php echo $list['pr_num'] ?>"></td>
                <td width="40px"><?php echo $list['pr_num'] ?></td>
                <td width="40px" class="span_limit"><?php echo $list['pr_cmf'].''.$list['pr_amf'] ?></td>                                    
                <td width="60px" class="span_limit"><?php echo $list['pr_payee'] ?></td>                                                         
                <td width="40px" style="text-align:right"><?php echo number_format($list['pr_amt'], 2, '.', ',') ?></td>    
                <td width="40px"><?php echo $list['ccf'] ?></td>                                                          
                <td width="40px"><?php echo $list['bank'] ?></td>                                                          
                <td width="40px"><?php echo $list['branch'] ?></td>                                                          
                <td width="100px" class="span_limit"><?php echo $list['pr_part'] ?></td>                                                                                
            </tr>
            <?php endforeach; ?>                                                                           
        </tbody>
    </table>
    <div class="clear"></div>
    </div>
</div>
<div class="row-form-booking" align="center">                            
    <div class="span2">
      <button class="btn btn-block" type="button" id="blookup_importpr" name="blookup_importpr">Import PR</button>
    </div>                           
    <div class="clear"></div>
</div>  

<script>
$("#blookup_importpr").click(function() {
    var prnum = $('.blookuplist:checked').val();
    if(typeof prnum != 'undefined') {
    window.location.href = "<?php echo base_url()?>payment/load_importpr/"+prnum;
    } else { alert("Select PR Number to load!"); return false;}
});
</script>