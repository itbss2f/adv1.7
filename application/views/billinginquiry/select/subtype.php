<table align="center">
    
    <tr>
    
        <td style="vertical-align: middle;">SUB TYPE : </td>
        
        <td style="vertical-align: middle;">
            
            <select style="width: 100px;padding-bottom:0px;margin-bottom:0px;" onchange="changevalue('#subtype<?php  echo $cell_id ?>','<?php  echo $ao_p_id ?>',this.value)" class="mysubtype"  id="mysubtype<?php echo $cell_id ?>">
                    
                    <option value="">--</option>
                    <option value="7">NS</option>
                    <?php for($ctr=0;$ctr<count($vartypes);$ctr++) { ?>
                    
                      <option value="<?php echo $vartypes[$ctr]['id'] ?>"><?php echo $vartypes[$ctr]['aosubtype_code'] ?></option> 
                            
                    <?php } ?>
                    
            </select> 
            
        </td>
    
    </tr>
   
</table>


