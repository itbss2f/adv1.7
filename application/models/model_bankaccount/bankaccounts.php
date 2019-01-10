<?php

class Bankaccounts extends CI_Model {
    

    
    public function delete($id)
    {
        $stmt = "UPDATE misbaf SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        $this->db->query($stmt);
        return TRUE;
    }
    

    public function updateBankAccount($data)
    {
            $userid = $this->session->userdata('authsess')->sess_id;

            $stmt = "UPDATE misbaf SET baf_acct ='".$data['baf_acct']."',
                                       baf_bank = '".$data['baf_bank']."',
                                       baf_bnch = '".$data['baf_bnch']."',
                                       baf_at = '".$data['baf_at']."',
                                       baf_an = '".$data['baf_an']."',
                                       user_n = '".$userid."',
                                       edited_n = NOW()
                                       WHERE id = '".$data['id']."'
                                    
            ";
            $this->db->query($stmt);
            return true;
    }
    
    public function updateBankAccountBalance($data)
    {
         $userid = $this->session->userdata('authsess')->sess_id;  
        $stmt = "UPDATE misbaf SET beg_amt = '".$data['beg_amt']."', 
                                   beg_code = '".$data['beg_code']."', 
                                   beg_date = '".$data['beg_date']."', 
                                   beg_bamt = '".$data['beg_bamt']."',   
                                   beg_bcode = '".$data['beg_bcode']."', 
                                   beg_bdate = '".$data['beg_bdate']."', 
                                   run_amt = '".$data['run_amt']."', 
                                   run_code = '".$data['run_code']."', 
                                   run_date = '".$data['run_date']."', 
                                   run_bamt = '".$data['run_bamt']."', 
                                   run_bcode = '".$data['run_bcode']."', 
                                   run_bdate = '".$data['run_bdate']."', 
                                   edited_n = '".$userid."',
                                   edited_d = NOW() WHERE id = '".$data['id']."'";        
        
        $this->db->query($stmt);
        return TRUE;
    } 
    
