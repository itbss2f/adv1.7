<table>
    
    <tr>
    
        <td style="padding-right:10px"><b>Cashier's Code</b></td>
        
        <td style="padding-right:100px;"> 
            
            <select id="cashier_collector" name="cashier_collector">
                
                <option value=""></option>
                
                <?php for($ctr=0;$ctr<count($employee);$ctr++) { ?>  
                
                    <option value="<?php echo $employee[$ctr]["id"]."|".$employee[$ctr]["employee_name"] ?>"><?php echo $employee[$ctr]['empprofile_code']." | ".$employee[$ctr]["employee_name"] ?></option> 
                                       
                <?php } ?>          
            
            </select>
        
        
        </td>
        
        <td style="vertical-align: middle;width:50px;"><input type="radio" name="cdcr_option" value="all" ><b>ALL</b></td> 
        
        <td style="vertical-align: middle;width:50px;"><input type="radio" name="cdcr_option" value="or" ><b>OR</b></td>
        
        <td style="vertical-align: middle;width:50px;"><input type="radio" name="cdcr_option" value="pr" ><b>PR</b></td> 
    
    </tr>

</table>