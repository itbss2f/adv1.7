<?php #print_r2($data); ?>
<div class="block-fluid">   
    <?php if ($data['or_type'] == 2) : ?>   
    <div class="row-form-booking">
        <div class="span2"><b>Advertiser Name</b></div>    
        <div class="span3"><input type="text" name="orpayee" id="orpayee" value="<?php echo $data['or_payee'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <?php else : ?>   
    <input type="hidden" name="orpayee" id="orpayee" value="<?php echo $data['or_payee'] ?>">
    <?php endif; ?>
    
    <div class="row-form-booking">
        <div class="span1"><b>OR #</b></div>    
        <div class="span1"><input type="text" name="ornum2" id="ornum2" value="<?php echo $data['or_num'] ?>"></div>        
        <div class="span1"><input type="checkbox" name="checkor" id="checkor" value="1"> Change</div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span1"><b>OR Date</b></div>    
        <div class="span1"><input type="text" name="ordate" id="ordate" class="datepicker" readonly="readonly" value="<?php echo $data['ordate'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save button</button></div>        
        <div class="clear"></div>        
    </div>
</div>


<script>
$('#ornum2').mask('99999999');
// $(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});  


//Antidate in 5days
$(".datepicker").datepicker({   
    dateFormat: 'yy-mm-dd',      
    minDate: -5, maxDate: "2D"      
});

// $('.datepicker').datepicker({
//         dateFormat: 'mm-dd-yyyy',
//         //maxDate: '+2'
//     }).on('changeDate', function(ev){
//         $('#sDate1').text($('.datepicker').data('date'));
//         $('.datepicker').datepicker('hide');
// });

$('#save').click(function() {
    var $id = '<?php echo $id; ?>';
    
    var orpayee = $('#orpayee').val();
    var ordate = $('#ordate').val();
    var ornum = $('#ornum2').val(); 
    
    //var chck = $('#checkor').attr('checked'); 
    
    if($("#checkor").is(':checked'))  {
        
           $.ajax({
                url: '<?php echo site_url('ordatafix/validateORnumber') ?>',
                type: 'post',
                data: {orno: ornum},
                success: function(response) { 
                    
                    if (response == true) {
                        alert('OR Number already exist!');
                        
                    } else {
                        $.ajax({
                            url: '<?php echo site_url('ordatafix/saveDatafix') ?>',
                            type: 'post',
                            data: {id: $id, orpayee: orpayee, ordate: ordate, ornum: ornum},
                            success: function(response) {
                                var $response = $.parseJSON(response);       
                                
                                $('#dataresult').html($response['result']); 
                                
                                $('#modal_edit').dialog('close');                   
                            }    
                        });     

                    }

                }    
            });
    } else {
        ornum = $id;
        $.ajax({
            url: '<?php echo site_url('ordatafix/saveDatafix') ?>',
            type: 'post',
            data: {id: $id, orpayee: orpayee, ordate: ordate, ornum: ornum},
            success: function(response) {
                var $response = $.parseJSON(response);       
                
                $('#dataresult').html($response['result']); 
                
                $('#modal_edit').dialog('close');                   
            }    
        });    
        
    } 
    
    
});
</script>