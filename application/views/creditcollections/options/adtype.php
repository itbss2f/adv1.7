<div class="span1">From</div>
<div class="span2">
<select class="adtype" name="adtype_select_from" id="adtype_select_from">
         <option value=""></option>
         <?php for($ctr=0;$ctr<count($adtypes);$ctr++) { ?>
                <option value="<?php echo $adtypes[$ctr]['id'] ?>"><?php echo $adtypes[$ctr]['adtype_name'] ?></option>
         <?php } ?>
</select>
</div>

<div class="span1">To</div>
<div class="span2">
<select class="adtype" name="adtype_select_to" id="adtype_select_to">
           <option value=""></option>
           <?php for($ctr=0;$ctr<count($adtypes);$ctr++) { ?>
                 <option value="<?php echo $adtypes[$ctr]['id'] ?>"><?php echo $adtypes[$ctr]['adtype_name'] ?></option>
           <?php } ?>
</select>
</div> 
