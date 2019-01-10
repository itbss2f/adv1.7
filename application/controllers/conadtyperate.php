<?php
  
class Conadtyperate extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_conadtyperate/model_conadtyperate', 'model_product/products', 'model_classification/classifications'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['rate'] = $this->model_conadtyperate->list_rate();                   
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                 
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('conadtyperate/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function newdata() {       
        $data['prod'] = $this->products->listOfProduct();  
        $data['class'] = $this->classifications->listOfClassification();        
        $response['newdata_view'] = $this->load->view('conadtyperate/newdata', $data, true);
        echo json_encode($response);
    }
    
    public function editdata() {
        $id = $this->input->post('id');
        $data['prod'] = $this->products->listOfProduct();  
        $data['class'] = $this->classifications->listOfClassification();
        $data['data'] = $this->model_conadtyperate->getData($id);
        
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $response['editdata_view'] = $this->load->view('conadtyperate/editdata', $data, true);
        echo json_encode($response);        
    }
    
    public function save() {
        
        $data['adtyperate_code'] = $this->input->post('rate_code');
        $data['adtyperate_name'] = $this->input->post('rate_name');        
        $data['adtyperate_type'] = $this->input->post('type');        
        $data['adtyperate_prod'] = $this->input->post('product');        
        $data['adtyperate_class'] = $this->input->post('class');        
        $data['adtyperate_startdate'] = $this->input->post('datestart');        
        $data['adtyperate_enddate'] = $this->input->post('dateend');        
        $data['adtyperate_amt'] = str_replace(",","",$this->input->post('amount'));
        $data['adtyperate_rate'] = str_replace(",","",$this->input->post('rate'));
        $data['adtyperate_sunday'] = $this->input->post('sun');        
        $data['adtyperate_monday'] = $this->input->post('mon');        
        $data['adtyperate_tuesday'] = $this->input->post('tue');        
        $data['adtyperate_wednesday'] = $this->input->post('wed');        
        $data['adtyperate_thursday'] = $this->input->post('thu');        
        $data['adtyperate_friday'] = $this->input->post('fri');        
        $data['adtyperate_saturday'] = $this->input->post('sat');                
                
        $this->model_conadtyperate->saveNewData($data);

        $msg = "You successfully save New Rate";

        $this->session->set_flashdata('msg', $msg);

        redirect('conadtyperate');     
    }
    
    public function removedata($id) {        
        $this->model_conadtyperate->removeData(abs($id));
        redirect('conadtyperate');
    }
    
    public function update($id) {        
        
        $data['adtyperate_code'] = $this->input->post('rate_code');
        $data['adtyperate_name'] = $this->input->post('rate_name');        
        $data['adtyperate_type'] = $this->input->post('type');        
        $data['adtyperate_prod'] = $this->input->post('product');        
        $data['adtyperate_class'] = $this->input->post('class');        
        $data['adtyperate_startdate'] = $this->input->post('datestart');        
        $data['adtyperate_enddate'] = $this->input->post('dateend');        
        $data['adtyperate_amt'] = str_replace(",","",$this->input->post('amount'));
        $data['adtyperate_rate'] = str_replace(",","",$this->input->post('rate'));
        $data['adtyperate_sunday'] = $this->input->post('sun');        
        $data['adtyperate_monday'] = $this->input->post('mon');        
        $data['adtyperate_tuesday'] = $this->input->post('tue');        
        $data['adtyperate_wednesday'] = $this->input->post('wed');        
        $data['adtyperate_thursday'] = $this->input->post('thu');        
        $data['adtyperate_friday'] = $this->input->post('fri');        
        $data['adtyperate_saturday'] = $this->input->post('sat'); 

        $this->model_conadtyperate->saveupdateNewData($data, abs($id));                   

        $msg = "You successfully save update Rate";

        $this->session->set_flashdata('msg', $msg);

        redirect('conadtyperate');     
    }
    
}
