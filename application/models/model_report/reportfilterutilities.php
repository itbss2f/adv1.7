<?php
    
    class ReportFilterUtilities extends CI_Model
    {
        function SaveFilterDetails($data)
        {
             $my_util_id        = $this->db->query("SELECT  MAX(id) FROM report_utilities")->row_array();
             $report_util_id    = $my_util_id['MAX(id)'];
             $table_fields      = $data['table_fields'];
             $connector         = $data['connector'];
             $par1              = $data['par1'];
             $operators         = $data['operators'];
             $view_field_name   = $data['view_field_name'];
             $par2              = $data['par2'];
             $table_name        = $data['table_name'];
             $table_field_name  = $data['table_field_name'];
             $filter_type       = $data['filter_type'];
             $id                = $data['report_id'];
             $element_name      = $data['element_name'];
           
           
            
           foreach($table_fields as $key => $value)                                                                                                                          
           {
               
              
              $kuery = "INSERT INTO report_filter_utilities 
                        SET  report_id      = '".$id."' ,
                        report_utility_id   = '".$report_util_id."',
                        filter_field_name   = '".$table_fields[$key]."',
                        operator            = '".$operators[$key]."',
                        and_or_operator     = '".$connector[$key]."',
                        open_par            = '".$par1[$key]."',
                        close_par           = '".$par2[$key]."',
                        view_field_name     = '".$view_field_name[$key]."',
                        filter_type         = '".$filter_type[$key]."',
                        select_table        = '".$table_name[$key]."',
                        select_field_table  = '".$table_field_name[$key]."',
                        element_name        = '".$element_name[$key]."'
                        ";
                        
              $this->db->query($kuery);
                        
           } 
           return TRUE; 
        }
        
        
        function fetchfilters($id)
        {
            $kuery = "SELECT a.filter_type,
                             a.element_name,
                             a.select_table,
                             a.select_field_table,
                             b.report_name,
                             a.report_id,
                             a.filter_field_name,
                             a.and_or_operator,
                             a.open_par,
                             a.close_par,
                             a.view_field_name 
                      FROM report_filter_utilities AS a
                      INNER JOIN reports AS b ON b.id = a.report_id
                      INNER JOIN report_utilities c ON c.id = a.report_utility_id
                      WHERE a.report_utility_id = '".$id."'";
             $result = $this->db->query($kuery);
             return $result->result_array();         
        }
        
        function GetSelectFields($data)
        {
              $kuery = "SELECT ".$data['field_name']."
                        FROM ".$data['table_name']."
                         ";
                         
             $result =   $this->db->query($kuery);
             return $result->result_array();              
        }
        
    }