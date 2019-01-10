<div class="span1">Agency</div>
<div class="span2">
    <select class="agency_client" name="agency_from2" id="agency_from2">
              <option value=""></option>
             <?php for($ctr=0;$ctr<count($agency);$ctr++) { ?>
                    <option value="<?php echo $agency[$ctr]['agency_code'] ?>"><?php echo $agency[$ctr]['agency'] ?></option>
             <?php } ?>
    </select>
</div>

<div class="span1">To</div>
<div class="span2">
      <select class="agency_client" name="client_from2" id="client_from2">
                          <option value=""></option>
            </select>
</div>

 