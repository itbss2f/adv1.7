<?php
class Payments extends CI_Model              
{
    
    public function getLastORNumberAuto() {
        $stmt = "SELECT IFNULL(MAX(or_num), 0)  + 1 AS ornum FROM or_m_tm WHERE or_transtype = 'A'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return @$result['ornum'];
        
    }
    
    public function countPayment($hkey) {
        $stmt = "SELECT COUNT(*) AS countpayment FROM temp_payment_types WHERE mykeyid = '$hkey'  AND is_temp_delete = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;    
    }
    
    public function getPaymentExdeal($hkey) {
        $stmt = "SELECT COUNT(*) AS note FROM temp_payment_types WHERE mykeyid = '$hkey' AND TYPE = 'EX' AND is_temp_delete = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }

    public function orUnPosted($ornum, $ortype, $status_n) {

        $status_n = $this->session->userdata('authsess')->sess_id;

        $stmtm = "UPDATE or_m_tm SET `status` = 'A', status_n =  $status_n,`status_d` = NOW() WHERE or_num = '$ornum' AND status = 'O'";
        $this->db->query($stmtm);
        
        $stmtp = "UPDATE or_p_tm SET `status` = 'A', status_n =  $status_n,`status_d` = NOW() WHERE  or_num  = '$ornum' AND status = 'O'";
        $this->db->query($stmtp);

        $stmtd = "UPDATE or_d_tm SET `status` = 'A', status_n =  $status_n,`status_d` = NOW() WHERE  or_num  = '$ornum' AND status = 'O'";
        $this->db->query($stmtd);
        
        $stmtres = "SELECT or_num, or_num , FORMAT(or_amt, 2) AS oramt, FORMAT(or_assignamt, 2) AS orassignamt 
                    FROM or_m_tm 
                    WHERE or_num = '$ornum' AND status = 'A'";
                    
        $result = $this->db->query($stmtres)->result_array();

        return $result;

  
    }
    
    // public function orUnPosted($ornum, $ortype) {
        
    //     $data['status'] = 'A';                                       
    //     $data['status_d'] = date('Y-m-d h:i:s');  
        
        
    //     if ($ortype == 1) {
    //     $this->db->where('or_num', $ornum);
    //     $this->db->update('or_d_tm', $data);    
    //     } else {
    //     $this->db->where('or_num', $ornum);
    //     $this->db->update('or_m_tm', $data);
    //     }
        
        
    //     return true;
    // }

    public function orPosted($ornum, $status_n) {

        $stmtm = "UPDATE or_m_tm SET `status` = 'O', status_n =  $status_n,`status_d` = NOW() WHERE or_num = '$ornum' AND status = 'A'";
        $this->db->query($stmtm);
        
        $stmtp = "UPDATE or_p_tm SET `status` = 'O', status_n = $status_n,`status_d` = NOW() WHERE  or_num  = '$ornum' AND status = 'A'";
        $this->db->query($stmtp);

        $stmtd = "UPDATE or_d_tm SET `status` = 'O', status_n = $status_n, `status_d` = NOW() WHERE  or_num  = '$ornum' AND status = 'A'";
        $this->db->query($stmtd);
        
        $stmtres = "SELECT or_num, or_num , FORMAT(or_amt, 2) AS oramt, FORMAT(or_assignamt, 2) AS orassignamt 
                    FROM or_m_tm 
                    WHERE or_num = '$ornum' AND status = 'O'";
                    
        $result = $this->db->query($stmtres)->result_array();

        return $result;

    
    }
    
    // public function orPosted($ornum) {
        
    //     $data['status'] = 'O';                                       
    //     $data['status_d'] = date('Y-m-d h:i:s');  
        
    //     if ($ortype == 1) {
    //     $this->db->where('or_num', $ornum);
    //     $this->db->update('or_d_tm', $data);   
    //     } else {
    //     $this->db->where('or_num', $ornum);
    //     $this->db->update('or_m_tm', $data);
    //     }
        
    //     return true;
    // }
    
    public function postthissingleor($ornum) {
        
        $stmt = "SELECT m.or_num, m.or_date, m.or_type, m.status 
                FROM or_m_tm AS m
                LEFT OUTER JOIN or_d_tm AS d  ON m.or_num  = d.or_num
                WHERE m.or_num = $ornum";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function postOR($datefrom, $todate) {
        
        $stmtm = "UPDATE or_m_tm SET `status` = 'O', `status_d` = NOW() WHERE DATE(or_date) >= '$datefrom' AND DATE(or_date) <= '$todate' AND status = 'A'";
        $this->db->query($stmtm);
        
        $stmtp = "UPDATE or_p_tm SET `status` = 'O', `status_d` = NOW() WHERE DATE(or_date) >= '$datefrom' AND DATE(or_date) <= '$todate' AND status = 'A'";
        $this->db->query($stmtp);
        
        //$stmtd = "UPDATE or_d_tm SET `status` = 'O', `status_d` = NOW() WHERE DATE(or_date) >= '$datefrom' AND DATE(or_date) <= '$todate' AND status = 'A'";
        $stmtd = "UPDATE or_d_tm SET `status` = 'O', `status_d` = NOW() WHERE DATE(or_date) <= '$todate' AND status = 'A'";
        $this->db->query($stmtd);
        
        $stmtres = "SELECT or_num, DATE(or_date) AS ordate, FORMAT(or_amt, 2) AS oramt, FORMAT(or_assignamt, 2) AS orassignamt 
                    FROM or_m_tm 
                    WHERE DATE(or_date) >= '$datefrom' AND DATE(or_date) <= '$todate' AND status = 'O'";
                    
        $result = $this->db->query($stmtres)->result_array();
        
        return $result;
    }
    
    public function changeORType($ornum, $paymenttypeid) {
        $data['or_type'] = $paymenttypeid;
        #$data['status_d'] = date('Y-m-d h:m:s'); 
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $data['edited_d'] = date('Y-m-d h:i:s');  
        
        $this->db->where('or_num', $ornum);
        $this->db->update('or_m_tm', $data);
        
        
        return true;    
    }
    
    public function removeRevenueAO($ornum) {
        $stmt = "UPDATE ao_p_tm SET ao_oramt = 0, is_payed = 0, is_temp = 0, ao_sinum = 0, ao_paginated_status = 0, is_invoice = 0, 
                 ao_ornum = NULL, ao_ordate = NULL, 
                 ao_paginated_name = NULL, ao_paginated_date = NULL, 
                 ao_sidate = NULL, 
                 ao_wtaxstatus = NULL, ao_wtaxamt = 0, ao_wtaxpercent = 0, ao_wvatstatus = NULL, ao_wvatamt = 0, 
                 ao_wvatpercent = 0
                 WHERE ao_ornum = $ornum";
        #echo "<pre>"; echo $stmt; exit;
        $this->db->query($stmt);
        
        return true;
    }
    
    public function savePaymentAppliedForImportedAO($ornum, $ordate, $aoimport, $data) {      
    
   
        $stmt = "SELECT id, ao_amt, ao_num FROM ao_p_tm WHERE ao_num = $aoimport";  
        $result = $this->db->query($stmt)->result_array();
        
        /*$update_d['ao_paginated_status'] = 0;    
        $update_d['ao_paginated_name'] = $this->session->userdata('authsess')->sess_id;                                              
        $update_d['ao_paginated_date'] = date('Y-m-d');*/    
        //$update_d['is_payed'] = 1;  
        $update_d['is_temp'] = 1;    
        $update_d['is_invoice'] = 1;    
        $update_d['ao_sinum'] = 1;    
        $update_d['ao_sidate'] = date('Y-m-d'); 
        $update_d['ao_ornum'] = $ornum;   
        $update_d['ao_ordate'] = $ordate;    

        foreach ($result as $row) {

            $this->db->where('id', $row['id']);
            $this->db->update('ao_p_tm', $update_d); 
        }

        $stmtup = "SELECT id, ao_amt, ao_num FROM ao_p_tm WHERE ao_ornum = $ornum ORDER BY ao_issuefrom";     
        $resultup = $this->db->query($stmtup)->result_array();     
        
        $stmt2 = "SELECT SUM(ao_amt) AS totalamt FROM ao_p_tm WHERE ao_ornum = $ornum";       
        $result2 = $this->db->query($stmt2)->row_array();
        $oramtdue = $result2['totalamt'];   
        
        $or_amt = $data['oramt'];   
        $wtax = $data['wtaxamt'];      
        $wvat = $data['wvatamt'];    
        $other = $data['otheramt'];    
        $or_percent = 0;        
        $or_percent_sum = 0;
        $or_lastdet = 0;
        $or_minus = 0;
        $or_plus = 0;
        $neworamt = 0;
        $newamt = 0;
        $newwtax = 0;
        $newwvat = 0;

        $totalresult = count($resultup); 

        for ($x = 0; $x < $totalresult; $x++) {   

            $or_percent = (floatval($resultup[$x]['ao_amt']) / floatval($oramtdue) * 100);
            
            if ($x == $totalresult - 1) {
                
                if ($or_percent_sum < 100) {
                    $or_plus = (100) - ($or_percent_sum);      
                    #$or_plus = (100) - ($or_percent_sum);
                    $or_lastdet = $or_lastdet + $or_plus;                               
                } else if ($or_percent_sum > 100) {
                    #$or_minus = ($or_percent_sum) - (100);
                    $or_minus = ($or_percent_sum) - (100);
                    $or_lastdet = $or_lastdet - $or_minus;                
                } else {
                    $or_lastdet = $or_percent;   
                }  
                #$or_lastdet = $or_percent;               
                $or_lastdet;
                $update_p['ao_oramt'] = number_format(floatval($or_amt) * ($or_lastdet / 100), 2, '.', '');     
                $update_p['ao_wtaxamt'] = number_format(floatval($wtax) * ($or_lastdet / 100), 2, '.', '');                                    
                $update_p['ao_wvatamt'] = number_format(floatval($wvat) * ($or_lastdet / 100), 2, '.', '');                                
                $update_p['ao_otheramt'] = number_format(floatval($other) * ($or_lastdet / 100), 2, '.', '');                                
            } else {    

                $or_percent_sum += $or_percent;  

                $update_p['ao_oramt'] = number_format(floatval($or_amt) * ($or_percent / 100), 2, '.', '');     
                $update_p['ao_wtaxamt'] = number_format(floatval($wtax) * ($or_percent / 100), 2, '.', '');                                    
                $update_p['ao_wvatamt'] = number_format(floatval($wvat) * ($or_percent / 100), 2, '.', '');                                
                $update_p['ao_otheramt'] = number_format(floatval($other) * ($or_percent / 100), 2, '.', '');                                
            }
            $update_p['ao_wvatstatus'] = $data['or_wvatstatus'];                    
            $update_p['ao_wvatpercent'] = $data['or_wvatpercent'];                                        
            $update_p['ao_wtaxstatus'] = $data['or_wtaxstatus'];                                        
            $update_p['ao_wtaxpercent'] = $data['or_wtaxpercent'];                                        
            $update_p['ao_ppdstatus'] = $data['or_ppdstatus'];                                        
            $update_p['ao_ppdpercent'] = $data['or_ppdpercent'];                                     
            #print_r2($update_p);     exit;
            $this->db->where('id', $resultup[$x]['id']);
            $this->db->update('ao_p_tm', $update_p);
        }
        
        #exit;
        
        $aoornum = $ornum;
        $stmtret = "SELECT id, ao_num, ao_amt, ao_oramt, ao_dcamt, is_payed, (ao_oramt + ao_dcamt) AS totalpaid, IF ((ao_oramt + ao_dcamt) >= ao_amt, 1, 0) AS ispayed FROM ao_p_tm WHERE ao_ornum = $aoornum"; 
        
        $resultret = $this->db->query($stmtret)->result_array();
        
        foreach ($resultret as $rrow) {
            if ($rrow['ispayed'] == 1) {
                $detail['is_payed'] = 1;
                
            }  else {
                $detail['is_payed'] = 0;           
            }
            $this->db->where('id', $rrow['id']);
                $this->db->update('ao_p_tm', $detail);    
        }
        return true;
    }
    
    public function getAOImport($aonum) {
        $stmt = "SELECT m.ao_num, m.ao_amt, m.ao_paytype, IF (p.is_payed = 1, COUNT(p.is_payed), 0) AS payed, m.ao_cmfvatrate, FORMAT(m.ao_amt, 2) AS totalamt
                FROM ao_m_tm AS m 
                LEFT OUTER JOIN ao_p_tm AS p ON p.ao_num = m.ao_num
                WHERE m.ao_num = $aonum AND m.ao_paytype IN (3, 4, 5, 6) AND m.status != 'C'   -- AND p.ao_ornum != ''
                GROUP BY m.ao_num";                                                     
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveORDatafix($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $data['edited_d'] = date('Y-m-d h:i:s');  
        
        $this->db->where('or_num', $id);
        $this->db->update('or_m_tm', $data);
        
        $datap['or_date'] = $data['or_date'];
        $datap['or_num'] = $data['or_num'];
        $datap['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $datap['edited_d'] = date('Y-m-d h:m:s');  
        
        $this->db->where('or_num', $id);
        $this->db->update('or_p_tm', $datap);
        
        $datad['or_date'] = $data['or_date'];    
        $datad['or_num'] = $data['or_num'];        
        $datad['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $datad['edited_d'] = date('Y-m-d h:i:s');  
        
        $this->db->where('or_num', $id);
        $this->db->update('or_d_tm', $datad);
        
        
        
        $dataaop['ao_ornum'] = $data['or_num']; 
        $dataaop['ao_ordate'] = $data['or_date'];   
        
        $this->db->where('ao_ornum', $id);
        $this->db->update('ao_p_tm', $dataaop); 
        
        return true;          
        
    }
    
    public function getDataOR($id) {
        $stmt = "SELECT m.or_num, DATE(m.or_date) AS ordate, m.or_cmf, m.or_payee, m.or_type
                  FROM or_m_tm AS m WHERE m.or_num = '$id'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function getORNumDataFix($ornum) {
        $stmt = "SELECT m.or_num, DATE(m.or_date) AS ordate, m.or_cmf, m.or_payee, m.or_amt, IFNULL(d.appliedcount, 0) AS totalapplied, m.status
                FROM or_m_tm AS m
                LEFT OUTER JOIN(
                           SELECT COUNT(d.or_num) AS appliedcount, d.or_num FROM or_d_tm AS d WHERE d.or_num = $ornum
                           ) AS d ON d.or_num = m.or_num
                WHERE m.or_num = $ornum";
                
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }

    public function printablePaymentDetail($ornum) {
        
        $stmt = "SELECT  CASE or_paytype
                         WHEN 'CH' THEN 'Cash' 
                         WHEN 'CK' THEN CONCAT(mispaycheckbank.bmf_code, ' ', DATE(or_paydate)) 
                         WHEN 'CC' THEN CONCAT(miscreditcard.creditcard_code, ' ', DATE(or_expirydate))
                         WHEN 'DD' THEN 'Direct' 
                         WHEN 'EX' THEN 'Exdeal'         
                    END AS `typename`,
                    IF (or_paytype = 'CK' , or_paynum, or_creditcardnumber) AS cno,
                    IF (or_paytype = 'CK' , IF(DATE(or_paydate) = '0000-00-00', '', DATE(or_paydate)), IF(DATE(or_expirydate) = '0000-00-00', '', DATE(or_expirydate))) AS cnodate,
                    or_num, or_paytype, or_amt, or_paybank, mispaycheckbank.bmf_code, miscreditcard.creditcard_code     
                FROM or_p_tm 
                LEFT OUTER JOIN mispaycheckbank ON mispaycheckbank.id = or_paybank
                LEFT OUTER JOIN miscreditcard ON miscreditcard.id = or_creditcard
                WHERE or_num = $ornum";
        
        $result = $this->db->query($stmt)->result_array();  
                
        return $result;     
    }
    
    public function printableData($ornum) {
        $stmt = "SELECT m.or_num, DATE_FORMAT(m.or_date,'%m/%d/%Y') AS ordate, IF(m.or_tin = '000000000000', '', m.or_tin) AS or_tin,
                        CONCAT(IFNULL(m.or_add1, ''), ' ',IFNULL(m.or_add2, ''), ' ',IFNULL(m.or_add3, '')) AS address,
                       m.or_payee AS payee, m.or_amtword,
                       m.or_part, m.or_vatsales, m.or_vatexempt, m.or_vatzero, m.or_grossamt,
                       m.or_vatamt, m.or_wtaxamt, m.or_wvatamt, m.or_ppdamt, m.or_amt, UPPER(CONCAT(firstname,' ',SUBSTR(middlename, 1, 1),'. ',lastname)) AS cashiername 
                FROM or_m_tm AS m
                LEFT OUTER JOIN users ON users.id = m.user_n
                WHERE m.or_num = $ornum";
        
        $result = $this->db->query($stmt)->row_array();          
        
        return $result;
    }
    
    public function saveUpdatePaymentAppliedAmountRevenue($ornum, $assamt) {
        
        $this->db->where('ao_ornum', $ornum);
        
        $data['ao_sinum'] = 1;    
        $data['ao_sidate'] = date('Y-m-d'); 
        $data['ao_oramt'] = $assamt;
        $this->db->update('ao_p_tm', $data);
        return true;
    }
    public function getInvoiceDataforOR($inv) {
        $stmt = "SELECT DISTINCT a.id AS detid, a.ao_sinum, b.ao_cmf AS clientcode, b.ao_payee AS clientname, b.ao_add1, b.ao_add2, b.ao_add3,  b.ao_celprefix, b.ao_cel, b.ao_branch,
                       b.ao_tin, b.ao_zip, b.ao_tel1, b.ao_telprefix1, b.ao_tel2, b.ao_telprefix2, b.ao_fax, b.ao_faxprefix, a.ao_amt AS ao_amtnotformat,
                       FORMAT(IFNULL(a.ao_amt, 0), 2) AS ao_amt, FORMAT(IFNULL(a.ao_vatsales, 0), 2) AS ao_vatsales, FORMAT(IFNULL(a.ao_vatexempt, 0), 2) AS ao_vatexempt, FORMAT(IFNULL(a.ao_grossamt, 0), 2) AS ao_grossamt,
                       FORMAT(IFNULL(a.ao_vatzero, 0), 2) AS ao_vatzero, FORMAT(IFNULL(a.ao_vatamt, 0), 2) AS ao_vatamt, FORMAT(IFNULL((a.ao_wtaxamt + a.ao_wvatamt + a.ao_ppdamt), 0), 2) AS witholdingandpp,
                       a.ao_cmfvatcode, a.ao_cmfvatrate, FORMAT(IFNULL(a.ao_wtaxamt, 0), 2) AS ao_wtaxamt, FORMAT(IFNULL(a.ao_wvatamt, 0), 2) AS ao_wvatamt, FORMAT(IFNULL(a.ao_ppdamt, 0), 2) AS ao_ppdamt,
                       IFNULL(a.ao_wtaxstatus, 0) AS ao_wtaxstatus, IFNULL(a.ao_wvatstatus, 0) AS ao_wvatstatus, IFNULL(a.ao_ppdstatus, 0) AS ao_ppdstatus,
                       IFNULL(a.ao_wtaxpercent, 0) AS ao_wtaxpercent, IFNULL(a.ao_wvatpercent, 0) AS ao_wvatpercent, IFNULL(a.ao_ppdpercent, 0) AS ao_ppdpercent, is_payed      
                FROM ao_p_tm AS a 
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                WHERE a.ao_sinum = '$inv' AND a.is_payed = 0";

        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function validateSingleInv($inv) {
        $stmt = "SELECT COUNT(ao_sinum) AS totalinv FROM ao_p_tm WHERE ao_sinum = '$inv' AND is_payed = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result['totalinv'];
    }
    
    public function prCheckDueList() {
        
        $stmt = "SELECT pr_m_tm.pr_num, DATE(pr_m_tm.pr_date) AS pr_date, pr_m_tm.pr_cmf, pr_m_tm.pr_amf, pr_m_tm.pr_payee, pr_m_tm.pr_amt,
                       pr_m_tm.pr_ccf, pr_m_tm.pr_bnacc, pr_m_tm.pr_branch, pr_m_tm.pr_part, pr_m_tm.pr_comment,
                       CONCAT(users.firstname, ' ',SUBSTRING(users.middlename,1,1), '. ',users.lastname) AS ccf,
                       misbaf.baf_acct AS bank, misbranch.branch_code AS branch 
                FROM pr_m_tm
                INNER JOIN pr_p_tm ON pr_m_tm.pr_num = pr_p_tm.pr_num
                LEFT OUTER JOIN users ON users.id = pr_m_tm.pr_ccf       
                LEFT OUTER JOIN misbaf ON misbaf.id = pr_m_tm.pr_bnacc
                LEFT OUTER JOIN misbranch ON misbranch.id = pr_m_tm.pr_branch
                WHERE pr_m_tm.pr_num IS NOT NULL AND pr_m_tm.pr_ornum IS NULL 
                AND pr_p_tm.pr_paytype = 'CK' AND DATE(pr_p_tm.pr_paydate) <= NOW()";
                
         $result = $this->db->query($stmt)->result_array();
        
         return $result;
    }
    
    public function getPRCheckDue() {
        
        $stmt = "SELECT COUNT(p.pr_num) AS pendingpr
                FROM pr_p_tm AS p 
                INNER JOIN pr_m_tm AS m ON m.pr_num = p.pr_num
                WHERE m.pr_ornum IS NULL AND m.pr_ordate IS NULL 
                AND p.pr_paytype = 'CK' AND DATE(p.pr_paydate) <= NOW()";
                
        $result = $this->db->query($stmt)->row_array();
        
        return $result['pendingpr'];
    }
    
    public function getDefaultDataMain($id) {
        $stmt = "SELECT p.id, m.ao_num, m.ao_cmf, m.ao_adtype, m.ao_payee, m.ao_title, m.ao_add1, m.ao_add2, m.ao_add3, m.ao_tin, m.ao_adtype, m.ao_paytype,
                     m.ao_zip, m.ao_telprefix1, m.ao_telprefix2, m.ao_tel1, m.ao_tel2,
                     m.ao_faxprefix, m.ao_fax, m.ao_celprefix, m.ao_cel, m.ao_cmfvatcode,
                     cmf.cmf_code AS agencycode, cmf.cmf_name AS agencyname, m.ao_amt, m.ao_cmfvatrate, FORMAT(m.ao_amt, 2) AS totalamt
                     -- CONCAT(IFNULL(p.ao_sinum, ''),' ',IFNULL(m.ao_ref, ''),' ',IFNULL(DATE(p.ao_issuefrom), ''),' ',IFNULL(p.ao_width, 0),' x ',IFNULL(p.ao_length,0),' | ') AS part
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                WHERE p.id = '$id'"; 
        #echo "<pre>"; echo $stmt; exit;        
        $result = $this->db->query($stmt)->row_array();   
        
        return $result;
    }
    
    public function getDefaultDataDet($id) {
        /*$stmt = "SELECT DISTINCT GROUP_CONCAT(CONCAT(IF(p.ao_sinum = 0, '', p.ao_sinum),' ',IFNULL(m.ao_ref, ''),' ',IFNULL(DATE(p.ao_issuefrom), ''),' ',IFNULL(p.ao_width, 0),' x ',IFNULL(p.ao_length,0),' | ')) AS part
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                WHERE m.ao_num = (SELECT ao_num FROM ao_p_tm WHERE id = '$id' LIMIT 1)
                GROUP BY p.ao_sinum";*/
        $stmt = "SELECT DISTINCT (CONCAT(IF(p.ao_sinum = 0, '', p.ao_sinum),' ',IFNULL(m.ao_ref, ''),' ',IFNULL(DATE(p.ao_issuefrom), ''),' ',IFNULL(p.ao_width, 0),' x ',IFNULL(p.ao_length,0),' | ')) AS part
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                WHERE p.id = $id";   
                
        #echo "<pre>"; echo $stmt; exit;        
        $result = $this->db->query($stmt)->row_array();   
        
        return $result; 
    }
    
    public function getDefaultDataDetAO($aonum) {
        $stmt = "SELECT GROUP_CONCAT(CONCAT(IF(p.ao_sinum = 0, '', p.ao_sinum),' ',IFNULL(m.ao_ref, ''),' ',IFNULL(DATE(p.ao_issuefrom), ''),' ',IFNULL(p.ao_width, 0),' x ',IFNULL(p.ao_length,0),' | ')) AS part
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                WHERE m.ao_num = $aonum";   
                
                
        $result = $this->db->query($stmt)->row_array();   
        
        return $result; 
    }
    
    public function viewbooking_list($search) {
        
        $conaono = ""; $conpayeecode = ""; $conpayeename = ""; $coninvoiceno = ""; $conissuefrom = ""; $conissueto = ""; 
        $conagencycode = ""; $conagencyname = ""; $conclienttype = ""; $conpono = ""; $conpaytype = "";
        
        if ($search['aono'] != "") {$conaono = "AND m.ao_num = '".$search['aono']."'";}
        if ($search['payeecode'] != "") {$conpayeecode = "AND m.ao_cmf LIKE '".$search['payeecode']."%'";}
        if ($search['payeename'] != "") {$conpayeename = "AND m.ao_payee LIKE '".$search['payeename']."%'";}
        if ($search['invoiceno'] != "") {$coninvoiceno = "AND p.ao_sinum = '".$search['invoiceno']."'";} 
        if ($search['issuefrom'] != "") {$conissuefrom = "AND DATE(p.ao_issuefrom) >= '".$search['issuefrom']."'";} 
        if ($search['issueto'] != "") {$conissueto = "AND DATE(p.ao_issueto) <= '".$search['issueto']."'";} 
        if ($search['agencycode'] != "") {$conagencycode = "AND agnt.cmf_code LIKE '".$search['agencycode']."%'";}   
        if ($search['agencyname'] != "") {$conagencyname = "AND agnt.cmf_name LIKE '".$search['agencyname']."%'";}   
        if ($search['clienttype'] != "") {$conclienttype = "AND p.ao_type = '".$search['clienttype']."'";}   
        if ($search['pono'] != "") {$conpono = "AND m.ao_ref LIKE '".$search['pono']."%'";}  
        if ($search['paytype'] != "") {$conpaytype = "AND m.ao_paytype = '".$search['paytype']."'";}  
        
        $stmt = "SELECT p.id, p.ao_num, p.ao_sinum, DATE(p.ao_issuefrom) AS issuedate, 
                       CONCAT(IFNULL(p.ao_width, 0), ' x ', IFNULL(p.ao_length, 0)) AS size,
                       m.ao_ref, ptype.paytype_name, CONCAT (m.ao_cmf, ' | ', m.ao_payee) AS clientname,
                       CONCAT(agnt.cmf_code, ' | ',agnt.cmf_name) AS agencyname       
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON p.ao_num = m.ao_num
                LEFT OUTER JOIN miscmf AS agnt ON agnt.id = m.ao_amf
                LEFT OUTER JOIN mispaytype AS ptype ON m.ao_paytype = ptype.id 
                WHERE p.is_payed = 0 AND p.status NOT IN ('C', 'F')  AND m.ao_paytype != 6
                $conaono $conpayeecode $conpayeename $coninvoiceno $conissuefrom $conissueto
                $conagencycode $conagencyname $conclienttype $conpono $conpaytype
                LIMIT 500";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
        
    }
    
    public function cancelledOR($ornum) {
        $data['status'] = 'C';
        #$data['status_d'] = date('Y-m-d h:m:s'); 
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $data['edited_d'] = date('Y-m-d h:i:s');  
        
        $this->db->where('or_num', $ornum);
        $this->db->update('or_m_tm', $data);
        
        
        $datap['status'] = 'C';
        #$data['status_d'] = date('Y-m-d h:m:s'); 
        $datap['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $datap['edited_d'] = date('Y-m-d h:i:s');  
        
        $this->db->where('or_num', $ornum);
        $this->db->update('or_p_tm', $datap);
        
        
        $stmt = "SELECT or_num, or_type FROM or_m_tm WHERE or_num = $ornum";
        $res = $this->db->query($stmt)->row_array();
        
        if ($res['or_type'] == 2) {
            $dataaop['ao_ornum'] = null;
            $dataaop['ao_ordate'] = null;
            $dataaop['ao_oramt'] = 0;
            $dataaop['is_payed'] = 0;
            
            $dataaop['ao_sinum'] = 0;
            $dataaop['ao_sidate'] = null;
            $dataaop['is_invoice'] = 0;
            
            $this->db->where('ao_ornum', $ornum);
            $this->db->update('ao_p_tm', $dataaop);
        }
        
        return true;      
    }
    
    public function validateORifApplied($ornum) {
        
        $stmt = "SELECT COUNT(or_num) AS total FROM or_d_tm WHERE or_num = $ornum";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result['total'];
    }
    
    public function validateORifPosted($ornum) {
        
        $stmt = "SELECT or_type, or_num, status FROM or_m_tm WHERE or_num = $ornum";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }

	public function appliedSingleInvoice($id, $mykeyid, $applied, $wtax, $wvat, $ppd, $wtaxp, $wvatp, $ppdp) {
		$countarr = count($id);
		for ($x = 0; $x < $countarr; $x++) {
			$_id = $id[$x];
			$_applied = $applied[$x];
			$_wtax = $wtax[$x];
			$_wvat = $wvat[$x];
			$_ppd = $ppd[$x];

            $data['mykeyid'] = $mykeyid;
			$data['doctype'] = 'SI';
			$data['aoptmid'] = $_id;
			$data['appliedamt'] = mysql_escape_string(str_replace(",","",$_applied)); 
			$data['wvat'] = mysql_escape_string(str_replace(",","",$_wvat)); 
			$data['wvatp'] = mysql_escape_string(str_replace(",","",$wvatp)); 
			$data['wtax'] = mysql_escape_string(str_replace(",","",$_wtax)); 
			$data['wtaxp'] = mysql_escape_string(str_replace(",","",$wtaxp)); 
			$data['ppd'] = mysql_escape_string(str_replace(",","",$_ppd)); 
			$data['ppdp'] = mysql_escape_string(str_replace(",","",$ppdp)); 

			$data['is_update'] = 1;

			$this->db->insert('temp_payment_applied', $data);
		}
	
		return true;
	}

	public function invoicenofind($invoiceno, $mykeyid) {
		$stmt = "select id, (ao_amt - (ao_oramt + ao_dcamt)) as amountdue, date(ao_issuefrom) as issuedate, ao_sinum 
                   from ao_p_tm where ao_sinum = '$invoiceno' AND id NOT IN(select aoptmid from temp_payment_applied where mykeyid='$mykeyid' and is_temp_delete = 0) ";
		
		$result = $this->db->query($stmt)->result_array();

		return $result;
	}

	public function dumpPRPaymentAppliedToORPaymentTyp($prnum, $mykeyid) {
		$stmt = "select (b.ao_amt - (b.ao_oramt + b.ao_dcamt)) as amountdue, a.*
				from pr_d_tm as a 
				inner join ao_p_tm as b on a.pr_docitemid = b.id
				where a.pr_num = $prnum
				order by a.pr_item_id asc";
        #echo "<pre>";
        #echo $stmt; exit;
		$data['mykeyid'] = $mykeyid;
		$data['is_tag'] = 1;
		$result = $this->db->query($stmt)->result_array();
		if (!empty($result)) {
			foreach ($result as $row) {
				if ($row['amountdue'] != 0) {		
					if ($row['pr_assignamt'] > $row['amountdue']){					
					$data['appliedamt'] = $row['amountdue'];	
					} else {
					$data['appliedamt'] = $row['pr_assignamt'];					
					}
                    $data['aoptmid'] = $row['pr_docitemid'];        
					$data['doctype'] = $row['pr_doctype'];		
					$data['wvat'] = $row['pr_assignwvatamt'];
					$data['wvatp'] = $row['pr_wvatpercent'];
					$data['wtax'] = $row['pr_assignwtaxamt'];
					$data['wtaxp'] = $row['pr_wtaxpercent'];
					$data['ppd'] = $row['pr_assignppdamt'];
					$data['ppdp'] = $row['pr_ppdpercent'];
					$data['id'] = $row['pr_item_id'];				
					$data['is_tag'] = 1;

					$this->db->insert('temp_payment_applied', $data);   
				} 				  
			}
		}	
	}

	public function dumpPRPaymentTypeToORPaymentType($prnum, $mykeyid) {
        $prnum = str_pad($prnum,8,00,STR_PAD_LEFT);
		$stmt = "select * from pr_p_tm where pr_num = $prnum order by pr_item_id asc";
        
		$data['mykeyid'] = $mykeyid;
		$data['is_tag'] = 1;
		$result = $this->db->query($stmt)->result_array();

		if (!empty($result)) {
			foreach ($result as $row) {								
				$data['id'] = $row['pr_item_id'];
				$data['type' ] = $row['pr_paytype'];
				$data['bank' ] = $row['pr_paybank'];
				$data['bankbranch' ] = $row['pr_paybranch'];
				$data['checknumber' ] = $row['pr_paynum'];
				$data['checkdate' ] = $row['pr_paydate'];
				$data['creditcard' ] = $row['pr_creditcard'];
				$data['creditcardnumber' ] = $row['pr_creditcardnumber'];
				$data['remarks' ] = $row['pr_remarks'];
				$data['authorizationno' ] = $row['pr_authorizationno'];
				$data['expirydate' ] = $row['pr_expirydate'];            
				$data['amount' ] = $row['pr_amt'];  
				$data['is_tag'] = 1;

				$this->db->insert('temp_payment_types', $data);     
			}
		}
	}

	public function findPRLookUpResult($find) { 
	$con_ornumber = ''; $con_ordate = ''; $con_orpayeecode = '';
	$con_orpayeename = ''; $con_orcollectorcashier = ''; $con_orbank = ''; $con_orbranch = ''; $con_orparticulars = ''; $con_amount = '';     

	if ($find['prdatefrom'] != "" || $find['prdateto'] != "") {
		 $con_dateby = "";
		 $con_ordate = "AND (DATE(pr_m_tm.pr_date) >= '".$find['prdatefrom']."' AND DATE(pr_m_tm.pr_date) <= '".$find['prdateto']."')";   
	} 
	  
	if (!empty($find['prnumber'])) { $con_ornumber = "AND pr_m_tm.pr_num = '".$find['prnumber']."'"; }        
	if (!empty($find['prpayeecode'])) { $con_orpayeecode = "AND pr_m_tm.pr_cmf LIKE '".$find['prpayeecode']."%'"; }
	if (!empty($find['prpayeename'])) { $con_orpayeename = "AND pr_m_tm.pr_payee LIKE '".$find['prpayeename']."%'"; }
	if (!empty($find['prcollectorcashier'])) { $con_orcollectorcashier = "AND pr_m_tm.pr_ccf = '".$find['prcollectorcashier']."'"; }
	if (!empty($find['prbank'])) { $con_orbank = "AND pr_m_tm.pr_bnacc = '".$find['prbank']."'"; }
	if (!empty($find['prbranch'])) { $con_orbranch = "AND pr_m_tm.pr_branch = '".$find['prbranch']."'"; }
	if (!empty($find['prparticulars'])) { $con_orparticulars = "AND pr_m_tm.pr_part LIKE '".$find['prparticulars']."%'"; }      
	if (!empty($find['pramount'])) { $con_amount = "AND pr_m_tm.pr_amt = '".$find['pramount']."'"; }  

	$stmt = "SELECT pr_m_tm.pr_num, DATE(pr_date) AS pr_date, pr_m_tm.pr_cmf, pr_m_tm.pr_amf, pr_payee, pr_amt,
			   pr_m_tm.pr_ccf, pr_m_tm.pr_bnacc, pr_m_tm.pr_branch, pr_m_tm.pr_part, pr_m_tm.pr_comment,
			   CONCAT(users.firstname, ' ',SUBSTRING(users.middlename,1,1), '. ',users.lastname) AS ccf,
			   misbmf.bmf_name AS bank, misbbf.bbf_bnch AS branch 
		 FROM pr_m_tm
		 LEFT OUTER JOIN users ON users.id = pr_m_tm.pr_ccf       
		 LEFT OUTER JOIN misbmf ON misbmf.id = pr_m_tm.pr_bnacc
		 LEFT OUTER JOIN misbbf ON misbbf.id = pr_m_tm.pr_branch
		 WHERE pr_m_tm.pr_num IS NOT NULL AND pr_ornum IS NULL $con_ordate $con_ornumber $con_orpayeecode $con_orpayeename 
		 $con_orcollectorcashier $con_orbank $con_orbranch $con_orparticulars $con_amount ORDER BY pr_m_tm.pr_num ASC LIMIT 500";  

	$result = $this->db->query($stmt)->result_array();

	return $result;                

	}

	public function getOldAppliedAmt($docitemid, $item_id) {
		$stmt = "select or_assignamt as ortotalamt from or_d_tm where or_docitemid = '$docitemid' and or_item_id = '$item_id'";
		
		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function getORDataMain($ornum) {
		$stmt = "select or_date, or_artype, or_argroup from or_m_tm where or_num = '$ornum'";
		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function saveUpdatePaymentApplied($ornum, $mykeyid, $vatrate, $data) {

		$stmt = "SELECT t.doctype, t.aoptmid, t.appliedamt, t.wvat, t.wvatp, t.wtax, t.wtaxp, t.ppd, t.ppdp, t.bal,
                       p.ao_prod, p.ao_prodissue, p.ao_issuefrom, p.ao_issueto, p.ao_num,
                       p.ao_part_billing, p.ao_adtyperate_code, p.ao_width, p.ao_length, 
                       p.ao_cmfvatcode, p.ao_cmfvatrate, p.ao_date, p.ao_amt,
				   IFNULL(p.ao_amt, 0.00) - (IFNULL(p.ao_oramt, 0.00) + IFNULL(p.ao_dcamt, 0.00)) as amountdue, 
				   t.is_tag, t.is_update, t.is_temp_delete, t.id	
                FROM temp_payment_applied AS t
                LEFT OUTER JOIN ao_p_tm AS p ON t.aoptmid = p.id
                WHERE t.mykeyid = '$mykeyid' AND t.appliedamt != 0 AND t.is_update = 1"; 
                
                #echo "<pre>"; echo $stmt; exit;
		$result = $this->db->query($stmt)->result_array();
        
		if (!empty($result)) {
			foreach ($result as $result) {
				if ($result['is_tag'] == 1 && $result['is_temp_delete'] == 1) {
                    if ($result['doctype'] == "SI"){
					    $this->db->where(array('or_num' => $ornum, 'or_item_id' => $result['id']));
					    $this->db->delete('or_d_tm');
					    $stmtupdateaoptm = "select sum(or_assignamt) as totaloramt from or_d_tm where or_docitemid = '".$result['aoptmid']."'";
					    $stmtgetornumordate = "select or_num, or_date from or_d_tm where or_docitemid = '".$result['aoptmid']."' order by edited_d desc limit 1";
					    $resultaoptm = $this->db->query($stmtupdateaoptm)->row_array();
					    $resultgetaoptm = $this->db->query($stmtgetornumordate)->row_array();
					    $data_aoptm['ao_ornum'] = $resultgetaoptm['or_num'];
					    $data_aoptm['ao_ordate'] = $resultgetaoptm['or_date'];
					    $data_aoptm['ao_oramt'] = $resultaoptm['totaloramt'];				
					    $data_aoptm['is_payed'] = 0;						 
					    $this->db->where('id', $result['aoptmid']);
					    $this->db->update('ao_p_tm', $data_aoptm); 
                        //echo "delete si"; 
                    } else {
                        $this->db->where(array('or_num' => $ornum, 'or_item_id' => $result['id']));
                        $this->db->delete('or_d_tm');
                        $stmtdm = "SELECT SUM(xall.total) AS total, id
                                FROM (
                                SELECT SUM(dc_assignamt) AS total, dc_docitemid AS id  
                                FROM dc_d_tm
                                WHERE dc_doctype = 'DM' AND dc_docitemid = '".$result['aoptmid']."' GROUP BY dc_docitemid
                                UNION                                
                                SELECT SUM(or_assignamt) AS total, or_docitemid AS id 
                                FROM or_d_tm 
                                WHERE or_doctype = 'DM' AND or_docitemid = '".$result['aoptmid']."' GROUP BY or_docitemid) AS xall
                                GROUP BY xall.id"; 
                                
                        $resultdm = $this->db->query($stmtdm)->row_array();
                         
                        //$updatedm['dc_amt'] = $resultdm['total'];
                        $updatedm['dc_assignamt'] = $resultdm['total'];
                        #print_r2($updatedm); exit;
                        $this->db->where(array('dc_num' => $result['aoptmid'], 'dc_type' => 'D'));
                        $this->db->update('dc_m_tm', $updatedm);  
                    }
                    
                    
				} else if ($result['is_tag'] == 1 && $result['is_update'] == 1) {
					$totalor = $this->getOldAppliedAmt($result['aoptmid'], $result['id']);						
					$assign = $result['appliedamt'];                
					$wtax = $result['wtax'];                
					$wvat = $result['wvat'];                
					$ppd = $result['ppd'];                
                    //$g = ($assign + $wtax + $wvat + $ppd) / (1 + ($vatrate/100));  

                    //$g = ($assign + $wtax + $wvat + $ppd) / (1 + ($result['ao_cmfvatrate']/100));                
					$g = ($assign) / (1 + ($result['ao_cmfvatrate']/100));                
                    //$vatamt = round($g * ($vatrate/100), 2);      
					$vatamt = round($g * ($result['ao_cmfvatrate']/100), 2);      
					$grossamt = round($g, 2);    
                    if ($row['doctype'] == "DM")  {
                        $vatamt = 0;
                        $grossamt = $assign;
                    }
					$bal = ($result['amountdue'] + $totalor['ortotalamt']) - ($result['appliedamt']); 
					$dataupdate['or_docamt'] = $result['appliedamt'];
					$dataupdate['or_docbal'] = $bal;
					$dataupdate['or_assignamt'] = $assign;
					$dataupdate['or_assigngrossamt'] = $grossamt;
					$dataupdate['or_assignvatamt'] = $vatamt;
					$dataupdate['or_assignwtaxamt'] = $result['wtax'];
					$dataupdate['or_assignwvatamt'] = $result['wvat'];
					$dataupdate['or_assignppdamt'] = $result['ppd']; 
					$dataupdate['or_cmfvatcode'] = $result['ao_cmfvatcode'];
					$dataupdate['or_cmfvatrate'] = $result['ao_cmfvatrate'];
					$dataupdate['or_wtaxpercent'] = $result['wtaxp'];
					$dataupdate['or_wvatpercent'] = $result['wvatp'];
					$dataupdate['or_ppdpercent'] = $result['ppdp'];
					$dataupdate['edited_n'] = $this->session->userdata('authsess')->sess_id;
					$dataupdate['edited_d'] = DATE('Y-m-d h:i:s');	
                    #print_r2($dataupdate); exit;
					$this->db->where(array('or_num' => $ornum, 'or_item_id' => $result['id']));
          			$this->db->update('or_d_tm', $dataupdate);

                    if ($result['doctype'] == "SI") {
					    $stmtupdateaoptm = "select sum(or_assignamt) as totaloramt from or_d_tm where or_docitemid = '".$result['aoptmid']."'";
					    $stmtgetornumordate = "select or_num, or_date from or_d_tm where or_docitemid = '".$result['aoptmid']."' order by edited_d desc limit 1";
					    $resultaoptm = $this->db->query($stmtupdateaoptm)->row_array();
					    $resultgetaoptm = $this->db->query($stmtgetornumordate)->row_array();
					    $data_aoptm['ao_ornum'] = $resultgetaoptm['or_num'];
					    $data_aoptm['ao_ordate'] = $resultgetaoptm['or_date'];
					    $data_aoptm['ao_oramt'] = $resultaoptm['totaloramt'];
					    $this->db->where('id', $result['aoptmid']);
					    $this->db->update('ao_p_tm', $data_aoptm); 

					    $updatepayedstmt = "select (ao_amt - (ao_oramt + ao_dcamt)) as totalpayed from ao_p_tm where id = '".$result['aoptmid']."'";
					    $ispayed = $this->db->query($updatepayedstmt)->row_array();					
					    $payed['is_payed'] = 0;	
					    if ($ispayed['totalpayed'] <= 0) {
					    $payed['is_payed'] = 1;
					    } 
					    $this->db->where('id', $result['aoptmid']);
					    $this->db->update('ao_p_tm', $payed); 
                    } else {
                        $stmtdm = "SELECT SUM(xall.total) AS total, id
                                FROM (
                                SELECT SUM(dc_assignamt) AS total, dc_docitemid AS id  
                                FROM dc_d_tm
                                WHERE dc_doctype = 'DM' AND dc_docitemid = '".$result['aoptmid']."' GROUP BY dc_docitemid
                                UNION                                
                                SELECT SUM(or_assignamt) AS total, or_docitemid AS id 
                                FROM or_d_tm 
                                WHERE or_doctype = 'DM' AND or_docitemid = '".$result['aoptmid']."' GROUP BY or_docitemid) AS xall
                                GROUP BY xall.id"; 
                                
                        $resultdm = $this->db->query($stmtdm)->row_array();
                        
                        $updatedm['dc_assignamt'] = $resultdm['total'];
                        #print_r2($updatedm);  echo "sad"; exit;
                        $this->db->where(array('dc_num' => $result['aoptmid'], 'dc_type' => 'D'));
                        $this->db->update('dc_m_tm', $updatedm);    
                    }

				} else if ($result['is_temp_delete'] == 0) {					
					$assign = $result['appliedamt'];                
					$wtax = $result['wtax'];                
					$wvat = $result['wvat'];                
					$ppd = $result['ppd'];                
                    //$g = ($assign + $wtax + $wvat + $ppd) / (1 + ($result['ao_cmfvatrate']/100));                
                    $g = ($assign) / (1 + ($result['ao_cmfvatrate']/100));                
					//$g = ($assign + $wtax + $wvat + $ppd) / (1 + ($vatrate/100));                
                    //$vatamt = round($g * ($vatrate/100), 2);      
					$vatamt = round($g * ($result['ao_cmfvatrate']/100), 2);      
					$grossamt = round($g, 2);     
                    if ($row['doctype'] == "DM")  {
                        $vatamt = 0;
                        $grossamt = $assign;
                    }
					$bal = $result['amountdue'] - $result['appliedamt']; 
					$prdata = $this->getORDataMain($ornum);
				    $stmt = "select ifnull(max(or_item_id), 0) + 1 as maxid from or_d_tm where or_num = '$ornum'";
					$maxid = $this->db->query($stmt)->row_array();
					$insertdata = array('or_num' => $ornum,
									'or_item_id' => $maxid['maxid'],
									'or_date' => $prdata['or_date'],
									'or_artype' => $prdata['or_artype'],
									'or_argroup' => $prdata['or_argroup'],
									'or_prod' => $result['ao_prod'],
									'or_prodissue' => $result['ao_prodissue'],
									'or_issuefrom' => $result['ao_issuefrom'],
									'or_issueto' => $result['ao_issueto'],
									'or_doctype' => $result['doctype'],
									'or_docnum' => $result['ao_num'],
									'or_docamt' => $result['appliedamt'],
									'or_docitemid' => $result['aoptmid'],
									'or_docpart' => $result['ao_part_billing'],
									'or_adtype' => $result['ao_adtyperate_code'],
									'or_width' => $result['ao_width'],
									'or_length' => $result['ao_length'],
									'or_docbal' => $bal,
									'or_assignamt' => $assign,
									'or_assigngrossamt' => $grossamt,
									'or_assignvatamt' => $vatamt,

									'or_assignwtaxamt' => $result['wtax'],
									'or_assignwvatamt' => $result['wvat'],
									'or_assignppdamt' =>$result['ppd'], 
									'or_cmfvatcode' => $result['ao_cmfvatcode'],

									'or_cmfvatrate' => $result['ao_cmfvatrate'],
									'or_wtaxpercent' => $result['wtaxp'],
									'or_wvatpercent' => $result['wvatp'],
									'or_ppdpercent' => $result['ppdp'],

									'user_n' => $this->session->userdata('authsess')->sess_id,
									'edited_n' => $this->session->userdata('authsess')->sess_id,
									'edited_d' => DATE('Y-m-d h:i:s'));
					$this->db->insert('or_d_tm', $insertdata);	

                    if ($result['doctype'] == "SI") {
					    $stmtupdateaoptm = "select sum(or_assignamt) as totaloramt from or_d_tm where or_docitemid = '".$result['aoptmid']."'";
					    $stmtgetornumordate = "select or_num, or_date from or_d_tm where or_docitemid = '".$result['aoptmid']."'order by or_date, edited_d desc limit 1";
					    $resultaoptm = $this->db->query($stmtupdateaoptm)->row_array();
					    $resultgetaoptm = $this->db->query($stmtgetornumordate)->row_array();
					    $data_aoptm['ao_ornum'] = $resultgetaoptm['or_num'];
					    $data_aoptm['ao_ordate'] = $resultgetaoptm['or_date'];
					    $data_aoptm['ao_oramt'] = $resultaoptm['totaloramt'];
					    $this->db->where('id', $result['aoptmid']);
					    $this->db->update('ao_p_tm', $data_aoptm); 

					    $updatepayedstmt = "select (ao_amt - (ao_oramt + ao_dcamt)) as totalpayed from ao_p_tm where id = '".$result['aoptmid']."'";
					    $ispayed = $this->db->query($updatepayedstmt)->row_array();					
					    $payed['is_payed'] = 0;	
					    if ($ispayed['totalpayed'] <= 0) {
					    $payed['is_payed'] = 1;
					    } 
					    $this->db->where('id', $result['aoptmid']);
					    $this->db->update('ao_p_tm', $payed); 
                    } else {
                        $stmtdm = "SELECT SUM(xall.total) AS total, id
                                FROM (
                                SELECT SUM(dc_assignamt) AS total, dc_docitemid AS id  
                                FROM dc_d_tm
                                WHERE dc_doctype = 'DM' AND dc_docitemid = '".$result['aoptmid']."' GROUP BY dc_docitemid
                                UNION                                
                                SELECT SUM(or_assignamt) AS total, or_docitemid AS id 
                                FROM or_d_tm 
                                WHERE or_doctype = 'DM' AND or_docitemid = '".$result['aoptmid']."' GROUP BY or_docitemid) AS xall
                                GROUP BY xall.id"; 
                                
                        $resultdm = $this->db->query($stmtdm)->row_array();
                        
                        $updatedm['dc_assignamt'] = $resultdm['total'];
                        #print_r2($updatedm); exit;
                        $this->db->where(array('dc_num' => $result['aoptmid'], 'dc_type' => 'D'));
                        $this->db->update('dc_m_tm', $updatedm);
                    }				
				}
			}
		}

	}


	public function saveupdateORPaymentMain($ornum, $data) {			
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        	$data['edited_d'] = DATE('Y-m-d h:i:s');
        
        	$this->db->where('or_num', $ornum);
        	$this->db->update('or_m_tm', $data); 
        	return true;		
	}

	public function saveUpdatePaymentTypes($ornum, $mykeyid) {
		$stmt = "select * from temp_payment_types where mykeyid='$mykeyid' and is_update = 1 order by id asc"; 
		$result = $this->db->query($stmt)->result_array();

		$stmtpaymenttypedata = "select or_date, or_artype, or_argroup from or_m_tm where or_num='$ornum'";
		$paymenttypedata = $this->db->query($stmtpaymenttypedata)->row_array();

		if (!empty($result)) {
			foreach ($result as $result) {
				if ($result['is_tag'] == 1 && $result['is_temp_delete'] == 1) {
					$this->db->where(array('or_num' => $ornum, 'or_item_id' => $result['id']));
					$this->db->delete('or_p_tm');
				} else if ($result['is_temp_delete'] == 0) {
					$stmt = "select ifnull(max(or_item_id), 0) + 1 as maxid from or_p_tm where or_num = '$ornum'";
					$maxid = $this->db->query($stmt)->row_array();
					$data['or_num'] = $ornum;
					$data['or_item_id'] = $maxid['maxid'];
					$data['or_date'] = $paymenttypedata['or_date'];
					$data['or_artype'] = $paymenttypedata['or_artype'];
					$data['or_argroup'] = $paymenttypedata['or_argroup'];
					$data['or_paytype'] = $result['type'];
					$data['or_paybank'] = $result['bank'];
					$data['or_paybranch'] = $result['bankbranch'];
					$data['or_paynum'] = $result['checknumber'];         
					$data['or_paydate'] = $result['checkdate'];         
					$data['or_creditcard'] = $result['creditcard'];
					$data['or_creditcardnumber'] = $result['creditcardnumber'];
					$data['or_remarks'] = $result['remarks'];
					$data['or_authorizationno'] = $result['authorizationno'];
					$data['or_expirydate'] = $result['expirydate'];            
					$data['or_amt'] = $result['amount'];            
					$data['user_n'] = $this->session->userdata('authsess')->sess_id;                                              
					$data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
					$data['edited_d'] = DATE('Y-m-d h:i:s');                                  										
					$this->db->insert('or_p_tm', $data);	
				}
			}
		}

		return true;
	}


	public function retrieveRevenuBooking($aonum) {
		$stmt = "SELECT a.ao_num, a.ao_adtype, a.ao_payee, a.ao_title, a.ao_add1, a.ao_add2, a.ao_add3, a.ao_tin, a.ao_adtype,
                     a.ao_zip, a.ao_telprefix1, a.ao_telprefix2, a.ao_tel1, a.ao_tel2,
                     a.ao_faxprefix, a.ao_fax, a.ao_celprefix, a.ao_cel, a.ao_amt, FORMAT(a.ao_vatsales, 2) AS ao_vatsales, 
                     FORMAT(IFNULL(a.ao_vatexempt, '0'), 2) AS ao_vatexempt, FORMAT(a.ao_vatzero, 2) AS ao_vatzero,
                     a.ao_cmfvatcode, FORMAT(a.ao_vatamt, 2) AS ao_vatamt, a.ao_cmfvatrate, 
                     FORMAT(IFNULL(a.ao_wtaxamt, '0'), 2) AS ao_wtaxamt, FORMAT(IFNULL(a.ao_wtaxpercent, '0'), 2) AS ao_wtaxpercent,
                     FORMAT(IFNULL(a.ao_wvatamt, '0'), 2) AS ao_wvatamt, FORMAT(IFNULL(a.ao_wvatpercent, '0'), 2) AS ao_wvatpercent,     
                     FORMAT(IFNULL(a.ao_ppdamt, '0'), 2) AS ao_ppdamt, FORMAT(IFNULL(a.ao_ppdpercent, '0'), 2) AS ao_ppdpercent,     
                     CASE a.ao_paytype
                        WHEN '1' THEN 'X'
                        WHEN '2' THEN 'X'
                        WHEN '3' THEN 'CH'
                        WHEN '4' THEN 'CC'
                        WHEN '5' THEN 'CK'
                        WHEN '6' THEN 'X'
                     END ao_paytype, 
                     a.ao_cardholder, a.ao_cardtype, a.ao_cardnumber, a.ao_authorisationno, a.ao_expirydate, a.ao_amt
             FROM ao_m_tm AS a 
             INNER JOIN ao_p_tm AS b ON a.ao_num = b.ao_num
             WHERE (a.ao_paytype != '1' AND a.ao_paytype != '2' AND a.ao_paytype != '6') AND a.status = 'A' AND a.ao_num = '$aonum' AND b.is_payed = 0 AND b.is_invoice = 0 AND b.ao_ornum IS NULL OR b.ao_ornum = 0";
		$result = $this->db->query($stmt)->row_array();
				    
		return $result;
	}


	public function removeTempORPaymentApplied($id,$mykeyid) {
		$this->db->where(array('aoptmid' => $id, 'mykeyid' => $mykeyid));
		$data['is_temp_delete'] = 1;
		$data['is_update'] = 1;
		$this->db->update('temp_payment_applied', $data);
		return true;	
	}

	public function dumpORPaymentApplied($ornum, $mykeyid) {
		$stmt = "select * from or_d_tm where or_num = $ornum order by or_item_id ASC";      
		$data['mykeyid'] = $mykeyid;
		$data['is_tag'] = 1;
		$result = $this->db->query($stmt)->result_array();
		if (!empty($result)) {
			foreach ($result as $row) {
                $data['aoptmid'] = $row['or_docitemid'];        
				$data['doctype'] = $row['or_doctype'];		
				$data['appliedamt'] = $row['or_assignamt'];	
				$data['wvat'] = $row['or_assignwvatamt'];
				$data['wvatp'] = $row['or_wvatpercent'];
				$data['wtax'] = $row['or_assignwtaxamt'];
				$data['wtaxp'] = $row['or_wtaxpercent'];
				$data['ppd'] = $row['or_assignppdamt'];
				$data['ppdp'] = $row['or_ppdpercent'];
                $data['id'] = $row['or_item_id'];                
				$data['lastapplieddate'] = $row['edited_d'];				
                $data['is_tag'] = 1;
				$data['status'] = $row['status'];

				$this->db->insert('temp_payment_applied', $data);     
			}
		}	
	}

	public function dumpORPaymentType($ornum, $mykeyid) {

		$stmt = "select * from or_p_tm where or_num ='$ornum' order by or_item_id asc";
		$data['mykeyid'] = $mykeyid;
		$data['is_tag'] = 1;
		$result = $this->db->query($stmt)->result_array();
		if (!empty($result)) {
			foreach ($result as $row) {								
				$data['id'] = $row['or_item_id'];
				$data['type' ] = $row['or_paytype'];
				$data['bank' ] = $row['or_paybank'];
				$data['bankbranch' ] = $row['or_paybranch'];
				$data['checknumber' ] = $row['or_paynum'];
				$data['checkdate' ] = $row['or_paydate'];
				$data['creditcard' ] = $row['or_creditcard'];
				$data['creditcardnumber' ] = $row['or_creditcardnumber'];
				$data['remarks' ] = $row['or_remarks'];
				$data['authorizationno' ] = $row['or_authorizationno'];
				$data['expirydate' ] = $row['or_expirydate'];            
				$data['amount' ] = $row['or_amt'];  
				$data['is_tag'] = 1;

				$this->db->insert('temp_payment_types', $data);     
			}
		}
	}

	public function getORMainData($ornum) {
		//$ornum = str_pad($ornum, 8, "0", STR_PAD_LEFT);  
		$stmt = "select *, IF(TRIM(or_prdate) = '0000-00-00 00:00:00', '', DATE(or_prdate)) AS prdate from or_m_tm where or_num = $ornum";

		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function findORLookUpResult($find) {
	$con_ornumber = ''; $con_ordate = ''; $con_orpayeecode = '';
	$con_orpayeename = ''; $con_orcollectorcashier = ''; $con_orbank = ''; $con_orbranch = ''; $con_orparticulars = ''; $con_amount = ''; $con_checkno = '';     

	if ($find['ordatefrom'] != "" || $find['ordateto'] != "") {
		 $con_dateby = "";
		 $con_ordate = "AND (DATE(or_m_tm.or_date) >= '".$find['ordatefrom']."' AND DATE(or_m_tm.or_date) <= '".$find['ordateto']."')";   
	} 
	  
	if (!empty($find['ornumber'])) { $con_ornumber = "AND or_m_tm.or_num = ".TRIM($find['ornumber']).""; }        
	if (!empty($find['orpayeecode'])) { $con_orpayeecode = "AND or_m_tm.or_cmf LIKE '".$find['orpayeecode']."%'"; }
	if (!empty($find['orpayeename'])) { $con_orpayeename = "AND or_m_tm.or_payee LIKE '".$find['orpayeename']."%'"; }
	if (!empty($find['orcollectorcashier'])) { $con_orcollectorcashier = "AND or_m_tm.or_ccf = '".$find['orcollectorcashier']."'"; }
	if (!empty($find['orbank'])) { $con_orbank = "AND or_m_tm.or_bnacc = '".$find['orbank']."'"; }
	if (!empty($find['orbranch'])) { $con_orbranch = "AND or_m_tm.or_branch = '".$find['orbranch']."'"; }
	if (!empty($find['orparticulars'])) { $con_orparticulars = "AND or_m_tm.or_part LIKE '".$find['orparticulars']."%'"; }      
	if (!empty($find['oramount'])) { $con_amount = "AND or_m_tm.or_amt = '".$find['oramount']."'"; }
	if (!empty($find['orcheckno'])) { $con_checkno = "AND or_p_tm.or_paynum = '".$find['orcheckno']."'"; }    

	$stmt = "SELECT distinct or_m_tm.or_num, DATE(or_m_tm.or_date) AS or_date, or_m_tm.or_cmf, or_m_tm.or_amf, or_m_tm.or_payee, or_m_tm.or_amt,
			   or_m_tm.or_ccf, or_m_tm.or_bnacc, or_m_tm.or_branch, or_m_tm.or_part, or_m_tm.or_comment,
			   CONCAT(users.firstname, ' ',SUBSTRING(users.middlename,1,1), '. ',users.lastname) AS ccf,
			   misbmf.bmf_name AS bank, misbbf.bbf_bnch AS branch, or_m_tm.or_assignamt
		 FROM or_m_tm
		 LEFT OUTER JOIN or_p_tm on or_p_tm.or_num = or_m_tm.or_num
		 LEFT OUTER JOIN users ON users.id = or_m_tm.or_ccf       
		 LEFT OUTER JOIN misbmf ON misbmf.id = or_m_tm.or_bnacc
		 LEFT OUTER JOIN misbbf ON misbbf.id = or_m_tm.or_branch
		 WHERE or_m_tm.or_num IS NOT NULL $con_ordate $con_ornumber $con_orpayeecode $con_orpayeename $con_checkno 
		 $con_orcollectorcashier $con_orbank $con_orbranch $con_orparticulars $con_amount ORDER BY or_m_tm.or_num ASC LIMIT 500";  
    #echo "<pre>"; echo $stmt; exit;
	$result = $this->db->query($stmt)->result_array();

	return $result;                

	}
	
	public function getTotalORAmount($aoptmid)	{
		$stmt = "select sum(or_assignamt) as ortotalamt from or_d_tm where or_docitemid = '$aoptmid'";
		
		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function saveORPaymentApplied($mykeyid, $vatrate, $data) {

		$stmt = "SELECT DISTINCT t.doctype, t.aoptmid, t.appliedamt, t.wvat, t.wvatp, t.wtax, t.wtaxp, t.ppd, t.ppdp, t.bal,
                       p.ao_prod, p.ao_prodissue, p.ao_issuefrom, p.ao_issueto, p.ao_num,
                       p.ao_part_billing, p.ao_adtyperate_code, p.ao_width, p.ao_length, 
                       p.ao_cmfvatcode, p.ao_cmfvatrate, p.ao_date, p.ao_amt,
				   IFNULL(p.ao_amt, 0.00) - (IFNULL(p.ao_oramt, 0.00) + IFNULL(p.ao_dcamt, 0.00)) as amountdue	
                FROM temp_payment_applied AS t
                LEFT OUTER JOIN ao_p_tm AS p ON t.aoptmid = p.id
                WHERE t.mykeyid = '$mykeyid' AND t.appliedamt != 0"; 

		$result = $this->db->query($stmt)->result_array();
        
		$bal = 0;
		if (!empty($result)) {
			$x = 1;
			foreach ($result as $row) {
				$totalpr = $this->getTotalORAmount($row['aoptmid']);
				$assign = $row['appliedamt'];                
				$wtax = $row['wtax'];                
				$wvat = $row['wvat'];                
				$ppd = $row['ppd'];                
                //$g = ($assign + $wtax + $wvat + $ppd) / (1 + ($vatrate/100));                
				$g = ($assign) / (1 + ($row['ao_cmfvatrate']/100));                
				$vatamt = round($g * ($row['ao_cmfvatrate']/100), 2);      
				$grossamt = round($g, 2);    
                if ($row['doctype'] == "DM")  {
                    $vatamt = 0;
                    $grossamt = $assign;
                }
				$bal = $row['amountdue'] - $row['appliedamt']; 
				$batchinsert = array( 'or_num' => $data['or_num'],
		                                  'or_item_id' => $x,
		                                  'or_date' => $data['or_date'],
		                                  'or_artype' => $data['or_artype'],
								          'or_argroup' => $data['or_argroup'],
		                                  'or_prod' => $row['ao_prod'],
		                                  'or_prodissue' => $row['ao_prodissue'],
		                                  'or_issuefrom' => $row['ao_issuefrom'],
		                                  'or_issueto' => $row['ao_issueto'],
		                                  'or_doctype' => $row['doctype'],
		                                  'or_docnum' => $row['ao_num'],
		                                  'or_docamt' => $row['appliedamt'],
		                                  'or_docitemid' => $row['aoptmid'],
		                                  'or_docpart' => $row['ao_part_billing'],
		                                  'or_adtype' => $row['ao_adtyperate_code'],
		                                  'or_width' => $row['ao_width'],
		                                  'or_length' => $row['ao_length'],
		                                  'or_docbal' => $bal,
		                                  'or_assignamt' => $assign,
		                                  'or_assigngrossamt' => $grossamt,
		                                  'or_assignvatamt' => $vatamt,
		                                  'or_assignwtaxamt' => $row['wtax'],
		                                  'or_assignwvatamt' => $row['wvat'],
		                                  'or_assignppdamt' =>$row['ppd'], 
		                                  'or_cmfvatcode' => $row['ao_cmfvatcode'],
		                                  'or_cmfvatrate' => $row['ao_cmfvatrate'],
		                                  'or_wtaxpercent' => $row['wtaxp'],
		                                  'or_wvatpercent' => $row['wvatp'],
		                                  'or_ppdpercent' => $row['ppdp'],
		                                  'user_n' => $this->session->userdata('authsess')->sess_id,
		                                  'edited_n' => $this->session->userdata('authsess')->sess_id,
		                                  'edited_d' => DATE('Y-m-d h:i:s'));                 
				$this->db->insert('or_d_tm', $batchinsert);   
				if ($row["doctype"] == "SI") {
                    $stmtupdateaoptm = "select sum(or_assignamt) as totaloramt from or_d_tm where or_docitemid = '".$row['aoptmid']."'";
				    $stmtgetornumordate = "select or_num, or_date from or_d_tm where or_docitemid = '".$row['aoptmid']."' order by or_date, edited_d desc limit 1";
				    $resultaoptm = $this->db->query($stmtupdateaoptm)->row_array();
				    $resultgetaoptm = $this->db->query($stmtgetornumordate)->row_array();
				    $data_aoptm['ao_ornum'] = $resultgetaoptm['or_num'];
				    $data_aoptm['ao_ordate'] = $resultgetaoptm['or_date'];
				    $data_aoptm['ao_oramt'] = $resultaoptm['totaloramt'];
				    $this->db->where('id', $row['aoptmid']);
				    $this->db->update('ao_p_tm', $data_aoptm); 
				    $updatepayedstmt = "select (ao_amt - (ao_oramt + ao_dcamt)) as totalpayed from ao_p_tm where id = '".$row['aoptmid']."'";
				    $ispayed = $this->db->query($updatepayedstmt)->row_array();					
				    $payed['is_payed'] = 0;	

				    if ($ispayed['totalpayed'] <= 0) {
				    $payed['is_payed'] = 1;
				    } 			
				    $this->db->where('id', $row['aoptmid']);
				    $this->db->update('ao_p_tm', $payed); 
                } else {
                    $stmtdm = "SELECT SUM(xall.total) AS total, id
                                FROM (
                                SELECT SUM(dc_assignamt) AS total, dc_docitemid AS id  
                                FROM dc_d_tm
                                WHERE dc_doctype = 'DM' AND dc_docitemid = '".$row['aoptmid']."' GROUP BY dc_docitemid
                                UNION                                
                                SELECT SUM(or_assignamt) AS total, or_docitemid AS id 
                                FROM or_d_tm 
                                WHERE or_doctype = 'DM' AND or_docitemid = '".$row['aoptmid']."' GROUP BY or_docitemid) AS xall
                                GROUP BY xall.id"; 
                                
                    $resultdm = $this->db->query($stmtdm)->row_array();
                    
                    $updatedm['dc_assignamt'] = $resultdm['total'];
                    
                    $this->db->where(array('dc_num' => $row['aoptmid'], 'dc_type' => 'D'));
                    $this->db->update('dc_m_tm', $updatedm);
                    
                }
				$x += 1;
			}				
		}
	}

	public function saveORPaymentType($mykeyid, $paymenttypedata) {
		$stmt = "select mykeyid, id, `type`, bank, bankbranch, checknumber,
					 checkdate, creditcard, creditcardnumber, remarks,
					 authorizationno, expirydate, amount
			    from temp_payment_types where mykeyid = '$mykeyid' AND is_temp_delete = 0";

		$result = $this->db->query($stmt)->result_array();
		$x = 1;
		if (!empty($result)) {
			foreach ($result as $result) {
				$data['or_num'] = $paymenttypedata['or_num'];
				$data['or_item_id'] = $x;
				$data['or_date'] = $paymenttypedata['or_date'];
				$data['or_artype'] = $paymenttypedata['or_artype'];
				$data['or_argroup'] = $paymenttypedata['or_argroup'];
				$data['or_paytype'] = $result['type'];
				$data['or_paybank'] = $result['bank'];
				$data['or_paybranch'] = $result['bankbranch'];

				$data['or_paynum'] = $result['checknumber'];         
				$data['or_paydate'] = $result['checkdate'];         

				$data['or_creditcard'] = $result['creditcard'];
				$data['or_creditcardnumber'] = $result['creditcardnumber'];


				$data['or_remarks'] = $result['remarks'];
				$data['or_authorizationno'] = $result['authorizationno'];
				$data['or_expirydate'] = $result['expirydate'];            

				$data['or_amt'] = $result['amount'];            

				$data['user_n'] = $this->session->userdata('authsess')->sess_id;                                              
				$data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
				$data['edited_d'] = DATE('Y-m-d h:i:s');                                       
				$x += 1;				
				$this->db->insert('or_p_tm', $data);	
			}
		}
	}

	public function saveORPaymentMain($data) {
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;                                              
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
		$data['edited_d'] = DATE('Y-m-d h:i:s'); 
		$this->db->insert('or_m_tm', $data);
		if ($data['or_prnum'] != "") {
			$updatepr['pr_ornum'] = $data['or_num'];	
			$updatepr['pr_ordate'] = $data['or_date'];				
			$this->db->update('pr_m_tm', $updatepr, array('pr_num' => $data['or_prnum']));
		}

		return true;
	}

	public function getAppliedDataTemp($id, $mykeyid) {
		$stmt = "SELECT ao_p_tm.id, ao_p_tm.ao_num, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom ,  DATE(ao_p_tm.ao_sidate) AS ao_sidate , 
                       ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_name,
                       ao_p_tm.ao_part_billing, ao_m_tm.ao_cmf, ao_m_tm.ao_payee,
                       (IFNULL(ao_p_tm.ao_amt, 0.00)) AS amt , FORMAT(IFNULL(ao_p_tm.ao_oramt, 0.00), 2) AS applied , DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, 
                       IFNULL(temp.appliedamt, 0.00) AS applied_amt, IFNULL(temp.bal, 0.00) AS bal, 
     			   IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) as amountdue,	
				   IFNULL(temp.wvat, 0.00) AS a_wvat, IFNULL(temp.wvatp, 0.00) AS a_wvatp, 
				   IFNULL(temp.wtax, 0.00) AS a_wtax, IFNULL(temp.wtaxp, 0.00) AS a_wtaxp, 
				   IFNULL(temp.ppd, 0.00) AS a_ppd, IFNULL(temp.ppdp, 0.00) AS a_ppdp, concat(ao_p_tm.ao_width,' x ',ao_p_tm.ao_length) as size, ao_m_tm.ao_ref				                    
		      from temp_payment_applied as temp
                inner join ao_p_tm ON ao_p_tm.id = temp.aoptmid
                INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                INNER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
			 where temp.aoptmid = '$id' and mykeyid = '$mykeyid' group by temp.aoptmid";

		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
    
    public function getAppliedDMDataTemp($id, $mykeyid) {
        $stmt = "SELECT dcm.dc_num AS id, dcm.dc_num AS ao_num, DATE(dcm.dc_date) AS ao_issuefrom ,  '' AS ao_sidate , 
                       dcm.dc_num AS ao_sinum, dcm.dc_payee AS ao_cmf, '' AS ao_amf, dcm.dc_payeename AS ao_payee, '' AS prod_name,
                       '' AS ao_part_billing,
                       IFNULL(dcm.dc_amt, 0.00) AS amt , '' , '' AS ordate, 
                       IFNULL(temp.appliedamt, 0.00) AS applied_amt, IFNULL(temp.bal, 0.00) AS bal, 
                       IFNULL(dcm.dc_amt, 0.00) - IFNULL(dcm.dc_assignamt, 0.00) AS amountdue,    
                       IFNULL(temp.wvat, 0.00) AS a_wvat, IFNULL(temp.wvatp, 0.00) AS a_wvatp, 
                       IFNULL(temp.wtax, 0.00) AS a_wtax, IFNULL(temp.wtaxp, 0.00) AS a_wtaxp, 
                       IFNULL(temp.ppd, 0.00) AS a_ppd, IFNULL(temp.ppdp, 0.00) AS a_ppdp, CONCAT('0 ',' x ',' 0') AS size, '' AS ao_ref                                    
                FROM temp_payment_applied AS temp
                LEFT OUTER JOIN dc_m_tm AS dcm ON (dcm.dc_num = temp.aoptmid AND dcm.dc_type = 'D')
                WHERE temp.aoptmid = '$id' AND mykeyid = '$mykeyid' GROUP BY temp.aoptmid";

        $result = $this->db->query($stmt)->row_array();
        return $result;
    }

	public function saveTempORPaymentApplied($data)
	{
		$data['is_update'] = 1;
		$this->db->insert('temp_payment_applied', $data);
		return true;	
	}

	public function getSummaryAssignAmount($mykeyid) 
	{
		$stmt = "SELECT FORMAT(IFNULL(SUM(wvat), 0),2) AS totalwvat, FORMAT(IFNULL(SUM(wtax), 0),2) AS totalwtax, FORMAT(IFNULL(SUM(ppd), 0), 2) AS totalppd, FORMAT(IFNULL(SUM(appliedamt), 0),2) AS totalappliedamt
				from temp_payment_applied where mykeyid = '$mykeyid' and is_temp_delete = 0";
		$result = $this->db->querY($stmt)->row_array();

		return $result;
	}

	public function getAppliedDataList($mykeyid) {
		/*$stmt = "SELECT temp.aoptmid, misprod.prod_name, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom , ao_p_tm.ao_type,
				IFNULL(miscmf.cmf_code, '') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname, 
				ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_name,  misprod.prod_code,
				ao_p_tm.ao_part_billing, ao_m_tm.ao_cmf, ao_m_tm.ao_payee,
				IFNULL(ao_p_tm.ao_amt, 0.00) AS amt , IFNULL(ao_p_tm.ao_oramt, 0.00) AS totalor, IFNULL(ao_p_tm.ao_dcamt, 0.00) AS totaldc, 
			     DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, DATE(IFNULL(ao_p_tm.ao_dcdate, '')) AS cmdate,				
				IFNULL(temp.appliedamt, 0.00) AS applied_amt, IFNULL(temp.bal, 0.00) AS bal, 
     			IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) as amountdue,	
				IFNULL(temp.wvat, 0.00) AS a_wvat, IFNULL(temp.wvatp, 0.00) AS a_wvatp, 
				IFNULL(temp.wtax, 0.00) AS a_wtax, IFNULL(temp.wtaxp, 0.00) AS a_wtaxp, 
				IFNULL(temp.ppd, 0.00) AS a_ppd, IFNULL(temp.ppdp, 0.00) AS a_ppdp,
				IFNULL(ao_p_tm.ao_wtaxstatus, 0) AS ao_wtaxstatus, IFNULL(ao_p_tm.ao_wtaxamt, 0.00) AS ao_wtaxamt, IFNULL(ao_p_tm.ao_wtaxpercent, 0.00) AS ao_wtaxpercent, ao_p_tm.ao_wtaxpart,
				IFNULL(ao_p_tm.ao_wvatstatus, 0) AS ao_wvatstatus, IFNULL(ao_p_tm.ao_wvatamt, 0.00) AS ao_wvatamt, IFNULL(ao_p_tm.ao_wvatpercent, 0.00) AS ao_wvatpercent, ao_p_tm.ao_wvatpart,
				IFNULL(ao_p_tm.ao_ppdstatus, 0) AS ao_ppdstatus, IFNULL(ao_p_tm.ao_ppdamt, 0.00) AS ao_ppdamt, IFNULL(ao_p_tm.ao_ppdpercent, 0.00) AS ao_ppdpercent, ao_p_tm.ao_ppdpart,
				concat(ao_p_tm.ao_width,' x ',ao_p_tm.ao_length) as size, ao_m_tm.ao_ref					                     
				FROM temp_payment_applied as temp
				INNER JOIN ao_p_tm ON ao_p_tm.id = temp.aoptmid
				INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
				INNER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
				LEFT OUTER JOIN miscmf ON miscmf.id =  ao_m_tm.ao_amf	
				WHERE temp.mykeyid = '$mykeyid' AND temp.is_temp_delete = 0 AND temp.doctype = 'SI' GROUP by temp.aoptmid"; */
                
        $stmt = "
               SELECT xall.*
              FROM (
                SELECT temp.aoptmid, misprod.prod_name, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom , ao_p_tm.ao_type,
                IFNULL(miscmf.cmf_code, '') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname, 
                ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_code,
                ao_p_tm.ao_part_billing, ao_p_tm.ao_billing_prodtitle,
                IFNULL(ao_p_tm.ao_amt, 0.00) AS amt , IFNULL(ao_p_tm.ao_oramt, 0.00) AS totalor, IFNULL(ao_p_tm.ao_dcamt, 0.00) AS totaldc, 
                 DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, DATE(IFNULL(ao_p_tm.ao_dcdate, '')) AS cmdate,                
                IFNULL(temp.appliedamt, 0.00) AS applied_amt, IFNULL(temp.bal, 0.00) AS bal, 
                 IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) AS amountdue,    
                IFNULL(temp.wvat, 0.00) AS a_wvat, IFNULL(temp.wvatp, 0.00) AS a_wvatp, 
                IFNULL(temp.wtax, 0.00) AS a_wtax, IFNULL(temp.wtaxp, 0.00) AS a_wtaxp, 
                IFNULL(temp.ppd, 0.00) AS a_ppd, IFNULL(temp.ppdp, 0.00) AS a_ppdp,
                IFNULL(ao_p_tm.ao_wtaxstatus, 0) AS ao_wtaxstatus, IFNULL(ao_p_tm.ao_wtaxamt, 0.00) AS ao_wtaxamt, IFNULL(ao_p_tm.ao_wtaxpercent, 0.00) AS ao_wtaxpercent, ao_p_tm.ao_wtaxpart,
                IFNULL(ao_p_tm.ao_wvatstatus, 0) AS ao_wvatstatus, IFNULL(ao_p_tm.ao_wvatamt, 0.00) AS ao_wvatamt, IFNULL(ao_p_tm.ao_wvatpercent, 0.00) AS ao_wvatpercent, ao_p_tm.ao_wvatpart,
                IFNULL(ao_p_tm.ao_ppdstatus, 0) AS ao_ppdstatus, IFNULL(ao_p_tm.ao_ppdamt, 0.00) AS ao_ppdamt, IFNULL(ao_p_tm.ao_ppdpercent, 0.00) AS ao_ppdpercent, ao_p_tm.ao_ppdpart,
                CONCAT(ao_p_tm.ao_width,' x ',ao_p_tm.ao_length) AS size, ao_m_tm.ao_ref, temp.lastapplieddate, misadtype.adtype_code, temp.status                                         
                FROM temp_payment_applied AS temp
                INNER JOIN ao_p_tm ON ao_p_tm.id = temp.aoptmid
                INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                INNER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
                LEFT OUTER JOIN miscmf ON miscmf.id =  ao_m_tm.ao_amf    
                LEFT OUTER JOIN misadtype ON misadtype.id = ao_m_tm.ao_adtype    
                WHERE temp.mykeyid = '$mykeyid' AND temp.is_temp_delete = 0 AND temp.doctype = 'SI' GROUP BY temp.aoptmid
                UNION 
                SELECT temp.aoptmid, 'Debit Memo' AS prod_name, DATE(dcm.dc_date) AS ao_issuefrom , 'DM' AS ao_type,
                '' AS agencycode, '' AS agencyname, 
                '' AS ao_sinum, dcm.dc_payee AS ao_cmf, '' AS ao_amf, dcm.dc_payeename AS ao_payee, '' AS prod_code,
                '' AS ao_part_billing, '' AS ao_billing_prodtitle,
                '0' AS amt , '0' AS totalor, '0' AS totaldc, 
                '' AS ordate, '' AS cmdate,                
                IFNULL(temp.appliedamt, 0.00) AS applied_amt, IFNULL(temp.bal, 0.00) AS bal, 
                (IFNULL(dcm.dc_amt, 0) - IFNULL(dcm.dc_assignamt, 0)) AS amountdue,    
                IFNULL(temp.wvat, 0.00) AS a_wvat, IFNULL(temp.wvatp, 0.00) AS a_wvatp, 
                IFNULL(temp.wtax, 0.00) AS a_wtax, IFNULL(temp.wtaxp, 0.00) AS a_wtaxp, 
                IFNULL(temp.ppd, 0.00) AS a_ppd, IFNULL(temp.ppdp, 0.00) AS a_ppdp,
                '' AS ao_wtaxstatus, '' AS ao_wtaxamt, '' AS ao_wtaxpercent, '' AS ao_wtaxpart,
                '' AS ao_wvatstatus, '' AS ao_wvatamt, '' AS ao_wvatpercent, '' AS ao_wvatpart,
                '' AS ao_ppdstatus, '' AS ao_ppdamt, '' AS ao_ppdpercent, '' AS ao_ppdpart,
                CONCAT('0 ', 'x', ' 0') AS size, '' AS ao_ref, temp.lastapplieddate, misadtype.adtype_code, dcm.status                                                  
                FROM temp_payment_applied AS temp
                LEFT OUTER JOIN dc_m_tm AS dcm ON (dcm.dc_num = temp.aoptmid AND dcm.dc_type = 'D')
                LEFT OUTER JOIN misadtype ON misadtype.id = dcm.dc_adtype
                WHERE temp.mykeyid = '$mykeyid' AND temp.is_temp_delete = 0 AND temp.doctype = 'DM' GROUP BY temp.aoptmid
              ) AS xall LIMIT 150
                ";
        #echo "<pre>"; echo $stmt; exit;
		$result = $this->db->query($stmt)->result_array();
		$resultcmf = array();		
		foreach ($result as $row) {                    
			$resultcmf[$row['agencycode']." ".$row['agencyname']." ".$row['ao_cmf'].' - '.$row['ao_payee']][] = $row;             
		}
		return $resultcmf;
	}

	public function updatesaveTempORPaymentApplied($data)
	{
		$data['is_update'] = 1;
		$this->db->where(array('aoptmid' => $data['aoptmid'], 'mykeyid' => $data['mykeyid']));
		$this->db->update('temp_payment_applied', $data);
		return true;	
	}

	public function getTotalAmountPaid($mykeyid) {
		$stmt = "select sum(ifnull(amount, 0)) as total_amountpaid from temp_payment_types where is_temp_delete ='0' and mykeyid='$mykeyid'";
		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function getListORPayment($mykeyid) {
		$stmt = "select a.mykeyid, a.id, 
					 CASE a.type
						WHEN 'CH' THEN 'Cash' 
						WHEN 'CK' THEN 'Check' 
						WHEN 'CC' THEN 'Credit Card' 
						WHEN 'DD' THEN 'Direct Deposit' 
						WHEN 'EX' THEN 'Exdeal' 		
					 END AS type,
					 b.bmf_code as bank, c.bbf_bnch as bankbranch, 
				      a.checknumber, date(a.checkdate) as checkdate, a.creditcardnumber, d.creditcard_name as creditcard,
				      a.remarks, a.authorizationno, date(a.expirydate) as expirydate, a.amount
			from temp_payment_types as a
			LEFT OUTER JOIN mispaycheckbank AS b ON a.bank = b.id
			LEFT OUTER JOIN mispaycheckbankbranch AS c ON a.bankbranch = c.id
			LEFT OUTER JOIN miscreditcard AS d ON a.creditcard = d.id
			where a.mykeyid = '$mykeyid' and is_temp_delete = 0 ORDER BY id ASC";

		$result = $this->db->query($stmt)->result_array();

		return $result;
	}

	public function saveTempORPayment($data, $mykeyid) {
		$stmt = "select ifnull(max(id), 0) + 1 as maxid from temp_payment_types where mykeyid = '$mykeyid'";
		$maxid = $this->db->query($stmt)->row_array();
		$data['id'] = $maxid['maxid'];
		$data['is_update'] = 1;
		$this->db->insert('temp_payment_types',$data);
		return true;
	}

	public function deleteTempOrPaymenttype($id, $mykeyid) {	
		$stmt = "Update temp_payment_types set is_temp_delete = 1, is_update = 1 where id ='$id' and mykeyid='$mykeyid'";
		$this->db->query($stmt);

		return true;
	}

	public function retrieveSearchList($code,$type,$choose,$mykeyid) {
		$con = '';       
		switch($type) {
		  case 'C' :
			 // client done			
			 $con = "AND ao_m_tm.ao_cmf = '$code' AND (ao_m_tm.ao_amf IS NULL OR ao_m_tm.ao_amf = 0)";   
		  break;
		  case 'A':
			 // agency done               
			 if ($choose == 2) {
			 $con = "AND ao_m_tm.ao_cmf = '$code' AND ao_m_tm.ao_amf != 0";
			 } else if ($choose == 1) {
			 $con = "AND ao_m_tm.ao_amf IN (SELECT id FROM miscmf WHERE cmf_code = '$code')";   
			 }
		  break;
		  case 'MA':
			 // main agency 
			 
			 $stmtmc = "SELECT id FROM miscmf WHERE cmf_code = '$code'";
			 $r = $this->db->query($stmtmc)->row_array();
			 
			 if (empty($r)) {       
		     $con = "AND ao_m_tm.ao_amf IN (SELECT cmf_code FROM miscmf WHERE id IN (
				   SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = (
				   SELECT id FROM miscmfgroup WHERE cmfgroup_code = '$code')))";                        
			 } else {
             $stmtxx = "SELECT cmf_code FROM miscmf WHERE id IN (SELECT cmf_code FROM misacmf WHERE amf_code = ".$r['id']." AND is_deleted = 0) AND cmf_catad != 2";
             $rr = $this->db->query($stmtxx)->result_array();    
            
            $strng = "'x',";
            if (!empty($rr)) {
                $strng = "";  
                foreach ($rr as $rr) {
                    $strng .= "'".$rr['cmf_code']."',";    
                }  
                
            }
            
            
             $strng = substr($strng, 0, -1); 
             
			 $con = "AND ao_m_tm.ao_amf IN (SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = ( 
				    SELECT cmfgroup_code FROM miscmfgroupaccess WHERE cmf_code IN (
				    SELECT id FROM miscmf WHERE cmf_code IN ($strng))))";      
			 } 
				                    
		  break;
		  case 'MC':
			 // main client       
			 
			 $stmtmc = "SELECT id FROM miscmf WHERE cmf_code = '$code'";
			 $r = $this->db->query($stmtmc)->row_array();
			 
			 if (empty($r)) {
			 $con = "AND ao_m_tm.ao_cmf IN (SELECT cmf_code FROM miscmf WHERE id IN (
				   SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = (
				   SELECT id FROM miscmfgroup WHERE cmfgroup_code = '$code')))";    
			 } else {
			 $con = "AND ao_m_tm.ao_cmf IN (SELECT cmf_code FROM miscmf WHERE id IN (                
				   SELECT cmf_code FROM miscmfgroupaccess 
				   WHERE cmfgroup_code IN (SELECT cmfgroup_code FROM miscmfgroupaccess WHERE cmf_code = (
				   SELECT id FROM miscmf WHERE cmf_code = '$code'))))";        
			 }                
			 
			 //}
		  break;
		}
		$stmt = "
            SELECT xall. * 
            FROM
            (
            SELECT ao_p_tm.id, ao_p_tm.ao_num, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom , ao_p_tm.ao_type, 
				   IFNULL(miscmf.cmf_code, '') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname, 
				   ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_name,  misprod.prod_code,
				   ao_p_tm.ao_part_billing,
				   IFNULL(ao_p_tm.ao_amt, 0.00) AS amt , (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) AS totalordc , DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, 
				   (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal,
				   IFNULL(ao_p_tm.ao_wtaxstatus, 0) AS ao_wtaxstatus, IFNULL(ao_p_tm.ao_wtaxamt, 0.00) AS ao_wtaxamt, IFNULL(ao_p_tm.ao_wtaxpercent, 0.00) AS ao_wtaxpercent, ao_p_tm.ao_wtaxpart,
				   IFNULL(ao_p_tm.ao_wvatstatus, 0) AS ao_wvatstatus, IFNULL(ao_p_tm.ao_wvatamt, 0.00) AS ao_wvatamt, IFNULL(ao_p_tm.ao_wvatpercent, 0.00) AS ao_wvatpercent, ao_p_tm.ao_wvatpart,
				   IFNULL(ao_p_tm.ao_ppdstatus, 0) AS ao_ppdstatus, IFNULL(ao_p_tm.ao_ppdamt, 0.00) AS ao_ppdamt, IFNULL(ao_p_tm.ao_ppdpercent, 0.00) AS ao_ppdpercent, ao_p_tm.ao_ppdpart,
                   ao_p_tm.ao_cmfvatrate AS vatrate, ao_p_tm.ao_cmfvatcode
			 FROM ao_p_tm
			 INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
			 LEFT OUTER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
			 LEFT OUTER JOIN miscmf ON miscmf.id =  ao_m_tm.ao_amf
			 WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1 AND ao_p_tm.ao_sinum <> 0 AND ao_p_tm.ao_sinum <> 1 
			 $con AND ao_p_tm.id NOT IN(select aoptmid from temp_payment_applied where mykeyid='$mykeyid' and is_temp_delete = 0 and doctype = 'SI')
             UNION

             SELECT dcm.dc_num, dcm.dc_num, DATE(dcm.dc_date) AS dc_date, 'DM', '', '',
                    dcm.dc_num, dcm.dc_payee, '0', dcm.dc_payeename,'DM','DM','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','', '', '' 
             FROM dc_m_tm AS dcm 
             WHERE dcm.dc_payee = '$code' 
             AND dcm.dc_num NOT IN(select aoptmid from temp_payment_applied where mykeyid='$mykeyid' and is_temp_delete = 0 and doctype = 'DM')
             AND dcm.dc_type = 'D' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcm.dc_assignamt, 0))
             ) AS xall              
			 ORDER BY xall.ao_cmf, xall.ao_payee, xall.ao_sinum, xall.ao_issuefrom ASC";
        #echo "<pre>"; echo $stmt; exit;
		$result = $this->db->query($stmt)->result_array();
        //print_r2($result); exit;
		$resultcmf = array();		
		foreach ($result as $row) {                    
			$resultcmf[$row['agencycode']." ".$row['agencyname']." ".$row['ao_cmf'].' - '.$row['ao_payee']][] = $row;             
		}
        #print_r2($resultcmf); exit;
		return $resultcmf;
	}      
    
    
    public function retrieveSearchListFilter($code,$type,$choose,$mykeyid, $inv) {
        $con = '';       
        switch($type) {
          case 'C' :
             // client done            
             $con = "AND ao_m_tm.ao_cmf = '$code' AND (ao_m_tm.ao_amf IS NULL OR ao_m_tm.ao_amf = 0)";   
          break;
          case 'A':
             // agency done               
             if ($choose == 2) {
             $con = "AND ao_m_tm.ao_cmf = '$code' AND ao_m_tm.ao_amf != 0";
             } else if ($choose == 1) {
             $con = "AND ao_m_tm.ao_amf IN (SELECT id FROM miscmf WHERE cmf_code = '$code')";   
             }
          break;
          case 'MA':
             // main agency 
             
             $stmtmc = "SELECT id FROM miscmf WHERE cmf_code = '$code'";
             $r = $this->db->query($stmtmc)->row_array();
             
             if (empty($r)) {       
             $con = "AND ao_m_tm.ao_amf IN (SELECT cmf_code FROM miscmf WHERE id IN (
                   SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = (
                   SELECT id FROM miscmfgroup WHERE cmfgroup_code = '$code')))";                        
             } else {
             $con = "AND ao_m_tm.ao_amf IN (SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = ( 
                    SELECT cmfgroup_code FROM miscmfgroupaccess WHERE cmf_code = (
                    SELECT id FROM miscmf WHERE cmf_code = '$code')))";      
             } 
                                    
