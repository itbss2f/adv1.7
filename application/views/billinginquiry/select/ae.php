<table align="center">
    
    <tr>
    
        <td style="vertical-align: middle;">AE : </td>
        
        <td style="vertical-align: middle;">
                
              <select class='myae' style="width: 100px;padding-bottom:0px;margin-bottom:0px;"  id="myae<?php echo $cell_id ?>"  onchange="changevalue('#ae<?php  echo $cell_id ?>','<?php  echo $ao_p_id ?>',this.value)">
              
                <option value=""></option>
                
                <?php for($ctr=0;$ctr<count($employee);$ctr++) { ?>
                
                 <option  <?php if($current_select==$employee[$ctr]['empprofile_code']) { echo "selected";} ?> value="<?php echo $employee[$ctr]['user_id'] ?>"><?php echo $employee[$ctr]['empprofile_code'] ?></option>
                 
                 <?php } ?>
                                                
              </select> 
        
        </td>
    
    </tr>
   
</table>


