<?php

class Dummy_util extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_deskreport/mod_deskreport', 'model_product/products'));  
    }
    
    public function index() 
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['prod'] = $this->products->listOfProduct();
          
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('dummy_util/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }  

    
    public function searchHiddenIssueDate() {
        $issuedate = $this->input->post('issuedate');
        $prod = $this->input->post('prod');
        $aonum = $this->input->post('aonum');
        
        $data['list'] = $this->mod_deskreport->getDummyList($issuedate, $prod, abs($aonum));  
        #print_r2($dataload['dlist']) ; exit;
        
       
        $response['result2'] = $this->load->view('dummy_util/result2', $data, true);
        
        echo json_encode($response);    
    } 
    
    public function searchIssueDate() {
        $issuedate = $this->input->post('issuedate');
        $prod = $this->input->post('prod');
        $aonum = $this->input->post('aonum');
        $bookingtype = $this->input->post('bookingtype');
        
        $data['list'] = $this->mod_deskreport->getDummyAO($issuedate, $prod, abs($aonum), $bookingtype);  
        #print_r2( $data['list']) ; exit;
        
       
        $response['result'] = $this->load->view('dummy_util/result', $data, true);
        
        echo json_encode($response);    
    } 
    
    public function unflowBox() {
        $id = $this->input->post('id'); 
        $issuedate = $this->input->post('issuedate');
        $prod = $this->input->post('prod'); 
        
        $this->mod_deskreport->unflowThisBox($id);
        
        $data['list'] = $this->mod_deskreport->getDummyAO($issuedate, $prod);  
        
       
        $response['result'] = $this->load->view('dummy_util/result', $data, true);

        
        echo json_encode($response);        
        
    } 


    public function restored_ads() {

        $issuedate = $this->input->post('issuedate');
        $prod = $this->input->post('prod');
        $aonum = $this->input->post('aonum');

        $this->mod_deskreport->restoredThisAds($issuedate);

        $data['list'] = $this->mod_deskreport->getDummyList($issuedate, $prod, abs($aonum));  

        $response['result2'] = $this->load->view('dummy_util/result2', $data, true);
        
        echo json_encode($response);     


    }
    
}


?>
