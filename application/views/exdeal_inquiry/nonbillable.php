
        
               <?php $color = ""; ?>
               <?php $ctr = 1; ?>
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
                    
            
                <tr style="white-space: nowrap;" class="inquiry_tr <?php echo $bg ?>" val = '<?php echo $result->ao_sinum ?>'   >
                    <td><?php echo $ctr ?></td>
                    <td><?php echo $result->ao_issuefrom ?></td>
                    <td><?php echo $result->product_discription ?></td>
                    <td><?php echo $result->agency_code ?></td>
                    <td><?php echo $result->agency_name ?></td>
                    <td><?php echo $result->client_code ?></td>
                    <td><?php echo $result->client_name ?></td>
                    <td><?php echo $result->PONumber ?></td>
                    <td><?php echo $result->empprofile_code ?></td>
                    <td><?php echo $result->size ?></td>
                    <td><?php echo $result->ao_totalsize ?></td>
                    <td><?php echo $result->ao_color ?></td>
                    <td><?php echo $result->ao_ref ?></td>
                    <td><?php echo $result->aosubtype_code ?></td>
                    <td><?php echo $result->adtype_code ?></td>
                    <td><?php echo $result->ao_type ?></td>
                    <td><?php echo $result->paytype_name ?></td>
                    <td><?php echo $result->branch_code ?></td>
                    <td><?php echo $result->ao_part_billing ?></td>
                    <td><?php echo $result->ao_part_production ?></td>
                    <td><?php echo $result->production_exdeal ?></td>
                    <td><?php echo $result->ao_totalcharge ?></td>
                 
                
                </tr>
                
                <?php $ctr++; ?>
            
            <?php endforeach; ?>
