<?php
    
    class DisplaySupplements extends CI_Model
    {
        function kueryformodel($data)
        {
            $kuery = "SELECT 
                            CONCAT(f.firstname,' ',f.middlename,' ',f.lastname) AS employee,
                            b.ao_ref,d.cmf_name,b.ao_payee,DATE(a.ao_issuefrom) AS issue_date,a.ao_num,
                            CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                            a.ao_totalsize AS ccm,
                            a.ao_grossamt AS amount,
                            a.ao_sinum,
                            CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,20),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,20),' ')) AS remarks
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_cmf
                    LEFT OUTER JOIN miscmf AS d ON d.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS e ON e.user_id = b.ao_aef  
                    INNER JOIN users AS f ON f.id = e.user_id
                    WHERE (a.status = 'A' OR a.status = 'O' )
                    AND   a.ao_type = 'D'
                    AND (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."' )
                    AND  b.ao_aef IN (
                                        SELECT id
                                        FROM users
                                        WHERE ( CONCAT(firstname,' ',middlename,' ',lastname) BETWEEN '".$data['from_ae']."' AND '".$data['to_ae']."' ) 
                                        ORDER BY lastname ASC
                                      )
                     AND b.ao_amf IN(
                                SELECT id
                                FROM miscmf
                                WHERE (cmf_name BETWEEN '".$data['from_client']."' AND '".$data['to_client']."') ORDER BY cmf_name ASC 
                                )
                    ORDER BY a.ao_issuefrom ASC,employee ASC, a.id ASC  ";
                    
            return $kuery;
        }
        
        function generate($data)
        {
          $stmt = $this->kueryformodel($data);
          $result = $this->db->query($stmt);
          return $result->result_array();  
            
        }
    }