<?php
$countpage = COUNT($page);
$z = 0;
$pn = 0;
$pnval = "";
for ($x = 0; $x < $countpage; $x++) {
	$col = $page[$x]['columns'];
	$hen = $page[$x]['len'];
	$w = ($col * 30) + (($col - 1) * 5);
	$h = $hen * 10;
	$z += 1;
    if ($pnval == $page[$x]['section']) {
            $pn += 1;            
    } else {
            $pn = 1;            
    }    
    $pnval = $page[$x]['section'];
    
	if ($page[$x]['color'] == "Spo") {
		$border = "border: 1px solid  #".$page[$x]['color_html'].";";
	} else if ($page[$x]['color'] == "4Co") {
		$border = "border: 1px solid #".$page[$x]['color_html'].";";
     } else if ($page[$x]['color'] == "2Sp") {
		$border = "border: 1px solid #".$page[$x]['color_html'].";";
	} else {
		$border = "border: 1px solid #000000;";
	}	
	$bookname = $page[$x]['book_name'];
	?>        
        <div id="<?php echo $bookname.''.$z ?>" onclick="dumpage(<?php echo "'$bookname'" ?>, <?php echo $page[$x]['counternum'] ?>, <?php echo $w ?>, <?php echo $h ?>)" style="<?php echo $border ?>width:<?php echo $w ?>px; height:<?php echo $h ?>px; margin: 10px 0 10px 10px; float: left; background: url(<?php echo base_url()?>images/grid.png)">        
        Section: <?php echo $page[$x]['sectionname']?>
        <br>
        Page: <?php echo $bookname."-".$pn ?>
        </div>         
	<?php 
}
?>

<div id="<?php echo $bookname.''.$page[$x]['layout_sequence'] ?>" onclick="dumpage(<?php echo "'$bookname'" ?>, <?php echo $page[$x]['layout_sequence'] ?>, <?php echo $w ?>, <?php echo $h ?>)" style="<?php echo $border ?>width:<?php echo $w ?>px; height:<?php echo $h ?>px; margin: 10px 0 10px 10px; float: left; background: url(<?php echo base_url()?>images/grid.png)">        
        Section: <?php echo $page[$x]['class_code']?>
        <br>
        Page: <?php echo $bookname."-".$page[$x]['layout_sequence'] ?>
        </div>         


