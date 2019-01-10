

<div class="block-fluid table-sorting">    
    <div class="block-fluid table-sorting">
        <table cellpadding="0" cellspacing="0" width="100%" class="table" id="mSortable1">
            <thead>
            <tr>                       
               <th width="2%">#</th>
               <th width="2%"><input type='checkbox' style='width:20px;' name='chckx' class='chckx' value='all'></th>
               <th width="6%">AO Number</th>
               <th width="6%">Issue Date</th>                                                              
               <th width="6%">Paginated Date</th> 
               <th width="5%">Agency Code</th> 
               <th width="10%">Agency Name</th> 
               <th width="5%">Client Code</th> 
               <th width="10%">Client Name</th> 
               <th width="5%">PO Number</th> 
               <th width="5%">AE</th> 
               <th width="5%">Branch</th> 
            </tr>
            </thead>
            <tbody>
            <?php
            if (empty($tempInvoice)) { ?>
                 <tr>
                    <td>No</td>        
                    <td>Record<?php #echo $temp['ao_num']?></td>                
                    <td> <?php #echo $temp['ao_issuefrom']?></td>                
                    <td> <?php #echo $temp['ao_paginated_date']?></td>                
                    <td> <?php #echo $temp['ao_amf']?></td>                
                    <td> <span><?php #echo $temp['agencyname']?></span></td>                
                    <td> <?php #echo $temp['ao_cmf']?></td>                
                    <td> <span><?php #echo $temp['ao_payee']?></span></td>                
                    <td> <?php #echo $temp['ao_ref']?></td>                
                    <td> <?php #echo $temp['username']?></td>                
                    <td> <?php #echo $temp['branch_code']?></td>                
                </tr>
            <?php    
            } else { 
                $counter = 1;   $color = "";
                foreach ($tempInvoice as $temp) { 
                    if ($temp['ao_amt'] == '0.00') { $color = "style='background-color: red;'";}  else { $color = "";    }
                    ?>
                <tr>
                    <td <?php echo $color; ?>><?php echo $counter ?></td>  
                    <td <?php echo $color; ?>><input type='checkbox' style='width:20px;' name='chck[]' class='chck' id='chck[]' value='<?php echo $temp['id'] ?>'></td>                  
                    <td <?php echo $color; ?>><?php echo $temp['ao_num']?></td>                
                    <td <?php echo $color; ?>><?php echo $temp['ao_issuefrom']?></td>                
                    <td <?php echo $color; ?>><?php echo $temp['ao_paginated_date']?></td>                
                    <td <?php echo $color; ?>><?php echo $temp['ao_amf']?></td>                
                    <td <?php echo $color; ?>><span><?php echo $temp['agencyname']?></span></td>                
                    <td <?php echo $color; ?>><?php echo $temp['ao_cmf']?></td>                
                    <td <?php echo $color; ?>><span><?php echo $temp['ao_payee']?></span></td>                
                    <td <?php echo $color; ?>><?php echo $temp['ao_ref']?></td>                
                    <td <?php echo $color; ?>><?php echo $temp['aename']?></td>                
                    <td <?php echo $color; ?>><?php echo $temp['branch_code']?></td>                
                </tr>
                <?php
                $counter += 1;
                }    
            }
            ?>
            
            </tbody>
        </table>
        <div class="clear"></div>
    </div>     
    <div class="clear"></div>
</div>   

<script> 
$(document).ready(function() {
    $('.chckx').click(function(){
        var ischeck = $(this).attr('checked');
        var value = $(this).val();    
        
        if (ischeck == 'checked') {
            if (value == 'all') {
                $('.chck').attr('checked', 'checked');
            }
        } else {           
            if (value == 'all') {
                $('.chck').removeAttr('checked');
            }
        }
    });

    /*$('#mSortable1').dataTable( {
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,
    } ); */
} );
</script>  

<!--<tr>
    <td style='width:20px;'><input type='checkbox' style='width:20px;' name='chck[]' class='chck' id='chck[]' value='<?php echo $temp['id'] ?>'></td>        
    <td style='width:75px;'><?php #echo $temp['ao_num']?></td>                
    <td style='width:100px;'><?php #echo $temp['ao_issuefrom']?></td>                
    <td style='width:100px;'><?php #echo $temp['ao_paginated_date']?></td>                
    <td style='width:80px;'><?php #echo $temp['ao_amf']?></td>                
    <td style='width:150px;'><span><?php #echo $temp['agencyname']?></span></td>                
    <td style='width:80px;'><?php #echo $temp['ao_cmf']?></td>                
    <td style='width:150px;'><span><?php #echo $temp['ao_payee']?></span></td>                
    <td style='width:150px;'><?php #echo $temp['ao_ref']?></td>                
    <td style='width:150px;'><?php #echo $temp['username']?></td>                
    <td style='width:150px;'><?php #echo $temp['branch_code']?></td>                
</tr>-->

