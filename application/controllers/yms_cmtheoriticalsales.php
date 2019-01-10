<?php

class YMS_cmtheoriticalsales extends CI_Controller {

    public function __construct() 
    {
        
         parent::__construct();
         $this->sess = $this->authlib->validate(); 
         $this->load->model('model_yms_cmtheoriticalsales/model_yms_cmtheoriticalsales');
         $this->load->model(array('model_yms_edition/model_yms_edition'));      
         
    } 
    
    public function index()
    {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true); 
        $data['list'] = $this->model_yms_cmtheoriticalsales->getData(); 
        $data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();
           #print_r2($data);         exit;
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD'); 
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
                         
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('yms_cmtheoriticalsales/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
           
    }
      public function save() {
        $data['datefrom'] = $this->input->post('datefrom');
        $data['dateto'] = $this->input->post('dateto');        
        $data['product'] = $this->input->post('product');        
        $data['rateamount'] = $this->input->post('rateamount');
        
        #print_r2($data);         exit;
        $this->model_yms_cmtheoriticalsales->saveNewTHEORITICAL_SALES($data);

        $msg = "You successfully save NEW YMS THEORITICAL SALES";

        $this->session->set_flashdata('msg', $msg);

        redirect('yms_cmtheoriticalsales'); 
    }

    
    public function newdata() {
        $data['product'] = $this->model_yms_edition->listYMS_Edition(); 
        
        $response['newdata_view'] = $this->load->view('yms_cmtheoriticalsales/newdata', $data, true);
        echo json_encode($response);
    } 
    
     public function editdata() {
        $id = $this->input->post('id');
        $data['product'] = $this->model_yms_edition->listYMS_Edition();        
        $data['data'] =  $this->model_yms_cmtheoriticalsales->getThisData($id);         
        $response['editdata_view'] = $this->load->view('yms_cmtheoriticalsales/editdata', $data, true);
        echo json_encode($response);
    } 
    
    public function update($id) {
        //echo $id; 
        $data['datefrom'] = $this->input->post('datefrom');
        $data['dateto'] = $this->input->post('dateto');        
        $data['product'] = $this->input->post('product');        
        $data['rateamount'] = $this->input->post('rateamount');
        
        //print_r2($data);         exit;
        $this->model_yms_cmtheoriticalsales->saveupdateNewData($data, abs($id));
        
         $msg = "You successfully update YMS THEORITICAL SALES";

        $this->session->set_flashdata('msg', $msg);

        redirect('yms_cmtheoriticalsales'); 
        
    }
    
     public function removeData($id) {        
        $this->model_yms_cmtheoriticalsales->removeData(abs($id));
        redirect('yms_cmtheoriticalsales');
    }
    

    
}
?>