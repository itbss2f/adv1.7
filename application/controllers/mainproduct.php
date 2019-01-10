<?php

class Mainproduct extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_mainproduct/mainproducts');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['mainproduct'] = $this->mainproducts->listOfMainProduct();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');  
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('mainproducts/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['mprod_code'] = $this->input->post('mprod_code');
        $data['mprod_name'] = $this->input->post('mprod_name');        
        $this->mainproducts->saveNewData($data);

        $msg = "You successfully save Main Product";

        $this->session->set_flashdata('msg', $msg);

        redirect('mainproduct'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('mainproducts/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[mismprodcms.mprod_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->mainproducts->getData($id);        
        $response['editdata_view'] = $this->load->view('mainproducts/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['mprod_code'] = $this->input->post('mprod_code');
        $data['mprod_name'] = $this->input->post('mprod_name');  
        
        $this->mainproducts->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Main Product";

        $this->session->set_flashdata('msg', $msg);

        redirect('mainproduct');       
    }
    
    public function removedata($id) {        
        $this->mainproducts->removeData(abs($id));
        redirect('mainproduct');
    }
    
     public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('mainproducts/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');  
        
        $searchkey['mprod_code'] = $this->input->post('mprod_code');
        
        $searchkey['mprod_name'] = $this->input->post('mprod_name');
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['mainproduct'] = $this->mainproducts->searched($searchkey);         
        
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('mainproducts/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);
    }
}
