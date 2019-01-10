<div class="span2" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo str_pad(@$aistatus['ao_rfa_num'], 8, "0", STR_PAD_LEFT); ?></p></div>
<div class="span2" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo str_pad(@$aistatus['ao_num'], 8, "0", STR_PAD_LEFT); ?></p></div>
<div class="span2" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo date('M d, Y', strtotime(@$aistatus['issuedate']));?></p></div>
<div class="span2" style="min-height: 10px;text-indent:10px;<?php if (@$aistatus['ao_rfa_aistatus'] == 'C') { echo 'color:red'; } else { echo 'color:green'; } ?>">
       											            <?php if (@$aistatus['ao_rfa_aistatus'] == 'C') { echo 'Cancelled'; } else { echo 'Active'; } ?>

</div>
<div class="span3" style="min-height: 10px;text-indent:10px"><p class="text-info"><?php echo @$aistatus['ao_rfa_supercedingai'] ?></p></div>
<div class="clear"></div>

