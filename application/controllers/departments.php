<?php

class Departments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_dept/depts','model_product/products', 'model_chartofacct/chartofaccts'));

    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['depart'] = $this->depts->listOfDept();      
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition(); 
        //$data['prod'] = $this->products->listOfProduct();  
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
          
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('department/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {

        $data['dept_code'] = strtoupper($this->input->post('dept_code'));
        $data['dept_name'] = strtoupper($this->input->post('dept_name'));        
        $data['mdept_name'] = strtoupper($this->input->post('mdept_name'));        
        $data['dept_branchstatus'] = $this->input->post('dept_branchstatus');
        $data['sect_name'] = strtoupper($this->input->post('sect_name'));
        $data['exp_type'] = $this->input->post('exp_type');
        //$data['prod_code'] = $this->input->post('prod_code');

        $this->depts->saveNewData($data);

        $msg = "You successfully save New Department";

        $this->session->set_flashdata('msg', $msg);

        redirect('departments'); 
    }
    
    public function newdata() {     

        $data['prod'] = $this->products->listOfProduct(); 
        $data['exptype'] =  $this->chartofaccts->listOfChartOfAccount();
        $response['newdata_view'] = $this->load->view('department/newdata', $data, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misdept.dept_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['prod'] = $this->products->listOfProduct(); 
        $data['acctlist'] =  $this->chartofaccts->listOfChartOfAccount();
        $data['data'] = $this->depts->getData($id);    
        $response['editdata_view'] = $this->load->view('department/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {


        $data['dept_code'] = strtoupper($this->input->post('dept_code'));
        $data['dept_name'] = strtoupper($this->input->post('dept_name'));        
        $data['mdept_name'] = strtoupper($this->input->post('mdept_name'));        
        $data['dept_branchstatus'] = $this->input->post('dept_branchstatus');
        $data['sect_name'] = strtoupper($this->input->post('sect_name'));
        $data['exp_type'] = $this->input->post('exp_type');
        $data['prod_code'] = $this->input->post('prod_code');
        
        $this->depts->saveupdateNewData($data, $id);

        $msg = "You successfully save update Department";

        $this->session->set_flashdata('msg', $msg);

        redirect('departments');       
    }
    
    public function removedata($id) {        
        $this->depts->removeData(abs($id));
        redirect('departments');
    }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');

            $search['dept_code'] = $this->input->post('dept_code');
                
            $search['dept_name'] = $this->input->post('dept_name');
                
            $search['mdept_name'] = $this->input->post('mdept_name');
            
            $search['dept_branchstatus'] = $this->input->post('dept_branchstatus');

            $search['sect_name'] = $this->input->post('sect_name');

            $search['exp_type'] = $this->input->post('exp_type');
            
            $data['depart'] = $this->depts->searched($search); 
            
            $navigation['data'] = $this->GlobalModel->moduleList(); 
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
            $welcome_layout['content'] = $this->load->view('department/index', $data, true);
        
            $this->load->view('welcome_index', $welcome_layout);
            
    }
    
    public function searchdata()
    {
        $response['searchdata_view'] = $this->load->view('department/searchdata', null, true);
        echo json_encode($response);
    }
}
