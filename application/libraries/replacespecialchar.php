<?php
      class replacespecialchar 
    {
        protected $_CI;
        
        function __construct()
        {
            $this->_CI =& get_instance();
       
        }
        
        public function replaceSpecialCharOrMaster($array)
        {
              $trans = array("&amp;" => "&",'&Ntilde;'=>'N',"&ntilde;"=>'n',"?"=>'n','&quot;'=>''); 
             
              $entries = array();
           for($x=0;$x<count($array);$x++)
           {
                   $entries[$x]['or_num'] = $array[$x]['or_num'];
                   $entries[$x]['or_date'] = $array[$x]['or_date'];
                   $entries[$x]['or_prnum'] = $array[$x]['or_prnum'] ;
                   $entries[$x]['accttype'] = $array[$x]['accttype'] ;
                   $entries[$x]['collinit'] = $array[$x]['collinit']  ;
                   $entries[$x]['payee'] =  strtr(htmlentities(utf8_decode($array[$x]['payee'])),$trans); //  preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['payee'])); //htmlentities($array[$x]['payee']);   //strtr($array[$x]['payee'], $unwanted_array ); ;
                   $entries[$x]['paytype'] = $array[$x]['paytype'] ;
                   $entries[$x]['agycode'] = $array[$x]['agycode'] ;
                   $entries[$x]['clientcode'] = $array[$x]['clientcode']  ;
                   $entries[$x]['agntcode'] = $array[$x]['agntcode'];
                   $entries[$x]['or_payee'] = strtr(htmlentities(utf8_decode($array[$x]['or_payee'])),$trans); // preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['or_payee']));  // htmlentities(($array[$x]['or_payee']);  //strtr($array[$x]['or_payee'], $unwanted_array ) ;
                   $entries[$x]['or_amt'] = $array[$x]['or_amt'] ;
                   $entries[$x]['or_amtword'] = $array[$x]['or_amtword'] ;
                   $entries[$x]['baf_acct'] = $array[$x]['baf_acct'];
                   $entries[$x]['or_part'] = $array[$x]['or_part'] ;
                   $entries[$x]['or_artype'] = $array[$x]['or_artype']  ;
                   $entries[$x]['stat'] = $array[$x]['stat']  ;
                   $entries[$x]['statusdate'] = $array[$x]['statusdate'] ;
                   $entries[$x]['usern'] =  strtr(htmlentities(utf8_decode($array[$x]['usern'])),$trans); // preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['usern'])); //htmlentities($array[$x]['usern']); //strtr($array[$x]['usern'], $unwanted_array );
                   $entries[$x]['userd'] =  $array[$x]['userd'] ;
                   $entries[$x]['product'] = strtr(htmlentities(utf8_decode($array[$x]['product'])),$trans); // preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['product'])); // htmlentities($array[$x]['product']);  //strtr($array[$x]['product'], $unwanted_array ); 
                   $entries[$x]['init_mark'] = $array[$x]['init_mark'] ;
                   $entries[$x]['gls_mark'] = $array[$x]['gls_mark'] ;
                   $entries[$x]['gls_date'] = $array[$x]['gls_date'] ;
                   $entries[$x]['tota_wtax'] = $array[$x]['tota_wtax'] ;
                   $entries[$x]['or_gov'] = $array[$x]['or_gov'] ;
                   $entries[$x]['branch_code'] = $array[$x]['branch_code'] ;
                   $entries[$x]['address1'] = strtr(htmlentities(utf8_decode($array[$x]['address1'])),$trans);  //preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['address1'])); // htmlentities($array[$x]['address1']);  //strtr($array[$x]['address1'], $unwanted_array ); 
                   $entries[$x]['address2'] = strtr(htmlentities(utf8_decode($array[$x]['address2'])),$trans); //preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['address2'])); // htmlentities($array[$x]['address2']); //strtr($array[$x]['address2'], $unwanted_array ); 
                   $entries[$x]['address3'] = strtr(htmlentities(utf8_decode($array[$x]['address3'])),$trans); // preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['address3'])); //htmlentities($array[$x]['address3']); //strtr($array[$x]['address3'], $unwanted_array ); 
                   $entries[$x]['or_tin'] = $array[$x]['or_tin'];
           }
             
              return $entries;
              
        }
        
        
        
         public function replaceSpecialCharAgency($array)
        {
                 $trans = array("&amp;" => "&",'&Ntilde;'=>'N',"&ntilde;"=>'n',"?"=>'n','&quot;'=>'');                 
                       
              $entries = array();
              for($x=0;$x<count($array);$x++)
              {
                   $entries[$x]['agency_code'] = strtr(htmlentities(utf8_decode($array[$x]['agency_code'])),$trans); // preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['agency_code'])); // htmlentities($array[$x]['agency_code']); //strtr($array[$x]['agency_code'], $unwanted_array ); 
                   $entries[$x]['agency_name'] = strtr(htmlentities(utf8_decode($array[$x]['agency_name'])),$trans); // preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['agency_name'])); //  htmlentities($array[$x]['agency_name']); //strtr($array[$x]['agency_name'], $unwanted_array ); 
                   $entries[$x]['address1'] = strtr(htmlentities(utf8_decode($array[$x]['address1'])),$trans); // preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['address1'])); // htmlentities($array[$x]['address1']); //strtr($array[$x]['address1'], $unwanted_array );
                   $entries[$x]['address2'] = strtr(htmlentities(utf8_decode($array[$x]['address2'])),$trans); //preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['address2'])); // htmlentities($array[$x]['address2']); //strtr($array[$x]['address2'], $unwanted_array );
                   $entries[$x]['address3'] = strtr(htmlentities(utf8_decode($array[$x]['address3'])),$trans); //preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['address3']));  //htmlentities($array[$x]['address3']); //strtr($array[$x]['address3'], $unwanted_array );
                   $entries[$x]['tel_no1'] = $array[$x]['tel_no1'];
                   $entries[$x]['tel_no2'] = $array[$x]['tel_no2'];
                   $entries[$x]['tel_no3'] = $array[$x]['tel_no3'];
                   $entries[$x]['fax_no1'] = $array[$x]['fax_no1'];
                   $entries[$x]['fax_no2'] = $array[$x]['fax_no2'];
                   $entries[$x]['pager_no'] = $array[$x]['pager_no'];
                   $entries[$x]['pay_terms'] = $array[$x]['pay_terms'];
                   $entries[$x]['cr_limit'] = $array[$x]['cr_limit'];
                   $entries[$x]['cr_rating'] = $array[$x]['cr_rating'];
                   $entries[$x]['remarks'] = $array[$x]['remarks'];
                   $entries[$x]['beg_bal'] = $array[$x]['beg_bal'];
                   $entries[$x]['beg_code'] = $array[$x]['beg_code'];
                   $entries[$x]['beg_date'] = $array[$x]['beg_date'];
                   $entries[$x]['end_bal'] = $array[$x]['end_bal'];
                   $entries[$x]['end_code'] = $array[$x]['end_code'];
                   $entries[$x]['end_date'] = $array[$x]['end_date'];
                   $entries[$x]['status'] = $array[$x]['status'];
                   $entries[$x]['status_d'] = $array[$x]['status_d'];
                   $entries[$x]['user_n'] = $array[$x]['user_n'];
                   $entries[$x]['user_d'] = $array[$x]['user_d'];
                   $entries[$x]['salestaxno'] = $array[$x]['salestaxno'];
                 
              }
    
              return $entries;
              
        }
        
        
        public function replaceSpecialCharClient($array)
        {
             $trans = array("&amp;" => "&",'&Ntilde;'=>'N',"&ntilde;"=>'n',"?"=>'n','&quot;'=>'');    
             
              $entries = array();
              for($x=0;$x<count($array);$x++)
              {
                   $entries[$x]['agency_code'] = strtr(htmlentities(utf8_decode($array[$x]['client_code'])),$trans); // preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['client_code'])); ///strtr($array[$x]['client_code'], $unwanted_array ); 
                   $entries[$x]['agency_name'] = strtr(htmlentities(utf8_decode($array[$x]['client_name'])),$trans); // preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['client_name'])); //strtr($array[$x]['client_name'], $unwanted_array ); 
                   $entries[$x]['address1'] = strtr(htmlentities(utf8_decode($array[$x]['address1'])),$trans); //preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['address1'])); //strtr($array[$x]['address1'], $unwanted_array );
                   $entries[$x]['address2'] = strtr(htmlentities(utf8_decode($array[$x]['address2'])),$trans); //preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['address2'])); //strtr($array[$x]['address2'], $unwanted_array );
                   $entries[$x]['address3'] = strtr(htmlentities(utf8_decode($array[$x]['address3'])),$trans); //preg_replace('/[^A-Za-z0-9\-]/', ' ',strtr($array[$x]['address3'])); // strtr($array[$x]['address3'], $unwanted_array );
                   $entries[$x]['tel_no1'] = $array[$x]['tel_no1'];
                   $entries[$x]['tel_no2'] = $array[$x]['tel_no2'];
                   $entries[$x]['tel_no3'] = $array[$x]['tel_no3'];
                   $entries[$x]['fax_no1'] = $array[$x]['fax_no1'];
                   $entries[$x]['fax_no2'] = $array[$x]['fax_no2'];
                   $entries[$x]['pager_no'] = $array[$x]['pager_no'];
                   $entries[$x]['pay_terms'] = $array[$x]['pay_terms'];
                   $entries[$x]['cr_limit'] = $array[$x]['cr_limit'];
                   $entries[$x]['cr_rating'] = $array[$x]['cr_rating'];
                   $entries[$x]['remarks'] = $array[$x]['remarks'];
                   $entries[$x]['beg_bal'] = $array[$x]['beg_bal'];
                   $entries[$x]['beg_code'] = $array[$x]['beg_code'];
                   $entries[$x]['beg_date'] = $array[$x]['beg_date'];
                   $entries[$x]['end_bal'] = $array[$x]['end_bal'];
                   $entries[$x]['end_code'] = $array[$x]['end_code'];
                   $entries[$x]['end_date'] = $array[$x]['end_date'];
                   $entries[$x]['status'] = $array[$x]['status'];
                   $entries[$x]['status_d'] = $array[$x]['status_d'];
                   $entries[$x]['user_n'] = $array[$x]['user_n'];
                   $entries[$x]['user_d'] = $array[$x]['user_d'];
                   $entries[$x]['salestaxno'] = $array[$x]['salestaxno'];
                 
              }
              
              return $entries;
              
        }
        
   }   
?>
