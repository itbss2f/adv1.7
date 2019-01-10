<?php 

class YMS_Edition extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->sess = $this->authlib->validate(); 
		$this->load->model(array('model_yms_edition/model_yms_edition'));	  
	}

	public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList();   		
		$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();	
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('yms_editions/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	public function newdata() {
		//$data['edition'] = $this->model_yms_edition->listYMS_Edition();      
		$response['newdata_view'] = $this->load->view('yms_editions/newdata', null, true);
		echo json_encode($response);
	}

	public function validateCode() {        
		$this->form_validation->set_rules('code', 'Code', 'trim|is_unique[yms_edition.code]');
		if ($this->form_validation->run() == FALSE)
		{
		  echo "true";
		}        
	}

	public function save() {
		$data['code'] = $this->input->post('edition_code');
		$data['name'] = $this->input->post('edition_name');
		$data['total_ccm'] = mysql_escape_string(str_replace(",","",$this->input->post('edition_totalccm')));     
		$data['type'] = $this->input->post('edition_type');

		$this->model_yms_edition->saveNewData($data);

		$msg = "You successfully save YMS - Edition";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_edition');
	}

	public function removedata($id) {		
		$this->model_yms_edition->removeData(abs($id));
		redirect('yms_edition');
	}

	public function editdata() {
		
		$id = $this->input->post('id');
		$data['data'] = $this->model_yms_edition->getData($id);
		$response['editdata_view'] = $this->load->view('yms_editions/editdata', $data, true);
		echo json_encode($response);
	}

	public function update($id) {	
		$data['name'] = $this->input->post('edition_name');
		$data['total_ccm'] = mysql_escape_string(str_replace(",","",$this->input->post('edition_totalccm')));     
		$data['type'] = $this->input->post('edition_type');
		$this->model_yms_edition->saveupdateNewData($data, abs($id));

		$msg = "You successfully update YMS - Edition";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_edition');
	}
}
