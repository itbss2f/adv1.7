<?php

class JournalEntries extends CI_Model {
    
    public function updatejvdata($data) {
        $jvnum = $data['jvnum'];
        $jvdate = $data['jvdate'];
        
        foreach ($data['chck'] as $row) {
            $xdata = explode('|', $row);
            
            if($xdata[1] == 'C' || $xdata[1] == 'D') {
                $updatecmjv['dc_jvnum'] = $jvnum;
                $updatecmjv['dc_jvdate'] = $jvdate;
                $this->db->where(array('dc_num' => $xdata[0], 'dc_type' => $xdata[1]));
                $this->db->update('dc_m_tm', $updatecmjv);
            } else if ($xdata[1] == 'S') {
                $updateaop['ao_sijv_num'] = $jvnum;    
                $updateaop['ao_sijv_date'] = $jvdate;  
                $this->db->where(array('ao_sinum' => $xdata[0]));
                $this->db->update('ao_p_tm', $updateaop);  
            }
        }
    }
    
    public function journalentrysearch($data) {
        $type = $data['type'];
        $datefrom = $data['datefrom'];
        $dateto = $data['dateto'];
        $sql = $data['sqlfilter'];
        
        $concmdmtype = ""; $conamt = "a.dc_amt,";
        
        $cmdmtype = $data['cmdmtype'];
        
        if ($cmdmtype != 0) {
            $concmdmtype = "AND a.dc_subtype = $cmdmtype";    
        } 
        
        if ($cmdmtype == 8 || $cmdmtype == 1 || $cmdmtype == 2 || $cmdmtype == 3 || $cmdmtype == 4) {      
            //$conamt = "ROUND((a.dc_amt / (1+(IFNULL(vat.vat_rate, 0)/100))), 2) AS dc_amt,";
            $conamt = "SUM(d.dc_amt) AS dc_amt,";
        }
        
        if ($type == 'DMCM') {
            $stmt = "SELECT CASE a.dc_type
                            WHEN 'C' THEN 'CM'
                            WHEN 'D' THEN 'DM'
                           END AS dc_type, a.dc_type AS dtype,
                           a.dc_num, DATE(a.dc_date) AS dc_date, cmf.cmf_name AS agencyname, a.dc_payeename, a.dc_part, $conamt a.dc_jvnum, DATE(a.dc_jvdate) AS dc_jvdate, dcsub.dcsubtype_name  
                    FROM dc_m_tm AS a
                    INNER JOIN dc_a_tm AS d ON (d.dc_num = a.dc_num AND d.dc_code = 'D')
                    LEFT OUTER JOIN misdcsubtype AS dcsub ON dcsub.id = a.dc_subtype
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = a.dc_amf
                    LEFT OUTER JOIN misvat AS vat ON vat.id = a.dc_vat
                    WHERE a.status NOT IN('C', 'F')
                    AND DATE(a.dc_date) >= '$datefrom' AND DATE(a.dc_date) <= '$dateto' $concmdmtype  $sql
                    GROUP BY d.dc_num, d.dc_type
                    ORDER BY a.dc_type, a.dc_date, a.dc_num ASC";
        } else if ($type == 'SI') {
            $stmt = "SELECT 'SI' AS si_type, 'S' AS sitype, cmf.cmf_name, m.ao_payee, a.ao_sinum, DATE(a.ao_sidate) AS sidate, a.ao_sijv_num, DATE(a.ao_sijv_date) AS jvdate, SUM(a.ao_vatsales + a.ao_vatexempt + a.ao_vatzero) AS grossamt
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    WHERE a.status NOT IN('C', 'F')
                    AND DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto' AND a.ao_sinum != 1 AND a.ao_sinum != 0
                    GROUP BY a.ao_sinum
                    ORDER BY a.ao_sidate
                    ";
        }
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}

