<table>

    <tr>
    
    
        <td style="padding-right: 15px;"><b>(Branch) From : </b></td>
        
        <td style="padding-right: 44px;">
            <select name="from_branch" id="from_branch">
            
                <option value=""></option>
            
                <?php for($ctr=0;$ctr<count($branches);$ctr++) { ?>
                                     
                    <option value="<?php echo $branches[$ctr]['branch_name'] ?>"><?php echo $branches[$ctr]['branch_name'] ?></option>
                                     
                <?php } ?>
                
            </select>
        </td>
      
        <td style="padding-right: 73px;"><b>To : </b></td>
        
        <td>
            <select name="to_branch" id="to_branch">
            
                <option value=""></option>
            
                <?php for($ctr=0;$ctr<count($branches);$ctr++) { ?>
                                     
                    <option value="<?php echo $branches[$ctr]['branch_name'] ?>"><?php echo $branches[$ctr]['branch_name'] ?></option>
                                     
                <?php } ?>
                
            </select>
        </td>   
    
    </tr>
    
     <tr>
    
        <td style="padding-right: 15px;"><b>(Product) From : </b></td>
        
        <td style="padding-right: 44px;">
            <select name="from_product" id="from_product">
            
                <option value=""></option>
            
                <?php for($ctr=0;$ctr<count($products);$ctr++) { ?>
                                     
                    <option value="<?php echo $products[$ctr]['prod_name'] ?>"><?php echo $products[$ctr]['prod_name'] ?></option>
                                     
                <?php } ?>
                
            </select>
        </td>
      
        <td style="padding-right: 73px;"><b>To : </b></td>
        
        <td>
            <select name="to_product" id="to_product">
            
                <option value=""></option>
            
                <?php for($ctr=0;$ctr<count($products);$ctr++) { ?>
                                     
                    <option value="<?php echo $products[$ctr]['prod_name'] ?>"><?php echo $products[$ctr]['prod_name'] ?></option>
                                     
                <?php } ?>
                
            </select>
        </td>   
    
    </tr>

</table>