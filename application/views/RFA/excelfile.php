 <tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b><br/>
        <b><td style= "text-align: left; font-size: 20">Request for Adjustment</b></td><br/>
        <b><td style= "text-align: left; font-size: 20">DATE FROM <?php echo date("F d, Y", strtotime($issuedatefrom)).' TO '. date("F d, Y", strtotime($issuedateto)); ?> </td>
</tr>
<br/> 

<table cellpadding="0" cellspacing="0" width="100%" border="1"> 

<thead>  
 

    <tr>
        <th width="1%">#</th>
        <th width="3%">AO Number</th> 
        <th width="3%">AO Date</th> 
        <th width="3%">Id</th> 
        <th width="3%">AO #</th> 
        <th width="4%">Issue Date</th> 
        <th width="2%">RFA No.</th> 
        <th width="3%">RFA Date</th> 
        <th width="7%">Client Name</th> 
        <th width="7%">Agency Name</th>                    
        <th width="5%">AE</th>   
        <th width="3%">Invoice No.</th>   
        <th width="3%">Invoice Date</th>   
        <th width="7%">RFA Findings</th>   
        <th width="5%">RFA Types</th>   
        <th width="5%">Invoice Amount</th>   
        <th width="5%">Adjusted Amount</th>   
        <th width="5%">Difference Amount</th>   
    </tr> 
</thead>

<?php 
    $no = 1;
    foreach ($dlist as $row){?>
    
     <tr>        
        <td style="text-align: left;"><?php echo $no ?></td>
        <td style="text-align: left;"><?php echo $row['ao_sinum'] ?></td>
        <td style="text-align: left;"><?php echo $row['ao_sidate'] ?></td>    
        <td style="text-align: left;"><?php echo $row['id'] ?></td>
        <td style="text-align: left;"><?php echo $row['ao_num'] ?></td> 
        <td style="text-align: left;"><?php echo $row['ao_issuefrom'] ?></td>
        <td style="text-align: left;"><?php echo $row['ao_rfa_num'] ?></td>
        <td style="text-align: left;"><?php echo $row['ao_rfa_date'] ?></td>
        <td style="text-align: left;"><?php echo $row['ao_payee'] ?></td> 
        <td style="text-align: left;"><?php echo $row['cmf_name'] ?></td> 
        <td style="text-align: left;"><?php echo $row['ae'] ?></td> 
        <td style="text-align: left;"><?php echo $row['ao_sinum'] ?></td> 
        <td style="text-align: left;"><?php echo $row['ao_sidate'] ?></td> 
        <td style="text-align: left;"><?php echo $row['ao_rfa_findings'] ?></td> 
        <td style="text-align: left;"><?php echo $row['rfatype_name'] ?></td> 
        <td style="text-align: left;"><?php echo @$row['origamt'] ?></td> 
        <td style="text-align: left;"><?php echo @$row['ao_rfa_amt'] ?></td> 
        <td style="text-align: left;"><?php echo @$row['diffamt'] ?></td> 
    </tr>
              
    
    <?php       
            /*    $result[] = array(array("text" => $no, 'align' => 'center'),
                                array("text" => $row['ao_sinum'],  'align' => 'left'),
                                array("text" => $row['ao_sidate'],  'align' => 'left'),
                                array("text" => $row['id'],  'align' => 'left'),
                                array("text" => $row['ao_num'],  'align' => 'left'),
                                array("text" => $row['ao_payee'],  'align' => 'left'),
                                array("text" => $row['cmf_name'],  'align' => 'left'),
                                array("text" => $row['ao_sidate'],  'align' => 'left'),
                                array("text" => $row['rfatype_name'],  'align' => 'left')
                                                     
                           );  */ 
                                           
                           $no += 1;   
                } 
        ?>


   

