<div class="span1">From</div>
<div class="span2">
<select class="collector_area" name="collector_area_from" id="collector_area_from">
             <option value=""></option>
             <?php for($ctr=0;$ctr<count($collector_area);$ctr++) { ?>
                    <option value="<?php echo $collector_area[$ctr]['id'] ?>"><?php echo $collector_area[$ctr]['collarea_name'] ?></option>
             <?php } ?>
</select>
</div>

<div class="span1">To</div>
<div class="span2">
<select class="collector_area" name="collector_area_to" id="collector_area_to">
          <option value=""></option>
         <?php for($ctr=0;$ctr<count($collector_area);$ctr++) { ?>
                <option value="<?php echo $collector_area[$ctr]['id'] ?>"><?php echo $collector_area[$ctr]['collarea_name'] ?></option>
         <?php } ?>
</select>
</div>
