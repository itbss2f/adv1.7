<?php 

class YMS_Parameter extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->sess = $this->authlib->validate(); 
		$this->load->model(array('model_yms_parameter/model_yms_parameter'));	  
	}

	public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList();   		
		$data['parameter'] = $this->model_yms_parameter->list_Parameter();	
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');  

		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('yms_parameters/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	public function newdata() {		
		$response['newdata_view'] = $this->load->view('yms_parameters/newdata', null, true);
		echo json_encode($response);
	}

	public function validateCode() {        
		$this->form_validation->set_rules('code', 'Code', 'trim|is_unique[yms_parameters.company_code]');
		if ($this->form_validation->run() == FALSE)
		{
		  echo "true";
		}        
	}
	
	public function save() {
		$data['company_code'] = $this->input->post('parameter_code');
		$data['company_name'] = $this->input->post('parameter_name');
		$data['vat_inclusive'] = mysql_escape_string(str_replace(",","",$this->input->post('parameter_vatinclusive')));
		$data['net_returns_rate'] = mysql_escape_string(str_replace(",","",$this->input->post('parameter_netratereturn')));     
		$data['insert_rate'] = mysql_escape_string(str_replace(",","",$this->input->post('parameter_insertrate')));     
		$data['ave_daily_circ'] = mysql_escape_string(str_replace(",","",$this->input->post('parameter_avedailycirc')));     
		$data['fixed_expenses'] = mysql_escape_string(str_replace(",","",$this->input->post('parameter_fixedexpenses')));            

		$this->model_yms_parameter->saveNewData($data);

		$msg = "You successfully save YMS - Parameter";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_parameter');
	}

	public function removedata($id) {		
		$this->model_yms_parameter->removeData(abs($id));
		redirect('yms_parameter');
	}

	public function editdata() {
		
		$id = $this->input->post('id');
		$data['data'] = $this->model_yms_parameter->getData($id);		
		$response['editdata_view'] = $this->load->view('yms_parameters/editdata', $data, true);
		echo json_encode($response);
	}

	public function update($id) {	
		$data['company_name'] = $this->input->post('parameter_name');
		$data['vat_inclusive'] = mysql_escape_string(str_replace(",","",$this->input->post('parameter_vatinclusive')));
		$data['net_returns_rate'] = mysql_escape_string(str_replace(",","",$this->input->post('parameter_netratereturn')));     
		$data['insert_rate'] = mysql_escape_string(str_replace(",","",$this->input->post('parameter_insertrate')));     
		$data['ave_daily_circ'] = mysql_escape_string(str_replace(",","",$this->input->post('parameter_avedailycirc')));     
		$data['fixed_expenses'] = mysql_escape_string(str_replace(",","",$this->input->post('parameter_fixedexpenses')));            
		$this->model_yms_parameter->saveupdateNewData($data, abs($id));

		$msg = "You successfully update YMS - Parameter";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_parameter');
	}

}
