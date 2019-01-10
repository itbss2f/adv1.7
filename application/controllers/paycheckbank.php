<?php
  
  class Paycheckbank extends CI_Controller
  {
      public function __construct()
      {
          parent::__construct();
          
          $this->sess = $this->authlib->validate(); 
          
          $this->load->model("model_paycheckbank/Paycheckbanks");
      }
      
      public function index()
      {
         $navigation['data'] = $this->GlobalModel->moduleList();  
         
         $data['bank'] = $this->Paycheckbanks->listOfBank();  
                
         #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition(); 
         
         $data['canADD'] = $this->GlobalModel->moduleFunction("paycheckbank", 'ADD');                  
         $data['canEDIT'] = $this->GlobalModel->moduleFunction("paycheckbank", 'EDIT');                  
         $data['canDELETE'] = $this->GlobalModel->moduleFunction("paycheckbank", 'DELETE');                  
            
         $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
         
         $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
         
         $welcome_layout['content'] = $this->load->view('paycheckbanks/index', $data, true);
         
         $this->load->view('welcome_index', $welcome_layout);
      }
      
      public function newdata()
      {        
        $response['newdata_view'] = $this->load->view('paycheckbanks/newdata', null, true);
        
        echo json_encode($response);
      }
      
      public function save() 
      {
        $data['bmf_code'] = $this->input->post('bmf_code');
        
        $data['bmf_name'] = $this->input->post('bmf_name'); 
               
        $this->Paycheckbanks->saveNewData($data);

        $msg = "You successfully save Bank";

        $this->session->set_flashdata('msg', $msg);

        redirect('paycheckbank'); 
     }
     
      public function validateCode()
      {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[mispaycheckbank.bmf_code]');
        
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
     }
    
    public function editdata()
    {
        $id = $this->input->post('id');
        
        $data['data'] = $this->Paycheckbanks->getData($id);        
        
        $response['editdata_view'] = $this->load->view('paycheckbanks/editdata', $data, true);
        
        echo json_encode($response);
    }
    
     public function update($id) 
     {
         
        $data['bmf_name'] = $this->input->post('bmf_name');  
        
        $this->Paycheckbanks->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Bank";

        $this->session->set_flashdata('msg', $msg);

        redirect('paycheckbank');       
    }
    
    public function removedata($id) {      
      
        $this->Paycheckbanks->removeData(abs($id));
        
        redirect('paycheckbank');
    }
    
    public function searchdata() {                       
         
        $response['searchdata_view'] = $this->load->view('paycheckbanks/searchdata', null, true);
        
        echo json_encode($response);
    }
    
    public function search()
    {
            $searchkey['bmf_code'] = $this->input->post('bmf_code');
        
            $searchkey['bmf_name'] = $this->input->post('bmf_name');    
    
            $data['bank'] = $this->Paycheckbanks->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            
            $data['canADD'] = $this->GlobalModel->moduleFunction("paycheckbank", 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction("paycheckbank", 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction("paycheckbank", 'DELETE');   
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('paycheckbanks/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
  }
