<?php

    class Salesbooks extends CI_Model
    {

        public function getSalesbookInvoiceList($datefrom, $dateto, $reporttype) {

            if ($reporttype == 1) {
            $stmt = "SELECT a.ao_sinum, DATE(a.ao_sidate) AS invdate, SUM(a.ao_grossamt) AS grossamt, SUM(a.ao_vatsales + a.ao_vatexempt + a.ao_vatzero) AS netvatsale, SUM(a.ao_agycommamt) AS agencycommamt, a.ao_billing_remarks,
                           b.ao_payee, IFNULL(c.cmf_name, '') AS agencyname, b.ao_ref, d.adtype_name, SUM(a.ao_totalsize) AS totalsize, e.empprofile_code AS aecode
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misadtype AS d ON d.id = b.ao_adtype
                    LEFT OUTER JOIN misempprofile AS e ON e.user_id = b.ao_aef
                    WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto' AND a.ao_sinum <> 1 AND a.ao_sinum <> 0  AND b.status NOT IN ('F', 'C') AND a.status NOT IN ('F', 'C')
                    AND b.ao_paytype != 6
                    GROUP BY a.ao_sinum, adtype_name
                    ORDER BY a.ao_sinum";

                    $result = $this->db->query($stmt)->result_array();
            } else if ($reporttype == 2) {
            $stmt = "SELECT SUM(a.ao_grossamt) AS grossamt, SUM(a.ao_vatsales + a.ao_vatexempt + a.ao_vatzero) AS netvatsale, SUM(a.ao_agycommamt) AS agencycommamt,
                           d.adtype_name, SUM(a.ao_totalsize) AS totalsize,
                          SUM(IF (b.ao_amf = 0, a.ao_grossamt, 0)) AS direcamt,
                          SUM(IF (b.ao_amf != 0, a.ao_grossamt, 0)) AS agencyamt,
                          SUM(a.ao_grossamt) AS totalamount
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misadtype AS d ON d.id = b.ao_adtype
                    WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto' AND a.ao_sinum <> 1 AND a.ao_sinum <> 0 AND b.status NOT IN ('F', 'C') AND a.status NOT IN ('F', 'C')
                    AND b.ao_paytype != 6
                    GROUP BY adtype_name
                    ORDER BY adtype_name";
                    #echo "<pre>"; echo $stmt; exit;
                    $result = $this->db->query($stmt)->result_array();
            } else if ($reporttype == 3) {
            $stmt = "SELECT a.ao_sinum, DATE(a.ao_sidate) AS invdate,
                           SUM(a.ao_totalsize) AS totalsize,
                           SUM(a.ao_cst) AS totalcost,
                           SUM(a.ao_computedamt) AS computedcost,
                           IF (SUM(a.ao_computedamt) <> SUM(a.ao_grossamt), SUM(a.ao_grossamt), (SUM(a.ao_grossamt) + SUM(a.ao_discamt))) AS beforedisamt,
                           IF (SUM(a.ao_computedamt) <> SUM(a.ao_grossamt), 0, SUM(a.ao_discamt)) AS discamt,
                           SUM(a.ao_grossamt) AS grossamt, SUM(a.ao_agycommamt) AS agencycommamt,
                           SUM(IFNULL(a.ao_vatsales, 0) + IFNULL(a.ao_vatexempt, 0) + IFNULL(a.ao_vatzero, 0)) AS netvatsale,
                           SUM(IFNULL(a.ao_vatamt, 0)) AS vatamt,
                           SUM(a.ao_amt) AS amountdue,
                           b.ao_payee, IFNULL(c.cmf_name, '') AS agencyname, b.ao_ref, d.adtype_name, SUM(a.ao_totalsize) AS totalsize, e.empprofile_code AS aecode,
                           CONCAT(IFNULL(b.ao_add1,''),' ',IFNULL(b.ao_add2,''),' ',IFNULL(b.ao_add3,'')) AS advertiseraddress, b.ao_tin AS clienttin,
                           CONCAT(IFNULL(c.cmf_add1,''),' ',IFNULL(c.cmf_add2,''),' ',IFNULL(c.cmf_add3,'')) AS agencyaddress, c.cmf_tin AS agencytin
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misadtype AS d ON d.id = b.ao_adtype
                    LEFT OUTER JOIN misempprofile AS e ON e.user_id = b.ao_aef
                    WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto' AND a.ao_sinum <> 1 AND a.ao_sinum <> 0  AND b.status NOT IN ('F', 'C') AND a.status NOT IN ('F', 'C')
                    AND b.ao_paytype != 6
                    GROUP BY a.ao_sinum
                    ORDER BY a.ao_sinum";

                    $result = $this->db->query($stmt)->result_array();
            } else if ($reporttype == 4) {
            $stmt = "SELECT xall.adtype_name, SUM(beforedisamt) AS beforedisamt, SUM(discamt) AS discamt,
                           SUM(grossamt) AS grossamt, SUM(netvatsale) AS netvatsale, SUM(agencycommamt) AS agencycommamt,
                           SUM(totalsize) AS totalsize, SUM(direcamt) AS direcamt, SUM(agencyamt) AS agencyamt, SUM(totalamount) AS totalamount,
                           SUM(vatamt) AS vatamt, SUM(amountdue) AS amountdue
                    FROM (
                    SELECT  a.ao_sinum,
                            d.adtype_name,
                            IF (SUM(a.ao_computedamt) <> SUM(a.ao_grossamt), SUM(a.ao_grossamt), (SUM(a.ao_grossamt) + SUM(a.ao_discamt))) AS beforedisamt,
                            IF (SUM(a.ao_computedamt) <> SUM(a.ao_grossamt), 0, SUM(a.ao_discamt)) AS discamt,
                        SUM(a.ao_grossamt) AS grossamt, SUM(a.ao_vatsales + a.ao_vatexempt + a.ao_vatzero) AS netvatsale, SUM(a.ao_agycommamt) AS agencycommamt,
                        SUM(a.ao_totalsize) AS totalsize,
                        SUM(IF (b.ao_amf = 0, a.ao_grossamt, 0)) AS direcamt,
                        SUM(IF (b.ao_amf != 0, a.ao_grossamt, 0)) AS agencyamt,
                        SUM(a.ao_grossamt) AS totalamount,
                        SUM(IFNULL(a.ao_vatamt, 0)) AS vatamt,
                        SUM(a.ao_amt) AS amountdue
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misadtype AS d ON d.id = b.ao_adtype
                    WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto' AND a.ao_sinum <> 1 AND a.ao_sinum <> 0 AND b.status NOT IN ('F', 'C') AND a.status NOT IN ('F', 'C')
                    AND b.ao_paytype != 6
                    GROUP BY a.ao_num) AS xall
                    GROUP BY xall.adtype_name
                    ORDER BY xall.adtype_name";
                    #echo "<pre>"; echo $stmt; exit;
                    $result = $this->db->query($stmt)->result_array();
            }


            return $result;
        }

         public function sales_book_advertising($data)
        {

            $stmt = "SELECT b.ao_sinum,
                           DATE(b.ao_sidate) ao_sidate,
                           SUBSTR(c.cmf_name,1,15) AS client_name,
                           SUBSTR(d.cmf_name,1,15) AS agency_name,
                           a.ao_ref,
                           e.adtype_code,
                           f.empprofile_code,
                           b.ao_totalsize,
                           amount_c(b.ao_vatamt,b.ao_amt) AS total_amt,
                           agency_commission(a.ao_amf,b.ao_vatamt,b.ao_amt) AS agency_com,
                           net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) AS net_adv_sales,
                           SUBSTR((IF(e.adtype_code='S',a.ao_part_production,b.ao_billing_remarks)),1,15) AS remarks


                    FROM ao_m_tm AS a
                    INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                    LEFT OUTER JOIN miscmf AS d ON d.id = a.ao_amf
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                    LEFT OUTER JOIN misempprofile AS f ON f.user_id = a.ao_aef

                    WHERE (b.ao_sinum != '' OR b.ao_sinum IS NOT NULL AND b.ao_sinum != '0')
                    AND b.ao_paginated_status = '1'
                    AND (b.ao_sidate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";

            if(!empty($data['search_key']))
            {
                $stmt .= " AND (
                                b.ao_sinum LIKE '".$data['search_key']."%'
                           OR   DATE(b.ao_sidate) LIKE '".$data['search_key']."%'
                           OR   SUBSTR(c.cmf_name,1,15) LIKE '".$data['search_key']."%'
                           OR   SUBSTR(d.cmf_name,1,15) LIKE '".$data['search_key']."%'
                           OR   a.ao_ref LIKE '".$data['search_key']."%'
                           OR   e.adtype_code LIKE '".$data['search_key']."%'
                           OR   b.ao_totalsize LIKE '".$data['search_key']."%'
                           OR   f.empprofile_code LIKE '".$data['search_key']."%'
                           OR   amount_c(b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'
                           OR   agency_commission(a.ao_amf,b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'
                           OR   net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'
                           OR   SUBSTR((IF(e.adtype_code='S',a.ao_part_production,b.ao_billing_remarks)),1,15) LIKE '".$data['search_key']."%'

                         ) ";
            }

            $stmt .= " ORDER BY b.ao_sinum ASC ";

            $result = $this->db->query($stmt);

            return $result->result_array();

        }


        public function sales_book_adjustment($data)
        {

             $stmt = "SELECT  i.ao_sinum,
                               i.ao_sidate,
                               c.cmf_name AS client_name,
                               d.cmf_name AS agency_name,
                               f.ao_ref,
                               e.adtype_code,
                               g.empprofile_code,
                               f.ao_totalsize,
                               amount_c(f.ao_vatamt,f.ao_amt) AS total_amt,
                              agency_commission(f.ao_amf,f.ao_vatamt,f.ao_vatamt) AS agency_com,
                              net_adv_sales(f.ao_amf,f.ao_vatamt,f.ao_amt) AS net_adv_sales,
                              SUBSTR(a.or_comment,1,15)  as remarks

                        FROM or_m_tm AS a
                        INNER JOIN or_p_tm AS b ON b.or_num = a.or_num
                        LEFT OUTER JOIN miscmf AS c ON c.id = a.or_cmf
                        LEFT OUTER JOIN miscmf AS d ON d.id = a.or_amf
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.or_adtype
                        INNER JOIN ao_m_tm AS f ON f.ao_num = a.or_aonum
                        LEFT OUTER JOIN misempprofile AS g ON g.user_id = f.ao_aef
                        LEFT OUTER JOIN or_d_tm AS h ON h.or_num = a.or_num
                        INNER JOIN ao_p_tm AS i ON i.ao_num = f.ao_num

                        WHERE (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                        AND a.or_cardtype = 'C'
                        ";

               if(!empty($data['search_key']))
               {
                $stmt .= " AND (
                                i.ao_sinum LIKE '".$data['search_key']."%'
                           OR   DATE(i.ao_sidate) LIKE '".$data['search_key']."%'
                           OR   SUBSTR(c.cmf_name,1,15) LIKE '".$data['search_key']."%'
                           OR   SUBSTR(d.cmf_name,1,15) LIKE '".$data['search_key']."%'
                           OR   f.ao_ref LIKE '".$data['search_key']."%'
                           OR   e.adtype_code LIKE '".$data['search_key']."%'
                           OR   g.empprofile_code LIKE '".$data['search_key']."%'
                           OR   f.ao_totalsize LIKE '".$data['search_key']."%'
                           OR   amount_c(f.ao_vatamt,f.ao_amt) LIKE   '".$data['search_key']."%'
                           OR   agency_commission(f.ao_amf,f.ao_vatamt,f.ao_vatamt) LIKE   '".$data['search_key']."%'
                           OR   net_adv_sales(f.ao_amf,f.ao_vatamt,f.ao_amt   '".$data['search_key']."%'
                           OR   SUBSTR(a.or_comment,1,15) LIKE '".$data['search_key']."%'

                         ) ";
                      $stmt .= " ORDER BY b.ao_sinum ASC LIMIT 25 ";
                }
                else
                {

                    $stmt .= " ORDER BY b.ao_sinum ASC";

                }

             $result = $this->db->query($stmt);

             return $result->result_array();

        }


        public function sales_book_details($data)
        {

            $stmt = "SELECT b.ao_sinum,
                           DATE(b.ao_sidate) ao_sidate,
                           c.cmf_name AS client_name,
                           d.cmf_name AS agency_name,
                           a.ao_ref,
                           e.adtype_code,
                           f.empprofile_code,
                           b.ao_totalsize,
                           amount_c(b.ao_vatamt,b.ao_amt) AS total_amt,
                           agency_commission(a.ao_amf,b.ao_vatamt,b.ao_amt) AS agency_com,
                           net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) AS net_adv_sales,
                           (IF(e.adtype_code='S' OR e.adtype_code='M',a.ao_part_production,b.ao_billing_remarks)) AS remarks


                    FROM ao_m_tm AS a
                    INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                    LEFT OUTER JOIN miscmf AS d ON d.id = a.ao_amf
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                    LEFT OUTER JOIN misempprofile AS f ON f.user_id = a.ao_aef

                    WHERE (b.ao_sinum != '' OR b.ao_sinum IS NOT NULL AND b.ao_sinum != '0')
                    AND b.ao_paginated_status = '1'
                    AND (a.status =  'A' OR a.status = 'F' )
                    AND (b.ao_sidate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";


                if(!empty($data['search_key']))
                {
                    $stmt .= " AND (
                                    b.ao_sinum LIKE '".$data['search_key']."%'
                               OR   DATE(b.ao_sidate) LIKE '".$data['search_key']."%'
                               OR   SUBSTR(c.cmf_name,1,15) LIKE '".$data['search_key']."%'
                               OR   SUBSTR(d.cmf_name,1,15) LIKE '".$data['search_key']."%'
                               OR   a.ao_ref LIKE '".$data['search_key']."%'
                               OR   e.adtype_code LIKE '".$data['search_key']."%'
                               OR   b.ao_totalsize LIKE '".$data['search_key']."%'
                               OR   f.empprofile_code LIKE '".$data['search_key']."%'
                               OR   amount_c(b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'
                               OR   agency_commission(a.ao_amf,b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'
                               OR   net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'
                               OR   (IF(e.adtype_code='S' OR e.adtype_code='M',a.ao_part_production,b.ao_billing_remarks)) LIKE '".$data['search_key']."%'

                             ) ";

                     $stmt .= " ORDER BY b.ao_sinum ASC LIMIT 25 ";
                }
                else
                {

                    $stmt .= " ORDER BY b.ao_sinum ASC";

                }



            $result = $this->db->query($stmt);

            return $result->result_array();

        }


        public function sales_book_vat_tin_address($data)
        {

           $stmt = "SELECT b.ao_sinum,
                   DATE(b.ao_sidate) ao_sidate,
                   SUBSTR(c.cmf_name,1,15) AS client_name,
                   SUBSTR(d.cmf_name,1,15) AS agency_name,
                   a.ao_ref,
                   e.adtype_code,
                   f.empprofile_code,
                   b.ao_totalsize,
                   salesbook_amount_b4_disc(b.ao_discpercent,b.ao_vatamt,b.ao_amt) AS amount_b4_disc,
                   salesbook_discount(b.ao_discpercent,b.ao_vatamt,b.ao_amt) AS discount_amount,
                   amount_c(b.ao_vatamt,b.ao_amt) AS total_amt,
                   agency_commission(a.ao_amf,b.ao_vatamt,b.ao_amt) AS agency_com,
                   net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) AS net_adv_sales,
                   salesbook_vat(a.ao_amf,b.ao_vatamt,b.ao_amt) AS vat,
                   (net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) + salesbook_vat(a.ao_amf,b.ao_vatamt,b.ao_amt) ) AS customer_vat,
                   IF(g.vat_rate != 0,CONCAT('TIN : ',c.cmf_tin),'') AS client_tin,
                   IF(h.vat_rate != 0,CONCAT('TIN : ',d.cmf_tin),'') AS agency_tin,
                   IF(CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3) != '' , CONCAT( 'Address : ',' ',CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3)  ),'') AS client_address,
                   IF(CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3) != '' , CONCAT('Address : ',' ',CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3)),'') AS agency_address


                FROM ao_m_tm AS a
                INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                LEFT OUTER JOIN miscmf AS d ON d.id = a.ao_amf
                LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                LEFT OUTER JOIN misempprofile AS f ON f.user_id = a.ao_aef
                LEFT OUTER JOIN misvat AS g ON g.id = c.cmf_vatcode
                LEFT OUTER JOIN misvat AS h ON h.id = d.cmf_vatcode

                WHERE (b.ao_sinum != '' OR b.ao_sinum IS NOT NULL AND b.ao_sinum != '0')
                AND b.ao_paginated_status = '1'
                AND (a.status =  'A' OR a.status = 'F' )
                AND (b.ao_sidate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";

            if(!empty($data['search_key']))
            {
                $stmt .= " AND (
                                b.ao_sinum LIKE '".$data['search_key']."%'

                           OR   DATE(b.ao_sidate) LIKE '".$data['search_key']."%'

                           OR   SUBSTR(c.cmf_name,1,15) LIKE '".$data['search_key']."%'

                           OR   SUBSTR(d.cmf_name,1,15) LIKE '".$data['search_key']."%'

                           OR   a.ao_ref LIKE '".$data['search_key']."%'

                           OR   e.adtype_code LIKE '".$data['search_key']."%'

                           OR   b.ao_totalsize LIKE '".$data['search_key']."%'

                           OR   f.empprofile_code LIKE '".$data['search_key']."%'

                           OR   salesbook_amount_b4_disc(b.ao_discpercent,b.ao_vatamt,b.ao_amt) LIKE '".$data['search_key']."%'

                           OR   salesbook_discount(b.ao_discpercent,b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   amount_c(b.ao_vatamt,b.ao_amt) LIKE '".$data['search_key']."%'

                           OR   agency_commission(a.ao_amf,b.ao_vatamt,b.ao_amt) LIKE  '".$data['search_key']."%'

                           OR   net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   salesbook_vat(a.ao_amf,b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   (net_adv_sales(f.ao_amf,b.ao_vatamt,b.ao_amt) + salesbook_vat(f.ao_amf,b.ao_vatamt,b.ao_amt) ) LIKE   '".$data['search_key']."%'

                           OR   IF(g.vat_rate != 0,CONCAT('TIN : ',c.cmf_tin),'') LIKE   '".$data['search_key']."%'

                           OR   IF(h.vat_rate != 0,CONCAT('TIN : ',d.cmf_tin),'') LIKE   '".$data['search_key']."%'

                           OR   (IF(CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3) != '' , CONCAT( 'Address : ',' ',CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3)),'')) LIKE   '".$data['search_key']."%'

                           OR   (IF(CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3) != '' , CONCAT('Address : ',' ',CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3)),'')) LIKE   '".$data['search_key']."%'


                         ) ";
                  $stmt .= " ORDER BY b.ao_sinum ASC LIMIT 25 ";
                }
                else
                {

                    $stmt .= " ORDER BY b.ao_sinum ASC";

                }

           $result = $this->db->query($stmt);

           return $result->result_array();

        }


        public function sales_book_adjustment_vat_tin_address($data)
        {

            $stmt = "SELECT i.ao_sinum,
                           i.ao_sidate,
                           c.cmf_name AS client_name,
                           d.cmf_name AS agency_name,
                           f.ao_ref,
                           e.adtype_code,
                           g.empprofile_code,
                           f.ao_totalsize,
                           salesbook_amount_b4_disc(i.ao_discpercent,i.ao_vatamt,i.ao_amt) AS amount_b4_disc,
                           salesbook_discount(i.ao_discpercent,i.ao_vatamt,i.ao_amt) AS discount,
                           amount_c(i.ao_vatamt,i.ao_amt) AS total_amt,
                           agency_commission(f.ao_amf,i.ao_vatamt,i.ao_vatamt) AS agency_com,
                           net_adv_sales(f.ao_amf,i.ao_vatamt,i.ao_amt) AS net_adv_sales,
                           salesbook_vat(f.ao_amf,i.ao_vatamt,i.ao_amt) AS vat,
                           (net_adv_sales(f.ao_amf,i.ao_vatamt,i.ao_amt) + salesbook_vat(f.ao_amf,i.ao_vatamt,i.ao_amt) ) AS customer_vat,
                           IF(j.vat_rate != 0,CONCAT('TIN : ',c.cmf_tin),'') AS client_tin,
                           IF(k.vat_rate != 0,CONCAT('TIN : ',d.cmf_tin),'') AS agency_tin,
                           CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3) AS client_address,
                           CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3) AS agency_address


                    FROM or_m_tm AS a
                    INNER JOIN or_p_tm AS b ON b.or_num = a.or_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.or_cmf
                    LEFT OUTER JOIN miscmf AS d ON d.id = a.or_amf
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.or_adtype
                    INNER JOIN ao_m_tm AS f ON f.ao_num = a.or_aonum
                    LEFT OUTER JOIN misempprofile AS g ON g.user_id = f.ao_aef
                    LEFT OUTER JOIN or_d_tm AS h ON h.or_num = a.or_num
                    INNER JOIN ao_p_tm AS i ON i.ao_num = f.ao_num
                    LEFT OUTER JOIN misvat AS j ON j.id = c.cmf_vatcode
                    LEFT OUTER JOIN misvat AS k ON k.id = d.cmf_vatcode


                    WHERE (a.or_date BETWEEN DATE('".$data['from_date']."') AND DATE('".$data['to_date']."'))
                    AND a.or_cardtype = 'C' ";

           if(!empty($data['search_key']))
            {
                $stmt .= " AND (
                                i.ao_sinum LIKE '".$data['search_key']."%'

                           OR   DATE(i.ao_sidate) LIKE '".$data['search_key']."%'

                           OR   SUBSTR(c.cmf_name,1,15) LIKE '".$data['search_key']."%'

                           OR   SUBSTR(d.cmf_name,1,15) LIKE '".$data['search_key']."%'

                           OR   f.ao_ref LIKE '".$data['search_key']."%'

                           OR   e.adtype_code LIKE '".$data['search_key']."%'

                           OR   f.ao_totalsize LIKE '".$data['search_key']."%'

                           OR   g.empprofile_code LIKE '".$data['search_key']."%'

                           OR   salesbook_amount_b4_disc(i.ao_discpercent,i.ao_vatamt,i.ao_amt) LIKE '".$data['search_key']."%'

                           OR   salesbook_discount(i.ao_discpercent,i.ao_vatamt,i.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   amount_c(i.ao_vatamt,i.ao_amt) LIKE '".$data['search_key']."%'

                           OR   agency_commission(f.ao_amf,i.ao_vatamt,i.ao_amt) LIKE  '".$data['search_key']."%'

                           OR   net_adv_sales(f.ao_amf,i.ao_vatamt,i.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   salesbook_vat(f.ao_amf,i.ao_vatamt,i.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   (net_adv_sales(f.ao_amf,i.ao_vatamt,i.ao_amt) + salesbook_vat(f.ao_amf,i.ao_vatamt,i.ao_amt) ) LIKE   '".$data['search_key']."%'

                           OR   IF(j.vat_rate != 0,CONCAT('TIN : ',c.cmf_tin),'') LIKE   '".$data['search_key']."%'

                           OR   IF(k.vat_rate != 0,CONCAT('TIN : ',d.cmf_tin),'') LIKE   '".$data['search_key']."%'

                           OR   (IF(CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3) != '' , CONCAT( 'Address : ',' ',CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3)),'')) LIKE   '".$data['search_key']."%'

                           OR   (IF(CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3) != '' , CONCAT('Address : ',' ',CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3)),'')) LIKE   '".$data['search_key']."%'


                         ) ";
                  $stmt .= " ORDER BY b.ao_sinum ASC LIMIT 25 ";
                }
                else
                {

                    $stmt .= " ORDER BY b.ao_sinum ASC";

                }

            $result  = $this->db->query($stmt);

            return $result->result_array();

        }

        public function sales_book_detail_vat_tin_address($data)
        {

           $stmt = "SELECT b.ao_sinum,
                   DATE(b.ao_sidate) ao_sidate,
                   SUBSTR(c.cmf_name,1,15) AS client_name,
                   SUBSTR(d.cmf_name,1,15) AS agency_name,
                   a.ao_ref,
                   e.adtype_code,
                   f.empprofile_code,
                   b.ao_totalsize,
                   salesbook_amount_b4_disc(b.ao_discpercent,b.ao_vatamt,b.ao_amt) AS amount_b4_disc,
                   salesbook_discount(b.ao_discpercent,b.ao_vatamt,b.ao_amt) AS discount_amount,
                   amount_c(b.ao_vatamt,b.ao_amt) AS total_amt,
                   agency_commission(a.ao_amf,b.ao_vatamt,b.ao_amt) AS agency_com,
                   net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) AS net_adv_sales,
                   salesbook_vat(a.ao_amf,b.ao_vatamt,b.ao_amt) AS vat,
                   (net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) + salesbook_vat(a.ao_amf,b.ao_vatamt,b.ao_amt) ) AS customer_vat,
                   IF(g.vat_rate != 0,CONCAT('TIN : ',c.cmf_tin),'') AS client_tin,
                   IF(h.vat_rate != 0,CONCAT('TIN : ',d.cmf_tin),'') AS agency_tin,
                   IF(CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3) != '' , CONCAT( 'Address : ',' ',CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3)  ),'') AS client_address,
                   IF(CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3) != '' , CONCAT('Address : ',' ',CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3)),'') AS agency_address


                FROM ao_m_tm AS a
                INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                LEFT OUTER JOIN miscmf AS d ON d.id = a.ao_amf
                LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                LEFT OUTER JOIN misempprofile AS f ON f.user_id = a.ao_aef
                LEFT OUTER JOIN misvat AS g ON g.id = c.cmf_vatcode
                LEFT OUTER JOIN misvat AS h ON h.id = d.cmf_vatcode

                WHERE (b.ao_sinum != '' OR b.ao_sinum IS NOT NULL AND b.ao_sinum != '0')
                AND b.ao_paginated_status = '1'
                AND (a.status =  'A' OR a.status = 'F' )
                AND (b.ao_sidate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";

            if(!empty($data['search_key']))
            {
                $stmt .= " AND (
                                b.ao_sinum LIKE '".$data['search_key']."%'

                           OR   DATE(b.ao_sidate) LIKE '".$data['search_key']."%'

                           OR   SUBSTR(c.cmf_name,1,15) LIKE '".$data['search_key']."%'

                           OR   SUBSTR(d.cmf_name,1,15) LIKE '".$data['search_key']."%'

                           OR   a.ao_ref LIKE '".$data['search_key']."%'

                           OR   e.adtype_code LIKE '".$data['search_key']."%'

                           OR   b.ao_totalsize LIKE '".$data['search_key']."%'

                           OR   f.empprofile_code LIKE '".$data['search_key']."%'

                           OR   salesbook_amount_b4_disc(b.ao_discpercent,b.ao_vatamt,b.ao_amt) LIKE '".$data['search_key']."%'

                           OR   salesbook_discount(b.ao_discpercent,b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   amount_c(b.ao_vatamt,b.ao_amt) LIKE '".$data['search_key']."%'

                           OR   agency_commission(a.ao_amf,b.ao_vatamt,b.ao_amt) LIKE  '".$data['search_key']."%'

                           OR   net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   salesbook_vat(a.ao_amf,b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   (net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) + salesbook_vat(a.ao_amf,b.ao_vatamt,b.ao_amt) ) LIKE   '".$data['search_key']."%'

                           OR   IF(g.vat_rate != 0,CONCAT('TIN : ',c.cmf_tin),'') LIKE   '".$data['search_key']."%'

                           OR   IF(h.vat_rate != 0,CONCAT('TIN : ',d.cmf_tin),'') LIKE   '".$data['search_key']."%'

                           OR   (IF(CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3) != '' , CONCAT( 'Address : ',' ',CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3)),'')) LIKE   '".$data['search_key']."%'

                           OR   (IF(CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3) != '' , CONCAT('Address : ',' ',CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3)),'')) LIKE   '".$data['search_key']."%'


                         ) ";

                   $stmt .= " ORDER BY b.ao_sinum ASC LIMIT 25 ";
                }
                else
                {

                    $stmt .= " ORDER BY b.ao_sinum ASC";

                }

           $result = $this->db->query($stmt);

           return $result->result_array();

        }


        public function sales_book_vat($data)
        {

            $stmt = "SELECT b.ao_sinum,
                           DATE(b.ao_sidate) ao_sidate,
                           SUBSTR(c.cmf_name,1,15) AS client_name,
                           SUBSTR(d.cmf_name,1,15) AS agency_name,
                           a.ao_ref,
                           e.adtype_code,
                           f.empprofile_code,
                           b.ao_totalsize,
                           amount_c(b.ao_vatamt,b.ao_amt) AS total_amt,
                           agency_commission(a.ao_amf,b.ao_vatamt,b.ao_amt) AS agency_com,
                           net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) AS net_adv_sales,
                           salesbook_vat(a.ao_amf,b.ao_vatamt,b.ao_amt) AS vat,
                           (net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) + salesbook_vat(a.ao_amf,b.ao_vatamt,b.ao_amt) ) AS customer_vat,
                           (IF(e.adtype_code='S' OR e.adtype_code='M',a.ao_part_production,b.ao_billing_remarks)) AS remarks


                    FROM ao_m_tm AS a
                    INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                    LEFT OUTER JOIN miscmf AS d ON d.id = a.ao_amf
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                    LEFT OUTER JOIN misempprofile AS f ON f.user_id = a.ao_aef
                    LEFT OUTER JOIN misvat AS g ON g.id = c.cmf_vatcode
                    LEFT OUTER JOIN misvat AS h ON h.id = d.cmf_vatcode

                    WHERE (b.ao_sinum != '' OR b.ao_sinum IS NOT NULL AND b.ao_sinum != '0')
                    AND b.ao_paginated_status = '1'
                    AND (a.status =  'A' OR a.status = 'F' ) ";


          if(!empty($data['search_key']))
                {
                    $stmt .= " AND (
                                    b.ao_sinum LIKE '".$data['search_key']."%'
                               OR   DATE(b.ao_sidate) LIKE '".$data['search_key']."%'
                               OR   SUBSTR(c.cmf_name,1,15) LIKE '".$data['search_key']."%'
                               OR   SUBSTR(d.cmf_name,1,15) LIKE '".$data['search_key']."%'
                               OR   a.ao_ref LIKE '".$data['search_key']."%'
                               OR   e.adtype_code LIKE '".$data['search_key']."%'
                               OR   b.ao_totalsize LIKE '".$data['search_key']."%'
                               OR   f.empprofile_code LIKE '".$data['search_key']."%'
                               OR   amount_c(b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'
                               OR   agency_commission(a.ao_amf,b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'
                               OR   net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) LIKE   '".$data['search_key']."%'
                               OR   (IF(e.adtype_code='S' OR e.adtype_code='M',a.ao_part_production,b.ao_billing_remarks)) LIKE '".$data['search_key']."%'

                             ) ";


                $stmt .= " ORDER BY b.ao_sinum ASC LIMIT 25 ";

                }
                else
                {

                    $stmt .= " ORDER BY b.ao_sinum ASC";

                }

            $result = $this->db->query($stmt);

            return $result->result_array();

        }


        public function sales_book_detail_adjustment_vat($data)
        {

            $stmt = " SELECT
                           i.ao_sinum,
                           i.ao_sidate,
                           c.cmf_name AS client_name,
                           d.cmf_name AS agency_name,
                           f.ao_ref,
                           e.adtype_code,
                           g.empprofile_code,
                           f.ao_totalsize,
                           amount_c(f.ao_vatamt,f.ao_amt) AS total_amt,
                          agency_commission(f.ao_amf,f.ao_vatamt,f.ao_vatamt) AS agency_com,
                          salesbook_vat(f.ao_amf,i.ao_vatamt,i.ao_amt) AS vat,
                          net_adv_sales(f.ao_amf,f.ao_vatamt,f.ao_amt) AS net_adv_sales,
                          SUBSTR(a.or_comment,1,15)  AS remarks

                FROM or_m_tm AS a
                INNER JOIN or_p_tm AS b ON b.or_num = a.or_num
                LEFT OUTER JOIN miscmf AS c ON c.id = a.or_cmf
                LEFT OUTER JOIN miscmf AS d ON d.id = a.or_amf
                LEFT OUTER JOIN misadtype AS e ON e.id = a.or_adtype
                INNER JOIN ao_m_tm AS f ON f.ao_num = a.or_aonum
                LEFT OUTER JOIN misempprofile AS g ON g.user_id = f.ao_aef
                LEFT OUTER JOIN or_d_tm AS h ON h.or_num = a.or_num
                INNER JOIN ao_p_tm AS i ON i.ao_num = f.ao_num
                LEFT OUTER JOIN misvat AS j ON j.id = c.cmf_vatcode
                LEFT OUTER JOIN misvat AS k ON k.id = d.cmf_vatcode

                WHERE (a.or_date BETWEEN DATE('".$data['from_date']."') AND DATE('".$data['to_date']."'))
                AND a.or_cardtype = 'C' ";

           if(!empty($data['search_key']))
           {
                $stmt .= " AND (
                                i.ao_sinum LIKE '".$data['search_key']."%'

                           OR   DATE(i.ao_sidate) LIKE '".$data['search_key']."%'

                           OR   SUBSTR(c.cmf_name,1,15) LIKE '".$data['search_key']."%'

                           OR   SUBSTR(d.cmf_name,1,15) LIKE '".$data['search_key']."%'

                           OR   f.ao_ref LIKE '".$data['search_key']."%'

                           OR   e.adtype_code LIKE '".$data['search_key']."%'

                           OR   f.ao_totalsize LIKE '".$data['search_key']."%'

                           OR   g.empprofile_code LIKE '".$data['search_key']."%'

                           OR   salesbook_amount_b4_disc(i.ao_discpercent,i.ao_vatamt,i.ao_amt) LIKE '".$data['search_key']."%'

                           OR   salesbook_discount(i.ao_discpercent,i.ao_vatamt,i.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   amount_c(i.ao_vatamt,i.ao_amt) LIKE '".$data['search_key']."%'

                           OR   agency_commission(f.ao_amf,i.ao_vatamt,i.ao_amt) LIKE  '".$data['search_key']."%'

                           OR   net_adv_sales(f.ao_amf,i.ao_vatamt,i.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   salesbook_vat(f.ao_amf,i.ao_vatamt,i.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   (net_adv_sales(f.ao_amf,i.ao_vatamt,i.ao_amt) + salesbook_vat(f.ao_amf,i.ao_vatamt,i.ao_amt) ) LIKE   '".$data['search_key']."%'

                           OR   IF(j.vat_rate != 0,CONCAT('TIN : ',c.cmf_tin),'') LIKE   '".$data['search_key']."%'

                           OR   IF(k.vat_rate != 0,CONCAT('TIN : ',d.cmf_tin),'') LIKE   '".$data['search_key']."%'

                           OR   (IF(CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3) != '' , CONCAT( 'Address : ',' ',CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3)),'')) LIKE   '".$data['search_key']."%'

                           OR   (IF(CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3) != '' , CONCAT('Address : ',' ',CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3)),'')) LIKE   '".$data['search_key']."%'


                         ) ";

                   $stmt .= " ORDER BY b.ao_sinum ASC LIMIT 25 ";

              }

              else
                {

                    $stmt .= " ORDER BY b.ao_sinum ASC";

                }

            $result = $this->db->query($stmt);

            return $result->result_array();
        }


        public function sales_book_detail_vat($data)
        {
            $stmt = "SELECT            i.ao_sinum,
                               i.ao_sidate,
                               c.cmf_name AS client_name,
                               d.cmf_name AS agency_name,
                               f.ao_ref,
                               e.adtype_code,
                               g.empprofile_code,
                               f.ao_totalsize,
                               amount_c(f.ao_vatamt,f.ao_amt) AS total_amt,
                              agency_commission(f.ao_amf,f.ao_vatamt,f.ao_vatamt) AS agency_com,
                              salesbook_vat(f.ao_amf,i.ao_vatamt,i.ao_amt) AS vat,
                              net_adv_sales(f.ao_amf,f.ao_vatamt,f.ao_amt) AS net_adv_sales,
                              SUBSTR(a.or_comment,1,15)  AS remarks

                    FROM or_m_tm AS a
                    INNER JOIN or_p_tm AS b ON b.or_num = a.or_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.or_cmf
                    LEFT OUTER JOIN miscmf AS d ON d.id = a.or_amf
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.or_adtype
                    INNER JOIN ao_m_tm AS f ON f.ao_num = a.or_aonum
                    LEFT OUTER JOIN misempprofile AS g ON g.user_id = f.ao_aef
                    LEFT OUTER JOIN or_d_tm AS h ON h.or_num = a.or_num
                    INNER JOIN ao_p_tm AS i ON i.ao_num = f.ao_num

                    WHERE  (a.or_date BETWEEN DATE('".$data['from_date']."') AND DATE('".$data['to_date']."'))

                    AND (i.ao_sinum != '' OR i.ao_sinum IS NOT NULL AND i.ao_sinum != '0')
                    AND i.ao_paginated_status = '1'
                    AND (f.status =  'A' OR f.status = 'F' ) ";

             if(!empty($data['search_key']))
           {
                $stmt .= " AND (
                                i.ao_sinum LIKE '".$data['search_key']."%'

                           OR   DATE(i.ao_sidate) LIKE '".$data['search_key']."%'

                           OR   SUBSTR(c.cmf_name,1,15) LIKE '".$data['search_key']."%'

                           OR   SUBSTR(d.cmf_name,1,15) LIKE '".$data['search_key']."%'

                           OR   f.ao_ref LIKE '".$data['search_key']."%'

                           OR   e.adtype_code LIKE '".$data['search_key']."%'

                           OR   f.ao_totalsize LIKE '".$data['search_key']."%'

                           OR   g.empprofile_code LIKE '".$data['search_key']."%'

                           OR   salesbook_amount_b4_disc(i.ao_discpercent,i.ao_vatamt,i.ao_amt) LIKE '".$data['search_key']."%'

                           OR   salesbook_discount(i.ao_discpercent,i.ao_vatamt,i.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   amount_c(i.ao_vatamt,i.ao_amt) LIKE '".$data['search_key']."%'

                           OR   agency_commission(f.ao_amf,i.ao_vatamt,i.ao_amt) LIKE  '".$data['search_key']."%'

                           OR   net_adv_sales(f.ao_amf,i.ao_vatamt,i.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   salesbook_vat(f.ao_amf,i.ao_vatamt,i.ao_amt) LIKE   '".$data['search_key']."%'

                           OR   (net_adv_sales(f.ao_amf,i.ao_vatamt,i.ao_amt) + salesbook_vat(f.ao_amf,i.ao_vatamt,i.ao_amt) ) LIKE   '".$data['search_key']."%'

                           OR   IF(j.vat_rate != 0,CONCAT('TIN : ',c.cmf_tin),'') LIKE   '".$data['search_key']."%'

                           OR   IF(k.vat_rate != 0,CONCAT('TIN : ',d.cmf_tin),'') LIKE   '".$data['search_key']."%'

                           OR   (IF(CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3) != '' , CONCAT( 'Address : ',' ',CONCAT(c.cmf_add1,' ',c.cmf_add2,' ',c.cmf_add3)),'')) LIKE   '".$data['search_key']."%'

                           OR   (IF(CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3) != '' , CONCAT('Address : ',' ',CONCAT(d.cmf_add1,' ',d.cmf_add2,' ',d.cmf_add3)),'')) LIKE   '".$data['search_key']."%'


                         ) ";

                $stmt .= " ORDER BY b.ao_sinum ASC LIMIT 25 ";

                }
                else
                {

                    $stmt .= " ORDER BY b.ao_sinum ASC";

                }


            $result = $this->db->query($stmt);

            return $result->result_array();
        }

    }
