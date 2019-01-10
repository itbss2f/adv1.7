<?php

class Soareports extends CI_Model {

    public function test2() {

        $stmt_get = "SELECT a.*, IFNULL(pay.payamount, 0) AS payamount, (a.amountdue - IFNULL(pay.payamount, 0)) AS balance FROM soa_tmp_tbl AS a
                        LEFT OUTER JOIN (
                                SELECT SUM(paymentamount) AS payamount, id FROM soa_tmp_tbl WHERE hkey = '1a6hifJ8201408190908292' AND paymenttype NOT IN('A1', 'A2') GROUP BY id
                        ) AS pay ON pay.id = a.id
                        WHERE a.hkey = '1a6hifJ8201408190908292' ORDER BY a.datatype, a.paymenttype, a.agencyname, a.agencycode,  a.clientname, a.clientcode, a.invdate, a.invnum";
        $result = $this->db->query($stmt_get)->result_array();

        foreach ($result as $row) {
        $newresult[$row['agencycode'].' '.$row['agencyname']][$row['clientcode'].' '.$row['clientname']][$row['id']][$row['issuedate']][] = $row;
        }

        print_r2($newresult);
    }

    public function query_report($find, $type) {

        $reporttype = $find['reporttype'];
        $dateasof = $find['dateasof'];
        $agencyfrom = $find['agencyfrom'];
        $agencyto = $find['agencyto'];
        $ac_agency = $find['ac_agency'];
        $ac_client = $find['ac_client'];
        $c_clientfrom = $find['c_clientfrom'];
        $c_clientto = $find['c_clientto'];
        $ac_mgroup = $find['ac_mgroup'];
        $exdeal = $find['exdeal'];
        $wtax = $find['wtax'];
        $con1 = "";  $con2 = "";  $con3 = ""; $con4 = "";  $conexdeal = "";
        $data = array();

        if ($exdeal == 1) {
            $conexdeal = "AND aop.ao_exdealstatus = 1";
        }

        switch ($reporttype) {

            case 1:
                $con1 = "WHERE z.agencycode >= '$agencyfrom' AND z.agencycode <= '$agencyto'";
                $con2 = "AND cmf.cmf_code >= '$agencyfrom' AND cmf.cmf_code <= '$agencyto'";
                #$con1 = "WHERE z.agencyname >= TRIM((SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyfrom'))  AND z.agencyname <= TRIM((SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyto'))";
                #$con2 = "AND IFNULL(cmf.cmf_name, '') >= TRIM((SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyfrom'))  AND IFNULL(cmf.cmf_name, '') <= TRIM((SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyto'))";
                $data = $this->query_stmt($dateasof, $reporttype, $con1, $con2, $con3, $con4, $type, $conexdeal, $wtax);
            break;

            case 2:
                $con1 = "WHERE z.agencycode = '$ac_agency' AND z.ao_cmf = '$ac_client'";
                $con2 = "AND IFNULL(cmf.cmf_code, '') = '$ac_agency' AND aom.ao_cmf = '$ac_client'";
                $data = $this->query_stmt($dateasof, $reporttype, $con1, $con2, $con3, $con4, $type, $conexdeal, $wtax);
            break;

            case 3:
                $con1 = "WHERE z.ao_cmf >= '$c_clientfrom' AND z.ao_cmf <= '$c_clientto'";
                $con2 = "AND aom.ao_cmf >= '$c_clientfrom' AND aom.ao_cmf <= '$c_clientto'";
                $con3 = "AND dcm.dc_payee >= '$c_clientfrom' AND dcm.dc_payee <= '$c_clientto'";
                $con4 = "AND orm.or_cmf >= '$c_clientfrom' AND orm.or_cmf <= '$c_clientto'";
                #$con1 = "WHERE z.ao_payee >= TRIM((SELECT cmf_name FROM miscmf WHERE cmf_code = '$c_clientfrom'))  AND z.ao_payee <= TRIM((SELECT cmf_name FROM miscmf WHERE cmf_code = '$c_clientto'))";
                #$con2 = "AND aom.ao_payee >= TRIM((SELECT cmf_name FROM miscmf WHERE cmf_code = '$c_clientfrom'))  AND aom.ao_payee <= TRIM((SELECT cmf_name FROM miscmf WHERE cmf_code = '$c_clientto'))";
                $data = $this->query_stmt($dateasof, $reporttype, $con1, $con2, $con3, $con4, $type, $conexdeal, $wtax);
            break;

            case 4:
                $con1 = "INNER JOIN (
                    SELECT cmf.cmf_code
                    FROM miscmfgroupaccess AS a
                    INNER JOIN miscmf AS cmf ON cmf.id = a.cmf_code
                    WHERE a.cmfgroup_code = $ac_mgroup
                ) AS mgroup ON mgroup.cmf_code = z.ao_cmf";
                #$con1 = "WHERE z.ao_cmf >= '$ac_mgroup' AND z.ao_cmf <= '$ac_mgroup'";
                $data = $this->query_stmt($dateasof, $reporttype, $con1, $con2, $con3, $con4, $type, $conexdeal, $wtax);
            break;
        }

