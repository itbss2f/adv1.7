<?php
class Tpa extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model('model_tpa/tpas');
        $this->load->model('model_booking/bookingissuemodel');
    }
    
    
    public function index() {    

        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['tpa'] = $this->tpas->listOfTPA(); 
                
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
          
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('tpa/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }

    public function save() {

        $data['code'] = strtoupper($this->input->post('code'));
        $data['name'] = strtoupper($this->input->post('name')); 
          
        $this->tpas->saveNewData($data);

        $msg = "You successfully save";

        $this->session->set_flashdata('msg', $msg);

        redirect('tpa'); 
    }
    
    public function newdata() {        

        $response['newdata_view'] = $this->load->view('tpa/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {

        $this->form_validation->set_rules('name', 'Name', 'trim|is_unique[mistpa.name]');

        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->tpas->getData($id);        
        $response['editdata_view'] = $this->load->view('tpa/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {

        $data['code'] = strtoupper($this->input->post('code'));
        $data['name'] = strtoupper($this->input->post('name')); 
        
        $this->tpas->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update";

        $this->session->set_flashdata('msg', $msg);

        redirect('tpa');       
    }
    
    public function removedata($id) {       

        $this->tpas->removeData(abs($id));

        $msg = "You successfully remove";

        $this->session->set_flashdata('msg', $msg);

        redirect('tpa');
    }
    
    public function searchdata() {  

        $response['searchdata_view'] = $this->load->view('tpa/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search(){

            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
            $searchkey['code'] = $this->input->post('code');
                
            $searchkey['name'] = $this->input->post('name');
            
            $data['tpa'] = $this->tpas->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('tpa/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }

}
