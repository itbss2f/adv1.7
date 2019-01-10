
<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">ACCOUNT EXECUTIVE REPORT - <b><td style="text-align: left"><?php echo $reportname ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>    
    <br/><b><td style="text-align: left"><?php echo $reportypename ?><br/></b>    
</tr>
</thead>

     <table cellpadding="0" cellspacing="0" width="80%" border="1">  
      
<thead>
  <tr>
            <?php if ($reporttype == 4 || $reporttype == 6) { ?>
            <th width="8%">PR Number</th>
            <th width="20%">Particulars</th>
            <th width="3%">Gov</th>      
            <th width="5%">Collector</th>
            <th width="18%">Remarks</th> 
            <th width="10%">Cheque Info</th>
            <th width="10%">Cheque Number</th>
            <th width="10%">Cheque Amount</th>
            <th width="5%">Adype</th>
            <th width="5%">Bank</th>
            <th width="8%">OR Number</th>
            <?php }
            else if ($reporttype == 5) { ?>
            <th width="8%">PR Number</th>
            <th width="8%">PR Date</th>
            <th width="8%">Payee Code</th>      
            <th width="16%">Payee Name</th>      
            <th width="8%">Net Sales</th>
            <th width="8%">VAT Amount</th> 
            <th width="8%">VAT %</th> 
            <th width="10%">Total Amount</th>
            <th width="15%">WTAX Amount</th>
            <th width="15%">WVAT Amount</th>
            <th width="15%">PPD Amount</th>
            <th width="15%">Assign Amount</th>
            <?php }
             else { ?>
            <th width="8%">PR Number</th>
            <th width="20%">Particulars</th>
            <th width="3%">Gov</th>      
            <th width="5%">Collector</th>
            <th width="18%">Remarks</th> 
            <th width="10%">Cash</th>
            <th width="10%">Cheque Number</th>
            <th width="10%">Cheque Amount</th>
            <th width="5%">W/tax Amount</th>
            <th width="5%">(%)</th>
            <th width="8%">Adype</th>
            <th width="8%">Bank</th>
            <?php }   ?>
       
  </tr>
</thead>

