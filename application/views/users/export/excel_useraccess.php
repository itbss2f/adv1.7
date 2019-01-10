
<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">USER ACCESS AND MODULES <b><br/></b>     
</tr>
</thead>

     <table cellpadding="0" cellspacing="0" width="80%" border="1">  
      
<thead>
  <tr>

        <th width="10%">#</th>
        <th width="25%">Username</th>                    
        <th width="15%">Dept_code</th>                    
        <th width="30%">Main Module</th>                    
        <th width="30%">Module</th>   
        <th width="15%">Function name</th>                                     
        <th width="10%">Expiration Date</th>                      
        <th width="10%">User Status</th>                      
       
               
  </tr>
</thead>


  <?php
    $no = 1;
    foreach ($dlist as $row) : ?> 

               <tr>
                    <td style="text-align: left;"><?php echo $no ?></td>
                    <td style="text-align: center;"><?php echo $row['username'] ?></td>                   
                    <td style="text-align: left;"><?php echo $row['department_code'] ?></td>                   
                    <td style="text-align: left;"><?php echo $row['main_module'] ?></td>                    
                    <td style="text-align: left;"><?php echo $row['modulename'] ?></td>                    
                    <td style="text-align: left;"><?php echo $row['functionname'] ?></td>                                         
                    <td style="text-align: left;"><?php echo $row['expiration_date'] ?></td>                     
                    <td style="text-align: left;"><?php echo $row['users_status'] ?></td>                     
                </tr> 

    <?php $no += 1; ?>
    <?php endforeach; ?> 
 
    
  
            
   

             
         
              
              
              
