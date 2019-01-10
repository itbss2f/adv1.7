<?php 

class Model_function extends CI_Model {

    public function removeData($id) {
        $data['is_deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update('functions', $data);
        return true;
    } 

    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');

        $this->db->where('id', $id);
        $this->db->update('functions', $data);
        return true;
    }

    public function getData($id) {
        $stmt = "SELECT id, name, description FROM functions WHERE id = '$id'";

        $result = $this->db->query($stmt)->row_array();

        return $result;
    }

    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');

        $this->db->insert('functions', $data);
        return true;
    }

    public function listFunction() {
        $stmt = "SELECT id, name, description FROM functions WHERE is_deleted = 0;";
        
        $result = $this->db->query($stmt)->result_array();

        return $result;
    }
}
