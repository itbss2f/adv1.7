<?php

class Aosubtype extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_aosubtype/aosubtypes');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['aosubtype'] = $this->aosubtypes->listOfAOSubType();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('aosubtypes/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['aosubtype_code'] = $this->input->post('aosubtype_code');
        $data['aosubtype_name'] = $this->input->post('aosubtype_name');        
        $data['aosubtype_spontag'] = $this->input->post('sponsor');        
        $this->aosubtypes->saveNewData($data);

        $msg = "You successfully save Ao Subtype";

        $this->session->set_flashdata('msg', $msg);

        redirect('aosubtype'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('aosubtypes/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misaosubtype.aosubtype_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->aosubtypes->getData($id);        
        $response['editdata_view'] = $this->load->view('aosubtypes/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['aosubtype_name'] = $this->input->post('aosubtype_name');  
        $data['aosubtype_spontag'] = $this->input->post('sponsor');        
        
        $this->aosubtypes->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Ao Subtype";

        $this->session->set_flashdata('msg', $msg);

        redirect('aosubtype');       
    }
    
    public function removedata($id) {        
        $this->aosubtypes->removeData(abs($id));
        redirect('aosubtype');
    }
    
    public function searchdata()
    {                
        $response['searchdata_view'] = $this->load->view('aosubtypes/searchdata', null, true);
        echo json_encode($response);   
    }
    
    public function search()
    {
            $searchkey['aosubtype_code'] = $this->input->post('aosubtype_code');
                
            $searchkey['aosubtype_name'] = $this->input->post('aosubtype_name');
            
            $data['aosubtype'] = $this->aosubtypes->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('aosubtypes/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);

    }
}
