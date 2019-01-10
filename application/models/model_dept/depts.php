<?php
class Depts extends CI_Model {
    
    public function listOfDeptByExpenseType($substring) {

        $stmt = "SELECT id, dept_code, dept_name FROM misdept WHERE SUBSTRING(exp_type,1, 2) = '$substring'";
        
        $result = $this->db->query($stmt)->result_array();

        #echo'<pre>'; echo $stmt ; exit;
        
        return $result;
    }
    
    public function listOfDept() {

        $stmt = "SELECT id, dept_code, dept_name, mdept_name, sect_name, group_name, exp_type, prod_code, dept_branchstatus
                FROM misdept WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();

        #echo'<pre>'; echo $stmt ; exit;
        
        return $result;
    }

    public function getData($id) {

        $stmt = "SELECT a.id, a.dept_code, a.dept_name, a.mdept_name, a.sect_name, 
                a.group_name, a.exp_type, a.prod_code, a.dept_branchstatus, prod.prod_name
                FROM misdept AS a
                LEFT OUTER JOIN misprod AS prod ON prod.id = a.prod_code
                WHERE a.is_deleted = 0 AND a.id = '$id'";
        
        $result = $this->db->query($stmt)->row_array();

        #echo'<pre>'; echo $stmt ; exit;
        
        return $result;
    }

    public function saveNewData($data) {

        $data['status'] = 'A';
        $data['status_d'] = DATE('Y-m-d h:i:s');
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['user_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->insert('misdept', $data);
        
        return true;    
    }

    public function saveupdateNewData($data, $id) {

        #$data['status'] = 'A';
        #$data['status_d'] = DATE('Y-m-d h:i:s');
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misdept', $data);
        
        return true;    
    }

     public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misdept', $data);
        
        return true;        
    }

    public function searched($search)
    {
                    $concode = ""; $conname = ""; $conmname = ""; $condeptbranch = ""; $consection = ""; $conexp = "";
                    
                    if ($search['dept_code'] != "") { $concode = " AND dept_code LIKE '".$search['dept_code']."%' ";}
                    if ($search['dept_name'] != "") { $conname = "AND dept_name LIKE '".$search['dept_name']."%'  "; }
                    if ($search['mdept_name'] != "") { $conmname = "AND mdept_name = '".$search['mdept_name']."'  "; }
                    if ($search['dept_branchstatus'] != "") { $condeptbranch = "AND dept_branchstatus = '".$search['dept_branchstatus']."'  "; }
                    if ($search['sect_name'] != "") { $consection = "AND sect_name = '".$search['sect_name']."'  "; }
                    if ($search['exp_type'] != "") { $conexp = "AND exp_type = '".$search['exp_type']."'  "; }

                    $stmt = "SELECT id, dept_code, mdept_name, dept_name, dept_branchstatus, sect_name, exp_type
                                    FROM misdept
                                    WHERE is_deleted = 0 $concode $conname $conmname $condeptbranch $consection $conexp"; 
                    
                    //echo "<pre>";
                    //echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }


}


