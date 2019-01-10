<?php $or_amt_total = 0; ?>
<?php $amt_due_total = 0; ?>
<?php $amt_paid_total = 0; ?>

<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>
      <dl class="tbody"  style="width: 1450px;font-size: 10px;">
           <dd style="width:100px"><?php echo $result[$ctr]['or_num'] ?>&nbsp;</dd>
           <dd style="width:150px"><?php echo $result[$ctr]['particulars'] ?>&nbsp;</dd>
           <dd style="width:60px"><?php echo $result[$ctr]['gov_status'] ?>&nbsp;</dd>
           <dd style="width:70px"><?php echo $result[$ctr]['or_amt'] ?>&nbsp;</dd>
           <dd style="width:70px"><?php echo $result[$ctr]['wtax_amt'] ?>&nbsp;</dd>
           <dd style="width:80px"><?php echo $result[$ctr]['wtax_percent'] ?>&nbsp;</dd>
           <dd style="width:50px"><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</dd>
           <dd style="width:50px"><?php echo $result[$ctr]['AdSize'] ?>&nbsp;</dd>
           <dd style="width:50px"><?php echo $result[$ctr]['ao_issuefrom'] ?>&nbsp;</dd>
           <dd style="width:70px"><?php echo $result[$ctr]['amount_due'] ?>&nbsp;</dd>
           <dd style="width:70px"><?php echo $result[$ctr]['amountpaid'] ?>&nbsp;</dd>
           <dd style="width:70px"><?php //echo $result[$ctr]['gov_status'] ?>&nbsp;</dd>
           <dd style="width:80px"><?php //echo $result[$ctr]['gov_status'] ?>&nbsp;</dd>


      </dl>
      
      <?php $or_amt_total += $result[$ctr]['or_amt']; ?>
      <?php $amt_due_total += $result[$ctr]['amount_due']; ?>
      <?php $amt_paid_total += $result[$ctr]['amountpaid']; ?>
      
<?php } ?>

    <dl class="tbody"  style="width: 1450px;font-size: 10px;">
           <dd style="width:100px">&nbsp;</dd>
           <dd style="width:150px">&nbsp;</dd>
           <dd style="width:60px">&nbsp;</dd>
           <dd style="width:70px">&nbsp;</dd>
           <dd style="width:70px;border-top:1px solid #000;border-bottom:1px solid #000;"><?php echo $or_amt_total ?>&nbsp;</dd>
           <dd style="width:80px">&nbsp;</dd>
           <dd style="width:50px">&nbsp;</dd>
           <dd style="width:50px">&nbsp;</dd>
           <dd style="width:50px">&nbsp;</dd>
           <dd style="width:70px;border-top:1px solid #000;border-bottom:1px solid #000;"><?php echo $amt_due_total ?>&nbsp;</dd>
           <dd style="width:70px;border-top:1px solid #000;border-bottom:1px solid #000;"><?php echo $amt_paid_total ?>&nbsp;</dd>
           <dd style="width:70px">&nbsp;</dd>
           <dd style="width:80px">&nbsp;</dd>
      </dl>