<?php

set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '../../zend/1.12.0/library/')));           

require_once('Zend/Pdf.php');

abstract class Crystal_Template_Abstract 
{
	public $_pdf  = null;
	
	protected $_size = null;
	
	public function render()
	{
		return $this->_pdf->render();
	}
}

interface Crystal_Template_Interface {}

class Crystal_Template_Zend extends Crystal_Template_Abstract implements Crystal_Template_Interface
{
	const FONT = Zend_Pdf_Font::FONT_HELVETICA;
	
	const FONT_BOLD = Zend_Pdf_Font::FONT_HELVETICA_BOLD;
	
	const FONT_BOLD_ITALIC = Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC;
	
	const FONT_ITALIC = Zend_Pdf_Font::FONT_HELVETICA_ITALIC;
	
	const SIZE_LEGAL = '612:1008:';

    const SIZE_LETTER = '595:795';
   
    const SIZE_LETTER_LANDSCAPE = '795:595';
	
	const SIZE_LEGAL_LANDSCAPE = '1008:612:';

	
	protected $_fields = array();
	
	protected $_y = 0;
	
	protected $_x = 10;
	
	protected $_size = null;

	protected $_page = null;
    
    protected $_margin = array(15, 15, 50, 15);
    
    protected $CI;

                                                 
    public function setY($y)
    {
        return $this->_y += $y;
    }
    
    public function getY() {
        return $this->_y;
    }
    
    public function getX() {
        return $this->_x;
    }
                                                 
	public function __construct($size = self::SIZE_LEGAL)
	{
		$this->_pdf = new Zend_Pdf();  

		// set initial page
		
		$this->_page = new Zend_Pdf_Page($this->_size = $size);
		
		$this->_page->_y = $this->_margin[0];
		
		$this->_page->_x = $this->_margin[3];
		
		$this->setFont(self::FONT);
	}
    
    public function setMargin($x1, $x2, $y1, $y2) {
        return $_margin = array($x1, $x2, $y1, $y2); 
   
    }

	public function setFont($font, $size = 12)
	{
		$font = Zend_Pdf_Font::fontWithName($font);
	
		$this->_page->setFont($font, $size);
	
		return $this;
	}
	
	public function setInput($width, $size = 12, $page = null, $break = true)
	{
		$page = $page == null ? $this->_page : $page;
		
		$x = $page->_x;
		
		$y = $page->_y;
		
		$p = $this->_getPoints(array($x, $y + $size - 2), $size);
		
		$page->drawLine($p['x'], $p['y'], $p['x'] + $width, $p['y']);
		
		$page->_y += $size + 3; // increment
			
		$page->_x = $this->_margin[3];
		
		return $this;
	}
	
	public function setText($text, $size = 12, $page = null, $params = null, $break = true, $float = false, $align = null)
	{
		$page = ($page == null) ? $this->_page : $page;
		
        
        $width = $this->_getTextWidth($text, $page->getFont(), $size) + 10; 
        
        // Set Alignment

        #echo $page->_x;
        #echo $width; exit;
        $align = isset($align) ? $align : 'left';
        #echo $align; exit;   
        $widthpage = $page->getWidth();     
        switch ($align) {

            case 'right':
                
                #$textWidth = $this->_getTextWidth(@$field['text'], $page->getFont(), $page->getFontSize());
                
                $page->_x = ($widthpage - $width) - 5;
                
                break;
                
            case 'center':                
                #$textWidth = $this->_getTextWidth(@$field['text'], $page->getFont(), $page->getFontSize());
                
                $page->_x = (($widthpage - $width) / 2);
                
                break;
                
            case 'left':
                
            default:
                
                $page->_x = $this->_margin[3];
        }  
        
        
        
		$x = isset($params['left']) ? $params['left'] + $page->_x : $page->_x;
	
		$y = isset($params['top']) ? $params['top'] + ($float ? 0 : $page->_y) : $page->_y;
		        
        //$x = $x + $left;
        
        //if ($bottom) $y = $this->_page->getHeight() - ($size + 80);
        
		
		$p = $this->_getPoints(array($x, $y), $size);
		
		$page->setFont($page->getFont(), $size);
		
		$page->drawText($text, $p['x'], $p['y']);
        
        if ($break) { $page->_y += $size + 3; } // increment
        
		
		/*if ($break) {
		
			$page->_y += $size + 3; // increment
			
			$page->_x = $this->_margin[3];

		} else {

			$width = $this->_getTextWidth($text, $page->getFont(), $size) + 10;
			
			$width = !empty($params['width']) ? $params['width'] : $width;
			
			$page->_x += $params['left'] + $width;

			$page->_y = $y;
		}
        */
        

		return $this;
	}
    
