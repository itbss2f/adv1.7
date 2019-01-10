<!doctype html>
<html lang="en">
<head>    

    <?php 
    header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
    
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    
    header("Cache-Control: no-store, no-cache, must-revalidate");
    
    header("Cache-Control: post-check=0, pre-check=0", false);
    
    header("Pragma: no-cache");
    ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8; gzip">
    <meta http-equiv="Lang" content="en">
    
    <title>Dummy Display [PDI]</title>
    
    <?php /*<link type="image/x-icon" href="http://www.inquirer.net/videos/images/faveicon.ico" rel="shortcut icon">    */ ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/dummy.css" media="screen" />    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/facebox/src/facebox.css" media="screen" /> 
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugin/jquery-ui-1.8.16.custom/css/smoothness/jquery-ui-1.8.16.custom.css">     
</head>                
<body>      
    <div class="container"><!-- container -->        
        <div class="nav">    
            <div id="nav1">  
                <?php if($canDUMOPEN) : ?>
                <div class="toolkit"><a href="<?php echo site_url('displaydummy/dummy/opend') ?>" title="Open Dummy" rel="facebox"><img src="<?php echo base_url() ?>assets/images/dummy/open.png" border="0"></a></div>
                <?php endif; ?>            
                <?php if($canDUMSAVE) : ?>
                <div class="toolkit"><a href="#save" title="Save Dummy" id="save" name="save"><img src="<?php echo base_url() ?>assets/images/dummy/save.png" border="0"></a></div>
                <?php endif; ?>
                <?php if($canDUMADDSEC) : ?>
                <div class="toolkit"><a href="<?php echo site_url('displaydummy/dummy/sectiond') ?>" title="Set Section" rel="facebox"><img src="<?php echo base_url() ?>assets/images/dummy/section.png" border="0"></a></div>
                <?php endif; ?>
                <?php if($canDUMCOLOR) : ?>
                <div class="toolkit"><a href="<?php echo site_url('displaydummy/dummy/colord') ?>" title="Set Color" rel="facebox"><img src="<?php echo base_url() ?>assets/images/dummy/color.png" border="0"></a></div>
                  <?php endif; ?>
                <?php if($canDUMADDPAGE) : ?>
                <div class="toolkit"><a href="<?php echo site_url('displaydummy/dummy/addpaged') ?>" title="Add New Page" rel="facebox"><img src="<?php echo base_url() ?>assets/images/dummy/addpage.png" border="0"></a></div>
                <?php endif; ?>
                <?php if($canDUMCOLOR) : ?>
                <div class="toolkit"><a href="#delete" title="Delete Page" id="delete" name="delete"><img src="<?php echo base_url() ?>assets/images/dummy/delete.png" border="0"></a></div>    
                <?php endif; ?>
                <!-- <div class="toolkit"><a href="<?php echo site_url('displaydummy/dummy/merged') ?>" title="Merged Page" id="merged" name="merged" rel="facebox"><img src="<?php echo base_url() ?>images/dummy/merged.png" border="0"></a></div>            -->        
                <?php if($canDUMCOLOR) : ?>
                <div class="toolkit"><a href="#unflow" title="Unflow Ads" id="unflow" name="unflow"><img src="<?php echo base_url() ?>assets/images/dummy/unflow.png" border="0"></a></div>
                <?php endif; ?>    
                <?php if($canDUMTEMPLATE) : ?> 
                <div class="toolkit"><a href="#template" title="Make Template" name="template" id="template"><img src="<?php echo base_url() ?>assets/images/dummy/template.png" border="0"></a></div>
                <?php endif; ?>
                <div class="toolkit"><a href="#productionremarks" title="Production Remarks" name="prodremarks" id="prodremarks"><img src="<?php echo base_url() ?>assets/images/dummy/prod-remarks.png" border="0"></a></div>
                <div class="toolkit"><a href="#lockbox" title="Lock Box" name="lockbox" id="lockbox"><img src="<?php echo base_url() ?>assets/images/dummy/lock.png" border="0"></a></div>
                <div class="toolkit"><a href="#unlockbox" title="Unlock Box" name="unlockbox" id="unlockbox"><img src="<?php echo base_url() ?>assets/images/dummy/unlock.png" border="0"></a></div>
                <div class="toolkit"><a href="#multievent" title="Multi Event" name="multi" id="multi"><img src="<?php echo base_url() ?>assets/images/dummy/multi.png" border="0"></a></div>
                <div class="toolkit"><a href="#refresh" title="Refresh for more box ads" name="refresh" id="refresh"><img src="<?php echo base_url() ?>assets/images/dummy/refresh.png" border="0"></a></div>                    
                <?php if($canDUMPRINT) : ?>
                <div class="toolkit"><a href="#print" title="Print Dummy" name="print" id="print"><img src="<?php echo base_url() ?>assets/images/dummy/print.png" border="0"></a></div>
                <?php endif; ?>     
                <div class="toolkit"><a href="#material" title="View Material" id="material" name="material"><img src="<?php echo base_url() ?>assets/images/dummy/hide.png" border="0"></a></div>    
                <div class="toolkit"><a href="#exit" title="Exit Dummy" id="exitdum" name="exitdum"><img src="<?php echo base_url() ?>assets/images/dummy/exit.png" border="0"></a></div>    
                <div class="toolkit2" style='font-size:13px'>View <select name="viewing" id="viewing">
                                            <option value="1"> 100% </option>
                                            <option value="2"> 75% </option>
                                            <option value="3"> 50% </option>
                                            <option value="4"> 25% </option>                                            
                                            <option value="7"> 15% </option>
                                            <option value="5"> 5% </option>  
                                            <option value="6"> 1% </option> 
                                           </select>
                </div>
                <div class="toolkit2" style='width:100px;font-size:13px'>Total Page: <span id='totalpages'>0</span></div>                            
                <div class="toolkit"><input type="checkbox" name="budget" id="budget"></div>                            
            </div>            
            <div id="nav2"><label style="font-size: 12px;">Selection:</label>
                           <input type="text" class="dummyflds" name="selection" id="selection" style="width: 90px; margin-top: 5px; background: #4D4B53; color: #FFFFFF; font-size: 12px;" readonly="readonly">
                           <button name="clear" id="clear">Clear</button>    
                           <a href='#hide' style='font-size:10px' id='header_hide'>Hide</a><a href='#unhide' id='header_unhide' style='display:none;font-size:10px'>Unhide</a>
            </div>            
        </div>                
        <div class="contentdummy">
            <div id="content1"></div>
            <div id="content2">
                <div id="content2-header">List of Ad: [<strong id="dateofissue">No Issue Date</strong>]</div>
                <div id="content2-search"> 
                    <table>
                        <tr>
                            <td>
                            <select class="styled-select" name="filtersection" id="filtersection">
                                <option value="" selected="selected">SECTION</option>    
                                <?php 
                                foreach ($sect as $sect) {
                                ?>
                                <option value="<?php echo $sect['class_code'] ?>"><?php echo $sect['class_code'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            </td>
                            <td>
                            <select class="styled-select" name="filtercolor" id="filtercolor">
                                <option value="" selected="selected">COLOR</option>    
                                <option value="Spo">Spo</option>
                                <option value="4Co">4Co</option>
                                <option value="2Sp">2Sp</option>
                                <option value="NoCol">No Color</option>    
                            </select>
                            </td>
                            <td>
                                <select class="styled-select" name="filtershow" id="filtershow">                                
                                <option value="2">All Dummied Ads</option>
                                <option value="0" selected="selected">All Undummied Ads</option>                                                                
                                <option value="1">All Ads</option>                                                                
                                <option value="3">Hide Ads</option>                                                                
                            </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='3' style="font-size: 12px;">Code: &nbsp; <input class="styled-select" type="text" name="filtercode" id="filtercode" style="width: 100px;"></td>                            
                        </tr>
                        <tr>
                            <td colspan='3' style="font-size: 12px;">Name:&nbsp;
                            <input class="styled-select" type="text" name="filtername" id="filtername" style="width: 220px;"></td>
                        </tr>
                        <tr>
                            <td colspan='3' style="font-size: 12px;">AO Num: &nbsp; <input class="styled-select" type="text" name="filteraonum" id="filteraonum" style="width: 100px;"></td>                            
                        </tr>
                        <tr>
                            <td colspan='2' style="font-size: 12px;">Width: &nbsp; <input class="styled-select" type="text" name="filterwidth" id="filterwidth" style="width: 50px;"></td>                            
                            <td colspan='2' style="font-size: 12px;">Height: &nbsp; <input class="styled-select" type="text" name="filterheight" id="filterheight" style="width: 50px;"></td>                            
                        </tr>
                    </table>
                </div>   
                <div id="content2-content"></div>                
            </div>
        </div>            
    </div> <!-- end container -->
    <div id="maketemplate" title="MAKE TEMPLATE">    
        <div align="center" id="calendar"></div>        
        <div align="center" style="margin-top:10px">
            <!-- <input type='button' name='makethistemplate' id='makethistemplate' value='Make This Template'>  -->
            <input type='button' name='canceltemplate' id='canceltemplate' value='Cancel Template'>
        </div>
    </div>   
    
    <div id="multievent" title="MULTI EVENT">    
        <div align="center">
            <table cellpadding="10">
                <tr>
                    <td>Section</td>
                    <td>
                        <select name='multisection' id='multisection' style='width:120px'>
                            <?php 
                            foreach ($multisect as $multisect) {
                            ?>
                            <option value="<?php echo $multisect['class_code'] ?>"><?php echo $multisect['class_code'] ?></option>
                            <?php 
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Color</td>
                    <td>
                        <select name='multicolor' id='multicolor' style='width:120px'>
                            <?php 
                            foreach ($multicolor as $color) {
                            ?>
                            <option value="<?php echo $color['color_code'] ?>"><?php echo $color['color_code'] ?></option>
                            <?php 
                            }
                            ?>
                            <option value="NoCol">No Color</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="width: 80px;">Pages</td>
                    <td>
                        <select name="multibookname" id="multibookname" style="width: 80px;">
                        <?php 
                        foreach ($multipage as $page) {
                        ?>
                            <option value="<?php echo $page['book_name'] ?>"><?php echo $page['book_name'] ?></option>
                        <?php
                        }
                        ?>
                       </select>
                        <input type='text' name='multipages' id='multipages' style='width:50px' value='2'>
                    </td>
                </tr>
                <tr style='margin-top:20px' align='center'>
                    <td><input type='button' name='multiadd' id='multiadd' value='Add' style='width:90px'></td>
                    <td><input type='button' name='multicancel' id='multicancel' value='Cancel' style='width:90px'></td>
                </tr>
            </table>
        </div> 
        
    </div>
    <div id="prod_remark" title="PRODUCTION REMARKS">                       
    </div>
    <script src="<?php echo base_url()?>assets/js/jquery-1.7.1.min.js" type="text/javascript"></script>    
    <script src="<?php echo base_url()?>assets/js/jquery-ui-1.8.13.custom.min.js" type="text/javascript"></script>    
    <script src="<?php echo base_url()?>assets/js/jquery.tablesorter.min.js" type="text/javascript"></script>    
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/global.js"></script>
    <script src="<?php echo base_url() ?>assets/facebox/src/facebox.js" type="text/javascript"></script>  
    <script type="text/javascript">
        $(document).ready(function() {
            jQuery(document).ready(function($) {
                $('a[rel*=facebox]').facebox({
                    loadingImage : '<?php echo base_url() ?>assets/facebox/src/loading.gif',
                    closeImage   : '<?php echo base_url() ?>assets/facebox/src/closelabel.png'
                    });
                });            
        });
    </script>
    <?php include("script/dummscript.php"); ?>
    <script type="text/javascript">   
    $('#budget').click(function(){
        var chxk = $('#budget').is(':checked');   
        if (chxk) {
            $('.draggableboxpage').css('outline', '0px none #FFFFFF');  
            $('.bg-text').hide();
        } else {
            $('.draggableboxpage').css('outline', '5px dotted #FFFF00');  
            $('.bg-text').show();
        }
    }); 
        <!-- important variable -->
        var product = "";
        var date = "";        
        $("#save").click(function(){
        
            if (product == "" || date == "") {
                alert("Cannot save dummy. No Product and Date Issue selected.!"); return false;
            } else {
                var conf = confirm("Are you sure you want to save this dummy data?.");
                
                if (conf) {
                    $("#selection").val("");  
                    var totalpage = $('#totalpages').text();
                    
                    var modulu = totalpage % 2;
                                    
                    if (modulu != 0) {
                        alert('Sorry dummy cannot be save!. Page number must not be odd!.');
                        return false;
                    }
                    $.ajax({
                        url: '<?php echo site_url('displaydummy/dummy/saveDummyData'); ?>',
                        type: 'post',
                        data: {product: product, date: date, key: key},
                        success: function(response){
                            var $response = $.parseJSON(response);
                            alert("Dummy Data successfully save!.");
                            loadthetemplate(date);    
                                                
                        }
                    });
                } 
            }
        });
        
        $("#clear").click(function(){
            $("#selection").val("");
            $('.draggablebox[data-value="pr"]').removeAttr('onclick');       
        });
        
        $("#refresh").click(function(){
            if (product == "" || date == "") {
                alert("Cannot refresh ads. No Product and Date Issue selected.!"); return false;
            } else {
                var show = $(":input[name='filtershow']").val();     
                $.ajax({
                    url: '<?php echo site_url('displaydummy/dummy/ajxRefresh'); ?>',
                    type: 'post',
                    data: {product: product, date: date, show: show},
                    success: function(response) {
                        var $response = $.parseJSON(response);        
                        $("#content2-content").html($response['listad']);                                                    
                    }
                });
            }
        });
        
        $("#content2-content").droppable({
            accept: ".draggablebox",
            drop: function( event, ui ) {                                    
                $.ajax({
                    url: '<?php echo site_url('displaydummy/dummy/ajxRefresh'); ?>',
                    type: 'post',
                    data: {product: product, date: date},
                    success: function(response) {
                        var $response = $.parseJSON(response);                                
                        $("#content2-content").html($response['listad']);                                                    
                    }
                });
            }            
        });            
        
        /* Zoom in Zoom out*/
        $("#viewing").change(function(){
            $('.radio_find').removeAttr('checked');
            if (product == "" || date == "") {                
            } else {            
                $.ajax({
                url: '<?php echo site_url('displaydummy/dummy/ajaxViewingPercent')?>',
                type: 'post',
                data: {key: key,
                       viewing: $("#viewing").val(),
                       product: product,
                       dateissue: date},
                success: function(response) {
                    var $response = $.parseJSON(response);
                    if ($response['valid'] == "true") {                         
                        $("#content1").html($response['pagelayout']);                        
                    } else {
                        alert("No existing dummy layout. New layout will be created!.");                        
                        $("#content1").html("<h4 class='alert_info'>No dummy pages been created. Create knew layout!.</h4>");                    
                    }
                }
            });
            }
                        
        });            
        
        /* Deleted */
        $("#delete").click(function(){
            $("#selection").val("Delete");
        });
        
        /* Merged */
        $("#merged").click(function() {            
            $("#selection").val("Merged");
        });
        
        /* Unmerged */
        $("#unmerged").click(function() {
            $("#selection").val("Unmerged");
        });
        
        /* Unflow */
        $("#unflow").click(function() {
            $("#selection").val("Unflow");
        }); 

        $("#material").click(function() {
            $("#selection").val("Material");         
        });
        
        $("#prodremarks").click(function() {
            $("#selection").val("Remarks");    
            
            $('.draggablebox[data-value="pr"]').attr('onclick', 'productionremarks(this.id)');
            $('.draggableboxlock[data-value="pr"]').attr('onclick', 'productionremarks(this.id)'); 
                        
        });          
        
        
        $("#filtersection").change(function(){
            filtering();
        });
        $("#filtercolor").change(function(){
            filtering();
        });
        $("#filtername").keyup(function(){
            filtering();
        });
        $("#filtershow").change(function(){
            filtering();
        });
        $("#filtercode").keyup(function(){
            filtering();
        });
        $("#filteraonum").keyup(function(){
            filtering();
        });
        $("#filterwidth").keyup(function(){
            filtering();
        });
        $("#filterheight").keyup(function(){
            filtering();
        });
        
        
        function filtering() {
            $('.draggablebox').css('outline', '0px none #FFFFFF');          
            var section = $(":input[name='filtersection']").val();
            var color = $(":input[name='filtercolor']").val();
            var name = $(":input[name='filtername']").val();
            var show = $(":input[name='filtershow']").val();
            var code = $(":input[name='filtercode']").val();
            var aonum = $(":input[name='filteraonum']").val();
            var width = $(":input[name='filterwidth']").val();
            var height = $(":input[name='filterheight']").val();
            $.ajax({
                url: '<?php echo site_url('displaydummy/dummy/ajxFiltering') ?>',
                type: 'post',
                data: {product: myprod,
                       dateissue: mydate,
                       section: section, 
                       color: color,
                       name: name,
                       show: show,
                       code: code,
                       aonum: aonum,
                       width: width,
                       height: height
                      },
                success: function(response) {
                    var $response = $.parseJSON(response);        
                    $("#content2-content").html($response['listad']);
                }
            });
        }
        
        $("#exitdum").click(function(){            
            window.close();
        });
        
        $("#print").click(function(){    
        
            window.open ("<?php echo site_url('displaydummy/dummy_layout/printout')?>/"+date+"/"+product+"","Dummy Print Output","menubar=1,resizable=1");
        });
        $("#multi").click(function() {
            // Creating of multi page
            if (myprod == "" || mydate == "") {
                alert('Cannot create multi page event no loaded dummy pages!');
            } else {
                $('#multievent').dialog('open');
            }
        });
        
        $("#template").click(function() {
            // Creating of template page
            if (product == "" || date == "") {
                alert('Cannot create template no loaded dummy pages!');
            } else {
                $('#maketemplate').dialog('open');
            }
        });
       
        $("#multiadd").click(function() {
             if ($("#multipages").val() == "" || $("#multipages").val() == 0) {
                alert("Page number should not be zero or empty!.");    
             } else {
                 $.ajax({
                    url : '<?php echo site_url('displaydummy/dummy/ajxAddPage') ?>',
                    type: 'post',
                    data: {bookname: $(":input[name='multibookname']").val(), numberofpage: $(":input[name='multipages']").val(),  
                           key: mykey, product: myprod, date: mydate, viewing: $("#viewing").val(), 
                           color: $("#multicolor").val(), class_code: $("#multisection").val()},
                    success: function(response) {
                        var $response = $.parseJSON(response);
                        if ($response['valid'] == "true") {                         
                            $("#content1").html($response['pagelayout']);
                            $("#totalpages").html($response['totalpages']);     
                            //jQuery.facebox.close(this);                            
                        }
                        $("#multievent").dialog('close'); 
                    }
                });
            }
        });
        
        $("#multicancel").click(function() {
            $("#multievent").dialog('close'); 
        });

        $('#calendar').datepicker({            
            dateFormat: 'yy-mm-dd',                         
            changeMonth: true,
            changeYear: true,                
            //beforeShowDay: highlightDays,  
            onSelect: function(dateText, inst) {
                  selectIssueDate(dateText, inst)
            }
        });

        function selectIssueDate(dateText, inst) {
            if (dateText == date) {
                alert('The date you pick must not be the issue date itself!');
                return false;
            } else {
                var ans = confirm("Are you sure you want to make a template page from this issue? This will alter the existing data of the selected issuedate if ok!");
                
                if (ans) {
                    $.ajax({
                        url: "<?php echo site_url('displaydummy/dummy/makeTemplate') ?>",
                        type: 'post',
                        data: {datetext: dateText, product: product, date: date},
                        success:function(response) {
                            $response = $.parseJSON(response);
                            if ($response['valid'] == 'yes') {
                                loadthetemplate(dateText);
                                $('#maketemplate').dialog('close');
                            } else {
                                alert('Template creation invalid the issue date you choose already has an existing dummy pages!');
                                return false;
                            }       
                        }
                    });
                }
            }
        }
        
        function loadthetemplate(dateText) {
            
            $.ajax({
                url: '<?php echo site_url('displaydummy/dummy/ajxRtAds')?>',
                type: 'post',
                data: {viewing: $("#viewing").val(),
                       product: product,
                       dateissue: dateText},
                success: function(response) {
                    var $response = $.parseJSON(response);
                        myprod = $response['product'];
                        mydate = $response['dateissue'];
                        mykey = $response['key'];
                    if ($response['valid'] == "true") { 
                        $("#dateofissue").html($(":input[name='dateissue']").val());
                        $("#content2-content").html($response['listad']);    
                        $("#content1").html($response['pagelayout']);                        
                        $("#dateofissue").html($response['convertDate']);
                        $("#totalpages").text($response['countpage']);
                        jQuery.facebox.close(this);
                    } else {
                        alert("No existing dummy layout. New layout will be created!.");                            
                        $("#dateofissue").html($(":input[name='dateissue']").val());
                        $("#content2-content").html($response['listad']);    
                        $("#content1").html("<h4 class='alert_info'>No dummy pages been created. Create new layout!.</h4>");
                        $("#totalpages").text($response['countpage']);
                        jQuery.facebox.close(this);
                    }
                }
            });
        }
        
        $("#canceltemplate").click(function(){
            $('#maketemplate').dialog('close');    
        });
        $('#maketemplate, #multievent, #prod_remark').dialog({
            autoOpen: false, 
            closeOnEscape: false,
            draggable: true,
            width: 280,    
            height:270,
            modal: true,
            resizable: false
        });   
        
        $("#header_hide").click(function(){
            //alert('test');
            $('#header_hide').hide();
            $('#header_unhide').show();
            $('#content1').css({width:  '1350px'});
        });  
        
        $("#header_unhide").click(function(){
            //alert('test');
            $('#header_unhide').hide();
            $('#header_hide').show();            
            $('#content1').css({width: '730px'});
        });    
        
        $("#lockbox").click(function() {
            $("#selection").val("Lockbox");            
            $('.draggablebox[data-value="pr"]').attr('onclick', 'do_lockbox(this.id)');
            $('.draggableboxlock[data-value="pr"]').attr('onclick', 'do_lockbox(this.id)'); 
        }); 
        
        $("#unlockbox").click(function() {
            $("#selection").val("Unlockbox");
            $('.draggablebox[data-value="pr"]').attr('onclick', 'do_unlockbox(this.id)');
            $('.draggableboxlock[data-value="pr"]').attr('onclick', 'do_unlockbox(this.id)');         
        }); 
        
    </script>
</body>
</html>
