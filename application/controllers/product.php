<?php

class Product extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_product/products');
    }
    
    public function index() 
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['product'] = $this->products->listOfProduct();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition(); 
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
           
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('products/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() 
    {
        $data['prod_code'] = $this->input->post('prod_code');
        $data['prod_name'] = $this->input->post('prod_name');        
        $data['prod_freq'] = $this->input->post('prod_freq');
        $data['prod_group'] = $this->input->post('prod_group'); 
        $data['prod_adtype'] = $this->input->post('prod_adtype');
        $data['prod_subgroup'] = $this->input->post('prod_subgroup'); 
        $data['prod_cms'] = $this->input->post('prod_cms');
        $data['prod_pricestatus'] = $this->input->post('prod_pricestatus');
        $data['prod_type'] = $this->input->post('prod_type');
        $data['prod_ccm'] = $this->input->post('prod_ccm');
        $data['prod_stdrate_weekday'] = $this->input->post('prod_stdrate_weekday');
        $data['prod_stdrate_weekend'] = $this->input->post('prod_stdrate_weekend');   
        $data['columnwidth'] = $this->input->post('columnwidth');
        $data['gutter'] = $this->input->post('gutter'); 
        $data['ruleweight'] = $this->input->post('ruleweight');
        $data['ruleindent'] = $this->input->post('ruleindent');
        $data['leadbefore'] = $this->input->post('leadbefore');
        $data['leadafter'] = $this->input->post('leadafter');
        $data['sunday'] = $this->input->post('sunday');
        $data['monday'] = $this->input->post('monday');
        $data['tuesday'] = $this->input->post('tuesday');
        $data['wednesday'] = $this->input->post('wednesday'); 
        $data['thursday'] = $this->input->post('thursday');
        $data['friday'] = $this->input->post('friday');
        $data['saturday'] = $this->input->post('saturday');
        $data['groupnumber'] = $this->input->post('groupnumber');
        $data['groupsequence'] = $this->input->post('groupsequence');
        $data['region'] = $this->input->post('region');
        
        $this->products->saveNewData($data);

        $msg = "You successfully save Product";

        $this->session->set_flashdata('msg', $msg);

        redirect('product'); 
    }
    
    public function newdata() 
    {        
        $response['newdata_view'] = $this->load->view('products/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() 
    {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misprod.prod_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() 
    {
        
        $id = $this->input->post('id');
        $data['data'] = $this->products->getData($id);        
        $response['editdata_view'] = $this->load->view('products/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) 
    {                                                               
        $data['prod_code'] = $this->input->post('prod_code');
        $data['prod_name'] = $this->input->post('prod_name');        
        $data['prod_freq'] = $this->input->post('prod_freq');
        $data['prod_group'] = $this->input->post('prod_group'); 
        $data['prod_adtype'] = $this->input->post('prod_adtype');
        $data['prod_subgroup'] = $this->input->post('prod_subgroup'); 
        $data['prod_cms'] = $this->input->post('prod_cms');
        $data['prod_pricestatus'] = $this->input->post('prod_pricestatus');
        $data['prod_type'] = $this->input->post('prod_type');
        $data['prod_ccm'] = $this->input->post('prod_ccm');
        $data['prod_stdrate_weekday'] = $this->input->post('prod_stdrate_weekday');
        $data['prod_stdrate_weekend'] = $this->input->post('prod_stdrate_weekend');   
        $data['columnwidth'] = $this->input->post('columnwidth');
        $data['gutter'] = $this->input->post('gutter'); 
        $data['ruleweight'] = $this->input->post('ruleweight');
        $data['ruleindent'] = $this->input->post('ruleindent');
        $data['leadbefore'] = $this->input->post('leadbefore');
        $data['leadafter'] = $this->input->post('leadafter');
        $data['sunday'] = $this->input->post('sunday');
        $data['monday'] = $this->input->post('monday');
        $data['tuesday'] = $this->input->post('tuesday');
        $data['wednesday'] = $this->input->post('wednesday'); 
        $data['thursday'] = $this->input->post('thursday');
        $data['friday'] = $this->input->post('friday');
        $data['saturday'] = $this->input->post('saturday');
        $data['groupnumber'] = $this->input->post('groupnumber');
        $data['groupsequence'] = $this->input->post('groupsequence');
        $data['region'] = $this->input->post('region');
        
        $this->products->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Products";

        $this->session->set_flashdata('msg', $msg);

        redirect('product');       
    }
    
    public function removedata($id) 
    {        
        $this->products->removeData(abs($id));
        redirect('product');
    }
    
     public function searchdata() 
    {        
        $response['searchdata_view'] = $this->load->view('products/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function searched()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
            $searchkey['prod_code'] = $this->input->post('prod_code');
                
            $searchkey['prod_name'] = $this->input->post('prod_name');
            
            $data['product'] = $this->products->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('products/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
}
