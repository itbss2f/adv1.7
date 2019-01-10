<div class="span1">From</div>
<div class="span2">
        <select  class="cashier" name="cashier_from" id="cashier_from">
             <option value=""></option>
             <?php for($ctr=0;$ctr<count($collector);$ctr++) { ?>
                    <option value="<?php echo $collector[$ctr]['full_name'] ?>"><?php echo $collector[$ctr]['full_name'] ?></option>
             <?php } ?>
        </select>
</div>

<div class="span1">To</div>
<div class="span2">
<select   class="cashier" name="cashier_to" id="cashier_to">
         <option value=""></option>
         <?php for($ctr=0;$ctr<count($collector);$ctr++) { ?>
                <option value="<?php echo $collector[$ctr]['full_name'] ?>"><?php echo $collector[$ctr]['full_name'] ?></option>
         <?php } ?>
</select>
</div>
