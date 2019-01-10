<?php
class Ppdreport extends CI_Controller
{
     public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_ppd/ppds');
    }
    
    public function index()
    {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('ppds/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generateReport()
    {
        $data['from_agency'] = $this->input->post('from_agency');
        $data['to_agency'] = $this->input->post('to_agency');
        $data['from_date'] = $this->input->post('from_date');
        $data['to_date'] = $this->input->post('to_date');
        $data['result'] = $this->ppds->retrievePPD($data); 
        $html = $this->load->view('ppds/report',$data,true);
        echo json_encode($html);
    }
    
    public function autocomplete()
    {
        
       $data['search'] = $this->input->post('search'); 
       $result = $this->ppds->autocomplete($data);       
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
       $html = $this->load->view('ppds/filter',null,true);
       
       echo json_encode($html); 
    }
   
}