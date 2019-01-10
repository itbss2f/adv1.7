 <tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b><br/>
        <b><td style= "text-align: left; font-size: 20">OR CASHIERS DAILY COLLECTION REPORT - PER COLLECTOR </b></td><br/>
        <b><td style= "text-align: left; font-size: 20">DATE FROM <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?> </td>
</tr>
<br/> 

<table cellpadding="0" cellspacing="0" width="100%" border="1"> 

  <tr>
            <th width="5%">#</th>
            <th width="8%">OR No.</th>
            <th width="8%">OR Date</th>
            <th width="12%">Payee</th>  
            <th width="5%">Collector</th> 
            <th width="8%">Area Collector</th>   
            <th width="8%">Area Code</th>   
            <th width="8%">Area Name</th>   
            <th width="8%">Cash</th> 
            <th width="8%">Cheque No.</th>
            <th width="8%">Check Amount</th>
            <th width="8%">Adtype</th>
            <th width="8%">Bank</th>
  </tr>

  <?php
            $no = 1;
            $totalcash = 0; $totalcheque = 0; $totalcdisc = 0; $totalwtax = 0;
            foreach ($result as $ornum => $datalist) {
                $cheque = 0; $cash = 0;  $wtaxper = 0;  $wtax = 0;    
                foreach ($datalist as $row) {
                    $totalcash += $row['cashamt'];
                    $totalcheque += $row['chequeamt'];
                    $totalcdisc += $row['or_creditcarddisc'];
                    $totalwtax += $row['or_wtaxamt'];
                    $cash = ''; $cheque = ''; $wtaxper = ''; $cdisc = ''; $wtax = ''; 
                           
                    if ($row['cashamt'] != '') {
                        $cash = number_format($row['cashamt'], 2, '.',',');   
                    }
                    if ($row['chequeamt'] != '') {
                        $cheque = number_format($row['chequeamt'], 2, '.',',');     
                    }
                    if ($row['or_wtaxpercent'] != '') {
                        $wtaxper = number_format($row['or_wtaxpercent'], 0, '.',',');
                    }
                    if ($row['or_wtaxamt'] != '') {
                        $wtax = number_format($row['or_wtaxamt'], 2, '.',',');
                    }
                    if ($row['or_creditcarddisc'] != '') {
                        $cdisc = number_format($row['or_creditcarddisc'], 2, '.',',');
                    }
                    
                    ?>

                    

                     <tr>   
                        <td style="text-align: left;"><?php echo $no ?></td>
                        <td style="text-align: left;"><?php echo str_pad($row['or_num'], 8, 0, STR_PAD_LEFT) ?></td>
                        <td><?php echo DATE('m/d/Y', strtotime($row['ordate']))  ?></td>
                        <td><?php echo str_replace('\\','',$row['or_payee'])  ?></td>
                        <td><?php echo $row['empprofile_code'] ?></td>
                        <td><?php echo $row['areacoll'] ?></td> 
                        <td><?php echo $row['collarea_code'] ?></td> 
                        <td><?php echo $row['collarea_name'] ?></td>    
                        <td><?php echo $cash ?></td>    
                        <td><?php echo $row['chequenum'] ?></td>    
                        <td><?php echo $cheque ?></td>    
                        <td><?php echo $row['adtype_code'] ?></td>    
                        <td><?php echo $row['or_bnacc'] ?></td>     
                    </tr>
                    <?php
                    /*$result[] = array(
                                array('text' => str_pad($row['or_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                array('text' => DATE('m/d/Y', strtotime($row['ordate'])), 'align' => 'center'),   
                                array('text' => str_replace('\\','',$row['or_payee']), 'align' => 'left'),
                                //array('text' => $row['govstat'], 'align' => 'center'),
                                array('text' => $row['empprofile_code'], 'align' => 'center'),
                                array('text' => $row['areacoll'], 'align' => 'center'),  
                                array('text' => $row['collarea_code'], 'align' => 'center'),  
                                array('text' => $row['collarea_name'], 'align' => 'left'),  
                                //array('text' => $row['or_part'], 'align' => 'left'),
                                array('text' => $cash, 'align' => 'right'),
                                array('text' => $row['chequenum'], 'align' => 'right'),
                                array('text' => $cheque, 'align' => 'right'),
                                //array('text' => $wtax, 'align' => 'right'),
                               // array('text' => $wtaxper, 'align' => 'right'),
                               // array('text' => $cdisc, 'align' => 'right'),
                                array('text' => $row['adtype_code'], 'align' => 'center'),
                                array('text' => $row['or_bnacc'], 'align' => 'center'));  */  
                }    

                $no += 1;


            } /*
            $result[] = array();
            $result[] = array(
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Total', 'align' => 'right', 'bold' => true),      
                                array('text' => number_format($totalcash, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'right'),
                                array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'));
                                
            $result[] = array();
            $result[] = array();   
                               
            $result[] = array(); */
            ?>
            <tr>
                <td></td>     
                <td></td>     
                <td></td>     
                <td></td>     
                <td></td>     
                <td></td>     
                <td></td>     
                <td style="font-weight: bold;">TOTAL</td>     
                <td style="font-weight:bold;"><?php echo number_format($totalcash, 2, '.',',') ?></td>    
                <td></td> 
                <td style="font-weight:bold;"><?php echo number_format($totalcheque, 2, '.',',') ?></td>     
                <td></td>  
                <td></td>  
                <td></td>  
            </tr>
            <?php
            $result[] = array(  
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => 'Checked by:', 'align' => 'right', 'font' => 12),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Prepared by:', 'align' => 'left', 'font' => 12),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center')); 
?>

            
          
       


</table>
