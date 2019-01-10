<?php
  
class Exdeal_reports extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        
      
    }
    
    public function ai_listing($data)
    {
       $string1 =  "";
       
       $string2 =  "";
       
       switch($data['radio_type'])
       {
           case "all":
           
            $string1 =  "";
           
           break;
           
           case "overapplied":
           
            $string1 =  "";
           
           break;
           
           case "unapplied":
           
             $string1 =  "";
           
           break;
       }
       
        switch($data['filter_type'])
        {
           case "agency" :
           
            $string2 = " AND (c.cmf_name BETWEEN '$data[from_select]' AND '$data[to_select]') ";
            
           break; 
           
           case "client_agency" :
           
            $string2 = " AND (d.cmf_name = '$data[from_select]' AND c.cmf_name = '$data[to_select]') ";
            
           break; 
           
           case "agency_client":
           
             $string2 = " AND (c.cmf_name = '$data[from_select]' AND d.cmf_name = '$data[to_select]') ";
           
           break; 
           
           case "direct_client":
           
             $string2 = " AND (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') ";
           
           break; 
           
           case "direct_client":
           
             $string2 = " AND ((c.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') OR (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]')) ";
           
           break;
           
           case "client_group":
           
             $string2 = "";
            
           break;
        }
        
       $sql = "  SELECT a.ao_sinum AS `AI No.`,
                   DATE(a.ao_sidate) AS `AI Date`,
                   SUBSTR(c.cmf_name,1,18) AS `Agency`,
                   SUBSTR(d.cmf_name,1,18) AS `Advertiser`,
                   e.adtype_code AS `Ad Type`,
                   '1.00' AS `Total Billing`,
                   a.ao_amt AS `Amount Due`,
                   '0.00' AS `Amount Paid`,
                   a.ao_wtaxpercent AS `Percent`,
                   a.ao_exdealamt AS `Exdeal Amount`,
                   a.ao_dcnum AS `CM No.`,
                   DATE(a.ao_dcdate) AS `CM Date`,
                   a.ao_dcamt  AS  `Total CM Amt`,
                   '1.00' AS `Exdeal Bal`,
                   SUBSTR(a.ao_billing_remarks,1,18) AS `Remarks`,
                   CASE 
                    WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1)
                    THEN (SELECT SUM('1.00') FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') )
                    ELSE ''
                   END AS `Total Billing x||x Grand Total`,
                   
                  CASE 
                      WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1)
                      THEN (SELECT SUM(ao_amt)FROM ao_p_tm WHERE(ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ) 
                      ELSE ' ' 
                  END AS `Amount Due x||x Grand Total`,
                  
                  CASE 
                      WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_issuefrom BETWEEN  '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1)
                      THEN (SELECT SUM('1.00') FROM ao_p_tm WHERE (ao_issuefrom BETWEEN  '$data[from_date]' AND '$data[to_date]'))
                      ELSE ''
                  END AS `Amount Paid x||x Grand Total`,
                  CASE 
                     WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1)
                     THEN (SELECT SUM(ao_wtaxpercent) FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') )
                     ELSE ''
                  END AS `Percent x||x Grand Total`,
                 CASE 
                    WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1)
                    THEN (SELECT SUM(ao_exdealamt) FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') )
                    ELSE ''
                END AS `Exdeal Amount x||x Grand Total`,
                CASE 
                    WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1)
                    THEN (SELECT SUM(ao_dcamt) FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') )
                    ELSE ''
                END AS `Total CM Amt x||x Grand Total`,
                CASE 
                    WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1)
                    THEN (SELECT SUM('1.00') FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') )
                    ELSE ''
                END AS `Exdeal Bal x||x Grand Total` 
                         

            FROM ao_p_tm AS a
            INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
            LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf
            LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype
            WHERE (a.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')  

            ";
       
      $sql .= $string1;  
      
      $sql .= $string2;  

      $sql .= " ORDER BY a.id ASC ";
       
      return $this->db->query($sql)->result_array();
       
/*        $sql = "SELECT a.ao_sinum AS `AI No.`,
                   DATE(a.ao_sidate) AS `AI Date`,
                   SUBSTR(c.cmf_name,1,15) AS `Agency`,
                   SUBSTR(d.cmf_name,1,15) AS `Advertiser`,
                   e.adtype_code AS `Ad Type`,
                   '1.00' AS `Total Billing`,
                   a.ao_amt AS `Amount Due`,
                   '0.00' AS `Amount Paid`,
                   a.ao_wtaxpercent AS `Percent`,
                   a.ao_exdealamt AS `Exdeal Amount`,
                   a.ao_dcnum AS `CM No.`,
                   a.ao_dcdate AS `CM Date`,
                   a.ao_dcamt  AS  `Total CM Amt`,
                   '1.00' AS `Exdeal Bal`,
                   a.ao_billing_remarks AS `Remarks`
                         

            FROM ao_p_tm AS a
            INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
            LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf
            LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype

            WHERE (a.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') 
       --    AND a.ao_exdealstatus = '1'
       --    AND a.ao_exdealamt > '0' AND (a.ao_sinum != 0 AND TRIM(a.ao_sinum) != '' AND a.ao_sinum IS NOT NULL) " ;  ORDER BY a.ao_sidate ASC */
    }
    
    public function ai_listing_query()
    {
       $sql = "L++ SELECT a.ao_sinum AS `AI No.||60||center||no||no||no`,
                   DATE(a.ao_sidate) AS `AI Date||60||center||no||no||no`,
                   SUBSTR(c.cmf_name,1,15) AS `Agency||130||left||no||no||no`,
                   SUBSTR(d.cmf_name,1,15) AS `Advertiser||130||left||no||no||no`,
                   e.adtype_code AS `Ad Type||60||center||no||no||no`,
                   '0.00' AS `Total Billing||80||right||yes||yes||no`,
                   a.ao_amt AS `Amount Due||80||right||yes||yes||no`,
                   '0.00' AS `Amount Paid||80||right||yes||yes||no`,
                   a.ao_wtaxpercent AS `Percent||80||right||no||yes||no`,
                   a.ao_exdealamt AS `Exdeal Amount||100||right||yes||yes||no`,
                   a.ao_dcnum AS `CM No.||60||center||no||no||no`,
                   a.ao_dcdate AS `CM Date||60||center||no||no||no`,
                   a.ao_dcamt  AS  `Total CM Amt||100||right||yes||yes||no`,
                   '0.00' AS `Exdeal Bal||80||right||yes||yes||no`,
                   a.ao_billing_remarks AS `Remarks||130||left||no||no||no`
                         

            FROM ao_p_tm AS a
            INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
            LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf
            LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype
            
            LIMIT 1 ";
       
       return $sql;
    }
    
    public function cm_listing($data)
    {
        
       $string1 =  "";
       
       $string2 =  "";
       
       switch($data['radio_type'])
       {
           case "all":
           
            $string1 =  "";
           
           break;
           
           case "overapplied":
           
            $string1 =  "";
           
           break;
           
           case "unapplied":
           
             $string1 =  "";
           
           break;
       }
       
        switch($data['filter_type'])
        {
           case "agency" :
           
            $string2 = " AND (c.cmf_name BETWEEN '$data[from_select]' AND '$data[to_select]') ";
            
           break; 
           
           case "client_agency" :
           
            $string2 = " AND (d.cmf_name = '$data[from_select]' AND c.cmf_name = '$data[to_select]') ";
            
           break; 
           
           case "agency_client":
           
             $string2 = " AND (c.cmf_name = '$data[from_select]' AND d.cmf_name = '$data[to_select]') ";
           
           break; 
           
           case "direct_client":
           
             $string2 = " AND (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') ";
           
           break; 
           
           case "direct_client":
           
             $string2 = " AND ((c.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') OR (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]')) ";
           
           break;
           
           case "client_group":
           
             $string2 = "";
            
           break;
        } 
        
       $sql = "  SELECT a.ao_dcnum AS `CM No.`,
                      DATE(a.ao_dcdate) AS `CM Date`,
                      SUBSTR(c.cmf_name,1,20) AS `Agency Name`,
                      SUBSTR(d.cmf_name,1,20) AS `Client Name`,
                      a.ao_sinum as `AI No.`,
                      DATE(a.ao_sidate) AS `AI Date`,
                      a.ao_siamt as `AI Amount`,
                      a.ao_exdealamt `Exdeal Amt`,
                      a.ao_dcamt `DC Amount`,
                      SUBSTR(a.ao_exdealpart,1,20) `Remarks`,
                   CASE 
                          WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                          THEN (SELECT SUM('1.00') FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') )
                          ELSE ''
                    END AS `AI Amount x||x Grand Total`,
                    CASE 
                          WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                          THEN (SELECT SUM('1.00') FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') )
                          ELSE ''
                    END AS `Exdeal Amt x||x Grand Total`,
                    CASE 
                          WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                          THEN (SELECT SUM('1.00') FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') )
                          ELSE ''
                    END AS `DC Amount x||x Grand Total`        
                     
                     FROM ao_p_tm AS a
                     INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                     LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                     LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf 
                     -- LEFT OUTER JOIN dc_m_tm AS f ON f.dc_ainum = a.ao_sinum

                     WHERE (a.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')          
                      AND a.ao_exdealstatus = '1'      //  a.ao_sidate
                 --    AND f.dc_type = 'C' ";
        
        $sql .= $string1;
                     
        $sql .= $string2;             
                     
        $sql .= " ORDER BY a.ao_sidate ASC   ";
       
       return $this->db->query($sql)->result_array();
    }
    
    public function cm_listing_query()
    {
             $sql = "P++SELECT '' AS `CM No.||50||center||no||no||no`,
                      '' AS `CM Date||50||center||no||no||no`,
                      '' AS `Agency Name||110||left||no||no||no`,
                      '' AS `Client Name||110||left||no||no||no`,
                      '' as `AI No.||60||center||no||no||no`,
                      '' AS `AI Date||50||center||no||no||no`,
                      '' as `AI Amount||80||right||yes||yes||no`,
                      '' `Exdeal Amt||80||right||yes||yes||no`,
                      '' `DC Amount||80||right||yes||yes||no`,
                      '' `Remarks||110||left||no||no||no`          
                     
                     FROM ao_p_tm AS a
                     INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                     LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                     LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf 
                     -- LEFT OUTER JOIN dc_m_tm AS f ON f.dc_ainum = a.ao_sinum 
                     LIMIT 1 ";
           
           return $sql;               
                       
    }
    
    public function ai_sched($data)
    {
       $string1 =  "";
       
       $string2 =  "";
       
       switch($data['radio_type'])
       {
           case "all":
           
            $string1 =  "";
           
           break;
           
           case "overapplied":
           
            $string1 =  "";
           
           break;
           
           case "unapplied":
           
             $string1 =  "";
           
           break;
       }
       
        switch($data['filter_type'])
        {
           case "agency" :
           
            $string2 = " AND (c.cmf_name BETWEEN '$data[from_select]' AND '$data[to_select]') ";
            
           break; 
           
           case "client_agency" :
           
            $string2 = " AND (d.cmf_name = '$data[from_select]' AND c.cmf_name = '$data[to_select]') ";
            
           break; 
           
           case "agency_client":
           
             $string2 = " AND (c.cmf_name = '$data[from_select]' AND d.cmf_name = '$data[to_select]') ";
           
           break; 
           
           case "direct_client":
           
             $string2 = " AND (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') ";
           
           break; 
           
           case "direct_client":
           
             $string2 = " AND ((c.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') OR (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]')) ";
           
           break;
           
           case "client_group":
           
             $string2 = "";
            
           break;
        } 
        
        
       $sql = "SELECT  c.cmf_name AS `Agency`,
                       b.ao_payee AS `Advertiser`,
                       a.ao_sinum AS `AI No.`,
                       DATE(a.ao_sidate) AS `AI Date`,
                       e.adtype_code AS `Ad Type`,
                       '0.00' AS `Total Billing`,
                       a.ao_amt AS `Amount Due`,
                       a.ao_wtaxpercent AS `Percent`,
                       a.ao_exdealamt AS `Exdeal Amount`,
                       '0.00' AS `Amount Paid`,
                       a.ao_billing_remarks AS `Remarks`,
                       CASE
                       WHEN a.id = (SELECT id FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                       THEN (SELECT SUM(1.00) FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                       ELSE ''
                       END AS `Total Billing x||x Grand Total`,
                       CASE
                       WHEN a.id = (SELECT id FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                       THEN (SELECT SUM(1.00) FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                       ELSE ''
                       END AS `Amount Due x||x Grand Total`,
                       CASE
                       WHEN a.id = (SELECT id FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                       THEN (SELECT SUM(1.00) FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                       ELSE ''
                       END AS `Percent x||x Grand Total`,
                       CASE
                       WHEN a.id = (SELECT id FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                       THEN (SELECT SUM(1.00) FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                       ELSE ''
                       END AS `Exdeal Amount x||x Grand Total`,
                                  CASE
                       WHEN a.id = (SELECT id FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                       THEN (SELECT SUM(1.00) FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                       ELSE ''
                       END AS `Amount Paid x||x Grand Total` 
                             
                     
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf 
                LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype

                WHERE (a.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') 
               --  AND a.ao_exdealstatus = '1'  a.ao_sidate ";
       
        $sql .= $string1;
                     
        $sql .= $string2;             
       
       $sql .=" ORDER a.id ASC, BY c.cmf_name ASC , b.ao_payee ASC  WITH ROLLUP   ";
       
       return $this->db->query($sql)->result_array();
    }
    
    public function ai_sched_query()
    {
        $sql = "L++SELECT '' AS `Agency||200||left||no||no||no`,
                       '' AS `Advertiser||200||left||no||no||no`,
                       '' AS `AI No.||60||center||no||no||no`,
                       '' AS `AI Date||60||center||no||no||no`,
                       '' AS `Ad Type||60||center||no||no||no`,
                       '' AS `Total Billing||100||right||yes||yes||no`,
                       '' AS `Amount Due||100||right||yes||yes||no`,
                       '' AS `Percent||100||right||yes||yes||no`,
                       '' AS `Exdeal Amount||100||right||yes||yes||no`,
                       '' AS `Amount Paid||100||right||yes||yes||no`,
                       '' AS `Remarks||210||left||no||no||no` 
                             
                     
                FROM ao_p_tm  
                LIMIT 1 ";
        
        return $sql;
    }
    
     public function cm_sched($data)
    {
       $string1 =  "";
       
       $string2 =  "";
       
       switch($data['radio_type'])
       {
           case "all":
           
            $string1 =  "";
           
           break;
           
           case "overapplied":
           
            $string1 =  "";
           
           break;
           
           case "unapplied":
           
             $string1 =  "";
           
           break;
       }
       
        switch($data['filter_type'])
        {
           case "agency" :
           
            $string2 = " AND (c.cmf_name BETWEEN '$data[from_select]' AND '$data[to_select]') ";
            
           break; 
           
           case "client_agency" :
           
            $string2 = " AND (d.cmf_name = '$data[from_select]' AND c.cmf_name = '$data[to_select]') ";
            
           break; 
           
           case "agency_client":
           
             $string2 = " AND (c.cmf_name = '$data[from_select]' AND d.cmf_name = '$data[to_select]') ";
           
           break; 
           
           case "direct_client":
           
             $string2 = " AND (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') ";
           
           break; 
           
           case "direct_client":
           
             $string2 = " AND ((c.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') OR (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]')) ";
           
           break;
           
           case "client_group":
           
             $string2 = "";
            
           break;
        }  
        
        
       $sql = "SELECT  SUBSTR(c.cmf_name,1,15) AS `Agency`,
                       SUBSTR(d.cmf_name,1,15) AS `Client`,
                       a.ao_dcnum AS `CM No.`,
                       DATE(a.ao_dcdate) AS `CM Date`,
                       a.ao_sinum AS `AI No.`,
                       DATE(a.ao_sidate) AS `AI Date`,
                       a.ao_siamt AS `AI Amount`,
                       a.ao_exdealamt AS `Exdeal Amount`,
                       a.ao_dcamt AS `CM Amount`,
                       CASE
                       WHEN a.id = (SELECT id FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                       THEN (SELECT SUM(1.00) FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                       ELSE ''
                       END AS `AI Amount x||x Grand Total`,
                       CASE
                       WHEN a.id = (SELECT id FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                       THEN (SELECT SUM(1.00) FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                       ELSE ''
                       END AS `Exdeal Amount x||x Grand Total`,
                       CASE
                       WHEN a.id = (SELECT id FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                       THEN (SELECT SUM(1.00) FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                       ELSE ''
                       END AS `CM Amount x||x Grand Total`
         
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf 
                LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype
                -- LEFT OUTER JOIN dc_m_tm AS f ON f.dc_ainum = a.ao_sinum
             
                WHERE (a.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')
                
          --      AND f.dc_type = 'C' 
           --     AND a.ao_exdealstatus = '1' a.ao_sidate ";  
       
       $sql .= $string1;
                     
       $sql .= $string2;            
                
       $sql .= "  ORDER a.id ASC ,a.ao_sinum ASC  " ;
       
       return $this->db->query($sql)->result_array();
    }
    
    public function cm_sched_query()
    {
       $sql = "P++SELECT '' AS `Agency||130||left||no||no||no`,
               '' AS `Client||130||left||no||no||no`,
               '' AS `CM No.||50||center||no||no||no`,
               '' AS `CM Date||50||center||no||no||no`,
               '' AS `AI No.||50||center||no||no||no`,
               '' AS `AI Date||50||center||no||no||no`,
               '' AS `AI Amount||100||right||yes||yes||no`,
               '' AS `Exdeal Amount||100||right||yes||yes||no`,
               '' AS `CM Amount||100||right||yes||yes||no`
        
        FROM ao_p_tm AS a
        INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
        LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
        LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf 
        LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype
        -- LEFT OUTER JOIN dc_m_tm AS f ON f.dc_ainum = a.ao_sinum LIMIT 1 "; 
        
       return $sql;  
    }
    
    public function cm_application($data)
    {
       
       $string1 =  "";
       
       $string2 =  "";
       
       switch($data['radio_type'])
       {
           case "all":
           
            $string1 =  "";
           
           break;
           
           case "overapplied":
           
            $string1 =  "";
           
           break;
           
           case "unapplied":
           
             $string1 =  "";
           
           break;
       }
       
        switch($data['filter_type'])
        {
           case "agency" :
           
            $string2 = " AND (c.cmf_name BETWEEN '$data[from_select]' AND '$data[to_select]') ";
            
           break; 
           
           case "client_agency" :
           
            $string2 = " AND (d.cmf_name = '$data[from_select]' AND c.cmf_name = '$data[to_select]') ";
            
           break; 
           
           case "agency_client":
           
             $string2 = " AND (c.cmf_name = '$data[from_select]' AND d.cmf_name = '$data[to_select]') ";
           
           break; 
           
           case "direct_client":
           
             $string2 = " AND (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') ";
           
           break; 
           
           case "direct_client":
           
             $string2 = " AND ((c.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') OR (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]')) ";
           
           break;
           
           case "client_group":
           
             $string2 = "";
            
           break;
        } 
        
       $sql = "SELECT 
                       a.ao_dcnum AS `CM No.`,
                       DATE(a.ao_date) AS `CM Date`,
                       SUBSTR(c.cmf_name,1,20) AS `Agency`,
                       SUBSTR(d.cmf_name,1,20) AS `Client`,
                       a.ao_sinum AS `AI No.`,
                       DATE(a.ao_sidate) AS `AI Date`,
                       a.ao_siamt AS `AI Amount`,
                       a.ao_exdealamt AS `Exdeal Amount`,
                       a.ao_dcamt AS `CM Amount`,                 
                       CASE
                       WHEN a.id = (SELECT id FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                       THEN (SELECT SUM(1.00) FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                       ELSE ''
                       END AS `AI Amount x||x Grand Total`,
                       CASE
                       WHEN a.id = (SELECT id FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                       THEN (SELECT SUM(1.00) FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                       ELSE ''
                       END AS `Exdeal Amount x||x Grand Total`,
                       CASE
                       WHEN a.id = (SELECT id FROM ao_p_tm WHERE  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                       THEN (SELECT SUM(1.00) FROM ao_p_tm WHERE (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                       ELSE ''
                       END AS `CM Amount x||x Grand Total`
                       
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf 
                LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype
                -- LEFT OUTER JOIN dc_m_tm AS f ON f.dc_ainum = a.ao_sinum
            
                WHERE (a.ao_issuefrom  BETWEEN '$data[from_date]' AND '$data[to_date]') 
              --  AND f.dc_type = 'C'   a.ao_sidate
                 
                ";
                
       $sql .= $string1;
                     
       $sql .= $string2;           
       
       $sql .= " ORDER BY  a.id ASC,a.ao_dcnum ASC, a.ao_dcdate ASC ";
       
       return $this->db->query($sql)->result_array();
    }
    
    public function  cm_application_query()
    {
        $sql =  "P++SELECT 
                       '' AS `CM No.||60||center||no||no||no`,
                       '' AS `CM Date||60||center||no||no||no`,
                       '' AS `Agency||130||left||no||no||no`,
                       '' AS `Client||130||left||no||no||no`,
                       '' AS `AI No.||60||center||no||no||no`,
                       '' AS `AI Date||60||center||no||no||no`,
                       '' AS `AI Amount||80||right||yes||yes||no`,
                       '' AS `Exdeal Amount||100||right||yes||yes||no`,
                       '' AS `CM Amount||80||right||yes||yes||no`
                       
                FROM ao_p_tm 
                LIMIT 1";
         
        return $sql;
    }
    
    /*public function contract_listing($data)
    {
        $sql = "SELECT 
                   a.contract_no AS `Contract No.`,
                   a.contract_date AS `Contract Date`,
                   b.group_name AS `Name of Client`,
                   a.advertising_agency AS `Advertising Agency`,
                   a.amount AS `Amount`,
                   CASE a.contract_type
                   WHEN 'B' THEN 'Billable'
                   WHEN 'N' THEN 'Non-Billable'
                   END AS `Type`,
                   CONCAT(a.barter_ratio,' %') AS `Barter Ratio`,
                   CASE a.barter_request
                        WHEN '1'
                        THEN 'CLIENT'
                        WHEN '2'
                        THEN 'PDI'
                   END AS `Requested By`,
                   a.remarks AS `Remarks`,
                  CASE a.status
                        WHEN '1'
                        THEN 'Pending'
                        WHEN '2'
                        THEN 'Approved'
                        WHEN '3'
                        THEN 'Disapproved'
                   END AS `Status`
                   
            FROM exdeal_contract AS a
            LEFT OUTER JOIN exdeal_advertisergroup AS b ON b.id = a.advertiser_group_id
            WHERE (a.contract_date  BETWEEN '$data[from_date]' AND '$data[to_date]') AND a.is_deleted = '0'
            ORDER BY a.contract_date ";
        
        return $this->db->query($sql)->result_array();
    }  */
    
    public function  contract_listing_query()
    {
         $sql = "L++SELECT   
                   a.contract_no AS `Contract No.||100||center||no||no||no`,
                   a.contract_date AS `Contract Date||100||center||no||no||no`,
                   b.group_name AS `Name of Client||200||left||no||no||no`,
                   a.advertising_agency AS `Advertising Agency||200||left||no||no||no`,
                   a.amount AS `Amount||100||right||yes||no||no`,
                   a.contract_type AS `Type||100||center||no||no||no`,
                   CONCAT(a.barter_ratio,'%') AS `Barter Ratio||100||center||no||no||no`,
                   a.barter_request  AS `Requested By||100||center||no||no||no`,
                   a.remarks AS `Remarks||200||left||no||no||no`,
                   a.status  AS `Status||90||center||no||no||no`
                   
            FROM exdeal_contract AS a
            LEFT OUTER JOIN exdeal_advertisergroup AS b ON b.id = a.advertiser_group_id
            ";
        
        return $sql;    
            
    }
    
    public function no_charge_listing($data)
    {
        $sql = "SELECT a.id,
                   DATE(a.ao_issuefrom) AS `Issue Date`,
                   SUBSTR(a.ao_part_billing,1,15) AS `Product`,
                   CONCAT(a.ao_width,' x ',a.ao_length) AS `Size`,
                   SUBSTR(b.ao_payee,1,20) AS `Advertiser`,
                   SUBSTR(c.cmf_name,1,20) AS `Agency`,
                   g.color_code AS `Color`,
                   SUBSTR(b.ao_ref ,1,20)AS `RN No.`,
                   a.ao_totalsize AS `CCM`,
                   e.adtype_code AS `Category`,
                   h.aosubtype_code AS `Type`,
                   '0.00' AS `Production Cost`, -- old field production_exdeal, No equivalent field on the new table
                   a.ao_totalcharge AS `Insertion Cost`,
                   CONCAT(SUBSTR(TRIM(b.ao_part_billing),1,10),' ',SUBSTR(TRIM(b.ao_part_records),1,10)) AS `Remarks`,
                   CASE
                   WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_paginated_status IS NOT NULL AND TRIM(ao_paginated_status) != '' AND ao_paginated_status != 0) AND  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                   THEN (SELECT SUM(ao_totalsize) FROM ao_p_tm WHERE (ao_paginated_status IS NOT NULL AND TRIM(ao_paginated_status) != '' AND ao_paginated_status != 0) AND (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                   ELSE ''
                   END AS `CCM x||x Grand Total`,
                   CASE
                   WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_paginated_status IS NOT NULL AND TRIM(ao_paginated_status) != '' AND ao_paginated_status != 0) AND  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                   THEN (SELECT SUM('1.00') FROM ao_p_tm WHERE (ao_paginated_status IS NOT NULL AND TRIM(ao_paginated_status) != '' AND ao_paginated_status != 0) AND (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                   ELSE ''
                   END AS `Production Cost x||x Grand Total`,
                   CASE
                   WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_paginated_status IS NOT NULL AND TRIM(ao_paginated_status) != '' AND ao_paginated_status != 0) AND  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
                   THEN (SELECT SUM('1.00') FROM ao_p_tm WHERE (ao_paginated_status IS NOT NULL AND TRIM(ao_paginated_status) != '' AND ao_paginated_status != 0) AND (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
                   ELSE ''
                   END AS `Insertion Cost x||x Grand Total`




            FROM ao_p_tm AS a
            INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
            LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf 
            LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype
          --  -- LEFT OUTER JOIN dc_m_tm AS f ON f.dc_ainum = a.ao_sinum
            LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
            LEFT OUTER JOIN misaosubtype AS h ON h.id = a.ao_subtype

            WHERE (a.ao_paginated_status IS NOT NULL AND TRIM(a.ao_paginated_status) != '' AND a.ao_paginated_status != 0) AND (a.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')
            -- AND b.ao_paytype = '6'
            -- AND a.ao_exdealstatus = '1'  a.ao_sidate
            -- AND

            ORDER BY a.id ASC ";     
        
        return $this->db->query($sql)->result_array();
    }

    public function no_charge_listing_query()
    {
               $sql = "L++SELECT 
                              '' AS `Issue Date||80||center||no||no||no`,
                              '' AS `Product||140||center||no||no||no`,
                              '' AS `Size||70||center||no||no||no`,
                              '' AS `Advertiser||140||left||no||no||no`,
                              '' AS `Agency||140||left||no||no||no`,
                              '' AS `Color||60||center||no||no||no`,
                              '' AS `RN No.||80||left||no||no||no`,
                              '' AS `CCM||80||right||yes||yes||no`,
                              '' AS `Category||60||center||no||no||no`,
                              '' AS `Type||60||center||no||no||no`,
                              '' AS `Production Cost||100||right||yes||yes||no`,
                              '' AS `Insertion Cost||100||right||yes||yes||no`,
                              '' AS `Remarks||140||left||no||no||no`
               FROM ao_p_tm  LIMIT 1 ";
            
               return $sql;      
    } 
    
    public function no_charge_listing_schedule($data)
    {
        $sql = "SELECT a.id,
               DATE(a.ao_issuefrom) AS `Issue Date`,
               a.ao_part_billing AS `Product`,
               CONCAT(a.ao_width,' x ',a.ao_length) AS `Size`,
               b.ao_payee AS `Advertiser`,
               c.cmf_name AS `Agency`,
               g.color_code AS `Color`,
               b.ao_ref AS `RN No.`,
               a.ao_totalsize AS `CCM`,
               e.adtype_code AS `Category`,
               h.aosubtype_code AS `Type`,
               '0.00' AS `Production Cost`, -- old field production_exdeal, No equivalent field on the new table
               a.ao_totalcharge AS `Insertion Cost`,
               CONCAT(SUBSTR(TRIM(b.ao_part_billing),1,15),' ',SUBSTR(TRIM(b.ao_part_records),1,15)) AS `Remarks`,
               
               CASE
               WHEN a.id = (SELECT sa.id FROM ao_p_tm AS sa 
                    INNER JOIN ao_m_tm AS sb ON sb.ao_num = sa.ao_num
                    WHERE sb.ao_payee = b.ao_payee
                    AND (sa.ao_paginated_status IS NOT NULL AND TRIM(sa.ao_paginated_status) != '' AND sa.ao_paginated_status != 0) AND  (sa.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')
                    ORDER BY sa.id DESC LIMIT 1 )
                
            
               THEN (SELECT SUM(sa.ao_totalsize) 
                FROM ao_p_tm AS sa
                INNER JOIN ao_m_tm AS sb ON sb.ao_num = sa.ao_num
                WHERE sb.ao_payee = b.ao_payee
                AND (sa.ao_paginated_status IS NOT NULL AND TRIM(sa.ao_paginated_status) != '' AND sa.ao_paginated_status != 0) AND  (sa.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')
                AND sa.id <= a.id )
               ELSE ''
               END AS `CCM x||x Sub Total`,
               
               CASE
               WHEN a.id = (SELECT sa.id FROM ao_p_tm AS sa 
                    INNER JOIN ao_m_tm AS sb ON sb.ao_num = sa.ao_num
                    WHERE sb.ao_payee = b.ao_payee
                    AND (sa.ao_paginated_status IS NOT NULL AND TRIM(sa.ao_paginated_status) != '' AND sa.ao_paginated_status != 0) AND  (sa.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')
                    ORDER BY sa.id DESC LIMIT 1 )
                
            
               THEN (SELECT SUM('1.00') 
                FROM ao_p_tm AS sa
                INNER JOIN ao_m_tm AS sb ON sb.ao_num = sa.ao_num
                WHERE sb.ao_payee = b.ao_payee
                AND (sa.ao_paginated_status IS NOT NULL AND TRIM(sa.ao_paginated_status) != '' AND sa.ao_paginated_status != 0) AND  (sa.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')
                AND sa.id <= a.id )
               ELSE ''
               END AS `Production Cost x||x Sub Total`,
                  CASE
               WHEN a.id = (SELECT sa.id FROM ao_p_tm AS sa 
                    INNER JOIN ao_m_tm AS sb ON sb.ao_num = sa.ao_num
                    WHERE sb.ao_payee = b.ao_payee
                    AND (sa.ao_paginated_status IS NOT NULL AND TRIM(sa.ao_paginated_status) != '' AND sa.ao_paginated_status != 0) AND  (sa.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')
                    ORDER BY sa.id DESC LIMIT 1 )
                
            
               THEN (SELECT SUM('1.00') 
                FROM ao_p_tm AS sa
                INNER JOIN ao_m_tm AS sb ON sb.ao_num = sa.ao_num
                WHERE sb.ao_payee = b.ao_payee
                AND (sa.ao_paginated_status IS NOT NULL AND TRIM(sa.ao_paginated_status) != '' AND sa.ao_paginated_status != 0) AND  (sa.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')
                AND sa.id <= a.id )
               ELSE ''
               END AS `Insertion Cost x||x Sub Total`,
               
              CASE
               WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_paginated_status IS NOT NULL AND TRIM(ao_paginated_status) != '' AND ao_paginated_status != 0) AND  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
               THEN (SELECT SUM(ao_totalsize) FROM ao_p_tm WHERE (ao_paginated_status IS NOT NULL AND TRIM(ao_paginated_status) != '' AND ao_paginated_status != 0) AND (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
               ELSE ''
               END AS `CCM x||x Grand Total`,
               CASE
               WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_paginated_status IS NOT NULL AND TRIM(ao_paginated_status) != '' AND ao_paginated_status != 0) AND  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
               THEN (SELECT SUM('1.00') FROM ao_p_tm WHERE (ao_paginated_status IS NOT NULL AND TRIM(ao_paginated_status) != '' AND ao_paginated_status != 0) AND (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
               ELSE ''
               END AS `Production Cost x||x Grand Total`,
               CASE
               WHEN a.id = (SELECT id FROM ao_p_tm WHERE (ao_paginated_status IS NOT NULL AND TRIM(ao_paginated_status) != '' AND ao_paginated_status != 0) AND  (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ORDER BY id DESC LIMIT 1 )
               THEN (SELECT SUM('1.00') FROM ao_p_tm WHERE (ao_paginated_status IS NOT NULL AND TRIM(ao_paginated_status) != '' AND ao_paginated_status != 0) AND (ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]'))
               ELSE ''
             END AS `Insertion Cost x||x Grand Total` 




            FROM ao_p_tm AS a
            INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
            LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf 
            LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype
            -- LEFT OUTER JOIN dc_m_tm AS f ON f.dc_ainum = a.ao_sinum
            LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
            LEFT OUTER JOIN misaosubtype AS h ON h.id = a.ao_subtype

            WHERE (a.ao_paginated_status IS NOT NULL AND TRIM(a.ao_paginated_status) != '' AND a.ao_paginated_status != 0)
            AND (a.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')
            -- AND b.ao_paytype = '6'
            -- AND a.ao_exdealstatus = '1'

            ORDER BY b.ao_payee ASC,a.id ASC ";
        
        return $this->db->query($sql)->result_array();
    }
    
    public function no_charge_listing_schedule_query()
    {
               $sql = "L++SELECT '' AS `Issue Date||80||center||no||no||no`,
                              '' AS `Product||150||center||no||no||no`,
                              '' AS `Size||70||center||no||no||no`,
                              '' AS `Advertiser||150||left||no||no||no`,
                              '' AS `Agency||150||left||no||no||no`,
                              '' AS `Color||60||center||no||no||no`,
                              '' AS `RN No.||80||center||no||no||no`,
                              '' AS `CCM||80||right||yes||yes||yes`,
                              '' AS `Category||60||center||no||no||no`,
                              '' AS `Type||60||center||no||no||no`,
                              '' AS `Production Cost||100||right||yes||yes||yes`,
                              '' AS `Insertion Cost||100||right||yes||yes||yes`,
                              '' AS `Remarks||150||left||no||no||no`
               FROM ao_p_tm  LIMIT 1 ";
            
               return $sql;   
    } 
    
    public function subsidiary_ledger_no_charge($data)
    {
        $sql = "SELECT d.cmf_code AS `Client Code`,
                       d.cmf_name AS `Client Name`,
                       c.cmf_name AS `Agency Name`,
                       c.cmf_code AS `Agency Code`,
                       
                       DATE(a.ao_issuefrom) AS `Issue Date`,
                       a.ao_part_billing AS `Product`,
                       CONCAT(a.ao_width,' x ',a.ao_length) AS `Size`,
                       c.cmf_code AS `Agency`,
                       e.color_code AS `Color`,
                       b.ao_ref AS `RN No.`,
                       a.ao_totalsize AS `CCM`,
                       f.adtype_code AS `Category`,
                       g.aosubtype_code  AS `Type`,
                       '0.00' AS `Production Cost`, -- old field production_exdeal, No equivalent field on the new table
                       a.ao_totalcharge AS `Insertion Cost`,
                       CONCAT(SUBSTR(TRIM(b.ao_part_billing),1,15),' ',SUBSTR(TRIM(b.ao_part_records),1,15)) AS `Remarks`
                       

                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf 
                LEFT OUTER JOIN miscolor AS e ON e.id = a.ao_color
                LEFT OUTER JOIN misadtype AS f ON f.id = b.ao_adtype 
                LEFT OUTER JOIN misaosubtype AS g ON g.id = a.ao_subtype
                WHERE 
                      (a.ao_paginated_status IS NOT NULL AND TRIM(a.ao_paginated_status) != '' AND a.ao_paginated_status != 0)
                       AND (a.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')
                       -- AND b.ao_paytype = '6'
                       -- AND a.ao_exdealstatus = '1' ";
        
        return $this->db->query($sql)->result_array();
    }
    
    public function  subsidiary_ledger_no_charge_query()
    {
        $sql = "L++SELECT 
                       '' AS `Issue Date||80||center||no||no||no`,
                       '' AS `Product||150||left||no||no||no`,
                       '' AS `Size||80||center||no||no||no`,
                       '' AS `Agency||150||left||no||no||no`,
                       '' AS `Color||80||center||no||no||no`,
                       '' AS `RN No.||100||left||no||no||no`,
                       '' AS `CCM||80||center||no||no||no`,
                       '' AS `Category||80||center||no||no||no`,
                       '' AS `Type||80||center||no||no||no`,
                       '' AS `Production Cost||80||center||yes||yes||yes`, -- old field production_exdeal, No equivalent field on the new table
                       '' AS `Insertion Cost||80||center||yes||yes||yes`,
                       '' AS `Remarks||150||center||no||no||no`
                          
                FROM ao_p_tm LIMIT 1";
        
        return $sql;
    }
    
    public function contract_listing($data)
    {
        $sql = "SELECT   a.id,b.group_name,
                        a.advertising_agency,
                        a.advertiser_id,
                        b.advertiser,
                        b.credit_limit,
                        b.contact_person,
                        a.amount,
                        a.contract_no,
                        a.contract_date,
                        CASE a.contract_type
                        WHEN 'B' THEN 'Billable'
                        WHEN 'N' THEN 'Non-Billable'
                        END contract_type,
                        a.contract_date,
                        a.attachment_file,
                        a.telephone,
                        SUBSTR(a.remarks,1,70) as remarks,
                        a.barter_ratio         
                FROM exdeal_contract AS a
                LEFT OUTER JOIN exdeal_advertisergroup AS b ON b.id = a.advertiser_group_id
                WHERE a.is_deleted = '0'
                AND (a.contract_date BETWEEN '$data[from_date]' AND '$data[to_date]')
                ORDER BY a.contract_date ";
        
        return $this->db->query($sql)->result();
    }
    
     public function subsdiary_ledger($data)
    {
        
          $string2 = "";
           switch($data['filter_type'])
        {
           case "agency" :
           
            $string2 = " AND (c.cmf_name BETWEEN '$data[from_select]' AND '$data[to_select]') ";
            
           break; 
           
           case "client_agency" :
           
            $string2 = " AND (d.cmf_name = '$data[from_select]' AND c.cmf_name = '$data[to_select]') ";
            
           break; 
           
           case "agency_client":
           
             $string2 = " AND (c.cmf_name = '$data[from_select]' AND d.cmf_name = '$data[to_select]') ";
           
           break; 
              
           case "direct_client":
           
             $string2 = " AND ((f.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') OR (g.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]')) ";
           
           break;
           
           case "client_group":
           
             $string2 = "";
            
           break;
        } 
        
        
        $stmt = "SELECT a.contract_no,
                           a.contract_date,
                           a.amount AS exdeal_amount, 
                           a.barter_ratio,
                           DATE(b.ao_sidate) sidate,
                           b.ao_sinum,
                           c.ao_payee,
                           SUBSTR(c.ao_part_billing,1,40) as ao_part_billing,
                           b.ao_grossamt AS gross_amount,
                           b.ao_agycommamt AS agency_commission,
                           b.ao_vatamt AS vat_amount,
                           b.ao_amt AS net_amount,
                           b.ao_exdealamt AS exdeal_amount,
                           b.ao_exdealcash AS exdeal_cash,
                           e.dc_assignamt AS consumption

                    FROM exdeal_contract AS a
                    LEFT OUTER JOIN ao_p_tm AS b ON b.ao_exdealcontractno = a.contract_no
                    LEFT OUTER JOIN ao_m_tm AS c ON c.ao_num = b.ao_num
                    LEFT OUTER JOIN exdeal_advertisergroup AS d ON d.id = a.advertiser_group_id
                    LEFT OUTER JOIN dc_d_tm AS e ON e.dc_docitemid =  b.id 
                    LEFT OUTER JOIN miscmf AS f ON f.id = c.ao_amf
                    LEFT OUTER JOIN miscmf AS g ON g.cmf_code = c.ao_cmf 
                    WHERE a.is_deleted = '0'
                    AND (a.contract_date BETWEEN '$data[from_date]' AND '$data[to_date]')
                    AND b.ao_exdealstatus = '1'
                    AND (b.ao_sisuperceded IS NULL OR  b.ao_sisuperceded = '')   
                    $string2      
                    ORDER BY d.advertiser ASC, a.contract_date";
                    
              
       return $this->db->query($stmt)->result();
    }
    
    public function subsdiary_ledger_headgroup($data)
    {
        
          $string2 = "";
         switch($data['filter_type'])
        {
           case "agency" :
           
            $string2 = " AND (c.cmf_name BETWEEN '$data[from_select]' AND '$data[to_select]') ";
            
           break; 
           
           case "client_agency" :
           
            $string2 = " AND (d.cmf_name = '$data[from_select]' AND c.cmf_name = '$data[to_select]') ";
            
           break; 
           
           case "agency_client":
           
             $string2 = " AND (c.cmf_name = '$data[from_select]' AND d.cmf_name = '$data[to_select]') ";
           
           break; 
           
         /*  case "direct_client":
           
             $string2 = " AND (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') ";
           
           break;    */
           
           case "direct_client":
           
             $string2 = " AND ((f.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') OR (g.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]')) ";
           
           break;
           
           case "client_group":
           
             $string2 = "";
            
           break;
        } 
        
          $stmt = "SELECT 
                   a.contract_no,
                   d.group_name,
                   d.advertiser,
                   a.amount AS exdeal_contract_amount,
                   SUM(b.ao_grossamt) as total_gross,
                   a.barter_ratio,
                   a.cash_ratio,
                  (a.amount-(SUM(b.ao_amt))) AS balance,
                   SUM(e.dc_assignamt) AS consumption           
              --      (ROUND((a.barter_ratio/100)*a.amount,2)- (SUM(b.ao_exdealamt))) AS balance        

                FROM exdeal_contract AS a
                LEFT OUTER JOIN ao_p_tm AS b ON b.ao_exdealcontractno = a.contract_no
                LEFT OUTER JOIN ao_m_tm AS c ON c.ao_num = b.ao_num
                LEFT OUTER JOIN exdeal_advertisergroup AS d ON d.id = a.advertiser_group_id  
                LEFT OUTER JOIN dc_d_tm AS e ON e.dc_docitemid =  b.id 
                WHERE a.is_deleted = '0'
                AND (a.contract_date BETWEEN '$data[from_date]' AND '$data[to_date]')
                AND b.ao_exdealstatus = '1'
                AND (b.ao_sisuperceded IS NULL OR  b.ao_sisuperceded = '')   
                 $string2      
                GROUP BY d.advertiser ASC ,a.contract_date 
                ORDER BY d.advertiser ASC, a.contract_date ";
               
        return $this->db->query($stmt)->result();
    } 
    
    public function subsdiary_ledger_query()
    {
        
               $sql = "L++SELECT '' AS `Issue Date||80||center||no||no||no`,
                              '' AS `Product||150||center||no||no||no`,
                              '' AS `Size||70||center||no||no||no`,
                              '' AS `Advertiser||150||left||no||no||no`,
                              '' AS `Agency||150||left||no||no||no`,
                              '' AS `Color||60||center||no||no||no`,
                              '' AS `RN No.||80||center||no||no||no`,
                              '' AS `CCM||80||right||yes||yes||yes`,
                              '' AS `Category||60||center||no||no||no`,
                              '' AS `Type||60||center||no||no||no`,
                              '' AS `Production Cost||100||right||yes||yes||yes`,
                              '' AS `Insertion Cost||100||right||yes||yes||yes`,
                              '' AS `Remarks||150||left||no||no||no`
               FROM exdeal_contract  LIMIT 1 ";
            
               return $sql;   
    }
    
    public function payment_monitoring()
    {
        $stmt = "";
        return $this->db->query($stmt)->result();  
    }
    
    public function payment_monitoring_query()
    {
                       $sql = "L++SELECT '' AS `Issue Date||80||center||no||no||no`,
                              '' AS `Product||150||center||no||no||no`,
                              '' AS `Size||70||center||no||no||no`,
                              '' AS `Advertiser||150||left||no||no||no`,
                              '' AS `Agency||150||left||no||no||no`,
                              '' AS `Color||60||center||no||no||no`,
                              '' AS `RN No.||80||center||no||no||no`,
                              '' AS `CCM||80||right||yes||yes||yes`,
                              '' AS `Category||60||center||no||no||no`,
                              '' AS `Type||60||center||no||no||no`,
                              '' AS `Production Cost||100||right||yes||yes||yes`,
                              '' AS `Insertion Cost||100||right||yes||yes||yes`,
                              '' AS `Remarks||150||left||no||no||no`
               FROM exdeal_contract  LIMIT 1 ";
            
               return $sql;     
    }  
    
    public function exdeal_summary($data)
    {
           $stmt = "SELECT 
                   a.contract_no,
                   d.group_name,
                   d.advertiser,
                   a.amount AS exdeal_contract_amount,
                   SUM(b.ao_grossamt) as total_gross,
                   a.barter_ratio,
                   a.cash_ratio,
                  (a.amount-(SUM(b.ao_amt))) AS balance,
                   SUM(e.dc_assignamt) AS consumption           
              --      (ROUND((a.barter_ratio/100)*a.amount,2)- (SUM(b.ao_exdealamt))) AS balance        

                FROM exdeal_contract AS a
                LEFT OUTER JOIN ao_p_tm AS b ON b.ao_exdealcontractno = a.contract_no
                LEFT OUTER JOIN ao_m_tm AS c ON c.ao_num = b.ao_num
                LEFT OUTER JOIN exdeal_advertisergroup AS d ON d.id = a.advertiser_group_id  
                LEFT OUTER JOIN dc_d_tm AS e ON e.dc_docitemid =  b.id 
                WHERE a.is_deleted = '0'
                AND (a.contract_date BETWEEN '$data[from_date]' AND '$data[to_date]')
                AND b.ao_exdealstatus = '1'
                GROUP BY d.advertiser ASC ,a.contract_date 
                ORDER BY d.advertiser ASC, a.contract_date";
                   
        return $this->db->query($stmt)->result();
    }
    
    public function exdeal_summary_query()
    {
                       $sql = "L++SELECT '' AS `Issue Date||80||center||no||no||no`,
                              '' AS `Product||150||center||no||no||no`,
                              '' AS `Size||70||center||no||no||no`,
                              '' AS `Advertiser||150||left||no||no||no`,
                              '' AS `Agency||150||left||no||no||no`,
                              '' AS `Color||60||center||no||no||no`,
                              '' AS `RN No.||80||center||no||no||no`,
                              '' AS `CCM||80||right||yes||yes||yes`,
                              '' AS `Category||60||center||no||no||no`,
                              '' AS `Type||60||center||no||no||no`,
                              '' AS `Production Cost||100||right||yes||yes||yes`,
                              '' AS `Insertion Cost||100||right||yes||yes||yes`,
                              '' AS `Remarks||150||left||no||no||no`
               FROM exdeal_contract  LIMIT 1";
            
               return $sql;     
    }  
    
}
