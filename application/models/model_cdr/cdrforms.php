<?php
class Cdrforms extends CI_Model
{
    
    
    public function searchData($data)
    {
        
        $cdr_no = $data['cdr_no'];
        $datefrom = $data['datefrom'];
        $dateto = $data['dateto'];
        $client_name = $data['client_name'];
        $agency_name = $data['agency_name'];
        $type_ad = $data['type_ad'];
        $cdrtype = $data['cdrtype'];
        
        
        $concdrno = "";
        $confrom = "";
        $conto = "";
        $conclient = "";
        $conagency = "";
        $contype = "";
        $concdrtype = "";
        
        if ($cdr_no != "") {
            $concdrno = "AND a.ao_cdr_num  = '$cdr_no'";    
        }if ($datefrom !=""){
             $confrom = "AND a.ao_issuefrom = '$datefrom'"; 
        }if ($dateto != ""){
            $conto = "AND a.ao_issuefrom = '$dateto'";
        }if($client_name != ""){
            $conclient = "AND c.cmf_name LIKE '%$client_name%'"; 
        }if ($agency_name != ""){
            $conagency = "AND  d.cmf_name LIKE '%$agency_name%'";
        }if($type_ad != ""){
            $contype = "AND b.ao_adtype = '$type_ad'"; 
        }if($cdrtype != ""){
            $concdrtype = "AND a.ao_cdr_type = $cdrtype";
        }                         

        $stmt = "SELECT a.id,
                        a.ao_sinum,
                        a.ao_num,
                        c.cmf_name AS client_name,
                        d.cmf_name AS agency_code,
                        b.ao_ref as PO,
                        DATE(a.ao_issuefrom) AS issue_date,
                        CONCAT(f.firstname,' ',f.middlename,' ',f.lastname) AS acct_exec,
                        DATE(a.ao_issuefrom) AS issue_date,
                        CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                        a.ao_totalsize AS ccm,
                        g.adtype_name,
                        a.ao_amt,        
                        h.cdrtype_name,
                        a.ao_cdr_reason  AS nature_of_complaint,
                        a.ao_cdr_findings  AS finding,
                        a.ao_cdr_person AS person,
                        a.ao_cdr_finalstatus,
                        a.ao_cdr_status,
                        a.ao_cdr_num,
                        DATE(a.ao_cdr_date) AS ao_cdr_date        
                
            FROM ao_p_tm AS a
            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
            LEFT OUTER JOIN miscmf AS c ON c.cmf_code = b.ao_cmf
            LEFT OUTER JOIN miscmf AS d ON d.id = b.ao_amf
            LEFT OUTER JOIN misempprofile AS e ON e.id = b.ao_aef
            LEFT OUTER JOIN users f ON f.id = e.user_id
            LEFT OUTER JOIN misadtype AS g ON g.id = b.ao_adtype
            LEFT OUTER JOIN miscdrtype h ON h.id = a.ao_cdr_type
            WHERE a.ao_cdr_num != 0 $concdrno $confrom $conto $conclient $conagency $contype $concdrtype
            ORDER BY ao_cdr_date ASC";            
            
        #echo "<pre>"; echo $stmt; exit;
        
        if(!empty($data['issue_date']))
        {
           $stmt .= " AND DATE(a.ao_issuefrom) = '$data[issue_date]' "; 
        }
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function generate($data)
    {   
        $condate = "";
        if(!empty($data['issue_date']))
        {
           $condate  = " AND DATE(a.ao_issuefrom) = '$data[issue_date]'  "; 
        }
        $stmt = "SELECT a.id,
                        a.ao_sinum,
                        a.ao_num,
                        c.cmf_name AS client_name,
                        d.cmf_name AS agency_code,
                        b.ao_ref as PO,
                        DATE(a.ao_issuefrom) AS issue_date,
                        CONCAT(f.firstname,' ',f.middlename,' ',f.lastname) AS acct_exec,
                        DATE(a.ao_issuefrom) AS issue_date,
                        CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                        a.ao_totalsize AS ccm,
                        g.adtype_name,
                        a.ao_amt,        
                        h.cdrtype_name,
                        a.ao_cdr_reason  AS nature_of_complaint,
                        a.ao_cdr_findings  AS finding,
                        a.ao_cdr_person AS person,
                        a.ao_cdr_finalstatus,
                        a.ao_cdr_num, 
                        DATE(a.ao_cdr_date) AS ao_cdr_date        
                
            FROM ao_p_tm AS a
            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
            LEFT OUTER JOIN miscmf AS c ON c.cmf_code = b.ao_cmf
            LEFT OUTER JOIN miscmf AS d ON d.id = b.ao_amf
            LEFT OUTER JOIN misempprofile AS e ON e.id = b.ao_aef
            LEFT OUTER JOIN users f ON f.id = e.user_id
            LEFT OUTER JOIN misadtype AS g ON g.id = b.ao_adtype
            LEFT OUTER JOIN miscdrtype h ON h.id = a.ao_cdr_type
            WHERE a.ao_num = '$data[ao_num]'  $condate
            ORDER BY ao_cdr_date ASC";
        
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function form($data)
    {
        $stmt = "SELECT a.id,
                        a.ao_sinum,
                        a.ao_num,
                        c.cmf_name AS client_name,
                        d.cmf_name AS agency_name,
                        b.ao_ref as PO,
                        DATE(a.ao_issuefrom) AS issue_date,
                        CONCAT(f.firstname,' ',f.middlename,' ',f.lastname) AS acct_exec,
                        DATE(a.ao_issuefrom) AS issue_date,
                        CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                        a.ao_totalsize AS ccm,
                        g.adtype_name,
                        a.ao_amt, 
                        a.ao_cdr_type as cdr_type,       
                        h.cdrtype_name,
                        DATE(a.ao_cdr_date) AS ao_cdr_date,
                        a.ao_cdr_num,
                        a.ao_cdr_reason  AS nature_of_complaint,
                        a.ao_cdr_findings  AS finding,
                        a.ao_cdr_person AS person,
                        a.ao_cdr_adjustment AS responsible,
                        a.ao_cdr_finalstatus        
                
            FROM ao_p_tm AS a
            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
            LEFT OUTER JOIN miscmf AS c ON c.cmf_code = b.ao_cmf
            LEFT OUTER JOIN miscmf AS d ON d.id = b.ao_amf
              LEFT OUTER JOIN misempprofile AS e ON  e.user_id = b.ao_aef
            LEFT OUTER JOIN users f ON f.id = e.user_id  
            LEFT OUTER JOIN misadtype AS g ON g.id = b.ao_adtype
            LEFT OUTER JOIN miscdrtype h ON h.id = a.ao_cdr_type
            WHERE a.id = '$data[id]' ";
       
        $result = $this->db->query($stmt)->row();
        
        return $result; 
    }
    
    public function getAllCdr()
    {
        $stmt = "SELECT id ,cdrtype_name FROM miscdrtype WHERE is_deleted = 0;";
        
        $result = $this->db->query($stmt)->result();
        
        return $result;
    }
    
    public function updateCdr($data)
    {
        $stmt = "UPDATE ao_p_tm SET
                        ao_cdr_type = '$data[cdr_type]',
                        ao_cdr_num = '$data[cdr_no]',
                        ao_cdr_date = '$data[cdrdate]',
                        ao_cdr_findings = '$data[findings]',
                        ao_cdr_adjustment = '$data[responsible]',
                  --      ao_cdr_person = '$data[cdr_type]',
                        ao_cdr_reason = '$data[nature_of_complaint]'
                 WHERE id = '$data[id]'    ";
                 
                
        $this->db->query($stmt);
    }
    
    public function getlastcdrno()
    {
        $stmt = "SELECT ao_cdr_num as cdr_num FROM ao_p_tm WHERE (ao_cdr_num != '' OR ao_cdr_num !=  NULL) ORDER BY CAST(ao_cdr_num AS SIGNED) DESC LIMIT 1";
        
        return $this->db->query($stmt)->row();
    }
    
    public function Searching($find){
        
        $conno = ""; $conclient = "";
        
       if ($find['cdr_no'] != "") {$connum = "AND cdr_no LIKE '".$find['cdr_no']."%' ";} 
       if ($find['clien_name'] != "") {$connum = "AND cdr_no LIKE '".$find['clien_name']."%' ";}  
        
       /* $connum = ""; $condatefrom =""; $conto =""; $conclient = ""; $agency = ""; 
        
        if ($find['cdr_no'] != "") {$connum = "AND cdr_no LIKE '".$find['cdr_no']."%' ";}
        if ($find['datefrom'] != "") {$connum = "AND cdr_no LIKE '".$find['datefrom']."%' ";}
        if ($find['dateto'] != "") {$connum = "AND cdr_no LIKE '".$find['dateto']."%' ";}       
        if ($find['clien_name'] != "") {$connum = "AND cdr_no LIKE '".$find['clien_name']."%' ";}
        if ($find['agency_name'] != "") {$connum = "AND cdr_no LIKE '".$find['agency_name']."%' ";}
        if ($find['cdr_no'] != "") {$connum = "AND cdr_no LIKE '".$find['cdr_no']."%' ";}
        if ($find['Type_ad'] != "") {$connum = "AND cdr_no LIKE '".$find['Type_ad']."%' ";}
                                                                                              */
                                                                    
        
        $stmt = "SELECT id ,cdrtype_name as client_name FROM miscdrtype WHERE is_deleted = 0 $conclient $conno";
                          
                               
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
     public function getAdtype() {
         
        $stmt = "SELECT id, adtype_code, adtype_name FROM misadtype WHERE is_deleted = 0 ORDER BY adtype_code";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getCdrtype() {
         
        $stmt = "SELECT id,cdrtype_name FROM miscdrtype";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
                            
}
