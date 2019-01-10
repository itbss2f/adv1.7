<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">CUSTOMER MASTERFILE REPORT- <b><td style="text-align: left"><?php echo $reportname?><br/></b>
</tr>
</thead>


     <table cellpadding="0" cellspacing="0" width="100%" border="1">  
      
<thead>
  <tr>
            <?php if ($reporttype == 5) { ?>
            <th width="5%">#</th>
            <th width="10%">Account Code</th>
            <th width="45%">Account Name</th>      
            <?php }
             else if ($reporttype == 7) { ?>
            <th width="5%">#</th>
            <th width="10%">Account Code</th>
            <th width="45%">Account Name</th>
            <th width="10%">Collection Asst</th> 
            <th width="10%">Account Executive</th> 
            <?php } 
             else { ?>    
            <th width="5%">#</th>
            <th width="10%">Account Code</th>
            <th width="25%">Account Name</th>
            <th width="20%">Address</th>
            <th width="7%">Tel no.</th>
            <th width="7%">Tin no.</th>
            <th width="5%">Category</th>
            <th width="5%">Branch</th>
            <th width="5%">Pay Type</th>
            <th width="5%">Gov Status</th> 
            <th width="5%">Industry</th> 
            <th width="12%">Unbilled</th> 
            <?php }   ?>
               
  </tr>
</thead>                           
<?php


        
        $no = 1;   
        if ($reporttype == 5) {
            foreach ($dlist as $agency => $row) {  ?>
            
            <tr>
                <td colspan="3" style="text-align: left; font-size: 18;font-weight: bold;"><?php echo $agency ?> </td>
            </tr>
            
            <?php
                $result[] = array(array("text" => $agency, 'align' => 'left', 'bold' => true, 'font' => 18));
                $result[] = array();   ?>
                
                <?php
                $no = 1;  
                foreach ($row as $list1) { ?>
                
            <tr>
                <td style="text-align: left; font-size: 12"><?php echo $no ?> </td>
                <td style="text-align: left; font-size: 12"><?php echo $list1['ao_cmf'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['ao_payee'] ?> </td> 
            </tr> 
                
                <?php   

                $result[] = array(array("text" => $no, 'align' => 'left'),
                                  array("text" => $list1['ao_cmf'], 'align' => 'left'),
                                  array("text" => $list1['ao_payee'], 'align' => 'left'),
                                  /*array("text" => $list1['catad_name'], 'align' => 'center'),
                                  array("text" => $list1['branch_code'], 'align' => 'center'),
                                  array("text" => $list1['paytype_name'], 'align' => 'center'),
                                  array("text" => $list1['govstat'], 'align' => 'center')   */
                           ); 
                           $no += 1;   
                }  ?>
                
                <?php
                $result[] = array(); 
            } ?>
            
            <?php
        } elseif ($reporttype == 7) {
            foreach ($dlist as $list1) {  ?>
            
            <tr>
                <td style="text-align: left; font-size: 12"><?php echo $no ?> </td>
                <td style="text-align: left; font-size: 12"><?php echo $list1['cmf_code'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['cmf_name'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['collasst'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['ae'] ?> </td> 
            </tr> 
            
            <?php   

                $result[] = array(array("text" => $no, 'align' => 'left'),
                                  array("text" => $list1['cmf_code'], 'align' => 'left'),
                                  array("text" => $list1['cmf_name'], 'align' => 'left'),
                                  array("text" => $list1['collasst'], 'align' => 'left'),
                                  array("text" => $list1['ae'], 'align' => 'left')
                           ); 
                           $no += 1;   
                }   ?>
                
                <?php
                $result[] = array(); ?>
                
                <?php
        } else {
            foreach ($dlist as $emp => $row) { ?>
            
            <tr>
                <td colspan="12" style="text-align: left; font-size: 18;font-weight: bold;"><?php echo $emp ?> </td>
            </tr>
            
            <?php
                $result[] = array(array("text" => $emp, 'align' => 'left', 'bold' => true, 'font' => 18));
                $result[] = array();
                ?>
                
                <?php
                $no = 1; $unbilledamount = 0;  
                foreach ($row as $list1) {  
                $payments = $list1['cmf_zero'] + $list1['cmf_thirty'] + $list1['cmf_sixty'] + $list1['cmf_ninety'] + $list1['cmf_onetwenty'] + $list1['cmf_overonetwenty']; 
                $overpayment = $list1['cmf_overpayment'];
                $unbilledamount = $payments - $overpayment ; ?>

            <tr>
                <td style="text-align: left; font-size: 12"><?php echo $no ?> </td>
                <td style="text-align: left; font-size: 12"><?php echo $list1['cmf_code'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['cmf_name'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['address'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['tel_number'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['tin_number'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['catad_name'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['branch_code'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['paytype_name'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['govstat'] ?> </td> 
                <td style="text-align: left; font-size: 12"><?php echo $list1['indname'] ?> </td> 
                <td style="text-align: right; font-size: 12"><?php echo number_format($unbilledamount, 2, ".", ",") ?> </td> 
            </tr> 
                
                <?php  

                $result[] = array(array("text" => $no, 'align' => 'left'),
                                  array("text" => $list1['cmf_code'], 'align' => 'left'),
                                  array("text" => $list1['cmf_name'], 'align' => 'left'),
                                  array("text" => $list1['catad_name'], 'align' => 'center'),
                                  array("text" => $list1['branch_code'], 'align' => 'center'),
                                  array("text" => $list1['paytype_name'], 'align' => 'center'),
                                  array("text" => $list1['govstat'], 'align' => 'center')
                           ); 
                           $no += 1;   
                }   ?>
                
                <?php
                $result[] = array(); 
            }  ?>
            <?php
        } ?>
                                                                                                                                
</table> 