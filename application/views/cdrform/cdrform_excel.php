
<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">Complaint Discrepancy Report <b><br/></b> 
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($issue_date)); ?>    
</tr>
</thead>

     <table cellpadding="0" cellspacing="0" width="80%" border="1">  
      
<thead>
  <tr>

            <th width="10%">CDR #</th>
            <th width="10%">CDR Date</th>
            <th width="10%">AO Number</th>
            <th width="10%">Issue Date</th>
            <th width="10%">Client Name</th>      
            <th width="10%">Agency Code</th>
            <th width="10%">PO#</th> 
            <th width="10%">Size</th>
            <th width="10%">Total CCM</th>
            <th width="10%">Adtype Name</th>
            <th width="10%">Action</th>

               
  </tr>
</thead>

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
 <?php if(count($result) == 0) { ?>
        <tr>
            <td colspan="8" style="text-align: center;">NO RESULTS FOUND</td>
        </tr>
 <?php } ?>

  
              
   
              
              
              
              
              
              
              
              
              
              
              