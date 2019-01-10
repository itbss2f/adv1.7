<?php 
foreach ($module_list as $x => $module_list) : ?>
<tr>
	<td style="width:30%"><strong><?php echo $x ?></strong></td>
	<td style="width:70%">
		<ul class="the-icons clearfix">
			<?php 
            
			foreach ($module_list as $list) : 
            $check = "checked = 'checked'";    
            
			if ($list['useraccess'] == 999999 ) :
				$check = "";   
			endif;
			?>
			<li><label class="checkbox inline font12"><input type="checkbox" class="functioncheckbox" <?php echo $check ?> value="<?php echo $list['moduleid'].'&'.$list['functionid'] ?>"><?php echo $list['functionname'] ?></label></li>
			<?php
			endforeach;
			?>
		</ul>
	</td>
</tr>		
<?php
endforeach;
?>
<script>
$(".functioncheckbox").one("click", function() {     
	var $module_function = $(this).val();
	var $userid = "<?php echo $userid ?>";
	$.ajax({
		url: "<?php echo site_url('user/setaccess') ?>",
		type: "post",
		data: {module_function: $module_function, userid: $userid},
		success: function(response) {

		}
	});
});
</script>
