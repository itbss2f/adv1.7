<div class="block-fluid">
    <div class="row-form-booking"> 
        <div class="span1" style="width: 60px; margin-top:12px"><b>Invoice#:</b></div>
        <div class="span1" style="width: 120px; margin-top:5px"><input type="text" id="invnum" name="invnum" value="<?php echo $info['ao_sinum'] ?>" readonly="readonly"/></div>
        <div class="span1" style="width: 120px; margin-top:5px"><input type="hidden" id="id" name="id" value="<?php echo $info['id'] ?>" readonly="readonly" disabled="disabled"/></div>
        <div class="clear"></div>
    </div>                                                                              
    <div class="row-form-booking">
        <?php if ($info['ao_return_inv_stat'] == 1): ?> 
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;"><input type="checkbox" id="returninv" name="returninv" value="1" <?php if ($info['ao_return_inv_stat'] == 1) { echo "disabled='disabled'";} { echo "checked='checked'"; } ?> >Return Invoice</div>
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;"><input type="text" id="returninvdate" name="returninvdate" class="datepicker" value="<?php echo $info['ao_return_inv'] ?>"></div>    
        <?php else: ?> 
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;"><input type="checkbox" id="returninv" name="returninv" value="1" <?php if ($info['ao_return_inv_stat'] == 0) ?> >Return Invoice</div> 
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;"><input type="text" id="returninvdate" name="returninvdate" class="datepicker" value="<?php echo $info['ao_return_inv'] ?>"></div>    
        <?php endif;?>
        <div class="clear"></div>
    </div>          
    <div class="row-form-booking">  
        <?php if ($info['ao_dateto_inv_stat'] == 1): ?>
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;" id="datetoxx"><input type="checkbox" id="dateto" name="dateto" value="1" <?php if ($info['ao_dateto_inv_stat'] == 1) { echo "disabled='disabled'";} { echo "checked='checked'"; } ?> >Date To Advtg</div>
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;"><input type="text" id="datetodate" name="datetodate" class="datepicker" value="<?php echo $info['ao_dateto_inv'] ?>"></div> 
        <?php else:?>
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px; <?php if ($info['ao_return_inv_stat'] == 0) { echo "display:none"; }?>" id="datetox"><input type="checkbox" id="dateto" name="dateto" value="1" <?php if ($info['ao_dateto_inv_stat'] == 0) ?> >Date To Advtg</div> 
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;"><input type="text" id="datetodate" name="datetodate" class="datepicker" value="<?php echo $info['ao_dateto_inv'] ?>" style="<?php if ($info['ao_return_inv_stat'] == 0) { echo "display:none"; }?>"></div> 
        <?php endif;?>  
        <div class="clear"></div>
    </div>         
    <div class="row-form-booking">   
        <?php if ($info['ao_datefrom_inv_stat'] == 1): ?>
        <div class="span1 datefromxx" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;"><input type="checkbox" id="datefrom" name="datefrom" value="1" <?php if ($info['ao_datefrom_inv_stat'] == 1) { echo "disabled='disabled'";} { echo "checked='checked'"; } ?> >Date From Advtg</div>
        <div class="span1 datefromxx" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;"><input type="text" id="datefromdate" name="datefromdate" class="datepicker" value="<?php echo $info['ao_datefrom_inv'] ?>"></div> 
        <?php else:?>
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;<?php if ($info['ao_dateto_inv_stat'] == 0) { echo "display:none"; } ?>" id="datefromx"><input type="checkbox" id="datefrom" name="datefrom" value="1" <?php if ($info['ao_datefrom_inv_stat'] == 1) { echo "checked='checked'";} ?>>Date From Advtg</div>          
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;"><input type="text" id="datefromdate" name="datefromdate" class="datepicker" value="<?php echo $info['ao_datefrom_inv'] ?>" style="<?php if ($info['ao_dateto_inv_stat'] == 0) { echo "display:none"; } ?>"></div>
        <?php endif;?>  
        <div class="clear"></div>
    </div>         
    <div class="row-form-booking">   
        <?php if ($info['ao_datetocol_inv_stat'] == 1): ?>   
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;<?php if ($info['ao_datetocol_inv_stat'] == 0) { echo "display:none"; } ?>" id="datetocoldatex"><input type="checkbox" id="datetocol" name="datetocol" value="1" <?php if ($info['ao_datetocol_inv_stat'] == 1) { echo "checked='checked'";} ?>>Date To Coll</div>          
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;"><input type="text" id="datetocoldate" name="datetocoldate" class="datepicker" value="<?php echo $info['ao_datetocol_inv'] ?>" style="<?php if ($info['ao_datetocol_inv_stat'] == 0) { echo "display:none"; } ?>"></div>
        <?php else:?>   
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;<?php if ($info['ao_datefrom_inv_stat'] == 0) { echo "display:none"; } ?>" id="datetocoldatex"><input type="checkbox" id="datetocol" name="datetocol" value="1" <?php if ($info['ao_datetocol_inv_stat'] == 1) { echo "checked='checked'";} ?>>Date To Coll</div>          
        <div class="span1" style="margin-top: 5px;width:150px;min-height: 10px;font-weight: bold;font-size: 14px;"><input type="text" id="datetocoldate" name="datetocoldate" class="datepicker" value="<?php echo $info['ao_datetocol_inv'] ?>" style="<?php if ($info['ao_datefrom_inv_stat'] == 0) { echo "display:none"; } ?>"></div>  
        <?php endif;?>      
        <div class="clear"></div>         
    </div>
    <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="save" name="save" type="button">Save</button></div>
    <div class="clear"></div>
