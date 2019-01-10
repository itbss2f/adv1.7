<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
       <tr >
            <td><?php echo $result[$ctr]['ao_cdr_num'] ?></td>
            <td><?php echo $result[$ctr]['ao_cdr_date'] ?></td>
            <td><?php echo $result[$ctr]['ao_num'] ?></td>
            <td><?php echo $result[$ctr]['issue_date'] ?></td>
            <td><?php echo $result[$ctr]['client_name'] ?></td>
            <td><?php echo $result[$ctr]['agency_code'] ?></td>
            <td><?php echo $result[$ctr]['PO'] ?></td>
            <td><?php echo $result[$ctr]['size'] ?></td>
            <td><?php echo $result[$ctr]['ccm'] ?></td>
            <td><?php echo $result[$ctr]['adtype_name'] ?></td>
            <td ><?php  if(!empty($result[$ctr]['ao_sinum']) AND $result[$ctr]['ao_sinum'] !='0') { echo "Invoiced"; } else { ?> 
                       <div class="span1"><button ao_id = "<?php echo $result[$ctr]['id'] ?>" class="btn cdrbtn" type="button">CDR FORM</button> 
                <?php } ?></td>
       </tr>
 <?php } ?>
 <?php if(count($result) ==0) { ?>
        <tr>
            <td colspan="8" style="text-align: center;">NO RESULTS FOUND</td>
        </tr>
 <?php } ?>