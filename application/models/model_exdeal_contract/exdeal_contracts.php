<?php
class Exdeal_contracts extends CI_Model
{
    
    public function autonumbering($type) {
        $year = DATE('Y');
        $stmt = "SELECT contract_no, SUBSTRING(contract_no,3,4) AS cyear, SUBSTRING(contract_no,8,3) AS ccount,
                       LPAD(IFNULL(MAX( SUBSTRING(contract_no,8,3) + 1), 1), 3,0) AS nextcontractnumber
                FROM exdeal_contract WHERE contract_type = '$type' AND SUBSTRING(contract_no,3,4) = $year";
        $result = $this->db->query($stmt)->row_array();  
        
        return  $result;
    }
    public function insert($data)
    {
        $sql = "INSERT INTO exdeal_contract
                set contract_type = '$data[contract_type]',
                    contract_no = '$data[contract_no]',
                    contract_date = '$data[contract_date]',
                    advertiser_group_id = '$data[advertiser_group_id]',
                    advertiser_id = '$data[advertiser_id]',
                    advertising_agency = '$data[advertising_agency]',
                    amount = '$data[amount]',
                    contact_person = '$data[contact_person]',
                    telephone = '$data[telephone]',
                    barter_ratio = '$data[barter_ratio]',
                    cash_ratio = '$data[cash_ratio]',
                    barter_request = '$data[barter_request]',
                    remarks = '$data[remarks]',
                    `status` = '$data[status]'
                    ";
        
        $this->db->query($sql);
        
        $id = $this->db->insert_id();
        
        $this->doc_rec($data['doc_req_id'],$id);
        
        $this->approver($data['approver'],$id);  
        
        $this->barter_condition($data['barter_conditon'],$data['condition_type'],$id);  
        
    }

    
    public function barter_condition($barter_condition,$condition_type,$id)
    { 

      foreach($barter_condition as $barcon => $barc)
        {
            $sql = "INSERT INTO exdeal_contract_condition
                             SET barter_condition = '".$this->db->escape_str($barter_condition[$barcon])."',
                                    condition_type = '$condition_type[$barcon]',
                                   contract_id = '$id' ";
           
           $this->db->query($sql);    
        }  
    } 
    
    public function approver($approver,$id)
    {
       
        
        foreach($approver as $appr => $val)
        {
             $sql = "INSERT INTO exdeal_contract_approver
                             SET approver_id = '$approver[$appr]',
                                 contract_id = '$id' ";
           
           $this->db->query($sql);    
        }
    }
    
    public function doc_rec($doc_rec,$id)
    {
         
        foreach($doc_rec as $doc => $val)
        {
           $sql = "INSERT INTO exdeal_doc_contract
                   SET doc_req_id = (SELECT id FROM exdeal_docreq WHERE doc_name = '$doc_rec[$doc]'),
                       contract_id = '$id' ";
           
           $this->db->query($sql);    
        }
    }
    
    public function update($data)
    {
       $sql = "UPDATE exdeal_contract
               SET contract_type = '$data[contract_type]',
               contract_no = '$data[contract_no]',
               contract_date = '$data[contract_date]',
               advertiser_group_id = '$data[advertiser_group_id]',
               advertiser_id = '$data[advertiser_id]',
               advertising_agency = '$data[advertising_agency]',
               amount = '$data[amount]',
               contact_person = '$data[contact_person]',
               telephone = '$data[telephone]',
               barter_ratio = '$data[barter_ratio]',
               cash_ratio = '$data[cash_ratio]',
               barter_request = '$data[barter_request]',
               remarks = '$data[remarks]',
               `status` = '$data[status]'
               WHERE id = '$data[contract_id]'
             ";

        $this->db->query($sql);
    
        $this->delete_exdeal_contract_approver($data['contract_id']);
    
        $this->delete_exdeal_barter_condtion($data['contract_id']);

        $this->delete_exdeal_barter_condtion_type($data['contract_id']);
        
        $this->delete_exdeal_doc_contract($data['contract_id']);
         
        $this->doc_rec($data['doc_req_id'],$data['contract_id']);
        
        $this->approver($data['approver'],$data['contract_id']); 
        
        $this->barter_condition($data['barter_condition'],$data['condition_type'],$data['contract_id']);  
    }
    
    public function delete_exdeal_doc_contract($contract_id)
    {
        $sql = "DELETE FROM exdeal_doc_contract WHERE contract_id = '$contract_id'";
        
        $this->db->query($sql);
    }
    
