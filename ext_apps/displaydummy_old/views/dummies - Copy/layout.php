<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Dummy Display [PDI]</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/dummy.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/facebox/src/facebox.css" media="screen" /> 
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugin/jquery-ui-1.8.16.custom/css/smoothness/jquery-ui-1.8.16.custom.css">     
    <script src="<?php echo base_url()?>assets/js/jquery-1.7.1.min.js" type="text/javascript"></script>    
    <script src="<?php echo base_url()?>assets/js/jquery-ui-1.8.13.custom.min.js" type="text/javascript"></script>    
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
</head>
<body>
    <div class="container"><!-- container -->        
        <div class="nav">    
            <div id="nav1">
                <div class="toolkit"><a href="<?php echo site_url('displaydummy/dummy/opend') ?>" title="Open Dummy" rel="facebox"><img src="<?php echo base_url() ?>assets/images/dummy/open.png" border="0"></a></div>            
                <div class="toolkit"><a href="#save" title="Save Dummy" id="save" name="save"><img src="<?php echo base_url() ?>assets/images/dummy/save.png" border="0"></a></div>
                <div class="toolkit"><a href="<?php echo site_url('displaydummy/dummy/sectiond') ?>" title="Set Section" rel="facebox"><img src="<?php echo base_url() ?>assets/images/dummy/section.png" border="0"></a></div>
                <div class="toolkit"><a href="<?php echo site_url('displaydummy/dummy/colord') ?>" title="Set Color" rel="facebox"><img src="<?php echo base_url() ?>assets/images/dummy/color.png" border="0"></a></div>
                <div class="toolkit"><a href="<?php echo site_url('displaydummy/dummy/addpaged') ?>" title="Add New Page" rel="facebox"><img src="<?php echo base_url() ?>assets/images/dummy/addpage.png" border="0"></a></div>
                <div class="toolkit"><a href="#delete" title="Delete Page" id="delete" name="delete"><img src="<?php echo base_url() ?>assets/images/dummy/delete.png" border="0"></a></div>    
                <!-- <div class="toolkit"><a href="<?php echo site_url('displaydummy/dummy/merged') ?>" title="Merged Page" id="merged" name="merged" rel="facebox"><img src="<?php echo base_url() ?>images/dummy/merged.png" border="0"></a></div>            -->        
                <div class="toolkit"><a href="#unflow" title="Unflow Ads" id="unflow" name="unflow"><img src="<?php echo base_url() ?>assets/images/dummy/unflow.png" border="0"></a></div>     
                <div class="toolkit"><a href="#refresh" title="Refresh for more box ads" name="refresh" id="refresh"><img src="<?php echo base_url() ?>assets/images/dummy/refresh.png" border="0"></a></div>                    
                <div class="toolkit"><a href="#exit" title="Exit Dummy" id="exitdum" name="exitdum"><img src="<?php echo base_url() ?>assets/images/dummy/exit.png" border="0"></a></div>    
                <div class="toolkit2">View <select name="viewing" id="viewing">
                                            <option value="1"> 100% </option>
                                            <option value="2"> 75% </option>
                                            <option value="3"> 50% </option>
                                            <option value="4"> 25% </option>
                                            <option value="5"> 5% </option> 
                                            <option value="6"> 1% </option> 
                                           </select>
                </div>
            </div>            
            <div id="nav2"><label style="font-size: 12px;">Selection:</label>
                           <input type="text" class="dummyflds" name="selection" id="selection" style="width: 90px; margin-top: 5px; background: #4D4B53; color: #FFFFFF; font-size: 12px;" readonly="readonly">
                           <button name="clear" id="clear">Clear</button>
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
                                <option value="">ALL</option>    
                                <option value="2">All Dummied Ads</option>
                                <option value="0" selected="selected">All Undummied Ads</option>                                                                
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
                <div id="content2-content"> </div>                
            </div>
        </div>            
    </div> <!-- end container -->
    <script type="text/javascript">
        <!-- important variable -->
        var product = "";
        var date = "";        
        $("#save").click(function(){
        
            if (product == "" || date == "") {
                alert("Cannot save dummy. No Product and Date Issue selected.!"); return false;
            } else {
                var conf = confirm("Are you sure you want to save this dummy data?.");
                
                if (conf) {
                    $.ajax({
                        url: '<?php echo site_url('displaydummy/dummy/saveDummyData'); ?>',
                        type: 'post',
                        data: {product: product, date: date, key: key},
                        success: function(response){
                            var $response = $.parseJSON(response);
                            if ($response['change'] == "F") {
                                //alert("There is no changes been made.! Dummy data will not be saved!"); 
                                alert("Dummy Data successfully save!.");
                                //return false;
                            } else if ($response['change'] == "T") {
                                alert("Dummy Data successfully save!.");
                            }
                        }
                    });
                } 
            }
        });
        
        $("#clear").click(function(){
            $("#selection").val("");
        });
        
        $("#refresh").click(function(){
            if (product == "" || date == "") {
                alert("Cannot refresh ads. No Product and Date Issue selected.!"); return false;
            } else {
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
            window.location.href = "<?php echo site_url('page') ?>";
        });
    </script>
</body>
</html>