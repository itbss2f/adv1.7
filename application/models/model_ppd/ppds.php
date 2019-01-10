<?php
class Ppds extends CI_Model
{
    public function retrievePPD($data)
    {
         
       $stmt = "  SELECT 
                         cmf.cmf_name AS agency_name,
                         aom.ao_payee AS client_name,
                             aop.ao_sinum,
                             DATE(aop.ao_sidate) AS ao_sidate,
                             DATE(aop.ao_receive_date) AS ao_receive_date, 
                             aop.ao_amt AS invoice_amount,
                             a.or_ppdpercent AS acmf_ppd, 
                             a.or_assignppdamt AS ppd_amount, 
                              (IFNULL(aop.ao_oramt, 0) + IFNULL(aop.ao_dcamt, 0))  AS applied_amount,
                             a.or_num as ao_ornum,
                         DATE(a.or_date) AS ao_ordate,
                        IFNULL(DATEDIFF(DATE(aop.ao_receive_date),DATE(a.or_date)),'') AS date_diff
                           
                    FROM or_d_tm AS a
                    INNER JOIN ao_p_tm AS aop ON aop.id = a.or_docitemid
                    INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                    WHERE a.or_ppdpercent != 0 AND a.or_doctype = 'SI'
                    AND (a.or_date BETWEEN '$data[from_date]' AND '$data[to_date]' )
                    "; 
        if(!empty($data['from_agency']) AND!empty($data["to_agency"]) )
        {
            $stmt .= " AND (cmf.cmf_name BETWEEN '$data[from_agency]' AND '$data[to_agency]')  ";
        }
       
       $stmt .= "  ORDER BY cmf.cmf_name ASC, aom.ao_payee ASC  ";
       return $this->db->query($stmt)->result_array(); 
    }
    
    public function autocomplete($data)
    {
        $stmt = "SELECT DISTINCT id, TRIM(cmf_name )as cmf_name FROM miscmf 
                  WHERE cmf_catad = 1 AND is_deleted = '0'
                  AND cmf_name LIKE '%$data[search]%'  
                  ORDER BY TRIM(cmf_name) ASC  LIMIT 15 ";
      
        return $this->db->query($stmt)->result();
    }
}

        
      /* $stmt2 = "SELECT c.cmf_name AS agency_name, 
                       SUBSTR(d.cmf_name,1,50) AS client_name,
                       a.ao_sinum,
                       DATE(a.ao_sidate) as ao_sidate,
                        DATE(a.ao_receive_date) as ao_receive_date,
                       a.ao_siamt AS invoice_amount,
                       e.acmf_ppd,
                       ROUND((a.ao_siamt * (e.acmf_ppd /100)),2) AS ppd_amount,
                       ROUND((a.ao_siamt -  (a.ao_siamt * (e.acmf_ppd /100))),2) AS applied_amount,
                       a.ao_ornum,
                       DATE(a.ao_ordate) as ao_ordate,
                       DATEDIFF(DATE(a.ao_ordate), DATE(a.ao_receive_date)) AS date_diff
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num 
                LEFT OUTER JOIN miscmf AS c ON c.id =b.ao_amf
                LEFT OUTER JOIN miscmf AS d ON d.cmf_code = b.ao_cmf
                LEFT OUTER JOIN misacmf AS e ON e.cmf_code = d.id
                WHERE (a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL)
                AND a.ao_ornum IS NOT NULL 
                AND (e.acmf_ppd IS NOT NULL AND e.acmf_ppd != 0) 
                AND ( a.ao_ordate BETWEEN '$data[from_date]' AND '$data[to_date]' )
                AND (c.cmf_name BETWEEN '$data[from_agency]' AND '$data[to_agency]') 
                ORDER BY c.cmf_name ASC, d.cmf_name ASC    ";*/