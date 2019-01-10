 <thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td> <br>
    <td style="text-align: left; font-size: 20">Actual Daily Ad Summary (Detailed per Month)</td> <br>
    <td style="text-align: left; font-size: 20">Issue Date : <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?></td><br>
    <td style="text-align: left; font-size: 20"><?php echo $paidname.' '.$excludename ?></b></td> 
</tr>
</thead>


    <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
            
            <thead>
             <tr>
             
                     <th width="10%">Month</th>
                     <th width="5%">Monday</th>
                     <th width="5%">Tuesday</th>
                     <th width="5%">Wednesday</th>
                     <th width="5%">Thursday</th>
                     <th width="5%">Friday</th>
                     <th width="5%">Saturday</th>
                     <th width="5%">Sunday</th>
                     <th width="5%">Total Sales</th>
              </tr>
          </thead>
   
   
         <?php
   
            $tmon = 0; $ttue = 0; $twed = 0; $tthu = 0; $tfri = 0; $tsat = 0; $tsun = 0; $ts_netadvsales = 0;     
            $tcmon = 0; $tctue = 0; $tcwed = 0; $tcthu = 0; $tcfri = 0; $tcsat = 0; $tcsun = 0; $tctotalday = 0; 
            $cmon = 0; $ctue = 0; $cwed = 0; $cthu = 0; $cfri = 0; $csat = 0; $csun = 0; $ctotalday = 0;    
            $mday = date("m", strtotime($datefrom));   
            while (strtotime($datefrom) <= strtotime($dateto)) {
                $day = date("D", strtotime($datefrom));
                $nmday = date("m", strtotime($datefrom));   
                //echo $mday." ".$nmday;   
                
                if ($mday <> $nmday) {
                #echo "feb";
                    $cmon = 0; $ctue = 0; $cwed = 0; $cthu = 0; $cfri = 0; $csat = 0; $csun = 0; $ctotalday = 0;    
                    $mday = date("m", strtotime($datefrom)); 
                }
                if ($day == "Sun") { 
                    $csun += 1; 
                    $tcsun += 1; 
                }    
                if ($day == "Mon") {   
                    $cmon += 1;
                    $tcmon += 1;
                }
                if ($day == "Tue") {   
                    $ctue += 1;
                    $tctue += 1;
                }
                if ($day == "Wed") {  
                    $cwed += 1; 
                    $tcwed += 1; 
                }
                if ($day == "Thu") {
                    $cthu += 1;   
                    $tcthu += 1;   
                }
                if ($day == "Fri") {
                    $cfri += 1;   
                    $tcfri += 1;   
                }
                if ($day == "Sat") {
                    $csat += 1;  
                    $tcsat += 1;  
                }
                
                $datefrom = date ("Y-m-d", strtotime("+1 day", strtotime($datefrom)));
                $ctotalday += 1;   
                $tctotalday += 1; 
                  
            }  #exit;
            //echo $ctue;
            ?>

         <?php
           foreach ($data as $data) : 
            foreach ($data as $month => $data) {
                $mon = 0; $tue = 0; $wed = 0; $thu = 0; $fri = 0; $sat = 0; $sun = 0; $s_netadvsales = 0;    
                 
                foreach ($data as $row) {
                    if ($row["days"] == "Sunday") {
                        $sun += $row["netsale"];   
                        $tsun += $row["netsale"];   
                    }    
                    if ($row["days"] == "Monday") {
                        $mon += $row["netsale"];    
                        $tmon += $row["netsale"];    
                    }
                    if ($row["days"] == "Tuesday") {
                        $tue += $row["netsale"];    
                        $ttue += $row["netsale"];    
                    }
                    if ($row["days"] == "Wednesday") {
                        $wed += $row["netsale"];   
                        $twed += $row["netsale"];   
                    }
                    if ($row["days"] == "Thursday") {
                        $thu += $row["netsale"];  
                        $tthu += $row["netsale"];  
                    }
                    if ($row["days"] == "Friday") {
                        $fri += $row["netsale"]; 
                        $tfri += $row["netsale"]; 
                    }
                    if ($row["days"] == "Saturday") {
                        $sat += $row["netsale"];   
                        $tsat += $row["netsale"];   
                    }
                    $s_netadvsales += $row["netsale"];
                    $ts_netadvsales += $row["netsale"];
                }
                ?>

                      <tr>
                            <td style="text-align: center;"><?php echo  "   ".$month ?></td>
                            <td style="text-align: right;"><?php echo number_format($mon, 2 , ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($tue, 2 , ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($wed, 2 , ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($thu, 2 , ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($fri, 2 , ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($sat, 2 , ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($sun, 2 , ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($s_netadvsales, 2 , ".", ",") ?></td>
                            
                        </tr>   
                
                <?php
                    /* $result[] = array(array("text" => "   ".$month, "align" => "left"),
                                  array("text" => number_format($mon, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($tue, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($wed, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($thu, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($fri, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($sat, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($sun, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($s_netadvsales, 2, ".", ",") , "align" => "right")
                                  );  */
                                                   ?> 
                                                   
                              <tr>
                                <td style="text-align: right;">Average  :</td>
                                <td style="text-align: right;"><?php echo number_format($mon/$cmon, 2 , ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($tue/$ctue, 2 , ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($wed/$cwed, 2 , ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($thu/$cthu, 2 , ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($fri/$cfri, 2 , ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($sat/$csat, 2 , ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($sun/$csun, 2 , ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($s_netadvsales/$ctotalday, 2 , ".", ",") ?></td>
                            </tr>                       
                      
                                  
                 <?php                 
                /* $result[] = array(array("text" => "       Average:"),
                                    array("text" =>  number_format($mon/$cmon, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($tue/$ctue, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($wed/$cwed, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($thu/$cthu, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($fri/$cfri, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($sat/$csat, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($sun/$csun, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($s_netadvsales/$ctotalday, 2, ".", ","), "align" => "right")
                                 );  */
                              ?> 

           
                                 <tr>
                                    <td style="text-align: right;"><b>GRAND TOTAL :</b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($tmon, 2 , ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($ttue, 2 , ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($twed, 2 , ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($tthu, 2 , ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($tfri, 2 , ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($tsat, 2 , ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($tsun, 2 , ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($ts_netadvsales, 2 , ".", ",") ?></b></td>
                                </tr>   
                         
                
            <?php
            
           /* $result[] = array(array("text" => "GRAND TOTAL", "align" => "left", "bold" => true),
                              array("text" => number_format($tmon, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($ttue, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($twed, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($tthu, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($tfri, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($tsat, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($tsun, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($ts_netadvsales, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true)
                              );   */    ?>


                                   
            <?php                         
            }
             ?>  
                         
             <?php                 
            /* $result[] = array(array("text" => "       Average:", "bold" => true),
                                array("text" =>  number_format($tmon/$tcmon, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($ttue/$tctue, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($twed/$tcwed, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($tthu/$tcthu, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($tfri/$tcfri, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($tsat/$tcsat, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($tsun/$tcsun, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($ts_netadvsales/$tctotalday, 2, ".", ","), "align" => "right", "bold" => true, "style" => true)
                             );  */
                              
                             
                 endforeach ;    
                        ?>     
                             
                            <tr>
                                <td style="text-align: right;"><b>Average  :</b></td>
                                <td style="text-align: right;"><b><?php echo number_format($tmon/$tcmon, 2, ".", ",") ?></b></td>
                                <td style="text-align: right;"><b><?php echo number_format($ttue/$tctue, 2, ".", ",") ?></b></td>
                                <td style="text-align: right;"><b><?php echo number_format($twed/$tcwed, 2, ".", ",") ?></b></td>
                                <td style="text-align: right;"><b><?php echo number_format($tthu/$tcthu, 2, ".", ",") ?></b></td>
                                <td style="text-align: right;"><b><?php echo number_format($tfri/$tcfri, 2, ".", ",") ?></b></td>
                                <td style="text-align: right;"><b><?php echo number_format($tsat/$tcsat, 2, ".", ",") ?></b></td>
                                <td style="text-align: right;"><b><?php echo number_format($tsun/$tcsun, 2, ".", ",") ?></b></td>
                                <td style="text-align: right;"><b><?php echo number_format($ts_netadvsales/$tctotalday, 2, ".", ",") ?></b></td>
                            </tr>      


</table>
