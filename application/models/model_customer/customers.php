<?php

class Customers extends CI_Model {
    
    public function getCustomerMasterfile($categorytype, $reporttype, $branch, $paytype, $ae, $coll, $collassts, $collarea, $ind, $advcode, $advname, $datefrom, $dateto) {
        
        $concat = ""; $conae = ""; $conbranch = ""; $conpaytype = ""; $concoll = ""; $concollarea = "";  $concollassts = ""; $conadvcode = ""; $conadvname = "";  $condate = ""; $conind = "";
        $dfrom = "1985-01-01";
        $dto = date('Y-m-d');
        if ($categorytype != 0) {
            $concat = "AND a.cmf_catad = $categorytype";    
        }
        
        if ($ae != 0) {
            $conae = "AND a.cmf_aef = $ae";    
        }
        
        if ($collassts != 0) {
            $concollassts = "AND a.cmf_collasst = $collassts";    
        }
        
        if ($coll != 0) {
            $concoll = "AND a.cmf_coll = $coll";    
        }
        
        if ($ind != 0) {
            $conind = "AND a.cmf_industry = $ind";    
        }
        
        if ($collarea != 0) {
            $concollarea = "AND a.cmf_collarea = $collarea";    
        }
        
        if ($branch != 0) {
            $conbranch = "AND a.cmf_branch = $branch";    
        }
        
        if ($paytype != 0) {
            $conpaytype = "AND a.cmf_paytype = $paytype";    
        }
        
        if ($advcode != "x") {
            $conadvcode = "AND a.cmf_code LIKE '$advcode%'";    
        }
        
        if ($advname != "x") {
            $conadvname = "AND a.cmf_name LIKE '$advname%'";        
        }
       
        if ($datefrom != "x" && $dateto == "x") {
            $condate = "AND DATE(a.user_d) >= '$datefrom' AND DATE(a.user_d) <= '$dto'";           
        } else if ($datefrom == "x" && $dateto != "x") { 
            $condate = "AND DATE(a.user_d) >= '$dfrom' AND DATE(a.user_d) <= '$dateto'";           
        } else if ($datefrom != "x" && $dateto != "x") {     
            $condate = "AND DATE(a.user_d) >= '$datefrom' AND DATE(a.user_d) <= '$dateto'";    
        } else {
             $condate = "AND DATE(a.user_d) >= '$dfrom' AND DATE(a.user_d) <= '$dto'";        
        }
        
        
        if ($reporttype == 1) {
            $stmt = "SELECT a.cmf_code, a.cmf_name, emp.empprofile_code, CONCAT(us.firstname,' ',SUBSTR(us.middlename, 1, 1),'. ',us.lastname) AS employee,
                           CONCAT(a.cmf_add1,', ' ,a.cmf_add2,', ',a.cmf_add3) AS address, CONCAT(a.cmf_telprefix1,-a.cmf_tel2) AS tel_number,
                           a.cmf_zero,a.cmf_thirty,a.cmf_sixty,a.cmf_ninety,a.cmf_onetwenty,a.cmf_overonetwenty,a.cmf_overpayment,a.cmf_tin AS tin_number,
                           catad.catad_name, bran.branch_code, pay.paytype_name, 
                           CASE a.cmf_gov 
                        WHEN 0 THEN 'Non-Gov'
                        WHEN 1 THEN 'Gov'
                           END AS govstat,
                           ind.ind_code AS indcode, IFNULL(ind.ind_name, ' NO INDUSTRY TAG') AS indname               
                    FROM miscmf AS a
                    LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = a.cmf_aef
                    LEFT OUTER JOIN users AS us ON us.id = a.cmf_aef
                    LEFT OUTER JOIN miscatad AS catad ON catad.id = a.cmf_catad
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = a.cmf_branch
                    LEFT OUTER JOIN mispaytype AS pay ON pay.id = a.cmf_paytype
                    LEFT OUTER JOIN misindustry AS ind ON ind.id = a.cmf_industry 
                    WHERE a.is_deleted = 0 $concat $conae $conbranch  $conpaytype $conadvcode $conadvname 
                    $condate
                    ORDER BY employee, a.cmf_name";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            $newresult = array();
            
            foreach ($result as $row) {
                $newresult[$row['empprofile_code'].' - '.$row['employee']][] = $row;
            }
            
            return $newresult;
            
        } else if ($reporttype == 8) {
            
            $stmt = "SELECT a.cmf_code, a.cmf_name, emp.empprofile_code, CONCAT(us.firstname,' ',SUBSTR(us.middlename, 1, 1),'. ',us.lastname) AS employee,
                           CONCAT(a.cmf_add1,', ' ,a.cmf_add2,', ',a.cmf_add3) AS address, CONCAT(a.cmf_telprefix1,-a.cmf_tel2) AS tel_number,
                           a.cmf_zero,a.cmf_thirty,a.cmf_sixty,a.cmf_ninety,a.cmf_onetwenty,a.cmf_overonetwenty,a.cmf_overpayment,a.cmf_tin AS tin_number,
                           catad.catad_name, bran.branch_code, pay.paytype_name, 
                           CASE a.cmf_gov 
                        WHEN 0 THEN 'Non-Gov'
                        WHEN 1 THEN 'Gov'
                           END AS govstat,
                           ind.ind_code AS indcode, IFNULL(ind.ind_name, ' NO INDUSTRY TAG') AS indname    
                    FROM miscmf AS a
                    LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = a.cmf_aef
                    LEFT OUTER JOIN users AS us ON us.id = a.cmf_aef
                    LEFT OUTER JOIN miscatad AS catad ON catad.id = a.cmf_catad
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = a.cmf_branch
                    LEFT OUTER JOIN mispaytype AS pay ON pay.id = a.cmf_paytype
                    LEFT OUTER JOIN misindustry AS ind ON ind.id = a.cmf_industry
                    WHERE a.is_deleted = 0 $concat $conind $conbranch  $conpaytype $conadvcode $conadvname 
                    $condate
                    ORDER BY indname, a.cmf_name ";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            $newresult = array();
            
            foreach ($result as $row) {
                $newresult[$row['indcode'].' - '.$row['indname']][] = $row;
            }
            
            return $newresult;
                
        }  else if ($reporttype == 2) {
 
            $stmt = "SELECT a.cmf_code, a.cmf_name, emp.empprofile_code, CONCAT(us.firstname,' ',SUBSTR(us.middlename, 1, 1),'. ',us.lastname) AS employee,
                            CONCAT(a.cmf_add1,', ' ,a.cmf_add2,', ',a.cmf_add3) AS address, CONCAT(a.cmf_telprefix1,-a.cmf_tel2) AS tel_number,
                            a.cmf_zero,a.cmf_thirty,a.cmf_sixty,a.cmf_ninety,a.cmf_onetwenty,a.cmf_overonetwenty,a.cmf_overpayment,a.cmf_tin AS tin_number,
                            catad.catad_name, bran.branch_code, pay.paytype_name, 
                           CASE a.cmf_gov 
                        WHEN 0 THEN 'Non-Gov'
                        WHEN 1 THEN 'Gov'
                           END AS govstat,
                           ind.ind_code AS indcode, IFNULL(ind.ind_name, ' NO INDUSTRY TAG') AS indname               
                    FROM miscmf AS a
                    LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = a.cmf_collasst
                    LEFT OUTER JOIN users AS us ON us.id = a.cmf_collasst
                    LEFT OUTER JOIN miscatad AS catad ON catad.id = a.cmf_catad
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = a.cmf_branch
                    LEFT OUTER JOIN mispaytype AS pay ON pay.id = a.cmf_paytype
                    LEFT OUTER JOIN misindustry AS ind ON ind.id = a.cmf_industry 
                    WHERE a.is_deleted = 0 $concat $concollassts $conbranch  $conpaytype $condate
                    ORDER BY employee, a.cmf_name";
                    
            #echo "<pre>"; echo $stmt; exit; 
            $result = $this->db->query($stmt)->result_array();
            
            $newresult = array();
            
            foreach ($result as $row) {
                $newresult[$row['empprofile_code'].' - '.$row['employee']][] = $row;
            }
            
            return $newresult;
            
        } else if ($reporttype == 3) {
            $stmt = "SELECT a.cmf_code, a.cmf_name, emp.empprofile_code, CONCAT(us.firstname,' ',SUBSTR(us.middlename, 1, 1),'. ',us.lastname) AS employee,
                           CONCAT(a.cmf_add1,', ' ,a.cmf_add2,', ',a.cmf_add3) AS address, CONCAT(a.cmf_telprefix1,-a.cmf_tel2) AS tel_number,
                           a.cmf_zero,a.cmf_thirty,a.cmf_sixty,a.cmf_ninety,a.cmf_onetwenty,a.cmf_overonetwenty,a.cmf_overpayment,a.cmf_tin AS tin_number,
                           catad.catad_name, bran.branch_code, pay.paytype_name, 
                           CASE a.cmf_gov 
                        WHEN 0 THEN 'Non-Gov'
                        WHEN 1 THEN 'Gov'
                           END AS govstat,
                           ind.ind_code AS indcode, IFNULL(ind.ind_name, ' NO INDUSTRY TAG') AS indname               
                    FROM miscmf AS a
                    LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = a.cmf_coll
                    LEFT OUTER JOIN users AS us ON us.id = a.cmf_coll
                    LEFT OUTER JOIN miscatad AS catad ON catad.id = a.cmf_catad
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = a.cmf_branch
                    LEFT OUTER JOIN mispaytype AS pay ON pay.id = a.cmf_paytype
                    LEFT OUTER JOIN misindustry AS ind ON ind.id = a.cmf_industry 
                    WHERE a.is_deleted = 0 $concat $concoll $conbranch  $conpaytype $condate
                    ORDER BY employee, a.cmf_name";
            #echo "<pre>"; echo $stmt; exit; 
            $result = $this->db->query($stmt)->result_array();
            
            $newresult = array();
            
            foreach ($result as $row) {
                $newresult[$row['empprofile_code'].' - '.$row['employee']][] = $row;
            }
            
            return $newresult;
            
        } else if ($reporttype == 4) {
            $stmt = "SELECT a.cmf_code, a.cmf_name, collarea.collarea_code, collarea.collarea_name,
                       CONCAT(a.cmf_add1,', ' ,a.cmf_add2,', ',a.cmf_add3) AS address, CONCAT(a.cmf_telprefix1,-a.cmf_tel2) AS tel_number,
                       a.cmf_zero,a.cmf_thirty,a.cmf_sixty,a.cmf_ninety,a.cmf_onetwenty,a.cmf_overonetwenty,a.cmf_overpayment,a.cmf_tin AS tin_number,
                       catad.catad_name, bran.branch_code, pay.paytype_name, 
                       CASE a.cmf_gov 
                    WHEN 0 THEN 'Non-Gov'
                    WHEN 1 THEN 'Gov'
                       END AS govstat,
                       ind.ind_code AS indcode, IFNULL(ind.ind_name, ' NO INDUSTRY TAG') AS indname                         
                    FROM miscmf AS a
                    LEFT OUTER JOIN miscollarea AS collarea ON collarea.id = a.cmf_collarea
                    LEFT OUTER JOIN miscatad AS catad ON catad.id = a.cmf_catad
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = a.cmf_branch
                    LEFT OUTER JOIN mispaytype AS pay ON pay.id = a.cmf_paytype
                    LEFT OUTER JOIN misindustry AS ind ON ind.id = a.cmf_industry 
                    WHERE a.is_deleted = 0 $concat $concollarea $conbranch  $conpaytype $condate   
                    ORDER BY collarea.collarea_name, a.cmf_name";
          
            $result = $this->db->query($stmt)->result_array();
            
            $newresult = array();
            
            foreach ($result as $row) {
                $newresult[$row['collarea_code'].' - '.$row['collarea_name']][] = $row;
            }
            
            return $newresult;
            
        }   else if ($reporttype == 6) {
            $stmt = "SELECT a.cmf_code, a.cmf_name, collarea.collarea_code, collarea.collarea_name,
                       CONCAT(a.cmf_add1,', ' ,a.cmf_add2,', ',a.cmf_add3) AS address, CONCAT(a.cmf_telprefix1,-a.cmf_tel2) AS tel_number,
                       a.cmf_zero,a.cmf_thirty,a.cmf_sixty,a.cmf_ninety,a.cmf_onetwenty,a.cmf_overonetwenty,a.cmf_overpayment,a.cmf_tin AS tin_number,
                       catad.catad_name, bran.branch_code, pay.paytype_name, bran.branch_name, 
                       CASE a.cmf_gov 
                    WHEN 0 THEN 'Non-Gov'
                    WHEN 1 THEN 'Gov'
                       END AS govstat,
                       ind.ind_code AS indcode, IFNULL(ind.ind_name, ' NO INDUSTRY TAG') AS indname                         
                    FROM miscmf AS a
                    LEFT OUTER JOIN miscollarea AS collarea ON collarea.id = a.cmf_collarea
                    LEFT OUTER JOIN miscatad AS catad ON catad.id = a.cmf_catad
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = a.cmf_branch
                    LEFT OUTER JOIN mispaytype AS pay ON pay.id = a.cmf_paytype
                    LEFT OUTER JOIN misindustry AS ind ON ind.id = a.cmf_industry 
                    WHERE a.is_deleted = 0 $concat $concollarea $conbranch  $conpaytype $condate   
                    ORDER BY bran.branch_name, a.cmf_code";
          
            $result = $this->db->query($stmt)->result_array();
            
            $newresult = array();
            
            foreach ($result as $row) {
                $newresult[$row['branch_code'].' - '.$row['branch_name']][] = $row;
            }
            
            return $newresult;
            
        } else if ($reporttype == 5) {      
        
            $stmt = "SELECT  a.cmf_code, a.cmf_name, aom.ao_cmf, aom.ao_payee
                        FROM ao_m_tm AS aom
                        INNER JOIN miscmf AS a ON aom.ao_amf = a.id
                        WHERE a.is_deleted = 0 AND a.cmf_catad = 1       
                        $concat $conae $conbranch  $conpaytype $conadvcode $conadvname 
                        $condate
                        GROUP BY cmf_code, ao_cmf      
                        ORDER BY cmf_name, aom.ao_payee";
            
            $result = $this->db->query($stmt)->result_array();
            
            $newresult = array();
            
            foreach ($result as $row) {
                $newresult[$row['cmf_code'].' - '.$row['cmf_name']][] = $row;
            }
            
            return $newresult;    
        } else if ($reporttype == 7) {
            
            $stmt = "SELECT DISTINCT a.cmf_code, a.cmf_name,catad.catad_name, CONCAT(us.firstname,' ',SUBSTR(us.middlename, 1, 1),'. ',us.lastname) AS collasst,
                    CONCAT(ae.firstname,' ',SUBSTR(ae.middlename, 1, 1),'. ',ae.lastname) AS ae
                    FROM miscmf AS a
                    INNER JOIN misacmf AS acmf ON acmf.id = a.id
                    LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = a.cmf_collasst
                    LEFT OUTER JOIN users AS us ON us.id = a.cmf_collasst
                    LEFT OUTER JOIN misempprofile AS empae ON empae.user_id = a.cmf_aef
                    LEFT OUTER JOIN users AS ae ON ae.id = a.cmf_aef
                    LEFT OUTER JOIN miscatad AS catad ON catad.id = a.cmf_catad
                    WHERE a.is_deleted = 0 AND cmf_catad = '2'
                    $concat $conae $conbranch  $conpaytype $conadvcode $conadvname 
                    $condate   
                    GROUP BY cmf_code, collasst       
                    ORDER BY cmf_name, ae";
                        
            #echo "<pre>"; echo $stmt; exit;             
            $result = $this->db->query($stmt)->result_array();

            return $result;            
            
        }
        
    }
    
