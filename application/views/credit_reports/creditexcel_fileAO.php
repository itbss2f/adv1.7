
<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">CREDIT REPORT - <b><td style="text-align: left"><?php echo $reportname ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">AO DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($aofrom)).' TO '. date("F d, Y", strtotime($aoto)); ?>    
</tr>
</thead>

     <table cellpadding="0" cellspacing="0" width="100%" border="1">  
      
<thead>
  <tr>
        <th width="5%">#</th>
        <th width="5%">AO Number</th>
        <th width="5%">AO Date</th>      
        <th width="10%">File Name</th>
        <th width="10%">File Type</th> 
        <th width="10%">Upload By</th>
        <th width="10%">Upload Date</th>    
  </tr>
</thead>



<?php

if ($reporttype == 7) {
        $no = 1; 
        foreach ($dlist as $row) {  ?>
            
            <tr>
                <td style="text-align: center;"><?php echo $no ?></td>
                <td style="text-align: left;"><?php echo $row['ao_num'] ?></td>
                <td style="text-align: center;"><?php echo $row['ao_date'] ?></td>
                <td style="text-align: left;"><?php echo $row['filename'] ?></td>
                <td style="text-align: left;"><?php echo $row['filetype'] ?></td>
                <td style="text-align: center;"><?php echo $row['uploadby'] ?></td>
                <td style="text-align: center;"><?php echo $row['uploaddate'] ?></td>
            </tr>
            
            <?php
            /*$result[] = array(
                        array('text' => $no,  'align' => 'center'),
                        array('text' => $row['ao_num'],  'align' => 'left'), 
                        array('text' => $row['ao_date'],  'align' => 'right'), 
                        array('text' => $row['filename'],  'align' => 'left'), 
                        array('text' => $row['filetype'],  'align' => 'left'), 
                        array('text' => $row['uploadby'],  'align' => 'left'), 
                        array('text' => $row['uploaddate'],  'align' => 'left'), 
                        );  */ ?>
                        
            <?php             
                     $no += 1;
                     
             }?>
             
     <?php                 
           
        }
    ?>    