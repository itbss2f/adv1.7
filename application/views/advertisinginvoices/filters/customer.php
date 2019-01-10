<div class="span1" style="white-space: nowrap;">From Customer</div>

<div class="span2">

    <select name="from_customer" id="from_customer" class="chzn-select" style="width:300px">

              <option value=""></option>
              
             <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
             
                    <option value="<?php echo $result[$ctr]['client_name'] ?>"><?php echo $result[$ctr]['client_name'] ?></option>
            
             <?php } ?>
             
    </select>

</div>  

<div class="span1" style="white-space: nowrap;">To Customer</div>    

<div class="span2">

<select name="to_customer" id="to_customer" class="chzn-select" style="width:300px">

              <option value=""></option>
              
             <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
             
                    <option value="<?php echo $result[$ctr]['client_name'] ?>"><?php echo $result[$ctr]['client_name'] ?></option>
            
             <?php } ?>
             
</select>

</div>          
  