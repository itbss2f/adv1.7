
<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">MAIN MODULES AND IT'S FUNCTION <b><br/></b>     
</tr>
</thead>

     <table cellpadding="0" cellspacing="0" width="80%" border="1">  
      
<thead>
  <tr>

        <th width="10%">#</th>
        <th width="25%">Main Module</th>                    
        <th width="30%">Module</th>                    
        <th width="10%">Description</th>                    
        <th width="15%">Functions</th>  
       
               
  </tr>
</thead>


  <?php
    $no = 1;
    foreach ($dlist as $row) : ?> 

               <tr>
                    <td><?php echo $no ?></td>
                    <td style="text-align: left; color: black"><b><?php echo $row['main_module'] ?></td>                   
                    <td><?php echo $row['module'] ?></td>                    
                    <td><?php echo $row['description'] ?></td>                    
                    <td><?php echo $row['functions'] ?></td>                     
                </tr> 

    <?php $no += 1; ?>
    <?php endforeach; ?> 
 
    
  
            
   

             
         
              
              
              
