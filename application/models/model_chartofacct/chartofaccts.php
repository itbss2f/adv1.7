<?php
class Chartofaccts extends CI_Model {
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('miscaf', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('miscaf', $data);
        
        return true;        
    }
    
    public function getData($id) {
        $stmt = "SELECT id, caf_code, acct_main, acct_class, acct_item, acct_cont, acct_sub, acct_title, acct_des, acct_code, acct_type, acct_ctax, acct_fas
                FROM miscaf WHERE id = $id";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;    
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('miscaf', $data);  
        
        return true;  
    }
    
    public function listOfChartOfAccount() {
        
        $stmt = "SELECT id,caf_code,acct_main,acct_class,acct_item,acct_cont,
                       acct_sub,acct_title,acct_des,acct_code,acct_type,acct_ctax,
                       acct_wtax,acct_fas,type_code,prod_code,exp_code,fixed_type,
                       main_type,caf_main,caf_class,caf_item,caf_cont,caf_sub
                FROM miscaf WHERE is_deleted = '0' ORDER BY caf_code ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function listOfChartOfAcctInType($type) {
        
        $stmt = "SELECT id,caf_code,acct_main,acct_class,acct_item,acct_cont,
                       acct_sub,acct_title,acct_des,acct_code,acct_type,acct_ctax,
                       acct_wtax,acct_fas,type_code,prod_code,exp_code,fixed_type,
                       main_type,caf_main,caf_class,caf_item,caf_cont,caf_sub
                FROM miscaf WHERE is_deleted = '0' AND acct_code ='$type' ORDER BY caf_code ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function listOfChartOfAcct($search="", $stat, $offset, $limit) {
        
        $stmt = "SELECT id,caf_code,acct_main,acct_class,acct_item,acct_cont,
                       acct_sub,acct_title,acct_des,acct_code,acct_type,acct_ctax,
                       acct_wtax,acct_fas,type_code,prod_code,exp_code,fixed_type,
                       main_type,caf_main,caf_class,caf_item,caf_cont,caf_sub
                FROM miscaf WHERE is_deleted = '0' ORDER BY caf_code ASC LIMIT 25 OFFSET $offset ";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function countAll()
    {
        $stmt = "SELECT COUNT(id) as count_id FROM miscaf WHERE is_deleted = 0";
        $result = $this->db->query($stmt);
        return $result->row();;
        
    }
    
    public function thisChartOfAcct($id)
    {
        $stmt = "SELECT id,caf_code,acct_main,acct_class,acct_item,acct_cont,
                       acct_sub,acct_title,acct_des,acct_code,acct_type,acct_ctax,
                       acct_wtax,acct_fas,type_code,prod_code,exp_code,fixed_type,
                       main_type,caf_main,caf_class,caf_item,caf_cont,caf_sub
                FROM miscaf WHERE is_deleted =0 AND id= $id ";
        
        $result = $this->db->query($stmt)->row_array();    
       
        return $result;  
    }
    
        public function updateChartAccount($data)
    {
        
        $userid = $this->session->userdata('sess_id');
        $array = array(
                        /*
                        'acct_main'        =>$data['acct_main'],
                                                'acct_sub'        =>$data['acct_sub'],
                                                'acct_item'        =>$data['acct_item'],
                                                'acct_cont'        =>$data['acct_cont'],
                                                'acct_class'    =>$data['acct_class'],    */
                        
                        'acct_code'        =>$data['acct_code'],                
                        'caf_main'         =>$data['caf_main'],
                        'caf_code'         =>$data['caf_code'],
                        'caf_item'         =>$data['caf_item'],
                        'caf_cont'         =>$data['caf_cont'],
                        'caf_class'        =>$data['caf_class'],
                        'acct_title'       =>$data['acct_title'],
                        'acct_des'         =>$data['acct_des'],
                        'acct_code'        =>$data['acct_code'],
                        'fixed_type'       =>$data['fixed_type'],
                        'acct_type'        =>$data['acct_type'],
                        'prod_code'        =>$data['prod_code'],
                        'acct_ctax'        =>$data['acct_ctax'],
                        'type_code'        =>$data['type_code'],
                        'acct_wtax'        =>$data['acct_wtax'],
                        'exp_code'         =>$data['exp_code'],
                        'main_type'        =>$data['main_type'],
                        'edited_n'         =>$userid,
                        'edited_d'         =>"NOW()"
                        
                      );
        $this->db->update("miscaf",$array,array("id"=>$data['id']));
        return true;
        
    }
    
    public function saveChartAccount($data)
    {
        $userid = $this->session->userdata('sess_id');
        $array = array(
                        'acct_main'        =>$data['acct_main'],
                        'acct_code'        =>$data['acct_code'],
                        'acct_item'        =>$data['acct_item'],
                        'acct_cont'        =>$data['acct_cont'],
                        'acct_class'       =>$data['acct_class'],                    
                        'caf_main'         =>$data['caf_main'],
                        'caf_code'         =>$data['caf_code'],
                        'caf_item'         =>$data['caf_item'],
                        'caf_cont'         =>$data['caf_cont'],
                        'caf_class'        =>$data['caf_class'],
                        'acct_sub'         =>$data['acct_sub'],
                        'acct_title'       =>$data['acct_title'],
                        'acct_des'         =>$data['acct_des'],
                        'acct_code'        =>$data['acct_code'],
                        'fixed_type'       =>$data['fixed_type'],
                        'acct_type'        =>$data['acct_type'],
                        'prod_code'        =>$data['prod_code'],
                        'acct_ctax'        =>$data['acct_ctax'],
                        'type_code'        =>$data['type_code'],
                        'acct_wtax'        =>$data['acct_wtax'],
                        'exp_code'         =>$data['exp_code'],
                        'main_type'        =>$data['main_type'],
                        'user_n'           =>$userid,
                        'edited_n'        =>$userid,
                        'user_d'        =>"NOW()",
                        'edited_d'        =>"NOW()"
                        
                      );
        $this->db->insert("miscaf",$array);
        return true;
        
    }
    
    
    public function deleteChartAccount($id = null)
    {
        $array = array(
                    'is_deleted'=> '1'
                      );
        $this->db->update('miscaf',$array,array('id'=>$id));
        return true;
    }
    
    public function search($searchkey) {
        
        $stmt = "SELECT id,caf_code,acct_main,acct_class,acct_item,acct_cont,
                       acct_sub,acct_title,acct_des,acct_code,acct_type,acct_ctax,
                       acct_wtax,acct_fas,type_code,prod_code,exp_code,fixed_type,
                       main_type,caf_main,caf_class,caf_item,caf_cont,caf_sub
                FROM miscaf 
                
                WHERE ( id LIKE '".$searchkey."%'
                OR caf_code LIKE '".$searchkey."%'
                OR acct_main LIKE '".$searchkey."%'
                OR acct_class LIKE '".$searchkey."%'
                OR acct_item LIKE '".$searchkey."%'
                OR acct_cont LIKE '".$searchkey."%'
                OR acct_sub LIKE '".$searchkey."%'
                OR acct_title LIKE '".$searchkey."%'
                OR acct_des LIKE '".$searchkey."%'
                OR acct_code LIKE '".$searchkey."%'
                OR acct_type LIKE '".$searchkey."%'
                OR acct_ctax LIKE '".$searchkey."%'
                OR acct_wtax LIKE '".$searchkey."%'
                OR acct_fas LIKE '".$searchkey."%'
                OR type_code LIKE '".$searchkey."%'
                OR prod_code LIKE '".$searchkey."%'
                OR exp_code LIKE '".$searchkey."%'
                OR main_type LIKE '".$searchkey."%'
                OR fixed_type LIKE '".$searchkey."%'
                OR caf_main LIKE '".$searchkey."%'
                OR caf_class LIKE '".$searchkey."%'
                OR caf_item LIKE '".$searchkey."%'
                OR caf_cont LIKE '".$searchkey."%'
                OR caf_sub LIKE '".$searchkey."%'  )
                
                AND is_deleted = '0' 
                ORDER BY caf_code ASC ";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function checkacct($data)
    {
        
        $stmt = "SELECT COUNT(id) as  count_id FROM miscaf
        
                 WHERE (
                            acct_main LIKE '".$data['acct_main']."%'
                            
                        AND acct_class LIKE '".$data['acct_class']."%'
                        
                        AND acct_item LIKE '".$data['acct_item']."%'
                        
                        AND acct_cont LIKE '".$data['acct_cont']."%'
                        
                        AND acct_sub LIKE '".$data['acct_sub']."%'
                 
                        )
                 AND is_deleted = '0'          
                        ";
        
        $result = $this->db->query($stmt);
        
        return $result->row();
        
    }
    
   
}
?>
