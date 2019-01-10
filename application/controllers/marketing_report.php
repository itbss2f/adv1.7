<?php

class Marketing_Report extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->sess = $this->authlib->validate();
        $this->load->model(array('model_advertising_dash/mod_advertising_dash', 'model_advertising_dash/mod_advertising_dashwithnet' ,'model_industry/industries', 'model_product/products', 'model_customer/customers'));
    }

    public function index() {
    $navigation['data'] = $this->GlobalModel->moduleList();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['industries'] = $this->industries->listOfIndustry();
        $data['prod'] = $this->products->listOfProduct();
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('marketing_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }

    public function export($datefrom, $dateto, $reporttype, $toptype, $toprank, $industry, $rettype) {
        echo 'test';
    }

    public function generate($datefrom, $dateto, $reporttype, $toptype, $toprank, $booktype, $industry, $rettype, $prod, $code = null) {
        #echo $booktype; exit;
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));

        ini_set('memory_limit', -1);

        set_time_limit(0);

        $this->load->library('Crystal', null, 'Crystal');
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);

        if ($reporttype == 1) {
            $reportname = "INDUSTRY";
        } else if ($reporttype == 2) {
            $reportname = "COMPARATIVE";
        }
		
		if ($rettype == 0) {
			$retrievaltype = "ACTUAL";
		} else if ($rettype == 2) {
			$retrievaltype = "ACTUAL NET";
		} else if ($rettype == 1) {
			$retrievaltype = "FORECAST";
		} else if ($rettype == 3) {
			$retrievaltype = "FORECAST NET";
		}

        $prev = $datefrom;
        $prevyr = strtotime('-1 year', strtotime($prev));
        $prevyear = date('Y', $prevyr);
        $curyear = date('Y', strtotime($datefrom));

        switch ($toptype) {
            case 1:
                $topname = 'TOP CLIENT';
                if ($reporttype == 1) {
                    $fields = array(
                            array('text' => 'Ranking', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Client Code', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .25, 'align' => 'center'),
                            array('text' => 'Agency Code', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .25, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .15, 'align' => 'center'),
                            );
                } else if ($reporttype == 2) {
                $fields = array(
                            array('text' => 'Ranking', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Client Code', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Agency Code', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .20, 'align' => 'center'),
                            array('text' => "Amount ($curyear)", 'width' => .13, 'align' => 'center'),
                            array('text' => "Amount ($prevyear)", 'width' => .13, 'align' => 'center'),
                            array('text' => 'Variance', 'width' => .11, 'align' => 'center'),
                            );
                }
            break;
            case 2:
                $topname = 'TOP AGENCY';
                if ($reporttype == 1) {
                $fields = array(
                            array('text' => 'Ranking', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Agency Code', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .30, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .15, 'align' => 'center'),
                            );
                } else if ($reporttype == 2) {
                $fields = array(
                            array('text' => 'Ranking', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Agency Code', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .30, 'align' => 'center'),
                            array('text' => "Amount ($curyear)", 'width' => .18, 'align' => 'center'),
                            array('text' => "Amount ($prevyear)", 'width' => .18, 'align' => 'center'),
                            array('text' => 'Variance', 'width' => .18, 'align' => 'center'),
                            );
                }
            break;
            case 3:
                $topname = 'TOP DIRECT';
                if ($reporttype == 1) {
                $fields = array(
                            array('text' => 'Ranking', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Client Code', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .30, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .15, 'align' => 'center'),
                            );
                } else if ($reporttype == 2) {
                $fields = array(
                            array('text' => 'Ranking', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Client Code', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .30, 'align' => 'center'),
                            array('text' => "Amount ($curyear)", 'width' => .18, 'align' => 'center'),
                            array('text' => "Amount ($prevyear)", 'width' => .18, 'align' => 'center'),
                            array('text' => 'Variance', 'width' => .18, 'align' => 'center'),
                            );
                }
            break;
            case 4:
                $topname = 'TOP AE';
                $fields = array(
                            array('text' => 'Ranking', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Account Executive Name', 'width' => .50, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .15, 'align' => 'center'),
                            );
            break;

            case 5:
                $topname = 'TOP INDUSTY SUMMARY';
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);
                $fields = array(
                            array('text' => 'Code', 'width' => .03, 'align' => 'center', 'bold' => true),
                            array('text' => 'Name', 'width' => .12, 'align' => 'center', 'bold' => true),
                            array('text' => 'Jan', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'Feb', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'Mar', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'Apr', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'May', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'Jun', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'Jul', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'Aug', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Sep', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Oct', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Nov', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Dec', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Total', 'width' => .08, 'align' => 'center'),
                            );
            break;
            case 6:
                $topname = 'TOP MAIN CUSTOMER';
                if ($reporttype == 1) {
                    $fields = array(
                            array('text' => 'Ranking', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Main Group Code', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Main Group Name', 'width' => .30, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .15, 'align' => 'center'),
                            );
                } else if ($reporttype == 2) {
                $fields = array(
                            array('text' => 'Ranking', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Main Group Code', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Main Group Name', 'width' => .30, 'align' => 'center'),
                            array('text' => "Amount ($curyear)", 'width' => .18, 'align' => 'center'),
                            array('text' => "Amount ($prevyear)", 'width' => .18, 'align' => 'center'),
                            array('text' => 'Variance', 'width' => .18, 'align' => 'center'),
                            );
                }
            break;
        }
        $template = $engine->getTemplate();
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('MARKETING REPORT - '.$reportname." ($topname) ", 10);
		$template->setText('RETRIEVE TYPE - '.$retrievaltype, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 8);

        $template->setFields($fields);

        if ($toptype == 5) {
        #$list = null;
        $list = $this->mod_advertising_dashwithnet->getTopIndustryYearTrend($datefrom, $dateto, $toprank, $booktype, $rettype, $prod, $code);
        } else {
        $list = $this->mod_advertising_dashwithnet->getMarketingData($datefrom, $dateto, $reporttype, $toptype, $toprank, $booktype, $industry, $rettype, $prod, $code);
        }
        #echo '<pre>'; print_r($list); exit;
        $rank = ($toprank == '' || $toprank == 0) ? '10' : "$toprank";
        $ranking = 1;  $subtotal = 0; $grandtotal = 0; $subprevtotal = 0; $subvartotal = 0; $grandprevtotal = 0; $grandvartotal = 0;
        if ($toptype == 1) {
            foreach ($list as $first => $datarow) {
                $result[] = array(array('text' => strtoupper($first), 'align' => 'left', 'bold' => true));
                array_splice($datarow, $rank);
                $ranking = 1; $subtotal = 0; $subprevtotal = 0; $subvartotal = 0;
                foreach ($datarow as $row) {
                    $subtotal += $row['totalsales']; $subprevtotal += $row['prevtotalsales']; $subvartotal += $row['var'];
                    $grandtotal += $row['totalsales']; $grandprevtotal += $row['prevtotalsales'];   $grandvartotal += $row['var'];
                    if ($reporttype == 2) {
                    $result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['clientcode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['clientname']), 'align' => 'left'),
                                        array('text' => $row['agencycode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['agencyname']), 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['prevtotalsales'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['var'], 2, '.',','), 'align' => 'right')
                                        );
                    } else {
                    $result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['clientcode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['clientname']), 'align' => 'left'),
                                        array('text' => $row['agencycode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['agencyname']), 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right')
                                        );
                    }
                $ranking += 1;
                }
                if ($reporttype == 2) {
                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Sub Total:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($subtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($subprevtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($subvartotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                );
                } else {
                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Sub Total:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($subtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                );
                }
                $result[] = array();
            }
            if ($reporttype == 2) {
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Grand Total:', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($grandtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($grandprevtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($grandvartotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
            } else {
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Grand Total:', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($grandtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
            }
        } else if ($toptype == 2) {
            foreach ($list as $first => $datarow) {
                $result[] = array(array('text' => strtoupper($first), 'align' => 'left', 'bold' => true));
                array_splice($datarow, $rank);
                $ranking = 1; $subtotal = 0; $subprevtotal = 0; $subvartotal = 0;
                foreach ($datarow as $row) {
                    $subtotal += $row['totalsales']; $subprevtotal += $row['prevtotalsales']; $subvartotal += $row['var'];
                    $grandtotal += $row['totalsales']; $grandprevtotal += $row['prevtotalsales'];   $grandvartotal += $row['var'];
                    if ($reporttype == 2) {
                    $result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['agencycode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['agencyname']), 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['prevtotalsales'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['var'], 2, '.',','), 'align' => 'right')
                                        );
                    } else {
                    $result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['agencycode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['agencyname']), 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right')
                                        );
                    }
                $ranking += 1;
                }
                if ($reporttype == 2) {
                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Sub Total:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($subtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($subprevtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($subvartotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                );
                } else {
                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Sub Total:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($subtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                );
                }
                $result[] = array();
            }
            if ($reporttype == 2) {
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Grand Total:', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($grandtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($grandprevtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($grandvartotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
            } else {
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Grand Total:', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($grandtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
            }

        } else if ($toptype == 3) {
            foreach ($list as $first => $datarow) {
                $result[] = array(array('text' => strtoupper($first), 'align' => 'left', 'bold' => true));
                array_splice($datarow, $rank);
                $ranking = 1; $subtotal = 0; $subprevtotal = 0; $subvartotal = 0;
                foreach ($datarow as $row) {
                    $subtotal += $row['totalsales']; $subprevtotal += $row['prevtotalsales']; $subvartotal += $row['var'];
                    $grandtotal += $row['totalsales']; $grandprevtotal += $row['prevtotalsales'];   $grandvartotal += $row['var'];

                    if ($reporttype == 2) {
                    $result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['clientcode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['clientname']), 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['prevtotalsales'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['var'], 2, '.',','), 'align' => 'right')
                                        );
                    } else {
                    $result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['clientcode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['clientname']), 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right')
                                        );
                    }
                $ranking += 1;
                }
                if ($reporttype == 2) {
                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Sub Total:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($subtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($subprevtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($subvartotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                );
                } else {
                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Sub Total:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($subtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                );
                }
                $result[] = array();
            }
            if ($reporttype == 2) {
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Grand Total:', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($grandtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($grandprevtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($grandvartotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
            } else {
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Grand Total:', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($grandtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
            }
        } else if ($toptype == 4) {
            foreach ($list as $first => $datarow) {
                $result[] = array(array('text' => strtoupper($first), 'align' => 'left', 'bold' => true));
                array_splice($datarow, $rank);
                $ranking = 1; $subtotal = 0;
                foreach ($datarow as $row) {
                    $subtotal += $row['totalsales'];  $grandtotal += $row['totalsales'];
                    $result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['aename'], 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right')
                                        );
                $ranking += 1;
                }
                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Sub Total:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($subtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                );
                $result[] = array();
            }
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Grand Total:', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($grandtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
        } else if ($toptype == 5) {
            $xjantotalsales = 0; $xfebtotalsales = 0; $xmartotalsales = 0; $xaprtotalsales = 0; $xmaytotalsales = 0; $xjunntotalsales = 0;
            $xjultotalsales = 0; $xaugtotalsales = 0; $xseptotalsales = 0; $xoctbtotalsales = 0; $xnovtotalsales = 0; $xdecetotalsales = 0;
            $xtotalsales = 0;
            foreach ($list as $row) {
                $xjantotalsales += $row['jantotalsales']; $xfebtotalsales += $row['febtotalsales']; $xmartotalsales += $row['martotalsales']; $xaprtotalsales += $row['aprtotalsales'];
                $xmaytotalsales += $row['maytotalsales']; $xjunntotalsales += $row['juntotalsales'];
                $xjultotalsales += $row['jultotalsales']; $xaugtotalsales += $row['augtotalsales']; $xseptotalsales += $row['septotalsales']; $xoctbtotalsales += $row['octbtotalsales'];
                $xnovtotalsales += $row['novtotalsales']; $xdecetotalsales += $row['decetotalsales'];
                $xtotalsales += $row['totalsales'];

                $result[] = array(
                                array('text' => $row['industry'], 'align' => 'left'),
                                array('text' => $row['industryname'], 'align' => 'left'),
                                array('text' => number_format($row['jantotalsales'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($row['febtotalsales'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($row['martotalsales'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($row['aprtotalsales'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($row['maytotalsales'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($row['juntotalsales'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($row['jultotalsales'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($row['augtotalsales'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($row['septotalsales'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($row['octbtotalsales'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($row['novtotalsales'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($row['decetotalsales'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right'),
                                );
            }

            $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'GRAND TOTAL:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($xjantotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($xfebtotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($xmartotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($xaprtotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($xmaytotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($xjunntotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($xjultotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($xaugtotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($xseptotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($xoctbtotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($xnovtotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($xdecetotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($xtotalsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                );
        }  else if ($toptype == 6) {
            foreach ($list as $first => $datarow) {
                $result[] = array(array('text' => strtoupper($first), 'align' => 'left', 'bold' => true));
                array_splice($datarow, $rank);
                $ranking = 1; $subtotal = 0; $subprevtotal = 0; $subvartotal = 0;
                foreach ($datarow as $row) {
                    $subtotal += $row['totalsales']; $subprevtotal += $row['prevtotalsales']; $subvartotal += $row['var'];
                    $grandtotal += $row['totalsales']; $grandprevtotal += $row['prevtotalsales'];   $grandvartotal += $row['var'];
                    if ($reporttype == 2) {
                    $result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['cmfgroup_code'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['cmfgroup_name']), 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['prevtotalsales'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['var'], 2, '.',','), 'align' => 'right')
                                        );
                    } else {
                    $result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['cmfgroup_code'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['cmfgroup_name']), 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right')
                                        );
                    }
                $ranking += 1;
                }
                if ($reporttype == 2) {
                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Sub Total:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($subtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($subprevtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($subvartotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                );
                } else {
                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Sub Total:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($subtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                );
                }
                $result[] = array();
            }
            if ($reporttype == 2) {
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Grand Total:', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($grandtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($grandprevtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($grandvartotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
            } else {
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Grand Total:', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($grandtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
            }
        }


        #exit;

        $template->setData($result);

        $template->setPagination();

        $engine->display();

    }

    public function marketing_export () {

        $datefrom = $this->input->get("datefrom");
        $dateto = $this->input->get("dateto");
        $reporttype = $this->input->get("reporttype");
        $toptype = $this->input->get("toptype");
        $toprank = $this->input->get("toprank");
        $industry = $this->input->get("industry");
        $rettype = $this->input->get("rettype");
        $prod = $this->input->get("prod");
        $code = $this->input->get("code");
        $booktype = $this->input->get("booktype");

        if ($toptype == 5) {
        $data['dlist'] = $this->mod_advertising_dashwithnet->getTopIndustryYearTrend($datefrom, $dateto, $toprank,$booktype, $rettype, $prod, $code);
        } else {
        $data['dlist'] = $this->mod_advertising_dashwithnet->getMarketingData($datefrom, $dateto, $reporttype, $toptype, $toprank,$booktype, $industry, $rettype, $prod, $code);
        }
        //print_r2($data); exit;

        $reportname = "";

        if ($reporttype == 1) {
            $reportname = "INDUSTRY";
        } else if ($reporttype == 2) {
            $reportname = "COMPARATIVE";
        }
		
		$retrievaltype == "";
		
		if ($rettype == 0) {
			$retrievaltype = "ACTUAL";
		} else if ($rettype == 2) {
			$retrievaltype = "ACTUAL NET";
		} else if ($rettype == 1) {
			$retrievaltype = "FORECAST";
		} else if ($rettype == 3) {
			$retrievaltype = "FORECAST NET";
		}

        $topname = "";
        if ($toptype == 1) {
            $topname = "TOP CLIENT";
        } else if ($toptype == 2) {
            $topname = "TOP AGENCY";
        } else if ($toptype == 3) {
            $topname = "TOP DIRECT";
        } else if ($toptype == 4) {
            $topname = "TOP AE";
        } else if ($toptype == 5) {
            $topname = "TOP INDUSTRY SUMMARY";
        }

        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data['reporttype'] = $reporttype;
        $data['retrievaltype'] = $retrievaltype;
		$data['reportname'] = $reportname;
        $data['topname'] = $topname;
        $data['toptype'] = $toptype;
        $data['toprank'] = $toprank;
        $data['industry'] = $industry;
        $data['rettype'] = $rettype;
        $data['prod'] = $prod;

        $prev = $datefrom;
        $prevyr = strtotime('-1 year', strtotime($prev));
        $data['prevyear'] = date('Y', $prevyr);
        $data['curyear'] = date('Y', strtotime($datefrom));

        if ($toptype == 5) {
            #echo 'test';  echo '<pre>'; var_dump($data); exit;
        $html = $this->load->view('marketing_report/marketing_reportexcel2', $data, true);
        } else {
        $html = $this->load->view('marketing_report/marketing_reportexcel', $data, true);
        }
        $filename ="Marketing_report".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);
        echo $html ;
        exit();



    }

    public function getClientInfo() {
        $search = $this->input->post('search');

        $response = $this->customers->list_of_clientinfo($search);

        echo json_encode($response);


    }
}
