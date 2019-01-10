<?php

class ReconcileBillings extends CI_Model
{
        
        function generate($data)
        {
        
                $stmt = "
                        SELECT
                        a.id AS ao_p_id,
                        SUBSTR(b.ao_ref,1,10) AS PONumber,
                        a.ao_sidate AS invoice_date,
                        i.book_name,
                        a.ao_sinum AS AINumber,
                        SUBSTR(a.ao_part_billing,1,10) AS product_title,
                        m.adtype_code,    
                        d.empprofile_code AS AE,
                        SUBSTR(b.ao_payee,1,10) AS advertiser,
                        SUBSTR(c.cmf_name,1,10) AS agency,
                        CONCAT(a.ao_width,' x ',a.ao_length) AS size,    
                        CASE a.ao_surchargepercent
                        WHEN '0.00' THEN ''
                            ELSE a.ao_surchargepercent
                        END AS  prempercent,
                        CASE a.ao_discpercent
                            WHEN '0.00' THEN ''
                            ELSE a.ao_discpercent
                        END AS descpercent,
                        CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN ''
                            ELSE a.ao_adtyperate_rate
                        END AS rate,
                        a.ao_grossamt AS gross_amount,
                        a.ao_totalsize AS ccm,
                        CASE a.status
                          WHEN 'A' THEN 'OK'
                          WHEN 'F' THEN 'CF'
                          WHEN 'O' THEN 'PO'
                          WHEN 'P' THEN 'PR'
                      END `status`,
                        a.ao_num,
                        CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,7),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,7),' ')) AS remarks,
                        l.paytype_name AS pay_type,
                        n.branch_code,
                        k.color_code,
                        j.class_code,    
                        a.ao_vatamt AS vat_amt,    
                        a.ao_paginated_date,    
                        SUBSTR(a.ao_billing_remarks,0,10) as billing_remarks
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.id = b.ao_aef
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.ao_num = a.ao_num
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id
                    LEFT OUTER JOIN mispaytype AS l ON l.id =  b.ao_paytype
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch
                    WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <=  '".$data['to_date']."') 
                    AND (a.status = 'A' OR a.status = 'O' )    
                    AND a.ao_type = 'D'            
                        
                             ";
                             
            if(!empty($data['search_key']))
           {
               
               $stmt .= " HAVING (
               
                                   ao_p_id LIKE '".$data['search_key']."%'
                               OR  PONumber LIKE '".$data['search_key']."%'
                               OR  invoice_date LIKE '".$data['search_key']."%'
                               OR  book_name LIKE '".$data['search_key']."%'
                               OR  AINumber LIKE '".$data['search_key']."%'
                               OR  product_title LIKE '".$data['search_key']."%'
                               OR  adtype_code LIKE '".$data['search_key']."%'
                               OR  AE LIKE '".$data['search_key']."%'
                               OR  advertiser LIKE '".$data['search_key']."%'
                               OR  agency LIKE '".$data['search_key']."%'
                               OR  size LIKE '".$data['search_key']."%'
                               OR  prempercent LIKE '".$data['search_key']."%'
                               OR  descpercent LIKE '".$data['search_key']."%'
                               OR  gross_amount LIKE '".$data['search_key']."%'
                               OR  ccm LIKE '".$data['search_key']."%'
                               OR  `status` LIKE '".$data['search_key']."%'
                               OR  ao_num LIKE '".$data['search_key']."%'
                               OR  remarks LIKE '".$data['search_key']."%'
                               OR  branch_code LIKE '".$data['search_key']."%'
                               OR  color_code LIKE '".$data['search_key']."%'
                               OR  class_code LIKE '".$data['search_key']."%'
                               OR  vat_amt LIKE '".$data['search_key']."%'
                               OR  ao_paginated_date LIKE '".$data['search_key']."%'
                               OR  billing_remarks LIKE '".$data['search_key']."%'
               
                                 )
                                 
                       LIMIT 25          
                                  ";
               
           }
                             
                             
                
            $result = $this->db->query($stmt); 
            
             return $result->result_array();    
        
 
        }
        
    /*    function countTotalRow($data)
        {
            $kuery = " SELECT  Count(a.id) as count_id
                                            FROM ao_p_tm AS a
                                            INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                                             WHERE (DATE(a.ao_issuefrom) >= '".$data['date_from']."' AND DATE(a.ao_issuefrom) <=  '".$data['date_to']."') 
                                            AND (a.status = 'A' OR a.status = 'O' )    
                        ";
            
            $result = $this->db->query($kuery);
            return $result->row_array();
    }        */
            
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
                echo $kuery;
            return TRUE;
    }    
            
    /*    
        function reconcilebilling($data)
        {
                $this->session->set_userdata(array('benchmark'=>'0'));    
                $kuery = $this->kueryformodel($data);
                $kuery .= " LIMIT 0,50 ";
                $result = $this->db->query($kuery);
                return $result->result_array();            
        }
        
        function forexport($data)
        {
                $ben = $this->session->userdata('benchmark');
                if($ben == 0 ) {$ben = 30;}
                $kuery = $this->kueryformodel($data);
                $kuery .= " LIMIT 0,".$ben." ";
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
                $kuery .= " LIMIT ".$benchmark.", ".$limit." ";
                $result = $this->db->query($kuery);
                return $result->result_array();    
        
        }        */

}


?>