        return $data;

    }


    public function testdel() {
        $stmtpayment = "SELECT d.id, d.paymentno, d.paymentdate, SUM(d.paymentamount) AS totalpayed
                FROM soa_tmp_tbl AS d
                WHERE d.paymenttype IN ('OR', 'CM')  AND  hkey = 'Ga8JnfTf20150811100832242'
                GROUP BY d.id";

        $resultpayment = $this->db->query($stmtpayment)->result_array();
        $paymentval = 0; $id = 0;
        foreach ($resultpayment as $payment) {
            $id = $payment['id'];
            $stmtcon = "SELECT p.tid, p.id, p.invnum, p.invdate, p.issuedate, p.amountdue,
                               payed.id AS payedid, IFNULL(payed.totalpayed, 0) AS totalpayed,
                               IF (p.amountdue > IFNULL(payed.totalpayed, 0), 0, 1) AS paid
                        FROM soa_tmp_tbl AS p
                        LEFT OUTER JOIN (
                                 SELECT d.id, d.paymentno, d.paymentdate, SUM(d.paymentamount) AS totalpayed
                                 FROM soa_tmp_tbl AS d
                                 WHERE d.paymenttype IN ('OR', 'CM') AND d.id = $id  AND  hkey = 'Ga8JnfTf20150811100832242'
                                 GROUP BY d.id
                                 ) AS payed ON payed.id = p.id
                        WHERE p.paymenttype IN ('A1', 'A2') AND p.id = $id AND p.hkey = 'Ga8JnfTf20150811100832242'";

            $resultcon = $this->db->query($stmtcon)->row_array();
            $tid = 0;
            if ($resultcon['paid'] == 1) {
                // Delete
                 $tid = $resultcon['tid'];

                $stmtdeldata = "DELETE FROM soa_tmp_tbl WHERE tid = $tid";
                $this->db->query($stmtdeldata);
            }
        }
    }

    public function query_stmt($dateasof, $reporttype, $con1, $con2, $con3, $con4, $type, $conexdeal, $wtax) {

         /*$tblname = "soa_tmp_tbl".date('Ymdhms');
         $tmp_tbl = "
                    CREATE TABLE ".$tblname." (
                    tid INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    datatype VARCHAR(10),
                    id INT(11),
                    paymenttype VARCHAR(10),
                    agencycode VARCHAR(10),
                    agencyname VARCHAR(70),
                    clientcode VARCHAR(10),
                    clientname VARCHAR(70),
                    invnum VARCHAR(10),
                    invdate VARCHAR(10),
                    issuedate VARCHAR(10),
                    particular VARCHAR(150),
                    ponum VARCHAR(70),
                    amountdue DECIMAL(18, 2),
                    paymentno VARCHAR(50),
                    paymentdate VARCHAR(10),
                    paymentamount DECIMAL(18, 2)
                    );
                     ";
         $this->db->query($tmp_tbl);       exit;*/  # Not use

         $tblnamekey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,8)."".date('Ymdhms').$this->session->userdata('authsess')->sess_id;

         if ($wtax == 1) {
         $stmt = "
                SELECT z.* FROM (
                SELECT 'A' AS datatype, IFNULL(cmf.cmf_code, '') AS agencycode, IFNULL(cmf.cmf_name, '') AS agencyname,
                       aom.ao_cmf, aom.ao_payee,
                       aop.id, aop.ao_sinum, DATE(aop.ao_sidate) AS invdate, DATE(aop.ao_issuefrom) AS issuedate,
                       SUBSTR(aop.ao_billing_prodtitle, 1, 50) AS particulars, aom.ao_ref AS ponum, SUM(ord.or_assignwtaxamt) AS ao_amt,
                       'A1' AS paymenttype, '' AS paymentno, '' AS paymentdate, '' AS paymentamt
                FROM ao_p_tm AS aop
                INNER JOIN or_d_tm AS `ord` ON (ord.or_docitemid = aop.id AND ord.or_doctype = 'SI')
                INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                WHERE aop.ao_sinum != 0 AND aop.ao_sinum != 1 AND aop.ao_sidate IS NOT NULL AND DATE(aop.ao_sidate) <= '$dateasof' AND aop.ao_amt > 0.06
                $con2 $conexdeal
                AND ord.or_assignwtaxamt != 0
                GROUP BY aop.id
                UNION
                SELECT 'A', IFNULL(cmf.cmf_code, '') AS agencycode, IFNULL(cmf.cmf_name, '') AS agencyname,
                       aom.ao_cmf, aom.ao_payee,
                       dcd.dc_docitemid, '', '', DATE(aop.ao_issuefrom) AS issuedate, '', '', '',
                       'CM', dcd.dc_num, DATE(dcd.dc_date) AS dcdate, dcd.dc_assignamt
                FROM dc_d_tm AS `dcd`
                INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dcd.dc_num
                INNER JOIN ao_p_tm AS aop ON aop.id = dcd.dc_docitemid
                INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                WHERE dcd.dc_type = 'C' AND DATE(dcd.dc_date) <= '$dateasof' AND dcd.dc_doctype = 'SI'
                AND dcm.dc_subtype IN (11, 12, 15, 16)
                $con2 $conexdeal
                ) AS z
                $con1
                ORDER BY z.datatype, z.id, z.paymentdate, z.agencycode, z.agencyname, z.ao_cmf, z.ao_payee, z.invdate DESC, z.ao_sinum
                ";
         } else {
         $stmt = "
                SELECT z.* FROM (
                SELECT 'A' AS datatype, IFNULL(cmf.cmf_code, '') AS agencycode, IFNULL(cmf.cmf_name, '') AS agencyname,
                       aom.ao_cmf, aom.ao_payee,
                       aop.id, aop.ao_sinum, DATE(aop.ao_sidate) AS invdate, DATE(aop.ao_issuefrom) AS issuedate,
                       CONCAT(IF(prd.pr_num = NULL, '', '**'), SUBSTR(aop.ao_billing_prodtitle, 1, 50)) AS particulars, aom.ao_ref AS ponum, aop.ao_amt,
                       'A1' AS paymenttype, '' AS paymentno, '' AS paymentdate, '' AS paymentamt
                FROM ao_p_tm AS aop
                INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                LEFT OUTER JOIN pr_d_tm AS prd ON (prd.pr_docitemid = aop.id)
                WHERE aop.ao_sinum != 0 AND aop.ao_sinum != 1 AND aop.ao_sidate IS NOT NULL AND DATE(aop.ao_sidate) <= '$dateasof' AND aop.ao_amt > 0.06
                $con2 $conexdeal
                GROUP BY aop.id, prd.pr_docitemid
                UNION
                SELECT 'A', IFNULL(cmf.cmf_code, '') AS agencycode, IFNULL(cmf.cmf_name, '') AS agencyname,
                       aom.ao_cmf, aom.ao_payee,
                       ord.or_docitemid, '', '', DATE(aop.ao_issuefrom) AS issuedate, '', '', '',
                       'OR', ord.or_num, DATE(ord.or_date) AS ordate, ord.or_assignamt
                FROM or_d_tm AS `ord`
                INNER JOIN ao_p_tm AS aop ON aop.id = ord.or_docitemid
                INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                WHERE DATE(ord.or_date) <= '$dateasof' AND ord.or_doctype = 'SI'
                $con2 $conexdeal
                UNION
                SELECT 'A', IFNULL(cmf.cmf_code, '') AS agencycode, IFNULL(cmf.cmf_name, '') AS agencyname,
                       aom.ao_cmf, aom.ao_payee,
                       dcd.dc_docitemid, '', '', DATE(aop.ao_issuefrom) AS issuedate, '', '', '',
                       'CM', dcd.dc_num, DATE(dcd.dc_date) AS dcdate, dcd.dc_assignamt
                FROM dc_d_tm AS `dcd`
                INNER JOIN ao_p_tm AS aop ON aop.id = dcd.dc_docitemid
                INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                WHERE dcd.dc_type = 'C' AND DATE(dcd.dc_date) <= '$dateasof' AND dcd.dc_doctype = 'SI'
                $con2 $conexdeal
                UNION
                SELECT 'D' AS datatype, IF (dcm.dc_payeetype = 2, dcm.dc_payee, cmf.cmf_code) AS agencycode, IF (dcm.dc_payeetype = 2, dcm.dc_payeename, cmf.cmf_name) AS agencyname,
                       IF (dcm.dc_payeetype = 1, dcm.dc_payee, '') AS ao_cmf, IF (dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS ao_payee,
                       dcm.dc_num AS dcid, dcm.dc_num, DATE(dcm.dc_date) AS dcdate, DATE(dcm.dc_date) AS dcissuedate,
                       IF (dcm.dc_amf != 0 , cmf.cmf_name , '') AS particulars, '' AS ponum, dcm.dc_amt,
                       'A2' AS paymenttype, '' AS paymentno, '' AS paymentdate, '' AS paymentamt
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = dcm.dc_amf
                WHERE dcm.dc_type = 'D' AND DATE(dcm.dc_date) <= '$dateasof' AND dcm.dc_amt > 0.06
                $con3
                UNION
                SELECT 'B', IFNULL(cmf.cmf_code, '') AS agencycode, IFNULL(cmf.cmf_name, '') AS agencyname,
                       dcm.dc_payee, dcm.dc_payeename, ord.or_docitemid, '', '', DATE(dcm.dc_date) AS issuedate, '', '', '',
                       'OR', ord.or_num, DATE(ord.or_date) AS ordate, ord.or_assignamt
                FROM or_d_tm AS `ord`
                INNER JOIN or_m_tm AS orm ON orm.or_num = ord.or_num
                LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = ord.or_docitemid
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = dcm.dc_amf
                WHERE DATE(ord.or_date) <= '$dateasof' AND ord.or_doctype = 'DM'
                $con4
                UNION
                SELECT 'B', IFNULL(cmf.cmf_code, '') AS agencycode, IFNULL(cmf.cmf_name, '') AS agencyname,
                       dcm.dc_payee, dcm.dc_payeename, dcd.dc_docitemid, '', '', DATE(dcm.dc_date) AS issuedate, '', '', '',
                       'CM', dcd.dc_num, DATE(dcd.dc_date) AS dcdate, dcd.dc_assignamt
                FROM dc_d_tm AS `dcd`
                LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = dcd.dc_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = dcm.dc_amf
                WHERE dcd.dc_type = 'C' AND DATE(dcd.dc_date) <= '$dateasof' AND dcd.dc_doctype = 'DM') AS z
                $con1
                ORDER BY z.datatype, z.id, z.paymentdate, z.agencycode, z.agencyname, z.ao_cmf, z.ao_payee, z.invdate DESC, z.ao_sinum
                ";
         #      echo "<pre>"; echo $stmt; exit;
         }
         #echo "<pre>"; echo $stmt; exit;
         $result = $this->db->query($stmt)->result_array();

         #print_r2($result); exit;

         $newresult = array();
         //switch ($reporttype) {

             //case 1:

                  foreach ($result as $newrow) {
                      $tmp_data = array(
                                         'hkey' => $tblnamekey,
                                         'id' => $newrow['id'],
                                         'datatype' => $newrow['datatype'],
                                         'paymenttype' => $newrow['paymenttype'],
                                         'agencycode' => $newrow['agencycode'],
                                         'agencyname' => $newrow['agencyname'],
                                         'clientcode' => $newrow['ao_cmf'],
                                         'clientname' => $newrow['ao_payee'],
                                         'invnum' => $newrow['ao_sinum'],
                                         'invdate' => $newrow['invdate'],
                                         'issuedate' => $newrow['issuedate'],
                                         'particular' => $newrow['particulars'],
                                         'ponum' => $newrow['ponum'],
                                         'amountdue' => $newrow['ao_amt'],
                                         'paymentno' => $newrow['paymentno'],
                                         'paymentdate' => $newrow['paymentdate'],
                                         'paymentamount' => $newrow['paymentamt'],
                                         );
                                         $this->db->insert('soa_tmp_tbl', $tmp_data);
                  }
                  //$tmp_data[] = "";
                  foreach ($newresult as $data) {
                       foreach ($data as $adata) {
                           foreach ($adata as $newrow) {
                              $tmp_data = array(
                                                 'id' => $newrow['id'],
                                                 'datatype' => $newrow['datatype'],
                                                 'paymenttype' => $newrow['paymenttype'],
                                                 'agencycode' => $newrow['agencycode'],
                                                 'agencyname' => $newrow['agencyname'],
                                                 'clientcode' => $newrow['ao_cmf'],
                                                 'clientname' => $newrow['ao_payee'],
                                                 'invnum' => $newrow['ao_sinum'],
                                                 'invdate' => $newrow['invdate'],
                                                 'issuedate' => $newrow['issuedate'],
                                                 'particular' => $newrow['particulars'],
                                                 'ponum' => $newrow['ponum'],
                                                 'amountdue' => $newrow['ao_amt'],
                                                 'paymentno' => $newrow['paymentno'],
                                                 'paymentdate' => $newrow['paymentdate'],
                                                 'paymentamount' => $newrow['paymentamt'],
                                                 );

                                                 $this->db->insert('soa_tmp_tbl', $tmp_data);
                           }
                       }

                  }
                  #exit;
                  $stmtpayment = "SELECT d.id, d.paymentno, d.paymentdate, SUM(d.paymentamount) AS totalpayed
                            FROM soa_tmp_tbl AS d
                            WHERE d.paymenttype IN ('OR', 'CM')  AND  hkey = '$tblnamekey'
                            GROUP BY d.id";

                    $resultpayment = $this->db->query($stmtpayment)->result_array();
                    $paymentval = 0; $xxid = 0;
                    foreach ($resultpayment as $payment) {
                        $xxid = $payment['id'];
                        $stmtcon = "SELECT p.tid, p.id, p.invnum, p.invdate, p.issuedate, p.amountdue,
                                           payed.id AS payedid, IFNULL(payed.totalpayed, 0) AS totalpayed,
                                           IF (p.amountdue > IFNULL(payed.totalpayed, 0), 0, 1) AS paid
                                    FROM soa_tmp_tbl AS p
                                    LEFT OUTER JOIN (
                                             SELECT d.id, d.paymentno, d.paymentdate, SUM(d.paymentamount) AS totalpayed
                                             FROM soa_tmp_tbl AS d
                                             WHERE d.paymenttype IN ('OR', 'CM') AND d.id = $xxid  AND  hkey = '$tblnamekey'
                                             GROUP BY d.id
                                             ) AS payed ON payed.id = p.id
                                    WHERE p.paymenttype IN ('A1', 'A2') AND p.id = $xxid AND p.hkey = '$tblnamekey'";

                        $resultcon = $this->db->query($stmtcon)->row_array();
                        $tid = 0;
                        if (!empty($resultcon)){
                            if ($resultcon['paid'] == 1) {
                                // Delete
                                $tid = $resultcon['id'];

                                $stmtdeldata = "DELETE FROM soa_tmp_tbl WHERE id = $tid AND hkey = '$tblnamekey'";
                                $this->db->query($stmtdeldata);
                            }
                        }
                    }

                  /*if (!empty($tmp_data)) {
                  $this->db->insert_batch('soa_tmp_tbl', $tmp_data);
                  }*/

                  /*$del_paid = "
                                DELETE FROM soa_tmp_tbl WHERE id NOT IN(
                                SELECT paid.id
                                FROM (
                                SELECT p.id, p.invnum, p.invdate, p.issuedate, p.amountdue,
                                       payed.id AS payedid, IFNULL(payed.totalpayed, 0) AS totalpayed
                                FROM soa_tmp_tbl AS p
                                LEFT OUTER JOIN (
                                                 SELECT d.id, d.paymentno, d.paymentdate, SUM(d.paymentamount) AS totalpayed
                                                 FROM soa_tmp_tbl AS d
                                                 WHERE d.paymenttype IN ('OR', 'CM')  AND  hkey = '$tblnamekey'
                                                 GROUP BY d.id
                                                 ) AS payed ON payed.id = p.id
                                WHERE p.paymenttype IN ('A1', 'A2') AND  p.hkey = '$tblnamekey')AS paid
                                WHERE paid.amountdue > paid.totalpayed AND  hkey = '$tblnamekey') AND  hkey = '$tblnamekey';
                               ";
                  #echo "<pre>"; echo $del_paid; exit;
                  $this->db->query($del_paid);*/




                  /*$tmp_update = "
                                UPDATE soa_tmp_tbl AS a
                                INNER JOIN
                                soa_tmp_tbl AS b
                                ON a.id = b.id
                                SET a.invnum = b.invnum, a.invdate = b.invdate, a.issuedate = b.issuedate;
                                ";
                  $this->db->query($tmp_update);*/

                  #$stmt_get = "SELECT * FROM soa_tmp_tbl WHERE hkey = '$tblnamekey' ORDER BY datatype, id, paymenttype, agencyname, agencycode, clientname, clientcode";
                  #$stmt_get = "SELECT * FROM soa_tmp_tbl WHERE hkey = '$tblnamekey' ORDER BY datatype, paymenttype, agencyname, agencycode,  clientname, clientcode, invdate, invnum";


                  if ($type == 1) {
                        $stmt_get = "SELECT * FROM soa_tmp_tbl WHERE hkey = '$tblnamekey' ORDER BY datatype, paymenttype, agencyname, agencycode,  clientname, clientcode, invdate, invnum";
                        $result = $this->db->query($stmt_get)->result_array();
                        foreach ($result as $row) {
                        $newresult[$row['agencycode']][$row['clientcode']][$row['id']][$row['issuedate']][] = $row;
                      }
                  } else {
                      $stmt_get = "SELECT a.*, IFNULL(pay.payamount, 0) AS payamount, (a.amountdue - IFNULL(pay.payamount, 0)) AS balance FROM soa_tmp_tbl AS a
                        LEFT OUTER JOIN (
                                SELECT SUM(paymentamount) AS payamount, id FROM soa_tmp_tbl WHERE hkey = '$tblnamekey' AND paymenttype NOT IN('A1', 'A2') GROUP BY id
                        ) AS pay ON pay.id = a.id
                        WHERE a.hkey = '$tblnamekey' ORDER BY a.datatype, a.paymenttype, a.agencyname, a.agencycode,  a.clientname, a.clientcode, a.invdate, a.invnum";
                      #echo "<pre>"; echo $stmt_get; exit;
                      $result = $this->db->query($stmt_get)->result_array();
                      foreach ($result as $row) {
                        $newresult[$row['agencycode'].' '.$row['agencyname']][$row['clientcode'].' '.$row['clientname']][$row['id']][$row['issuedate']][] = $row;
                      }
                  }


                  #print_r2($newresult); exit;
                  $drop_tmp = "DELETE FROM soa_tmp_tbl WHERE hkey = '$tblnamekey'";
                  $this->db->query($drop_tmp);

             //break;
         //}

         return $newresult;
    }


    public function test() {
        $stmt_get = "SELECT * FROM soa_tmp_tbl WHERE hkey = 'TxxR9UZl201406231206342' ORDER BY datatype, id, paymenttype, agencyname, agencycode, clientname, clientcode, invnum";
                  $result = $this->db->query($stmt_get)->result_array();

                  foreach ($result as $row) {
                    $newresult[$row['id']][$row['agencycode']][$row['clientcode']][$row['invnum']][$row['issuedate']][] = $row;
                  }
                  //print_r2($newresult);
    }
}
