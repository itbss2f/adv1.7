<?php

class ORPosting extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model('model_payment/payments');
        #$this->load->model('model_booking/bookingissuemodel');
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('orposting/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);    
    } 
    
    public function postthisor() {
        $datefrom = $this->input->post('fromdate');
        $todate = $this->input->post('todate');
        
        $data['list'] = $this->payments->postOR($datefrom, $todate);
        
        $response['viewresult'] = $this->load->view('orposting/viewresult', $data, true);
         
        echo json_encode($response); 
    }
    
    public function postthissingleor() {
        $ornum = $this->input->post('ornum');         
        
        $data = $this->payments->postthissingleor($ornum);

        $status_n = $this->session->userdata('authsess')->sess_id;  
    
    
        if ($data['status'] == 'O') {
            $response['msg'] = "This OR application is already posted!";
            $response['view'] = "<tr><td>".$ornum."</td><td>Cannot Post</td></tr>";
        } else {
            $this->payments->orPosted($data['or_num'], $status_n);
            $response['msg'] = "OR application successfully posted!";
            $response['view'] = "<tr><td>".$ornum."</td><td>Posted Done</td></tr>"; 
        }
        
        echo json_encode($response);
    }
    
    public function unpostthissingleor() {
        $ornum = $this->input->post('ornum');         
        
        $data = $this->payments->postthissingleor($ornum);

        $status_n = $this->session->userdata('authsess')->sess_id;  
    
    
        if ($data['status'] == 'A') {
            $response['msg'] = "This application OR is already unpost!";
            $response['view'] = "<tr><td>".$ornum."</td><td>Cannot Unpost</td></tr>";
        } else {
            $this->payments->orUnPosted($data['or_num'], $data['or_type'], $status_n);
            $response['msg'] = "OR application successfully unpost!";
            $response['view'] = "<tr><td>".$ornum."</td><td>Unpost Done</td></tr>"; 
        }
        
        echo json_encode($response);
    }
}

?>
