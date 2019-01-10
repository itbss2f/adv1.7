<?php

class Soareportsforcollection extends CI_Model {

  public function query_report($datefrom,$dateto, $reporttype, $category) {

    if($category == 1) {

      $stmt = "SELECT z.*, SUM(z.balanceamount)  AS totalbalanceamount
                  FROM(
                	   SELECT
                	       IFNULL(cmf.cmf_code, '') AS agencycode, IFNULL(cmf.cmf_name, '') AS agencyname,
                	       aom.ao_cmf, aom.ao_payee AS clientname,
                	       ordt.or_num, DATE(ordt.or_date) as or_date, ordt.or_docitemid, SUM(ordt.or_assignwtaxamt) AS or_assignwtaxamt, ordt.or_wtaxpercent,
                	       IFNULL(ctax.dc_assignamt, 0) AS dcassignamt, (SUM(ordt.or_assignwtaxamt) - IFNULL(ctax.dc_assignamt, 0)) AS balanceamount,
                	       MONTH(ordt.or_date) AS mon, YEAR(ordt.or_date) AS yr
                	FROM or_d_tm AS ordt
                	INNER JOIN ao_p_tm AS aop ON ordt.or_docitemid = aop.id
                	INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                	LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                	LEFT OUTER JOIN (
                		SELECT dcdt.dc_docitemid, dcdt.dc_num, dcdt.dc_date, SUM(dcdt.dc_assignamt) AS dc_assignamt
                		FROM dc_d_tm AS dcdt
                		INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dcdt.dc_num
                		WHERE dcdt.dc_doctype = 'SI'
                		AND DATE(dcdt.dc_date) >= '$datefrom'
                		AND dcm.dc_subtype IN (11, 12, 15, 16)
                		GROUP BY dcdt.dc_docitemid
                  	) AS ctax ON ctax.dc_docitemid = ordt.or_docitemid
                  	WHERE ordt.or_doctype = 'SI' AND ordt.or_wtaxpercent != 0 AND cmf_code != '' AND ao_cmf != ''
                  	AND DATE(ordt.or_date)  BETWEEN '$datefrom' AND '$dateto'
                  	AND ordt.or_assignwtaxamt > IFNULL(ctax.dc_assignamt, 0)
                  	GROUP BY ordt.or_docitemid, MONTH(ordt.or_date), YEAR(ordt.or_date)
                  	ORDER BY ordt.or_date
                  ) AS z
                  GROUP BY z.ao_cmf, z.mon, z.yr
                  ORDER BY z.ao_cmf, z.or_date, z.yr ASC";

      $result = $this->db->query($stmt)->result_array();

      $newresult = array();

      foreach ($result as $row) {
          $newresult[$row['ao_cmf'].' - '.$row['clientname']][] = $row;
      }

  } else  {
            $stmt = "SELECT z.*, SUM(z.balanceamount)  AS totalbalanceamount
                        FROM(
                      	   SELECT
                      	       IFNULL(cmf.cmf_code, '') AS agencycode, IFNULL(cmf.cmf_name, '') AS agencyname,
                      	       aom.ao_cmf, aom.ao_payee AS clientname,
                      	       ordt.or_num, DATE(ordt.or_date) as or_date, ordt.or_docitemid, SUM(ordt.or_assignwtaxamt) AS or_assignwtaxamt, ordt.or_wtaxpercent,
                      	       IFNULL(ctax.dc_assignamt, 0) AS dcassignamt, (SUM(ordt.or_assignwtaxamt) - IFNULL(ctax.dc_assignamt, 0)) AS balanceamount,
                      	       MONTH(ordt.or_date) AS mon, YEAR(ordt.or_date) AS yr
                      	FROM or_d_tm AS ordt
                      	INNER JOIN ao_p_tm AS aop ON ordt.or_docitemid = aop.id
                      	INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                      	LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                      	LEFT OUTER JOIN (
                      		SELECT dcdt.dc_docitemid, dcdt.dc_num, dcdt.dc_date, SUM(dcdt.dc_assignamt) AS dc_assignamt
                      		FROM dc_d_tm AS dcdt
                      		INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dcdt.dc_num
                      		WHERE dcdt.dc_doctype = 'SI'
                      		AND DATE(dcdt.dc_date) >= '$datefrom'
                      		AND dcm.dc_subtype IN (11, 12, 15, 16)
                      		GROUP BY dcdt.dc_docitemid
                        	) AS ctax ON ctax.dc_docitemid = ordt.or_docitemid
                        	WHERE ordt.or_doctype = 'SI' AND ordt.or_wtaxpercent != 0 AND cmf_code != '' AND ao_cmf != ''
                        	AND DATE(ordt.or_date)  BETWEEN '$datefrom' AND '$dateto'
                        	AND ordt.or_assignwtaxamt > IFNULL(ctax.dc_assignamt, 0)
                        	GROUP BY ordt.or_docitemid, MONTH(ordt.or_date), YEAR(ordt.or_date)
                        	ORDER BY ordt.or_date
                        ) AS z
                        GROUP BY z.agencycode, z.mon, z.yr
                        ORDER BY z.agencycode, z.or_date, z.yr ASC";

            $result = $this->db->query($stmt)->result_array();

            $newresult = array();

            foreach ($result as $row) {
                $newresult[$row['agencycode'].' - '.$row['agencyname']][] = $row;
            }

        }

  $dataresult = array();

  $jan =0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $jun = 0;
  $jul =0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;

  foreach ($newresult as $rsort) {
        $jan =0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $jun = 0;
        $jul =0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;
        foreach ($rsort as $sort) {
            if ($sort['mon'] == 1) { $jan += $sort['balanceamount']; }
            if ($sort['mon'] == 2) { $feb += $sort['balanceamount']; }
            if ($sort['mon'] == 3) { $mar += $sort['balanceamount']; }
            if ($sort['mon'] == 4) { $apr += $sort['balanceamount']; }
            if ($sort['mon'] == 5) { $may += $sort['balanceamount']; }
            if ($sort['mon'] == 6) { $jun += $sort['balanceamount']; }
            if ($sort['mon'] == 7) { $jul += $sort['balanceamount']; }
            if ($sort['mon'] == 8) { $aug += $sort['balanceamount']; }
            if ($sort['mon'] == 9) { $sep += $sort['balanceamount']; }
            if ($sort['mon'] == 10) { $oct += $sort['balanceamount']; }
            if ($sort['mon'] == 11) { $nov += $sort['balanceamount']; }
            if ($sort['mon'] == 12) { $dec += $sort['balanceamount']; }
            }
            $dataresult[$sort['yr']][] = array(
                                  'agencycode' => $sort['agencycode'],
                                  'agencyname' => $sort['agencyname'],
                                  'clientcode' => $sort['ao_cmf'],
                                  'clientname' => $sort['clientname'],
                                  'jan' => $jan, 'feb' => $feb,
                                  'mar' => $mar, 'apr' => $apr,
                                  'may' => $may, 'jun' => $jun,
                                  'jul' => $jul, 'aug' => $aug,
                                  'sep' => $sep, 'oct' => $oct,
                                  'nov' => $nov, 'dec' => $dec,
                                    );
    }

    return $dataresult;

  }


}
