<?php

/*
    Sequence Layout Numbering
*/

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
    if ($pnval == $page[$x]['book_name']) {
            $pn += 1;            
    } else { 
            $pn = 1;            
    } 
    $pnval = $page[$x]['book_name'];   
	if ($page[$x]['color_code'] == "Spo") {
		$border = "border: 1px solid  #".$page[$x]['color_html'].";";
	} else if ($page[$x]['color_code'] == "4Co") {
		$border = "border: 1px solid #".$page[$x]['color_html'].";";
     } else if ($page[$x]['color_code'] == "2Sp") {
		$border = "border: 1px solid #".$page[$x]['color_html'].";";
	} else {
		$border = "border: 1px solid #000000;";
	}	
	$bookname = $page[$x]['book_name'];
	?>    
        <div style="width:<?php echo $w+20 ?>px; height:<?php echo $h+10 ?>px; margin: 10px 0 20px 10px;  float: left;">
            <div><?php echo $page[$x]['class_code']?> | <?php echo $bookname."-".$pn?></div>
            <div id="<?php echo $bookname.''.$pn ?>" onclick="dumpage(<?php echo "'$bookname'" ?>, <?php echo $page[$x]['id'] ?>, <?php echo $w ?>, <?php echo $h ?>)" style="<?php echo $border ?>width:<?php echo $w ?>px; height:<?php echo $h ?>px; margin: 10px 0 10px 10px; float: left; background: url(<?php echo base_url()?>images/grid.png)"> </div>       
        </div>
	<?php 
}
?>


