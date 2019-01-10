<?php

        class Materialadvertisings extends CI_Model
        {
        
                function generate($data)
                {
                        $stmt = "
                                        SELECT
                                              a.id AS ao_p_id,
                                              a.ao_num,
                                              k.color_code,
                                              j.class_code,    
                                              SUBSTR(b.ao_payee,1,15) AS advertiser,
                                              SUBSTR(c.cmf_name,1,15) AS agency,
                                              b.ao_ref AS PONumber,
                                              CONCAT(a.ao_width,' x ',a.ao_length) AS size,    
                                              CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,15),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,15),' ')) AS remarks,
                                              SUBSTR(a.ao_part_billing,1,15) AS product_title,
                                              d.empprofile_code AS AE,
                                              m.adtype_code,
                                              CASE a.ao_adtyperate_rate 
                                                WHEN '0.00' THEN ''
                                                ELSE a.ao_adtyperate_rate
                                              END AS rate,
                                             CASE a.status
                                                      WHEN 'A' THEN 'OK'
                                                      WHEN 'F' THEN 'CF'
                                                      WHEN 'O' THEN 'PO'
                                                      WHEN 'P' THEN 'PR'
                                              END `status`,
                                              a.ao_sidate AS invoice_date,
                                              a.ao_grossamt AS gross_amount,
                                              a.ao_totalsize AS ccm,
                                              a.ao_sinum AS AINumber,
                                              a.ao_vatamt AS vat_amt,
                                              a.ao_paginated_date,
                                              l.paytype_name AS pay_type,
                                              n.branch_code    
                                            FROM ao_p_tm AS a
                                            INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                                            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                                            LEFT OUTER JOIN misempprofile AS d ON d.id = b.ao_aef
                                            LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod
                                            LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                                            LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class
                                            LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color
                                            LEFT OUTER JOIN mispaytype AS l ON l.id =  b.ao_paytype
                                            LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype
                                            LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch
                                            WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <=  '".$data['to_date']."') 
                                            AND (a.status = 'A' OR a.status = 'O' )
                                            AND a.ao_type = 'D'            
                                    ";
                                    
                       if(!empty($data['search_key']))
                       {
                             $stmt .= "
                                 AND(
                                      
                                      a.id LIKE '".$data['search_key']."%' 
                             
                                   OR a.ao_num LIKE '".$data['search_key']."%' 
                             
                                   OR k.color_code LIKE '".$data['search_key']."%' 
                             
                                   OR j.class_code LIKE '".$data['search_key']."%' 
                              
                                   OR SUBSTR(b.ao_payee,1,20) LIKE '".$data['search_key']."%' 
                              
                                   OR SUBSTR(c.cmf_name,1,20) LIKE '".$data['search_key']."%' 
                              
                                   OR CONCAT(a.ao_width,' x ',a.ao_length) LIKE '".$data['search_key']."%' 
                             
                                   OR CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,20),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,20),' ')) LIKE '".$data['search_key']."%' 
                              
                                   OR SUBSTR(a.ao_part_billing,1,20) LIKE '".$data['search_key']."%' 
                              
                                   OR d.empprofile_code LIKE '".$data['search_key']."%' 
                              
                                   OR m.adtype_code LIKE '".$data['search_key']."%' 
                                   
                       
                                  OR a.ao_sidate LIKE '".$data['search_key']."%'
                                  
                                  OR a.ao_grossamt LIKE '".$data['search_key']."%' 
                                      
                                  OR a.ao_totalsize LIKE '".$data['search_key']."%'  
                                     
                                  OR a.ao_sinum LIKE '".$data['search_key']."%' 
                                      
                                  OR a.ao_vatamt LIKE '".$data['search_key']."%'
                                       
                                  OR a.ao_paginated_date LIKE '".$data['search_key']."%'  
                                     
                                  OR l.paytype_name LIKE '".$data['search_key']."%'  
                                     
                                  OR n.branch_code LIKE '".$data['search_key']."%' 
 
                                     LIMIT 25    
                                )
                                
    

                                    
                                 ";
                       }             
                                    
                                    
                                  
                    
                       $result = $this->db->query($stmt);
                     
                       return $result->result_array();       
                
                }
                
                function countTotalRow($data)
                {
                        $kuery = " SELECT  Count(id) as count_id
                                        FROM ao_p_tm
                                        WHERE (DATE(ao_issuefrom) >= '".$data['date_from']."' AND DATE(ao_issuefrom) <=  '".$data['date_to']."') 
                                        AND (`status` = 'A' OR `status` = 'O' )
                                    ";
                        
                        $result = $this->db->query($kuery);
                        return $result->row_array();
                }
                 
                function saveinquiry($data)
                {
                    foreach($data['ae_val'] as $key => $value)
                    {
                        if(!empty($value))
                        {
                            $new_val = explode(" : ",$value);
                            $kuery = "UPDATE ao_p_tm AS a
                                      INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                                      set b.ao_aef ='".$new_val[1]."' where a.id = '".$new_val[0]."' ";
                            $this->db->query($kuery);
                        }


                    }
                    
                    
                    foreach($data['class_val'] as $key => $value)
                    {
                        if(!empty($value))
                        {
                            $new_val = explode(" : ",$value);
                            $kuery = "UPDATE ao_p_tm set ao_class ='".$new_val[1]."' where id = '".$new_val[0]."' ";
                            $this->db->query($kuery);
                        }


                    }
                    
                    foreach($data['adtype_val'] as $key => $value)
                    {
                        if(!empty($value))
                        {
                            $new_val = explode(" : ",$value);
                                $kuery = "UPDATE ao_p_tm AS a
                                          INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                                          set b.ao_adtype ='".$new_val[1]."' where a.id = '".$new_val[0]."' ";
                            $this->db->query($kuery);
                        }


                    }
                
                    return TRUE;
                }
            /*    
                function materialadvertising($data=null)
                {
                        $this->session->set_userdata(array('benchmark'=>'0'));    
                        $kuery = $this->kueryformodel($data);
                        $kuery .= " LIMIT  0 , 30";
                        $result = $this->db->query($kuery);
                        return $result->result_array();            
                }          */
                
/*                function forexport($data)
                {
                        $ben = $this->session->userdata('benchmark');
                        if($ben == 0 ) {$ben = 30;}
                        $kuery = $this->kueryformodel($data);
                        $kuery .= " LIMIT 0 , ".$ben." ";
                        $result = $this->db->query($kuery);
                        return $result->result_array();            
                }
                
                function viewmoredetails($data)
                {
                        $ben = $this->session->userdata('benchmark');
                        $benchmark =  intval($ben) + intval(30);    
                        $limit = $benchmark + 30;
                         $this->session->set_userdata(array("benchmark"=>$limit));
                        $kuery = $this->kueryformodel($data);
                        $benchmark  += 1;
                        $kuery .= " LIMIT ".$benchmark." , ".$limit." ";
                        $result = $this->db->query($kuery);
                        return $result->result_array();    
                
                } */
        
        }

?>