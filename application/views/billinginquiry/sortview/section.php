
<script>
$('#myTable2').fixedHeaderTable({ 
footer: true,
//  cloneHeadToFoot: true,
altClass: 'odd',
autoShow: true,
width: '1085',
height: '630'
///  fixedColumn:2,  

});

</script> 

<div class="row-fluid">
<div class="span12">
<div class="block-fluid" style="height:600px;">

<form action="<?php echo site_url('billinginquiries/savesection') ?>" id="myform" method="post">


         <input type='hidden' name='inquiry_type' id='inquiry_type' value='<?php echo $inquiry_type ?>'>

        <table class="fancyTable" id="myTable2" cellpadding="0" cellspacing="0">
            <thead>
                    <tr style="text-align: center;font-size:10px;white-space:nowrap;">
                    
                         <?php for($ctr=0;$ctr<count($field_name_column_text);$ctr++) { ?>
                         
                            <?php
                          
                            $width = "5em";
                                 
                            $width100 = array("Sub Type","Ad Type","Page #",
                                              "Rate","Premium","Discount",
                                              "Size","Gross Amount","CCM",
                                              "Color","Ao Number.","Po Number",
                                              "Status","Branch","Pay Type",
                                              "OR Number","Account Executive");
                            $width150 = array("Section","Dummy Section");  
                                
                            $width200 = array("Agency","Production Remarks","Remarks","Agency","Product Title"); 

                            
                            $right    = array("Rate","Premium","Discount","Size","Gross Amount","CCM");
                                          
                            $left     = array("Production Remarks","Agency","Advertiser",);              
                             
                            $center   = array("Sub Type","Ad Type","Section","Dummy Section","Color","Ao Number.","Po Number",
                                           "Status","Branch","Pay Type","Or Number","Account Executive","Product Title",);  

                            if(in_array($field_name_column_text[$ctr],$width100))  
                            {
                                 $width = "5em"; 
                            } 
                            if(in_array($field_name_column_text[$ctr],$width150))  
                            {
                                 $width = "7em"; 
                            } 

                            
                            if(in_array($field_name_column_text[$ctr],$width200)) 
                            {
                                 $width = "10em"; 
                            }

                          ?>
                          
                          <th style="<?php echo $width ?>" ><?php echo $field_name_column_text[$ctr] ?></th>  
                          
                          <?php } ?>
                                                                                                 
                    </tr>

            </thead>
             
            <tbody>
            
          <?php $grand_rate = 0; ?>
          <?php $grand_prem = 0; ?>
          <?php $grand_disc = 0; ?>
          <?php $grand_size = 0; ?>
          <?php $grand_amount = 0; ?>
          
          <?php $sub_rate   = 0; ?>
          <?php $sub_prem   = 0; ?>
          <?php $sub_disc   = 0; ?>
          <?php $sub_size   = 0; ?>
          <?php $sub_amount = 0; ?>
          
          <?php $trstyle = "background-color: #dfdfdf;" ?>
          <?php $text_align = "text-align:center";   ?>  
          <?php $section = "";  ?> 
          
       
          <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>  
              
              <?php if($trstyle != 'background-color: #dfdfdf;') { ?>
            
                <?php $trstyle = "background-color: #dfdfdf;" ?>      
            
             <?php } else {?>
            
                <?php $trstyle = "background-color: #efefef" ?>      
            
             <?php } ?>
       
             <?php if( $result[$ctr]["section"] != $section and $ctr != 0) { ?>
             
                    <tr style="height:2em;vertical-align:middle;white-space:nowrap;">
                     
                        <?php for($i=0;$i<count($field_name_column_sort);$i++) { ?> 
                        
                            <?php 
                                    
                                    switch($field_name_column_sort[$i])
                                    {
                                        case "rate":
                                        ?>
                                        
                                        <td style="text-align: right;"><b>[<?php echo $section ?>] <?php echo number_format($sub_rate,2,'.',',') ?></b></td>    
                            
                                     <?php
                                         break;
                                        
                                        case "premium":
                                      ?>  
                                     
                                        <td style="text-align: right;"><b>[<?php echo $section ?>]<?php echo number_format($sub_prem,2,'.',',') ?></b></td>    
                                     
                                      <?php 
                                        break;
 
                                        case "discount":
                                       ?>
                                         <td style="text-align: right;"><b>[<?php echo $section ?>] <?php echo number_format($sub_disc,2,'.',',') ?></b></td>    
                                     
                                      <?php     
                                        break;

                                        case "gross_amount":
                                      ?>
                                        <td style="text-align: right;"><b>[<?php echo $section ?>] <?php echo number_format($sub_amount,2,'.',',') ?></b></td>    
                                    
                                    <?php    
                                        break;

                                        case "ccm":
                                     ?>
                                        <td style="text-align: right;"><b>[<?php echo $section ?>] <?php echo number_format($sub_size,2,'.',',') ?></b></td>    
                                      <?php    
                                        break;
                                        
                                        default;
                                          ?>
                                             <td></td>
                                          <?php 
                                        break;
                                    }
                            
                            ?>
                                                           
                        <?php } ?>   
                  
                     </tr>
                     
                     <?php $sub_rate   = 0; ?>
                     <?php $sub_prem   = 0; ?>
                     <?php $sub_disc   = 0; ?>
                     <?php $sub_size   = 0; ?>
                     <?php $sub_amount = 0; ?>
                     
                                
                   <?php } ?>  
                   
                   <?php $section = $result[$ctr]["section"] ?>   
            
                   <tr style="white-space:nowrap;" >     
                          <?php for($i=0;$i<count($field_name_column_sort);$i++) { ?>
                          <?php
                                     if(in_array($field_name_column_text[$i],$right))
                                    {
                                         $text_align = "text-align:right"; 
                                    }  
                                    if(in_array($field_name_column_text[$i],$left))
                                    {
                                         $text_align = "text-align:left"; 
                                    }  
                                    if(in_array($field_name_column_text[$i],$center))
                                    {
                                         $text_align = "text-align:center"; 
                                    }     
                            ?> 
                             
                         <?php if($field_name_column_sort[$i] == 'account_executive') { ?> 
                         
                            <td style="text-align: center;cursor: pointer;" class="ae_cell_section" cell_id = <?php echo $ctr; ?>  id="ae_cell_section<?php echo $ctr ?>" ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>"><?php echo $result[$ctr]['AE'] ?></td>
                         
                         <?php } ?>
                         
                         <?php if($field_name_column_sort[$i] == 'ad_type') { ?>
                            
                             <td style="text-align: center;cursor: pointer;" cell_id = <?php echo $ctr; ?> id="adtype_cell_section<?php echo $ctr ?>" class="adtype_cell_section" ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>"><?php echo $result[$ctr]['adtype_code'] ?></td>
                          
                         <?php } ?> 
                          
                          <?php if( $field_name_column_sort[$i] != 'ad_type' and $field_name_column_sort[$i] != 'account_executive' ) {?>
                                    
                              <td style="<?php echo $text_align  ?>"><?php if(is_numeric($result[$ctr]["$field_name_column_sort[$i]"]) and $field_name_column_sort[$i] !='ao_number' and $field_name_column_sort[$i] !='po_number'){ echo number_format($result[$ctr]["$field_name_column_sort[$i]"],2,'.',',');}else{ echo $result[$ctr]["$field_name_column_sort[$i]"];} ?></td>
                          
                          <?php } ?>
                          
                          <?php } ?>
                    </tr>
                    
                 <?php $sub_rate   += $result[$ctr]["rate"]; ?>
                 <?php $sub_prem   += $result[$ctr]["premium"]; ?>
                 <?php $sub_disc   += $result[$ctr]["discount"]; ?>
                 <?php $sub_size   += $result[$ctr]["ccm"]; ?>
                 <?php $sub_amount += $result[$ctr]["gross_amount"]; ?>   
                        
                    
                  <?php $grand_rate   += $result[$ctr]["rate"]; ?>
                  <?php $grand_prem   += $result[$ctr]["premium"]; ?>
                  <?php $grand_disc   += $result[$ctr]["discount"]; ?>
                  <?php $grand_size   += $result[$ctr]["ccm"]; ?>
                  <?php $grand_amount += $result[$ctr]["gross_amount"]; ?>
                   
               <?php }  ?>
               
               <tr style="height:2em;vertical-align:middle;">
             
                    <?php for($i=0;$i<count($field_name_column_sort);$i++) { ?> 
                        
                            <?php 
                                    
                                    switch($field_name_column_sort[$i])
                                    {
                                        case "rate":
                                        ?>
                                        
                                        <td style="text-align: right;background-color: #B22222;vertical-align:middle;"><b>[<?php echo $section ?>] <?php echo number_format($sub_rate,2,'.',',') ?></b></td>    
                            
                                     <?php
                                         break;
                                        
                                        case "premium":
                                      ?>  
                                     
                                        <td style="text-align: right;background-color: #B22222;vertical-align:middle;"><b>[<?php echo $section ?>]<?php echo number_format($sub_prem,2,'.',',') ?></b></td>    
                                     
                                      <?php 
                                        break;
 
                                        case "discount":
                                       ?>
                                         <td style="text-align: right;background-color: #B22222;vertical-align:middle;"><b>[<?php echo $section ?>] <?php echo number_format($sub_disc,2,'.',',') ?></b></td>    
                                     
                                      <?php     
                                        break;

                                        case "gross_amount":
                                      ?>
                                        <td style="text-align: right;background-color: #B22222;vertical-align:middle;"><b>[<?php echo $section ?>] <?php echo number_format($sub_amount,2,'.',',') ?></b></td>    
                                    
                                    <?php    
                                        break;

                                        case "ccm":
                                     ?>
                                        <td style="text-align: right;background-color: #B22222;vertical-align:middle;"><b>[<?php echo $section ?>] <?php echo number_format($sub_size,2,'.',',') ?></b></td>    
                                      <?php    
                                        break;
                                        
                                        default;
                                          ?>
                                             <td style="background-color:  #B22222;vertical-align:middle;"></td>
                                          <?php 
                                        break;
                                    }
                            
                            ?>
                                                           
                        <?php } ?>                                           
          
              </tr>
               
                 <tr style="height:2em;vertical-align:middle;">
             
                            <?php for($i=0;$i<count($field_name_column_sort);$i++) { ?> 
                        
                            <?php 
                                    
                                    switch($field_name_column_sort[$i])
                                    {
                                        case "rate":
                                        ?>
                                        
                                        <td style="text-align: right;background-color:#B22222;vertical-align:middle;"><b><?php echo number_format($grand_rate,2,'.',',') ?></b></td>    
                            
                                     <?php
                                         break;
                                        
                                        case "premium":
                                      ?>  
                                     
                                        <td style="text-align: right;background-color:#B22222;vertical-align:middle;"><b><?php echo number_format($grand_prem,2,'.',',') ?></b></td>    
                                     
                                      <?php 
                                        break;
 
                                        case "discount":
                                       ?>
                                         <td style="text-align: right;background-color:#B22222;vertical-align:middle;"><b><?php echo number_format($grand_disc,2,'.',',') ?></b></td>    
                                     
                                      <?php     
                                        break;

                                        case "gross_amount":
                                      ?>
                                        <td style="text-align: right;background-color:#B22222;vertical-align:middle;"><b><?php echo number_format($grand_amount,2,'.',',') ?></b></td>    
                                    
                                    <?php    
                                        break;

                                        case "ccm":
                                     ?>
                                        <td style="text-align: right;background-color:#B22222;vertical-align:middle;"><b><?php echo number_format($grand_size,2,'.',',') ?></b></td>    
                                      <?php    
                                        break;
                                        
                                        default;
                                          ?>
                                             <td style="background-color:#B22222;"></td>
                                          <?php 
                                        break;
                                    }
                            
                            ?>
                                                           
                        <?php } ?>   
             
             </tr>
           
            </tbody>
        </table>
        
             </div>       
        <div class="clear"></div>
        </div>
