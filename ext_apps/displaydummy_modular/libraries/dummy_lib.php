<?php

class Dummy_Lib {
   
    
    function constant_variable($constant) {
        
        $constant_variable = array('col_per_pixel' => $constant['cols'], 
                                   'cm_per_pixel' => $constant['cm'], 
                                   'cm_per_pixelmod' => $constant['cmmod'], 
                                   'gutter_per_pixel' => $constant['gutter'],
                                   'border_thickness' => $constant['border_thickness'],
                                   'innerborder_thickness' => $constant['innerborder_thickness'],
                                   'column' => $constant['column'],
                                   'centimeter' => $constant['centimeter'],
                                   'centimetermod' => $constant['centimetermod'],
                                   'totacolcm' => $constant['totacolcm']
                                   );        
        
        return $constant_variable;
    }  
    
    function create_layout($color, $papersize, $constant, $data, $randname) {

		 ini_set('memory_limit', '-1');
        #header ('Content-Type: image/jpeg');
        $cons = $this->constant_variable($constant);
        $paper_size = $this->paper_layout($papersize);      
     
        $im = @imagecreatetruecolor($paper_size['width'], $paper_size['length']) or die('Cannot Initialize new GD image stream');
        
        /* Color List */
        $red = imagecolorallocate($im, 255, 0, 0);
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        $darkgrey = imagecolorallocate($im, 150, 149, 149);
        $grey= imagecolorallocate($im, 204, 204, 204);
        
        switch(strtolower($color)) {
            
            case "white":
                $color = $white;    
            break;
            
            case "red":
                $color = $red;
            break;
            
            case "black":
                $color = $black;
            break;
            
            case "darkgrey":
                $color = $darkgrey;
            break;
            
            case "grey":
                $color = $grey;
            break;    
        }
        
        imagefill($im, 0, 0, $color);  
        
        /* Constant Border */
        imagesetthickness($im, $constant['border_thickness']); 
        
        $pw_border = $paper_size['width'] - 50;
        $pl_border =  $paper_size['length'] - 50; 
        #$pl_border =  $paper_size['length']; 
        

        imageline ($im , 50 , 200 , $pw_border , 200 , $black);                 # top
        imageline ($im , 50 , $pl_border , $pw_border , $pl_border , $black);   # bottom
        imageline ($im , 50 , 200 , 50 , $pl_border , $black);                  # left
        imageline($im , $pw_border , 200 , $pw_border , $pl_border , $black);   # right
        
        /* Inner Border */    
        imagesetthickness($im, $cons['innerborder_thickness']); 
        
        $pw_innerborder = $paper_size['width'] - 250;  
        #$pl_innerborder =  $paper_size['length'] - 190;  
        $pl_innerborder =  $paper_size['length'] - 80;  
        
        imageline ($im , 250 , 390 , $pw_innerborder , 390 , $darkgrey);                             # top
        imageline ($im , 250 , $pl_innerborder , $pw_innerborder , $pl_innerborder , $darkgrey);     # bottom
        imageline ($im , 250 , 390 , 250 , $pl_innerborder , $darkgrey);                             # left      
        imageline($im , $pw_innerborder , 390 , $pw_innerborder , $pl_innerborder , $darkgrey);      # right
        
        /* Column lines */
        $column_point = 250;       
        $cols = $cons['col_per_pixel'] + $cons['gutter_per_pixel'];        
        $col_new_point = $column_point + $cols;
        for ($c = 1; $c < $cons['column']; $c++) {

        #column #imagedashedline($im, $col_new_point - $cons['gutter_per_pixel'], 300, $col_new_point - $cons['gutter_per_pixel'], $paper_size['length'] - 202, $grey);    
            imagedashedline($im, $col_new_point - $cons['gutter_per_pixel'], 450, $col_new_point - $cons['gutter_per_pixel'], $paper_size['length'] - 80, $grey);    
        #column #imagedashedline($im, $col_new_point, 300, $col_new_point, $paper_size['length'] - 202, $grey);        
            imagedashedline($im, $col_new_point, 450, $col_new_point, $paper_size['length'] - 80, $grey);        
            $col_new_point += $cols;
        } 
                
        /*
        * Creating grid lines x axis 
        */   
               
        $cm_point = 230;
        $centi = $cons['cm_per_pixelmod'];
        $cm_new_point = $cm_point + $centi;
        $centiledger = $cons['centimetermod'];

        $font = dirname(__FILE__) . '/fonts/arial.ttf';      
        $style = array($white, $white, $white, $grey, $grey, $grey);
        imagesetstyle($im, $style);
        imagettftext($im, 50, 0, 250, 120, $black, $font, '(MODULAR) Rundate : '.$data['rundate']);                                           
        imagettftext($im, 50, 0, 250, 280, $black, $font, $data['pageprint']['class_code'].'-'.$data['pageprint']['folio_number']);    
        imagettftext($im, 50, 0, ($paper_size['width'] / 2) - 70, 280, $black, $font, 'Page '.$data['pageprint']['book_name'].'-'.$data['pageprint']['folio_number']);    
        for ($cm = 1 ; $cm <= $cons['centimetermod'] + 1; $cm++) {
             
            imagettftext($im, 25, 0, 195, $cm_new_point + 10, $black, $font, $centiledger);    
            imageline($im, 240, $cm_new_point, $paper_size['width'] - 240, $cm_new_point, IMG_COLOR_STYLED); 
            imagettftext($im, 25, 0, $paper_size['width'] - 230, $cm_new_point + 10, $black, $font, $centiledger);    
            if ($cm != 1) {              
            imageline($im, 250, $cm_new_point - 35, $paper_size['width'] - 250, $cm_new_point - 37, IMG_COLOR_STYLED);    
            }

            $centiledger -= 1; 
            $cm_new_point += $centi;  
            
        }   

        imagettftext($im, 50, 0, ($paper_size['width'] / 2) - 70, 4200, $black, $font, $data['pageprint']['color_code']); 
        
        /* Create Boxes */
        
        $bw = imagecolorallocate($im, 255, 255, 255);
        $spot = imagecolorallocate($im, 240, 234, 234);
        $tspot = imagecolorallocate($im, 178, 170, 170);
        $fcol = imagecolorallocate($im, 152, 150, 150);
        
        $margin_left = 250;
        $margin_top = 300;
        $totalcmbox = 0;
        $countbox = count($data['boxprint']);      
        if ($countbox > 0) {                                                    
            for ($b = 0; $b < $countbox; $b++) {
                
                $box_x1 = (($data['boxprint'][$b]['xaxis'] / 55) * $cons['col_per_pixel']) + ((($data['boxprint'][$b]['xaxis'] / 55)) * $cons['gutter_per_pixel']);    
                $box_y1 = ($data['boxprint'][$b]['yaxis'] / 12) * $cons['cm_per_pixel'] + 90;       
                #$box_y1 = 2085;
                $box_x2 = ($data['boxprint'][$b]['box_width'] * $cons['col_per_pixel']) + (($data['boxprint'][$b]['box_width'] - 1) * $cons['gutter_per_pixel']);   
                $box_y2 = ($data['boxprint'][$b]['box_height'] * $cons['cm_per_pixel']); 
                #$box_y2 = 1560;  
                $totalcmbox += ($data['boxprint'][$b]['box_height'] * $data['boxprint'][$b]['box_width']);
                
                $xp1 = $box_x1 + $margin_left;
                $yp1 = $box_y1 + $margin_top;
                $xp2 = (($box_x1 + $margin_left) + $box_x2);
                $yp2 = (($box_y1 + $margin_top) + $box_y2);
                imagerectangle($im, $xp1, $yp1, $xp2, $yp2, $black);  
                $rectcolor = $bw;
                
                switch ($data['boxprint'][$b]['ao_color']) {
                    case 0:
                        $rectcolor = $bw;    
                    break;
                    case 1:
                        $rectcolor = $tspot;
                    break;
                    case 2:
                        $rectcolor = $fcol;
                    break;
                    case 3:
                        $rectcolor = $spot;                                                                        
                    break;
                }    
                      
                imagefilledrectangle($im, ($box_x1 + $margin_left), ($box_y1 + $margin_top), (($box_x1 + $margin_left) + $box_x2), (($box_y1 + $margin_top) + $box_y2), $rectcolor);    
                
                $char = 6;
                $fontsize = 40;
                $textlen = ($data['boxprint'][$b]['box_width'] * $char) + $data['boxprint'][$b]['box_width'];
                if ($data['boxprint'][$b]['box_width'] <= 2 && $data['boxprint'][$b]['box_height'] <= 2) {
                    $fontsize = 25;  
                    $textlen = 15;
                }            
                $lines = explode('|', wordwrap($data['boxprint'][$b]['ao_payee'], $textlen, '|'));
                $textx = ($box_x1 + $margin_left) + 10;                                                                                                                                                                 
                $texty = ($box_y1 + $margin_top) + ($data['boxprint'][$b]['box_width'] * 20);
                imagettftext($im, $fontsize, 0, $textx, $texty, $black, $font, $data['boxprint'][$b]['color_name'].' '.$data['boxprint'][$b]['ao_num']); 
                $texty += 45;              
                imagettftext($im, $fontsize, 0, $textx, $texty, $black, $font, $data['boxprint'][$b]['boxsize']);     
                $texty += 45;            
                foreach ($lines as $line)
                {                                                
                    imagettftext($im, $fontsize, 0, $textx, $texty, $black, $font, $line);     
                    $texty += 45;           
                }
                imagettftext($im, $fontsize, 0, $textx, $texty, $black, $font, $data['boxprint'][$b]['ao_eps']);     
                            
            }
        }
        
        $pagepercent = ($totalcmbox / $cons['totacolcm']) * 100; 
        
        imagettftext($im, 50, 0, $paper_size['width'] - 350, 280, $black, $font, round($pagepercent, 2).'%');     
        

        imagejpeg($im, "/tmp/dummy_layout_output/".$randname."".$data['pageprint']['book_name']."".$data['pageprint']['folio_number'].".jpg");
        #imagejpeg($im, "D:\\test\\".$randname."".$data['pageprint']['book_name']."".$data['pageprint']['folio_number'].".jpg");
        imagedestroy($im);         

    }       

    private function paper_layout($paper_size) {
        
        switch (strtoupper($paper_size)) {
            case "LEGAL":
                $size = array('width' => 2550, 'length' => 4200);
            break;
            
            case "LETTER":
                $size = array('width' => 2550, 'length' => 3300);
            break;
        }
        return $size;                          
        
    }
    
}