    public function setFieldsHead($fields, $coord = array(), $page = null)
    {
        $page = (!is_null($page)) ? $page : $this->_page;
        
        
        $margin = array(10, $this->_margin[3], 10, $this->_margin[3]);
        
        $lineHeight = 2; $fontSize = 8;
        
        
        // top border
        
        
        $page->_y = isset($coord[1]) ? $coord[1] : $page->_y;
        
        
        $p1 = $this->_getPoints(array($margin[3], ($y = $page->_y + $margin[0])));
        
        $p2 = $this->_getPoints(array($page->getWidth() - $margin[1], $y + $lineHeight));
        
        
        
        $page->drawRectangle($p1['x'], $p1['y'], $p2['x'], $p2['y'], Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
        
        
        $this->setFont(self::FONT_ITALIC, $fontSize);
        
        $l = $margin[3];
        
        $page->_y += $margin[0] + $lineHeight + $fontSize + 2;
        
        
        $this->_fields = $fields;
        
        foreach ($this->_fields as $i => $field) {
        
            $width = $field['width'] * ($page->getWidth() - ($margin[1] + $margin[3]));
            
            $align = isset($field['align']) ? $field['align'] : 'left';
                  
            switch ($align) {

                case 'right':
                    
                    $textWidth = $this->_getTextWidth(@$field['text'], $page->getFont(), $page->getFontSize());
                    
                    $left = $l + ($width - $textWidth) - 5;
                    
                    break;
                    
                case 'center':
                    
                    $textWidth = $this->_getTextWidth(@$field['text'], $page->getFont(), $page->getFontSize());
                    
                    $left = $l + (($width - $textWidth) / 2);
                    
                    break;
                    
                case 'left':
                    
                default:
                    
                    $left = $l + 5;
            }
            
            $p3 = $this->_getPoints(array($left, $page->_y));
            
            $page->drawText(@$field['text'] . "\t", $p3['x'], $p3['y']);
        
            $l += $width;
        }
        
        $this->setFont(self::FONT);
        
        // bottom border
        
        $page->_y += $fontSize;
        
        $p1 = $this->_getPoints(array($margin[3], ($y = $page->_y - 2)));
        
        $p2 = $this->_getPoints(array($page->getWidth() - $margin[1], $y + $lineHeight));
        
        #$page->drawRectangle($p1['x'], $p1['y'], $p2['x'], $p2['y'], Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
        
        $page->_y += $margin[2];
         
        return $this;
    }
	
	public function setFields($fields, $coord = array(), $page = null)
	{
		$page = (!is_null($page)) ? $page : $this->_page;
        
		
		$margin = array(10, $this->_margin[3], 10, $this->_margin[3]);
		
		$lineHeight = 2; $fontSize = 8;
		
        
		// top border
		
        
        $page->_y = isset($coord[1]) ? $coord[1] : $page->_y;
        
        
		$p1 = $this->_getPoints(array($margin[3], ($y = $page->_y + $margin[0])));
		
		$p2 = $this->_getPoints(array($page->getWidth() - $margin[1], $y + $lineHeight));
        
        
		
		$page->drawRectangle($p1['x'], $p1['y'], $p2['x'], $p2['y'], Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
		
		
		$this->setFont(self::FONT_ITALIC, $fontSize);
		
		$l = $margin[3];
		
		$page->_y += $margin[0] + $lineHeight + $fontSize + 2;
		
		
		$this->_fields = $fields;
		
		foreach ($this->_fields as $i => $field) {
		
			$width = $field['width'] * ($page->getWidth() - ($margin[1] + $margin[3]));
			
			$align = isset($field['align']) ? $field['align'] : 'left';
			      
			switch ($align) {

				case 'right':
					
					$textWidth = $this->_getTextWidth(@$field['text'], $page->getFont(), $page->getFontSize());
					
					$left = $l + ($width - $textWidth) - 5;
					
					break;
					
				case 'center':
					
					$textWidth = $this->_getTextWidth(@$field['text'], $page->getFont(), $page->getFontSize());
					
					$left = $l + (($width - $textWidth) / 2);
					
					break;
					
				case 'left':
					
				default:
					
					$left = $l + 5;
			}
			
			$p3 = $this->_getPoints(array($left, $page->_y));
			
			$page->drawText(@$field['text'] . "\t", $p3['x'], $p3['y']);
		
			$l += $width;
		}
		
		$this->setFont(self::FONT);
		
		// bottom border
		
		$page->_y += $fontSize;
		
		$p1 = $this->_getPoints(array($margin[3], ($y = $page->_y - 2)));
		
		$p2 = $this->_getPoints(array($page->getWidth() - $margin[1], $y + $lineHeight));
		
		$page->drawRectangle($p1['x'], $p1['y'], $p2['x'], $p2['y'], Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
		
		$page->_y += $margin[2];
	 	
		return $this;
	}
	
	public function setData($data, $group = null, $page = null)
	{
		$margin = array(15, $this->_margin[1], 5, $this->_margin[3]);
		
		$defaultFontSize = $fontSize = 8;

		$defaultFontStyle = $fontStyle = Zend_Pdf_Font::fontWithName(self::FONT);
		
		
		$page = $page ? $page : $this->addPage($fontSize); // 2013-06-27
		
		$top  = $page->_y;
		
        
        $total = $k = 0;
        
        $temp1 = $temp2 = '';
        
		foreach ($data as $datum) {

            // added break 2013-04-29 : ronald
            
            if (isset($datum['break']) && $datum['break']) {

                $page = $this->addPage($fontSize);
                
                $top  = $page->_y;
                
                $page->_x = $this->_margin[3];
                
                continue;
            }
            
			$datum = array_values($datum);
			
			// next page?
			
			if ($top >= ($page->getHeight() - $this->_margin[2])) {				
				
				$page = $this->addPage($fontSize);
				
				$top  = $page->_y;
				
				$page->_x = $this->_margin[3];
			}
			
			$l = $margin[3]; $i = 0;
                        
            
            $temp1 = @implode('', array_slice($datum, 0, $group + 1));
            
            $total = ($temp1 == $temp2) ? $total : 0;
            
			foreach ($datum as $each) {

                $fields = $this->_fields;
				
				$text = is_array($each) ? $each['text'] : $each;
                
                $text = $this->_formatText($text, $each);

				// has style?
				
				$fontSize  = is_array($each) && isset($each['size']) ? $each['size'] : $fontSize;

				$fontStyle = is_array($each) && isset($each['bold']) && $each['bold'] ? Zend_Pdf_Font::fontWithName(self::FONT_BOLD) : $defaultFontStyle;
							
				$page->setFont($fontStyle, $fontSize);


				// compute column width
				
				$width = $fields[$i]['width'] * ($page->getWidth() - ($margin[1] + $margin[3]));

				if (is_array($each) && isset($each['columns'])) {
					
					$j = 0;
					
					for ($k = 0; $k < $each['columns']; $k++) {
						
						$j += $fields[$i + $k]['width']; 	
					}
					
					$i += $each['columns'] - 1;
					
					$width = $j * $page->getWidth();
				}
				
                
                // compute total
                /*
                if (strtolower($this->_fields[$i]['text']) == 'total') {
                    
                    $total += (float) $datum[$text];
                    
                    $next = @implode('', array_slice($data[$k + 1], 0, $group + 1));
                    
                    $text = ($temp1 != $next) ? round($total, 2) : '';
                }
				*/
                
                
				// setting text align; compute left point
				
				$align = isset($fields[$i]['align']) ? $fields[$i]['align'] : 'left';
					
				$align = is_array($each) && isset($each['align']) ? $each['align'] : $align; // override if align is set
				
				$textWidth = $this->_getTextWidth($text, $page->getFont(), $page->getFontSize());
				
				switch ($align) {

					case 'right':

						$left = $l + ($width - $textWidth) - 5;
			
						break;
			
					case 'center':
			
						$left = $l + (($width - $textWidth) / 2);
			
						break;
			
					case 'left':
			
					default:
			
						$left = $l + 5;
				}

				// clipping
				
				$p2 = $this->_getPoints(array($l, $top));
		
                //$p3 = $this->_getPoints(array($l + $width + 30, $top + ($fontSize + 4.5)));
                
                
                #$p3 = $this->_getPoints(array($page->getWidth(), $top + ($fontSize + 4.5)));
				$p3 = $this->_getPoints(array($page->getWidth(), $top + ($fontSize + 4.5)));
		
				$page->setFillColor(new Zend_Pdf_Color_Rgb(255, 255, 255));
					
				$page->drawRectangle($p2['x'], $p2['y'], $p3['x'], $p3['y'], Zend_Pdf_Page::SHAPE_DRAW_FILL/*_AND_STROKE*/);
				
				$page->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0));
				
				
				// highlight for summary (total)
				
				if (is_array($each) && isset($each['style'])) {
					
					$page->setFont(Zend_Pdf_Font::fontWithName(self::FONT_BOLD), $fontSize);
					
					$w1 = $textWidth + (.20 * $width);
					
					$w1 = .88 * $width;
					
					switch ($align) {
					
						case 'right':
					
							$x1 = $p2['x'] + ($width - $w1) - 5;
							
							break;
								
						case 'center':
								
							$x1 = $p2['x'] + (($width - $w1) / 2);
							
							break;
								
						case 'left':
								
						default:
								
							$x1 = $p2['x'];
					}
					
					$x2 = $x1 + $w1;
					
					$page->drawLine($x1, $y1 = ($p2['y'] + 1), $x2, $y1);
					
					$page->drawLine($x1, $y1 = ($p2['y'] - $fontSize - 4), $x2, $y1);
				}
				
                // setting text
                
                
				
				if ($group === null || ($i > ($group)) || ($temp1 != $temp2)) {
                    
                    $p1 = $this->_getPoints(array($left, $top), $fontSize);
                    
                    // added yposition 2013-07-04 : bossing
                
                    /*if (is_array($each) && isset($each['ypos'])) {                 
                        $p1['y'] = $each['ypos']; 
                    }*/ 
                    
				    $page->drawText($text . "\t", $p1['x'], $p1['y']);
                }
                
                
				
                
                #if (isset($each['hook'])) {
                if (is_array($each) && isset($each['hook'])) {       
     
                    $each['hook'][0]($page, array($p1['x'], $p1['y']), $each['hook'][1]); 
                }
                
				$l += $width;
				
				$i++;
			}
			
			$top += $fontSize + $margin[2];
			
			$page->_y = $top;
			
            // resetting font
            
            $fontSize  = $defaultFontSize;
			
			$fontStyle = $defaultFontStyle;
            
            $k++;
            
            $temp2 = $temp1;
		}
		
		$page->setFont($defaultFontStyle, $defaultFontSize);
	}
	
    public function getPage()
    {
        return $this->_page;
    }
    
    public function boss() {
        $pages = count($this->_pdf->pages);      
        $pagenum = 0;
        foreach ($this->_pdf->pages as $i => $page) {
        
            $pagenum += 1;  
            /*$textrundate = Date('Y-m-d');
            
            $left = ($page->getWidth() - $this->_getTextWidth($text, $page->getFont(), $size)); 
            
            $xy = $this->_getPoints(array($left - $this->_margin[3], $this->_margin[0]), $size);
            
            $page->setFont($page->getFont(), $size);
            
            $page->drawText($text, $xy['x'] - 20, $xy['y'] - 30);
            $page->drawText($textrundate, $xy['x'] - 20, $xy['y'] - 40);*/   
        } 
        echo $pagenum; exit;
        return $pagenum;
    }
    
    public function getLastPage()
    {
        $n = $this->countPages();
        
        return $this->_pdf->pages[$n - 1];     
    }
    
    public function countPages()
    {
        return count($this->_pdf->pages);
    }
    
    public function addPage($font = 8)
    {
        $page = clone $this->_page;
        
        $page->setFont(Zend_Pdf_Font::fontWithName(self::FONT), $font);
        
        $this->_pdf->pages[] = $page;
        
        return $page;
    }
    
    public function setPagination($size = 7)
    {
        // set page number
        
        $pages = count($this->_pdf->pages);      
        
        foreach ($this->_pdf->pages as $i => $page) {
        
            $text = sprintf("page: %d of %d", ($i + 1), $pages); 
            $CI =& get_instance();
            $sessiondata = $CI->session->all_userdata(); 
            #$this->session->userdata('sess_name');
                        
            #print_r2($generateby);
            
            #echo $generateby['authsess']->sess_name;
            #echo "hala";
            #echo $CI->session->userdata($generateby['sess_name']);
            #print_r($CI->session->all_userdata('sess_name'));
            #echo "test";
            
            #echo $generateby; 
            #exit;
            
            $generateby = $sessiondata['authsess']->sess_name; 
            //$textrundate = Date('Y-m-d H:i:s');
            $textrundate = Date('m/d/Y H:i:s');
            
            $left = ($page->getWidth() - $this->_getTextWidth($text, $page->getFont(), $size)); 
            
            $xy = $this->_getPoints(array($left - $this->_margin[3], $this->_margin[0]), $size);
            
            $page->setFont($page->getFont(), $size);
            
            $page->drawText($text, $xy['x'] - 10, $xy['y']);
            $page->drawText($generateby, $xy['x'] - 60, $xy['y'] - 10);   
            $page->drawText($textrundate, $xy['x'] - 15, $xy['y'] - 10);   
        } 
    }
     
	protected function _getPoints($points, $height = 0)
	{
		$y = ($this->_page->getHeight() - $points[1]) - $height;
		
		$x = $points[0];
		
		return array('x' => $x, 'y' => $y);
	}

	protected function _getTextWidth($text, Zend_Pdf_Resource_Font $font, $font_size)
	{
		setlocale(LC_CTYPE, 'en_US.UTF-8');
		
		$drawing_text = iconv('', 'UTF-16BE//IGNORE', $text);
		
		$characters = array();
		
		for ($i = 0; $i < strlen($drawing_text); $i++) {
			
			$characters[] = (ord($drawing_text[$i++]) << 8) | ord ($drawing_text[$i]);
			
		}
		
		$glyphs = $font->glyphNumbersForCharacters($characters);
		
		$widths = $font->widthsForGlyphs($glyphs);
		
		$text_width = (array_sum($widths) / $font->getUnitsPerEm()) * $font_size;
		
		return $text_width;
	}
    
    protected function _formatText($text, $options = null)
    {
        switch (true) {
            /*case (isset($options['numformat'])                 
                  || isset($options['negative']) 
                  || isset($options['blank'])):  //&& $options['numformat']):
                
                    if (@$options['numformat']) {
                        
                        $text = number_format($text, 2, '.', ',');            
                    } 
                    
                    if (@$options['negative']) {                        
                        $text = '('.str_replace('-','',$text).')';        
                    }
                    
                    if (@$options['blank']) {
                        if ($text == 0) {
                            $text = '';
                        }    
                    }
                    
                break;    */
            
            case (isset($options['blank']) && $options['blank']):                             
            
                if ($text == 0) {
                    $text = '';
                }
            
                break; 
            
            /*case (isset($options['negative']) && $options['negative']):                             
                
                if ($text < 0) {                    
                    $text = '('.abs($text).')';
                }
                            
                break;
            */
            /*case check_date($text): 
            
                $text = date('m/d/Y', strtotime($text));

                break;
            
            case check_time($text): 
            
                $text = date('H:i', strtotime($text));
                
                break;*/
            
            //case is_numeric($text): 
            
            case preg_match('/[\d]*(\.[\d])+/', $text): 
            
                //$text = number_format($text, 2, '.', ',');
                
                //$text = ($text == "0") ? '' : $text;
				  $text;
                
                break;
            
            case $text == "0": 
            
                $text = '0';
            
                break;
        }
        
        
        
        
        return $text;
    }
}

class Crystal
{
	protected $_template = '';
	
	public static function create($template, $size)
	{
		$template = 'Crystal_Template_' . $template;
		
		$report = new Crystal();
		
		$report->_template = new $template($size);
		
		return $report;
	}
	
	public function display()
	{
		header('Content-type: application/pdf');
		
		echo $this->_template->render(); exit;
	}
	
	public function getTemplate()
	{
		return $this->_template;
	}
	
	public function setTemplate($template)
	{
		$this->_template = $template;
	
		return $this;
	}
}

function drawRectangle($page, $coord, $params) {
    
    $page->drawRectangle(15, $coord[1] + $params[0], $page->getWidth() - 15, $coord[1] - $params[1], Zend_Pdf_Page::SHAPE_DRAW_STROKE);
}

