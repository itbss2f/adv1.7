<script type="text/javascript">   
$('#myTable').fixedHeaderTable({ 
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
<form action="<?php echo site_url('billinginquiries/savelayout') ?>" id="myform" method="post">     
<input type='hidden' name='inquiry_type' id='inquiry_type' value='<?php echo $inquiry_type ?>'>  
<table id="myTable" class="fancyTable" cellpadding="0" cellspacing="0" >
<thead>
        <tr style="text-align: center;font-size:10px;white-space: nowrap;">
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
              
              <th><span style="width:<?php echo $width; ?>"> <?php echo $field_name_column_text[$ctr] ?></span></th>  
              
              <?php } ?>
                                                                                     
        </tr>

</thead>
 
<tbody>

<?php $grand_rate = 0; ?>
<?php $grand_prem = 0; ?>
<?php $grand_disc = 0; ?>
<?php $grand_size = 0; ?>
<?php $grand_amount = 0; ?>
<?php $trstyle = 'background-color: #dfdfdf;' ?>
<?php $text_align = "text-align:center";   ?>  

<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>  
  
       <tr style="white-space: nowrap;" >     
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
            
             <?php if($field_name_column_sort[$i] == 'sub_type') { ?>
                <td style="text-align: center;cursor: pointer;" class="subtype_cell" cell_id = <?php echo $ctr; ?>  id="subtype_cell<?php echo $ctr ?>" ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>"><?php echo $result[$ctr]['sub_type'] ?></td>
             <?php } ?>
             
             <?php if($field_name_column_sort[$i] == 'section') { ?> 
                <td style="text-align:center;" id="billing_section_layout<?php echo $ctr; ?>" class="billing_section_layout" cell_id = "<?php echo $ctr; ?>" ao_p_id = "<?php echo $result[$ctr]['ao_p_id'] ?>"></td>
             <?php } ?> 
             
             <?php if($field_name_column_sort[$i] == 'product_title') { ?>
               <td style="text-align: center;" id="product_title_layout<?php echo $ctr; ?>" class="product_title_layout" cell_id = "<?php echo $ctr; ?>" ao_p_id = "<?php echo $result[$ctr]['ao_p_id'] ?>" ><?php if($result[$ctr]['is_bill_rem_update'] == "1"){ echo $result[$ctr]["product_title"];  }?></td>
             <?php } ?>
             
             <?php if($field_name_column_sort[$i] == 'account_executive') { ?>
                <td style="text-align: center;cursor: pointer;" class="ae_cell" cell_id = <?php echo $ctr; ?>  id="ae_cell<?php echo $ctr ?>" ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>" ><?php echo $result[$ctr]['AE'] ?></td>
             <?php } ?>
             
             <?php if($field_name_column_sort[$i] == 'ad_type') { ?>
                <td style="text-align: center;cursor: pointer;" cell_id = <?php echo $ctr; ?> id="adtype_cell<?php echo $ctr ?>" class="adtype_cell" ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>"><?php echo $result[$ctr]['adtype_code'] ?></td>
              <?php } ?> 
             
             <?php if($field_name_column_sort[$i] == 'billing_remarks') { ?>
                 <td style="text-align: center;"  id="remarks_layout<?php echo $ctr; ?>" class="remarks_layout" cell_id = "<?php echo $ctr; ?>" ao_p_id = "<?php echo $result[$ctr]['ao_p_id'] ?>"></td>
             <?php } ?>
             
              <?php if( $field_name_column_sort[$i] != 'billing_remarks' and $field_name_column_sort[$i] != 'ad_type' and $field_name_column_sort[$i] != 'account_executive' and $field_name_column_sort[$i] != 'product_title' and $field_name_column_sort[$i] != 'section' and $field_name_column_sort[$i] != 'sub_type' and $field_name_column_sort[$i] != 'section') {?>
            
              <td style="<?php echo $text_align  ?>"><?php if(is_numeric($result[$ctr]["$field_name_column_sort[$i]"]) and $field_name_column_sort[$i] != 'po_number' and $field_name_column_sort[$i] !='ao_number' ){ echo number_format($result[$ctr]["$field_name_column_sort[$i]"],2,'.',','); } else { echo $result[$ctr]["$field_name_column_sort[$i]"]; }  ?></td>
              
              <?php } ?>
              
              <?php } ?>
        </tr>
        
      <?php $grand_rate   += $result[$ctr]["rate"]; ?>
      <?php $grand_prem   += $result[$ctr]["premium"]; ?>
      <?php $grand_disc   += $result[$ctr]["discount"]; ?>
      <?php $grand_size   += $result[$ctr]["ccm"]; ?>
      <?php $grand_amount += $result[$ctr]["gross_amount"]; ?>
      
      <input type="hidden" name="adtype[]" id="adtype<?php  echo $ctr ?>" >  
    <input type="hidden" name="ae[]" id="ae<?php  echo $ctr ?>" >  
    <input type="hidden" name="product_title[]" id="product_title<?php  echo $ctr ?>" value="<?php if($result[$ctr]['is_bill_rem_update'] == "1"){ echo $result[$ctr]["product_title"].":".$result[$ctr]["ao_p_id"];  }  ?>" >
    <input type="hidden" name="billing_section[]" id="billing_section<?php echo $ctr ?>" > 
    <input type="hidden" name="folio_number[]" id="folio_number<?php  echo $ctr ?>" > 
    <input type="hidden" name="subtype[]" id="subtype<?php echo $ctr ?>" >
    <input type="hidden" name="remarks[]" id="remarks<?php echo $ctr ?>" value="<?php if($result[$ctr]['is_bill_rem_update'] == "0" ){ echo $result[$ctr]["product_title"].":".$result[$ctr]["ao_p_id"];  } else { echo $result[$ctr]["billing_remarks"].":".$result[$ctr]["ao_p_id"]; } ?>" > 
                                  
       
   <?php }  ?>
    
     <tr style="background-color: gray;height:2em;">
 
    <?php for($i=0;$i<count($field_name_column_sort);$i++) { ?> 
            
                <?php 
                        
                        switch($field_name_column_sort[$i])
                        {
                            case "rate": 
                            ?>
                            
                            <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_rate,2,'.',',') ?></b></td>    
                
                         <?php
                             break;
                            
                            case "premium":
                          ?>  
                         
                            <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_prem,2,'.',',') ?></b></td>    
                         
                          <?php 
                            break;

                            case "discount":
                           ?>
                             <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_disc,2,'.',',') ?></b></td>    
                         
                          <?php     
                            break;

                            case "gross_amount":
                          ?>
                            <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_amount,2,'.',',') ?></b></td>    
                        
                        <?php    
                            break;

                            case "ccm":
                         ?>
                            <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_size,2,'.',',') ?></b></td>    
                          <?php    
                            break;
                            
                            default;
                              ?>
                                 <td style="vertical-align:middle;background-color:#B22222;"></td>
                              <?php 
                            break;
                        }
                
                ?>
                                               
            <?php } ?>   
 
                             
     </tr>

    </tbody>
    
