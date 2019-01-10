<style>
	div {
		font-size: 12px;		
	}

</style>
<div id="newtabs" style="background: #A4A4A4">
	<h4>Open Existing Dummy</h4>
	<ul>
		<li><a href="#newtab1">Issue</a></li>
		<!-- <li><a href="#newtab2">Template</a></li> -->
	</ul>
	<div id="newtab1">
		<p>Product: <select name="product" id="product">
						<?php 
						foreach ($prod as $prod) {
                            if ($prod['id'] == '15') {
                            ?>
                            <option value="<?php echo $prod['id'] ?>" selected="selected"><?php echo $prod['prod_code'] ?></option>
                            <?php    
                            } else {
						    ?>
						    <option value="<?php echo $prod['id'] ?>"><?php echo $prod['prod_code'] ?></option>
						    <?php
                            }
						}
						?>
					</select>
		</p>
		<p>Date: <input type="text" name="dateissue" id="dateissue"></p>
		<p align="center"><button class="dummyflds" style="width:180px;" name="load" id="load">Load</button></p>
        
        <p id='loadinghud' align="center" style="display: none;"><img title="c_loader_ye.gif" src="<?php echo base_url() ?>themes/img/loaders/c_loader_ye.gif"></p>
	</div>
	<!-- <div id="newtab2">
		<p>Master: <select>
						<option>--- Select Master ---</option>
						<option></option>
						<option></option>
						<option></option>
				   </select>		
		</p>
		<p align="center"><button class="dummyflds" style="width:200px;">Load</button></p>
	</div>	-->
</div>
<script>	
	var myprod = "";
	var mydate = "";
	var mykey = "";
	$(function() {
		$( "#newtabs" ).tabs();
		$( "#dateissue" ).datepicker({
			dateFormat: 'M d, yy',
			changeMonth: true,
			changeYear: true
		});
	});
	
	$("#load").click(function(){	
		if ($(":input[name='dateissue']").val() === "") {
			alert('Date must not be empty or NULL!.');
			return false;
		} else {
            
            var ans = confirm('Are you sure to open this dummy rundate?');
            
            if (ans) {
                
                
                $('#loadinghud').show();
                $.ajax({
                    url: '<?php echo site_url('displaydummy/dummy/ajxRtAds')?>',
                    type: 'post',
                    data: {viewing: $("#viewing").val(),
                           product: $(":input[name='product']").val(),
                           dateissue: $(":input[name='dateissue']").val(),
                           show: $('#filtershow').val()},
                    success: function(response) {
                        $("#selection").val("");   
                        var $response = $.parseJSON(response);
                            myprod = $response['product'];
                            mydate = $response['dateissue'];
                            mykey = $response['key'];
                        if ($response['valid'] == "true") { 
                            $("#dateofissue").html($(":input[name='dateissue']").val());
                            $("#content2-content").html($response['listad']);    
                            $("#content1").html($response['pagelayout']);
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
			
		}
	});	
	
</script>