     public function updateccAEDetail($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d');    
        
        $this->db->where('id', $id);
        $this->db->update('miscmf', $data);
        return true;    
    }
    
    public function getDataCustomerPerAE($id) {
        
        $stmt = "SELECT a.id, a.cmf_code, a.cmf_name, CONCAT(IFNULL(a.cmf_add1,''),' ',IFNULL(a.cmf_add2,''),' ',IFNULL(a.cmf_add3,'')) AS address, a.cmf_tin,
                       b.catad_name, c.vat_code, SUBSTR(d.ind_name, 1, 20) AS ind_name, CONCAT(IFNULL(a.ae_contact1,''),' ',IFNULL(a.ae_contact2,'')) AS contacts,
                       ae_name1, ae_position1, ae_position1, ae_email1, ae_contact1, ae_name2, ae_position2, ae_email2, ae_contact2, a.cmf_industry  
                FROM miscmf AS a 
                LEFT OUTER JOIN miscatad AS b ON b.id = a.cmf_catad
                LEFT OUTER JOIN misvat AS c ON c.id = a.cmf_vatcode
                LEFT OUTER JOIN misindustry AS d ON d.id = a.cmf_industry
                WHERE a.id = '$id'
                ORDER BY a.cmf_name";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function getCustomerPerAE($aeid) {
        
        $conae = "a.is_deleted = 0";
        
        if ($aeid != 247) {
            $conae = "a.cmf_aef = '$aeid'";      
        }
        
        $stmt = "SELECT a.id, a.cmf_code, a.cmf_name, CONCAT(IFNULL(a.cmf_add1,''),' ',IFNULL(a.cmf_add2,''),' ',IFNULL(a.cmf_add3,'')) AS address, a.cmf_tin,
                       b.catad_name, c.vat_code, SUBSTR(d.ind_name, 1, 20) AS ind_name, CONCAT(IFNULL(a.ae_contact1,''),' ',IFNULL(a.ae_contact2,'')) AS contacts  
                FROM miscmf AS a 
                LEFT OUTER JOIN miscatad AS b ON b.id = a.cmf_catad
                LEFT OUTER JOIN misvat AS c ON c.id = a.cmf_vatcode
                LEFT OUTER JOIN misindustry AS d ON d.id = a.cmf_industry
                WHERE $conae
                ORDER BY a.cmf_name
                ";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function updateccCollectionDetail($data, $id) {
        $data['cc_edited_n'] = $this->session->userdata('authsess')->sess_id;      
        $data['cc_edited_d'] = DATE('Y-m-d h:i:s');    
        
        $this->db->where('id', $id);
        $this->db->update('miscmf', $data);
        return true;    
    }
    
