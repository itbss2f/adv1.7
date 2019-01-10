<?php

class YMS_IncomeParameter extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->sess = $this->authlib->validate(); 
		$this->load->model(array('model_yms_incomeparameter/model_yms_incomeparameter'));	  
	}

	public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList();   		
		$data['incomeparameter'] = $this->model_yms_incomeparameter->list_incomeparameter();	
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('yms_incomeparameters/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	public function newdata() {		
		$response['newdata_view'] = $this->load->view('yms_incomeparameters/newdata', null, true);
		echo json_encode($response);
	}

	public function save() {
		
		$data['period_covered_from'] = $this->input->post('periodcoveredfrom');
		$data['period_covered_to'] = $this->input->post('periodcoveredto');
		$data['ave_daily_circ'] = mysql_escape_string(str_replace(",","",$this->input->post('avedailycopies')));
		$data['circ_manila'] = mysql_escape_string(str_replace(",","",$this->input->post('circmanila')));
     	$data['circ_cebu'] = mysql_escape_string(str_replace(",","",$this->input->post('circcebu')));
		$data['circ_davao'] = mysql_escape_string(str_replace(",","",$this->input->post('circdavao')));
		$data['net_return_rate'] = mysql_escape_string(str_replace(",","",$this->input->post('netreturnrate')));
		$data['fixed_monthly'] = mysql_escape_string(str_replace(",","",$this->input->post('fixedmonthly')));
		$data['fixed_daily'] = mysql_escape_string(str_replace(",","",$this->input->post('fixeddaily')));
		$data['percentage_circ'] = mysql_escape_string(str_replace(",","",$this->input->post('percentagecirc')));
		$data['percentage_delivery'] = mysql_escape_string(str_replace(",","",$this->input->post('percentagedelivery')));
		$data['percentage_comm'] = mysql_escape_string(str_replace(",","",$this->input->post('percentegecomm')));
		$data['remarks'] = $this->input->post('remarks');

		$this->model_yms_incomeparameter->saveNewData($data);

		$msg = "You successfully save YMS - Income Parameter";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_incomeparameter');
	}

	public function editdata() {
		
		$id = $this->input->post('id');
		$data['data'] = $this->model_yms_incomeparameter->getData($id);		
		$response['editdata_view'] = $this->load->view('yms_incomeparameters/editdata', $data, true);
		echo json_encode($response);
	}

	public function update($id) {	
		$data['period_covered_from'] = $this->input->post('periodcoveredfrom');
		$data['period_covered_to'] = $this->input->post('periodcoveredto');
		$data['ave_daily_circ'] = mysql_escape_string(str_replace(",","",$this->input->post('avedailycopies')));
		$data['circ_manila'] = mysql_escape_string(str_replace(",","",$this->input->post('circmanila')));
     	$data['circ_cebu'] = mysql_escape_string(str_replace(",","",$this->input->post('circcebu')));
		$data['circ_davao'] = mysql_escape_string(str_replace(",","",$this->input->post('circdavao')));
		$data['net_return_rate'] = mysql_escape_string(str_replace(",","",$this->input->post('netreturnrate')));
		$data['fixed_monthly'] = mysql_escape_string(str_replace(",","",$this->input->post('fixedmonthly')));
		$data['fixed_daily'] = mysql_escape_string(str_replace(",","",$this->input->post('fixeddaily')));
		$data['percentage_circ'] = mysql_escape_string(str_replace(",","",$this->input->post('percentagecirc')));
		$data['percentage_delivery'] = mysql_escape_string(str_replace(",","",$this->input->post('percentagedelivery')));
		$data['percentage_comm'] = mysql_escape_string(str_replace(",","",$this->input->post('percentegecomm')));
		$data['remarks'] = $this->input->post('remarks');

		$this->model_yms_incomeparameter->saveupdateNewData($data, abs($id));

		$msg = "You successfully update YMS - Income Parameter";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_incomeparameter');
	}

	public function removedata($id) {		
		$this->model_yms_incomeparameter->removeData(abs($id));
		redirect('yms_incomeparameter');
	}
}