</table>   

      
 </form>
</div>
</div>
</div>
    

<script>

$('.billing_section_text').hide();
$('.product_title_text').hide();

$(".remarks_layout").die().live("dblclick",function()
{   
$ht = $(this).parent('tr').find('.remarks_layout').text();
                              
$(this).contents().first().remove();

$cell_id = $(this).attr('cell_id'); 

$ao_p_id = $(this).attr('ao_p_id'); 
       
$(this).html("<input type='text' class='remarks_layout_text' id='rem_text"+$cell_id+"' cell_id = '"+$cell_id+"' ao_p_id='"+$ao_p_id+"' value='"+$ht+"' > "); 
 
$("#rem_text"+$cell_id).focus();                           
 
});



$(".remarks_layout_text").die().live("blur",function(){

 $cell_id = $(this).parent('td').attr('cell_id');
 
 $ao_p_id = $(this).attr('ao_p_id'); 

 $(this).css('display','none');
 
 $("#remarks"+$cell_id).val($(this).val()+" : "+$ao_p_id);  

 $("#remarks_layout"+$cell_id).html($(this).val());
  
}); 

$(".product_title_layout").die().live("dblclick",function()
{   
$ht = $(this).parent('tr').find('.product_title_layout').text();
                              
$(this).contents().first().remove();

$cell_id = $(this).attr('cell_id'); 

$ao_p_id = $(this).attr('ao_p_id'); 
       
$(this).html("<input type='text' class='product_title_text' id='prod_title"+$cell_id+"' cell_id = '"+$cell_id+"' ao_p_id='"+$ao_p_id+"' value='"+$ht+"' > "); 
 
$("#prod_title"+$cell_id).focus();                           
 
});

$(".product_title_text").die().live("blur",function(){

 $cell_id = $(this).parent('td').attr('cell_id');
 
 $ao_p_id = $(this).attr('ao_p_id'); 

 $(this).css('display','none');
 
 $("#product_title"+$cell_id).val($(this).val()+" : "+$ao_p_id);  

 $("#product_title_layout"+$cell_id).html($(this).val());
  
}); 


$(".billing_section_layout").die().live("dblclick",function()
{   
$ht = $(this).html();

$(this).contents().first().remove();

$cell_id = $(this).attr('cell_id'); 

$ao_p_id = $(this).attr('ao_p_id');

$(this).html("<input type='text' style='width:70px' class='billing_section_text' id='sec"+$cell_id+"' cell_id = '"+$cell_id+"' ao_p_id='"+$ao_p_id+"' value='"+$ht+"' > "); 

$("#sec"+$cell_id).focus();    

});

$(".billing_section_text").die().live("blur",function(){

   $cell_id = $(this).parent('td').attr('cell_id'); 
    
   $ao_p_id = $(this).attr('ao_p_id');    

   $("#sec"+$cell_id).css('display','none');
   
   $("#billing_section"+$cell_id).val($(this).val()+" : "+$ao_p_id);       
   
   $("#billing_section_layout"+$cell_id).html($(this).val());  
});

$(".adtype_cell").die().live("click",function(){

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
                           
                              $("#myTable").find("#adtype_cell"+$cell_id).html($new_select);  
                           
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

$(".ae_cell").die().live("click",function(){

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
                           
                              $("#myTable").find("#ae_cell"+$cell_id).html($new_select);  
                           
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

$(".subtype_cell").die().live("click",function(){

$cell_id = $(this).attr('cell_id');

$ao_p_id = $(this).attr('ao_p_id');  

$current_select = $(this).html().trim(); 
 
$.ajax({
    type:'post',                    
    url:'<?php echo site_url('billinginquiry/generate_select') ?>',         
    data:{type:'subtype',current_select:$current_select,ao_p_id:$ao_p_id,cell_id:$cell_id},                  
    success : function(response)       
    {
      $res = $.parseJSON(response);  
      
      var options = {
                      buttons: {
                            Submit: function () {   
                              $new_select = $("#mysubtype"+$cell_id+" option:selected").text();     
                           
                              $("#myTable").find("#subtype_cell"+$cell_id).html($new_select);  
                           
                              $(this).dialog('close');
                            },
                             Close: function () {
                              
                              $(this).dialog('close');
                                
                            }
                        }
                        
                      };                            
            
     $se_d.dialog('option', options);
     $se_d.dialog('option', 'title', 'Sub Type');    
     $se_d.dialog('open').html($res['html']);  
     
    }
});

//
});


</script>

