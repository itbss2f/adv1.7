<?php

class Material_uploading extends CI_Model  {
    
    public function removedata($layout_boxes_id) {
        
        $data['material_uploadby'] = $this->session->userdata('authsess')->sess_id;
        $data['material_updatedate'] = DATE('Y-m-d h:i:s'); 
        $data['material_filename'] = null;
        $data['material_remarks'] = null;
        $data['material_status'] = 'A';   
        
        $this->db->where('id', $layout_boxes_id);        
        $this->db->update('ao_p_tm', $data);
        
        return true;        
        
        
    }
    
    public function getProductInfo($product, $datefrom){
        
        
        $stmt = "SELECT b.book_name, b.folio_number, b.class_code, a.ao_num,a.layout_boxes_id,
                    CONCAT(IFNULL(c.ao_width, 0), ' x ', IFNULL(c.ao_length, 0)) AS size,
                    c.ao_totalsize, mc.color_code,m.ao_payee AS client_name,ac.cmf_name AS agency, 
                    CONCAT(u.lastname,', ',u.firstname,' ',SUBSTR(u.middlename,1,1),'. ') AS ae, material_status,
                    a.prod_code AS product, c.material_remarks 
                    FROM d_layout_boxes AS a
                    LEFT OUTER JOIN d_layout_pages AS b ON b.layout_id = a.layout_id
                    LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.layout_boxes_id 
                    LEFT OUTER JOIN ao_m_tm AS m ON m.ao_num = c.ao_num
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN miscolor AS mc ON mc.id = b.color_code
                    LEFT OUTER JOIN miscmf AS ac ON ac.id =  m.ao_amf   
                    WHERE DATE(a.issuedate) = '$datefrom' AND a.prod_code = '$product' AND a.component_type = 'advertising' AND book_name !=''
                    ORDER BY b.book_name, b.folio_number";
                    
        #echo "<pre>"; echo $stmt; exit;            
        
        $result = $this->db->query($stmt)->result_array(); 
        
    return $result;           
    }
    
    public function getThisFileAttachment($id) {
        
        $stmt = "SELECT a.id, a.material_status, a.material_uploadby, a.material_updatedate, a.material_filename, a.material_remarks, 
                b.prod_code AS product, DATE(b.issuedate) AS datefrom
                FROM ao_p_tm AS a
                LEFT OUTER JOIN d_layout_boxes AS b ON b.layout_boxes_id = a.id
                WHERE a.id = '$id'";
                
                #echo "<pre>"; echo $stmt; exit;
        
        $result = $this->db->query($stmt)->row_array();
        
    return $result;
        
    }
    
    public function savefileupload($layout_boxes_id, $data) {
        
        $data['material_uploadby'] = $this->session->userdata('authsess')->sess_id;
        $data['material_updatedate'] = DATE('Y-m-d h:i:s');
        $data['material_status'] = 'U';      
        
        $this->db->where('id' , $layout_boxes_id); 
        $this->db->update('ao_p_tm', $data);  
        
    return true;  
        
    }


    
}