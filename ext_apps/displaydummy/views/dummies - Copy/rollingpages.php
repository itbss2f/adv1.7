<?php
$col = $page['columns'];
$hen = $page['len'];
$w = ($col * 30) + (($col - 1) * 5);
$h = $hen * 10;
$pagenumber = $pagenumber;
$bookname = $bookname;
for ($x = 1; $x <= $pagenumber; $x++) {
	?>
	<div id="<?php echo $bookname.''.$x ?>" onclick="dumpage(<?php echo "'$bookname'" ?>, <?php echo $x ?>, <?php echo $w ?>, <?php echo $h ?>)" style="width:<?php echo $w ?>px; height:<?php echo $h ?>px; margin: 10px 0 0 10px; float: left; border: 1px solid #000000; background: url(<?php echo base_url()?>images/grid.png)">test</div>
	<?php 
}
?>

<script type="text/javascript">
	function dumpage(book, page, w, h)
	{
		var setcolor = $('#color').val();
		alert(setcolor);
		if (setcolor == 3) {
			$("#"+book+page).attr("style", "width:"+w+"px; height:"+h+"px; margin: 10px 0 0 10px; float: left; border: 1px solid red; background: url(<?php echo base_url()?>images/grid.png);");		
		}
	}
</script>
