<?php
class Vat extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model('model_vat/vats');
    }
    
    public function ajaxVat()
    {
        $vatid =  mysql_escape_string($this->input->post('vat'));
        $vat = $this->vats->thisVat($vatid);            
        if (!empty($vat)) {
            echo $vat['vat_rate'];
        } else { echo ""; }
    }
      public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['vat'] = $this->vats->listOfVat();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();  
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
          
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('vats/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('vats/newdata', null, true);
        echo json_encode($response);
    }
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misvat.vat_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    public function save() {
        $data['vat_code'] = $this->input->post('vat_code');
        $data['vat_name'] = $this->input->post('vat_name'); 
        $data['vat_rate'] = $this->input->post('vat_rate');
        $data['vat_from'] = $this->input->post('vat_date_from');
        $data['vat_to'] = $this->input->post('vat_date_to');  
        $this->vats->saveNewData($data);

        $msg = "You successfully save VAT";

        $this->session->set_flashdata('msg', $msg);

        redirect('vat'); 
    }
    /*public function index() {
                
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $page = input_get('page', 1);
        $total = $this->vats->countAll();        
        $data['result'] = $this->vats->listOfVATView($search="", false, offset($page, $limit = 25), $limit);
        $data['pages'] = pages($total, $page, $limit);
        
        $welcome_layout['nav'] = $this->GlobalModel->moduleList();
        $welcome_layout['sidebar_page'] = "";
        $welcome_layout['content_page'] = $this->load->view('vats/index', $data, true);
        $this->load->view('welcome_layout', $welcome_layout);
        
    }    
    
    function pageselect()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $page = input_post('page', 1);
        $total = $this->vats->countAll();
        $data['result'] = $this->vats->listOfVATView($search="", false, offset($page, $limit = 25), $limit);
        $data['pages'] = pages($total, $page, $limit);
        $welcome_layout['nav'] = $this->GlobalModel->moduleList();
        $welcome_layout['sidebar_page'] = "";        
        $html = $this->load->view('vats/index', $data, true);
        
        echo json_encode($html);        
    }
    
    public function addview() {
        
        $response['addview'] = $this->load->view('vats/_addview', null, true);
        
        echo json_encode($response);
    }
    
    public function editview($id) {
    
        $data['id'] = abs($id);
        $data['data'] = $this->vats->thisVAT($data['id']);
        $response['editview'] = $this->load->view('vats/_editview', $data, true);
    
        echo json_encode($response);
    }
    
    public function validateCode() {        
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misvat.vat_code]');
        if ($this->form_validation->run() == FALSE)
        {
            echo "true";
        }        
    }
    
    public function saveNew() {        
    
        $data['vat_code'] = mysql_escape_string($this->input->post('vatcode'));
        $data['vat_name'] = mysql_escape_string($this->input->post('vatname'));
        $data['vat_rate'] = mysql_escape_string($this->input->post('vatrate'));
        $data['vat_from'] = mysql_escape_string($this->input->post('vatdatefrom'));
        $data['vat_to'] = mysql_escape_string($this->input->post('vatdateto'));
                
        $this->vats->insertData($data);
        
        redirect('vat/index');
    }
    
    public function update($id) {
        
        $data['vat_name'] = mysql_escape_string($this->input->post('vatname'));
        $data['vat_rate'] = mysql_escape_string($this->input->post('vatrate'));
        $data['vat_from'] = mysql_escape_string($this->input->post('vatdatefrom'));
        $data['vat_to'] = mysql_escape_string($this->input->post('vatdateto'));
        
        $this->vats->updateData($id, $data);
        
        redirect('vat/index');
        
    }
    
    public function delete($id) {
        
        $this->vats->deleteData($id);
                
    }
    
    function search()
    {
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        $searchkey      = $this->input->post('mydata');
        $data['result'] = $this->vats->search($searchkey, 25);
        $html = $this->load->view("vats/_search",$data,true);
        echo json_encode($html);
    } */

    public function update($id) {
        $data['vat_name'] = $this->input->post('vat_name');  
        $data['vat_from'] = $this->input->post('vat_date_from');
        $data['vat_to'] = $this->input->post('vat_date_to'); 
        $data['vat_rate'] = $this->input->post('vat_rate');        
        $this->vats->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update VAT";

        $this->session->set_flashdata('msg', $msg);

        redirect('vat');       
    }
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->vats->getData($id);        
        $response['editdata_view'] = $this->load->view('vats/editdata', $data, true);
        echo json_encode($response);
    }
        public function removedata($id) {        
        $this->vats->removeData(abs($id));
        redirect('vat');
    }
    
     public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('vats/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
        $searchkey['vat_name'] = $this->input->post('vat_name');  
        
        $searchkey['vat_code'] = $this->input->post('vat_code');  
        
        $searchkey['vat_from'] = $this->input->post('vat_date_from');
        
        $searchkey['vat_to'] = $this->input->post('vat_date_to'); 
        
        $searchkey['vat_rate'] = $this->input->post('vat_rate');
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['vat'] = $this->vats->searched($searchkey);         
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('vats/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout); 
    }
}