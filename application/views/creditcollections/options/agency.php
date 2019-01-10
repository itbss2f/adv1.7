<div class="span1">From</div>
<div class="span2">
<select class="agency" name="agency_from" id="agency_from">
              <option value=""></option>
             <?php for($ctr=0;$ctr<count($agency);$ctr++) { ?>
                    <option value="<?php echo $agency[$ctr]['agency_code'] ?>"><?php echo $agency[$ctr]['agency'] ?></option>
             <?php } ?>
</select>
</div>

<div class="span1">To</div>
<div class="span2">
<select class="agency" name="agency_to" id="agency_to">
              <option value=""></option>
             <?php for($ctr=0;$ctr<count($agency);$ctr++) { ?>
                    <option value="<?php echo $agency[$ctr]['agency_code'] ?>"><?php echo $agency[$ctr]['agency'] ?></option>
             <?php } ?>
</select>
</div>
