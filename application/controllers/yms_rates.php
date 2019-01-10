<?php 

class YMS_Rates extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->sess = $this->authlib->validate(); 
		$this->load->model(array('model_yms_edition/model_yms_edition', 'model_yms_printingpress/model_yms_printingpress', 'model_yms_rates/model_yms_rates'));	  
	}

	public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList();   					
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$data['rates'] = $this->model_yms_rates->getData();
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('yms_rates/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	public function newdata() {
		$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();
		$data['printingpress'] = $this->model_yms_printingpress->list_printingpress();
		$response['newdata_view'] = $this->load->view('yms_rates/newdata', $data, true);
		echo json_encode($response);
	}

	public function validateCode() {  
		$edition = $this->input->post('edition');
		$printingpress = $this->input->post('printingpress');      

		$check = $this->model_yms_rates->checkUnique($edition, $printingpress);
		if (!empty($check)) {
			echo "true";
		}  
	}

	public function save() {
		$data['edition'] = $this->input->post('edition');
		$data['printing_press'] = $this->input->post('printingpress');
		$data['period_covered_from'] = $this->input->post('periodcoveredfrom');
		$data['period_covered_to'] = $this->input->post('periodcoveredto');
		$data['circulation_copies'] = mysql_escape_string(str_replace(",","",$this->input->post('circulationcopies')));     
		$data['newsprint_cost_rate'] = mysql_escape_string(str_replace(",","",$this->input->post('newsprintcostrate')));     
		$data['printing_cost_rate_bw'] = mysql_escape_string(str_replace(",","",$this->input->post('bw')));     
		$data['printing_cost_rate_spot1'] = mysql_escape_string(str_replace(",","",$this->input->post('spot1')));     
		$data['printing_cost_rate_spot2'] = mysql_escape_string(str_replace(",","",$this->input->post('spot2')));     
		$data['printing_cost_rate_fullcolor'] = mysql_escape_string(str_replace(",","",$this->input->post('fullcolor')));     
		$data['printing_cost_rate_discount'] = mysql_escape_string(str_replace(",","",$this->input->post('discount')));     
		$data['pre_press_charge'] = mysql_escape_string(str_replace(",","",$this->input->post('prepresscharge')));     
		$data['pre_press_discount'] = mysql_escape_string(str_replace(",","",$this->input->post('prepressdiscount')));  
		$data['delivery_cost_per_copy'] = mysql_escape_string(str_replace(",","",$this->input->post('deliverycostcopy')));     
		$data['delivery_cost_per_issue'] = mysql_escape_string(str_replace(",","",$this->input->post('deliverycostissue')));     
		$data['remarks'] = $this->input->post('remarks'); 

		$this->model_yms_rates->saveNewData($data);

		$msg = "You successfully save YMS - Rates";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_rates');
	}

	public function editdata() {
		
		$id = $this->input->post('id');
		$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();
		$data['printingpress'] = $this->model_yms_printingpress->list_printingpress();
		$data['data'] = $this->model_yms_rates->getDataRate($id);
		$response['editdata_view'] = $this->load->view('yms_rates/editdata', $data, true);
		echo json_encode($response);
	}

	public function update($id) {
		
		$data['period_covered_from'] = $this->input->post('periodcoveredfrom');
		$data['period_covered_to'] = $this->input->post('periodcoveredto');
		$data['circulation_copies'] = mysql_escape_string(str_replace(",","",$this->input->post('circulationcopies')));     
		$data['newsprint_cost_rate'] = mysql_escape_string(str_replace(",","",$this->input->post('newsprintcostrate')));     
		$data['printing_cost_rate_bw'] = mysql_escape_string(str_replace(",","",$this->input->post('bw')));     
		$data['printing_cost_rate_spot1'] = mysql_escape_string(str_replace(",","",$this->input->post('spot1')));     
		$data['printing_cost_rate_spot2'] = mysql_escape_string(str_replace(",","",$this->input->post('spot2')));     
		$data['printing_cost_rate_fullcolor'] = mysql_escape_string(str_replace(",","",$this->input->post('fullcolor')));     
		$data['printing_cost_rate_discount'] = mysql_escape_string(str_replace(",","",$this->input->post('discount')));     
		$data['pre_press_charge'] = mysql_escape_string(str_replace(",","",$this->input->post('prepresscharge')));     
		$data['pre_press_discount'] = mysql_escape_string(str_replace(",","",$this->input->post('prepressdiscount')));  
		$data['delivery_cost_per_copy'] = mysql_escape_string(str_replace(",","",$this->input->post('deliverycostcopy')));     
		$data['delivery_cost_per_issue'] = mysql_escape_string(str_replace(",","",$this->input->post('deliverycostissue')));     
		$data['remarks'] = $this->input->post('remarks'); 

		$this->model_yms_rates->saveupdateData($data, abs($id));

		$msg = "You successfully save updae YMS - Rates";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_rates');
	}

	public function removedata($id) {		
		$this->model_yms_rates->removeData(abs($id));
		redirect('yms_rates');
	}
    
    public function duplicate() {
        $id = $this->input->post('id');
        $data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();
        $data['printingpress'] = $this->model_yms_printingpress->list_printingpress();
        $data['data'] = $this->model_yms_rates->getDataRate($id);
        $response['duplicate_view'] = $this->load->view('yms_rates/duplicatedata', $data, true);
        echo json_encode($response);    
    }
    
    public function duplicatesave() {
        $data['edition'] = $this->input->post('edition');
        $data['printing_press'] = $this->input->post('printingpress');
        $data['period_covered_from'] = $this->input->post('periodcoveredfrom');
        $data['period_covered_to'] = $this->input->post('periodcoveredto');
        $data['circulation_copies'] = mysql_escape_string(str_replace(",","",$this->input->post('circulationcopies')));     
        $data['newsprint_cost_rate'] = mysql_escape_string(str_replace(",","",$this->input->post('newsprintcostrate')));     
        $data['printing_cost_rate_bw'] = mysql_escape_string(str_replace(",","",$this->input->post('bw')));     
        $data['printing_cost_rate_spot1'] = mysql_escape_string(str_replace(",","",$this->input->post('spot1')));     
        $data['printing_cost_rate_spot2'] = mysql_escape_string(str_replace(",","",$this->input->post('spot2')));     
        $data['printing_cost_rate_fullcolor'] = mysql_escape_string(str_replace(",","",$this->input->post('fullcolor')));     
        $data['printing_cost_rate_discount'] = mysql_escape_string(str_replace(",","",$this->input->post('discount')));     
        $data['pre_press_charge'] = mysql_escape_string(str_replace(",","",$this->input->post('prepresscharge')));     
        $data['pre_press_discount'] = mysql_escape_string(str_replace(",","",$this->input->post('prepressdiscount')));  
        $data['delivery_cost_per_copy'] = mysql_escape_string(str_replace(",","",$this->input->post('deliverycostcopy')));     
        $data['delivery_cost_per_issue'] = mysql_escape_string(str_replace(",","",$this->input->post('deliverycostissue')));     
        $data['remarks'] = $this->input->post('remarks'); 

        $this->model_yms_rates->saveNewData($data);

        $msg = "You successfully save YMS - Rates";

        $this->session->set_flashdata('msg', $msg);

        redirect('yms_rates');
    }
}
