<!DOCTYPE HTML>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link href="<?php echo base_url() ?>plugins/jquery-ui-1.8.13.custom/css/custom-theme/jquery-ui-1.8.17.custom.css" media="screen" rel="stylesheet" type="text/css" />	
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/jquery-1.5.1.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/ui/jquery.ui.core.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/ui/jquery.ui.widget.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/ui/jquery.ui.mouse.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/ui/jquery.ui.draggable.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/jquery-collision-1.0.1.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/jquery-ui-draggable-collision-1.0.1.js"></script>
    <script src="<?php echo base_url() ?>plugins/jquery-ui-1.8.13.custom/js/jquery-ui-1.8.13.custom.min.js" type="text/javascript"></script>	
	</head>
	<body>	
        <div id="maincontent">
            <input type="hidden" name="setter" id="setter">        
            <div id="headermenu" style="border: 1px solid; margin-bottom: 5px; ">
                <p>
                    <label>Product</label>
                    <select name="product" id="product">
                        <option value="">--- Select Product ---</option>
                        <?php 
                        foreach ($product as $product) {
                         ?>
                         <option value="<?php echo $product['id'] ?>"><?php echo $product['prod_code'] ?></option>
                         <?php
                        }
                        ?>
                    </select>
                    <label>Issue Date</label>
                    <input type="text" name="calendar" id="calendar" align="center">
                    <input type="button" name="roll" id="roll" align="center" value="Roll Dummy">
                    <input type="button" name="cancel" id="cancel" align="center" value="Cancel">
               </p>
            </div>    
            <div id="dummycontent" style="width: 100%; height: 650px;height: auto">                
                <div id="dummy" style="float: left; width: 70%;height: auto; margin-right: 10px">
                    <div id="dummymenu" style="border: 1px solid;margin-bottom:5px; float: left; width: 100%;height: 30px; display: none">           
                        <select name="book" id="book">
                            <option value="" selected="selected">Page</option>
                            <?php 
                            foreach ($book as $book) {
                            ?>
                            <option value="<?php echo $book['book_name'] ?>"><?php echo $book['book_name']?></option>
                            <?php
                            }
                            ?>
                            <?php                         
                            ?>
                        </select>   
                        <select name="section" id="section">
                            <option value="">Section</option>
                            <?php 
                            foreach ($section as $section) {
                            ?>
                            <option value="<?php echo $section['class_code'] ?>"><?php echo $section['class_code']?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <input type="text" name="pagenumber" id="pagenumber" size="2" >
                        <input type="button" name="addpage" id="addpage" value="Add Pages">
                        <input type="radio" name="none" id="none">None
                        <?php 
                        foreach ($color as $color) {
                        ?>
                        <input type="radio" name="<?php echo $color['color_code']?>" id="<?php echo $color['color_code']?>" value="<?php echo $color['color_code'] ?>"><?php echo $color['color_code']?>
                        <?php 	
                        }
                        ?>
                        <input type="radio" name="deletepage" id="deletepage">Delete
                        <input type="radio" name="setsection" id="setsection">Set Section
                    </div>
                    <div id="dummycontent" style="border: 1px solid; float: left; width: 100%;height: 600px; scroll: auto">
                        <div id="pagelayout" style="width: 100%;max-height: 600px; overflow: auto"></div>  
                        <div class="ui-droppable" id="draggable"></div> 
                    </div>
                </div>                        
                <div id="flow" style="float: left; width: 28%;height: 550px; overflow: auto;margin-right: 10px">
                    <div id="flowmenu" style="border: 1px solid;margin-bottom:5px; float: left; width: 98%;height: 30px;">
                        Flow Menu
                    </div>
                    <div id="flowcontent" style="border: 1px solid; float: left; width: 98%;height: 450px; scroll: auto">
                        <div id="flowlayout" style="width: 98%;max-height: 450px; overflow: auto"><?php echo $flowads ?></div>                        
                    </div>
                </div>
            </div>    
		</div>
        
        
        <!-- Javascriopt -->
        <script type="text/javascript">
        $("#setsection").click(function(){
            $("#setter").val("");
			$("#Spo").removeAttr('checked');
			$("#4Co").removeAttr('checked');
			$("#2Sp").removeAttr('checked');
            $("#deletepage").removeAttr('checked');
            $("#none").removeAttr('checked');
		});
        
        $("#deletepage").click(function(){		
            $("#setter").val("");
            $("#Spo").removeAttr('checked');
			$("#4Co").removeAttr('checked');
			$("#2Sp").removeAttr('checked');
			$("#none").removeAttr('checked');
            $("#setsection").removeAttr('checked');
		});
        $("#Spo").click(function(){
			$("#setter").val($("#Spo").val());
			$("#4Co").removeAttr('checked');
			$("#2Sp").removeAttr('checked');
			$("#none").removeAttr('checked');
            $("#deletepage").removeAttr('checked');
            $("#setsection").removeAttr('checked');
		});
		$("#4Co").click(function(){
			$("#setter").val($("#4Co").val());
			$("#Spo").removeAttr('checked');
			$("#2Sp").removeAttr('checked');
			$("#none").removeAttr('checked');
            $("#deletepage").removeAttr('checked');
            $("#setsection").removeAttr('checked');
		});
		$("#2Sp").click(function(){
			$("#setter").val($("#2Sp").val());
			$("#Spo").removeAttr('checked');
			$("#4Co").removeAttr('checked');
			$("#none").removeAttr('checked');
            $("#deletepage").removeAttr('checked');
            $("#setsection").removeAttr('checked');
		});
		$("#none").click(function(){
			$("#setter").val($("#none").val());
			$("#Spo").removeAttr('checked');
			$("#4Co").removeAttr('checked');
			$("#2Sp").removeAttr('checked');
            $("#deletepage").removeAttr('checked');
            $("#setsection").removeAttr('checked');
		});            
        
        $(function() {
            $( "#calendar" ).datepicker({ dateFormat: "yy-mm-dd" });
                                                        
            $("#cancel").click(function() {
                $("#product").removeAttr("disabled").val("");
                $("#calendar").removeAttr("disabled").val("");
                $("#roll").removeAttr("disabled");
                $("#dummymenu").hide();
                $("#pagelayout").empty();
                 $.ajax({
                        url: "<?php echo site_url('dummy/deleteTemp') ?>",                    
                        type: "post",
                        data: {hkey: <?php echo "'".$hkey."'" ?>},
                        success: function(response){
                                // Do nothing
                        }
                });
            });
            
            $("#roll").click(function() {                  
                var product = $("#product").val();
                var calendar = $("#calendar").val();
                if (product != "" && calendar != "") {
                    $("#product").attr("disabled", true);
                    $("#calendar").attr("disabled", true);
                    $("#roll").attr("disabled", true);
                    $("#dummymenu").toggle().animate({ backgroundColor: "#68BFEF" }, 1000);
                    $.ajax({
                        url: "<?php echo site_url('dummy/rolldummy') ?>",                    
                        type: "post",
                        data: {product: product, calendar: calendar, hkey: <?php echo "'".$hkey."'" ?>},
                        success: function(response){
                                var $response =  $.parseJSON(response);
                                $("#pagelayout").html($response['pagelayout']);
                                $("#flowlayout").html($response['flowad']);
                        }
                    });
                }
            });
            
            $('#addpage').click(function() {                  
                /* Variables Declaration */
                var product = $("#product").val();
                var calendar = $("#calendar").val();
                var pagenumber = $('#pagenumber').val();
                var bookname = $('#book').val();                
                var section = $('#section').val();
                if (pagenumber != "" && bookname != "") {                    
                    $.ajax({
                        url: '<?php echo site_url('dummy/addnewpage') ?>',
                        type: 'post',
                        data: {product: product, calendar: calendar,pagenumber: pagenumber, bookname: bookname, hkey: <?php echo "'".$hkey."'"?>, section: section},
                        success: function(response) {
                            var $response =  $.parseJSON(response);
                            
                            $("#pagelayout").html($response['pagelayout']);
                        }				
                    });
                
                } else {return false;}
            });
            
        });
        
        function dumpage(book, page, w, h)
		{
        var setter = $("#setter").val();
		var product = $("#product").val();
        var calendar = $("#calendar").val();
        var pagenumber = page;
        var bookname = $('#book').val();                
        var section = $('#section').val();   
        if ($('#deletepage').attr('checked')) {
                var ansdel = confirm("Are you sure you want to delete this page?"); 
                if (ansdel) {
                        $.ajax({
                            url: '<?php echo site_url('dummy/deletePage') ?>',
                            type: 'post',
                            data: {setter: setter, product: product, calendar: calendar,pagenumber: pagenumber, bookname: bookname, hkey: <?php echo "'".$hkey."'"?>, section: section},
                            success: function(response) {
                                    var $response =  $.parseJSON(response);		                        
                                    $("#pagelayout").html($response['pagelayout']);
                            }
                        }) ;
                }
        } else if ($('#setsection').attr('checked')) {
            if (section == "") {
                    alert ("Choose section first");
            } else {
                var ans = confirm("Are you sure you want to change page section?");
                    if (ans) {
                        $.ajax({
                            url: "<?php echo site_url('dummy/setPageSection') ?>",
                            type: 'post',
                            data: {setter: setter, product: product, calendar: calendar,pagenumber: pagenumber, bookname: bookname, hkey: <?php echo "'".$hkey."'"?>, section: section},
                            success: function(response) {
                                var $response =  $.parseJSON(response);		                        
                                $("#pagelayout").html($response['pagelayout']);
                            }
                        });
                    } else {
                        return false;
                    }     
            }
        } else {        
            var ans = confirm("Are you sure you want to change page color?");
                if (ans) {
                    $.ajax({
                        url: "<?php echo site_url('dummy/setPageColor') ?>",
                        type: 'post',
                        data: {setter: setter, product: product, calendar: calendar,pagenumber: pagenumber, bookname: bookname, hkey: <?php echo "'".$hkey."'"?>, section: section},
                        success: function(response) {
                            var $response =  $.parseJSON(response);		                        
                            $("#pagelayout").html($response['pagelayout']);
                        }
                    });
                } else {
                    return false;
                }
            }
		}
        $( "#draggable" ).draggable();
        </script>
        
	</body>
</html>