</div>


<script>
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});  
$(function() {
  var returninv = $("#returninv");
  var dateto = $("#datetox");
  var datetodate = $("#datetodate");
  
  returninv.change(function() {
    if (returninv.is(':checked')) {
      dateto.show();
      datetodate.show();
      $("#dateto").show();       
      $("#datetoxx").show();       
    } else {
    $("#dateto").attr("checked", false);
      dateto.hide();
      $("#dateto").hide();       
      $("#datetoxx").hide();       
      datetodate.hide();   
    }
  });
});

$(function() {
  var dateto = $("#dateto");
  var datefrom = $("#datefromx");
  var datefromdate = $("#datefromdate");
  
  dateto.change(function() {
    if (dateto.is(':checked')) {
      datefrom.show();
      datefromdate.show();
      $(".datefromxx").show();  
      $('#returninv').attr('disabled', 'disabled');
    } else {
      $("#datefrom").attr("checked", false); 
      datefrom.hide(); 
      datefromdate.hide(); 
      $(".datefromxx").hide();       
      $('#returninv').removeAttr('disabled');    
    }
  });
});

$(function() {
    var dateto = $("#dateto");
    var datefrom = $("#datefrom");
    var datefrom = $("#datefrom");

  datefrom.change(function() {
    if (datefrom.is(':checked')) {
      $('#dateto').attr('disabled', 'disabled');
    } else {
      $('#dateto').removeAttr('disabled');  
    }
  });
});

$(function() {
    var datetocol = $("#datetocol");
    var datefrom = $("#datefrom");

  datetocol.change(function() {
    if (datetocol.is(':checked')) {
      $('#datefrom').attr('disabled', 'disabled');
    } else {
      $('#datefrom').removeAttr('disabled');  
    }
  });
});

$(function() {
    var returninv = $("#returninv");  
    var dateto = $("#datetox");
    var datefrom = $("#datefrom");     
    var datetocoldatex = $("#datetocoldatex");
    var datetocoldate = $("#datetocoldate");

  datefrom.change(function() {
    if (datefrom.is(':checked')) {
       
       datetocoldatex.show();
        datetocoldate.show();
        datetocoldate.css({'display': 'block'});
        $('#returninv').attr('disabled', 'disabled');
        $('#datetox').attr('disabled', 'disabled');
    } else {
        //Do Nothing
        datetocoldatex.hide();
        datetocoldate.hide();
        $("#datefrom").attr("checked", false);  
        
    }
  });
});




$('#save').click(function() {
    var countValidate = 0;  
    var validate_fields = [];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) {        
        $.ajax({
            url: "<?php echo site_url('aiform/savereturninv') ?>",
            type: "post",
            data: {    sinum: "<?php echo $info['ao_sinum'] ?>",
                    returninv: $("#returninv:checked").val(),
                    returninvdate: $("#returninvdate").val(),
                    dateto: $("#dateto:checked").val(),
                    datetodate: $("#datetodate").val(),  
                    datefrom: $("#datefrom:checked").val(),
                    datefromdate: $("#datefromdate").val(),  
                    datetocol: $("#datetocol:checked").val(),
                    datetocoldate: $("#datetocoldate").val(), 
                     },
            success: function(response) {
                alert('Successfully update');
            }
        });
    } else {            
        return false;
    }    
});



    
</script>

