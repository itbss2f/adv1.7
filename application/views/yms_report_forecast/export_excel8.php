<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">FORECAST DAILY AD SUMMARY REPORT (Section Summary) </td> <br>
</tr>
<tr>  
    <td style="text-align: left; font-size: 20">Issue Date : <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?></td> <br>
</tr>
</thead>
                
                    
        <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
                <thead>
                    <tr>
                        <th width="5%">Issue Date</th> 
                        <th width="5%">Day</th> 
                        <th width="5%">Edition</th> 
                        <th width="5%">Section</th> 
                        <th width="5%">Pages</th>  
                    </tr>
                </thead>
                
        <?php
        foreach ($data as $data)  :
            foreach ($data as $x => $rowhead) {    ?>
            
                                 <tr>
                                    <td style= "color: black; text-align: center"><b><?php echo $x ?></b></td>
                                </tr> 
            
              <?php     /*     $result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11"));   */ ?>  
                       
                     <?php   
                        $totalpage = 0;
                        
                        foreach ($rowhead as $row) {
                            $totalpage += $row["booknamepage"];
                            ?> 
        
                                <tr>
                                    <td style= "color: black; text-align: center"><?php echo $row["issuedate"] ?></td>
                                    <td style= "color: black; text-align: center"><?php echo date("l", strtotime($row["issuedate"])) ?></td>
                                    <td style= "color: black; text-align: center"><?php echo $row["prod_code"]." - ".$row["prod_name"] ?></td>
                                    <td style= "color: black; text-align: center"><?php echo $row["book_name"] ?></td>
                                    <td style= "color: black; text-align: center"><?php echo $row["booknamepage"] ?></td>
                                </tr> 
                                
                          <?php    /*  $result[] = 
                                            array(
                                            array("text" =>$row["issuedate"]), 
                                            array("text" =>date("l", strtotime($row["issuedate"]))), 
                                            array("text" =>$row["prod_code"]." - ".$row["prod_name"]), 
                                            array("text" =>$row["book_name"]), 
                                            array("text" =>$row["booknamepage"])); */   
                        }
                        ?>
                                <tr>
                                    <td colspan="4" style= "color: black; text-align: right"><b>Total number of pages : </b></td>
                                    <td style="color: black; text-align: center"><b><?php echo number_format($totalpage, 2, ".",",") ?></b></td>
                                </tr>
                        
                        
                        <?php
                        
                    /*    $result[] = array("",array("text" => "total number of pages: ", "bold" => true, "align" => "center"),
                                          "","",array("text" => number_format($totalpage, 2, ".",","), "bold" => true, "style" => true, "align" => "right"));   */ 
        
            
                
                
            }
          endforeach;     
            ?>  