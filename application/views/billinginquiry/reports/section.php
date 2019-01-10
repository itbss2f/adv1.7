
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
              
              <?php $section = 0; ?>
              
              <?php $trstyle = "odd" ?>
              
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
                                          
                <?php if( $result[$ctr]["sec"] != $section and $ctr != 0 ) { ?>      
                 
                <tr style="white-space: nowrap;" >
                 
                    <td colspan="9" style="text-align: right;vertical-align:middle;background-color:#B22222;"><b>SUB TOTAL <?php echo $section ?></b></td>
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($sub_rate,2,'.',',') ?></b></td>
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($sub_prem,2,'.',',') ?></b></td>
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($sub_disc,2,'.',',') ?></b></td>
                    <td style="background-color:#B22222;"></td> 
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($sub_size,2,'.',',') ?></b></td>
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($sub_amount,2,'.',',') ?></b></td>
                    <td colspan="9" style="background-color:#B22222;"></td>
              
                 </tr>
                 
                 <?php $sub_rate   = 0; ?>
                 <?php $sub_prem   = 0; ?>
                 <?php $sub_disc   = 0; ?>
                 <?php $sub_size   = 0; ?>
                 <?php $sub_amount = 0; ?>
                 
                 <?php } ?>  
                 
                 <?php $section = $result[$ctr]["sec"] ?>   
                
                <tr style="white-space: nowrap;" class="<?php echo $bg ?>">
                        <td style="text-align: center;"><?php echo $result[$ctr]['sub_type'] ?></td>
                        <td style="text-align: center;"><?php  echo $result[$ctr]["sec"] ?></td>
                        <td style="text-align: center;"><?php echo $result[$ctr]["dummy_section"] ?></td>
                        <td style="text-align: center;"><?php echo $result[$ctr]["d_pages"] ?></td>
                        <td><?php echo $result[$ctr]['product_title'] ?></td>
                        <td style="text-align: center;cursor: pointer;" class="ae_cell_section" cell_id = <?php echo $ctr; ?>  id="ae_cell_section<?php echo $ctr ?>" ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>"><?php echo $result[$ctr]['AE'] ?></td>
                        <td><?php echo $result[$ctr]["advertiser"] ?></td>
                        <td style="text-align: center;cursor: pointer;" cell_id = <?php echo $ctr; ?> id="adtype_cell_section<?php echo $ctr ?>" class="adtype_cell_section" ao_p_id="<?php echo $result[$ctr]['ao_p_id'] ?>"><?php echo $result[$ctr]['adtype_code'] ?></td>
                        <td><?php echo $result[$ctr]["agency"] ?></td>
                        <td style="text-align: right;text-indent:10px"><?php  if(!empty($result[$ctr]["rate"])){ echo number_format($result[$ctr]["rate"],2,'.',','); }  ?></td>
                        <td style="text-align: right;"><?php  if(!empty($result[$ctr]["prempercent"])){ echo number_format($result[$ctr]["prempercent"],2,'.',','); }  ?></td>
                        <td style="text-align: right;"><?php  if(!empty($result[$ctr]["descpercent"])){ echo number_format($result[$ctr]["descpercent"],2,'.',','); }  ?></td>
                        <td style="text-align: center;"><?php echo $result[$ctr]["size"] ?></td>
                        <td style="text-align: right;"><?php  if(!empty($result[$ctr]["ccm"])){ echo number_format($result[$ctr]["ccm"],2,'.',','); }?></td>
                        <td style="text-align: right;"><?php  if(!empty($result[$ctr]["gross_amount"])){ echo number_format($result[$ctr]["gross_amount"],2,'.',','); }  ?></td>
                        <td style="text-align: center;"><?php  echo $result[$ctr]["color_code"] ?></td>
                        <td style="text-align: center;"><?php  echo $result[$ctr]["ao_num"] ?></td>
                        <td style="text-align: center;"><?php  echo $result[$ctr]["ao_ref"] ?></td>
                        <td style="text-align: center"><?php   echo $result[$ctr]["status"] ?></td>
                        <td style="text-align: center;"><?php echo $result[$ctr]["branch_code"] ?></td> 
                        <td style="text-align: center;"><?php echo $result[$ctr]["paytype_name"] ?></td>  
                        <td style="text-align: center;"><?php echo $result[$ctr]["invoice_number"] ?></td> 
                        <td style="text-align: left;"><?php  echo $result[$ctr]["remarks"] ?></td>
                        <td><?php echo $result[$ctr]["billing_remarks"] ?></td>
                        

                 </tr>    
                 
                  <input type="hidden" name="adtype[]" id="adtype<?php  echo $ctr ?>" >  
                  <input type="hidden" name="ae[]" id="ae<?php  echo $ctr ?>" >
                  
                     <?php $sub_rate      += $result[$ctr]["rate"]; ?>
                     <?php $sub_prem      += $result[$ctr]["prempercent"]; ?>
                     <?php $sub_disc      += $result[$ctr]["descpercent"]; ?>
                     <?php $sub_size      += $result[$ctr]["ccm"]; ?>
                     <?php $sub_amount    += $result[$ctr]["gross_amount"]; ?>
                 
                      <?php $grand_rate   += $result[$ctr]["rate"]; ?>
                      <?php $grand_prem   += $result[$ctr]["prempercent"]; ?>
                      <?php $grand_disc   += $result[$ctr]["descpercent"]; ?>
                      <?php $grand_size   += $result[$ctr]["ccm"]; ?>
                      <?php $grand_amount += $result[$ctr]["gross_amount"]; ?>
                 
                 <?php } ?>
                 
                 <?php  if(count($result)>0) { ?>      
                 
                 <tr style="white-space: nowrap;">
                 
                    <td colspan="9" style="text-align: right;vertical-align:middle;background-color:#B22222;"><b>SUB TOTAL <?php echo $section ?></b></td>
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($sub_rate,2,'.',',') ?></b></td>
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($sub_prem,2,'.',',') ?></b></td>
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($sub_disc,2,'.',',') ?></b></td>
                    <td style="background-color:#B22222;"></td> 
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($sub_size,2,'.',',') ?></b></td>
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($sub_amount,2,'.',',') ?></b></td>
                    <td colspan="9" style="background-color:#B22222;"></td>
              
                 </tr>
                 
                 <tr style="white-space: nowrap;background-color: gray;vertical-align:middle;">
                 
                    <td colspan="9" style="text-align: right;vertical-align:middle;background-color:#B22222;"><b>GRAND TOTAL</b></td>
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_rate,2,'.',',') ?></b></td>
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_prem,2,'.',',') ?></b></td>
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_disc,2,'.',',') ?></b></td>
                    <td style="background-color:#B22222;"></td> 
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_size,2,'.',',') ?></b></td>
                    <td style="text-align: right;vertical-align:middle;background-color:#B22222;"><b><?php echo number_format($grand_amount,2,'.',',') ?></b></td>
                    <td colspan="9" style="background-color:#B22222 ;"> </td>
                 
                 
                 </tr>
                   <?php } else { ?>
                         
                                <tr style="height:3em">
                                
                                    <td style="text-align:center">NO RESULTS FOUND</td>
                                
                                </tr>

                         <?php } ?>
               

