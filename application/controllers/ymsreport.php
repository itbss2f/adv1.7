<?php
set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library')));
//set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/var/www/zend/library')));

class ymsreport extends CI_Controller
{

	public function __construct() {
	parent::__construct();

	$this->sess = $this->authlib->validate(); 
	$this->load->model(array('model_yms_reports/model_yms_reports'));	  
	}

	public function generate($id)
	{
	$this->load->library('Crystal', null, 'Crystal');


	//$post = $this->session->userdata('post');


	//$company = $this->CompaniesModel->fetch($post['company']);

	//$report = $this->ReportsModel->fetch($report);


	//$report->sql = $this->session->userdata('sql');

	//$result = $this->ReportsModel->query($report->sql, 'result_array');


		     

	//$report->pdf_size = $report->pdf_size ? $report->pdf_size : Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE;

	$engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);

	$template = $engine->getTemplate();

		       
	// HEADER

	$template->setText('PHILIPPINE DAILY INQUIRER', 15);

	$template->setText('SAMPLE REPORT', 10);



	/*

	if (isset($post['from']) && isset($post['to'])) {
	  
	  $from = date('F d, Y', strtotime($post['from'])); // March 01, 2013 
	  
	  $to = date('F d, Y', strtotime($post['to'])); // March 15, 2013
	  
	  $template->setText("for the period $from to $to", 9);
	}
	*/



	// BODY
	/*
	$widths = explode(';', $report->pdf_widths);

	$align = explode(';', $report->column_align);

	$fields = array();

	foreach ($this->_fields($report->sql) as $i => $each) {
	  
	  $fields[] = array('text' => $each, 'width' => @$widths[$i], 'align' => @$this->_aligns[$align[$i++]]);    
	}
	*/


	$data = $this->model_yms_reports->getData($id);
	$fieldname = $data['field_name'];
	$fieldsize = $data['field_size'];
	$fieldalign = $data['field_align'];	
	$fn = explode(';', $fieldname);
	$fs = explode(';', $fieldsize);
	$fa = explode(';', $fieldalign);
	$fields = array();	
	$x = 0;
	$a = 'center';
	foreach ($fn as $i) {	
	
	     if (@$fa[$x] == 'C') {
			$a = 'center';
		} else if (@$fa[$x] == 'L') {
			$a = 'left';
		} else if (@$fa[$x] == 'R') {
			$a = 'right';
		}
		
		$fields[] = array('text' => $i, 'width' => @$fs[$x], 'align' => @$a);
		$x += 1;
	}

	$template->setFields($fields);

	/*$fields = array(
	array('text' => 'ONE', 'width' => .30, 'align' => 'right'),
	array('text' => 'TWO', 'width' => .20),
	array('text' => 'THREE', 'width' => .20, 'align' => 'right'),
	array('text' => 'FOUR', 'width' => .20),
	array('text' => 'FIVE', 'width' => .10),
	);

	$template->setFields($fields);*/

	/*

	$group = count((explode(';', $report->group_by))) - 1;

	$group = ($group < 0 ? null : $group);

	$result = array();
	for ($i=0;$i<10;$i++) {
		$result[] = array(10000,'ASDASDASDASDASD ASD ASD ASD AS DAS DAS DAS DAS DASD ASD ASD ASD ASD',3,4,5);
	}
	$result[] = array(
	 	array('text' => 'SUB TITLE', 'bold' => true),
		'',
	 	array('text' => 3, 'style' => true, 'size' => 10),
	 	4,
		5
	);*/
	
	eval($data['formula']);  
	
	/*$sql_stmt = $data['sql_query'];

	$dataresult = $this->model_yms_reports->querydata($sql_stmt);

	$result = array();

	foreach ($dataresult as $res) {
		$result[] = array($res['code'], $res['name'], $res['id']);
	}
	$template->setData($result);*/

	/*
	// FOOTER

	$footer = $template->getLastPage();

	$top = $footer->getHeight() - 65;

	if ($footer->_y >= ($top - 10)) $footer = $template->addPage(8);

	$signatories = $this->ReportsModel->fetchSignatories($post['company']);

	$total = count($signatories);


	$employeeName = ucwords(strtolower(e_name2($this->_user->employee)));

	foreach ($signatories as $i => $each) {
	  
	  $footer->_y = 0;
	  
	  $left = ($footer->getWidth() / $total) * ($i);
	  
	  $template->setText($each->caption, 9, $footer, array('top' => $top, 'left' => $left));

	  $template->setText(' ', 10, $footer, array('top' => $top, 'left' => $left));
	  
	  $template->setText(str_replace('{employee}', $employeeName, $each->name), 9, $footer, array('top' => $top, 'left' => $left));
	  
	  $template->setText($each->title_name, 9, $footer, array('top' => $top, 'left' => $left));
	}
	*/

	$template->setPagination();

	$engine->display();

	//		echo 'test';
	}
}
