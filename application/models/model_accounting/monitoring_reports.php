<?php

class Monitoring_reports extends CI_Model  {
    
    
    public function getMonitoringReport($datefrom, $dateto, $paytype, $reporttype)
     {
        $conpaytype = ""; $conreport = "";
        
        if ($paytype != 0) {
        $conpaytype = "AND aom.ao_paytype = $paytype";     
        } 
        
        switch ($reporttype) {
            case 1:
                $conreport = "DATE(aop.ao_sidate) >= '$datefrom' AND DATE(aop.ao_sidate) <= '$dateto'";    
            break;
            case 2:
                $conreport = "DATE(aop.ao_issuefrom) >= '$datefrom' AND DATE(aop.ao_issuefrom) <= '$dateto'";  
            break;   
        }
        
        $stmt = "SELECT aop.ao_num, aop.id, aom.ao_amf, aom.ao_cmf, aom.ao_payee AS client_name, 
                    clientcmf.id, SUM(aop.ao_cst) AS totalcost, SUM((aop.ao_cst * .15)) AS agencycomm, b.paytype_name
                    FROM ao_p_tm AS aop
                    INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                    LEFT OUTER JOIN miscmf AS clientcmf ON clientcmf.cmf_code = aom.ao_cmf
                    LEFT OUTER JOIN mispaytype AS b ON b.id = aom.ao_paytype
                    -- inner join misacmf as gacmf on gacmf.cmf_code = clientcmf.id  
                    WHERE $conreport $conpaytype  
                    AND (aom.ao_amf = 0 OR aom.ao_amf IS NULL)       
                    AND aop.status NOT IN ('F', 'C')
                    AND clientcmf.id IN (SELECT cmf_code FROM misacmf GROUP BY cmf_code ORDER BY cmf_code)
                    AND is_invoice = '1'
                    GROUP BY ao_num
                    ";

                #echo '<pre>'; echo $stmt ; exit();

            $result = $this->db->query($stmt)->result_array();

        return $result;

    }
    
}