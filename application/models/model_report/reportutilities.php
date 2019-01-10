<?php
    class ReportUtilities extends CI_Model
    {
        function SaveReportUtilities($data)
        {
            $check_values       = $data['check_values'];
            $field_name         = $data['field_name'];        
            $group_by_name      = $data['group_by_name'];
            $total_field        = $data['total_field'];
            $is_with_subtotal   = $data['is_with_subtotal'];
                              
            $sort="  ";
            $arrangement = "";
            $ctr         = 0;
            $group_by    = "";
            $tot_field  = "";
            $comma = "";
            $colon = "";           
                        foreach($field_name as $key => $values)
                        {
                                if($ctr !=0 ) {$comma =",";}
                                $sort .= " ".$comma." ".$values." ".$check_values[$key];
                                $arrangement .= " ".$comma." ".$values;
                                $ctr++;
                        }
                        $ctr = 0;
                        foreach($group_by_name as $key => $values)
                        {
                                if($ctr !=0 ) {$colon =",";}
                                $group_by .= " ".$colon." ".$values." ".$check_values[$key]." , ";
                                $ctr++;
                        }
                        
                        foreach($total_field as $key => $values)
                        {         
                                if($ctr !=0 ) {$colon =",";}    
                                $tot_field .=   " ".$colon." ".$values;  
                        }
                        
                        
            $this->report_id         = $data['report_id'];            
            $this->report_name       = $data['my_report_name'];
            $this->order_by          = $sort;
            $this->group_by          = $group_by;
            $this->total_fields      = $tot_field;
            $this->is_with_subtotal  = $is_with_subtotal;
            $this->field_arrangement = $arrangement;
            $this->db->insert('report_utilities',$this);
            return true;           
        }
        
        function GetReports()
        {
            $kuery = "SELECT id,report_name,report_id FROM report_utilities ORDER BY report_name ASC";
            $result = $this->db->query($kuery);
            return $result->result_array();
        }
        
        
        function countAll()
        {
             $stmt = "SELECT count(id) as count_id FROM report_utilities";
             $result = $this->db->query($stmt);
             return $result->row();
        }
        
        function whereClass($id)
        {
             $kuery = "SELECT where_class FROM report_utilities where id= '".$id."'";
             $result =   $this->db->query($kuery);
             return $result->row_array();        
        }
        
        function FetchReport($id)
        {
            $kuery = " SELECT b.report_query,a.order_by,a.group_by,a.field_arrangement
                        FROM report_utilities AS a
                        INNER JOIN reports AS b ON a.report_id = b.id
                        WHERE a.id = '".$id."'
                     ";
             $result =   $this->db->query($kuery);
             return $result->row_array();        
        }
        
       
        function showTables()
        {
            $kuery = "SHOW TABLES";
            $result = $this->db->query($kuery);
            return $result->result_array();
        }
        
        function GetFields($data)
        {
            $kuery = "DESC ".$data['table_name']." ";
             $result = $this->db->query($kuery);
            return $result->result_array();
        }
        
        function CreateQuery($data,$id)
        {
              $new_query = "";
              $query = "SELECT report_query
                        FROM reports
                        WHERE id IN (SELECT report_id FROM report_utilities WHERE id = '$id');";
               $report_query = $this->db->query($query)->row_array();
                $filter_kuery = "SELECT and_or_operator,
                                   operator,
                                   filter_field_name,
                                   open_par,
                                   close_par,
                                   element_name
                            FROM report_filter_utilities
                            WHERE report_utility_id = $id";
                $filter = $this->db->query($filter_kuery)->result_array();
               $order_by = "SELECT  group_by,order_by
                            FROM report_utilities
                            WHERE id = $id "; 
            
               $order_by = $this->db->query($order_by)->row_array();      
               $new_query = $report_query['report_query']; 
                $having = "HAVING ";
                for($z=0;$z<count($filter);$z++)
                {      
                     if($z!= 0)
                     {
                          $having = "";
                     }                                                                                 
                     $new_query  .= $having." ".$filter[$z]['and_or_operator']." ".$filter[$z]['open_par']." ".trim($filter[$z]['filter_field_name'])." ".$filter[$z]['operator']." '".$data[trim($filter[$z]['element_name'])]."' ".$filter[$z]['close_par']." ";
                }
                $new_query .= " ORDER BY ".$order_by['group_by'];  
                $new_query .= $order_by['order_by'];
                $new_query .= " LIMIT 10 ";   
                $result = $this->db->query($new_query);  
                return $result->result_array();  

        }
        
        function getReportHeader($id)
        {
                $kuery = "SELECT
                          group_by, 
                          report_name,
                          total_fields,
                          is_with_subtotal,
                          field_arrangement
                          FROM report_utilities
                          WHERE id = $id ";
                
                $result = $this->db->query($kuery);
                return $result->row_array();
        }
        
        
        function GetQueryUtility($id)
        {
                          $utilities = "SELECT report_name,
                                   order_by,
                                   field_arrangement,
                                   group_by
                            FROM report_utilities
                            WHERE id = $id "; 
            
               $report_utilities = $this->db->query($utilities); 
        }
        
        function generateFields($data)
        {
             $result = mysql_query($data['query']);
             return $result;
        }
    }
    
    
    /*              $neworder =  str_replace(',','',$order_by['group_by']); 
                $new_order_by =  preg_split( "/ (ASC|DESC) /",$neworder  );
                var_dump(array_filter($new_order_by));  */  
