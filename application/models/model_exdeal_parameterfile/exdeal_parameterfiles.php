<?php
    
class Exdeal_parameterfiles extends CI_Model
{
    public function getall()
    {
        $sql = "SELECT * FROM exdeal_parameterfile WHERE is_deleted = '0'";

        return $this->db->query($sql)->result();
    }
    
    public function get($data)
    {
        $sql = "SELECT * FROM exdeal_parameterfile WHERE id = '$data[id]'";

        return $this->db->query($sql)->row();
    }
    
    public function insert($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id; 
        
        $sql = "INSERT INTO exdeal_parameterfile
                SET company_code = '$data[company_code]',
                    company_name = '$data[company_name]', 
                    recommended_by = '$data[recommended_by]', 
                    rec_position = '$data[rec_position]', 
                    noted_by = '$data[noted_by]', 
                    not_position = '$data[not_position]', 
                    recommended_by2 = '$data[recommended_by2]', 
                    rec_position2 = '$data[rec_position2]', 
                    noted_by2 = '$data[noted_by2]', 
                    not_position2 = '$data[not_position2]',
                    approved_by = '$data[approved_by]', 
                    app_position = '$data[app_position]', 
                    b_last_contract_no = '$data[b_last_contract_no]', 
                    n_last_contract_no = '$data[n_last_contract_no]',
                    user_id = '$user_id' "; 
      
        $result = $this->db->query($sql);
        
        return $this->db->insert_id();
       
    }

    public function update($data)
    {
        $sql = "UPDATE exdeal_parameterfile 
                SET company_code = '$data[company_code]',
                    company_name = '$data[company_name]', 
                    recommended_by = '$data[recommended_by]', 
                    rec_position = '$data[rec_position]', 
                    noted_by = '$data[noted_by]', 
                    not_position = '$data[not_position]',
                    recommended_by2 = '$data[recommended_by2]', 
                    rec_position2 = '$data[rec_position2]', 
                    noted_by2 = '$data[noted_by2]', 
                    not_position2 = '$data[not_position2]', 
                    approved_by = '$data[approved_by]', 
                    app_position = '$data[app_position]', 
                    b_last_contract_no = '$data[b_last_contract_no]', 
                    n_last_contract_no = '$data[n_last_contract_no]' 
                WHERE id = '$data[id]'";

        $result = $this->db->query($sql);
        
        return $this->db->affected_rows();  
    }
    
    public function delete($id)
    {
        $sql = "UPDATE exdeal_parameterfile SET is_deleted = '1' WHERE id = '$id' ";
        
        $result = $this->db->query($sql);
    }
}
