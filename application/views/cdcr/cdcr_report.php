<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>
      <dl class="tbody"  style="width: 1450px;font-size: 10px;">
           <dd style="width:100px"><?php echo $result[$ctr]['or_num'] ?>&nbsp;</dd>
           <dd style="width:150px"><?php echo $result[$ctr]['particulars'] ?>&nbsp;</dd>
           <dd style="width:60px"><?php echo $result[$ctr]['gov_status'] ?>&nbsp;</dd>
           <dd style="width:150px"><?php echo $result[$ctr]['remarks'] ?>&nbsp;</dd>
           <dd style="text-align: right;width:70px;"><?php if($result[$ctr]['paytype'] == 'CH'){ echo $result[$ctr]['amount']; } ?>&nbsp;</dd>
           <dd style="width:70px"><?php echo $result[$ctr]['check_no'] ?>&nbsp;</dd>
           <dd style="text-align: right;width:70px;"><?php if($result[$ctr]['paytype'] == 'CK'){ echo $result[$ctr]['amount']; } ?>&nbsp;</dd>
           <dd style="width:80px"><?php echo $result[$ctr]['wtax_amt'] ?>&nbsp;</dd>
           <?php if(in_array($cdcr_type, $for_cashier)) { ?>
           <dd style="width:70px"><?php echo $result[$ctr]['empprofile_code'] ?>&nbsp;</dd>
           <?php } ?>
           <dd style="width:50px"><?php echo $result[$ctr]['wtax_percent'] ?>&nbsp;</dd>
           <dd style="width:50px"><?php echo $result[$ctr]['adtype_code'] ?>&nbsp;</dd>
           <dd style="width:58px;border-right:none"><?php echo $result[$ctr]['bank_code'] ?>&nbsp;</dd>
      </dl>
<?php } ?>