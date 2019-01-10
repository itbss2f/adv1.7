<?php

    class Exdeal_contactperson extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            
            $this->sess = $this->authlib->validate();     
            
            $this->load->model(array("model_exdeal_contactperson/Exdeal_contactpersons"));
           
        }
        
        public function index()
        {
             $navigation['data'] = $this->GlobalModel->moduleList();
             
             
             $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
             $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
             $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
       
             $data['result'] = $this->Exdeal_contactpersons->getContacPersons();                    
               
             $layout['navigation'] = $this->load->view('navigation', $navigation, true);
               
             $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);  
               
             $layout['content'] = $this->load->view('exdeal_contactperson/index', $data, true);
               
             $this->load->view('welcome_index', $layout);
        }
        
        public function openform()
       {
            $data['result'] = null;
            
            $data['id']  = "";
            
            $action  = $this->input->post('action');  
            
            $data['action'] = 'insert';
            
            if($action == 'update')
            {
                $data['id']     = $this->input->post('id');
                
                $data['result'] = $this->Exdeal_contactpersons->get($data);
                
                $data['action'] = 'update'; 
            }
            
            $html = $this->load->view("exdeal_forms/contact_person",$data,true);
            
            echo json_encode($html);
        }
        
        public function insert()
        {  
            $data['contact_person'] = $this->input->post('contact_person');
            
            $data['company_name'] = $this->input->post('company_name');
            
            $data['designation'] = $this->input->post('designation');
            
            $data['contact_no'] = $this->input->post('contact_no');
            
            $data['fax_no'] = $this->input->post('fax_no');
            
            $data['email'] = $this->input->post('email');
            
            $this->Exdeal_contactpersons->insert($data);
           
            $msg = "Contact Person successfully added.";

            $this->session->set_flashdata('msg', $msg);

            redirect('exdeal_contactperson');
        }
        
        
        public function update($id)
        {  
            $data['id'] = $id;
        
            $data['contact_person'] = $this->input->post('contact_person');
            
            $data['company_name'] = $this->input->post('company_name');
            
            $data['designation'] = $this->input->post('designation');
            
            $data['contact_no'] = $this->input->post('contact_no');
            
            $data['fax_no'] = $this->input->post('fax_no');
            
            $data['email'] = $this->input->post('email');
            
            $this->Exdeal_contactpersons->update($data);
           
            $msg = "Contact Person successfully updated.";

            $this->session->set_flashdata('msg', $msg);

            redirect('exdeal_contactperson');
        
        }
        
         public function check_contactperson() 
        {        
            $this->form_validation->set_rules('contact_person', 'Code', 'trim|is_unique[exdeal_contact_person.contact_person]');
          
            if ($this->form_validation->run() == FALSE)
            {
              echo "true";
            }        
        }
        
        public function delete($id)
        {
            $this->Exdeal_contactpersons->delete($id);
            
            $msg = "Contact person successfully deleted";
             
            $this->session->set_flashdata('msg', $msg);

            redirect('exdeal_contactperson');
        }
    }