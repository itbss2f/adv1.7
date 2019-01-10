<?php
Class Exdeal_inquiries extends CI_Model
{
    public function billable($data)
    {
        
        $status = $data['status'];
        
        $filter_type = $data['filter_type'];
        
        $sumpay = "";
        
        $sumpay2 = "";

        
        switch($status)
        {
           case "all" :
           
            $sumpay = "";
           
           break; 

           case "exdeal_rem" :
           
            $sumpay = " AND (a.ao_part_billing LIKE 'EX-DEAL%')  ";
           
           break;
           
           case "exdeal_only" :
           
            $sumpay = " AND (TRIM(a.ao_exdealstatus) != '' AND a.ao_exdealstatus IS NOT NULL OR a.ao_exdealstatus != '0' )";
           
           break;
           
           case "not_exdeal" :
           
            $sumpay = " AND (TRIM(a.ao_exdealstatus) = '' OR a.ao_exdealstatus IS NULL) ";
           
           break;
        }
        
        switch($filter_type)
        {
           case "agency" :
           
            $sumpay2 = " AND (c.cmf_name BETWEEN '$data[from_select]' AND '$data[to_select]') ";
            
           break; 
           
           case "client_agency" :
           
            $sumpay2 = " AND (d.cmf_name = '$data[from_select]' AND c.cmf_name = '$data[to_select]') ";
            
           break; 
           
           case "agency_client":
           
             $sumpay2 = " AND (c.cmf_name = '$data[from_select]' AND d.cmf_name = '$data[to_select]') ";
           
           break; 
           
           case "direct_client":
           
             $sumpay2 = " AND (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') ";
           
           break; 
           
           case "all_client":
           
             $sumpay2 = " AND (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') ";
           
           break;
           
           case "client_group":
           
             $sumpay2 = "";
            
           break;
        }
        
        $sql = "       SELECT     
                                a.id,
                                b.ao_ref,
                                a.ao_exdealcontractno,
                                a.ao_sinum,
                                DATE(a.ao_sidate) AS ao_sidate,
                                c.cmf_code AS agency_code,
                                SUBSTR(c.cmf_name,1,15) AS agency_name,
                                d.cmf_code AS client_code,
                                SUBSTR(d.cmf_name,1,15) AS client_name,
                                b.ao_ref AS PONumber,
                                e.empprofile_code, 
                                f.adtype_code,
                                CASE b.ao_type          
                                WHEN 'D' THEN 'Display'
                                WHEN 'C' THEN 'Classified' 
                                END `ao_type`,
                                CASE g.paytype_name
                                WHEN 'Billable Ad' THEN 'B1'
                                WHEN 'PTF Ad'   THEN 'PTF'
                                WHEN 'Cash Ad' THEN 'CA'
                                WHEN 'Credit Card' THEN 'CC'
                                WHEN 'Check' THEN 'CH'
                                WHEN 'NO CHARGE' THEN 'NC'
                                END as paytype_name ,
                                h.branch_code,
                                '0.00' AS total_billing,                                     
                                a.ao_agycommamt AS due_to_agency,
                                a.ao_grossamt AS net_billing,
                                a.ao_vatamt AS plus_vat,
                                a.ao_amt AS amount_due,
                                '0.00' AS total_paid,
                                a.ao_wtaxpercent,
                                a.ao_exdealpercent,
                                a.ao_exdealstatus AS exdeal_status,
                                a.ao_exdealamt AS exdeal_amount,
                                SUBSTR(a.ao_exdealpart,1,15) AS exdeal_remarks,
                                a.ao_dcnum,
                                DATE(a.ao_dcdate) AS ao_dcdate,
                                a.ao_dcamt,
                                a.ao_receive_date,
                                a.ao_receive_part,
                                a.ao_rfa_aistatus,
                                a.ao_rfa_supercedingai,
                                a.ao_part_billing
                             
                                FROM ao_p_tm AS a 
                                INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
                                LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf
                                LEFT OUTER JOIN misempprofile AS e ON e.id = b.ao_aef
                                LEFT OUTER JOIN misadtype AS f ON f.id = b.ao_adtype  
                                LEFT OUTER JOIN mispaytype AS g ON g.id = b.ao_paytype
                                LEFT OUTER JOIN misbranch AS h ON h.id = b.ao_branch 
                            
                                WHERE (DATE(a.ao_issuefrom) BETWEEN DATE('$data[from_date]') AND  DATE('$data[to_date]'))
                                AND (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]')
                                AND b.ao_ref = '$data[po_number]'
                          --     AND (a.ao_sinum != '0' AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum) != '')
                                AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O')    
                           --    AND (b.ao_paytype = '1') 
                           --    AND (a.ao_paginated_status = '1')
                                ";
                                
                            $sql .= $sumpay; 
                               
                         //   $sql .= $sumpay2;    
                             
                        //    $sql .= "AND (a.ao_sinum != '0' AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum) != '') ORDER BY a.ao_sinum ASC ";
                      //  echo $sql;
                        return $this->db->query($sql)->result();  
    }
    
    
    public function nonbillable($data)
    {
        $status = $data['status'];
        
        $filter_type = $data['filter_type'];
        
        $sumpay = "";
        
        $sumpay2 = "";
        
        switch($status)
        {
           case "all" :
           
            $sumpay = "";
           
           break; 

           case "exdeal_rem" :
           
            $sumpay = " AND (a.ao_part_billing LIKE 'EX-DEAL%')  ";
           
           break;
           
           case "exdeal_only" :
           
            $sumpay = " AND (a.ao_exdealstatus = 1)";
           
           break;
           
           case "not_exdeal" :
           
            $sumpay = " AND (TRIM(a.ao_exdealstatus) = '' OR a.ao_exdealstatus IS NULL) ";
           
           break;
        }
        
        switch($filter_type)
        {
           case "agency" :
           
            $sumpay2 = " AND (c.cmf_name BETWEEN '$data[from_select]' AND '$data[to_select]') ";
            
           break; 
           
           case "client_agency" :
           
            $sumpay2 = " AND (d.cmf_name = '$data[from_select]' AND c.cmf_name = '$data[to_select]') ";
            
           break; 
           
           case "agency_client":
           
             $sumpay2 = " AND (c.cmf_name = '$data[from_select]' AND d.cmf_name = '$data[to_select]') ";
           
           break; 
           
        
           case "direct_client":
           
             $sumpay2 = " AND ((c.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]') OR (d.cmf_name BETWEEN '$data[from_select]' AND  '$data[to_select]')) ";
           
           break;
           
           case "client_group":
           
             $sumpay2 = "";
            
           break;
        } 
        
        $sql = "SELECT     
                    a.id,
                    a.ao_sinum,
                    DATE(a.ao_issuefrom) AS ao_issuefrom,
                    a.ao_part_billing AS product_discription,
                    c.cmf_code AS agency_code,
                    SUBSTR(c.cmf_name,1,15) AS agency_name,
                    d.cmf_code AS client_code,
                    SUBSTR(d.cmf_name,1,15) AS client_name,
                    b.ao_ref AS PONumber,
                    e.empprofile_code, 
                    CONCAT(a.ao_width,'X',a.ao_length) AS size,
                    a.ao_totalsize,
                    a.ao_color,
                    b.ao_ref,
                    i.aosubtype_code,
                    f.adtype_code,
                    CASE b.ao_type          
                    WHEN 'D' THEN 'Display'
                    WHEN 'C' THEN 'Classified' 
                    END `ao_type`,
                    g.paytype_name,
                    h.branch_code,
                    a.ao_part_billing AS ao_part_billing,
                    a.ao_part_production AS ao_part_production,
                    '' AS production_exdeal,
                    a.ao_totalcharge,
                    a.ao_exdealstatus AS exdeal_status

                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
                    LEFT OUTER JOIN miscmf AS d ON TRIM(d.cmf_code) = TRIM(b.ao_cmf)
                    LEFT OUTER JOIN misempprofile AS e ON e.id = b.ao_aef
                    LEFT OUTER JOIN misadtype AS f ON f.id = b.ao_adtype  
                    LEFT OUTER JOIN mispaytype AS g ON g.id = b.ao_paytype
                    LEFT OUTER JOIN misbranch AS h ON h.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS i ON i.id = a.ao_subtype
                    
                    WHERE (DATE(a.ao_issuefrom) BETWEEN DATE('$data[from_date]') AND  DATE('$data[to_date]')) 
                    AND (a.ao_sinum != '0' AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum) != '')
                    AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O')    
                    AND (b.ao_paytype != '1') 
                    ";
                             
                $sql .= $sumpay; 
                   
                $sql .= $sumpay2;    
                 
                $sql .= " ORDER BY a.ao_sinum ASC ";


        return $this->db->query($sql)->result();
    }
    
    public function getinquirydata($data)
    {
         $sql = "SELECT  a.id,
                        b.ao_ref,
                        a.ao_sinum,
                        DATE(a.ao_issuefrom) AS ao_issuefrom,
                        c.cmf_name AS cmf_name,
                        '' AS item_id,
                        a.ao_amt AS amount,
                        a.ao_exdealstatus as exdeal_status,
                        a.ao_exdealpercent,
                        a.ao_exdealamt,
                        a.ao_exdealcontractno,
                        a.ao_exdealpart,
                        a.ao_exdealcash    
             
                FROM ao_p_tm AS a 
                INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                LEFT OUTER JOIN miscmf as c ON c.cmf_code = b.ao_cmf
                WHERE a.id = '$data[invoice_no]'
                AND (a.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]') ";
         
         return $this->db->query($sql)->result();
    }
    
    public function update_inquiry($data)
    {
        $id = $data['ao_id']; 
        
        $date =  isset($data['exdeal_date']) ? $data['exdeal_date'] : '';
                $exdeal_status = isset($data['exdeal_status']) ? $data['exdeal_status'] : '0';
                $exdeal_percent = isset($data['exdeal_percent']) ? $data['exdeal_percent']: '0.00';
                $exdeal_amount = isset($data['exdeal_amount']) ? $data['exdeal_amount']: '0.00';
                $exdeal_cash = isset($data['exdeal_cash']) ? $data['exdeal_cash']: '0.00';
                $exdeal_remarks = isset($data['exdeal_remarks']) ? $data['exdeal_remarks']: '';
                $contract_no = isset($data['contract_no']) ? $data['contract_no'] : '';
                $ao_id = $data['ao_id']; 
                $po_num = $data['ao_ref']; 
              if(!empty($po_num))
              {
                  $sql = "UPDATE ao_p_tm AS a
                          INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num      
                          SET 
                                a.ao_exdealstatus = '$exdeal_status',
                                a.ao_exdealpercent = '$exdeal_percent',
                                a.ao_exdealamt = '$exdeal_amount',
                                a.ao_exdealcash = '$exdeal_cash',
                                a.ao_exdealcontractno = '$contract_no',
                                a.ao_exdealpart = '$exdeal_remarks'
                         WHERE b.ao_ref = '$po_num' ";
               
                  $this->db->query($sql);   
              }  
                

       /* for($ctr=0;$ctr<count($data);$ctr++)
        {
          
          if(!empty($data['ao_id'][$ctr]) and isset($data['trigger_box'][$ctr]) )
          {
                $date =  isset($data['exdeal_date'][$ctr]) ? $data['exdeal_date'][$ctr] : '';
                $exdeal_status = isset($data['exdeal_status'][$ctr]) ? $data['exdeal_status'][$ctr] : '0';
                $exdeal_percent = isset($data['exdeal_percent'][$ctr]) ? $data['exdeal_percent'][$ctr] : '0.00';
                $exdeal_amount = isset($data['exdeal_amount'][$ctr]) ? $data['exdeal_amount'][$ctr] : '0.00';
                $exdeal_remarks = isset($data['exdeal_remarks'][$ctr]) ? $data['exdeal_remarks'][$ctr] : '0.00';
                $contract_no = isset($data['contract_no'][$ctr]) ? $data['contract_no'][$ctr] : '';
                $ao_id = $data['ao_id'][$ctr]; 
                
                 $sql = "UPDATE ao_p_tm SET 
                                ao_exdealstatus = '$exdeal_status',
                                ao_exdealpercent = '$exdeal_percent',
                                ao_exdealamt = '$exdeal_amount',
                                ao_exdealcontractno = '$contract_no',
                                ao_exdealpart = '$exdeal_remarks'
                         WHERE id = '$ao_id' ";
               
                $this->db->query($sql);
                
                echo $sql;  
               
          }             
 
        }     */
         
    }
    
    public function agency($data)
    {
        $sql = "  SELECT DISTINCT id, TRIM(cmf_name )as cmf_name FROM miscmf 
                  WHERE cmf_catad = 1 AND is_deleted = '0'
                  AND cmf_name LIKE '%$data[search]%'  
                  ORDER BY TRIM(cmf_name) ASC  LIMIT 15 ";
        
        return $this->db->query($sql)->result(); 
    }
    
    public function agency_client($data)
    {
        $sql = "SELECT DISTINCT id, TRIM(cmf_name )as cmf_name
                FROM miscmf 
                WHERE cmf_catad = 1   AND is_deleted = '0' 
                AND cmf_name LIKE '%$data[search]%'  
                ORDER BY TRIM(cmf_name) ASC LIMIT 15 ";
        
        return $this->db->query($sql)->result(); 
    }
    
    public function agency_client_chain($data)
    {
       $sql =  "SELECT c.id,(c.cmf_name) as cmf_name
                FROM misacmf as a
                INNER JOIN miscmf as b on b.id = a.amf_code
                INNER JOIN miscmf AS c ON c.id = a.cmf_code
                WHERE b.cmf_name = TRIM('$data[parent_val]')
                AND c.cmf_name LIKE '%$data[search]%'
                ORDER BY TRIM(b.cmf_name) ASC LIMIT 10";
       
       return $this->db->query($sql)->result(); 
    }
    
    public function client_agency($data)
    {
        $sql = "SELECT a.id, b.cmf_code AS clients,TRIM(b.cmf_name) AS cmf_name
                    FROM misacmf AS a
                    INNER JOIN miscmf AS b ON b.id = a.cmf_code
                    WHERE a.is_deleted = '0'
                      AND b.cmf_name LIKE '%$data[search]%'
                    GROUP BY cmf_name
                    ORDER BY b.cmf_name ASC LIMIT 15";
        
        return $this->db->query($sql)->result(); 
    }
    
    public function client_agency_chain($data)
    {
        $sql = "SELECT b.id,(b.cmf_name) as cmf_name
                FROM misacmf as a
                INNER JOIN miscmf as b on b.id = a.amf_code
                INNER JOIN miscmf AS c ON c.id = a.cmf_code
                WHERE c.cmf_name = TRIM('$data[parent_val]')
                AND b.cmf_name LIKE '%$data[search]%'
                ORDER BY TRIM(b.cmf_name) ASC LIMIT 10";
        
        return $this->db->query($sql)->result();
    }

    
    public function direct_client($data)
    {
     $sql = "SELECT DISTINCT id, TRIM(cmf_name) AS cmf_name,cmf_code FROM miscmf 
              WHERE cmf_catad = 2  AND is_deleted = '0'
              AND cmf_name LIKE '%$data[search]%'       
              ORDER BY TRIM(cmf_name) ASC LIMIT 15";
        
        return $this->db->query($sql)->result(); 
    }
    
    public function all_client($data)
    {
        $sql = "SELECT DISTINCT id,  TRIM(cmf_name )AS cmf_name
                    FROM miscmf 
                  WHERE is_deleted = '0'
                      AND cmf_name LIKE '%$data[search]%'   
                  ORDER BY TRIM(cmf_name) ASC LIMIT 15";
        
        return $this->db->query($sql)->result(); 
    }  
    
    public function client_group($data)
    {
        $sql = "SELECT id,group_name,advertiser FROM exdeal_advertisergroup WHERE is_deleted = '0'   AND group_name LIKE '%$data[search]%'  LIMIT 15  ";
        
        return $this->db->query($sql)->result();
    }
    
     public function industry_group()
    {
        $sql = "";
        
        return $this->db->query($sql)->result(); 
    }
    
}