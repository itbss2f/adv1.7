<table>
    
    <tr>
    
        <td style="padding-right:10px"><b>Depository Bank</b></td>
        
        <td> 
            
            <select id="depository_bank" name="depository_bank">
                
                <option value=""></option>
                
                <?php for($ctr=0;$ctr<count($bank);$ctr++) { ?>  
                
                    <option value="<?php echo $bank[$ctr]["id"]."|".$bank[$ctr]["bmf_name"]; ?>"><?php echo $bank[$ctr]["baf_acct"] ?> | <?php echo $bank[$ctr]["bmf_code"] ?> | <?php echo $bank[$ctr]["bbf_bnch"] ?> | <?php echo $bank[$ctr]["bmf_name"] ?></option> 
                   
                <?php } ?>          
            
            </select>
        
        
        </td>
    
    </tr>

</table>