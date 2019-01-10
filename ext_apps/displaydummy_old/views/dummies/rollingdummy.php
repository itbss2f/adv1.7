<!DOCTYPE HTML>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/jquery-1.5.1.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/ui/jquery.ui.core.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/ui/jquery.ui.widget.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/ui/jquery.ui.mouse.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/ui/jquery.ui.draggable.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/jquery-collision-1.0.1.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/jquery-ui-draggable-collision-1.0.1.js"></script>
	<style>
		.draggable { width:  65px; height:  30px; margin: 0; background-color: #0000ff; z-index: 10px; }
		.obstacle  { width: 100px; height: 100px; background-color: #ff0000; }
		.restraint { width: 340px; height: 250px; margin: 0; position: absolute; background-color: #00ff00; }
		#container { width: 1150px; 
		             min-height: 550px;                      
                     height: auto; 
		             scroll: auto;
		             position:absolute;
  				     left: 15px;
  				     top: 40px;
                     border: 1px solid;
		             }
		#draggable1 { width: 100px; height: 50px; background: red; }
		body { margin: 0px; padding: 0px; }
	</style>
	</head>
	<body>	
        <form action="<?php echo site_url('dummy/savethispagelayout') ?>" method="post" name="savepagelayout">
		<input type="hidden" name="colorsetter" id="colorsetter">        
		<?php 
		$hkey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);		
		?>	
        <input type="hidden" name="hkey" id="hkey" value="<?php echo $hkey ?>">
		<select name="book" id="book">
		    <option value="" selected="selected">--- Select Page ---</option>
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
            <option value="">--- Select Section---</option>
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
		<input type="radio" name="none" id="none">Default
		<?php 
		foreach ($color as $color) {
		?>
		<input type="radio" name="<?php echo $color['color_code']?>" id="<?php echo $color['color_code']?>" value="<?php echo $color['color_code'] ?>"><?php echo $color['color_code']?>
		<?php 	
		}
		?>			
        <input type="radio" name="deletepage" id="deletepage">Delete
        <input type="submit" name="savepagelayout" id="savepagelayout" value="Save Page Layout">
		</form>
        <div id="container">			
		</div>		
		
		<script type="text/javascript">
        $('#savepagelayout').click(function() {
            document.submit(this);
        });
		$('#addpage').click(function() {
			var pagenumber = $('#pagenumber').val();
			var bookname = $('#book').val();
			var setcolor = $('#colorsetter').val();
            var section = $('#section').val();
			if (pagenumber != "" && bookname != "") {
			var x = 1;				
			$.ajax({
				url: '<?php echo site_url('dummy/initpage') ?>',
				type: 'post',
				data: {color: setcolor, pagenumber: pagenumber, bookname: bookname, hkey: <?php echo "'".$hkey."'"?>, section: section},
				success: function(response) {
					var $response =  $.parseJSON(response);
					
					$("#container").html($response['pagelayout']);
				}				
			});
			} else {
				return false;
			}
			
	 	});
		$(function() {
			//$( ".draggable" ).draggable({ containment: "#board", obstacle: ".div b", preventCollision: true, restraint: ".restraint", preventProtrusion: true });
			$( "#draggable1" ).draggable({ 
				cursor: 'move',
				containment: "#a1",
				grid: [ 35, 5 ],
				stop: handleDragStop				
			});
			function handleDragStop( event, ui ) {
				  var offsetXPos = parseInt( ui.offset.left );
				  var offsetYPos = parseInt( ui.offset.top );
				  alert( "Position: (" + offsetXPos + ", " + offsetYPos + ")\n");
			}
		});

		$("#deletepage").click(function(){		
            $("#colorsetter").val("");
            $("#Spo").removeAttr('checked');
			$("#4Co").removeAttr('checked');
			$("#2Sp").removeAttr('checked');
			$("#none").removeAttr('checked');
		});
        $("#Spo").click(function(){
			$("#colorsetter").val($("#Spo").val());
			$("#4Co").removeAttr('checked');
			$("#2Sp").removeAttr('checked');
			$("#none").removeAttr('checked');
            $("#deletepage").removeAttr('checked');
		});
		$("#4Co").click(function(){
			$("#colorsetter").val($("#4Co").val());
			$("#Spo").removeAttr('checked');
			$("#2Sp").removeAttr('checked');
			$("#none").removeAttr('checked');
            $("#deletepage").removeAttr('checked');
		});
		$("#2Sp").click(function(){
			$("#colorsetter").val($("#2Sp").val());
			$("#Spo").removeAttr('checked');
			$("#4Co").removeAttr('checked');
			$("#none").removeAttr('checked');
            $("#deletepage").removeAttr('checked');
		});
		$("#none").click(function(){
			$("#colorsetter").val($("#none").val());
			$("#Spo").removeAttr('checked');
			$("#4Co").removeAttr('checked');
			$("#2Sp").removeAttr('checked');
            $("#deletepage").removeAttr('checked');
		});

		function dumpage(book, page, w, h)
		{
		var setcolor = $('#colorsetter').val();
        var section = $('#section').val();       
        if ($('#deletepage').attr('checked')) {
                var ansdel = confirm("Are you sure you want to delete this page?"); 
                if (ansdel) {
                        $.ajax({
                            url: '<?php echo site_url('dummy/deletePage') ?>',
                            type: 'post',
                            data: {book: book, 
                                       page: page, 
                                       hkey: <?php echo "'".$hkey."'"?> },
                            success: function(response) {
                                    var $response =  $.parseJSON(response);		                        
                                    $("#container").html($response['pagelayout']);
                            }
                        }) ;
                }
        } else {        
            var ans = confirm("Are you sure you want to change page value?");
                if (ans) {
                    $.ajax({
                        url: "<?php echo site_url('dummy/setPageValue') ?>",
                        type: 'post',
                        data: {color: setcolor, 
                                   book: book, 
                                   page: page, 
                                   hkey: <?php echo "'".$hkey."'"?>, 
                                   section: section},
                        success: function(response) {
                            var $response =  $.parseJSON(response);		                        
                            $("#container").html($response['pagelayout']);
                        }
                    });
                } else {
                    return false;
                }
            }
		}
                
		</script>
		
	</body>
</html>

