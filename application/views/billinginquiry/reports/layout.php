
              <?php $grand_rate = 0; ?>
              <?php $grand_prem = 0; ?>
              <?php $grand_disc = 0; ?>
              <?php $grand_size = 0; ?>
              <?php $grand_amount = 0; ?>
              <?php $switch = 1; ?>
              <?php $bg = "" ?>
       
              <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>  
              
               <?php if($switch == 1)
                {
                    $bg = "gradeC odd";
                    $switch = 2;
                } 
                else{  
                    $bg = "gradeC even";
                    $switch = 1; 
                }
                        
               ?>
                      
                      
                  <tr style="white-space: nowrap;"  class="<?php echo $bg ?>">
                    <td><?php echo $ctr+1 ?></td>     
                    <td style="text-align: center;cursor: pointer;" class="subtype_cell" cell_id = <?php echo $ctr; ?>  id="subtype_cell<?php echo $ctr ?>" ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>"><?php echo $result[$ctr]['sub_type'] ?></td>
                    <td style="text-align:center;" cell_id ="<?php echo $ctr; ?>">
                        <input type="text" style="width:70px;margin:0px;padding:0px;text-align: center;font-size:10;" cell_id ="<?php echo $ctr; ?>" value="<?php echo $result[$ctr]['sec'] ?>"  ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>" class="billing_section_text" id="sec<?php echo $ctr; ?>" >    
                    </td>
                    <td style="text-align: center;"><?php echo $result[$ctr]["dummy_section"] ?></td>
                    <td style="text-align: center;"><?php echo $result[$ctr]["d_pages"] ?></td>
                    <td style="text-align: center;" id="product_title_layout<?php echo $ctr; ?>" class="product_title_layout" ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>" cell_id="<?php echo $ctr; ?>"  >
                      <input type="text" style="margin:0px;padding:0px" class="product_title_text" id="prod_title<?php echo $ctr; ?>"  value="<?php echo $result[$ctr]["product_title"] ?>"  ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>"  > 
                    </td>
                    <td style="text-align: center;cursor: pointer;" class="ae_cell" cell_id = <?php echo $ctr; ?>  id="ae_cell<?php echo $ctr ?>" ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>" ><?php echo $result[$ctr]['AE'] ?></td>
                    <td><?php echo $result[$ctr]["advertiser"] ?></td>
                    <td style="text-align: center;cursor: pointer;" cell_id = <?php echo $ctr; ?> id="adtype_cell<?php echo $ctr ?>" class="adtype_cell" ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>"><?php echo $result[$ctr]['adtype_code'] ?></td>
                    <td><?php echo $result[$ctr]["agency"] ?></td>
                    <td style="text-align: right;"><?php  if(!empty($result[$ctr]["rate"])){ echo number_format($result[$ctr]["rate"],2,'.',','); }  ?></td>
                    <td style="text-align: right;"><?php  if(!empty($result[$ctr]["prempercent"])){ echo number_format($result[$ctr]["prempercent"],2,'.',','); }  ?></td>
                    <td style="text-align: right;"><?php  if(!empty($result[$ctr]["descpercent"])){ echo number_format($result[$ctr]["descpercent"],2,'.',','); }  ?></td>
                    <td style="text-align: center;"><?php echo $result[$ctr]["size"] ?></td>
                    <td style="text-align: right;"><?php  if(!empty($result[$ctr]["gross_amount"])){ echo number_format($result[$ctr]["gross_amount"],2,'.',','); }  ?></td>
                    <td style="text-align: right;"><?php  if(!empty($result[$ctr]["ccm"])){ echo number_format($result[$ctr]["ccm"],2,'.',','); }?></td>
                    <td style="text-align: center;"><?php  echo $result[$ctr]["color_code"] ?></td>
                    <td style="text-align: center;"><?php  echo $result[$ctr]["ao_num"] ?></td>
                    <td style="text-align: center;"><?php  echo $result[$ctr]["ao_ref"] ?></td>
                    <td style="text-align: center;"><?php echo $result[$ctr]["branch_code"] ?></td> 
                    <td style="text-align: center;"><?php echo $result[$ctr]["paytype_name"] ?></td>  
                    <td style="text-align: left;"><?php  echo $result[$ctr]["remarks"] ?></td>
                    <td style="text-align: center;"  cell_id = "<?php echo $ctr; ?>" >
                  
                    <input type="text" style="margin:0px;padding:0px" ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>" cell_id = "<?php echo $ctr; ?>" id='rem_text<?php echo $ctr; ?>' class="remarks_layout_text" value="<?php echo $result[$ctr]['billing_remarks'] ?>">
                       
                   </td>
          
                    </tr>
                    
                    <input type="hidden" name="adtype[]" id="adtype<?php  echo $ctr ?>" >  
                    <input type="hidden" name="ae[]" id="ae<?php  echo $ctr ?>" >  
                    <input type="hidden" name="billing_section[]" id="billing_section<?php echo $ctr ?>" >    
                    <input type="hidden" name="product_title[]" id="product_title<?php  echo $ctr ?>" value="<?php echo $result[$ctr]["product_title"].":".$result[$ctr]["ao_p_id"]; ?>">
                    <input type="hidden" name="folio_number[]" id="folio_number<?php  echo $ctr ?>" > 
                    <input type="hidden" name="subtype[]" id="subtype<?php echo $ctr ?>" >
                    <input type="hidden" name="remarks[]" id="remarks<?php echo $ctr ?>" value="<?php echo $result[$ctr]["billing_remarks"].":".$result[$ctr]["ao_p_id"]; ?>" > 
          
                              <?php $grand_rate   += $result[$ctr]["rate"]; ?>
                              <?php $grand_prem   += $result[$ctr]["prempercent"]; ?>
                              <?php $grand_disc   += $result[$ctr]["descpercent"]; ?>
                              <?php $grand_size   += $result[$ctr]["ccm"]; ?>
                              <?php $grand_amount += $result[$ctr]["gross_amount"]; ?>
                         
                         <?php } ?>
                         
                         
                         <?php  if(count($result)>0) { ?>
                         
                            <tr style="background-color: gray;height:2em;">
                             
                                <td colspan="9" style="text-align: right;vertical-align:middle;background-color:#B22222;"><b>GRAND TOTAL</b></td>
                                <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_rate,2,'.',',') ?></b></td>
                                <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_prem,2,'.',',') ?></b></td>
                                <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_disc,2,'.',',') ?></b></td>
                                <td style="vertical-align:middle;background-color:#B22222;"></td> 
                                <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_amount,2,'.',',') ?></b></td>
                                <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_size,2,'.',',') ?></b></td>
                                <td colspan="9" style="vertical-align:middle;background-color:#B22222;"></td>
                             
                            </tr>
                                  
                         <?php } else { ?>
                         
                                <tr style="height:3em">
                                
                                    <td colspan="24" style="text-align:center">NO RESULTS FOUND</td>
                                
                                </tr>

                         <?php } ?>

                         </form>
                         

