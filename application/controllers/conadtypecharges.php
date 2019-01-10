<?php
  
class Conadtypecharges extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_conadtypecharges/model_conadtypecharges', 'model_product/products', 'model_classification/classifications'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['charges'] = $this->model_conadtypecharges->list_charges();                   
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('conadtypecharges/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function newdata() {      
        $data['prod'] = $this->products->listOfProduct();  
        $data['class'] = $this->classifications->listOfClassification();        
        $response['newdata_view'] = $this->load->view('conadtypecharges/newdata', $data, true);
        echo json_encode($response);
    }
    
    public function editdata() {
        $id = $this->input->post('id');
        $data['prod'] = $this->products->listOfProduct();  
        $data['class'] = $this->classifications->listOfClassification();
        $data['data'] = $this->model_conadtypecharges->getData($id);
        
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $response['editdata_view'] = $this->load->view('conadtypecharges/editdata', $data, true);
        echo json_encode($response);        
    }
    
    public function save() {
        
        $data['adtypecharges_code'] = $this->input->post('charge_code');
        $data['adtypecharges_name'] = $this->input->post('charge_name');        
        $data['adtypecharges_type'] = $this->input->post('type');        
        $data['adtypecharges_prod'] = $this->input->post('product');        
        $data['adtypecharges_class'] = $this->input->post('class');        
        $data['adtypecharges_startdate'] = $this->input->post('datestart');        
        $data['adtypecharges_enddate'] = $this->input->post('dateend');        
        $data['adtypecharges_amt'] = str_replace(",","",$this->input->post('amount'));
        $data['adtypecharges_rate'] = str_replace(",","",$this->input->post('rate'));
        $data['adtypecharges_sunday'] = $this->input->post('sun');        
        $data['adtypecharges_monday'] = $this->input->post('mon');        
        $data['adtypecharges_tuesday'] = $this->input->post('tue');        
        $data['adtypecharges_wednesday'] = $this->input->post('wed');        
        $data['adtypecharges_thursday'] = $this->input->post('thu');        
        $data['adtypecharges_friday'] = $this->input->post('fri');        
        $data['adtypecharges_saturday'] = $this->input->post('sat');        
        $data['adtypecharges_rank'] = $this->input->post('rank');        
                
        $this->model_conadtypecharges->saveNewData($data);

        $msg = "You successfully save New Charges";

        $this->session->set_flashdata('msg', $msg);

        redirect('conadtypecharges');     
    }
    
    public function removedata($id) {        
        $this->model_conadtypecharges->removeData(abs($id));
        redirect('conadtypecharges');
    }
    
    public function update($id) {        
        
        $data['adtypecharges_code'] = $this->input->post('charge_code');
        $data['adtypecharges_name'] = $this->input->post('charge_name');        
        $data['adtypecharges_type'] = $this->input->post('type');        
        $data['adtypecharges_prod'] = $this->input->post('product');        
        $data['adtypecharges_class'] = $this->input->post('class');        
        $data['adtypecharges_startdate'] = $this->input->post('datestart');        
        $data['adtypecharges_enddate'] = $this->input->post('dateend');        
        $data['adtypecharges_amt'] = str_replace(",","",$this->input->post('amount'));
        $data['adtypecharges_rate'] = str_replace(",","",$this->input->post('rate'));
        $data['adtypecharges_sunday'] = $this->input->post('sun');        
        $data['adtypecharges_monday'] = $this->input->post('mon');        
        $data['adtypecharges_tuesday'] = $this->input->post('tue');        
        $data['adtypecharges_wednesday'] = $this->input->post('wed');        
        $data['adtypecharges_thursday'] = $this->input->post('thu');        
        $data['adtypecharges_friday'] = $this->input->post('fri');        
        $data['adtypecharges_saturday'] = $this->input->post('sat');        
        $data['adtypecharges_rank'] = $this->input->post('rank');                                

        $this->model_conadtypecharges->saveupdateNewData($data, abs($id));                   

        $msg = "You successfully save update Charges";

        $this->session->set_flashdata('msg', $msg);

        redirect('conadtypecharges');     
    }
    
}
