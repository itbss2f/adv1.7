<?php

class Paycheckbankbranch extends CI_Controller
{
     
    public function __construct()
    {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
         $this->load->model(array('model_paycheckbankbranch/paycheckbankbranches', 'model_paycheckbank/paycheckbanks'));
    }
    
    public function index()
    {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['bankbranch'] = $this->paycheckbankbranches->listOfBankBranch();   
              
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition(); 
        
        $data['canADD'] = $this->GlobalModel->moduleFunction("paycheckbankbranch", 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction("paycheckbankbranch", 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction("paycheckbankbranch", 'DELETE');                  
           
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('paycheckbankbranches/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);
        
    }
    
     public function save() 
     {
         
        $data['bbf_bank'] = $this->input->post('bbf_bank');
        
        $data['bbf_bnch'] = $this->input->post('bbf_bnch');
        
        $data['bbf_add1'] = $this->input->post('bbf_add1');
          
        $data['bbf_tel1'] = $this->input->post('bbf_tel1'); 
        
        $data['bbf_name'] = $this->input->post('bbf_name'); 
            
        $this->paycheckbankbranches->saveNewData($data);

        $msg = "You successfully save Bank Branch";

        $this->session->set_flashdata('msg', $msg);

        redirect('paycheckbankbranch'); 
    }
    
    public function newdata() 
    {        
        $data['bank'] = $this->paycheckbanks->listOfBank();
        
        $response['newdata_view'] = $this->load->view('paycheckbankbranches/newdata', $data, true);
        
        echo json_encode($response);
    }
    
    public function validateCode() 
    {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[mispaycheckbankbranch.bbf_bank]');
        
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() 
    {
        $data['bank'] = $this->paycheckbanks->listOfBank();
        
        $response['newdata_view'] = $this->load->view('paycheckbankbranches/newdata', $data, true);
        
        $id = $this->input->post('id');
        
        $data['data'] = $this->paycheckbankbranches->getData($id);    
            
        $response['editdata_view'] = $this->load->view('paycheckbankbranches/editdata', $data, true);
        
        echo json_encode($response);
    }
    
    public function update($id) 
    {
        $data['bbf_bank'] = $this->input->post('bbf_bank');
        
        $data['bbf_bnch'] = $this->input->post('bbf_bnch');
        
        $data['bbf_add1'] = $this->input->post('bbf_add1'); 
             
        $data['bbf_tel1'] = $this->input->post('bbf_tel1');
        
        $data['bbf_name'] = $this->input->post('bbf_name'); 
           
        $this->paycheckbankbranches->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Bank Branch";

        $this->session->set_flashdata('msg', $msg);

        redirect('paycheckbankbranch');       
    }
    
    public function removedata($id) 
    {        
        $this->paycheckbankbranches->removeData(abs($id));
        
        redirect('paycheckbankbranch');
    }
    
     public function searchdata() 
     {        
        $data['bank'] = $this->paycheckbanks->listOfBank();
        
        $response['searchdata_view'] = $this->load->view('paycheckbankbranches/searchdata', $data, true);
        
        echo json_encode($response);
    }
    
    public function search()
    {
            $searchkey['bbf_bnch'] = $this->input->post('bbf_bnch');
        
            $searchkey['bbf_bank'] = $this->input->post('bbf_bank');
            
            $searchkey['bbf_name'] = $this->input->post('bbf_name');
        
            $searchkey['bbf_tel1'] = $this->input->post('bbf_tel1');
            
            $searchkey['bbf_add1'] = $this->input->post('bbf_add1');
    
            $data['bankbranch'] = $this->paycheckbankbranches->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $data['canADD'] = $this->GlobalModel->moduleFunction("paycheckbankbranch", 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction("paycheckbankbranch", 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction("paycheckbankbranch", 'DELETE');                  
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('paycheckbankbranches/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
    
}
