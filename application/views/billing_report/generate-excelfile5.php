 <thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">BOOKING TYPE - <b><td style="text-align: left"><?php echo $bookingtype ?><br/></b> 
    <b><td style="text-align: left">REPORT TYPE - <b><td style="text-align: left"><?php echo $reporttype ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>    
</thead>

<table cellpadding="0" cellspacing="0" width="100%" border="1">    
<thead>
  <tr>
        <th>ID #</th>
        <th>AO No.</th>
        <th>Section</th>                                                                     
        <th>Client Name</th>                                                                     
        <th>Agency Name</th>                                                                                                                                                                                                            
        <th>Product Title</th>                                                                                                              
        <th>Classification</th> 
        <th>Adtype</th>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
        <th>Billing Adtype</th>                                                                      
  </tr>
</thead>

<?php $subtotalamt = 0; $subtotalccm = 0; $grandtotalccm = 0; $grandtotalamt = 0; $x = 1; ?>
<?php foreach ($dlist as $sectionname => $xlist) : ?>  
    <tr>
        <td colspan = "9" style="background: #CCCCCC; font-size: 12px; color: red; font-weight: 300"><?php if ($sectionname == '') { echo 'No Section'; } else { echo $sectionname; } ?></td>
    </tr>
    <?php $subtotalamt = 0;  $subtotalccm = 0; $x = 1; ?>
    <?php foreach ($xlist as $list) : ?>
    <?php 
    if ($list['section'] != '') :
    $subtotalamt += $list['ao_grossamt']; $subtotalccm += $list['ao_totalsize'];  $grandtotalccm += $list['ao_totalsize']; $grandtotalamt += $list['ao_grossamt']; 
    endif;
    ?>
    <tr>
        <td style="color: blue;" class="resultlist" id="<?php echo $list['id'] ?>"><a href="#"><?php echo $list['id'] ?></a></td>                
        <td><?php echo $list['ao_num'] ?></td>                
        <td><?php echo $list['section'] ?></td>                
        <td title="<?php echo $list['clientname'] ?>"><?php echo character_limiter($list['clientname'], 20, ''); ?></td>
        <td title="<?php echo $list['agencyname'] ?>"><?php echo $list['agencyname']; ?></td>
        <td><?php echo $list['billingproduct'] ?></td>

        <td><?php echo $list['class_code'].' - '.$list['class_name'] ?></td>                
        <td><?php echo $list['adtype_code'].' - '.$list['adtype_name'] ?></td>
        <td><?php echo $list['billingadtype'] ?></td>
    </tr>
    <?php endforeach; ?> 
    <?php if ($sectionname != '') : ?>
    <tr>
        <td colspan="5" style="text-align: right;background: #CCCCCC; font-size: 12px; color: red; font-weight: 300"><b>SUBTOTAL : </b></td>        
        <td style="text-align: right;font-size: 12px; color: red; font-weight: 300"><?php echo number_format($subtotalccm, 2, '.', ','); ?></td>
        <td style="text-align: right;font-size: 12px; color: red; font-weight: 300"><?php echo number_format($subtotalamt, 2, '.', ','); ?></td>
    </tr> 
    <?php endif; ?>                                                          
<?php endforeach; ?>   
    <tr>
        <td colspan="5" style="text-align: right;background: #CCCCCC; font-size: 12px; color: red; font-weight: 300"><b>GRANDTOTAL : </b></td>        
        <td style="text-align: right;font-size: 12px; color: red; font-weight: 300"><?php echo number_format($grandtotalccm, 2, '.', ','); ?></td>
        <td style="text-align: right;font-size: 12px; color: red; font-weight: 300"><?php echo number_format($grandtotalamt, 2, '.', ','); ?></td> 
    </tr>    


<script>
$('#viewmovieclass').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: 400,    
    height:'auto',
    modal: true,
    resizable: false    
});
$('.resultlist').click(function() {
    var id = $(this).attr('id');   
    
    $.ajax({
        url: '<?php echo site_url('billing_report/ajaxMovieClassification') ?>',
        type: 'post',
        data: {id : id, type: $('#bookingtype').val()},
        success: function(response) {
            $response = $.parseJSON(response);
            
            $('#viewmovieclass').html($response['view']).dialog('open');
        }
    });
    
});

</script>                           
                                 