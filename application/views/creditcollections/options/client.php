<div class="span1">From</div>
<div class="span2">
        <select  class="client" name="client_from" id="client_from">
                      <option value=""></option>
                      <?php for($ctr=0;$ctr<count($client);$ctr++) { ?>
                            <option value="<?php echo $client[$ctr]['client'] ?>"><?php echo $client[$ctr]['client'] ?></option>
                     <?php } ?>
        </select>
</div>

<div class="span1">To</div>
<div class="span2">
<select   class="client" name="client_to" id="client_to">
         <option value=""></option>
         <?php for($ctr=0;$ctr<count($client);$ctr++) { ?>
                <option value="<?php echo $client[$ctr]['client'] ?>"><?php echo $client[$ctr]['client'] ?></option>
         <?php } ?>
</select>
</div>
