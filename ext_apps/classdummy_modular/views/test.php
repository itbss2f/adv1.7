<?php
 echo "asdasd";
exit; 
header ('Content-Type: image/png');

$im = @imagecreatetruecolor(2550, 4200) or die('Cannot Initialize new GD image stream');

// colors
$red = imagecolorallocate($im, 255, 0, 0);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
$darkgrey = imagecolorallocate($im, 150, 149, 149);
$grey= imagecolorallocate($im, 204, 204, 204);

$bw = imagecolorallocate($im, 255, 255, 255);
$spot = imagecolorallocate($im, 240, 234, 234);
$tspot = imagecolorallocate($im, 178, 170, 170);
$fcol = imagecolorallocate($im, 152, 150, 150);

imagefill($im, 0, 0, $white);  


/*
* Outside border 
*/
imagesetthickness($im, 5); 
imageline ($im , 50 , 200 , 2500 , 200 , $black);    # top
imageline ($im , 50 , 4150 , 2500 , 4150 , $black);  # bottom
imageline ($im , 50 , 200 , 50 , 4150 , $black);     # left
imageline($im , 2500 , 200 , 2500 , 4150 , $black); # right

/*
* Inside border 
*/
imagesetthickness($im, 4); 
imageline ($im , 250 , 300 , 2300 , 300 , $darkgrey);    # top
imageline ($im , 250 , 4010 , 2300 , 4010 , $darkgrey);  # bottom
imageline ($im , 250 , 300 , 250 , 4010 , $darkgrey);     # left
imageline($im , 2300 , 300 , 2300 , 4010 , $darkgrey); # right

/*
* Creating grid lines y axis 
*/


$column_point = 250;
$cols = 210 + 20;
$col_new_point = $column_point + $cols;
for ($c = 1; $c < 9; $c++) {

    imagedashedline($im, $col_new_point - 20, 300, $col_new_point - 20, 3998, $grey);    
    imagedashedline($im, $col_new_point, 300, $col_new_point, 3998, $grey);        
    $col_new_point += $cols;
}


$style = array($white, $white, $white, $grey, $grey, $grey);
imagesetstyle($im, $style);

$cm_point = 230;
$centi = 70;
$cm_new_point = $cm_point + $centi;
$centiledger = 53;

/*
* Creating grid lines x axis 
*/     

$font = dirname(__FILE__) . '/fonts/arial.ttf';      

for ($cm = 1 ; $cm <= 54; $cm++) {
    
    #imagestring($im, 8, 220, $cm_new_point - 8, $centiledger , $black);      
    #imagettftext($im, 10, 0, 11, 21, $grey, $font, $text);    
    imagettftext($im, 25, 0, 195, $cm_new_point + 10, $black, $font, $centiledger);    
    imageline($im, 240, $cm_new_point, 2300, $cm_new_point, IMG_COLOR_STYLED); 
    if ($cm != 1) {              
    imageline($im, 250, $cm_new_point - 35, 2300, $cm_new_point - 37, IMG_COLOR_STYLED);    
    }

    $centiledger -= 1; 
    $cm_new_point += $centi;  
    
}   

imagerectangle($im, 250, 300, 690, 650, $black);  
imagefilledrectangle($im, 250, 300, 690, 650, $bw);

imagerectangle($im, 710, 720, 920, 825, $black);     
imagefilledrectangle($im, 710, 720, 920, 825, $fcol);   

imagerectangle($im, 1170, 720, 1380, 825, $black);     
imagefilledrectangle($im, 1170, 720, 1380, 825, $spot);   

imagerectangle($im, 1630, 1080, 2070, 1825, $black);     
imagefilledrectangle($im, 1630, 1080, 2070, 1825, $tspot);   


imagepng($im);
imagedestroy($im);

/*function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 4) {
    # this way it works well only for orthogonal lines
    #imagesetthickness($image, $thick);
    #return imageline($image, $x1, $y1, $x2, $y2, $color);
    
    if ($thick == 1) {
        return imageline($image, $x1, $y1, $x2, $y2, $color);
    }
    $t = $thick / 2 - 0.5;
    if ($x1 == $x2 || $y1 == $y2) {
        return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
    }
    $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
    $a = $t / sqrt(1 + pow($k, 2));
    $points = array(
        round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
        round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
        round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
        round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
    );
    imagefilledpolygon($image, $points, 4, $color);
    return imagepolygon($image, $points, 4, $color);
}*/

/*function verticalDashedLine($image, $x0, $y0, $x1, $y1, $fg, $bg)
{
        $st = array($fg, $fg, $fg, $fg, $bg, $bg, $bg, $bg);
        ImageSetStyle($image, $st);
        ImageLine($image, $x0, $y0, $x1, $y1, IMG_COLOR_STYLED);
}*/ 

