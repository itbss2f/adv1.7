<?php
    
    class headerlib 
    {
        protected $_CI;
        
        function __construct()
        {
            
           $this->_CI =& get_instance();
           
        }
        
        public function headerconstructor($data)
        {
            
            $header = $this->headers($data['report']);
              
            $headerout = "<tr>";
            
            foreach($header as $list => $val)
            {
                    
                $headerout .= "<th style='text-align:center;font-size:10px;width:80px'>".$val."</th>";
                
            }
            
            $headerout .= "</tr>";
            
            return $headerout;
              
        }
        
        public function headers($type) 
        {
            
            $header = array();
            
            switch ($type)
            {
                case "daily_ad_report_entered_ads":
                
                    $header = array("RN #","PO #","Advertiser","Agency","AE","Size","Color","Status",
                                    "Section / Charges","Position / Remarks","Items","User","Issue Date");
                
                break;
                
                  case "daily_ad_report_edited_ads":
                
                    $header = array("RN #","PO #","Advertiser","Agency","AE","Size","Color","Status",
                                    "Section / Charges","Position / Remarks","Items","User","Issue Date");
                
                break;
                
                case "daily_ad_premium":
                
                    $header = array("RN #","PO #","Product Title","Advertiser","Agency","Ad Size","Color","Section",
                                    "Page","Section","Page","Color");
                
                break;
                
                 case "dail_ad_miscell_charges":
                
                    $header = array("RN #","PO #","Product Title","Advertiser","Agency","Ad Size","Color","Section",
                                    "Page","Section","Page","Color");
                
                 break; 
                 
                 case "list_of_dummied_ads":
                
                    $header = array("Sec #","Page #","Size","Class","Advertiser","Agency","Color","Caption",
                                    "Production Notes","Comments","Remarks");
                
                 break;
                 
                  case "dummied_ads":
                
                    $header = array("Sec #","Page #","Size","Class","Advertiser","Agency","Color","RN #",
                                    "AE","Caption","Production Notes","Comments","Remarks");
                
                 break;     
                 
                 case "undummied_ads":
                
                    $header = array("Sec #","Page #","Size","Class","Advertiser","Agency","Color","RN #",
                                    "AE","Caption","Production Notes","Comments","Remarks");
                
                 break;              
                 
                 case "blockout":
                
                    $header = array("Sec #","Page #","Size","Class","Advertiser","Agency","Color","RN #",
                                    "AE","Caption","Production Notes","Comments","Remarks");
                
                 break;    
                 
                 case "dummied_undummied_ads":
                
                    $header = array("Sec #","Page #","Size","Class","Advertiser","Agency","Color","RN #",
                                    "AE","Caption","Production Notes","Comments","Remarks");
                
                 break;
            }
            
            return $header;
            
        }
        
    }
