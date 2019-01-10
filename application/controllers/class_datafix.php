<?php

class Class_datafix extends CI_Controller {
    
     public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model('model_classdatafix/mod_classdatafix');
        $this->load->model('model_classification/classifications');       
        
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
          
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('class_datafix/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function searchaonum() {
        $aonum = $this->input->post('aonum');

        $data['list'] = $this->mod_classdatafix->searchAO($aonum);
        
        $response['result'] = $this->load->view('class_datafix/result', $data, true);
        
        echo json_encode($response);
    }
    
  
    
    public function editdata() {
        $id = $this->input->post('id');  
        $data['id'] = $id; 
        $data['class'] = $this->classifications->listOfClassificationPerType('C');          
        $data['subclass'] = $this->classifications->listOfSubClassificationType();      
        $data['data'] = $this->mod_classdatafix->getData($id);
        $response['editdata_view'] = $this->load->view('class_datafix/editdata_view', $data, true);      
        echo json_encode($response);        
    }
    
    public function saveDatafix() {
        $id = $this->input->post('id');
        $data['ao_issuefrom'] = $this->input->post('issuedate');
        $data['ao_width'] = $this->input->post('wid');
        $data['ao_length'] = $this->input->post('len');
        $data['ao_class'] = $this->input->post('clas');
        $data['ao_subclass'] = $this->input->post('sclas');
        $data['ao_totalsize'] = $data['ao_width'] *  $data['ao_length'];
        
        $this->mod_classdatafix->saveDatafix($id, $data);
        $aonum = $this->input->post('aonum');      
        
        $data2['ao_payee'] = $this->input->post('payeename');
        
        $this->mod_classdatafix->saveDatafixM($aonum, $data2);    

        $data['list'] = $this->mod_classdatafix->searchAO($aonum);
        
        $response['result'] = $this->load->view('class_datafix/result', $data, true);
        
        echo json_encode($response);  
        
    }
    
    
     /* public function editdata_class() {
        $data['id'] = $this->input->post('id');    
        $data['classid'] = $this->input->post('classid'); 
        $data['class'] = $this->classifications->listOfClassificationPerType('C');          

        $response['editdataclass_view'] = $this->load->view('class_datafix/class_view', $data, true);
        
        echo json_encode($response);
    }*/
    /*public function tempupdateclass() {
        $id = $this->input->post('pid');     
        $nclass = $this->input->post('nclass');  
        
        $this->mod_classdatafix->updateDatafix_class($id, $nclass);  
        
        $aonum = $this->input->post('aonum');
        
        $data['list'] = $this->mod_classdatafix->searchAO($aonum);
        
        $response['result'] = $this->load->view('class_datafix/result', $data, true);
        
        echo json_encode($response); 
    } */
    
    /*public function editdata_subclass() {
        $data['id'] = $this->input->post('id');    
        $data['subclassid'] = $this->input->post('subclassid'); 
        $data['subclass'] = $this->classifications->listOfSubClassificationType();      

        $response['editdatasubclass_view'] = $this->load->view('class_datafix/subclass_view', $data, true);
        
        echo json_encode($response);
    }*/    
    
    /*public function tempupdatesubclass() {
        $id = $this->input->post('pid');     
        $nclass = $this->input->post('nclass');  
        
        $this->mod_classdatafix->updateDatafix_subclass($id, $nclass);  
        
        $aonum = $this->input->post('aonum');
        
        $data['list'] = $this->mod_classdatafix->searchAO($aonum);
        
        $response['result'] = $this->load->view('class_datafix/result', $data, true);
        
        echo json_encode($response); 
    } */
}      

?>
