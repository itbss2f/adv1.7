<?php

class AR_aging_adtype extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->sess = $this->authlib->validate(); 
		$this->load->model(array('model_adtype/adtypes'));	  
	}

	public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList();   		
		$data['adtype'] = $this->adtypes->listOfAdType();	
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('ar_aging_adtype/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}
	
	public function generatereport() {
		set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library')));
		
		$this->load->library('Crystal', null, 'Crystal');
		
		$engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);

		$template = $engine->getTemplate();
		
		$template->setText('PHILIPPINE DAILY INQUIRER', 15);

		$template->setText('SAMPLE REPORT', 10);
		
		$fields = array(
			array('text' => 'PARTICULARS', 'width' => .25, 'align' => 'center'),
			array('text' => 'AI Number', 'width' => .08, 'align' => 'right'),
			array('text' => 'Total Amount Due', 'width' => .10, 'align' => 'right'),
			array('text' => 'Current', 'width' => .08, 'align' => 'right'),
			array('text' => '30 Days', 'width' => .08, 'align' => 'right'),
			array('text' => '60 Days', 'width' => .08, 'align' => 'right'),
			array('text' => '90 Days', 'width' => .08, 'align' => 'right'),
			array('text' => '120 Days', 'width' => .08, 'align' => 'right'),
			array('text' => 'Over 120 Days', 'width' => .08, 'align' => 'right'),
			array('text' => 'Over-payment', 'width' => .08, 'align' => 'right'),
		);

		$template->setFields($fields);
		
		
		$result = array();
		for ($i=0;$i<1000;$i++) {
			$result[] = array(10000,'ASDASDASDASDASD ASD ASD ASD AS DAS DAS DAS DAS DASD ASD ASD ASD ASD',3,4,5);
		}
		
		$template->setData($result);
		
		$template->setPagination();

		$engine->display();
	}
}