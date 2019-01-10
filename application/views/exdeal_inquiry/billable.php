
               <?php $color = ""; ?>
               <?php $ctr = 1; ?>
               <?php $switch = 1; ?>
               <?php $bg = "" ?>
               
               <?php $total_billing = 0 ?>
               <?php $due_to_agency = 0 ?>
               <?php $net_billing = 0 ?>
               <?php $amount_due = 0 ?>
               <?php $total_paid = 0 ?>
               <?php $exdeal_amount = 0 ?>
               
               <?php foreach($result as $result ) : ?>
              
                    <?php if($switch == 1)
                    {
                        $bg = "gradeC odd";
                        $switch = 2;
                    } 
                    else{  
                        $bg = "gradeC even";
                        $switch = 1; 
                    }
                    
                    if($result->exdeal_status == '1')
                    {   
                       $bg = "gradeX odd";   
                    }
                     ?>
                    
                    
                
                <tr style="white-space: nowrap;" client_name='<?php echo $result->client_name; ?>' id='<?php echo $result->id ?>' class="inquiry_tr <?php echo $bg ?>" val = '<?php echo $result->id ?>'>
                  
                    <td><?php echo $result->ao_exdealcontractno ?></td>
                    <td><?php echo $result->ao_sinum ?></td>
                    <td><?php echo $result->ao_sidate ?></td>
                    <td><?php echo $result->agency_code ?></td>
                    <td><?php echo $result->agency_name ?></td>
                    <td><?php echo $result->client_code ?></td>
                    <td><?php echo $result->client_name ?></td>
                    <td><?php echo $result->PONumber ?></td>
                    <td style="text-align: center;"><?php echo $result->ao_type ?></td>
                    <td style="text-align: right;"><?php echo number_format($result->total_billing,2,'.',',') ?></td>
                    <td style="text-align: right;"><?php echo number_format($result->due_to_agency,2,'.',',') ?></td>
                    <td style="text-align: right;"><?php echo number_format($result->net_billing,2,'.',',') ?></td>
                    <td style="text-align: right;"><?php echo number_format($result->plus_vat,2,'.',',') ?></td>
                    <td style="text-align: right;"><?php echo number_format($result->amount_due,2,'.',',') ?></td>
                    <td style="text-align: right;"><?php echo number_format($result->total_paid,2,'.',',') ?></td>
                    <td style="text-align: right;"><?php echo number_format($result->ao_wtaxpercent,2,'.',',') ?></td>
                    <td style="text-align: right;"><?php echo number_format($result->ao_exdealpercent,2,'.',',') ?></td>
                    <td style="text-align: right;"><?php echo number_format($result->exdeal_amount,2,'.',',') ?></td>
                    <td style=";"><?php echo $result->exdeal_remarks ?></td>    
                       
<!--                    <td style=";"><?php echo $result->ao_dcnum ?></td>
                    <td style=";"><?php echo $result->ao_dcdate ?></td>
                    <td style="text-align: right;;"><?php echo number_format($result->ao_dcamt,2,'.',',') ?></td>
                    <td style=";"><?php echo $result->ao_receive_date ?></td>
                    <td style=";"><?php echo $result->ao_receive_part ?></td>
                    <td style=";"><?php echo $result->ao_rfa_aistatus ?></td>
                    <td style=";"><?php echo $result->ao_rfa_supercedingai ?></td>
                    <td style=";"><?php echo $result->ao_part_billing ?></td>  -->
                
                </tr>
                
                <?php $total_billing += $result->total_billing; ?>
                <?php $due_to_agency += $result->due_to_agency;  ?>
                <?php $net_billing += $result->net_billing ?>
                <?php $amount_due += $result->amount_due ?>
                <?php $total_paid += $result->total_paid ?>
                <?php $exdeal_amount += $result->exdeal_amount ?>
                
                <?php $ctr++; ?>
            
            <?php endforeach; ?>
            
              <tr style="white-space: nowrap;" client_name='<?php echo $result->client_name; ?>' id='<?php echo $result->id ?>' class="inquiry_tr <?php echo $bg ?>" val = '<?php echo $result->id ?>'>
                  
                    <td></td>
                    <td></td>       
                    <td></td>       
                    <td></td>       
                    <td></td>       
                    <td></td>       
                    <td></td>       
                    <td><b>TOTAL</b></td>
                    <td></td>       
                    <td style="text-align: right;"><?php echo number_format($total_billing,2,'.',',') ?></td>
                    <td style="text-align: right;"><?php echo number_format($due_to_agency,2,'.',',') ?></td>
                    <td style="text-align: right;"><?php echo number_format($net_billing,2,'.',',') ?></td>
                    <td></td>
                    <td style="text-align: right;"><?php echo number_format($amount_due,2,'.',',') ?></td>
                    <td style="text-align: right;"><?php echo number_format($total_paid,2,'.',',') ?></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;"><?php echo number_format($exdeal_amount,2,'.',',') ?></td>
                   <td></td>           
                       
<!--                    <td style=";"><?php echo $result->ao_dcnum ?></td>
                    <td style=";"><?php echo $result->ao_dcdate ?></td>
                    <td style="text-align: right;;"><?php echo number_format($result->ao_dcamt,2,'.',',') ?></td>
                    <td style=";"><?php echo $result->ao_receive_date ?></td>
                    <td style=";"><?php echo $result->ao_receive_part ?></td>
                    <td style=";"><?php echo $result->ao_rfa_aistatus ?></td>
                    <td style=";"><?php echo $result->ao_rfa_supercedingai ?></td>
                    <td style=";"><?php echo $result->ao_part_billing ?></td>  -->
                
                </tr>
                