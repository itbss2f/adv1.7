<?php

class Gl_datauploading_mod extends CI_Model  {
    
    public function getList($datefrom, $dateto, $reporttype, $type) {
        
        if ($reporttype == 1) {
        $stmt = "SELECT *, DATE(datadate) AS ddate FROM gldata WHERE datatype = '$type' AND DATE(datadate) BETWEEN '$datefrom' AND '$dateto' ORDER BY datadate";  
        $result = $this->db->query($stmt)->result_array();          
        } else if ($reporttype == 2) {
        $stmt = "SELECT *, DATE(datadate) AS ddate FROM sldata WHERE datatype = '$type' AND DATE(datadate) BETWEEN '$datefrom' AND '$dateto' ORDER BY datadate";  
        $result = $this->db->query($stmt)->result_array();          
        } else if ($reporttype == 3 ) {
        $stmt = "SELECT z.*, (z.glamount - z.slamount) AS diff
                FROM (
                SELECT gl.datatype, gl.datanumber, DATE(gl.datadate) AS datadate, SUM(gl.amount) AS glamount, gl.account AS glaccount, sl.slamount AS slamount, sl.slaccount AS slaccount  
                FROM gldata AS gl 
                LEFT OUTER JOIN (
                    SELECT sl.datatype, sl.datanumber AS sldatanumber, SUM(sl.amount) AS slamount, sl.account AS slaccount
                    FROM sldata AS sl
                    WHERE sl.datatype = '$type' AND DATE(sl.datadate) BETWEEN '$datefrom' AND '$dateto'
                    GROUP BY sl.datanumber, sl.datatype
                ) AS sl ON (sl.sldatanumber = gl.datanumber AND sl.slaccount = gl.account )
                WHERE gl.datatype = '$type' AND DATE(gl.datadate) BETWEEN '$datefrom' AND '$dateto' 
                GROUP BY datanumber, datatype)
                AS z
                WHERE (z.glamount - z.slamount) != 0  AND ABS((z.glamount - z.slamount)) >= 0.06
                ORDER BY diff DESC";    
        $result = $this->db->query($stmt)->result_array();        
        }  else if ($reporttype == 4 ) {
        $stmt = "SELECT z.*, (z.glamount - z.slamount) AS diff
                FROM (
                SELECT sl.datatype, sl.datanumber, DATE(sl.datadate) AS datadate, sl.payeename, sl.account, sl.amount AS slamount, gl.datanumber AS gldatanumber, SUM(gl.amount) AS glamount, gl.account AS glaccount
                FROM sldata AS sl
                LEFT OUTER JOIN gldata AS gl ON (gl.datanumber = sl.datanumber AND gl.datatype = sl.datatype AND DATE(gl.datadate) BETWEEN '$datefrom' AND '$dateto')
                WHERE sl.datatype = '$type' 
                AND DATE(sl.datadate) BETWEEN '$datefrom' AND '$dateto'
                GROUP BY sl.datanumber, sl.datatype) AS z
                WHERE (z.glamount - z.slamount) != 0  AND ABS((z.glamount - z.slamount)) >= 0.06 AND account != slamount 
                ORDER BY diff DESC";    
        $result = $this->db->query($stmt)->result_array();                
        }
        
        return $result;
    }
    
