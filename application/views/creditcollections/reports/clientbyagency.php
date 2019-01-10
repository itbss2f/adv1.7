  
  <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
             
               <option value="<?php echo $result[$ctr]['cmf_code'] ?>"><?php echo $result[$ctr]['client_name'] ?></option>
  <?php } ?>