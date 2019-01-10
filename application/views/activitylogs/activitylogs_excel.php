<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b> 
    <br><b><td style="text-align: left">REPORT TYPE - <b><td style="text-align: left"><?php echo $reportname ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>
    </tr>    
</thead>


        <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
                <thead>
                    <tr>
                         <th width="08%">#</th> 
                         <th width="08%">Employee #</th> 
                         <th width="15%">Name</th> 
                         <th width="8%">Activity</th> 
                         <th width="8%">Date</th> 
                         <th width="8%">Remarks</th> 
                         <th width="8%">Email</th> 
                         <th width="8%">Branch</th> 
                    </tr>
                </thead>
                
                
                
       <?php 
       $no = 1;
       if ($reporttype == 1) {
            foreach ($dlist as $row) { ?>
            
            
                <tr>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $no ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['emp_id'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['username'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['activity'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['activitydate'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['remarks'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['email'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['branch'] ?></td>
                </tr>
            
            
            
            <?php  
                /*$result[] = array(array("text" => $row['emp_id'], 'align' => 'center'),
                                array("text" => $row['username'], 'align' => 'left'),
                                array("text" => $row['activity'], 'align' => 'left'),
                                array("text" => $row['activitydate'], 'align' => 'left'),
                                array("text" => $row['remarks'], 'align' => 'left'),
                                array("text" => $row['email'], 'align' => 'left'),
                                array("text" => $row['branch'], 'align' => 'center')
                           );  */ 
                           $no += 1;
                        }   
                        
                    }
                    
        ?>                    
