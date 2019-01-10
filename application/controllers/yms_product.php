<?php 

class YMS_Product extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->sess = $this->authlib->validate(); 
		$this->load->model(array('model_yms_edition/model_yms_edition', 'model_yms_products/model_yms_products'));	  
	}

	public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList();   		
		$data['ymsproduct'] = $this->model_yms_products->list_ymsproducts();	
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
        
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('yms_products/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	public function newdata() {
		$data['edition'] = $this->model_yms_edition->listYMS_Edition();      
		$response['newdata_view'] = $this->load->view('yms_products/newdata', $data, true);
		echo json_encode($response);
	}

	public function validateCode() {        
		$this->form_validation->set_rules('code', 'Code', 'trim|is_unique[yms_products.code]');
		if ($this->form_validation->run() == FALSE)
		{
		  echo "true";
		}        
	}

	public function save() {
		$data['code'] = $this->input->post('product_code');
		$data['name'] = $this->input->post('product_name');
		$data['total_ccm'] = mysql_escape_string(str_replace(",","",$this->input->post('product_totalccm')));     
		$data['edition_id'] = $this->input->post('product_edition');

		$this->model_yms_products->saveNewData($data);

		$msg = "You successfully save YMS - Product";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_product');
	}

	public function removedata($id) {		
		$this->model_yms_products->removeData(abs($id));
		redirect('yms_product');
	}

	public function editdata() {
		
		$id = $this->input->post('id');
		$data['data'] = $this->model_yms_products->getData($id);
		$data['edition'] = $this->model_yms_edition->listYMS_Edition(); 
		$response['editdata_view'] = $this->load->view('yms_products/editdata', $data, true);
		echo json_encode($response);
	}

	public function update($id) {	
		$data['name'] = $this->input->post('product_name');
		$data['total_ccm'] = mysql_escape_string(str_replace(",","",$this->input->post('product_totalccm')));     
		$data['edition_id'] = $this->input->post('product_edition');
		$this->model_yms_products->saveupdateNewData($data, abs($id));

		$msg = "You successfully update YMS - Product";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_product');
	}
}