<script>
    $(function() {
        
        
        $('.billing_section_text').keyup(function() {
            if (this.value.match(/[^a-zA-Z0-9]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
            }
        });
        
        $('input:text:first').focus();
        //$('.billing_section_text').mask('** ');
        var $inp = $('.billing_section_text');
        $inp.bind('keydown', function(e) {
        //var key = (e.keyCode ? e.keyCode : e.charCode);
        var key = e.which;
        if (key == 13) {
            e.preventDefault();
            var nxtIdx = $inp.index(this) + 1;
            $(".billing_section_text:eq(" + nxtIdx + ")").focus();
            }
        });
        
        var $inp2 = $('.product_title_text');
        $inp2.bind('keydown', function(e) {
        //var key = (e.keyCode ? e.keyCode : e.charCode);
        var key = e.which;
        if (key == 13) {
            e.preventDefault();
            var nxtIdx = $inp2.index(this) + 1;
            $(".product_title_text:eq(" + nxtIdx + ")").focus();
            }
        });
        
        
        var $inp3 = $('.remarks_layout_text');
        $inp3.bind('keydown', function(e) {
        //var key = (e.keyCode ? e.keyCode : e.charCode);
        var key = e.which;
        if (key == 13) {
            e.preventDefault();
            var nxtIdx = $inp3.index(this) + 1;
            $(".remarks_layout_text:eq(" + nxtIdx + ")").focus();
            }
        });

    });

</script>