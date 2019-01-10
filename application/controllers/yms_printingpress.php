<?php 

class YMS_PrintingPress extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->sess = $this->authlib->validate(); 
		$this->load->model(array('model_yms_printingpress/model_yms_printingpress'));	  
	}

	public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList();  
		$data['printingpress'] = $this->model_yms_printingpress->list_printingpress(); 			
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
            
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('yms_printingpress/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	public function newdata() {
		
		$response['newdata_view'] = $this->load->view('yms_printingpress/newdata', null, true);
		echo json_encode($response);
	}

	public function validateCode() {        
		$this->form_validation->set_rules('code', 'Code', 'trim|is_unique[yms_printing_press.code]');
		if ($this->form_validation->run() == FALSE)
		{
		  echo "true";
		}        
	}

	public function save() {
		$data['code'] = $this->input->post('printing_code');
		$data['name'] = $this->input->post('printing_name');

		$this->model_yms_printingpress->saveNewData($data);

		$msg = "You successfully save YMS - Printing Press";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_printingpress');
	}

	public function editdata() {
		
		$id = $this->input->post('id');
		$data['data'] = $this->model_yms_printingpress->getData($id);

		$response['editdata_view'] = $this->load->view('yms_printingpress/editdata', $data, true);
		echo json_encode($response);
	}

	public function update($id) {	
		$data['name'] = $this->input->post('printing_name');
		$this->model_yms_printingpress->saveupdateNewData($data, abs($id));

		$msg = "You successfully update YMS - Printing Press";

		$this->session->set_flashdata('msg', $msg);
		redirect('yms_printingpress');
	}

	public function removedata($id) {		
		$this->model_yms_printingpress->removeData(abs($id));
		redirect('yms_printingpress');
	}
}
