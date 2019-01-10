<?php

class YMS_Reports extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->sess = $this->authlib->validate(); 
		$this->load->model(array('model_yms_reports/model_yms_reports'));	  
	}

	public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList();   		
		$data['reports'] = $this->model_yms_reports->list_YMS_Reports();
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('yms_reports/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	public function newdata() {
		$response['newdata_view'] = $this->load->view('yms_reports/newdata', null, true);
		echo json_encode($response);
	}

	public function savenewreports() {
		$data['title'] = $this->input->post('title');
		$data['description'] = $this->input->post('description');
		$data['sql_query'] = $this->input->post('sqlquery');
		$data['formula'] = $this->input->post('formula');
		$data['field_name'] = $this->input->post('fname');
		$data['field_align'] = $this->input->post('falign');
		$data['field_size'] = $this->input->post('fsize');
		
		$this->model_yms_reports->saveNewData($data);

		$msg = "You successfully save YMS - Reports";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_reports');
	}

	public function removedata($id) {		
		$this->model_yms_reports->removeData(abs($id));
		redirect('yms_reports');
	}

	public function editdata() {		
		$id = $this->input->post('id');
		$data['data'] = $this->model_yms_reports->getData($id);
		$response['editdata_view'] = $this->load->view('yms_reports/editdata', $data, true);
		echo json_encode($response);
	}

	public function update($id) {	
		$data['title'] = $this->input->post('title');
		$data['description'] = $this->input->post('description');
		$data['sql_query'] = $this->input->post('sqlquery');
		$data['formula'] = $this->input->post('formula');
		$data['field_name'] = $this->input->post('fname');
		$data['field_align'] = $this->input->post('falign');
		$data['field_size'] = $this->input->post('fsize');
		$this->model_yms_reports->saveupdateNewData($data, abs($id));

		$msg = "You successfully update YMS - Reports";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_reports');
	}
	
}