<?php 

  $cheque = 0; $cash = 0;  $wtaxper = 0;
        if ($reporttype == 4 || $reporttype == 6) { 
            $totalcheque = 0;
            foreach ($list as $ornum => $datalist) {
                foreach ($datalist as $row) {
                    $totalcash += $row['cashamt'];
                    $totalcheque += $row['chequeamt'];
                    $cash = ''; $cheque = ''; $wtaxper = '';          
                    if ($row['cashamt'] != '') {   ?>
                
                    
                    
                    <?php 
                        $cash = number_format($row['cashamt'], 2, '.',',');   
                    } 
                    if ($row['chequeamt'] != '') { ?>
                    
                    
        

                    
                    <?php 
                        $cheque = number_format($row['chequeamt'], 2, '.',',');     
                    }
                    
                    ?>
                    
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['pr_num']?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$row['pr_payee']) ?></td>
                    <td style="text-align: center; font-size: 12px; color: black"><?php echo $row['govstat'] ?></td>
                    <td style="text-align: center; font-size: 12px; color: black"><?php echo $row['empprofile_code'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['pr_part'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['cheqeuinfo'] ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['chequenum'] ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo $cheque ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['adtype_code'] ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['pr_bnacc'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['pr_ornum'] ?></td>
                </tr>
                    
                    <?php
                    $result[] = array(
                                array('text' => $row['pr_num'], 'align' => 'left'),
                                array('text' => str_replace('\\','',$row['pr_payee']), 'align' => 'left'),
                                array('text' => $row['govstat'], 'align' => 'center'),
                                array('text' => $row['empprofile_code'], 'align' => 'center'),
                                array('text' => $row['pr_part'], 'align' => 'left'),
                                array('text' => $row['cheqeuinfo'], 'align' => 'left'),
                                array('text' => $row['chequenum'], 'align' => 'right'),
                                array('text' => $cheque, 'align' => 'right'),
                                array('text' => $row['adtype_code'], 'align' => 'right'),
                                array('text' => $row['pr_bnacc'], 'align' => 'right'),
                                array('text' => $row['pr_ornum'], 'align' => 'left'));    
                }    
            } ?>
            
            
            <tr>
                <td style="text-align: center;"></td>
                <td style="text-align: left;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: left;"></td>
                <td style="text-align: right;"></td>
                <td style="text-align: right;"></td>
                <td style="text-align: right; font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalcheque, 2, '.',',') ?></td>
                <td style="text-align: right;"></td> 
                <td style="text-align: right;"></td> 
                <td style="text-align: center;"></td> 
            </tr>
            
            <?php
            $result[] = array();
            $result[] = array(
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'center'));     

        } else if ($reporttype == 5) {     ?>
        
        <?php
            
            $totalgross = 0; $totalvat = 0; $totalamt = 0; $totalwtax = 0; $totalwvat = 0; $totalppd = 0; $totalassamt = 0;
            foreach ($list as $row) {
                $totalgross += $row['pr_grossamt']; $totalvat += $row['pr_vatamt']; $totalamt += $row['pr_amt']; $totalwtax += $row['pr_wtaxamt']; $totalwvat += $row['pr_wvatamt']; 
                $totalppd += $row['pr_ppdamt']; $totalassamt+= $row['pr_assignamt'];
                
                ?>
                
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['pr_num']?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['prdate']?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['prdate']?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$row['payeename']) ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['pr_grossamt'], 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['pr_vatamt'], 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['pr_cmfvatrate'], 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['pr_amt'], 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['pr_wtaxamt'], 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['pr_wvatamt'], 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['pr_ppdamt'], 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['pr_assignamt'], 2, '.',',') ?></td>
                </tr>
                
                <?php
                $result[] = array(
                                        array('text' => $row['pr_num'], 'align' => 'left'),
                                        array('text' => $row['prdate'], 'align' => 'left'),
                                        array('text' => $row['payeecode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['payeename']), 'align' => 'left'),
                                        array('text' => number_format($row['pr_grossamt'], 2, '.',','), 'align' => 'right'),    
                                        array('text' => number_format($row['pr_vatamt'], 2, '.',','), 'align' => 'right'),       
                                        array('text' => number_format($row['pr_cmfvatrate'], 0, '.',','), 'align' => 'right'),   
                                        array('text' => number_format($row['pr_amt'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['pr_wtaxamt'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['pr_wvatamt'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['pr_ppdamt'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['pr_assignamt'], 2, '.',','), 'align' => 'right')
                                        );    
            }    ?>
            
            <tr>
                <td style="text-align: left;"></td>
                <td style="text-align: left;"></td>
                <td style="text-align: left;"></td>
                <td style="text-align: center;font-weight: bold;">Total :</td>
                <td style="text-align: right; font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalgross, 2, '.',',') ?></td>
                <td style="text-align: right; font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalvat, 2, '.',',') ?></td>
                <td style="text-align: right;"></td>
                <td style="text-align: right; font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalamt, 2, '.',',') ?></td>  
                <td style="text-align: right; font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalwtax, 2, '.',',') ?></td>  
                <td style="text-align: right; font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalwvat, 2, '.',',') ?></td>  
                <td style="text-align: right; font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalppd, 2, '.',',') ?></td>  
                <td style="text-align: right; font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalassamt, 2, '.',',') ?></td>  
            </tr>
            
            <?php
            $result[] = array(
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => 'Total : ', 'align' => 'right', 'bold' => true),
                                        array('text' => number_format($totalgross, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),    
                                        array('text' => number_format($totalvat, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),       
                                        array('text' => '', 'align' => 'right'),   
                                        array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                        array('text' => number_format($totalwtax, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                        array('text' => number_format($totalwvat, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                        array('text' => number_format($totalppd, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                        array('text' => number_format($totalassamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                        );    ?>
                                        
        <?php              
        } else {            
            $totalcash = 0; $totalcheque = 0;
            foreach ($list as $ornum => $datalist) {
                foreach ($datalist as $row) {
                    $totalcash += $row['cashamt'];
                    $totalcheque += $row['chequeamt'];
                    $cash = ''; $cheque = ''; $wtaxper = '';          
                    if ($row['cashamt'] != '') {  ?>
                   
                    
                    <?php
                        $cash = number_format($row['cashamt'], 2, '.',',');   
                    }
                    if ($row['chequeamt'] != '') {  ?>
                    
                   
                    
                    <?php
                        $cheque = number_format($row['chequeamt'], 2, '.',',');     
                    }
                    if ($row['pr_wtaxpercent'] != '') {   ?>
                    
                   
                    
                    <?php
                        $wtaxper = number_format($row['pr_wtaxpercent'], 0, '.',',');
                    }
                    
                    ?>
                    
                <tr>
                    <td style="text-align: center; font-size: 12px; color: black"><?php echo $row['pr_num']?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$row['pr_payee']) ?></td>
                    <td style="text-align: center; font-size: 12px; color: black"><?php echo $row['govstat'] ?></td>
                    <td style="text-align: center; font-size: 12px; color: black"><?php echo $row['empprofile_code'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['pr_comment'] ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo $cash ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['chequenum'] ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo $cheque ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['pr_wtaxamt'] ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo $wtaxper ?></td>
                    <td style="text-align: center; font-size: 12px; color: black"><?php echo $row['adtype_code'] ?></td>
                    <td style="text-align: center; font-size: 12px; color: black"><?php echo $row['pr_bnacc'] ?></td>
                </tr>
                    
                    
                    
                    <?php
                    $result[] = array(
                                array('text' => $row['pr_num'], 'align' => 'center'),
                                array('text' => str_replace('\\','',$row['pr_payee']), 'align' => 'left'),
                                array('text' => $row['govstat'], 'align' => 'center'),
                                array('text' => $row['empprofile_code'], 'align' => 'center'),
                                array('text' => $row['pr_comment'], 'align' => 'left'),
                                array('text' => $cash, 'align' => 'right'),
                                array('text' => $row['chequenum'], 'align' => 'right'),
                                array('text' => $cheque, 'align' => 'right'),
                                array('text' => $row['pr_wtaxamt'], 'align' => 'right'),
                                array('text' => $wtaxper, 'align' => 'right'),
                                array('text' => $row['adtype_code'], 'align' => 'center'),
                                array('text' => $row['pr_bnacc'], 'align' => 'center'));    
                }    
            }    ?>
            
             <tr>
                <td style="text-align: center;"></td>
                <td style="text-align: left;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: left;"></td>
                <td style="text-align: right; font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalcash, 2, '.',',') ?></td>
                <td style="text-align: right;"></td>  
                <td style="text-align: right; font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalcheque, 2, '.',',') ?></td>
                <td style="text-align: right;"></td>  
                <td style="text-align: right;"></td>  
                <td style="text-align: center;"></td>  
                <td style="text-align: center;"></td>  
            </tr>
            
            <?php
            $result[] = array();
            $result[] = array(
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => number_format($totalcash, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'right'),
                                array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'));    
        }
        
        ?>
        
   
        

     
              
              
              
              
              
              
              
              
              
              