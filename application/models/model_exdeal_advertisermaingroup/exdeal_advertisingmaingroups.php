<?php

class Exdeal_advertisingmaingroups extends CI_Model
{
    public function insert($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;  
        
        $sql = "INSERT INTO exdeal_advertisergroup
                SET group_name = '$data[group_name]',
                    advertiser = '$data[advertiser]',
                    credit_limit = '$data[credit_limit]',
              --      address = '$data[address]',
                    contact_person = '$data[contact_person]',
                    telephone = '$data[telephone]',
                    user_id = $user_id,
                    is_deleted = 0 ";
        
        $result = $this->db->query($sql);  
        
        return $this->db->insert_id();
             
    }
    
    public function update($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
          
        $sql = "UPDATE exdeal_advertisergroup
                SET group_name = '$data[group_name]',
                    advertiser = '$data[advertiser]',
                    credit_limit = '$data[credit_limit]',
              --      address = '$data[address]',
                    contact_person = '$data[contact_person]',
                    telephone = '$data[telephone]',
                    user_id = $user_id,
                    is_deleted = 0
                WHERE id = '$data[id]' ";
        
        $result = $this->db->query($sql); 
    }
    
    public function remove($id)
    {
        $user_id = $this->session->userdata('authsess')->sess_id; 
        
        $sql = "UPDATE exdeal_advertisergroup SET is_deleted = '1' WHERE id = '$id' ";
        
        $result = $this->db->query($sql);
    }
    
    public function getalladvertisergroup()
    {
        $sql = "SELECT id,group_name,advertiser FROM exdeal_advertisergroup WHERE is_deleted = '0' ";
        
        return $this->db->query($sql)->result();
    }
    
    public function getadvertisergroup($id)
    {
       $sql = "SELECT id,group_name,advertiser,credit_limit,address,contact_person,telephone 
               FROM exdeal_advertisergroup
               WHERE id = '$id' "; 
       
       return $this->db->query($sql)->row(); 
    }
    
    public function getassignedadvertiser($id)
    {
        $sql = "SELECT a.id, b.cmf_name
                FROM exdeal_advertiserlist AS a
                LEFT OUTER JOIN miscmf AS b ON b.id = a.advertiser_id
                WHERE a.advertiser_group_id = (SELECT advertiser_group_id FROM exdeal_contract WHERE id = '$id' AND is_deleted = '0' ) ";
        
        return $this->db->query($sql)->result();
    }
    
    public function getassignedadvertiserlist($id)
    {
        $sql = "SELECT a.id, b.cmf_name
                FROM exdeal_advertiserlist AS a
                LEFT OUTER JOIN miscmf AS b ON b.id = a.advertiser_id
                WHERE a.advertiser_group_id = $id ";
        
        return $this->db->query($sql)->result();
    }
    
    public function insertintogrouplist($data)
    {
       $sql = "INSERT INTO exdeal_advertiserlist
               SET advertiser_id = '$data[advertiser_id]', 
                   advertiser_group_id = '$data[group_id]' ";
       
       $this->db->query($sql);  
    }
    
    public function removeassignedadvertiser($id)
    {
        $sql = "DELETE FROM exdeal_advertiserlist WHERE id = '$id'";
        
        $this->db->query($sql);  
    }
    
    public function list_of_free_customer()
    {
        $stmt = "SELECT id, TRIM(cmf_name) AS cmf_name
                 FROM miscmf 
                 WHERE is_deleted = '0'
                 -- AND id NOT IN (SELECT advertiser_id FROM exdeal_advertiserlist )
                 ORDER BY cmf_name ASC LIMIT 50";
        $result = $this->db->query($stmt)->result();

        return $result;
    }
    
    public function search_free_customer($data)
    {
        $adv = $data['adv'];
        $con = "SELECT advertiser_id FROM exdeal_advertiserlist";
        if (!empty($adv)) $con = "SELECT advertiser_id FROM exdeal_advertiserlist WHERE advertiser_group_id = $adv";
        $stmt = "SELECT id, TRIM(cmf_name) AS cmf_name
                 FROM miscmf 
                 WHERE is_deleted = '0'
                 AND id NOT IN ($con)
                 AND (cmf_name LIKE '%$data[search]%')
                 ORDER BY cmf_name ASC LIMIT 50";
        $result = $this->db->query($stmt)->result();

        return $result;
    }
    
    public function more_customer($data)
    {
       $stmt = "SELECT id, TRIM(cmf_name) AS cmf_name
                 FROM miscmf 
                 WHERE is_deleted = '0'
                 AND id NOT IN (SELECT advertiser_id FROM exdeal_advertiserlist )
                 AND TRIM(cmf_name) >= '$data[cmf_name]'
                 ORDER BY cmf_name ASC LIMIT 10";
        $result = $this->db->query($stmt)->result();

        return $result; 
    }
    
    public function list_of_grouped_customer()
    {
         $stmt = "SELECT id, cmf_name
                 FROM miscmf 
                 WHERE is_deleted = '0'
                 AND id NOT IN (SELECT advertiser_id FROM exdeal_advertisergroup )";
        $result = $this->db->query($stmt)->result_array();

        return $result;
    } 
}