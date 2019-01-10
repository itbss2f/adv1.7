<option value=""></option>
<?php  foreach($result as $result) : ?>
<option value="<?php echo $result->id ?>"><?php echo $result->cmf_name ?></option> 
<?php  endforeach; ?>
