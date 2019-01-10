
<div class="breadLine">
    <?php echo $breadcrumb; ?>
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Marketing Report</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>
                    </ul>
                <div class="clear"></div>
            </div>
        </div>
        <div class="block-fluid">
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:70px;margin-top:12px">Date Retrieval:</div>
                <div class="span1" style="width:60px;margin-top:12px"><input type="text" id="datefrom" placeholder="FROM" name="" class="datepicker"/></div>
                <div class="span1" style="width:60px;margin-top:12px"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker"/></div>

                <div class="span1" style="width:70px;margin-top:12px">Report Type</div>
                <div class="span2" style="margin-left:0px;width:100px;margin-top:12px">
                    <select name="reporttype" id="reporttype">
                        <option value="1">Industry</option>
                        <option value="2">Comparative</option>
                    </select>
                </div>

                <div class="span1" style="width:70px;margin-top:12px">Top Type</div>
                <div class="span2" style="margin-left:0px;width:100px;margin-top:12px">
                    <select name="toptype" id="toptype">
                        <option value="1">Client</option>
                        <option value="2">Agency</option>
                        <option value="3">Direct</option>
                        <option value="4">AE</option>
                        <option value="5">Summary</option>
                        <option value="6">Main Group</option>
                    </select>
                </div>

                <div class="span1" style="width:70px;margin-top:12px">Retrieve Type</div>
                <div class="span2" style="margin-left:0px;width:100px;margin-top:12px">
                    <select name="rettype" id="rettype">
                        <option value="0">Actual</option>
                        <option value="2">Actual Net</option>
                        <option value="1">Forecast</option>
                        <option value="3">Forecast Net</option>
                    </select>
                </div>

                <div class="span2" style="width:50px;margin-top:12px">Top Rank</div>
                <div class="span2" style="width:50px;margin-top:12px"><input type="text" id="toprank" value="15" name="toprank" style="text-align: center;"/></div>
                <div class="span1" style="width:70px;margin-top:12px;">Product</div>
                <div class="span3" style="margin-left:0px;width:250px;margin-top:12px;">
                    <select name="prod" id="prod">
                        <option value="0">--All--</option>
                        <?php foreach ($prod as $prod) : ?>
                        <option value="<?php echo $prod['id'] ?>"><?php echo $prod['prod_code'].' - '.$prod['prod_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="clear"></div>
            </div>


            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1 vind" style="width:70px;margin-top:12px;">Booking</div>
                <div class="span2" style="margin-left:0px;width:100px;margin-top:12px">
                    <select name="booktype" id="booktype">
                        <option value="0">All</option>
                        <option value="D">Display</option>
                        <option value="C">Classifieds</option>
                    </select>
                </div>
                <div class="span1 vind" style="width:70px;margin-top:12px;">Industry</div>
                <div class="span3 vind" style="margin-left:0px;width:250px;margin-top:12px;">
                    <select name="ind" id="ind">
                        <option value="0">--All--</option>
                        <?php foreach ($industries as $industries) : ?>
                        <option value="<?php echo $industries['id'] ?>"><?php echo $industries['ind_code'].' - '.$industries['ind_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="span1" style="width:70px;margin-top:12px;">Advertiser</div>
                <div class="span1" style="margin-top:12px;"><input type="text" placeholder="Code" id="advcode" name="advcode"/></div>
                <div class="span2" style="margin-top:12px;"><input type="text" placeholder="Name" id="advname" name="advname"/></div>

                <div class="span2" style="width:150px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>
                <div class="span2" style="width:150px;margin-top:12px"><button class="btn btn-success" id="exportreport" type="button">Export button</button></div>

                <div class="clear"></div>
            </div>

            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>

            </div>
        </div>
    </div>

    <div class="dr"><span></span></div>
</div>

<script>

$('#reporttype').change(function() {
    $val = $(this).val();
    $('#toptype').empty();
    $('#toptype').append('<option value="1">Client</option>');
    $('#toptype').append('<option value="2">Agency</option>');
    $('#toptype').append('<option value="3">Direct</option>');
    $('#toptype').append('<option value="4">AE</option>');
    $('#toptype').append('<option value="5">Summary</option>');
    $('#toptype').append('<option value="6">Main Group</option>');
    if ($val == 2) {
        $('#toptype').empty();
        $('#toptype').append('<option value="1">Client</option>');
        $('#toptype').append('<option value="2">Agency</option>');
        $('#toptype').append('<option value="3">Direct</option>');
        $('#toptype').append('<option value="6">Main Group</option>');
    }
});

$( "#advcode" ).autocomplete({
    source: function( request, response ) {
        $.ajax({
            //url: 'http://128.1.200.249/hris/public/api/json/employees',
            url: '<?php echo site_url('marketing_report/getClientInfo') ?>',
            type: "post",
            data: {   search: request.term
                   },
            success: function(data) {

                var $data = $.parseJSON(data);
                 response($.map($data, function(item) {
                      return {
                             label: item.cmf_name + ' | ' + item.cmf_code,
                             value: item.cmf_code,
                             item: item
                      }
                 }));
            }
        });
    },
    autoFocus: false,
    minLength: 2,
    delay: 300,
    select: function(event, ui) {
        $(':input[name=advcode]').val(ui.item.item.cmf_code);
        $(':input[name=advname]').val(ui.item.item.cmf_name);
    }
});
$( "#advname" ).autocomplete({
    source: function( request, response ) {
        $.ajax({
            //url: 'http://128.1.200.249/hris/public/api/json/employees',
            url: '<?php echo site_url('marketing_report/getClientInfo') ?>',
            type: "post",
            data: {   search: request.term
                   },
            success: function(data) {

                var $data = $.parseJSON(data);
                 response($.map($data, function(item) {
                      return {
                             label: item.cmf_name + ' | ' + item.cmf_code,
                             value: item.cmf_name,
                             item: item
                      }
                 }));
            }
        });
    },
    autoFocus: false,
    minLength: 2,
    delay: 300,
    select: function(event, ui) {
        $(':input[name=advcode]').val(ui.item.item.cmf_code);
        $(':input[name=advname]').val(ui.item.item.cmf_name);
    }
});
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'};
$("#generatereport").click(function(response) {

    var countValidate = 0;
    var validate_fields = ['#datefrom', '#dateto', '#toprank', '#reporttype'];

    for (x = 0; x < validate_fields.length; x++) {
        if($(validate_fields[x]).val() == "") {
            $(validate_fields[x]).css(errorcssobj);
              countValidate += 1;
        } else {
              $(validate_fields[x]).css(errorcssobj2);

        }
    }

    if (countValidate == 0) {
        var datefrom = $("#datefrom").val();
        var dateto = $("#dateto").val();
        var reporttype = $("#reporttype").val();
        var toptype = $("#toptype").val();
        var toprank = $("#toprank").val();
        var industry = $("#ind").val();
        var rettype = $("#rettype").val();
        var prod = $("#prod").val();
        var advcode = $("#advcode").val();
        var booktype = $("#booktype").val();
        $("#source").attr('src', "<?php echo site_url('marketing_report/generate') ?>/"+datefrom+"/"+dateto+"/"+reporttype+"/"+toptype+"/"+toprank+"/"+booktype+"/"+industry+"/"+rettype+"/"+prod+"/"+advcode);
    }
 });


 $("#exportreport").die().live ("click",function() {

        var countValidate = 0;
        var validate_fields = ['#datefrom', '#dateto', '#toprank', '#reporttype'];

    for (x = 0; x < validate_fields.length; x++) {
        if($(validate_fields[x]).val() == "") {
            $(validate_fields[x]).css(errorcssobj);
              countValidate += 1;
        } else {
              $(validate_fields[x]).css(errorcssobj2);
        }

    }
    if (countValidate == 0) {
        var datefrom = $("#datefrom").val();
        var dateto = $("#dateto").val();
        var reporttype = $("#reporttype").val();
        var toptype = $("#toptype").val();
        var toprank = $("#toprank").val();
        var industry = $("#ind").val();
        var rettype = $("#rettype").val();
        var prod = $("#prod").val();
        var advcode = $("#advcode").val();
        var booktype = $("#booktype").val();
    window.open("<?php echo site_url('marketing_report/marketing_export/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&toptype="+toptype+"&toprank="+toprank+"&booktype="+booktype+"&industry="+industry+"&rettype="+rettype+"&prod="+prod+"&code="+advcode, '_blank');
        window.focus();
    }


});


</script>
