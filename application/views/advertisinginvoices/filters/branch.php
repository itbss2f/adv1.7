<div class="span1" style="white-space: nowrap;">From Branch</div> 

<div class="span2"> 

    <select name="from_branch" id="from_branch" class="chzn-select" style="width:300px">

        <option value=""></option>

        <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

            <option value="<?php echo $result[$ctr]['branch_name'] ?>"><?php echo $result[$ctr]['branch_name'] ?></option>

        <?php } ?>
        
    </select>
        
</div>   

<div class="span1" style="white-space: nowrap;">To Branch</div>

<div class="span2">
              
<select name="to_branch" id="to_branch" class="chzn-select" style="width:300px">

          <option value=""></option>
          
         <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
         
                <option value="<?php echo $result[$ctr]['branch_name'] ?>"><?php echo $result[$ctr]['branch_name'] ?></option>
        
         <?php } ?>
</select>

</div>
