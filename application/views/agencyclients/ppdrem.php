<div class="block-fluid">     

    <div class="row-form-booking">
        <div class="span1" style="width:100px"><b>Agency :</b></div>    
        <div class="span3"><b><?php echo $data['agencycode'].' - '.$data['agencyname'] ?></b></div>        
        <div class="clear"></div>
    </div>
    <div class="row-form-booking">
        <div class="span1" style="width:100px"><b>Client :</b></div>    
        <div class="span3"><b><?php echo $data['cmf_code'].' - '.$data['cmf_name'] ?></b></div>        
        <div class="clear"></div>
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>PPD Percentage</b></div>    
        <div class="span1"><input type="text" name="ppd_percent" id="ppd_percent" style="text-align: right;" value="<?php echo $data['acmf_ppd'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Remarks</b></div>    
        <div class="span3"><input type="text" name="ppd_rem" id="ppd_rem" value="<?php echo $data['acmf_rem'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="saveppd" id="saveppd">Save PPD Data</button></div>        
        <div class="clear"></div>        
    </div>
</div>

<script>

$('#ppd_percent').autoNumeric({});
$('#saveppd').click(function(){
    var id = "<?php echo $data['mid'] ?>";
    var ppdper = $('#ppd_percent').val();
    var ppdrem = $('#ppd_rem').val();
    $.ajax({
        url: '<?php echo site_url('agencyclient/ppdremarkssave') ?>',
        type: 'post',
        data: {id : id, ppdper: ppdper, ppdrem: ppdrem},
        success: function() {
              $("#modal_ppddata").dialog('close');
              var agencyid = $('#agency').val();
   
               $.ajax({
                   url: '<?php echo site_url('agencyclient/clientlist') ?>',
                   type: 'post',
                   data: {agencyid: agencyid},
                   success: function(response) {
                       var $response = $.parseJSON(response);
                       $('#clientnoagency').html($response['clientnoagency']);
                       $('#underclient').html($response['underclient']);           
                   }
               })
        }    
    });    
});

</script>