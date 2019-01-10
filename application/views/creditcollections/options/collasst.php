<div class="span1">From</div>
<div class="span2">
<select  class="collasst" name="coll_asst_from" id="coll_asst_from">
          <option value=""></option>
         <?php for($ctr=0;$ctr<count($coll_asst);$ctr++) { ?>
                <option value="<?php echo $coll_asst[$ctr]['full_name'] ?>"><?php echo $coll_asst[$ctr]['full_name'] ?></option>
         <?php } ?>
</select>
</div>

<div class="span1">To</div>
<div class="span2">
<select  class="collasst" name="coll_asst_to" id="coll_asst_to">
              <option value=""></option>
             <?php for($ctr=0;$ctr<count($coll_asst);$ctr++) { ?>
                    <option value="<?php echo $coll_asst[$ctr]['full_name'] ?>"><?php echo $coll_asst[$ctr]['full_name'] ?></option>
             <?php } ?>
</select>
</div>
