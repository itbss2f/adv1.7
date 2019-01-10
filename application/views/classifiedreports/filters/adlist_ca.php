<table>

     <tr>
    
        <td style="padding-right: 13px;"><b>(Product) From : </b></td>
        
        <td style="padding-right: 53px;">
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