    public function delete_exdeal_contract_approver($contract_id)
    {
        $sql = "DELETE FROM exdeal_contract_approver WHERE contract_id = '$contract_id'";        
        $this->db->query($sql);   
    }
    
    public function delete_exdeal_barter_condtion($contract_id)
    {
        $sql = "DELETE FROM exdeal_contract_condition WHERE contract_id = '$contract_id'";
        
        $this->db->query($sql);   
    }
    
    public function delete_exdeal_barter_condtion_type($contract_id)
    {
         $sql = "DELETE FROM exdeal_barter_condition_type WHERE contract_id = '$contract_id'";
        
         $this->db->query($sql);
    }
    
    public function saveupload($filename)
    {
        $sql_id = "SELECT LAST_INSERT_ID(id) as last_id FROM exdeal_contract   ORDER BY 1 DESC LIMIT 1";
        
        $res = $this->db->query($sql_id)->row();
        
        $stmt = "UPDATE exdeal_contract SET attachment_file='$filename' WHERE id = '$res->last_id'";
        
        $this->db->query($stmt);
    }
    
    public function updateAttachment($data)
    {
         $stmt = "UPDATE exdeal_contract SET attachment_file='$data[filename]' WHERE id = '$data[id]'";
        
         $this->db->query($stmt);
    }
    
    public function getcontracts()
    {
        $sql = "SELECT a.id,b.group_name,
                       a.advertiser_id,
                       b.advertiser,
                       b.credit_limit,
                       b.contact_person,
                       a.amount,
                       a.contract_no,
                       CASE a.contract_type
                       WHEN 'B' THEN 'Billable'
                       WHEN 'N' THEN 'Non-Billable'
                       END contract_type,
                       a.contract_date,
                       a.attachment_file
                FROM exdeal_contract AS a
                LEFT OUTER JOIN exdeal_advertisergroup AS b ON b.id = a.advertiser_group_id
                WHERE a.is_deleted = '0'
                ORDER BY a.contract_date ";
        
        return $this->db->query($sql)->result();
    } 
    
    public function remove($id)
    {
        $sql = "UPDATE exdeal_contract SET is_deleted = '1' WHERE id = '$id' ";
        
        $this->db->query($sql); 
    }
    
