<?php

class Ordatafix extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_payment/payments');
    }
    
    public function index() 
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
          
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('ordatafix/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function searchornum() {
        $aonum = $this->input->post('ornum');
        $data['list'] = $this->payments->getORNumDataFix($aonum);   
        
        $response['result'] = $this->load->view('ordatafix/result', $data, true);
        
        echo json_encode($response);
    }
    
    public function editdata() {
        $id = $this->input->post('id');  
        $data['id'] = $id;     
        $data['data'] = $this->payments->getDataOR($id);
        $response['editdata_view'] = $this->load->view('ordatafix/editdata_view', $data, true);      
        echo json_encode($response);        
    }
    
    public function saveDatafix() {
        $id = $this->input->post('id'); 
        $data['or_payee'] = $this->input->post('orpayee');
        $data['or_date'] = $this->input->post('ordate');
        $data['or_num'] = intval($this->input->post('ornum'));
        
        $this->payments->saveORDatafix($id, $data);
        
        
        $data['list'] = $this->payments->getORNumDataFix($id);   
        
        $response['result'] = $this->load->view('ordatafix/result', $data, true);
        
        echo json_encode($response); 
    }
    
    public function validateORnumber() {
        $orno = mysql_escape_string($this->input->post('orno'));

        $chck = $this->payments->validateORNumber($orno);
        
        echo $chck;
    }
}
?>
