<?php
  
  class Exdeal_bartercondition Extends CI_Controller
  {
      function __construct()
      {
          parent::__construct();
          
          $this->sess = $this->authlib->validate(); 
          
          $this->load->model("model_exdeal_bartercondition/Exdeal_barterconditions");  
          
      }
      
      public function index()
      {
          $navigation['data'] = $this->GlobalModel->moduleList();
          
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
       
          $data['result'] = $this->Exdeal_barterconditions->getAllBarterCondition();                    
               
          $layout['navigation'] = $this->load->view('navigation', $navigation, true);
               
          $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);  
               
          $layout['content'] = $this->load->view('exdeal_bartercondition/index', $data, true);
               
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
                
                $data['result'] = $this->Exdeal_barterconditions->getBarterCondition($data);
                
                $data['action'] = 'update'; 
            }
            
            $html = $this->load->view("exdeal_forms/barter_condition",$data,true);
            
            echo json_encode($html);
        }
        
        public function insert()
        {  
            $data['condition'] = mysql_real_escape_string($this->input->post('condition'));
            
            $data['category'] = mysql_real_escape_string($this->input->post('category'));
             
            $this->Exdeal_barterconditions->insert($data);
           
            $msg = "Condition successfully added.";

            $this->session->set_flashdata('msg', $msg);

            redirect('exdeal_bartercondition');
        }
        
        public function check_condition()
        {
             $this->form_validation->set_rules(
                                            array('condition', 'condition', 'trim|is_unique[exdeal_barter_condtion.barter_condition]'),
                                            array('category', 'category', 'trim|is_unique[exdeal_barter_condtion.category_id]'));
                            
             if ($this->form_validation->run() == FALSE)
            {
              echo "true";
            }                                   
        }
        
        public function update($id)
        {  
            $data['condition'] = mysql_real_escape_string($this->input->post('condition'));
            
            $data['category'] = mysql_real_escape_string($this->input->post('category'));
            
            $data['id'] = $id;      
             
            $this->Exdeal_barterconditions->update($data);
           
            $msg = " ";

            $this->session->set_flashdata('msg', $msg);

            redirect('exdeal_bartercondition');
        }
        
        public function delete($id)
        {
            $this->Exdeal_barterconditions->delete($id);
            
            $msg = "Deleted";
             
            $this->session->set_flashdata('msg', $msg);

            redirect('exdeal_bartercondition');
        }
  }