    public function selectcontract($id)
    {
          $sql = "SELECT a.id,
                       GROUP_CONCAT(g.doc_name) AS doc_name,
                       f.cmf_add1,
                       f.cmf_add2,
                       f.cmf_add3,
                       a.contract_type,
                       a.contract_no,
                       a.contract_date,
                       a.advertiser_group_id,
                       a.advertiser_id,
                       b.group_name,
                       b.advertiser,
                       a.advertising_agency,       
                       a.amount,
                       b.contact_person,
                       a.telephone,
                       a.barter_ratio,
                       a.cash_ratio,
                       a.barter_request,
                       a.remarks,
                       GROUP_CONCAT(c.doc_req_id) AS doc_con_id,
                       a.status,
                       d.approver_id,
                       a.attachment_file
                FROM exdeal_contract AS a
                LEFT OUTER JOIN exdeal_advertisergroup AS b ON b.id = a.advertiser_group_id
                LEFT OUTER JOIN exdeal_doc_contract AS c ON c.contract_id = a.id
                LEFT OUTER JOIN exdeal_contract_approver AS d ON d.contract_id = a.id
                LEFT OUTER JOIN exdeal_advertiserlist AS e ON e.advertiser_group_id  = a.advertiser_group_id                
                LEFT OUTER JOIN miscmf AS f ON f.id  = e.advertiser_id 
                LEFT OUTER JOIN exdeal_docreq AS g ON g.id = c.doc_req_id                               
                WHERE a.is_deleted = '0'
                      AND a.id = $id
                ORDER BY a.contract_date

                 ";
        
        return $this->db->query($sql)->row();
    }
    
        
    public function selectcontract2($id)
    {
          $sql = "SELECT a.id,
                     CONCAT(j.firstname,' ',j.middlename,' ',j.lastname) AS acct_exec,
                    --  GROUP_CONCAT(' ',g.doc_name) AS doc_name,
                    g.doc_name,
                    --  g.doc_name AS doc_name,
                       k.recommended_by,
                       k.rec_position,
                       k.recommended_by2,
                       k.rec_position2,
                       k.noted_by,
                       k.not_position,
                       k.noted_by2,
                       k.not_position2,
                       k.approved_by,
                       k.app_position,
                       k.approved_by2,
                       k.app_position2,
                       f.cmf_add1,
                       f.cmf_add2,
                       f.cmf_add3,
                       a.contract_type,
                       a.contract_no,
                       a.contract_date,
                       a.advertiser_group_id,
                       a.advertiser_id,
                       b.group_name,
                       b.advertiser,
                       a.advertising_agency,       
                       a.amount,
                       b.contact_person,
                       a.telephone,
                       a.barter_ratio,
                       a.cash_ratio,
                       a.barter_request,
                       a.remarks,
                       GROUP_CONCAT(c.doc_req_id) AS doc_con_id,
                       a.status,
                       d.approver_id,
                       a.attachment_file
                FROM exdeal_contract AS a
                LEFT OUTER JOIN exdeal_advertisergroup AS b ON b.id = a.advertiser_group_id
                LEFT OUTER JOIN exdeal_doc_contract AS c ON c.contract_id = a.id
                LEFT OUTER JOIN exdeal_contract_approver AS d ON d.contract_id = a.id
                LEFT OUTER JOIN exdeal_advertiserlist AS e ON e.advertiser_group_id  = a.advertiser_group_id                
                LEFT OUTER JOIN miscmf AS f ON f.id  = e.advertiser_id 
                LEFT OUTER JOIN exdeal_docreq AS g ON g.id = c.doc_req_id  
                LEFT OUTER JOIN ao_p_tm AS h ON h.ao_exdealcontractno  = a.contract_no                                                
                LEFT OUTER JOIN ao_m_tm AS i ON i.ao_num = h.ao_num  
                LEFT OUTER JOIN users AS j ON j.id = i.ao_aef  
                LEFT OUTER JOIN exdeal_parameterfile AS k ON k.id = d.approver_id                               
                WHERE a.is_deleted = '0'
                      AND a.id = $id
                ORDER BY a.contract_date

                 ";
        
        return $this->db->query($sql)->row();
    }
    
    
    public function getBarterAgreement($id)
    {
          $stmt = "SELECT barter_condition,condition_type FROM exdeal_contract_condition WHERE contract_id = '$id'";
          
          return $this->db->query($stmt)->result();
    }

      
    public function search_customer($data)
    {
        $stmt = "SELECT id, TRIM(cmf_name) AS cmf_name
                 FROM miscmf 
                 WHERE is_deleted = '0'
                 AND (cmf_name LIKE '%$data[search]%')
                 ORDER BY cmf_name ASC LIMIT 10";
                 
        return $this->db->query($stmt)->result();           
    }
    
    public function search_contract($data)
    {
         $stmt = "SELECT id, contract_no
                 FROM exdeal_contract 
                 WHERE is_deleted = '0'
                 AND (contract_no LIKE '%$data[search]%')
                 AND (advertiser_group_id IN (SELECT id FROM exdeal_advertisergroup WHERE group_name LIKE '%$data[advertiser]%'))
                 ORDER BY contract_no ASC LIMIT 10";

        return $this->db->query($stmt)->result();
    }
    
        
    public function retrieve_barter_condition($id)
    { 
        $new_id = implode(",",$id);
             
        $stmt = "SELECT id,
                        barter_condition,
                        category_id,
                         CASE category_id
                            WHEN '1' THEN 'Commodities'
                            WHEN '2' THEN 'Gift Certificate'
                            WHEN '3' THEN 'Hotels/Resorts'
                        END category,
                        con_status FROM exdeal_barter_condition 
                WHERE (category_id IN ($new_id)) AND is_deleted = 0 ";
                
         $result = $this->db->query($stmt);

         return $result->result();        
    }
    
    public function getConditionList($id)
    {
        $stmt = "SELECT barter_condition,condition_type FROm exdeal_contract_condition WHERE contract_id = $id  ORDER BY condition_type ASC";
        
        $result = $this->db->query($stmt);
        
        return $result->result();
    }
    
    public function validateContractNo($contract_no)
    {
        $stmt = "SELECT * FROM exdeal_contract WHERE contract_no = '$contract_no' AND is_deleted = '0'";
        $result = $this->db->query($stmt);
        return $result->num_rows() > 0 ? TRUE : FALSE;
    }

} 