    public function getCCCustomerData($id) {
        $stmt = "SELECT id, cmf_code, cmf_name, cmf_tin,
                       cc_name1, cc_position1, cc_email1, cc_contact1, cc_address1, 
                       cc_name2, cc_position2, cc_email2, cc_contact2, cc_address2,
                       cc_name3, cc_position3, cc_email3, cc_contact3, cc_address3
                FROM miscmf WHERE id = $id";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function getCustomerIDBYAOPID($id) {
        $stmt = "
                SELECT b.ao_cmf, c.id 
                FROM ao_p_tm AS a 
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                INNER JOIN miscmf AS c ON c.cmf_code = b.ao_cmf
                WHERE a.id = '$id' LIMIT 1
                ";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function getCustomerDataDetailed($cust) {
        
        $stmt = "SELECT CONCAT(a.cmf_name, ' - ',a.cmf_code) AS custname, 
                       -- CONCAT(IFNULL(a.cmf_add1, ''), ' ', IFNULL(a.cmf_add2, ''),' ', IFNULL(a.cmf_add3, '')) AS custadd,
                       a.cmf_add1, a.cmf_add2, a.cmf_add3,
                       CONCAT(IFNULL(a.cmf_telprefix1,''),' ',IFNULL(a.cmf_tel1,'')) AS tel1,
                       CONCAT(IFNULL(a.cmf_telprefix2,''),' ',IFNULL(a.cmf_tel2,'')) AS tel2,
                       CONCAT(IFNULL(a.cmf_celprefix,''),' ',IFNULL(a.cmf_cel,'')) AS cel,
                       CONCAT(IFNULL(a.cmf_faxprefix,''),' ',IFNULL(a.cmf_fax,'')) AS fax,
                       a.cmf_tin AS tin, 
                       -- CONCAT(IFNULL(a.cmf_contact, ''),' ', IFNULL(a.cmf_position, ''), ' ', IFNULL(a.cmf_email, '')) AS contactdetail, 
                       a.cmf_contact, a.cmf_position, a.cmf_email,
                       b.country_name, c.zip_code, d.collarea_code, 
                       CONCAT(e.firstname, ' ',e.lastname) AS ae,
                       CONCAT(f.firstname, ' ',f.lastname) AS collasst,
                       g.catad_name
                FROM miscmf AS a
                LEFT OUTER JOIN miscountry AS b ON b.id = a.cmf_country
                LEFT OUTER JOIN miszip AS c ON c.id = a.cmf_zip
                LEFT OUTER JOIN miscollarea AS d ON d.id = a.cmf_collarea
                LEFT OUTER JOIN users AS e ON e.id = a.cmf_aef
                LEFT OUTER JOIN users AS f ON f.id = a.cmf_collasst
                LEFT OUTER JOIN miscatad AS g ON g.id = a.cmf_catad
                WHERE a.id = '$cust'";
                
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function getCustomerDataID($id) {
        $stmt = "SELECT id, cmf_code, cmf_name, cmf_add1, cmf_add2, cmf_add3 FROM miscmf WHERE id = '$id'";    

        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getCustomerData($code) {
        $stmt = "SELECT id, cmf_code, cmf_name, cmf_add1, cmf_add2, cmf_add3 FROM miscmf WHERE cmf_code = '$code'";  
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function removeData($id) {
		
        $data['is_deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update('miscmf', $data);
        return true;
    } 
    
    public function saveupdateNewData($data, $id) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;      
        $data['edited_d'] = DATE('Y-m-d h:i:s');    
        
        $this->db->where('id', $id);
        $this->db->update('miscmf', $data);
        
        
        $stmt = "SELECT cmf_code FROM miscmf WHERE id = $id";
        $result = $this->db->query($stmt)->row_array();
        
        
        $data2['user_id'] =  $this->session->userdata('authsess')->sess_id;           
        $data2['activity'] = 'UPDATING';
        $data2['remarks'] = 'MAINTENANCE: Update Customer '.$result['cmf_code'];
        $this->db->insert('advprod_db02logs.activitylogs', $data2);
        return true;
    }
    
    public function saveNewData($data) {
        
        $this->load->model(array('model_vat/vats', 'model_wtax/wtaxes'));
        
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;      
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;      
        $data['edited_d'] = DATE('Y-m-d h:i:s');    
        
        /* Getting VAT Rate and WTAX Rate */ 
        $vat = $this->vats->thisVat($data['cmf_vatcode']);
        $data['cmf_vatrate'] = $vat['vat_rate'];
        $wtax = $this->wtaxes->thisWTAX($data['cmf_wtaxcode']);
        $data['cmf_wtaxrate'] = $wtax['wtax_rate'];
        
        $this->db->insert('miscmf', $data);
        return true;
    }
	
	public function getvalidation($id) {
		
		$stmt = "SELECT miscat.catad_name,a.id, a.cmf_code, a.cmf_name,aom.ao_amf, aom.ao_cmf,pr.pr_amf,pr.pr_cmf,
						orm.or_amf, orm.or_cmf, dc.dc_amf, dc.dc_cmf
				FROM miscmf AS a
                LEFT OUTER JOIN miscatad AS miscat ON miscat.id = a.cmf_catad
				LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_amf = a.id
				LEFT OUTER JOIN pr_m_tm AS pr ON pr.pr_amf = a.id
				LEFT OUTER JOIN or_m_tm AS orm ON orm.or_amf =a.id
				LEFT OUTER JOIN dc_m_tm AS dc ON dc.dc_amf = a.id
				WHERE a.id = '$id'
                GROUP BY a.id";
				
		#echo "<pre>"; echo $stmt; exit;
		$result = $this->db->query($stmt)->result_array();

		return $result;
	
	}

	public function list_of_customer($offset, $limit) {        
	    $stmt = "SELECT a.id,c.catad_name,c.id AS cat_id,a.cmf_code,a.cmf_name,a.cmf_crlimit, 
				CASE a.cmf_crstatus		                             
						WHEN 'A' THEN 'Auto CF'
						WHEN 'Y' THEN 'Yes'
						WHEN 'N' THEN 'No'
						WHEN 'B' THEN 'Bad'
				END AS cmf_crstatusname, b.firstname, b.middlename, b.lastname, a.user_d, a.edited_d		   
				FROM miscmf AS a 
                LEFT OUTER JOIN miscatad AS c ON c.id = a.cmf_catad
				LEFT OUTER JOIN users AS b ON b.id = a.cmf_aef
				WHERE a.is_deleted = 0 
				ORDER BY a.id LIMIT $limit OFFSET $offset" ;  

		#echo "<pre>"; echo $stmt; exit;
		$result = $this->db->query($stmt)->result_array();

		return $result;
		
	}
    
    public function customerVAT($customercode) {
        $stmt = "SELECT misvat.vat_rate 
                FROM miscmf
                INNER JOIN misvat ON miscmf.cmf_vatcode = misvat.id
                WHERE cmf_code = '$customercode'";   
        $result = $this->db->query($stmt)->row(); 
        if (!empty($result)) {
        return $result->vat_rate;    
        } else { return 0;}
        
    }
	
    function checkcmfcode($data)
    {
         $stmt = "SELECT cmf_code FROM miscmf WHERE cmf_code = '".$data['cmf_code']."'";
         $result = $this->db->query($stmt);
         return $result->num_rows();
    } 
    
	public function getAgencyAE($agencyid) {
        $stmt = "SELECT cmf_aef FROM miscmf WHERE id = '".$agencyid."'";
        $result = $this->db->query($stmt)->row();
        return (!empty($result) ? $result->cmf_aef : 0);
    }
    
	public function fetchClientByAgency($data)
	{
		$kuery = "select b.cmf_code as clients,b.cmf_name as client_name
					from misacmf as a
					inner join miscmf as b on b.id = a.cmf_code
					where a.amf_code = (select id from miscmf where cmf_code = '".$data['agency']."' and cmf_catad = '1')
					and a.cmf_code in (select c.id
					from ao_p_tm as a
					inner join ao_m_tm as b on a.ao_num = b.ao_num
					inner join miscmf as c on b.ao_cmf = c.cmf_code
					where (a.ao_issuefrom >= DATE('".$data['from_date']."')
					       AND a.ao_issuefrom   <= DATE('".$data['to_date']."')
					and b.ao_amf in (select id from miscmf where cmf_code =  '".$data['agency']."')))";		   
				   
		$kuery = $this->db->query($kuery);
		return $kuery->result_array();		  
	}
    
    public function fetchClientByAgency2($data)
    {
        $kuery = "select b.cmf_code as cmf_code,b.cmf_name as client_name
                    from misacmf as a
                    inner join miscmf as b on b.id = a.cmf_code
                    where a.amf_code = (SELECT id FROM miscmf WHERE cmf_code =  '".$data['agency']."')
                    ";           
                   
        $kuery = $this->db->query($kuery);
        return $kuery->result_array();          
    }   
	
	public function fetchCustomerByDate($data)
	{
		$kuery = "SELECT DISTINCT ao_payee as advertiser
				 FROM ao_m_tm 
				WHERE ao_num IN(SELECT ao_num 
							FROM ao_p_tm
							WHERE ao_issuefrom  >= DATE('".$data['from_date']."')
							AND   ao_issuefrom  <= DATE('".$data['to_date']."')
							)
					ORDER BY ao_payee ASC";
		$kuery = $this->db->query($kuery);
		return $kuery->result_array();
	}
	
	function fetchAgencyByDate($data)
	{
		$kuery = "SELECT DISTINCT cmf_code, cmf_name as agency
					FROM miscmf
					WHERE
					cmf_catad = 1
					AND id IN (SELECT a.ao_amf
						     FROM ao_m_tm as a
						     INNER JOIN ao_p_tm as b ON a.ao_num = b.ao_num
						     WHERE b.ao_issuefrom >= DATE('".$data['from_date']."')
					         AND b.ao_issuefrom   <= DATE('".$data['to_date']."')
						  
					             )
					AND is_deleted = 0
					ORDER BY cmf_name ASC";
					
		$kuery = $this->db->query($kuery);
		return $kuery->result_array();
	}
	
	public function fetchClient()
	{
		$kuery = "SELECT DISTINCT cmf_name as client,cmf_code FROM miscmf 
				  WHERE cmf_catad = 2
				  ORDER BY cmf_name ASC ";
	    $kuery = $this->db->query($kuery);			  
		return $kuery->result_array();
	}
	
	public function fetchAgency()
	{
		$kuery = "SELECT DISTINCT  cmf_name as agency, cmf_code as agency_code FROM miscmf 
				  WHERE cmf_catad = 1
				  ORDER BY cmf_name ASC ";
	    $kuery = $this->db->query($kuery);			  
		return $kuery->result_array();
	}

	public function getCustomerID($cust_code)
	{
		$stmt = "SELECT id FROM miscmf WHERE cmf_code = '".$cust_code."'";
		$result = $this->db->query($stmt)->row();
		return $result->id;
	}

	public function whatIsTheCreditStatus($cmf_code) {
		$stmt = "SELECT	CASE cmf_crstatus
						WHEN 'A' THEN 'Auto CF'
						WHEN 'Y' THEN 'Yes'
						WHEN 'N' THEN 'No'
						WHEN 'B' THEN 'Bad'
					END cmf_crstatus FROM miscmf WHERE cmf_code = '".$cmf_code."' AND is_deleted = '0'";
		$result = $this->db->query($stmt)->row();		
        return $result->cmf_crstatus;
	}
	
	public function viewMyAging($code)
	{
		$stmt = "SELECT cmf_code, cmf_name, cmf_crstatus, cmf_crlimit, 
					   cmf_crf, cmf_crrem, cmf_adrem, cmf_agingdate,
					   cmf_zero, cmf_thirty, cmf_sixty, cmf_ninety,
					   cmf_onetwenty, cmf_overonetwenty, cmf_overpayment,
					   IFNULL(cmf_zero + cmf_thirty + cmf_sixty + cmf_ninety + cmf_onetwenty + cmf_overonetwenty - cmf_overpayment, '0') AS total
				FROM miscmf WHERE cmf_code = '".$code."' AND is_deleted = '0'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function validateCode($custcode)
	{
		$stmt = "SELECT cmf_code FROM miscmf WHERE cmf_code = '".$custcode."' AND is_deleted = '0'";
		$result = $this->db->query($stmt)->row();		
		return $result;
	}
	
	public function findCustomerSuggestion($data)
	{
		/*$stmt = "SELECT id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip,
		                             cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
		                             cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
		                             cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
		                             cmf_vatcode,cmf_vatrate,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
		                             cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
		                             cmf_cardnumber,cmf_authorisationno,cmf_expirydate, cmf_crstatus,
									 cmf_crstatus,
								     CASE cmf_crstatus		                             
										WHEN 'A' THEN 'Auto CF'
										WHEN 'Y' THEN 'Yes'
										WHEN 'N' THEN 'No'
										WHEN 'B' THEN 'Bad'
		                             END AS cmf_crstatusname,		                             	 
									 cmf_crlimit,cmf_autooverride,
		                             cmf_crf,cmf_crrem,cmf_adrem
				FROM miscmf
				WHERE is_deleted = '0' 
				AND cmf_code LIKE '".$data['customer_code']."%' AND cmf_name LIKE '".$data['customer_name']."%' ORDER BY cmf_name ASC LIMIT 100 ";   */
                
        $stmt = "SELECT miscmf.id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip,
                    cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
                    cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
                    cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
                    cmf_vatcode,misvat.vat_rate AS cmf_vatrate ,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
                    cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
                    cmf_cardnumber,cmf_authorisationno,cmf_expirydate, cmf_crstatus,
                    cmf_crstatus,
                    CASE cmf_crstatus                                     
                    WHEN 'A' THEN 'Auto CF'
                    WHEN 'Y' THEN 'Yes'
                    WHEN 'N' THEN 'No'
                    WHEN 'B' THEN 'Bad'
                    WHEN 'O' THEN 'Auto Override'  
                    END AS cmf_crstatusname,                                          
                    cmf_crlimit,cmf_autooverride,
                    cmf_crf,cmf_crrem,cmf_adrem, misvat.vat_name
                FROM miscmf
                LEFT OUTER JOIN misvat ON misvat.id = miscmf.cmf_vatcode
                WHERE miscmf.is_deleted = '0' 
                AND cmf_code LIKE '".$data['customer_code']."%' AND cmf_name LIKE '".$data['customer_name']."%' ORDER BY cmf_name ASC LIMIT 100 ";     
                
        
		
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function suggestedName($name)
	{
		$stmt = "SELECT cmf_name FROM miscmf WHERE cmf_name LIKE '".$name."%' ";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function audittrailCostumer($code, $action,$reason)
	{
		$user_id = $this->session->userdata('authsess')->sess_id;
		$stmt = "INSERT INTO auditrail_customer (user_id,cust_code,action,reason)
		         VALUES ('".$user_id."','".$code."','".$action."','".$reason."')";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function getLogoName($id)
	{
		$stmt = "SELECT cmf_logo FROM miscmf WHERE id = '".$id."'";
		$result = $this->db->query($stmt)->row();
		return $result->cmf_logo;	
	}
	
	public function uploadlogo($id, $upload)
	{
		$stmt = "UPDATE miscmf SET cmf_logo = '".$upload."' WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function delete($id)
	{
		$stmt = "UPDATE miscmf SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateCustomer($data)
	{
		$user_id = $this->session->userdata('authsess')->sess_id;      
		$z = preg_replace("[,]", "", $data['data']['cmf_crlimit']);		
		$creditlimit = substr($z, 0, -3);
		$stmt = "UPDATE miscmf SET cmf_name = '".$data['data']['cmf_name']."',
						                 cmf_title = '".$data['data']['cmf_title']."', cmf_add1 = '".$data['data']['cmf_add1']."',
						                 cmf_country = '".$data['data']['cmf_country']."', cmf_add2 = '".$data['data']['cmf_add2']."',			                 
						                 cmf_zip = '".$data['data']['cmf_zip']."', cmf_add3 = '".$data['data']['cmf_add3']."',
						                 cmf_add3 = '".$data['data']['cmf_add3']."', cmf_telprefix1 = '".$data['data']['cmf_telprefix1']."',						                 
						                 cmf_tel1 = '".$data['data']['cmf_tel1']."', cmf_telprefix2 = '".$data['data']['cmf_telprefix2']."',						                 
						                 cmf_tel2 = '".$data['data']['cmf_tel2']."', cmf_faxprefix = '".$data['data']['cmf_faxprefix']."',							                 		                 
						                 cmf_fax = '".$data['data']['cmf_fax']."', cmf_celprefix = '".$data['data']['cmf_celprefix']."',
						                 cmf_cel = '".$data['data']['cmf_cel']."', cmf_tin = '".$data['data']['cmf_tin']."',						                 
						                 cmf_industry = '".$data['data']['cmf_industry']."', cmf_url = '".$data['data']['cmf_url']."',						                 
						                 cmf_contact = '".$data['data']['cmf_contact']."',							                 	                 
						                 cmf_salutation = '".$data['data']['cmf_salutation']."', cmf_position = '".$data['data']['cmf_position']."',			                 
						                 cmf_email = '".$data['data']['cmf_email']."', cmf_catad = '".$data['data']['cmf_catad']."',			                
						                 cmf_pana = '".$data['data']['cmf_pana']."', cmf_gov = '".$data['data']['cmf_gov']."',						                 
						                 cmf_paytype = '".$data['data']['cmf_paytype']."', cmf_vatcode = '".$data['data']['cmf_vatcode']."',
						                 cmf_vatrate = '".$data['data']['cmf_vatrate']."', cmf_wtaxcode = '".$data['data']['cmf_wtaxcode']."',
						                 cmf_wtaxrate = '".$data['data']['cmf_wtaxrate']."', cmf_aef = '".$data['data']['cmf_aef']."',
						                 cmf_branch = '".$data['data']['cmf_branch']."', cmf_coll = '".$data['data']['cmf_coll']."',								                 	                 
						                 cmf_collarea = '".$data['data']['cmf_collarea']."', cmf_collasst = '".$data['data']['cmf_collasst']."',			                 
						                 cmf_status = '".$data['data']['cmf_status']."', cmf_rem_source = '".$data['data']['cmf_rem_source']."',
						                 cmf_rem = '".$data['data']['cmf_rem']."', cmf_cardholder = '".$data['data']['cmf_cardholder']."',				                 		                 
						                 cmf_cardtype = '".$data['data']['cmf_cardtype']."', cmf_cardnumber = '".$data['data']['cmf_cardnumber']."',							                 		                 
						                 cmf_authorisationno = '".$data['data']['cmf_authorisationno']."', cmf_expirydate = '".$data['data']['cmf_expirydate']."',						                 
						                 cmf_crstatus = '".$data['data']['cmf_crstatus']."', cmf_crlimit = '".$creditlimit."',
						                 cmf_crrem = '".$data['data']['cmf_crrem']."', cmf_adrem = '".$data['data']['cmf_adrem']."',cmf_autooverride = '".$data['data']['cmf_autooverride']."',
				 				         edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['data']['id']."'";	
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function thisCustomerCurrentData($id)
	{
		$stmt = "SELECT id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip,
		                             cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
		                             cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
		                             cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
		                             cmf_vatcode,cmf_vatrate,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
		                             cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
		                             cmf_cardnumber,cmf_authorisationno,date(cmf_expirydate) as cmf_expirydate,cmf_crstatus,cmf_crlimit,
		                             cmf_crf,cmf_crrem,cmf_adrem, bipps_status     
				FROM miscmf
				WHERE id = '".$id."'
				AND is_deleted = '0'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
		#cmf_autooverride,
	}
		
	public function thisCustomer($id)
	{
		$stmt = "SELECT id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip,
		                             cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
		                             cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
		                             cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
		                             cmf_vatcode,cmf_vatrate,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
		                             cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
		                             cmf_cardnumber,cmf_authorisationno,cmf_expirydate,cmf_crstatus,cmf_crlimit,cmf_autooverride,
		                             cmf_crf,cmf_crrem,cmf_adrem,cmf_agingdate,cmf_zero,cmf_thirty,cmf_sixty,cmf_ninety,
									 cmf_onetwenty,cmf_overonetwenty,cmf_overpayment,beg_date,
									 beg_code,beg_amt,run_date,run_code,run_amt,ytd_date,ytd_code,
									 ytd_amt,user_n,user_d,edited_n,edited_d,
									 IFNULL(cmf_zero + cmf_thirty + cmf_sixty + cmf_ninety + cmf_onetwenty + cmf_overonetwenty - cmf_overpayment, '0') AS cmf_total       
				FROM miscmf
				WHERE id = '".$id."'
				AND is_deleted = '0'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
    
    public function thisFindCustomer($id)
    {
        $stmt = "SELECT id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip,
                                     cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
                                     cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
                                     cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
                                     cmf_vatcode,cmf_vatrate,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
                                     cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
                                     cmf_cardnumber,cmf_authorisationno,DATE(cmf_expirydate) as cmf_expirydate,cmf_crstatus,cmf_crlimit,cmf_autooverride,
                                     cmf_crf,cmf_crrem,cmf_adrem,cmf_agingdate,cmf_zero,cmf_thirty,cmf_sixty,cmf_ninety,
                                     cmf_onetwenty,cmf_overonetwenty,cmf_overpayment,beg_date,
                                     beg_code,beg_amt,run_date,run_code,run_amt,ytd_date,ytd_code,
                                     ytd_amt,user_n,user_d,edited_n,edited_d,
                                     IFNULL(cmf_zero + cmf_thirty + cmf_sixty + cmf_ninety + cmf_onetwenty + cmf_overonetwenty - cmf_overpayment, '0') AS cmf_total       
                FROM miscmf
                WHERE id = '".$id."'
                AND is_deleted = '0'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
	
	public function insertCustomer($data)
	{		
		$user_id = $this->session->userdata('authsess')->sess_id;
		$z = preg_replace("[,]", "", $data['cmf_crlimit']);		
		$creditlimit = substr($z, 0, -3);
		$stmt = "INSERT INTO miscmf (cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip,
		                             cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
		                             cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
		                             cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
		                             cmf_vatcode,cmf_vatrate,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
		                             cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
		                             cmf_cardnumber,cmf_authorisationno,cmf_expirydate,cmf_crstatus,cmf_crlimit,cmf_autooverride,
		                             cmf_crf,cmf_crrem,cmf_adrem,user_n,edited_n,edited_d) 
			          VALUES('".$data['cmf_code']."','".$data['cmf_name']."',
			          		 '".$data['cmf_title']."','".$data['cmf_add1']."',
			          		 '".$data['cmf_country']."','".$data['cmf_add2']."',
			          		 '".$data['cmf_zip']."','".$data['cmf_add3']."',
			          		 '".$data['cmf_telprefix1']."','".$data['cmf_tel1']."',
			          		 '".$data['cmf_telprefix2']."','".$data['cmf_tel2']."',
			          		 '".$data['cmf_faxprefix']."','".$data['cmf_fax']."',
			          		 '".$data['cmf_celprefix']."','".$data['cmf_cel']."',
			          		 '".$data['cmf_tin']."','".$data['cmf_industry']."',
			          		 '".$data['cmf_url']."','".$data['cmf_contact']."',
			          		 '".$data['cmf_salutation']."','".$data['cmf_position']."',
			          		 '".$data['cmf_email']."','".$data['cmf_catad']."',
			          		 '".$data['cmf_pana']."','".$data['cmf_gov']."',
			          		 '".$data['cmf_paytype']."','".$data['cmf_vatcode']."',
			          		 '".$data['cmf_vatrate']."','".$data['cmf_wtaxcode']."',
			          		 '".$data['cmf_wtaxrate']."','".$data['cmf_aef']."',
			          		 '".$data['cmf_branch']."','".$data['cmf_coll']."',
			          		 '".$data['cmf_collarea']."','".$data['cmf_collasst']."',			          		 
			          		 '".$data['cmf_status']."','".$data['cmf_rem_source']."', 
			          		 '".$data['cmf_rem']."','".$data['cmf_cardholder']."',
			          		 '".$data['cmf_cardtype']."','".$data['cmf_cardnumber']."',		
			          		 '".$data['cmf_authorisationno']."','".$data['cmf_expirydate']."',
			          		 '".$data['cmf_crstatus']."','".$creditlimit."',
			          		 '".$data['cmf_autooverride']."','".$data['cmf_crf']."','".$data['cmf_crrem']."',
			          		 '".$data['cmf_adrem']."','".$user_id."','".$user_id."',NOW())";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function listOfCustomer($data, $limit, $offset) 
	{
		if ((!empty($data['customercode'])) ? $customercode = "AND cmf_code LIKE'".$data['customercode']."%'" : $customercode = "");
		if ((!empty($data['customername'])) ? $customername = "AND cmf_name LIKE'".$data['customername']."%'" : $customername = "");
		if ((!empty($data['address'])) 		? $address = "AND cmf_add1 LIKE'".$data['address']."%'" : $address = "");
		if ((!empty($data['country'])) 		? $country = "AND cmf_country LIKE'".$data['country']."%'" : $country = "");
		if ((!empty($data['zip'])) 			? $zip = "AND cmf_zip LIKE'".$data['zip']."%'" : $zip = "");		
		if ((!empty($data['vat'])) 			? $vat = "AND cmf_vatcode LIKE'".$data['vat']."%'" : $vat = "");
		if ((!empty($data['telephone'])) 	? $telephone = "AND cmf_tel1 LIKE'".$data['telephone']."%'" : $telephone = "");
		if ((!empty($data['cellphone'])) 	? $cellphone = "AND cmf_cel LIKE'".$data['cellphone']."%'" : $cellphone = "");
		if ((!empty($data['paytype'])) 		? $paytype = "AND cmf_paytype LIKE'".$data['paytype']."%'" : $paytype = "");
		if ((!empty($data['branch'])) 		? $branch = "AND cmf_branch LIKE'".$data['branch']."%'" : $branch = "");		
		if ((!empty($data['collector'])) 	? $collector = "AND cmf_coll LIKE'".$data['collector']."%'" : $collector = "");
		if ((!empty($data['collectorarea'])) ? $collectorarea = "AND cmf_collarea LIKE'".$data['collectorarea']."%'" : $collectorarea = "");
		if ((!empty($data['collectionasst'])) ? $collectionasst = "AND cmf_collasst LIKE'".$data['collectionasst']."%'" : $collectionasst = "");
		if ((!empty($data['industry'])) 	? $industry = "AND cmf_industry LIKE'".$data['industry']."%'" : $industry = "");
		if ((!empty($data['status'])) 		? $status = "AND cmf_status LIKE'".$data['status']."%'" : $status = "");
		if ((!empty($data['pana'])) 		? $pana = "AND cmf_pana LIKE'".$data['pana']."%'" : $pana = "");		
		if ((!empty($data['catad'])) 		? $catad = "AND cmf_catad LIKE'".$data['catad']."%'" : $catad = "");
		if ((!empty($data['creditstatus'])) ? $creditstatus = "AND cmf_crstatus LIKE'".$creditstatus."%'" : $creditstatus = "");
		if ((!empty($data['govt'])) 		? $govt = "AND cmf_gov LIKE'".$data['creditstatus']."%'" : $govt = "");
		if ((!empty($data['wtax'])) 		? $wtax = "AND cmf_wtaxcode LIKE'".$data['wtax']."%'" : $wtax = "");
		if ((!empty($data['creditterms'])) 	? $creditterms = "AND cmf_crf LIKE'".$data['creditterms']."%'" : $creditterms = "");
		if ((!empty($data['autooverid'])) 	? $autooverid = "AND cmf_autooverride LIKE'".$data['autooverid']."%'" : $autooverid = "");
		
		//$condition = "LIMIT ".$limit." OFFSET ".$offset;
		
		$stmt = "SELECT id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_add2,cmf_add3,cmf_country,
				       cmf_telprefix1,cmf_telprefix2,cmf_tel1,cmf_tel2,cmf_faxprefix,cmf_fax,
				       cmf_celprefix,cmf_cel,cmf_tin,cmf_zip,cmf_industry,cmf_contact,cmf_position,
				       cmf_salutation,cmf_email,cmf_url,cmf_logo,cmf_coll,cmf_collarea,cmf_aef,cmf_collasst,
				       cmf_crf,cmf_crstatus,cmf_crlimit,cmf_autooverride,cmf_crrem,cmf_status,cmf_rem,cmf_adrem,cmf_pana,
				       cmf_gov,cmf_wtaxcode,cmf_wtaxrate,cmf_vatcode,cmf_vatrate,cmf_catad,cmf_branch,cmf_paytype,cmf_cardholder,
				       cmf_authorisationno,cmf_rem_source,cmf_agingdate,cmf_zero,cmf_thirty,cmf_sixty,cmf_ninety,cmf_onetwenty,cmf_overpayment,
				       beg_date,beg_code,beg_amt,run_date,run_code,run_amt,ytd_date,ytd_code,ytd_amt,cityname,postcode,cardholder,cardtype,cardnumber,expirydate,name2     
				FROM miscmf
				WHERE is_deleted = '0'"." ".
				$customercode." ".$customername." ".$address." ".$country." ".$zip." ".
				$vat." ".$telephone." ".$cellphone." ".$paytype." ".$branch." ".$collector." ".
				$collectorarea." ".$collectionasst." ".$industry." ".$status." ".
				$pana." ".$catad." ".$creditstatus." ".$govt." ".$wtax." ".$creditterms." ".$autooverid."
				ORDER BY id DESC LIMIT 100  ";
                
                
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function countCustomer($data)
	{
		if ((!empty($data['customercode'])) ? $customercode = "AND cmf_code LIKE'%".$data['customercode']."%'" : $customercode = "");
		if ((!empty($data['customername'])) ? $customername = "AND cmf_name LIKE'%".$data['customername']."%'" : $customername = "");
		if ((!empty($data['address'])) 		? $address = "AND cmf_add1 LIKE'%".$data['address']."%'" : $address = "");
		if ((!empty($data['country'])) 		? $country = "AND cmf_country LIKE'%".$data['country']."%'" : $country = "");
		if ((!empty($data['zip'])) 			? $zip = "AND cmf_zip LIKE'%".$data['zip']."%'" : $zip = "");		
		if ((!empty($data['vat'])) 			? $vat = "AND cmf_vatcode LIKE'%".$data['vat']."%'" : $vat = "");
		if ((!empty($data['telephone'])) 	? $telephone = "AND cmf_tel1 LIKE'%".$data['telephone']."%'" : $telephone = "");
		if ((!empty($data['cellphone'])) 	? $cellphone = "AND cmf_cel LIKE'%".$data['cellphone']."%'" : $cellphone = "");
		if ((!empty($data['paytype'])) 		? $paytype = "AND cmf_paytype LIKE'%".$data['paytype']."%'" : $paytype = "");
		if ((!empty($data['branch'])) 		? $branch = "AND cmf_branch LIKE'%".$data['branch']."%'" : $branch = "");		
		if ((!empty($data['collector'])) 	? $collector = "AND cmf_coll LIKE'%".$data['collector']."%'" : $collector = "");
		if ((!empty($data['collectorarea'])) ? $collectorarea = "AND cmf_collarea LIKE'%".$data['collectorarea']."%'" : $collectorarea = "");
		if ((!empty($data['collectionasst'])) ? $collectionasst = "AND cmf_collasst LIKE'%".$data['collectionasst']."%'" : $collectionasst = "");
		if ((!empty($data['industry'])) 	? $industry = "AND cmf_industry LIKE'%".$data['industry']."%'" : $industry = "");
		if ((!empty($data['status'])) 		? $status = "AND cmf_status LIKE'%".$data['status']."%'" : $status = "");
		if ((!empty($data['pana'])) 		? $pana = "AND cmf_pana LIKE'%".$data['pana']."%'" : $pana = "");		
		if ((!empty($data['catad'])) 		? $catad = "AND cmf_catad LIKE'%".$data['catad']."%'" : $catad = "");
		if ((!empty($data['creditstatus'])) ? $creditstatus = "AND cmf_crstatus LIKE'%".$creditstatus."%'" : $creditstatus = "");
		if ((!empty($data['govt'])) 		? $govt = "AND cmf_gov LIKE'%".$data['creditstatus']."%'" : $govt = "");
		if ((!empty($data['wtax'])) 		? $wtax = "AND cmf_wtaxcode LIKE'%".$data['wtax']."%'" : $wtax = "");
		if ((!empty($data['creditterms'])) 	? $creditterms = "AND cmf_crf LIKE'%".$data['creditterms']."%'" : $creditterms = "");
		if ((!empty($data['autooverid'])) 	? $autooverid = "AND cmf_autooverride LIKE'%".$data['autooverid']."%'" : $autooverid = "");
		$stmt = "SELECT COUNT(*) AS total FROM miscmf WHERE is_deleted = '0'"." ".
				$customercode." ".$customername." ".$address." ".$country." ".$zip." ".
				$vat." ".$telephone." ".$cellphone." ".$paytype." ".$branch." ".$collector." ".
				$collectorarea." ".$collectionasst." ".$industry." ".$status." ".
				$pana." ".$catad." ".$creditstatus." ".$govt." ".$wtax." ".$creditterms." ".$autooverid;
		$result = $this->db->query($stmt)->row();
		return $result->total;
	}	
	
	public function listOfCustomeInGroup()
	{
		$stmt = "SELECT id, cmf_code, cmf_name
				FROM miscmf WHERE is_deleted = '0'";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
    
    public function countAll()
    {
        $stmt = "SELECT count(id) as count_id
                 from miscmf where is_deleted = 0";
        $result = $this->db->query($stmt);
        return $result->row();
        
    }
    
    public function listOfCustomeInGroupDesc($search, $stat, $offset, $limit)
    {
        $stmt = "SELECT id, cmf_code, cmf_name,cmf_title,
                        CONCAT(cmf_add1,' ',cmf_add2,' ',cmf_add3) as address
                FROM miscmf WHERE is_deleted = '0' ORDER BY id DESC 
                LIMIT 25 OFFSET $offset" ;
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function seekthisCustomer($id)
    {
        $stmt = "SELECT id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip, 
                                     cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
                                     cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
                                     cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
                                     cmf_vatcode,cmf_vatrate,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
                                     cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
                                     cmf_cardnumber,cmf_authorisationno,DATE(cmf_expirydate) as cmf_expirydate,cmf_crstatus,cmf_crlimit,
                                     cmf_crf,cmf_crrem,cmf_adrem,cmf_autooverride     
                FROM miscmf
                WHERE id = '".$id."'
                AND is_deleted = '0'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
        #cmf_autooverride,
    }
    
    public function search($searchkey)
    {
         $concode = ""; $conname = ""; $conbranch = "";
         $concatad = ""; $conpaytype = ""; $convat = "";
         $conaef = ""; $concoll = ""; $concollarea = "";
         $concollasst = ""; $conindustry = ""; $concrf = "";
         
        
        if ($searchkey['cmf_code'] != "") { $concode = " AND a.cmf_code LIKE '".$searchkey['cmf_code']."%' ";}
        if ($searchkey['cmf_name'] != "") { $conname = "AND a.cmf_name LIKE '".$searchkey['cmf_name']."%'  "; }
        if ($searchkey['cmf_branch'] != "") {$conbranch = "AND a.cmf_branch = '".$searchkey['cmf_branch']."'"; }
        if ($searchkey['cmf_catad'] != "") {$concatad = "AND a.cmf_catad = '".$searchkey['cmf_catad']."'"; }
        if ($searchkey['cmf_paytype'] != "") {$conpaytype = "AND a.cmf_paytype = '".$searchkey['cmf_paytype']."'"; }
        if ($searchkey['cmf_vatcode'] != "") {$convat = "AND a.cmf_vatcode = '".$searchkey['cmf_vatcode']."'"; }
        if ($searchkey['cmf_aef'] != "") {$conaef = "AND a.cmf_aef = '".$searchkey['cmf_aef']."'"; }
        if ($searchkey['cmf_coll'] != "") {$concoll = "AND a.cmf_coll = '".$searchkey['cmf_coll']."'"; }
        if ($searchkey['cmf_collarea'] != "") {$concollarea = "AND a.cmf_collarea = '".$searchkey['cmf_collarea']."'"; }
        if ($searchkey['cmf_collasst'] != "") {$concollasst = "AND a.cmf_collasst = '".$searchkey['cmf_collasst']."'"; }
        if ($searchkey['cmf_industry'] != "") {$conindustry = "AND a.cmf_industry = '".$searchkey['cmf_industry']."'"; }
        if ($searchkey['cmf_crf'] != "") {$concrf = "AND a.cmf_crf = '".$searchkey['cmf_crf']."'"; }

        $stmt = "SELECT DISTINCT a.id, a.cmf_crlimit, a.cmf_crstatus AS cmf_crstatusname, a.cmf_code, a.cmf_name, b.branch_name, c.catad_name,
                        d.paytype_name, e.vat_name,
                        CONCAT(emp.empprofile_code,' ', f.firstname, ' ', f.lastname) AS acctexec,
                        CONCAT(empp.empprofile_code, ' ', g.firstname, ' ', g.lastname) AS collector,
                        CONCAT(h.collarea_code, ' ', h.collarea_name) AS collarea,
                        CONCAT(emppp.empprofile_code, ' ', i.firstname, ' ', i.lastname) AS collasst,
                        l.firstname, l.lastname,
                        j.ind_name, k.crf_name, a.user_d, a.edited_d    
                        FROM miscmf AS a
                        LEFT OUTER JOIN misbranch AS b ON b.id = a.cmf_branch
                        LEFT OUTER JOIN miscatad AS c ON c.id = a.cmf_catad
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.cmf_paytype
                        LEFT OUTER JOIN misvat AS e ON e.id = a.cmf_vatcode
                        LEFT OUTER JOIN users AS f ON f.id = a.cmf_aef
                        LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = a.cmf_aef
                        LEFT OUTER JOIN users AS l ON l.id = a.cmf_aef
                        LEFT OUTER JOIN users AS g ON g.id = a.cmf_coll
                        LEFT OUTER JOIN misempprofile AS empp ON empp.user_id = a.cmf_coll
                        LEFT OUTER JOIN miscollarea AS h ON h.id = a.cmf_collarea
                        LEFT OUTER JOIN users AS i ON i.id = a.cmf_collasst
                        LEFT OUTER JOIN misempprofile AS emppp ON emppp.user_id = a.cmf_collasst
                        LEFT OUTER JOIN misindustry AS j ON j.id = a.cmf_industry
                        LEFT OUTER JOIN miscrf AS k ON k.id = a.cmf_crf
                        WHERE a.is_deleted = 0 $concode $conname $conbranch $concatad $conpaytype 
                        $convat $conaef $concoll $concollarea $concollasst $conindustry $concrf
                        ORDER BY a.cmf_code LIMIT 500"; 
           
		#echo "<pre>"; echo $stmt; exit;   
        $result = $this->db->query($stmt)->result_array();
        
        return $result; 
    }
    
    function fetchcustomer()
    {
        $stmt = "select a.id, b.cmf_code as clients,b.cmf_name as client_name
                    from misacmf as a
                    inner join miscmf as b on b.id = a.cmf_code
                    WHERE a.is_deleted = '0'
                    ORDER BY b.cmf_name ASC ";
       $result = $this->db->query($stmt)->result_array();
        return $result;            
    }
    
    public function getallclient()
    {
        $stmt = "SELECT a.id, b.cmf_code AS clients,TRIM(b.cmf_name) AS client_name
                    FROM misacmf AS a
                    INNER JOIN miscmf AS b ON b.id = a.cmf_code
                    WHERE a.is_deleted = '0'
                    GROUP BY client_name
                    ORDER BY b.cmf_name ASC ";
        $result = $this->db->query($stmt)->result_array();
        return $result;    
    }
    
    public function list_of_agency() {
        $stmt = "SELECT id, cmf_code, cmf_name, cmf_catad FROM miscmf WHERE cmf_catad != 2 AND is_deleted = 0 ORDER BY cmf_code";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function client_by_agency($agency) {
        
        $stmt = "SELECT b.id, b.cmf_code, b.cmf_name FROM misacmf AS a 
                INNER JOIN miscmf AS b ON b.id = a.cmf_code
                WHERE a.amf_code = (SELECT id FROM miscmf WHERE cmf_code = '$agency') AND a.is_deleted = 0 ORDER BY b.cmf_code";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getThisCustomerDataInfo_ID($who) {
        
        $stmt = "SELECT cmf_name, cmf_add1, cmf_add2, cmf_add3 FROM miscmf WHERE id = '$who'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function getThisCustomerDataInfo_CODE($who) {
        
        $stmt = "SELECT cmf_name, cmf_add1, cmf_add2, cmf_add3 FROM miscmf WHERE cmf_code = '$who'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    

    #Update 2018-11-13 removed (cmf_catad != '1')
    public function list_of_client_filter($search) {
        $stmt = "SELECT id, cmf_code, cmf_name,
                        CONCAT(IFNULL(cc_name1, ''), ' ', IFNULL(cc_position1, ''), ' ', IFNULL(cc_email1,''), ' ', IFNULL(cc_contact1, '')) AS contact1,
                        CONCAT(IFNULL(cc_name2, ''), ' ', IFNULL(cc_position2, ''), ' ', IFNULL(cc_email2,''), ' ', IFNULL(cc_contact2, '')) AS contact2,
                        CONCAT(IFNULL(cc_name3, ''), ' ', IFNULL(cc_position3, ''), ' ', IFNULL(cc_email3,''), ' ', IFNULL(cc_contact3, '')) AS contact3 
                 FROM miscmf 
                 WHERE (cmf_code LIKE '%$search%' OR cmf_name LIKE '$search%') ORDER BY cmf_code";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
      
    }
    public function list_of_clientinfo($search) {
        $stmt = "SELECT id, cmf_code, cmf_name,
                        CONCAT(IFNULL(cc_name1, ''), ' ', IFNULL(cc_position1, ''), ' ', IFNULL(cc_email1,''), ' ', IFNULL(cc_contact1, '')) AS contact1,
                        CONCAT(IFNULL(cc_name2, ''), ' ', IFNULL(cc_position2, ''), ' ', IFNULL(cc_email2,''), ' ', IFNULL(cc_contact2, '')) AS contact2,
                        CONCAT(IFNULL(cc_name3, ''), ' ', IFNULL(cc_position3, ''), ' ', IFNULL(cc_email3,''), ' ', IFNULL(cc_contact3, '')) AS contact3 
                 FROM miscmf 
                 WHERE (cmf_code LIKE '%$search%' OR cmf_name LIKE '$search%') ORDER BY cmf_code";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
      
    }
    
    public function clientinfo($id) {
        $stmt = "SELECT id, cmf_code, cmf_name,
                        CONCAT(IFNULL(cc_name1, ''), ' ', IFNULL(cc_position1, ''), ' ', IFNULL(cc_email1,''), ' ', IFNULL(cc_contact1, '')) AS contact1,
                        CONCAT(IFNULL(cc_name2, ''), ' ', IFNULL(cc_position2, ''), ' ', IFNULL(cc_email2,''), ' ', IFNULL(cc_contact2, '')) AS contact2,
                        CONCAT(IFNULL(cc_name3, ''), ' ', IFNULL(cc_position3, ''), ' ', IFNULL(cc_email3,''), ' ', IFNULL(cc_contact3, '')) AS contact3 
                 FROM miscmf 
                 WHERE id = $id ORDER BY cmf_code";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;    
        
    }
    
    public function list_of_agency_filter($search) {
        $stmt = "SELECT id, cmf_code, cmf_name,
                        CONCAT(IFNULL(cc_name1, ''), ' ', IFNULL(cc_position1, ''), ' ', IFNULL(cc_email1,''), ' ', IFNULL(cc_contact1, '')) AS contact1,
                        CONCAT(IFNULL(cc_name2, ''), ' ', IFNULL(cc_position2, ''), ' ', IFNULL(cc_email2,''), ' ', IFNULL(cc_contact2, '')) AS contact2,
                        CONCAT(IFNULL(cc_name3, ''), ' ', IFNULL(cc_position3, ''), ' ', IFNULL(cc_email3,''), ' ', IFNULL(cc_contact3, '')) AS contact3  
                 FROM miscmf 
                 WHERE cmf_catad = '1' AND (cmf_code LIKE '%$search%' OR cmf_name LIKE '%$search%') ORDER BY cmf_code";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
      
    }
    
    public function list_of_client() {
        $stmt = "SELECT id, cmf_code, cmf_name FROM miscmf WHERE cmf_catad != '1' ORDER BY cmf_code LIMIT 1000";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getFileAttachmentofClientData($id) {
        
        $stmt = "SELECT a.*, b.username
                FROM dataupload AS a
                LEFT OUTER JOIN users AS b ON b.id = a.uploadby
                WHERE a.custid = '$id' AND isdeleted = 0  
                ORDER BY a.id ASC";
        
          $result = $this->db->query($stmt)->result_array(); 
        
        return $result;

    }
    
    public function getFileattachmentofClientDataUpload($id) {
        
        $stmt = "SELECT a.*, b.username
                FROM dataupload AS a
                LEFT OUTER JOIN users AS b ON b.id = a.uploadby
                WHERE a.id = '$id' AND isdeleted = 0  
                ORDER BY a.id ASC";
        
        $result = $this->db->query($stmt)->row_array();  
        
        return $result;

    }
    
    public function saveDataUpload($data){
          
        $data['uploadby'] = $this->session->userdata('authsess')->sess_id;
        $data['uploaddate'] = DATE('Y-m-d h:i:s');
        $data['reuploadby'] = $this->session->userdata('authsess')->sess_id;
        $data['reuploaddate'] = DATE('Y-m-d h:i:s');       
         
        $this->db->insert('dataupload', $data);  
        
    return true;  
    
    }
    
    public function removeupload($id) {
        
        $data['isdeleted'] = 1;
        
        $this->db->where('id', $id);            
        $this->db->update('dataupload', $data);
        
    return true;        
        
    }
    
}