    public function thisBankAccount($id)
    {
        $stmt = "SELECT a.id, a.baf_acct, a.baf_bank, b.id AS bbf_id, c.id AS bmf_id, a.baf_bnch, a.baf_at,
                       a.baf_an, a.beg_amt, a.beg_code, a.beg_date, a.run_amt,
                       a.run_code, a.run_date, a.beg_bamt, a.beg_bcode, a.beg_bdate,
                       a.run_bamt, a.run_bcode, a.run_bdate, b.bbf_bnch, b.bbf_add1, b.bbf_add2, b.bbf_add3, c.bmf_code, c.bmf_name,
                       CASE 
                           WHEN a.baf_at = 'C' THEN 'CURRENT'
                           WHEN a.baf_at = 'D' THEN 'DOLLAR'
                           WHEN a.baf_at = 'S' THEN 'SAVINGS'
                           WHEN a.baf_at = 'P' THEN 'PLACEMENT'
                           WHEN a.baf_at = 'A' THEN 'ALL IN ONE'
                       END AS accounttype
                FROM misbaf AS a
                INNER JOIN misbbf AS b ON a.baf_bank = b.id
                INNER JOIN misbmf AS c ON b.bbf_bank = c.id
                WHERE a.id = '".$id."'
                AND a.is_deleted = '0'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function seekBankAccount($id)
    {
        $stmt = "SELECT a.id, a.baf_acct,  c.bmf_code,b.bbf_bnch,
                       
                       CASE 
                           WHEN a.baf_at = 'C' THEN 'CURRENT'
                           WHEN a.baf_at = 'D' THEN 'DOLLAR'
                           WHEN a.baf_at = 'S' THEN 'SAVINGS'
                           WHEN a.baf_at = 'P' THEN 'PLACEMENT'
                           WHEN a.baf_at = 'A' THEN 'ALL IN ONE'
                       END AS accounttype,
                        a.baf_an
                FROM misbaf AS a
                LEFT OUTER JOIN misbbf AS b ON a.baf_bnch = b.id
                LEFT OUTER JOIN misbmf AS c ON a.baf_bank = c.id
                WHERE a.is_deleted = '0' ORDER BY id desc";
       $result = $this->db->query($stmt)->row_array();
       return $result;
    }
    
    public function insertBankAccount($data)
    {
         $userid = $this->session->userdata('authsess')->sess_id;  
        $stmt = "INSERT INTO misbaf (baf_acct,baf_bank,baf_bnch,baf_at,baf_an,user_n,edited_n,edited_d) 
                 VALUES('".$data['baf_acct']."','".$data['baf_bank']."',
                         '".$data['baf_bnch']."','".$data['baf_at']."',
                        '".$data['baf_an']."','".$userid."','".$userid."',NOW())";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function listOfBankAccount() 
    {
        $stmt = "SELECT a.*, b.bmf_name AS bank, c.bbf_bnch AS branch,
                    CASE 
                            WHEN a.baf_at = 'C' THEN 'CURRENT'
                            WHEN a.baf_at = 'D' THEN 'DOLLAR'
                            WHEN a.baf_at = 'S' THEN 'SAVINGS'
                            WHEN a.baf_at = 'P' THEN 'PLACEMENT'
                            WHEN a.baf_at = 'A' THEN 'ALL IN ONE'
                    END AS accounttype
                    FROM misbaf AS a
                    LEFT OUTER JOIN misbmf AS b ON a.baf_bank = b.id
                    LEFT OUTER JOIN misbbf AS c ON a.baf_bnch = c.id
                    WHERE a.is_deleted = '0' ORDER BY id DESC";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function listOfBankAccountDesc($stat, $offset, $limit) 
    {
        $stmt = "SELECT a.id, a.baf_acct,  c.bmf_code,b.bbf_bnch,
                        CASE 
                           WHEN a.baf_at = 'C' THEN 'CURRENT'
                           WHEN a.baf_at = 'D' THEN 'DOLLAR'
                           WHEN a.baf_at = 'S' THEN 'SAVINGS'
                           WHEN a.baf_at = 'P' THEN 'PLACEMENT'
                           WHEN a.baf_at = 'A' THEN 'ALL IN ONE'
                       END AS accounttype,
                        a.baf_an
                FROM misbaf AS a
                LEFT OUTER JOIN misbbf AS b ON a.baf_bnch = b.id
                LEFT OUTER JOIN misbmf AS c ON a.baf_bank = c.id
                WHERE a.is_deleted = '0' ORDER BY id desc LIMIT 25 OFFSET $offset";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function countAll()
    {
        $stmt = "SELECT COUNT(id) as count_id FROM misbaf WHERE is_deleted = '0' ";
        
        $result = $this->db->query($stmt);
        
        return $result->row();
        
    }
    
    public function search($search)
    {
          $stmt = "SELECT a.id, a.baf_acct, a.baf_bank, b.id AS bbf_id, c.id AS bmf_id, a.baf_bnch, a.baf_at,
                       a.baf_an, a.beg_amt, a.beg_code, a.beg_date, a.run_amt,
                       a.run_code, a.run_date, a.beg_bamt, a.beg_bcode, a.beg_bdate,
                       a.run_bamt, a.run_bcode, a.run_bdate, b.bbf_bnch, b.bbf_add1, b.bbf_add2, b.bbf_add3, c.bmf_code,c.bmf_name, 
                       CASE 
                           WHEN a.baf_at = 'C' THEN 'CURRENT'
                           WHEN a.baf_at = 'D' THEN 'DOLLAR'
                           WHEN a.baf_at = 'S' THEN 'SAVINGS'
                           WHEN a.baf_at = 'P' THEN 'PLACEMENT'
                           WHEN a.baf_at = 'A' THEN 'ALL IN ONE'
                       END AS accounttype
                FROM misbaf AS a
                LEFT OUTER JOIN misbbf AS b ON a.baf_bnch = b.id
                LEFT OUTER JOIN misbmf AS c ON a.baf_bank = c.id
                WHERE a.is_deleted = '0' 
                AND (
                      a.id LIKE '".$search."%'
                     OR  a.baf_acct LIKE '".$search."%'
                     OR  c.bmf_code LIKE '".$search."%'
                     OR  c.bmf_name LIKE '".$search."%'
                     OR  CASE 
                           WHEN a.baf_at = 'C' THEN 'CURRENT'
                           WHEN a.baf_at = 'D' THEN 'DOLLAR'
                           WHEN a.baf_at = 'S' THEN 'SAVINGS'
                           WHEN a.baf_at = 'P' THEN 'PLACEMENT'
                           WHEN a.baf_at = 'A' THEN 'ALL IN ONE'
                          END LIKE '".$search."%'
                     OR  a.baf_an LIKE '".$search."%'
                     )
                ORDER BY a.id DESC     
                LIMIT 25 ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function getBankList() {
         $stmt = "SELECT id, bmf_code FROM misbmf WHERE is_deleted = '0'";
         
         $result = $this->db->query($stmt)->result_array();
         
         return $result;
    }
    
    public function getBranchList() {
        $stmt = "SELECT id, bbf_bnch FROM misbbf WHERE is_deleted = '0'";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
        public function removeData($id) {
        
        $data['is_deleted'] = 1;
                
        $this->db->where('id', $id);
        $this->db->update('misbaf', $data);
                
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
                    
        $this->db->where('id', $id);
        $this->db->update('misbaf', $data);
                    
        return true;    
    }
                
    public function getData($id) {
            $stmt = "SELECT * FROM misbaf WHERE id = '$id' AND is_deleted = 0";
            
            $result = $this->db->query($stmt)->row_array();
            
            return $result;
        }
                
    public function saveNewData($data) {
            $data['user_n'] = $this->session->userdata('authsess')->sess_id;
            $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
            $data['edited_d'] = DATE('Y-m-d h:i:s');
            $this->db->insert('misbaf', $data);  
            
            return true;  
        }
    
    public function searched($searchkey)
    {
        $concode = ""; $conname = ""; $concatad = "";
        $conclass = ""; $contype = ""; 
         
        if ($searchkey['baf_acct'] != "") { $concode = " AND a.baf_acct LIKE '".$searchkey['baf_acct']."%' ";}
        if ($searchkey['baf_bank'] != "") { $conname = "AND a.baf_bank = '".$searchkey['baf_bank']."'  "; }
        if ($searchkey['baf_an'] != "") {$concatad = "AND a.baf_an = '".$searchkey['baf_an']."'"; }
        if ($searchkey['baf_at'] != "") {$conclass = "AND a.baf_at LIKE '".$searchkey['baf_at']."%'"; }
        if ($searchkey['baf_bnch'] != "") {$contype = "AND a.baf_bnch = '".$searchkey['baf_bnch']."'"; }
        
        $stmt = "SELECT a.id, a.baf_acct, b.bmf_name AS bank, b.bmf_code, c.branch_name AS branch, a.baf_an, 
                        CASE 
                        WHEN a.baf_at = 'C' THEN 'CURRENT'
                        WHEN a.baf_at = 'D' THEN 'DOLLAR'
                        WHEN a.baf_at = 'S' THEN 'SAVINGS'
                        WHEN a.baf_at = 'P' THEN 'PLACEMENT'
                        WHEN a.baf_at = 'A' THEN 'ALL IN ONE'
                        END AS accounttype 
                        FROM misbaf AS a
                        LEFT OUTER JOIN misbmf AS b ON b.id = a.baf_bank
                        LEFT OUTER JOIN misbranch AS c ON c.id = a.baf_bnch
                        WHERE a.is_deleted = '0' $concode $conname $concatad $conclass $contype"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
   
}

/* End of file bankaccounts.php */
/* Location: ./applications/models/bankaccounts.php */