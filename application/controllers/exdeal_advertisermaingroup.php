<?php
class Exdeal_advertisermaingroup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();   
        
        $this->load->model("model_exdeal_advertisermaingroup/Exdeal_advertisingmaingroups");
    }
    
    public function index()
    {
       $navigation['data'] = $this->GlobalModel->moduleList();
       
       
       $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
       #$data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
       #$data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
      
       $layout['navigation'] = $this->load->view('navigation', $navigation, true);
       
       $data['advertiser'] = $this->Exdeal_advertisingmaingroups->list_of_free_customer();
       
       $data['advertiser_group'] = $this->Exdeal_advertisingmaingroups->getalladvertisergroup();
    
       $layout['content'] = $this->load->view('exdeal_advertisermaingroup/index', $data, true);
       
       $this->load->view('welcome_index', $layout);
    }
    
    public function openform()
    {
        $data['action'] = $this->input->post('action');
        
        if($data['action'] =='update')
        {
            
            $data['id'] = $this->input->post('id');
            
            $data['result'] = $this->Exdeal_advertisingmaingroups->getadvertisergroup($data['id']);
        }
        
        $html = $this->load->view("exdeal_forms/advertisermaingroup",$data,true);
        
        echo json_encode($html);
    } 
    
    public function validateCode() 
    {        
        $this->form_validation->set_rules('group_name', 'Code', 'trim|is_unique[exdeal_advertisergroup.group_name]');
      
        if ($this->form_validation->run() == FALSE)
        {
          echo 'true';
        }        
    }
    
    public function more_advertiser()
    {
        $data['cmf_name'] = $this->input->post('advertiser');
        
        $data['advertiser'] = $this->Exdeal_advertisingmaingroups->more_customer($data);
        
        $html = $this->load->view('exdeal_advertisermaingroup/more_advertiser',$data,true);
        
        echo json_encode($html);
    }

    public function insertintogrouplist()
    {
        $data = array(
        
                 'group_id'=> $this->input->post('group_id'),
                 
                 'advertiser_id'=> $this->input->post('advertiser_id')
             );
             
        $this->Exdeal_advertisingmaingroups->insertintogrouplist($data);
        
        $group_id = $this->input->post('group_id');
        
        $this->callassignedadvertiser($group_id);   
    }
    
    public function getassignedadvertiser()
    {
        $group_id = $this->input->post('id');
        
        $this->callassignedadvertiser($group_id);  
    }
    
    public function removeassignedadvertiser()
    {
        $id = $this->input->post('id');
        
        $group_id = $this->input->post('group_id');
        
        $this->Exdeal_advertisingmaingroups->removeassignedadvertiser($id);
        
        $data1['advertiser'] = $this->Exdeal_advertisingmaingroups->list_of_free_customer();
         
        $data['result'] = $this->Exdeal_advertisingmaingroups->getassignedadvertiser($group_id);
        
        $html = array(
                      'assigned_advertisers'=> $this->load->view('exdeal_advertisermaingroup/assignedadvertisers',$data,true),
                      'unassigned_advertisers'=>$this->load->view('exdeal_advertisermaingroup/unassignedadvertisers',$data1,true)
                    );   
        
        echo json_encode($html);
    }
    
    public function callassignedadvertiser($group_id)
    {         
        if(!empty($group_id))
        {
            $data['result'] = $this->Exdeal_advertisingmaingroups->getassignedadvertiserlist($group_id);
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
            $html = $this->load->view('exdeal_advertisermaingroup/assignedadvertisers',$data,true);   
            
            echo json_encode($html);  
        }
   
    }
    
    public function insert()
    {
        $data = array(
                 'group_name'=>$this->input->post('group_name'),
                 'advertiser'=>$this->input->post('advertiser'),
                 'credit_limit'=>str_replace(',','',$this->input->post('credit_limit')), 
         //        'address'=>$this->input->post('address'),
                 'contact_person'=>$this->input->post('contact_person'),
                 'telephone'=>$this->input->post('telephone')
                );
                
         $result =  $this->Exdeal_advertisingmaingroups->insert($data);  
         
         echo json_encode($result);     
    }
    
    public function update()
    {
        $data = array(
                 'group_name'=>$this->input->post('group_name'),
                 'advertiser'=>$this->input->post('advertiser'),
                 'credit_limit'=>str_replace(',','',$this->input->post('credit_limit')),
             //    'address'=>$this->input->post('address'),
                 'contact_person'=>$this->input->post('contact_person'),
                 'telephone'=>$this->input->post('telephone'),
                 'id'=>$this->input->post('group_id'),
                );
                
         $this->Exdeal_advertisingmaingroups->update($data); 
    } 
    
    public function remove()
    {
        $id = $this->input->post('id');
        
        $this->Exdeal_advertisingmaingroups->remove($id);
    }
    
    public function getadvertiserlist()
    {
        $data['free_client'] = $this->Exdeal_advertisingmaingroups->list_of_free_customer();
        
        $data['group_client'] = $this->Exdeal_advertisingmaingroups->list_of_grouped_customer();
    }
    
    public function searchadvertiser()
    {
        $data['search'] = $this->input->post('search');
        $data['adv'] = $this->input->post('adv');
        
        $data['advertiser'] = $this->Exdeal_advertisingmaingroups->search_free_customer($data);
        
        $html = $this->load->view('exdeal_advertisermaingroup/search',$data,true);
        
        echo json_encode($html);
        
    }
    
    public function checkmaingroup()
    {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[exdeal_advertisergroup.group_name]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }          
    }
    
   
    
}
