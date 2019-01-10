<?php
class Exdeal_docreq Extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();

        $this->load->model('model_exdeal_docreq/Exdeal_docreqs');
    }
    
    public function index()
    {
       $data['result'] = $this->Exdeal_docreqs->getdocreqs();
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
        
       $navigation['data'] = $this->GlobalModel->moduleList();
    
       $layout['navigation'] = $this->load->view('navigation', $navigation, true);
       
       $layout['content'] = $this->load->view('exdeal_docreq/index', $data, true);
       
       $this->load->view('welcome_index', $layout);
    }
    
    public function openform()
    {
       $data['action'] = $this->input->post('action'); 
       
       $data['id'] = $this->input->post('id'); 
       
       if($data['action'] == 'update')
       {
           $data['result']  = $this->Exdeal_docreqs->selectdocreq($data);
       }
        
       $html = $this->load->view('exdeal_forms/docreq',$data,true); 
       
       echo json_encode($html);
    }
    
    public function save()
    {
        $data['doc_name'] = $this->input->post('doc_name');
        
        $this->Exdeal_docreqs->save($data);
    }
    
    public function update()
    {
        $data['doc_name'] = $this->input->post('doc_name');  
        
        $data['id'] = $this->input->post('t_id'); 
        
        $this->Exdeal_docreqs->update($data);
    }
    
    public function delete()
    {
        $id = $this->input->post('id');
        
        $this->Exdeal_docreqs->delete($id);
    }
    
    public function refresh()
    {
        $data['result'] = $this->Exdeal_docreqs->getdocreqs();
        
        $html = $this->load->view('exdeal_docreq/refresh', $data, true);
        
        echo json_encode($html);
    }
}