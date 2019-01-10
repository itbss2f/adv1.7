<?php
      
     class Reports extends CI_Model
     {
         function savereport($data)
         {   
                $this->report_name =  $data['query_name'];
                $this->report_query = $data['query'];
                $this->db->insert('reports',$this);
                return true;
         }
         
         function countAll()
         {
             $stmt = "SELECT count(id) as count_id FROM reports";
             $result = $this->db->query($stmt);
             return $result->row();
         }
         
         function getReports($search,$stat,$offset,$limit)
         {
             $kuery = "SELECT * FROM reports ORDER BY report_name ASC LIMIT 25 OFFSET $offset ";
             $result = $this->db->query($kuery);
             return $result->result_array();
         }
         
         function querylist()
         {
             $kuery = "SELECT * FROM reports ORDER BY report_name ASC ";
             $result = $this->db->query($kuery);
             return $result->result_array(); 
         }
         
         function GetThisReport($data)
         {
             $kuery = "SELECT * FROM reports WHERE id = '".$data['id']."'";
             $result = $this->db->query($kuery);
             return $result->row_array();
         }
         
         function DeleteReport($data)
         {
             $kuery = "DELETE FROM reports WHERE  id = '".$data['id']."'";
             $this->db->query($kuery);
             return TRUE;
         }
         
         function getQuery($data)
         {
             $kuery = "SELECT report_query FROM reports WHERE  id = '".$data['query_id']."'";
             $result = $this->db->query($kuery);
             return $result->row();
         }
         
         function UpdateReport($data)
         {
             $this->report_name =  $data['query_name'];
             $this->report_query = $data['query'];          
             $this->db->update('reports',$this,array('id'=>$data['id']));
             return TRUE;          
         }
         
         function search($searchkey)
         {
             $stmt = "SELECT *
                      FROM reports
                      WHERE (
                        id LIKE '".$searchkey."%'
                     OR report_name LIKE '".$searchkey."%' 
                     OR report_query LIKE '".$searchkey."%' 
                      ) ";
                      
             $result = $this->db->query($stmt);
             return $result->result_array();         
         } 
     }   
        