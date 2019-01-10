<div class="block-fluid">      
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>CDR No:</b></div>    
        <div class="span1"><input type="text" name="cdr_no" id="cdr_no"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>CDR Date</b></div>    
        <div class="span1" style="width:80px"><input type="text" placeholder="From" id="datefrom" name="datefrom"/></div>
        <div class="span1" style="width:80px"><input type="text" placeholder="To" id="dateto" name="dateto"/></div>
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Client Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="client_name" id="client_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Agency Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="agency_name" id="agency_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">   
        <div class="span2" style="width:120px;margin-top:12px"><strong>Type of Ad</strong></div>                                                                                                                            
            <div class="span2" style="width:180px;margin-top:10px">
                <select name="type_ad" id="type_ad">       
                    <option value="">--All--</option>                                                          
                    <?php foreach ($type_ad as $row) : ?>
                    <option value="<?php echo $row['id'] ?>"><?php echo $row['adtype_name'] ?></option>
                    <?php endforeach; ?>                                                       
                </select>
        </div>
         <div class="clear"></div>    
    </div>
     <div class="row-form-booking">   
        <div class="span2" style="width:120px;margin-top:12px"><strong>CDR Type</strong></div>                                                                                                                            
            <div class="span2" style="width:180px;margin-top:10px">
                <select name="cdrtype" id="cdrtype">       
                    <option value="">--All--</option>                                                          
                    <?php foreach ($cdrtype as $row) : ?>
                    <option value="<?php echo $row['id'] ?>"><?php echo $row['cdrtype_name'] ?></option>
                    <?php endforeach; ?>                                                       
                </select>
        </div>
         <div class="clear"></div>    
    </div>

    <div class="row-form-booking">
        <div class="span3" style="width: 50px;"><button class="btn btn-success" type="button" name="searchbutton" id="searchbutton">Search</button></div>
        
        <div class="clear"></div>        
    </div>

</div>
<script>

var errorcssobj = {'background': '#E1CECE', 'border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E5E5E5', 'border' : '1px solid #E9EAEE'};

var xhr = "";

$("#searchbutton").click(function() {

    var cdr_no = $("#cdr_no").val();    
    var datefrom = $("#datefrom").val();    
    var dateto = $("#dateto").val();    
    var client_name = $("#client_name").val();    
    var agency_name = $("#agency_name").val();    
    var type_ad = $("#type_ad").val();    
    var cdrtype = $("#cdrtype").val();    
   
    xhr && xhr.abort();        
    xhr = $.ajax({        
        url: "<?php echo site_url('cdrform/searchdata') ?>",
        type: "post",
        data: {cdr_no: cdr_no, datefrom: datefrom, dateto: dateto, client_name: client_name, agency_name: agency_name,type_ad: type_ad, cdrtype: cdrtype },
        success: function(response) {
            $("#cdr_body").html($.parseJSON(response));      
            $("#modal_search").dialog('close');    
        }
        
    });
        
});                                                                                                

$('#datefrom').datepicker({dateFormat: "yy-mm-dd"})
$('#dateto').datepicker({dateFormat: "yy-mm-dd"})




</script>
