<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">CUSTOMER MASTERFILE REPORT- <b><td style="text-align: left"><br/></b>
</tr>
</thead>



 <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
 <thead>
 <tr>

                                <th width="1%">#.</th>
                                <th width="3%">Account Code</th>
                                <th width="3%">Account Name</th>
                                <th width="5%">Category</th> 
                                <th width="5%">Branch</th> 
                                <th width="5%">Paytype</th>
                                <th width="5%">Gov Status</th>  
   </tr>
 </thead>                              
                                
                 <?php                          
        $no = 1;
        foreach ( $list as $emp => $row) {  ?> 

             <tr>
            <td style="text-align: left; font-size: 18"><?php echo $emp ?> </td>
            </tr>
            
           <?php 
             $result[] = array(array("text" => $emp, 'align' => 'left', 'bold' => true, 'font' => 18));
            $result[] = array();  ?>
            
                 <?php
           $no = 1;
           foreach ($row as $list1) {  ?> 

             <tr>
            <td style="text-align: left; font-size: 18"><?php echo $no ?> </td>
            <td style="text-align: left; font-size: 18"><?php echo $list1['cmf_code'] ?> </td> 
            <td style="text-align: left; font-size: 18"><?php echo $list1['cmf_name'] ?> </td> 
            <td style="text-align: left; font-size: 18"><?php echo $list1['catad_name'] ?> </td> 
            <td style="text-align: left; font-size: 18"><?php echo $list1['branch_code'] ?> </td> 
            <td style="text-align: left; font-size: 18"><?php echo $list1['paytype_name'] ?> </td> 
            <td style="text-align: left; font-size: 18"><?php echo $list1['govstat'] ?> </td> 
            </tr> 
           
          <?php  /* $result[] = array(array("text" => $no, 'align' => 'left'),
                              array("text" => $list1['cmf_code'], 'align' => 'left'),
                              array("text" => $list1['cmf_name'], 'align' => 'left'),
                              array("text" => $list1['catad_name'], 'align' => 'center'),
                              array("text" => $list1['branch_code'], 'align' => 'center'),
                              array("text" => $list1['paytype_name'], 'align' => 'center'),
                              array("text" => $list1['govstat'], 'align' => 'center')
                       );     */
                       
                       } ?>
                       
                       
                     <?php  
                     $no += 1; 
                     }       
                        ?> 
                                                                                                                                
</table> 