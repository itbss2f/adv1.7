<?php

class Collection_report2 extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->sess = $this->authlib->validate();
    $this->load->model(array('model_soareport/soareportsforcollection', 'model_empprofile/employeeprofiles'));
  }

  public function index() {
    $navigation['data'] = $this->GlobalModel->moduleList();
    $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);

    $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
    $welcome_layout['content'] = $this->load->view('collection_report2/index', $data, true);
    $this->load->view('welcome_index', $welcome_layout);
  }

  public function generatereport($datefrom,$dateto, $reporttype, $category) {
    #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));
    set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));

    set_time_limit(-1);

    $this->load->library('Crystal', null, 'Crystal');
    $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);
    $reportname = "";

    $reportname = "Uncollected w/tax";
    $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);
    $fields = array(
                        array('text' => 'Particulars', 'width' => .20, 'align' => 'left', 'bold' => true),
                        array('text' => 'Jan', 'width' => .06, 'align' => 'right'),
                        array('text' => 'Feb', 'width' => .06, 'align' => 'right'),
                        array('text' => 'March', 'width' => .06, 'align' => 'right'),
                        array('text' => 'April', 'width' => .06, 'align' => 'right'),
                        array('text' => 'May', 'width' => .06, 'align' => 'right'),
                        array('text' => 'June', 'width' => .06, 'align' => 'right'),
                        array('text' => 'July', 'width' => .06, 'align' => 'right'),
                        array('text' => 'Aug', 'width' => .06, 'align' => 'right'),
                        array('text' => 'Sept', 'width' => .06, 'align' => 'right'),
                        array('text' => 'Oct', 'width' => .06, 'align' => 'right'),
                        array('text' => 'Nov', 'width' => .06, 'align' => 'right'),
                        array('text' => 'Dec', 'width' => .06, 'align' => 'right'),
                        array('text' => 'Total', 'width' => .06, 'align' => 'right'),
                    );

    $template = $engine->getTemplate();
    $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
    $template->setText('REPORT TYPE - '.$reportname, 10);
    $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);

    $template->setFields($fields);


    $data = $this->soareportsforcollection->query_report($datefrom,$dateto, $reporttype, $category);
    #print_r2($data); exit;
    $totalbalanceamount = 0; $grandtotalamount = 0;
    $jan = 0; $feb = 0; $mar= 0; $apr = 0; $may = 0; $jun = 0; $jul = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;
    foreach($data as  $x => $datalist) {
      $result[] = array(array('text' => $x, 'align' => 'left', 'bold' => true));
      foreach($datalist as $dataitems) {
        $jan += $dataitems['jan']; $feb += $dataitems['feb']; $mar += $dataitems['mar']; $apr += $dataitems['apr']; $may += $dataitems['may']; $jun += $dataitems['jun'];
        $jul += $dataitems['jul']; $aug += $dataitems['aug']; $sep += $dataitems['sep']; $oct += $dataitems['oct']; $nov += $dataitems['nov']; $dec += $dataitems['dec'];
        //totalbalanceamount
        $totalbalanceamount = $dataitems['jan']+$dataitems['feb']+$dataitems['mar']+$dataitems['apr']+$dataitems['may']+ $dataitems['jun']+
        $dataitems['jul']+$dataitems['aug']+$dataitems['sep']+$dataitems['oct']+$dataitems['nov']+$dataitems['dec'];
        //GrandTotal
        $grandtotalamount += $totalbalanceamount;

        if($category == 1) {
          $particulars = $dataitems['clientcode'].' - '.$dataitems['clientname'];
        } else {
          $particulars = $dataitems['agencycode'].' - '.$dataitems['agencyname'];
        }

        $result[] = array(
                        array('text' => $particulars, 'align' => 'left'),
                        array('text' => number_format($dataitems['jan'], 2,'.',','), 'align' => 'right'),
                        array('text' => number_format($dataitems['feb'], 2,'.',','), 'align' => 'right'),
                        array('text' => number_format($dataitems['mar'], 2,'.',','), 'align' => 'right'),
                        array('text' => number_format($dataitems['apr'], 2,'.',','), 'align' => 'right'),
                        array('text' => number_format($dataitems['may'], 2,'.',','), 'align' => 'right'),
                        array('text' => number_format($dataitems['jun'], 2,'.',','), 'align' => 'right'),
                        array('text' => number_format($dataitems['jul'], 2,'.',','), 'align' => 'right'),
                        array('text' => number_format($dataitems['aug'], 2,'.',','), 'align' => 'right'),
                        array('text' => number_format($dataitems['sep'], 2,'.',','), 'align' => 'right'),
                        array('text' => number_format($dataitems['oct'], 2,'.',','), 'align' => 'right'),
                        array('text' => number_format($dataitems['nov'], 2,'.',','), 'align' => 'right'),
                        array('text' => number_format($dataitems['dec'], 2,'.',','), 'align' => 'right'),
                        array('text' => number_format($totalbalanceamount, 2,'.',','), 'align' => 'right', 'bold' => true)
                        );
                }

                $result[] = array();
                $result[] = array(
                                array('text' => 'Total', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($jan, 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($feb, 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($mar, 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($apr, 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($may, 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($jun, 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($jul, 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($aug, 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($sep, 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($oct, 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($nov, 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($dec, 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($grandtotalamount, 2, '.',','), 'align' => 'right', 'bold' => true)
                              );
        }


    $template->setData($result);

    $template->setPagination();

    $engine->display();
  }

}