    public function insertSLData($datatype, $datadate){
        /*$stmt = "SELECT 'OR' AS datatype,or_num AS datanumber, DATE(or_date) AS datadate, 'C' AS datacode, 
                or_payee AS payeename, or_adtype, ad.adtype_araccount, caf_code,
                (or_vatsales + or_vatexempt + or_vatzero) AS vatsales,
                ROUND(( or_amt /(1+(or_cmfvatrate/100))),2) AS amount
                FROM or_m_tm 
                LEFT OUTER JOIN misadtype AS ad ON ad.id = or_adtype
                LEFT OUTER JOIN miscaf AS caf ON caf.id = ad.adtype_araccount
                WHERE or_type = 1 
                AND YEAR(or_date) = YEAR('$datadate') AND MONTH(or_date) = MONTH('$datadate') AND or_m_tm.status NOT IN('C','F')";*/
                
        $stmt = "SELECT 'OR' AS datatype, m.or_num AS datanumber, DATE(m.or_date) AS datadate, 'C' AS datacode, 
                    m.or_payee AS payeename, aom.ao_adtype AS or_adtype, ad.adtype_araccount, caf_code,
                    SUM(d.or_assignamt) AS vatsales,
                    ROUND(SUM(d.or_assigngrossamt),2) AS amount
                FROM or_m_tm AS m 
                LEFT OUTER JOIN or_d_tm AS d ON d.or_num = m.or_num
                LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = d.or_docitemid
                LEFT OUTER JOIN ao_m_tm AS aom ON aop.ao_num = aom.ao_num
                LEFT OUTER JOIN misadtype AS ad ON ad.id = aom.ao_adtype
                LEFT OUTER JOIN miscaf AS caf ON caf.id = ad.adtype_araccount
                WHERE m.or_type = 1 AND or_doctype = 'SI'
                AND YEAR(m.or_date) = YEAR('$datadate') AND MONTH(m.or_date) = MONTH('$datadate') AND m.status NOT IN('C','F')
                GROUP BY datanumber, caf_code 
                UNION
                SELECT 'OR' AS datatype, m.or_num AS datanumber, DATE(m.or_date) AS datadate, 'C' AS datacode, 
                    m.or_payee AS payeename, dcm.dc_adtype AS or_adtype, ad.adtype_araccount, caf_code,
                    SUM(d.or_assignamt) AS vatsales,
                    ROUND(SUM(d.or_assigngrossamt),2) AS amount
                FROM or_m_tm AS m 
                LEFT OUTER JOIN or_d_tm AS d ON d.or_num = m.or_num
                LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = d.or_docitemid
                LEFT OUTER JOIN misadtype AS ad ON ad.id = dcm.dc_adtype
                LEFT OUTER JOIN miscaf AS caf ON caf.id = ad.adtype_araccount
                WHERE m.or_type = 1 AND or_doctype = 'DM'
                AND YEAR(m.or_date) = YEAR('$datadate') AND MONTH(m.or_date) = MONTH('$datadate') AND m.status NOT IN('C','F')
                GROUP BY datanumber, caf_code 
                UNION
                SELECT 'OR' AS datatype, or_num AS datanumber, DATE(or_date) AS datadate, 'C' AS datacode, 
                    or_payee AS payeename, or_adtype, ad.adtype_araccount, caf_code,
                    (or_amt - totalpaid) AS vatsales,
                    ROUND(( (or_amt - totalpaid) /(1+(or_cmfvatrate/100))),2) AS amount
                FROM or_m_tm  AS m
                LEFT OUTER JOIN (
                    SELECT or_num AS paidornum, SUM(or_assignamt) AS totalpaid
                    FROM or_d_tm
                    WHERE YEAR(or_date) = YEAR('$datadate') AND MONTH(or_date) = MONTH('$datadate') AND status NOT IN('C','F')       
                    GROUP BY or_num
                ) AS paid ON paid.paidornum = m.or_num
                LEFT OUTER JOIN misadtype AS ad ON ad.id = or_adtype
                LEFT OUTER JOIN miscaf AS caf ON caf.id = ad.adtype_araccount
                WHERE or_type = 1 
                AND YEAR(or_date) = YEAR('$datadate') AND MONTH(or_date) = MONTH('$datadate') AND m.status NOT IN('C','F')
                AND or_amt != totalpaid  
                GROUP BY datanumber, caf_code";
        #echo '<pre>'; echo $stmt; exit;      
        $result = $this->db->query($stmt)->result_array();   
        $data['uploadby'] = $this->session->userdata('authsess')->sess_id;        
        if (!empty($result)) {
            foreach ($result as $row) {
                $data['datatype'] = $row['datatype'];   
                $data['datanumber'] = $row['datanumber'];
                $data['datadate'] = $row['datadate'];
                $data['datacode'] = $row['datacode'];
                $data['payeename'] = $row['payeename'];
                $data['account'] = $row['caf_code'];
                $data['amount'] = $row['amount'];
                
                $this->db->insert('sldata', $data);   
            }
        }
    }
    
    public function insertGLData($data) {
        $data['uploadby'] = $this->session->userdata('authsess')->sess_id;       
        
        $this->db->insert('gldata', $data);
        
        return true;
    }
    
    public function glcheckexistingdata($datatype,$datadate){ 

        $stmt = "SELECT COUNT(*) AS total FROM gldata WHERE datatype = '$datatype' AND YEAR(datadate) = YEAR('$datadate') AND MONTH(datadate) = MONTH('$datadate')";       

        $result = $this->db->query($stmt)->row_array();
        
        if ($result['total'] > 0) {
            $del = "DELETE FROM gldata WHERE datatype = '$datatype' AND YEAR(datadate) = YEAR('$datadate') AND MONTH(datadate) = MONTH('$datadate')";   
            $this->db->query($del);
        } 
        return true;
    }
    
    public function slcheckexistingdata($datatype,$datadate){ 

        $stmt = "SELECT COUNT(*) AS total FROM sldata WHERE datatype = '$datatype' AND YEAR(datadate) = YEAR('$datadate') AND MONTH(datadate) = MONTH('$datadate')";       

        $result = $this->db->query($stmt)->row_array();
        
        if ($result['total'] > 0) {
            $del = "DELETE FROM sldata WHERE datatype = '$datatype' AND YEAR(datadate) = YEAR('$datadate') AND MONTH(datadate) = MONTH('$datadate')";   
            $this->db->query($del);
        } 
        return true;
    }
}