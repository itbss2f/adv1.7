
<?php if(!empty($result)) { ?>
                
<?php foreach($result as $result) { ?>
       
     <tr>
     
        <td><input type="checkbox" name="chk"/></td>
        <td><input type="text" value="<?php echo $result->barter_condition ?>" name="barter_condition[]" style="width:100%" name="txt"/></td>
        <td style="text-align: center;" class="opts">
        
                <select name="condition_type[]" style="width:100px;margin:0px;">
                        <option value="0"> -- </option>
                        <option <?php if($result->category_id == 1){ echo "selected";} ?> value="1">Commodities</option>
                        <option <?php if($result->category_id == 2){ echo "selected";} ?> value="2">Gift Check</option>
                        <option <?php if($result->category_id == 3){ echo "selected";} ?> value="3">Hotel/Resort</option>
                </select>
        </td>    
       
    </tr>  
    
<?php }  ?>

<?php }else { ?>

        <tr>
                <td><INPUT type="checkbox" name="chk"/></td>
                <td><INPUT type="text" name="barter_condition[]" style="width:100%" name="txt"/></td>
                <td>
                    <select name="condition_type[]" style="width:100px">
                                    <option value="0"> -- </option>
                                    <option value="1">Commodities</option>
                                    <option value="2">Gift Check</option>
                                    <option value="3">Hotel/Resort</option>
                     </select> 
                     </td>
        </tr>

<?php } ?>