</form>           






    
    

<script>


  $(".adtype_cell_section").die().live("click",function(){

$cell_id = $(this).attr('cell_id');

$ao_p_id = $(this).attr('ao_p_id');  

$current_select = $(this).html().trim(); 
 
$.ajax({
    type:'post',                    
    url:'<?php echo site_url('billinginquiry/generate_select') ?>',         
    data:{type:'adtype',current_select:$current_select,ao_p_id:$ao_p_id,cell_id:$cell_id},                  
    success : function(response)       
    {
      $res = $.parseJSON(response);  
      
      var options = {
                      buttons: {
                            Submit: function () {   
                              $new_select = $("#myadtype"+$cell_id+" option:selected").text();     
                           
                              $("#myTable2").find("#adtype_cell_section"+$cell_id).html($new_select);  
                           
                              $(this).dialog('close');
                            },
                             Close: function () {
                              
                              $(this).dialog('close');
                                
                            }
                        }
                        
                      };                            
            
     $se_d.dialog('option', options);
     $se_d.dialog('option', 'title', 'Ad Type');    
     $se_d.dialog('open').html($res['html']);  
     
    }
});

//
});

$(".ae_cell_section").die().live("click",function(){

$cell_id = $(this).attr('cell_id');

$ao_p_id = $(this).attr('ao_p_id');  

$current_select = $(this).html().trim(); 
 
$.ajax({
    type:'post',                    
    url:'<?php echo site_url('billinginquiry/generate_select') ?>',         
    data:{type:'ae',current_select:$current_select,ao_p_id:$ao_p_id,cell_id:$cell_id},                  
    success : function(response)       
    {
      $res = $.parseJSON(response);  
      
      var options = {
                      buttons: {
                            Submit: function () {   
                              $new_select = $("#myae"+$cell_id+" option:selected").text();     
                           
                              $("#myTable2").find("#ae_cell_section"+$cell_id).html($new_select);  
                           
                              $(this).dialog('close');
                            },
                             Close: function () {
                              
                              $(this).dialog('close');
                                
                            }
                        }
                        
                      };                            
            
     $se_d.dialog('option', options);
     $se_d.dialog('option', 'title', 'Account Exec.');    
     $se_d.dialog('open').html($res['html']);  
     
    }
});

//
});


</script> 

