<?php
    
class Exdeal_parameterfile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->sess = $this->authlib->validate();

        $this->load->model('model_exdeal_parameterfile/Exdeal_parameterfiles');
    }
    
    public function index()
    {
       $navigation['data'] = $this->GlobalModel->moduleList();
       
       $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
       
       $data['result'] = $this->Exdeal_parameterfiles->getall();                    
       
       $layout['navigation'] = $this->load->view('navigation', $navigation, true);
       
       $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);  
       
       $layout['content'] = $this->load->view('exdeal_parameterfile/index', $data, true);
       
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
            
            $data['result'] = $this->Exdeal_parameterfiles->get($data);
            
            $data['action'] = 'update'; 
        }
        
        $html = $this->load->view("exdeal_forms/parameterfile",$data,true);
        
        echo json_encode($html);
    }
    
    
    public function validateCode() 
    {        
        $this->form_validation->set_rules('company_code', 'Code', 'trim|is_unique[exdeal_parameterfile.company_code]');
      
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }

    public function insert()
    {  
        $data['company_code'] = $this->input->post('company_code');
        
        $data['company_name'] = $this->input->post('company_name');
        
        $data['recommended_by'] = $this->input->post('recommended_by');
        
        $data['rec_position'] = $this->input->post('rec_position');
        
        $data['noted_by'] = $this->input->post('noted_by');
        
        $data['not_position'] = $this->input->post('not_position');
        
        $data['noted_by'] = $this->input->post('noted_by');
        
        $data['recommended_by2'] = $this->input->post('recommended_by2');
        
        $data['rec_position2'] = $this->input->post('rec_position2');
        
        $data['noted_by2'] = $this->input->post('noted_by2');
        
        $data['not_position2'] = $this->input->post('not_position2');
   
        $data['approved_by'] = $this->input->post('approved_by');
        
        $data['app_position'] = $this->input->post('app_position');
        
        $data['b_last_contract_no'] = $this->input->post('b_last_contract_no');
        
        $data['n_last_contract_no'] = $this->input->post('n_last_contract_no');
        
        $this->Exdeal_parameterfiles->insert($data);
       
        $msg = "Parameter file successfully added.";

        $this->session->set_flashdata('msg', $msg);

        redirect('exdeal_parameterfile');
    }
  
    public function update($id)
    {  
        $data['id'] = $id;
        
        $data['company_code'] = $this->input->post('company_code');
        
        $data['company_name'] = $this->input->post('company_name');
        
        $data['recommended_by'] = $this->input->post('recommended_by');
        
        $data['rec_position'] = $this->input->post('rec_position');
        
        $data['noted_by'] = $this->input->post('noted_by');
        
        $data['not_position'] = $this->input->post('not_position');
        
        $data['recommended_by2'] = $this->input->post('recommended_by2');
        
        $data['rec_position2'] = $this->input->post('rec_position2');
        
        $data['noted_by2'] = $this->input->post('noted_by2');
        
        $data['not_position2'] = $this->input->post('not_position2');
        
        $data['noted_by'] = $this->input->post('noted_by');
   
        $data['approved_by'] = $this->input->post('approved_by');
        
        $data['app_position'] = $this->input->post('app_position');
        
        $data['b_last_contract_no'] = $this->input->post('b_last_contract_no');
        
        $data['n_last_contract_no'] = $this->input->post('n_last_contract_no');
        
        $this->Exdeal_parameterfiles->update($data);
        
        $msg = "Parameter file successfully updated";
         
        $this->session->set_flashdata('msg', $msg);

        redirect('exdeal_parameterfile');
    }
    
    public function delete($id)
    {
        $this->Exdeal_parameterfiles->delete($id);
        
        $msg = "Parameter file successfully deleted";
         
        $this->session->set_flashdata('msg', $msg);

        redirect('exdeal_parameterfile');
    }
    
}
