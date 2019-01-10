<?php

class Fileupload extends CI_Model  {
    
    public function getFileAttachment($aonum) { 
        
        
        $stmt = "SELECT a.*, b.username 
                FROM fileupload AS a
                LEFT OUTER JOIN users AS b ON b.id = a.uploadby  
                WHERE a.ao_num = '$aonum' AND isdeleted = 0  
                ORDER BY uploaddate DESC";
        
          $result = $this->db->query($stmt)->result_array(); 
        
        return $result;
    }
    
    public function getAOData($aonum) {
        
        
        $stmt = "SELECT CONCAT(a.ao_payee,' - ', a.ao_cmf) AS clientname, d.username AS book_by,
                       CONCAT(cmf.cmf_name,' - ', cmf.cmf_code) AS agencyname, b.adtype_name AS adtype, c.paytype_name AS paytype, ao_num
                FROM ao_m_tm AS a 
                LEFT OUTER JOIN misadtype AS b ON b.id = a.ao_adtype 
                LEFT OUTER JOIN mispaytype AS c ON c.id = a.ao_paytype 
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = a.ao_amf
                LEFT OUTER JOIN users AS d ON d.id = a.user_n 
                WHERE ao_num = '$aonum'";
                
                #echo "<pre>"; echo $stmt; exit; 
        
        $result = $this->db->query($stmt)->row_array(); 
        
        return $result;           
    }
    
    
    public function getAOFile($aonum) {
        
        $stmt = "SELECT a.*, b.adtype_name, c.paytype_name
                FROM ao_m_tm AS a
                LEFT OUTER JOIN misadtype AS b ON b.id = a.ao_adtype
                LEFT OUTER JOIN mispaytype AS c ON c.id = a.ao_paytype
                WHERE ao_num = '$aonum'
                LIMIT 150";
        
        $result = $this->db->query($stmt)->result_array(); 
        
    return $result;       
        
    }
    
    public function savefileupload($data) {
        
        //$data['upload_path'] = './uploads/uploading_of_data/';  
        $data['uploadby'] = $this->session->userdata('authsess')->sess_id;
        $data['uploaddate'] = DATE('Y-m-d h:i:s');
        $data['reuploadby'] = $this->session->userdata('authsess')->sess_id;
        $data['reuploaddate'] = DATE('Y-m-d h:i:s');       
         
        $this->db->insert('fileupload', $data);  
        
    return true;  
        
    }
    
    public function getThisFileAttachment($id) {
        
        $stmt = "SELECT a.*, b.username 
                FROM fileupload AS a
                LEFT OUTER JOIN users AS b ON b.id = a.uploadby  
                WHERE a.id = '$id' AND isdeleted = 0 
                ORDER BY uploaddate";
        
        $result = $this->db->query($stmt)->row_array(); 
        
        return $result;
    }
    
    public function removeData($id) {
        
        $data['isdeleted'] = 1;
        
        $this->db->where('id', $id);        
        $this->db->update('fileupload', $data);
        
        return true;        
        
    }

    
}