<table>

    <tr>
    
        <td style="padding-right: 60px;"><b>From : </b></td>
        
        <td style="padding-right: 44px;">
            <select name="from_branch" id="from_branch">
            
                <option value=""></option>
            
                <?php for($ctr=0;$ctr<count($branches);$ctr++) { ?>
                                     
                    <option value="<?php echo $branches[$ctr]['id'] ?>"><?php echo $branches[$ctr]['branch_name'] ?></option>
                                     
                <?php } ?>
                
            </select>
        </td>
      
        <td style="padding-right: 73px;"><b>To : </b></td>
        
        <td>
            <select name="to_branch" id="to_branch">
            
                <option value=""></option>
            
                <?php for($ctr=0;$ctr<count($branches);$ctr++) { ?>
                                     
                    <option value="<?php echo $branches[$ctr]['id'] ?>"><?php echo $branches[$ctr]['branch_name'] ?></option>
                                     
                <?php } ?>
                
            </select>
        </td>
    
    </tr>

</table>