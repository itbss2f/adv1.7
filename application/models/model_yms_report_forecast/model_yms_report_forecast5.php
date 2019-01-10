<?php

class Model_yms_report_forecast5 extends CI_Model {         
    
    public function getData($val) {
        
        $datefrom = $val['datefrom'];
        $dateto = $val['dateto'];
        $edition = $val['edition'];
        
        $reporttype = $val['reporttype'];

        if ($reporttype == 1) {
        
            $stmt = "SELECT DATE(a.ao_issuefrom) AS issuedate, m.ao_payee, m.ao_part_billing, col.color_code, a.ao_num, ad.adtype_code, emp.empprofile_code, cmf.cmf_name,
                           IF (m.ao_amf = 0, a.ao_grossamt, 0) AS directamt,
                           IF (m.ao_amf != 0, a.ao_grossamt, 0) AS agencyamt,
                           a.ao_grossamt AS totalamt, a.ao_agycommamt, (a.ao_vatsales + a.ao_vatexempt + a.ao_vatzero) AS netvatsales
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                    LEFT OUTER JOIN miscolor AS col ON col.id = a.ao_color
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.ao_aef
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.status NOT IN ('F', 'C') AND a.ao_class = 153 
                    AND a.ao_prod = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$edition')) 
                    ORDER BY a.ao_issuefrom";       
            
            $result = $this->db->query($stmt)->result_array();
            
            return $result;
        
        }
    }    
}
?>
