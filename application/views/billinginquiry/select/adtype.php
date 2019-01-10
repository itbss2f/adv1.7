<table align="center">
    
    <tr>
    
        <td style="vertical-align: middle;">AD TYPE : </td>
        
        <td style="vertical-align: middle;">
        
              <select style="width: 100px;padding-bottom:0px;margin-bottom:0px;" onchange="changevalue('#adtype<?php  echo $cell_id ?>','<?php  echo $ao_p_id ?>',this.value)"   class="myadtype" id="myadtype<?php echo $cell_id ?>">
       
                <option value="" >NA</option>
                  
                <?php for($ctr=0;$ctr<count($adtypes);$ctr++) { ?>  
                
                  <option <?php if($current_select==$adtypes[$ctr]['adtype_code']) { echo "selected";} ?> value="<?php echo $adtypes[$ctr]['id'] ?>" ><?php echo $adtypes[$ctr]['adtype_code'] ?></option>   
             
                <?php } ?>
                
             </select>
        
        </td>
    
    </tr>
   
</table>


