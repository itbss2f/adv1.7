<?php

Class Exdeal_inquiry Extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();     
        
        $this->load->model('model_exdeal_inquiry/Exdeal_inquiries');
    }
    
    public function index()
    {
       $navigation['data'] = $this->GlobalModel->moduleList();
      
       $layout['navigation'] = $this->load->view('navigation', $navigation, true);
       
       $layout['content'] = $this->load->view('exdeal_inquiry/index', null, true);
       
       $this->load->view('welcome_index', $layout);
    }
    
    public function generate()
    {
       $data = array(
                'from_date'=>$this->input->post('from_date'),
                'to_date' => $this->input->post('to_date'),
                'status' => $this->input->post('status'),
                'inquiry_type' => $this->input->post('inquiry_type'),
                'from_select' => $this->input->post('from_select'),
                'to_select' => $this->input->post('to_select'),
                'po_number' => $this->input->post('po_number'),
                'filter_type' => $this->input->post('filter_type')
                );
                
         $inquiry_type =  $this->input->post('inquiry_type');      
                
         $data['result'] = $this->Exdeal_inquiries->billable($data); 
                
         $response['header'] = $this->load->view("exdeal_inquiry/header/billable",null,true);
            
         $response['result'] = $this->load->view("exdeal_inquiry/billable",$data,true);
              
         echo json_encode($response);      
    }
    
    public function getinquirydata()
    {
       $data['invoice_no'] = $this->input->post('id');
       
       $data['from_date'] = $this->input->post('from_date');
       
       $data['to_date'] = $this->input->post('to_date');
       
       $data['result'] = $this->Exdeal_inquiries->getinquirydata($data);       
       
       $html = $this->load->view('exdeal_inquiry/inquiry_data',$data,true);   
       
       echo json_encode($html);         
    }
    
    public function update_inquiry()
    {
        $data = array(
                    'trigger_box'=>$this->input->post('trigger_box'),
                    'ao_id'=>$this->input->post('ao_id'),
                    'exdeal_date'=>$this->input->post('exdeal_date'),
                    'amount'=>$this->input->post('amount'),
                    'exdeal_status'=>$this->input->post('exdeal_status'),
                    'exdeal_percent'=>$this->input->post('exdeal_percent'),
                    'exdeal_amount'=>str_replace(',','',$this->input->post('exdeal_amount')),
                    'contract_no'=>$this->input->post('contract_no'),
                    'exdeal_cash'=>$this->input->post('exdeal_cash'),
                    'ao_ref'=>$this->input->post('ao_ref'),
                    'exdeal_remarks'=>$this->input->post('exdeal_remarks')
                    );
                    
        $this->Exdeal_inquiries->update_inquiry($data);            
    }
    
    public function createFilter()
    {
          $data['search'] = $this->input->post('search');
          
          $data['filter_type'] = $this->input->post('filter_type');
          
          $data['parent_val'] = $this->input->post('parent_val'); 
          
          $action = $data['filter_type']; 
          
          $array = array("agency_client","client_agency");
          
          if(in_array($data['filter_type'],$array) and !empty($data['parent_val']))
          {
               $action = $data['filter_type']."_chain";
          }
          
          $result  = $this->Exdeal_inquiries->$action($data);
            
          $entries = array();
          
          if($result != null)
          {
            foreach($result as $result)
              {
                  array_push($entries,  array('id'=>$result->id,'label'=>$result->cmf_name,'value'=>$result->cmf_name) );
              }  
          }
          
          else
          {
            array_push($entries,  array('id'=>'','label'=>'No Results Found','value'=>'') );
          }
         
          echo json_encode($entries);
    }
    
    
    
    public function filters()
    {
       $html = $this->load->view('exdeal_inquiry/filter',null,true);
       
       echo json_encode($html); 
    }
    
    public function filter_type()
    {
         $filter_type = $this->input->post('filter_type');
         
         $result = $this->Exdeal_inquiries->$filter_type();  
          
         echo json_encode($result);      
    }
    
    public function filter_chain()
    {
       $filter_type = $this->input->post('filter_type')."_chain";
       
       $data['filter']= $this->input->post('filter');
         
       $result = $this->Exdeal_inquiries->$filter_type($data);  
          
       echo json_encode($result); 
    }
    
    public function exportselection()
    {
        $html = $this->load->view("exdeal_export/selection",null,true);
        
        echo json_encode($html);
    }
    
    public function selectheader()
    {
        $header = $this->input->post("header");
       
        $html = $this->load->view("exdeal_inquiry/header/".$header,null,true);
        
        echo json_encode($html);
    }
}
