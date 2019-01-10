
<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">GL VS SL REPORT - <b><td style="text-align: left"><?php echo $reportname ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>    
</tr>
</thead>

    <table cellpadding="0" cellspacing="0" width="80%" border="1">  
      
        <thead>
          <tr>
                <?php if ($reporttype == 1 || $reporttype == 2) { ?>
                <th width="5%">#</th>
                <th width="10%">Type</th>
                <th width="10%">OR Number</th>      
                <th width="10%">OR Date</th>
                <th width="10%">Payee Name</th> 
                <th width="10%">AR Account</th>
                <th width="15%">Amount</th>
                <?php } 
                 else if ($reporttype == 3) { ?>    
                <th width="5%">#</th>
                <th width="5%">Type</th>
                <th width="5%">OR Number</th>      
                <th width="5%">OR Date</th>
                <th width="10%">Payee Name</th> 
                <th width="5%">GL AR Account</th>
                <th width="5%">SL AR Account</th> 
                <th width="17%">GL Amount</th> 
                <th width="17%">SL Amount</th> 
                <th width="17%">Diff Amount</th> 
                <?php }  ?>
          </tr>
        </thead>
        
        <?php
        
        if ($reporttype == 1 || $reporttype == 2) {
            $no = 1; $totalamt = 0;       
            foreach ($dlist as $row) {
            $totalamt += $row['amount'];  ?>
            
            <tr>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $no ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['datatype'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['datanumber'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['ddate'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['payeename'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['account'] ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['amount'], 2,'.', ',') ?></td>
            </tr>

            <?php
            $result[] = array(
                        array('text' => $no,  'align' => 'left'),
                        array('text' => $row['datatype'],  'align' => 'left'),
                        array('text' => $row['datanumber'],  'align' => 'right'),
                        array('text' => $row['ddate'],  'align' => 'left'),
                        array('text' => $row['payeename'],  'align' => 'left'),
                        array('text' => $row['account'],  'align' => 'left'),
                        array('text' => number_format($row['amount'], 2,'.', ','),  'align' => 'right'),
                        
                        );
                     $no += 1; 
                     
             } ?>
             
             <tr>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: right; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: right; font-size: 13px; color: black;font-weight: bold;">TOTAL</td>
                <td style="text-align: right; font-size: 13px; color: black;font-weight: bold;"><?php echo number_format($totalamt, 2,'.', ',') ?></td>
            </tr>
             
             <?php  
             $result[] = array(
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'right'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => 'TOTAL', 'bold' => true, 'align' => 'right'),
                        array('text' => number_format($totalamt, 2,'.', ','),  'align' => 'right', 'bold' => true, 'style' => true),
                        
                        ); 
        } 
        else {
            $no = 1; $gltotalamt = 0; $sltotalamt = 0; $diftotalamt = 0;       
            foreach ($dlist as $row) {
            $gltotalamt += $row['glamount'];
            $sltotalamt += $row['slamount'];
            $diftotalamt += $row['diff'];     ?>
            
             <tr>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $no ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['datatype'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['datanumber'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['datadate'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['payeename'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['account'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['slaccount'] ?></td>
                <td style="text-align: right; font-size: 13px; color: black"><?php echo number_format($row['glamount'], 2,'.', ',') ?></td>
                <td style="text-align: right; font-size: 13px; color: black"><?php echo number_format($row['slamount'], 2,'.', ',') ?></td>
                <td style="text-align: right; font-size: 13px; color: black"><?php echo number_format($row['diff'], 2,'.', ',') ?></td>
            </tr>
            
            <?php
            $result[] = array(
                        array('text' => $no,  'align' => 'left'),
                        array('text' => $row['datatype'],  'align' => 'left'),
                        array('text' => $row['datanumber'],  'align' => 'right'),
                        array('text' => $row['datadate'],  'align' => 'left'),
                        array('text' => $row['payeename'],  'align' => 'left'),
                        array('text' => $row['account'],  'align' => 'left'),
                        array('text' => $row['slaccount'],  'align' => 'left'),
                        array('text' => number_format($row['glamount'], 2,'.', ','),  'align' => 'right'),
                        array('text' => number_format($row['slamount'], 2,'.', ','),  'align' => 'right'),
                        array('text' => number_format($row['diff'], 2,'.', ','),  'align' => 'right'),
                        
                        );
                     $no += 1;
                     
             }  ?>
             
             <tr>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: right; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: right; font-size: 13px; color: black;font-weight: bold;">TOTAL</td>
                <td style="text-align: right; font-size: 13px; color: black;font-weight: bold;"><?php echo number_format($gltotalamt, 2,'.', ',') ?></td>
                <td style="text-align: right; font-size: 13px; color: black;font-weight: bold;"><?php echo number_format($sltotalamt, 2,'.', ',') ?></td>
                <td style="text-align: right; font-size: 13px; color: black;font-weight: bold;"><?php echo number_format($diftotalamt, 2,'.', ',') ?></td>
            </tr>
             
             <?php 
             $result[] = array(
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'right'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => 'TOTAL', 'bold' => true, 'align' => 'right'),
                        array('text' => number_format($gltotalamt, 2,'.', ','),  'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($sltotalamt, 2,'.', ','),  'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($diftotalamt, 2,'.', ','),  'align' => 'right', 'bold' => true, 'style' => true),
                        
                        );     
        }
            ?>
            
            
              
              
              
              
              
              
              