          break;
          case 'MC':
             // main client       
             
             $stmtmc = "SELECT id FROM miscmf WHERE cmf_code = '$code'";
             $r = $this->db->query($stmtmc)->row_array();
             
             if (empty($r)) {
             $con = "AND ao_m_tm.ao_cmf IN (SELECT cmf_code FROM miscmf WHERE id IN (
                   SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = (
                   SELECT id FROM miscmfgroup WHERE cmfgroup_code = '$code')))";    
             } else {
             $con = "AND ao_m_tm.ao_cmf IN (SELECT cmf_code FROM miscmf WHERE id IN (                
                   SELECT cmf_code FROM miscmfgroupaccess 
                   WHERE cmfgroup_code IN (SELECT cmfgroup_code FROM miscmfgroupaccess WHERE cmf_code = (
                   SELECT id FROM miscmf WHERE cmf_code = '$code'))))";        
             }                
             
             //}
          break;
        }
        $stmt = "
            SELECT xall. * 
            FROM
            (
            SELECT ao_p_tm.id, ao_p_tm.ao_num, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom , ao_p_tm.ao_type, 
                   IFNULL(miscmf.cmf_code, '') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname, 
                   ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_name,  misprod.prod_code,
                   ao_p_tm.ao_part_billing,
                   IFNULL(ao_p_tm.ao_amt, 0.00) AS amt , (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) AS totalordc , DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, 
                   (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal,
                   IFNULL(ao_p_tm.ao_wtaxstatus, 0) AS ao_wtaxstatus, IFNULL(ao_p_tm.ao_wtaxamt, 0.00) AS ao_wtaxamt, IFNULL(ao_p_tm.ao_wtaxpercent, 0.00) AS ao_wtaxpercent, ao_p_tm.ao_wtaxpart,
                   IFNULL(ao_p_tm.ao_wvatstatus, 0) AS ao_wvatstatus, IFNULL(ao_p_tm.ao_wvatamt, 0.00) AS ao_wvatamt, IFNULL(ao_p_tm.ao_wvatpercent, 0.00) AS ao_wvatpercent, ao_p_tm.ao_wvatpart,
                   IFNULL(ao_p_tm.ao_ppdstatus, 0) AS ao_ppdstatus, IFNULL(ao_p_tm.ao_ppdamt, 0.00) AS ao_ppdamt, IFNULL(ao_p_tm.ao_ppdpercent, 0.00) AS ao_ppdpercent, ao_p_tm.ao_ppdpart,
                   ao_p_tm.ao_cmfvatrate AS vatrate, ao_p_tm.ao_cmfvatcode   
             FROM ao_p_tm
             INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
             LEFT OUTER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
             LEFT OUTER JOIN miscmf ON miscmf.id =  ao_m_tm.ao_amf
             WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1 
             $con AND ao_p_tm.id NOT IN(select aoptmid from temp_payment_applied where mykeyid='$mykeyid' and is_temp_delete = 0 and doctype = 'SI')
             UNION

             SELECT dcm.dc_num, dcm.dc_num, DATE(dcm.dc_date) AS dc_date, 'DM', '', '',
                    dcm.dc_num, dcm.dc_payee, '0', dcm.dc_payeename,'DM','DM','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','', '', ''  
             FROM dc_m_tm AS dcm 
             WHERE dcm.dc_payee = '$code' 
             AND dcm.dc_num NOT IN(select aoptmid from temp_payment_applied where mykeyid='$mykeyid' and is_temp_delete = 0 and doctype = 'DM')
             AND dcm.dc_type = 'D' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcm.dc_assignamt, 0))
             ) AS xall  
             WHERE xall.ao_sinum = '$inv'            
             ORDER BY xall.ao_cmf, xall.ao_payee, xall.ao_sinum, xall.ao_issuefrom ASC";
        
        $result = $this->db->query($stmt)->result_array();
        //print_r2($result); exit;
        $resultcmf = array();        
        foreach ($result as $row) {                    
            $resultcmf[$row['agencycode']." ".$row['agencyname']." ".$row['ao_cmf'].' - '.$row['ao_payee']][] = $row;             
        }
        #print_r2($resultcmf); exit;
        return $resultcmf;
    }
    
    public function saveRevenueApplied($update_p, $aonumber, $data) {
        
        
        /*$stmt = "SELECT id, ao_num, ao_amt FROM ao_p_tm WHERE ao_num = $aonumber"; 
        $result = $this->db->query($stmt)->result_array();
        
        foreach ($result as $row) {
            $this->db->where('id', $row['id']);
            $update_p['ao_oramt'] = $row['ao_amt']; 
            $this->db->update('ao_p_tm', $update_p);    
        }*/
        
        
        $stmt = "SELECT id, ao_amt FROM ao_p_tm WHERE ao_num = '$aonumber'";
        $result = $this->db->query($stmt)->result_array();
        
        $stmt2 = "SELECT ao_num, ao_amt FROM ao_m_tm WHERE ao_num = '$aonumber'";  
        $result2 = $this->db->query($stmt2)->row_array();
        $oramtdue = $result2['ao_amt'];

        $or_amt = $data['oramt'];
        $wtax = $data['wtaxamt'];      
        $wvat = $data['wvatamt'];    
        $other = $data['otheramt'];    
        
        $or_percent = 0;        
        $or_percent_sum = 0;
        $or_lastdet = 0;
        $or_minus = 0;
        $or_plus = 0;
        $neworamt = 0;
        $newamt = 0;
        $newwtax = 0;
        $newwvat = 0;

        $totalresult = count($result); 
        for ($x = 0; $x < $totalresult; $x++) {   

            $or_percent = number_format((floatval($result[$x]['ao_amt']) / floatval($oramtdue) * 100), 2, '.', '');         

            if ($x == $totalresult - 1) {
            
                $or_percent_sum += $or_percent;                    
                if ($or_percent_sum < 100) {
                    $or_plus = (100) - ($or_percent_sum);      
                    $or_plus = number_format((100) - ($or_percent_sum), 2, '.', '');      
                    $or_lastdet = $or_lastdet + $or_plus;                               
                } else if ($or_percent_sum > 100) {
                    $or_minus = number_format(($or_percent_sum) - (100), 2, '.', '');              
                    $or_minus = ($or_percent_sum) - (100);
                    $or_lastdet = $
                    $or_lastdet - $or_minus;                
                } else {
                    $or_lastdet = $or_percent;   
                }              

                $update_p['ao_oramt'] = number_format(floatval($or_amt) * ($or_lastdet / 100), 2, '.', '');     
                $update_p['ao_wtaxamt'] = number_format(floatval($wtax) * ($or_lastdet / 100), 2, '.', '');                                    
                $update_p['ao_wvatamt'] = number_format(floatval($wvat) * ($or_lastdet / 100), 2, '.', '');                                
                $update_p['ao_otheramt'] = number_format(floatval($other) * ($or_lastdet / 100), 2, '.', '');                                
            } else {    

                $or_percent_sum += $or_percent;     
                
                $update_p['ao_oramt'] = number_format(floatval($or_amt) * ($or_percent / 100), 2, '.', '');     
                $update_p['ao_wtaxamt'] = number_format(floatval($wtax) * ($or_percent / 100), 2, '.', '');                                    
                $update_p['ao_wvatamt'] = number_format(floatval($wvat) * ($or_percent / 100), 2, '.', '');                                
                $update_p['ao_otheramt'] = number_format(floatval($other) * ($or_percent / 100), 2, '.', '');                                
            }
            
            $update_p['ao_ornum'] = $update_p['ao_ornum'];               
            $update_p['ao_ordate'] = date('Y-m-d');    
            $update_p['ao_paginated_status'] = 0;    
            $update_p['ao_paginated_name'] = $this->session->userdata('authsess')->sess_id;                                              
            $update_p['ao_paginated_date'] = date('Y-m-d');    
            
            $update_p['is_payed'] = 1;  
              
            $update_p['is_temp'] = 1;    
            $update_p['is_invoice'] = 1;    
            $update_p['ao_sinum'] = 1;    
            $update_p['ao_sidate'] = date('Y-m-d'); 
            
            $update_p['ao_wvatstatus'] = $data['or_wvatstatus'];                    
            $update_p['ao_wvatpercent'] = $data['or_wvatpercent'];                                        
            $update_p['ao_wtaxstatus'] = $data['or_wtaxstatus'];                                        
            $update_p['ao_wtaxpercent'] = $data['or_wtaxpercent'];                                        
            $update_p['ao_ppdstatus'] = $data['or_ppdstatus'];                                        
            $update_p['ao_ppdpercent'] = $data['or_ppdpercent'];   
             
            $this->db->where('id', $result[$x]['id']);
            $this->db->update('ao_p_tm', $update_p);
            
            /*$aoornum = $update_p['ao_ornum'];
            $stmtret = "SELECT id, ao_num, ao_amt, ao_oramt, ao_dcamt, is_payed, (ao_oramt + ao_dcamt) AS totalpaid, IF ((ao_oramt + ao_dcamt) >= ao_amt, 1, 0) AS ispayed FROM ao_p_tm WHERE ao_ornum = $aoornum"; 
            
            $resultret = $this->db->query($stmtret)->result_array();
            
            foreach ($resultret as $rrow) {
                if ($rrow['ispayed'] == 1) {
                    $detail['is_payed'] = 1;
                    $this->db->where('id', $rrow['id']);
                    $this->db->update('ao_p_tm', $detail);    
                }
            } */
        }

        return TRUE;   
    }

    /*********************************************************/
    
    public function autoORSave($adid, $data, $ordata) {
        
        #print_r2($data);             
        $main['or_num'] = intval($ordata['ornum']);
        $main['or_date'] = date('Y-m-d');          
        $main['or_artype'] = $data['ao_artype'];
        $main['or_type'] = '2';       
        $main['or_argroup'] = 'A';
        $main['or_transtype'] = 'M';
        $main['or_subtype'] = 'R';
        $main['or_adtype'] = $data['ao_adtype'];    
        $main['or_ccf'] = $this->session->userdata('authsess')->sess_id;   
        
        $main['or_amf'] = null;                                                
         
        $main['or_cmf'] = 'REVENUE'; 
        $main['or_payee'] = $data['ao_payee']; 
        $main['or_title'] = $data['ao_title']; 
        $main['or_prod'] = $data['ao_prod']; 
        
        $branch_stmt = "SELECT db.branch_bnacc, b.baf_bank 
                        FROM misbranch AS db
                        INNER JOIN misbaf AS b ON b.id = db.branch_bnacc
                        WHERE db.id = '".$data['ao_branch']."'";
        $branch = $this->db->query($branch_stmt)->row_array();
        $main['or_bnacc'] = $branch['baf_bank'];
        $main['or_branch'] = $data['ao_branch'];
        $main['or_add1'] = $data['ao_add1']; 
        $main['or_add2'] = $data['ao_add2']; 
        $main['or_add3'] = $data['ao_add3']; 
        $main['or_title'] = $data['ao_title'];
        $main['or_telprefix1'] = $data['ao_telprefix1'];
        $main['or_tel1'] = $data['ao_tel1']; 
        $main['or_telprefix2'] = $data['ao_telprefix2'];
        $main['or_tel2'] = $data['ao_tel2']; 
        $main['or_celprefix'] = $data['ao_celprefix']; 
        $main['or_cel'] = $data['ao_cel']; 
        $main['or_faxprefix'] = $data['ao_faxprefix']; 
        $main['or_fax'] = $data['ao_fax']; 
        $main['or_tin'] = $data['ao_tin']; 
        $main['or_zip'] = $data['ao_zip'];  
        
        $part = $this->getDefaultDataDetAO($adid);
        
        $main['or_part'] = $part['part'];   
        
        $or_amt = $ordata['oramt'];          
        $oramt = floatval($ordata['oramt']) + $ordata['otheramt'];          
        $vatrate = $data['ao_cmfvatrate'];   
        $wvat = $ordata['wvatamt'];
        $wtax = $ordata['wtaxamt'];
        $or_grossamt = $or_amt / (1+($vatrate/100));         
        $or_vatsales = ($or_amt + $wvat + $wtax) / (1+($vatrate/100));             
        $or_vatamt = $or_vatsales * ($vatrate/100);                                  
        if ($data['ao_cmfvatcode'] == 4) {
            $main['or_vatsales'] = 0;
            $main['or_vatexempt'] = $or_amt;            
            $main['or_vatzero'] = 0;    
            $main['or_vatstatus'] = 1;   
            $main['or_cmfvatcode'] = $data['ao_cmfvatcode'];           
            $main['or_cmfvatrate'] = 0;       
        } else if ($data['ao_cmfvatcode'] == 5){                        
            $main['or_vatsales'] = 0;
            $main['or_vatexempt'] = 0;            
            $main['or_vatzero'] = $or_amt;  
            $main['or_vatstatus'] = 1;      
            $main['or_cmfvatcode'] = $data['ao_cmfvatcode'];        
            $main['or_cmfvatrate'] = 0;        
        } else {
            $main['or_vatsales'] = $or_vatsales;            
            $main['or_vatexempt'] = 0;            
            $main['or_vatzero']  = 0;    
            $main['or_vatstatus'] = 1;  
            $main['or_cmfvatcode'] = $data['ao_cmfvatcode'];            
            $main['or_cmfvatrate'] = $data['ao_cmfvatrate'];       
        }
                   
        $main['or_grossamt'] = $or_grossamt;
        $main['or_vatamt'] = $or_vatamt;
        $main['or_notarialfee'] = $ordata['otheramt']; 
        
        $main['or_amt'] = $or_amt;      
	    $main['or_amtword'] = $ordata['oramtwords'];       
            	    
        $main['or_assignamt'] = $or_amt;
        $main['or_assignvatamt'] = $or_vatamt;
        $main['or_assigngrossamt'] = $or_grossamt;
        
        $main['or_comment'] = "AO #".$adid;
 
        if ($ordata['wtaxstat'] == 1) {
            $main['or_wtaxstatus'] = $ordata['wtaxstat'];                 
            $main['or_wtaxamt'] = $ordata['wtaxamt'];                                      
            $main['or_wtaxpercent'] = $ordata['wtaxper'];   
            $main['or_assignwtaxamt'] = $ordata['wtaxamt'];                                                                             
        }
        
        if ($ordata['wvatstat'] == 1) {             
            $main['or_wvatstatus'] = $ordata['wvatstat'];                              
            $main['or_wvatamt'] = $ordata['wvatamt'];                              
            $main['or_wvatpercent'] = $ordata['wvatper']; 
            $main['or_assignwvatamt'] = $ordata['wvatamt'];                              
        }   
                                                    
        
        $main['user_n'] = $this->session->userdata('authsess')->sess_id;                                              
        $main['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $main['edited_d'] = date('Y-m-d h:i:s');      
        
        $paydetailed['or_num'] = $ordata['ornum'];    
        $paydetailed['or_item_id'] = 1;
        $paydetailed['or_date'] = date('Y-m-d');                  
        $paydetailed['or_artype'] = $data['ao_artype'];      
        $paydetailed['or_argroup'] = 'A'; 
        $paydetailed['or_amt'] = $or_amt; 
        $paytype = 'CH';    
        if ($data['ao_paytype'] == 5) {
            $paytype = 'CK';    
            $paydetailed['or_paynum'] = $ordata['checknum'];    
            $paydetailed['or_paydate'] = $ordata['checkdate'];  
		    $paydetailed['or_paybank'] = $ordata['checkbank'];  
            $paydetailed['or_paybranch'] = $ordata['checkbankbranch'];  
        } else if ($data['ao_paytype'] == 4) {  
            $paytype = 'CC'; 
            $paydetailed['or_creditcard'] = $ordata['cardtype'];                    
            $paydetailed['or_creditcardnumber'] = $ordata['cardnum'];        
            $paydetailed['or_expirydate'] = $ordata['expirydate'];                   
            $paydetailed['or_authorizationno'] = $ordata['authorization'];        
        }
        $paydetailed['or_paytype'] = $paytype;           
        $paydetailed['user_n'] = $this->session->userdata('authsess')->sess_id;                                              
        $paydetailed['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $paydetailed['edited_d'] = date('Y-m-d h:i:s');

        
        $this->db->insert('or_m_tm', $main);
        $this->db->insert('or_p_tm', $paydetailed);
        
        $update_p['ao_ornum'] = $ordata['ornum'];               
        $update_p['ao_ordate'] = date('Y-m-d');    
        $update_p['ao_paginated_status'] = 0;    
        $update_p['ao_paginated_name'] = $this->session->userdata('authsess')->sess_id;                                              
        $update_p['ao_paginated_date'] = date('Y-m-d');    
        
        //$update_p['is_payed'] = 1;  
          
        $update_p['is_temp'] = 1;    
        $update_p['is_invoice'] = 1;    
        $update_p['ao_sinum'] = 1;    
        $update_p['ao_sidate'] = date('Y-m-d');    

        if ($ordata['wvatstat'] == 1) {
            $update_p['ao_wvatstatus'] = $ordata['wvatstat'];       
            $update_p['ao_wvatamt'] = $ordata['wvatamt'];      
            $update_p['ao_wvatpercent'] = $ordata['wvatper'];       
            $update_p['ao_wvatpart'] = $ordata['wvatrem'];  
        }    
        
        if ($ordata['wtaxstat'] == 1) {  
            $update_p['ao_wtaxstatus'] = $ordata['wtaxstat'];       
            $update_p['ao_wtaxamt'] = $ordata['wtaxamt'];      
            $update_p['ao_wtaxpercent'] = $ordata['wtaxper'];       
            $update_p['ao_wtaxpart'] = $ordata['wtaxrem'];
        }       
        
        if ($ordata['otherstat'] == 1) {
            $update_p['ao_otherstatus'] = $ordata['otherstat'];      
            $update_p['ao_otheramt'] = $ordata['otheramt'];       
            $update_p['ao_otherpercent'] = $ordata['otherper'];       
            $update_p['ao_otherpart'] = $ordata['otherrem'];  
        }    

        $stmt = "SELECT id, ao_amt FROM ao_p_tm WHERE ao_num = '$adid' ORDER BY ao_issuefrom";
        $result = $this->db->query($stmt)->result_array();
        $oramtdue = $data['ao_amt'];
        $or_amt = $ordata['oramt'];
        $wtax = $ordata['wtaxamt'];      
        $wvat = $ordata['wvatamt'];    
        $other = $ordata['otheramt'];         
        $or_percent = 0;        
        $or_percent_sum = 0;
        $or_lastdet = 0;
        $or_minus = 0;
        $or_plus = 0;
        $neworamt = 0;
        $newamt = 0;
        $newwtax = 0;
        $newwvat = 0;

        $totalresult = count($result); 
        for ($x = 0; $x < $totalresult; $x++) {   

            $or_percent = (floatval($result[$x]['ao_amt']) / floatval($oramtdue) * 100);
            
            if ($x == $totalresult - 1) {
                
                if ($or_percent_sum < 100) {
                    $or_plus = (100) - ($or_percent_sum);      
                    #$or_plus = (100) - ($or_percent_sum);
                    $or_lastdet = $or_lastdet + $or_plus;                               
                } else if ($or_percent_sum > 100) {
                    #$or_minus = ($or_percent_sum) - (100);
                    $or_minus = ($or_percent_sum) - (100);
                    $or_lastdet = $or_lastdet - $or_minus;                
                } else {
                    $or_lastdet = $or_percent;   
                }  
                #$or_lastdet = $or_percent;               
                $or_lastdet;
                $update_p['ao_oramt'] = number_format(floatval($or_amt) * ($or_lastdet / 100), 2, '.', '');     
                $update_p['ao_wtaxamt'] = number_format(floatval($wtax) * ($or_lastdet / 100), 2, '.', '');                                    
                $update_p['ao_wvatamt'] = number_format(floatval($wvat) * ($or_lastdet / 100), 2, '.', '');                                
                $update_p['ao_otheramt'] = number_format(floatval($other) * ($or_lastdet / 100), 2, '.', '');                                
            } else {    

                $or_percent_sum += $or_percent;  

                $update_p['ao_oramt'] = number_format(floatval($or_amt) * ($or_percent / 100), 2, '.', '');     
                $update_p['ao_wtaxamt'] = number_format(floatval($wtax) * ($or_percent / 100), 2, '.', '');                                    
                $update_p['ao_wvatamt'] = number_format(floatval($wvat) * ($or_percent / 100), 2, '.', '');                                
                $update_p['ao_otheramt'] = number_format(floatval($other) * ($or_percent / 100), 2, '.', '');                                
            }
             
            $this->db->where('id', $result[$x]['id']);
            $this->db->update('ao_p_tm', $update_p);
        }
        $aoornum = $ordata['ornum'];
        $stmtret = "SELECT id, ao_num, ao_amt, ao_oramt, ao_dcamt, is_payed, (ao_oramt + ao_dcamt) AS totalpaid, IF ((ao_oramt + ao_dcamt) >= ao_amt, 1, 0) AS ispayed FROM ao_p_tm WHERE ao_ornum = $aoornum"; 
        
        $resultret = $this->db->query($stmtret)->result_array();
        
        foreach ($resultret as $rrow) {
            if ($rrow['ispayed'] == 1) {
                $detail['is_payed'] = 1;
                $this->db->where('id', $rrow['id']);
                $this->db->update('ao_p_tm', $detail);    
            }
        }
        

        return TRUE;
    }
    
    public function doUnAppliedProcess($ornum, $pid, $itemid) {
        $unapplied = "SELECT or_num, or_date, or_assignamt, or_assignwtaxamt, or_assignwvatamt, or_assignppdamt 
                      FROM or_d_tm WHERE or_num = '$ornum' AND or_docitemid = '$pid' AND or_item_id = '$itemid'";
        
        $result_unapplied = $this->db->query($unapplied)->row_array();
        
        $ormtm = "SELECT or_num, or_date, or_amt, or_assignamt, or_assignwtaxamt, or_assignwvatamt, or_assignppdamt 
                        FROM or_m_tm WHERE or_num = '$ornum'";
        
        $result_ormtm = $this->db->query($ormtm)->row_array();
        
        $or_m_tm['or_assignamt'] = $result_ormtm['or_assignamt'] - $result_unapplied['or_assignamt'];
        $or_m_tm['or_assignwvatamt'] = $result_ormtm['or_assignwvatamt'] - $result_unapplied['or_assignwvatamt'];
        $or_m_tm['or_assignwtaxamt'] = $result_ormtm['or_assignwtaxamt'] - $result_unapplied['or_assignwtaxamt'];        
        $or_m_tm['or_assignppdamt'] = $result_ormtm['or_assignppdamt'] - $result_unapplied['or_assignppdamt'];        

        $this->db->where('or_num', $ornum);
        $this->db->update('or_m_tm', $or_m_tm);
                      
        $this->db->query("DELETE FROM or_d_tm WHERE or_docitemid = '$pid' AND or_num = '$ornum' AND or_item_id = '$itemid'");                      
        
        $latestorforaoptm = "SELECT or_num, or_date, user_d, SUM(or_assignamt) AS or_amt
                             FROM or_d_tm WHERE or_docitemid = '$pid'
                             ORDER BY user_d DESC LIMIT 1";  
        $result_latestorforaoptm = $this->db->query($latestorforaoptm)->row_array();
        
        $aoptm['ao_oramt'] = $result_latestorforaoptm['or_amt']; 
        $aoptm['ao_ornum'] = $result_latestorforaoptm['or_num'];
        $aoptm['ao_ordate'] = $result_latestorforaoptm['or_date'];        
                
        $this->db->where('id', $pid);  
        $this->db->update('ao_p_tm', $aoptm);
        
        $aoptmdata = "SELECT IFNULL(ao_amt, 0) AS ao_amt, (IFNULL(ao_oramt, 0) + IFNULL(ao_dcamt, 0)) AS totalpay FROM ao_p_tm WHERE id = '$pid'";
        $result_aoptmdata = $this->db->query($aoptmdata)->row_array();
        
        if ($result_aoptmdata['totalpay'] < $result_aoptmdata['ao_amt'])  {
            $aoptmpayed['is_payed'] = 0;        
                
            $this->db->where('id', $pid);  
            $this->db->update('ao_p_tm', $aoptmpayed);    
        }   
        
    }
    
    public function updatePayment($value, $id, $hkey) {
        $stmt = "SELECT id, tag_type, tag_bank, tag_bankbranch, tag_checknumber, tag_checkdate, tag_creditcard, tag_creditcardnumber,
                       tag_authorizationno, tag_expirydate, tag_amount, tag_remarks, is_temp_delete 
                FROM temp_payment_types WHERE mykeyid = '".$hkey."'";
                
        $result = $this->db->query($stmt)->result_array();
                
        foreach ($result as $row) :        
            $c_stmt = "SELECT  or_num FROM or_p_tm WHERE or_num = '$id' AND or_item_id = '".$row['id']."'";
            $x = count($this->db->query($c_stmt)->result_array());            
            
            if ($x == 0) {
                $xnum = "SELECT IFNULL((MAX(IFNULL(or_item_id, 0)) + 1), 1) AS oritemid FROM or_p_tm WHERE or_num = '$id'";
                $xres = $this->db->query($xnum)->row_array();   
                    if ($row['is_temp_delete'] != 1) {           
                    $inspayment['or_num'] = $value['or_num'];
                    $inspayment['or_item_id'] = $xres['oritemid'];
                    $inspayment['or_date'] = $value['or_date'];
                    $inspayment['or_artype'] = $value['or_artype'];
                    $inspayment['or_argroup'] = $value['or_argroup'];
                    $inspayment['or_paytype'] = $row['tag_type'];
                    $inspayment['or_paybank'] = $row['tag_bank'];
                    $inspayment['or_paybranch'] = $row['tag_bankbranch'];
                    
                    $inspayment['or_paynum'] = $row['tag_checknumber'];         
                    $inspayment['or_paydate'] = $row['tag_checkdate'];         
                
                    $inspayment['or_creditcard'] = $row['tag_creditcard'];
                    $inspayment['or_creditcardnumber'] = $row['tag_creditcardnumber'];
                                        
                    $inspayment['or_remarks'] = $row['tag_remarks'];
                    $inspayment['or_authorizationno'] = $row['tag_authorizationno'];
                    $inspayment['or_expirydate'] = $row['tag_expirydate'];            
                    
                    $inspayment['user_n'] = $this->session->userdata('authsess')->sess_id;
                    $inspayment['edited_n'] = $this->session->userdata('authsess')->sess_id;
                    $inspayment['edited_d'] = DATE('Y-m-d h:i:s');
        
                    $inspayment['or_amt'] = $row['tag_amount'];  
                    $this->db->insert('or_p_tm', $inspayment);
                    }
            } else {  
            
                    if ($row['is_temp_delete'] == 1) {
                        #$del_stmt = "DELETE FROM pr_p_tm WHERE pr_num = '$id' AND pr_item_id = '".$row['id']."'";                        
                        #$this->db->query($del_stmt);
                        
                        $this->db->where('or_num', $id);
                        $this->db->where('or_item_id', $row['id']);
                        $this->db->delete('or_p_tm'); 
                    } else { 
                    $updatepayment['or_artype'] = $value['or_artype'];
                    $updatepayment['or_argroup'] = $value['or_argroup'];
                    $updatepayment['or_paytype'] = $row['tag_type'];
                    $updatepayment['or_paybank'] = $row['tag_bank'];
                    $updatepayment['or_paybranch'] = $row['tag_bankbranch'];
                    
                    $updatepayment['or_paynum'] = $row['tag_checknumber'];         
                    $updatepayment['or_paydate'] = $row['tag_checkdate'];         
                    
                    $updatepayment['or_creditcard'] = $row['tag_creditcard'];
                    $updatepayment['or_creditcardnumber'] = $row['tag_creditcardnumber'];
                                
                    $updatepayment['or_remarks'] = $row['tag_remarks'];
                    $updatepayment['or_authorizationno'] = $row['tag_authorizationno'];
                    $updatepayment['or_expirydate'] = $row['tag_expirydate'];    
                                        
                    $updatepayment['edited_n'] = $this->session->userdata('authsess')->sess_id;
                    $updatepayment['edited_d'] = DATE('Y-m-d h:i:s');        
                    
                    $updatepayment['or_amt'] = $row['tag_amount'];  
                    $this->db->where('or_num', $id);
                    $this->db->where('or_item_id', $row['id']);
                    $this->db->update('or_p_tm', $updatepayment);    
                    }                                          
            }
        endforeach;
        return true;
    }
    
    
    public function deleteOR($orid) {
        
        $this->db->where('or_num', $orid);  
        $this->db->delete('or_p_tm'); 
        $this->db->where('or_num', $orid);  
        $this->db->delete('or_d_tm'); 
        $this->db->where('or_num', $orid);
        $this->db->delete('or_m_tm'); 
        
        return true;
    }
    
    public function updatePaymentApplied($data,$hkey,$id,$vatrate) {
        $stmt = "SELECT t.aoptmid, t.appliedamt, t.wvat, t.wvatp, t.wtax, t.wtaxp, t.ppd, t.ppdp, t.bal,
                       p.ao_prod, p.ao_prodissue, p.ao_issuefrom, p.ao_issueto, p.ao_num,
                       p.ao_part_billing, p.ao_adtyperate_code, p.ao_width, p.ao_length, 
                       p.ao_cmfvatcode, p.ao_cmfvatrate, p.ao_date, p.ao_amt, t.is_intag
                FROM temp_payment_applied AS t
                INNER JOIN ao_p_tm AS p ON t.aoptmid = p.id
                WHERE t.mykeyid = '$hkey' AND t.appliedamt != 0";
                 
        $result = $this->db->query($stmt)->result_array(); 
                   
        $xnum = "SELECT IFNULL((MAX(IFNULL(or_item_id, 0)) + 1), 1) AS oritemid FROM or_d_tm WHERE or_num = '$id'";
        $xres = $this->db->query($xnum)->row_array();
        if (!empty($result)) {             
            $insertdata = array();
            $updatedata = array();
            $x = $xres['oritemid']; 

            foreach ($result as $row) {
             
                #$chckstmt = "SELECT or_num AS xxx FROM or_d_tm WHERE or_num = '$id' AND or_docitemid = '".$row['aoptmid']."'";
                #$chck = $this->db->query($chckstmt)->row_array();
                #if (!empty($chck)) {
                if ($row['is_intag'] == 1) {                
                    $assign = $row['appliedamt'];                
                    $wtax = $row['wtax'];                
                    $wvat = $row['wvat'];                
                    $ppd = $row['ppd'];                
                    $g = ($assign + $wtax + $wvat + $ppd) / (1 + ($vatrate/100));                
                    $vatamt = round($g * ($vatrate/100), 2);      
                    $grossamt = round($g, 2);                
                    $updatedata = array('or_num' => $id,                    
                                               'or_artype' => $data['or_artype'],
                                               'or_argroup' => $data['or_argroup'], 
                                               'or_prod' => $row['ao_prod'],
                                               'or_prodissue' => $row['ao_prodissue'],
                                               'or_issuefrom' => $row['ao_issuefrom'],
                                               'or_issueto' => $row['ao_issueto'],
                                               'or_doctype' => $data['or_doctype'],
                                               'or_docnum' => $row['ao_num'],
                                               'or_docamt' => $data['or_amt'],     
                                               'or_docitemid' => $row['aoptmid'],
                                               'or_docpart' => $row['ao_part_billing'],
                                               'or_adtype' => $row['ao_adtyperate_code'],
                                               'or_width' => $row['ao_width'],
                                               'or_length' => $row['ao_length'],
                                               'or_docbal' => $row['bal'],
                                               'or_assignamt' => $assign,
                                               'or_assigngrossamt' => $grossamt,
                                               'or_assignvatamt' => $vatamt,
                                               'or_assignwtaxamt' => $row['wtax'],
                                               'or_assignwvatamt' => $row['wvat'],
                                               'or_assignppdamt' =>$row['ppd'], 
                                               'or_cmfvatcode' => $row['ao_cmfvatcode'],
                                               'or_cmfvatrate' => $row['ao_cmfvatrate'],
                                               'or_wtaxpercent' => $row['wtaxp'],
                                               'or_wvatpercent' => $row['wvatp'],
                                               'or_ppdpercent' => $row['ppdp'],     
                                               'user_n' => $this->session->userdata('authsess')->sess_id,                                           
                                               'edited_n' => $this->session->userdata('authsess')->sess_id,
                                               'edited_d' => DATE('Y-m-d h:i:s'));
                    
                    
                    $stmt2 = "SELECT ao_oramt, ao_dcamt, ao_wtaxamt, ao_wvatamt, ao_ppdamt FROM ao_p_tm WHERE id = '".$row['aoptmid']."'";
                    $getamt = $this->db->query($stmt2)->row_array();
                    
                    $pupdate['ao_wtaxstatus'] = $data['or_wtaxstatus'];
                    $pupdate['ao_wtaxamt'] = $row['wtax'] + $getamt['ao_wtaxamt'];
                    $pupdate['ao_wtaxpercent'] = $row['wtaxp'];
                    $pupdate['ao_wvatstatus'] = $data['or_wvatstatus'];
                    $pupdate['ao_wvatamt'] = $row['wvat'] + $getamt['ao_wvatamt'];
                    $pupdate['ao_wvatpercent'] = $row['wvatp'];
                    $pupdate['ao_ppdstatus'] = $data['or_ppdstatus'];
                    $pupdate['ao_ppdamt'] = $row['ppd'] + $getamt['ao_ppdamt'];
                    $pupdate['ao_ppdpercent'] = $row['ppdp'];
                    $pupdate['ao_ornum'] = $id;
                    $pupdate['ao_ordate'] = $data['or_date'];
                    $pupdate['ao_oramt'] = ($getamt['ao_oramt'] + $row['appliedamt']);
                    
                    
                    /* Applied amount is greater than the existing applied amoun ao_oramt 'plus'updatePayment 
                    * Applied amount is greater than the existing applied amoun ao_oramt 'plus'updatePayment
                    *
                    * */
                    $xtotalpay = ($getamt['ao_oramt'] + $row['appliedamt']) + $getamt['ao_dcamt'];
                    $pupdate['is_payed'] = 0;
                    if ($xtotalpay == $row['ao_amt']) {
                        $pupdate['is_payed'] = 1;
                    }                    
                    $this->db->update('ao_p_tm', $pupdate, array('id' => $row['aoptmid']));                       
                    $this->db->update('or_d_tm', $updatedata, array('or_num' => $id, 'or_docitemid' => $row['aoptmid']));                                              
                } else {                
                    $assign = $row['appliedamt'];                
                    $wtax = $row['wtax'];                
                    $wvat = $row['wvat'];                
                    $ppd = $row['ppd'];                
                    $g = ($assign + $wtax + $wvat + $ppd) / (1 + ($vatrate/100));                
                    $vatamt = round($g * ($vatrate/100), 2);      
                    $grossamt = round($g, 2);                
                    $insertdata = array('or_num' => $id,
                                           'or_item_id' => $x,
                                           'or_date' => $data['or_date'],
                                           'or_artype' => $data['or_artype'],
                                           'or_argroup' => $data['or_argroup'],   
                                           'or_prod' => $row['ao_prod'],
                                           'or_prodissue' => $row['ao_prodissue'],
                                           'or_issuefrom' => $row['ao_issuefrom'],
                                           'or_issueto' => $row['ao_issueto'],
                                           'or_doctype' => $data['or_doctype'],
                                           'or_docnum' => $row['ao_num'],
                                           'or_docamt' => $data['or_amt'],     
                                           'or_docitemid' => $row['aoptmid'],
                                           'or_docpart' => $row['ao_part_billing'],
                                           'or_adtype' => $row['ao_adtyperate_code'],
                                           'or_width' => $row['ao_width'],
                                           'or_length' => $row['ao_length'],
                                           'or_docbal' => $row['bal'],
                                           'or_assignamt' => $assign,
                                           'or_assigngrossamt' => $grossamt,
                                           'or_assignvatamt' => $vatamt,
                                           'or_assignwtaxamt' => $row['wtax'],
                                           'or_assignwvatamt' => $row['wvat'],
                                           'or_assignppdamt' =>$row['ppd'], 
                                           'or_cmfvatcode' => $row['ao_cmfvatcode'],
                                           'or_cmfvatrate' => $row['ao_cmfvatrate'],
                                           'or_wtaxpercent' => $row['wtaxp'],
                                           'or_wvatpercent' => $row['wvatp'],
                                           'or_ppdpercent' => $row['ppdp'],
                                           'user_n' => $this->session->userdata('authsess')->sess_id,
                                           'edited_n' => $this->session->userdata('authsess')->sess_id,
                                           'edited_d' => DATE('Y-m-d h:i:s'));
                    
                    
                    $stmt2 = "SELECT ao_oramt, ao_dcamt, ao_wtaxamt, ao_wvatamt, ao_ppdamt FROM ao_p_tm WHERE id = '".$row['aoptmid']."'";
                    $getamt = $this->db->query($stmt2)->row_array();
                    
                    $pupdate['ao_wtaxstatus'] = $data['or_wtaxstatus'];
                    $pupdate['ao_wtaxamt'] = $row['wtax'] + $getamt['ao_wtaxamt'];
                    $pupdate['ao_wtaxpercent'] = $row['wtaxp'];
                    $pupdate['ao_wvatstatus'] = $data['or_wvatstatus'];
                    $pupdate['ao_wvatamt'] = $row['wvat'] + $getamt['ao_wvatamt'];
                    $pupdate['ao_wvatpercent'] = $row['wvatp'];
                    $pupdate['ao_ppdstatus'] = $data['or_ppdstatus'];
                    $pupdate['ao_ppdamt'] = $row['ppd'] + $getamt['ao_ppdamt'];
                    $pupdate['ao_ppdpercent'] = $row['ppdp'];
                    $pupdate['ao_ornum'] = $id;
                    $pupdate['ao_ordate'] = $data['or_date'];                
                    $pupdate['ao_oramt'] = ($getamt['ao_oramt'] + $row['appliedamt']);
                    $xtotalpay = ($getamt['ao_oramt'] + $row['appliedamt']) + $getamt['ao_dcamt'];
                    $pupdate['is_payed'] = 0;
                    if ($xtotalpay == $row['ao_amt']) {
                        $pupdate['is_payed'] = 1;
                    }
                    $this->db->update('ao_p_tm', $pupdate, array('id' => $row['aoptmid']));   
                    $this->db->insert('or_d_tm', $insertdata);       
                    $x += 1;                        
                }      
            }                     
        }
        return true;
    }
    
    public function updateOR($data, $id, $hkey) {
        $data['edited_n'] = $this->session->userdata('sess_id');       
        $data['edited_d'] = date('Y-m-d h:i:s');  
          
        $stmt = "SELECT * FROM temp_payment_applied WHERE mykeyid = '$hkey';";
        $result = $this->db->query($stmt)->row_array();
        if (!empty($result)) {
            $data['is_applied'] = 1;    
        }
        
        $this->db->where('or_num', $id);
        $this->db->update('or_m_tm', $data);
        
        return true;        
    }
    
    public function findORLookUp($find) {
        $con_ornumber = ''; $con_ordate = ''; $con_orpayeecode = '';
        $con_orpayeename = ''; $con_orcollectorcashier = ''; $con_orbank = ''; $con_orbranch = ''; $con_orparticulars = '';     
        
        if ($find['ordatefrom'] != "" || $find['ordateto'] != "") {
                $con_dateby = "";
                $con_ordate = "AND (DATE(or_m_tm.or_date) >= '".$find['ordatefrom']."' AND DATE(or_m_tm.or_date) <= '".$find['ordateto']."')";   
        } 
            
        if (!empty($find['ornumber'])) { $con_ornumber = "AND or_m_tm.or_num = '".$find['ornumber']."'"; }        
        if (!empty($find['orpayeecode'])) { $con_orpayeecode = "AND or_m_tm.or_cmf LIKE '".$find['orpayeecode']."%'"; }
        if (!empty($find['orpayeename'])) { $con_orpayeename = "AND or_m_tm.or_payee LIKE '".$find['orpayeename']."%'"; }
        if (!empty($find['orcollectorcashier'])) { $con_orcollectorcashier = "AND or_m_tm.or_ccf = '".$find['orcollectorcashier']."'"; }
        if (!empty($find['orbank'])) { $con_orbank = "AND or_m_tm.or_bnacc = '".$find['orbank']."'"; }
        if (!empty($find['orbranch'])) { $con_orbranch = "AND or_m_tm.or_branch = '".$find['orbranch']."'"; }
        if (!empty($find['orparticulars'])) { $con_orparticulars = "AND or_m_tm.or_part LIKE '".$find['orparticulars']."%'"; }      
        
        $stmt = "SELECT or_m_tm.or_num, DATE(or_date) AS or_date, or_m_tm.or_cmf, or_m_tm.or_amf, or_payee,
                       or_m_tm.or_ccf, or_m_tm.or_bnacc, or_m_tm.or_branch, or_m_tm.or_part, or_m_tm.or_comment,
                       CONCAT(users.firstname, ' ',SUBSTRING(users.middlename,1,1), '. ',users.lastname) AS ccf,
                       misbmf.bmf_name AS bank, misbbf.bbf_bnch AS branch 
                FROM or_m_tm
                LEFT OUTER JOIN users ON users.id = or_m_tm.or_ccf       
                LEFT OUTER JOIN misbmf ON misbmf.id = or_m_tm.or_bnacc
                LEFT OUTER JOIN misbbf ON misbbf.id = or_m_tm.or_branch
                WHERE or_m_tm.or_num IS NOT NULL $con_ordate $con_ornumber $con_orpayeecode $con_orpayeename 
                $con_orcollectorcashier $con_orbank $con_orbranch $con_orparticulars ORDER BY or_m_tm.or_num ASC";  
         
         $result = $this->db->query($stmt)->result_array();
         
         return $result;                
    
    }
    
    public function getAppliedList($id) {
        # -- or_d_tm.or_docbal AS bal, 
        $stmt = " SELECT or_d_tm.or_docitemid AS id, or_d_tm.or_item_id, misprod.prod_name,
                       DATE(or_d_tm.or_issuefrom) AS or_issuefrom, or_d_tm.or_docpart, DATE(or_m_tm.or_date) AS or_date,
                       FORMAT(or_amt, 2) AS or_amt, or_d_tm.or_assignwtaxamt, or_d_tm.or_assignwvatamt, or_d_tm.or_assignppdamt,
                       or_d_tm.or_wtaxpercent, or_d_tm.or_wvatpercent, or_d_tm.or_ppdpercent, 
                       (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)))  AS bal,
				   IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) as amountdue,	                                            
                       or_d_tm.or_assignamt,
                       ao_p_tm.ao_sinum, ao_p_tm.ao_amt, (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) AS totalordc
                FROM or_d_tm   
                INNER JOIN misprod ON or_d_tm.or_prod =  misprod.id
                INNER JOIN or_m_tm ON or_d_tm.or_num = or_m_tm.or_num  
                INNER JOIN ao_p_tm ON or_d_tm.or_docitemid = ao_p_tm.id
                WHERE or_d_tm.or_num = '$id'";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function resetApplied($hkey, $id) {
        
        $array = array('bal' => 0,
                       'appliedamt' => 0,
                       'wvat' => 0,
                       'wvatp' => 0,
                       'wtax' => 0,
                       'wtaxp' => 0,
                       'ppd' => 0,
                       'ppdp' => 0);
       $this->db->update('temp_payment_applied', $array, array('mykeyid' => $hkey, 'aoptmid' => $id));
        
        return true;
    }
    
    public function insertPaymentApplied($id, $hkey) {
        $stmt = "SELECT or_docitemid, or_docbal,or_assignamt, 
                       or_assignwvatamt, or_assignwtaxamt, or_assignppdamt,
                       or_wvatpercent, or_wtaxpercent, or_ppdpercent
                FROM or_d_tm WHERE or_num = '$id'";

       $result = $this->db->query($stmt)->result_array();
       #aoptmid, bal, appliedamt, wvat, wvatp, wtax, wtaxp, ppd, ppdp
       if (!empty ($result) ) {
            foreach ( $result as $result) :
            $ins['mykeyid'] = $hkey;
            $ins['aoptmid'] = $result['or_docitemid'];
            $ins['bal'] = $result['or_docbal'];
            $ins['appliedamt'] = $result['or_assignamt'];     
            $ins['wvat'] = $result['or_assignwvatamt'];     
            $ins['wvatp'] = $result['or_wvatpercent'];     
            $ins['wtax'] = $result['or_assignwtaxamt'];     
            $ins['wtaxp'] = $result['or_wtaxpercent'];     
            $ins['ppd'] = $result['or_assignppdamt'];     
            $ins['ppdp'] = $result['or_ppdpercent'];     
            $ins['is_intag'] = 1; 

            $this->db->insert('temp_payment_applied', $ins);
            endforeach;
        }
        return true;
                       
    }
    
    public function insertPaymentTypes($id, $hkey) {
        $stmt = "SELECT or_num, or_item_id, or_date, or_artype, or_argroup, or_paytype,
                       or_paybank, or_paybranch, or_paynum, or_paydate, or_creditcard,
                       or_creditcardnumber, or_expirydate, or_authorizationno,
                       or_remarks, or_amt
                FROM or_p_tm WHERE or_num = '$id' ORDER BY or_item_id ASC";
        $result = $this->db->query($stmt)->result_array();
        
        if (!empty ($result) ) {
            foreach ( $result as $result) :
            $ins['mykeyid'] = $hkey;
            $ins['id'] = $result['or_item_id'];
            $ins['type'] = $result['or_paytype'];
            $ins['bank'] = $result['or_paybank'];     
            $ins['bankbranch'] = $result['or_paybranch'];     
            $ins['checknumber'] = $result['or_paynum'];     
            $ins['checkdate'] = $result['or_paydate'];     
            $ins['creditcard'] = $result['or_creditcard'];     
            $ins['creditcardnumber'] = $result['or_creditcardnumber'];     
            $ins['remarks'] = $result['or_remarks'];     
            $ins['authorizationno'] = $result['or_authorizationno'];     
            $ins['expirydate'] = $result['or_expirydate'];     
            $ins['amount'] = $result['or_amt']; 
                
            $ins['tag_type'] = $result['or_paytype'];     
            $ins['tag_bank'] = $result['or_paybank'];     
            $ins['tag_bankbranch'] = $result['or_paybranch'];     
            $ins['tag_checknumber'] = $result['or_paynum'];     
            $ins['tag_checkdate'] = $result['or_paydate'];     
            $ins['tag_creditcard'] = $result['or_creditcard'];     
            $ins['tag_creditcardnumber'] = $result['or_creditcardnumber'];     
            $ins['tag_remarks'] = $result['or_remarks'];     
            $ins['tag_authorizationno'] = $result['or_authorizationno'];     
            $ins['tag_expirydate'] = $result['or_expirydate'];     
            $ins['tag_amount'] = $result['or_amt'];                      
            $ins['is_tag'] = 1;    
            
            $this->db->insert('temp_payment_types', $ins);
            endforeach;
        }
        return true;
       //var_dump($result);
    }
    
    public function getPayment($id) {
        $stmt = "SELECT or_num, DATE(or_date) AS or_date, or_prnum, DATE(or_prdate) AS or_prdate, or_artype,
                       or_subtype, or_type, or_adtype, or_ccf,
                       or_amf, or_cmf, or_payee, or_title,
                       or_prod, IFNULL(or_branch, 0) AS or_branch, or_add1, or_add2, or_add3,
                       or_telprefix1, or_telprefix2, or_tel1, or_tel2,
                       or_faxprefix, or_fax, or_celprefix, or_cel,
                       or_tin, or_zip, or_cardholder, or_cmfvatcode,
                       or_cmfvatrate, IFNULL(or_bnacc, 0) AS or_bnacc, or_gov, or_wtaxcertificate, 
                       FORMAT(or_amt, 2) AS or_amt, or_amtword,
                       FORMAT(or_vatsales, 2) AS or_vatsales, FORMAT(or_vatexempt, 2) AS or_vatexempt, 
                       FORMAT(or_vatzero, 2) AS or_vatzero, FORMAT(or_grossamt, 2) AS or_grossamt, 
                       FORMAT(or_vatamt, 2) AS or_vatamt, FORMAT(or_wtaxamt, 2) AS or_wtaxamt, FORMAT(or_wvatamt, 2) AS or_wvatamt, 
                       FORMAT(or_ppdamt, 2) AS or_ppdamt, FORMAT(or_wtaxpercent, 2) AS or_wtaxpercent,
                       FORMAT(or_wvatpercent, 2) AS or_wvatpercent, FORMAT(or_ppdpercent, 2) AS or_ppdpercent, 
                       FORMAT(or_notarialfee, 2) AS or_notarialfee, FORMAT(or_assignamt, 2) AS or_assignamt,
                       FORMAT(or_assigngrossamt, 2) AS or_assigngrossamt, FORMAT(or_assignvatamt, 2) AS or_assignvatamt, 
                       FORMAT(or_assignwtaxamt, 2) AS or_assignwtaxamt, FORMAT(or_assignwvatamt, 2) AS or_assignwvatamt,
                       FORMAT(or_assignppdamt, 2) AS or_assignppdamt, or_vatstatus, or_wtaxstatus, or_wvatstatus, or_ppdstatus,
                       FORMAT(or_wvatamt + or_wtaxamt, 2) AS withholding,
                       or_part, or_comment, IFNULL(or_amf, 2) AS choose, is_applied, status 
                FROM or_m_tm 
                WHERE or_num = '$id'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function savePaymentApplied($data,$value,$vatrate) {
        $stmt = "SELECT t.aoptmid, t.appliedamt, t.wvat, t.wvatp, t.wtax, t.wtaxp, t.ppd, t.ppdp, t.bal,
                       p.ao_prod, p.ao_prodissue, p.ao_issuefrom, p.ao_issueto, p.ao_num,
                       p.ao_part_billing, p.ao_adtyperate_code, p.ao_width, p.ao_length, 
                       p.ao_cmfvatcode, p.ao_cmfvatrate, p.ao_date, p.ao_amt
                FROM temp_payment_applied AS t
                INNER JOIN ao_p_tm AS p ON t.aoptmid = p.id
                WHERE t.mykeyid = '".$value['hkey']."' AND t.appliedamt != 0";
                 
        $result = $this->db->query($stmt)->result_array();
        if (!empty($result)) {
            $batchinsert = array();
            $x = 1;

            foreach ($result as $row) {

                $assign = $row['appliedamt'];                
                $wtax = $row['wtax'];                
                $wvat = $row['wvat'];                
                $ppd = $row['ppd'];                
                //$g = ($assign + $wtax + $wvat + $ppd) / (1 + ($vatrate/100));                
                $g = ($assign + $wtax + $wvat + $ppd) / (1 + ($row['ao_cmfvatrate']/100));                
                //$vatamt = round($g * ($vatrate/100), 2);      
                $vatamt = round($g * ($row['ao_cmfvatrate']/100), 2);      
                $grossamt = round($g, 2);                
                $batchinsert[] = array('or_num' => $data['or_num'],
                                       'or_item_id' => $x,
                                       'or_date' => $data['or_date'],
                                       'or_artype' => $data['or_artype'],
                                       'or_argroup' => $data['or_argroup'],   
                                       'or_prod' => $row['ao_prod'],
                                       'or_prodissue' => $row['ao_prodissue'],
                                       'or_issuefrom' => $row['ao_issuefrom'],
                                       'or_issueto' => $row['ao_issueto'],
                                       'or_doctype' => $data['or_doctype'],
                                       'or_docnum' => $row['ao_num'],
                                       'or_docamt' => $data['or_amt'],
                                       'or_docitemid' => $row['aoptmid'],
                                       'or_docpart' => $row['ao_part_billing'],
                                       'or_adtype' => $row['ao_adtyperate_code'],
                                       'or_width' => $row['ao_width'],
                                       'or_length' => $row['ao_length'],
                                       'or_docbal' => $row['bal'],
                                       'or_assignamt' => $assign,
                                       'or_assigngrossamt' => $grossamt,
                                       'or_assignvatamt' => $vatamt,
                                       'or_assignwtaxamt' => $row['wtax'],
                                       'or_assignwvatamt' => $row['wvat'],
                                       'or_assignppdamt' =>$row['ppd'], 
                                       'or_cmfvatcode' => $row['ao_cmfvatcode'],
                                       'or_cmfvatrate' => $row['ao_cmfvatrate'],
                                       'or_wtaxpercent' => $row['wtaxp'],
                                       'or_wvatpercent' => $row['wvatp'],
                                       'or_ppdpercent' => $row['ppdp'],
                                       'user_n' => $this->session->userdata('authsess')->sess_id,
                                       'edited_n' => $this->session->userdata('authsess')->sess_id,
                                       'edited_d' => DATE('Y-m-d h:i:s'));
                
                
                $stmt2 = "SELECT ao_oramt, ao_dcamt, ao_wtaxamt, ao_wvatamt, ao_ppdamt FROM ao_p_tm WHERE id = '".$row['aoptmid']."'";
                $getamt = $this->db->query($stmt2)->row_array();
                
                $pupdate['ao_wtaxstatus'] = $data['or_wtaxstatus'];
                $pupdate['ao_wtaxamt'] = $row['wtax'] + $getamt['ao_wtaxamt'];
                $pupdate['ao_wtaxpercent'] = $row['wtaxp'];
                $pupdate['ao_wvatstatus'] = $data['or_wvatstatus'];
                $pupdate['ao_wvatamt'] = $row['wvat'] + $getamt['ao_wvatamt'];
                $pupdate['ao_wvatpercent'] = $row['wvatp'];
                $pupdate['ao_ppdstatus'] = $data['or_ppdstatus'];
                $pupdate['ao_ppdamt'] = $row['ppd'] + $getamt['ao_ppdamt'];
                $pupdate['ao_ppdpercent'] = $row['ppdp'];
                $pupdate['ao_ornum'] = $data['or_num'];
                $pupdate['ao_ordate'] = $data['or_date'];
                $pupdate['ao_oramt'] = ($getamt['ao_oramt'] + $row['appliedamt']);
                $xtotalpay = ($getamt['ao_oramt'] + $row['appliedamt']) + $getamt['ao_dcamt'];
                $pupdate['is_payed'] = 0;
                if ($xtotalpay == $row['ao_amt']) {
                    $pupdate['is_payed'] = 1;
                }
               # var_dump($pupdate);
                $this->db->update('ao_p_tm', $pupdate, array('id' => $row['aoptmid']));   
                
                //$stmt3 = "SELECT ao_oramt,  FROM ao_p_tm WHERE id = '".$row['aoptmid']."'";
                //$getoranddc = $this->db->query($stmt2)->row_array();
                
                $x += 1;
            }
            #$data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
            #$data['edited_d'] = DATE('Y-m-d h:m:s');                                       
            //echo "<pre>";
            //var_dump($batchinsert);          
            $this->db->insert_batch('or_d_tm', $batchinsert);            
        }
    }
    
    public function saveTempApplied($temp) {
        
        /*$val = "SELECT * FROM temp_payment_applied WHERE mykeyid='".$temp['mykeyid']."' AND aoptmid='".$temp['aoptmid']."'";
        
        $resultVal = $this->db->query($val)->row_array();
        print_r2($resultVal); exit;
        if (empty($resultVal)) {
            $this->db->insert('temp_payment_applied', $temp);
        } else {
            if ($resultVal['is_intag'] == 1) {
                $this->db->update('temp_payment_applied', $temp, array('mykeyid' => $temp['mykeyid']));
            } else {
                $this->db->insert('temp_payment_applied', $temp);
            }
        }*/
        $this->db->insert('temp_payment_applied', $temp);    
        
        return true;
    }
    
    public function getAppliedData($id) {
        $stmt = "SELECT ao_p_tm.id, ao_p_tm.ao_num, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom ,  DATE(ao_p_tm.ao_sidate) AS ao_sidate , 
                       ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_name,
                       ao_p_tm.ao_part_billing, ao_m_tm.ao_cmf, ao_m_tm.ao_payee,
                       (IFNULL(ao_p_tm.ao_amt, 0.00)) AS amt , FORMAT(IFNULL(ao_p_tm.ao_oramt, 0.00), 2) AS applied , DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, 
                       FORMAT((IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))), 2) AS bal,
				       IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) as amountdue,
                       IFNULL(ao_p_tm.ao_wtaxstatus, 0) AS ao_wtaxstatus, IFNULL(ao_p_tm.ao_wtaxamt, 0.00) AS ao_wtaxamt, IFNULL(ao_p_tm.ao_wtaxpercent, 0.00) AS ao_wtaxpercent, ao_p_tm.ao_wtaxpart,
                       IFNULL(ao_p_tm.ao_wvatstatus, 0) AS ao_wvatstatus, IFNULL(ao_p_tm.ao_wvatamt, 0.00) AS ao_wvatamt, IFNULL(ao_p_tm.ao_wvatpercent, 0.00) AS ao_wvatpercent, ao_p_tm.ao_wvatpart,
                       IFNULL(ao_p_tm.ao_ppdstatus, 0) AS ao_ppdstatus, IFNULL(ao_p_tm.ao_ppdamt, 0.00) AS ao_ppdamt, IFNULL(ao_p_tm.ao_ppdpercent, 0.00) AS ao_ppdpercent, ao_p_tm.ao_ppdpart,
                       is_payed                       
                FROM ao_p_tm
                INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                INNER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
                WHERE ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1 AND ao_p_tm.id = '$id'";
                #ao_p_tm.is_payed = 0 AND 
        $result = $this->db->query($stmt)->row_array();
        
        return $result;                
    }
    
    public function getDMAppliedData($id) {
        $stmt = "SELECT dcm.dc_num, DATE(dcm.dc_date) AS dc_date , dcm.dc_payee, dcm.dc_payeename, dcm.dc_amt, dcm.dc_assignamt, dcm.dc_adtype,
                       adtype.adtype_name, (IFNULL(dcm.dc_amt, 0) - IFNULL(dcm.dc_assignamt, 0)) AS bal
                FROM dc_m_tm AS dcm 
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dcm.dc_adtype
                WHERE dcm.dc_num = '$id' AND dcm.dc_type = 'D' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcm.dc_assignamt, 0))";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;            
    }
    
    public function retrieveApplied($code,$type, $choose, $id) {
        $con = '';   
        if ($id != '' || $id != 0) {
            $conexisting = "";
        } else {
            $conexisting = " AND ao_p_tm.id NOT IN (SELECT or_d_tm.or_docitemid FROM or_d_tm       
                                                WHERE or_d_tm.or_num = '$id')";     
        }     
        switch($type) {
            case 'C' :
                // client done
                $con = "AND ao_m_tm.ao_cmf = '$code'"; #" AND (ao_m_tm.ao_amf IS NULL OR ao_m_tm.ao_amf = 0)";   
            break;
            case 'A':
                // agency done               
                if ($choose == '2') {
                $con = "AND ao_m_tm.ao_cmf = '$code' AND ao_m_tm.ao_amf != 0";
                } else if ($choose == '1') {
                $con = "AND ao_m_tm.ao_amf IN (SELECT id FROM miscmf WHERE cmf_code = '$code')";   
                }
            break;
            case 'MA':
                // main agency 
                
                $stmtmc = "SELECT id FROM miscmf WHERE cmf_code = '$code'";
                $r = $this->db->query($stmtmc)->row_array();
                
                if (empty($r)) {       
                $con = "AND ao_m_tm.ao_amf IN (SELECT cmf_code FROM miscmf WHERE id IN (
                       SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = (
                       SELECT id FROM miscmfgroup WHERE cmfgroup_code = '$code')))";                        
                } else {
                $con = "AND ao_m_tm.ao_amf IN (SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = ( 
                        SELECT cmfgroup_code FROM miscmfgroupaccess WHERE cmf_code = (
                        SELECT id FROM miscmf WHERE cmf_code = '$code')))";      
                } 
                                        
            break;
            case 'MC':
                // main client       
                
                $stmtmc = "SELECT id FROM miscmf WHERE cmf_code = '$code'";
                $r = $this->db->query($stmtmc)->row_array();
                
                if (empty($r)) {
                $con = "AND ao_m_tm.ao_cmf IN (SELECT cmf_code FROM miscmf WHERE id IN (
                       SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = (
                       SELECT id FROM miscmfgroup WHERE cmfgroup_code = '$code')))";    
                } else {
                $con = "AND ao_m_tm.ao_cmf IN (SELECT cmf_code FROM miscmf WHERE id IN (                
                       SELECT cmf_code FROM miscmfgroupaccess 
                       WHERE cmfgroup_code = (SELECT cmfgroup_code FROM miscmfgroupaccess WHERE cmf_code = (
                       SELECT id FROM miscmf WHERE cmf_code = '$code'))))";        
                }                
                
                //}
            break;
        }
        $stmt = "SELECT ao_p_tm.id, ao_p_tm.ao_num, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom , ao_p_tm.ao_type, 
                       IFNULL(miscmf.cmf_code, '') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname, 
                       ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_name,
                       ao_p_tm.ao_part_billing, ao_m_tm.ao_cmf, ao_m_tm.ao_payee,
                       IFNULL(ao_p_tm.ao_amt, 0.00) AS amt , (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) AS totalordc , DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, 
                       (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal,
                       IFNULL(ao_p_tm.ao_wtaxstatus, 0) AS ao_wtaxstatus, IFNULL(ao_p_tm.ao_wtaxamt, 0.00) AS ao_wtaxamt, IFNULL(ao_p_tm.ao_wtaxpercent, 0.00) AS ao_wtaxpercent, ao_p_tm.ao_wtaxpart,
                       IFNULL(ao_p_tm.ao_wvatstatus, 0) AS ao_wvatstatus, IFNULL(ao_p_tm.ao_wvatamt, 0.00) AS ao_wvatamt, IFNULL(ao_p_tm.ao_wvatpercent, 0.00) AS ao_wvatpercent, ao_p_tm.ao_wvatpart,
                       IFNULL(ao_p_tm.ao_ppdstatus, 0) AS ao_ppdstatus, IFNULL(ao_p_tm.ao_ppdamt, 0.00) AS ao_ppdamt, IFNULL(ao_p_tm.ao_ppdpercent, 0.00) AS ao_ppdpercent, ao_p_tm.ao_ppdpart
                FROM ao_p_tm
                INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                INNER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
                LEFT OUTER JOIN miscmf ON miscmf.id =  ao_m_tm.ao_amf
                WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1  AND ao_p_tm.is_payed = 0
                $con $conexisting                
                ORDER BY miscmf.cmf_code, ao_m_tm.ao_cmf, ao_p_tm.ao_sinum, ao_p_tm.ao_issuefrom ASC";
        $result = $this->db->query($stmt)->result_array();
        $resultcmf = array();

        
        foreach ($result as $row) {                    
            $resultcmf["<span style='color:#FF4E22'>".$row['agencycode']." ".$row['agencyname']."</span>".$row['ao_cmf'].' - '.$row['ao_payee']][] = $row;             
        }
        #var_dump($resultcmf);            
        #foreach ($resultcmf as $x => $cmf) {
        #    echo var_dump($x);
        #    var_dump($cmf);
        #}
        
        #exit;
        return $resultcmf;
    }
    
    public function validateORNumber($ornum) {
        
        $stmt = "SELECT or_num FROM or_m_tm WHERE or_num = $ornum";
                                                                  
        $result = $this->db->query($stmt)->row();
        
        return !empty($result) ? true : false;
    }
    
    public function savePaymentType($value) {
        $stmt = "SELECT tag_type, tag_bank, tag_bankbranch, tag_checknumber, tag_checkdate, tag_creditcard, tag_creditcardnumber,
                       tag_authorizationno, tag_expirydate, tag_amount, tag_remarks 
                FROM temp_payment_types WHERE mykeyid = '".$value['hkey']."' AND is_temp_delete = 0";
        $result = $this->db->query($stmt)->result_array();
        $x = 1;
        foreach ($result as $result) {

            $data['or_num'] = $value['or_num'];
            $data['or_item_id'] = $x;
            $data['or_date'] = $value['or_date'];
            $data['or_artype'] = $value['or_artype'];
            $data['or_argroup'] = $value['or_argroup'];
            $data['or_paytype'] = $result['tag_type'];
            $data['or_paybank'] = $result['tag_bank'];
            $data['or_paybranch'] = $result['tag_bankbranch'];
            
            $data['or_paynum'] = $result['tag_checknumber'];         
            $data['or_paydate'] = $result['tag_checkdate'];         
            
            $data['or_creditcard'] = $result['tag_creditcard'];
            $data['or_creditcardnumber'] = $result['tag_creditcardnumber'];
            
            
            $data['or_remarks'] = $result['tag_remarks'];
            $data['or_authorizationno'] = $result['tag_authorizationno'];
            $data['or_expirydate'] = $result['tag_expirydate'];            
            
            $data['or_amt'] = $result['tag_amount'];            
            
            $data['user_n'] = $this->session->userdata('authsess')->sess_id;                                              
            $data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
            $data['edited_d'] = DATE('Y-m-d h:i:s');                                       
            $x += 1;
            
            $this->db->insert('or_p_tm', $data);
        }
        return true;
    }    
    
    public function insertRevenuePaymentTypes($revData, $hkey) {  
    
                    /*WHEN 'CH' THEN 'Cash' 
                    WHEN 'CK' THEN 'Check' 
                    WHEN 'CC' THEN 'Credit Card'       */
        $data['mykeyid'] = $hkey;
        $data['id'] = 1;
        $data['type'] = $revData['ao_paytype'];
        $data['tag_type'] = $revData['ao_paytype'];
        #echo $revData['ao_paytype'];
        #exit;
        if ($revData['ao_paytype'] == 'CK') {
        $data['checknumber'] = $revData['ao_cardnumber'];
        $data['tag_checknumber'] = $revData['ao_cardnumber'];
        $data['checkdate'] = date("Y-m-d", strtotime($revData['ao_expirydate'])); 
        $data['tag_checkdate'] = date("Y-m-d", strtotime($revData['ao_expirydate'])); 
        }
        if ($revData['ao_paytype'] == 'CC') {
        $data['creditcard'] = $revData['ao_cardtype'];
        $data['tag_creditcard'] = $revData['ao_cardtype'];
        $data['creditcardnumber'] = $revData['ao_cardnumber'];
        $data['tag_creditcardnumber'] = $revData['ao_cardnumber'];
        $data['authorizationno'] = $revData['ao_authorisationno'];
        $data['tag_authorizationno'] = $revData['ao_authorisationno'];
        $data['expirydate'] = date("Y-m-d", strtotime($revData['ao_expirydate']));
        $data['tag_expirydate'] = date("Y-m-d", strtotime($revData['ao_expirydate']));   
        }
        $data['amount'] = $revData['ao_amt'];
        $data['tag_amount'] = $revData['ao_amt'];
        $data['is_tag'] = 1;      
        $this->db->insert('temp_payment_types', $data);
        
        return TRUE;
    }    
    
    public function revenueImportData($rev_aonumber) {
        $stmt = "SELECT  ao_num, ao_adtype, ao_payee, ao_title, ao_add1, ao_add2, ao_add3, ao_tin,
                         ao_zip, ao_telprefix1, ao_telprefix2, ao_tel1, ao_tel2,
                         ao_faxprefix, ao_fax, ao_celprefix, ao_cel, ao_amt, FORMAT(ao_vatsales, 2) AS ao_vatsales, 
                         FORMAT(IFNULL(ao_vatexempt, '0'), 2) AS ao_vatexempt, FORMAT(ao_vatzero, 2) AS ao_vatzero,
                         ao_cmfvatcode, FORMAT(ao_vatamt, 2) AS ao_vatamt, ao_cmfvatrate, 
                         FORMAT(IFNULL(ao_wtaxamt, '0'), 2) AS ao_wtaxamt, FORMAT(IFNULL(ao_wtaxpercent, '0'), 2) AS ao_wtaxpercent,
                         FORMAT(IFNULL(ao_wvatamt, '0'), 2) AS ao_wvatamt, FORMAT(IFNULL(ao_wvatpercent, '0'), 2) AS ao_wvatpercent,     
                         FORMAT(IFNULL(ao_ppdamt, '0'), 2) AS ao_ppdamt, FORMAT(IFNULL(ao_ppdpercent, '0'), 2) AS ao_ppdpercent,     
                         CASE ao_paytype
                            WHEN '1' THEN 'X'
                            WHEN '2' THEN 'X'
                            WHEN '3' THEN 'CH'
                            WHEN '4' THEN 'CC'
                            WHEN '5' THEN 'CK'
                            WHEN '6' THEN 'X'
                         END ao_paytype, 
                         ao_cardholder, ao_cardtype, ao_cardnumber, ao_authorisationno, ao_expirydate, ao_amt
                FROM ao_m_tm WHERE (ao_paytype != '1' AND ao_paytype != '2' AND ao_paytype != '6') AND ao_num = '".$rev_aonumber."' AND ao_ornum IS NULL";
        $result = $this->db->query($stmt)->row_array();
                        
        return $result;
    }
        
	public function updateTempPaymentTempData($key, $id, $data) {
		$this->db->where(array('mykeyid' => $key,'id' => $id));
		$this->db->update('temp_payment_types', $data);                 
		return TRUE;
	}

	public function savePayment($data, $hkey) {   
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;                                              
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $data['edited_d'] = date('Y-m-d h:i:s');          

        $stmt = "SELECT * FROM temp_payment_applied WHERE mykeyid = '$hkey';";
        $result = $this->db->query($stmt)->row_array();
        if (!empty($result)) {
            $data['is_applied'] = 1;    
        }
        
		$this->db->insert('or_m_tm', $data);
		return TRUE;
	}

	public function deleteTempPaymentTypeUnTag($data) {
		$stmt = "DELETE FROM temp_payment_types WHERE mykeyid = '".$data['hkey']."' AND is_tag = '0'";
		$this->db->query($stmt);
		return TRUE;
	}
	
    public function getAmountPaid($data) {
        $stmt = "SELECT SUM(tag_amount) AS assignedamt FROM temp_payment_types  WHERE mykeyid = '".$data['hkey']."' AND is_tag = '1' AND is_temp_delete = '0'";     
        $result = $this->db->query($stmt)->row();
        return $result->assignedamt; 
    }
    
	public function getTagTempPaymentType($data) {
		$stmt = "SELECT a.id, CASE a.tag_type
					WHEN 'CH' THEN 'Cash' 
					WHEN 'CK' THEN 'Check' 
					WHEN 'CC' THEN 'Credit Card' 
					WHEN 'DD' THEN 'Direct Deposit' 
					WHEN 'EX' THEN 'Exdeal' 		
				END AS tag_type, 
				b.bmf_code AS tag_bank, c.bbf_bnch AS tag_bankbranch, a.tag_checknumber, DATE(a.tag_checkdate) AS tag_checkdate, d.creditcard_name AS tag_creditcard,
				a.tag_creditcardnumber, a.tag_remarks, a.tag_authorizationno, DATE(a.tag_expirydate) AS tag_expirydate, FORMAT(a.tag_amount, 2) AS tag_amount, a.tag_amount AS amount
				FROM temp_payment_types AS a
				LEFT OUTER JOIN misbmf AS b ON a.tag_bank = b.id
				LEFT OUTER JOIN misbbf AS c ON a.tag_bankbranch = c.id
				LEFT OUTER JOIN miscreditcard AS d ON a.tag_creditcard = d.id
		        WHERE a.mykeyid = '".$data['hkey']."' AND a.is_tag = '1' AND a.is_temp_delete = '0'";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}

	public function insertTempPaymentType($data) {
		$stmt = "INSERT INTO temp_payment_types (mykeyid, id) VALUES('".$data['hkey']."', '".$data['id']."')";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function getTempPaymentTypeID($data) {
		$stmt = "SELECT IFNULL(MAX(id), 0) + 1 AS latestid FROM temp_payment_types WHERE mykeyid = '".$data['hkey']."' AND is_temp_delete = 0";
		$result = $this->db->query($stmt)->row();
		return $result->latestid;
	}
	
	public function getTempPaymentType($data) {
		$stmt = "SELECT id, type, tag_type, bank, bankbranch, checknumber, checkdate, creditcard, creditcardnumber,
					    remarks, authorizationno, expirydate, FORMAT(amount, 2) AS amount
				 FROM temp_payment_types WHERE mykeyid = '".$data['hkey']."' AND is_temp_delete = 0";		
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function removeTempPayment($data) {
		$stmt = "UPDATE temp_payment_types SET is_temp_delete = '1', is_tag = '0' WHERE mykeyid = '".$data['hkey']."' AND id = '".$data['id']."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateTempPaymentTypeTemporary($data) {
		$stmt = "UPDATE temp_payment_types SET type = '".$data['typeselect']."' WHERE mykeyid = '".$data['hkey']."' AND id = '".$data['id']."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateTempPaymentTypeIsTag($data) {
		$stmt = "SELECT id, type, bank, bankbranch, checknumber, checkdate, creditcard, creditcardnumber,
				 remarks, authorizationno, expirydate, amount
		         FROM temp_payment_types AS b WHERE mykeyid = '".$data['hkey']."' AND is_temp_delete = 0";
		$result = $this->db->query($stmt)->result_array();		
		if (!empty($result)) {
			foreach ($result as $row) {
				$stmt2 = "UPDATE temp_payment_types SET 
							tag_type = '".$row['type']."',
							tag_bank = '".$row['bank']."',
							tag_bankbranch = '".$row['bankbranch']."',
							tag_checknumber = '".$row['checknumber']."',
							tag_checkdate = '".$row['checkdate']."',
							tag_creditcard = '".$row['creditcard']."',
							tag_creditcardnumber = '".$row['creditcardnumber']."',
							tag_remarks = '".$row['remarks']."',
							tag_authorizationno = '".$row['authorizationno']."',
							tag_expirydate = '".$row['expirydate']."',
							tag_amount = '".$row['amount']."',
							is_tag = '1' 
						  WHERE mykeyid = '".$data['hkey']."' AND id = '".$row['id']."' ";
				$this->db->query($stmt2);
			}
		
			/*$id = substr($id, 0,-1);
			$stmt2 = "UPDATE temp_payment_types SET is_tag = '1' 
					  WHERE mykeyid = '".$data['hkey']."'
					  AND id IN (".$id.") ";
			$this->db->query($stmt2);*/
		}
		return TRUE;
	}
}
?>
