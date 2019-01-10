<table cellpadding="0" cellspacing="0" border="0">
   <thead>
    <tr style="white-space: nowrap;text-align: center;">
        <th style="width:90px;">Contract #</th>
        <th style="width:90px;">Contract Date</th>
        <th style="width:160px;">Client Name</th>
        <th style="width:160px;">Agency</th>
        <th style="width:80px;">Total Cost</th>
        <th style="width:100px;">Contact Person</th>
        <th style="width:90px;">Phone</th>
        <th style="width:90px;">Barter Ratio</th>
        <th style="width:250px;">Remarks</th>
   </tr>
  </thead>
  <tbody>
  <?php foreach($result as $result) { ?>
    <tr>
        <td><?php echo $result->contract_no?></td>
        <td><?php echo $result->contract_date ?></td>
        <td><?php echo $result->advertiser ?></td>
        <td><?php echo $result->advertising_agency ?></td>
        <td style="text-align:right"><?php echo number_format($result->amount,2,'.',',') ?></td>
        <td style="text-align: center;"><?php echo $result->contact_person ?></td>
        <td style="text-align: center;"><?php echo $result->telephone ?></td>
        <td style="text-align: center;"><?php echo $result->barter_ratio ?></td>
        <td><?php echo $result->remarks ?></td>
    </tr>
  <?php } ?>  
  </tbody>
</table>