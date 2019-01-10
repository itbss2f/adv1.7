<?php

class Adtype extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_adtype/adtypes');
    }
    
    /*public function runmisc() {
    $data = $this->adtypes->rerunadtype();
    $surchage = 0; $disc = 0;
    
        foreach ($data as $row) {
            $surchage = 0; $disc = 0;  
            if ($row['ao_mischargepercent1'] > 0) {
                $surchage += $row['ao_mischargepercent1'];
            } else {
                $disc += $row['ao_mischargepercent1'];   
            }  
            
            if ($row['ao_mischargepercent2'] > 0) {
                $surchage += $row['ao_mischargepercent2'];
            } else {
                $disc  += $row['ao_mischargepercent2'];   
            }    
            
            if ($row['ao_mischargepercent3'] > 0) {
                $surchage += $row['ao_mischargepercent3'];
            } else {
                $disc  += $row['ao_mischargepercent3'];   
            }      
            
            $update_p['ao_surchargepercent'] = $surchage;   
            $update_p['ao_discpercent'] = abs($disc);   
            
            $this->adtypes->updaterunmisc($row['id'], $update_p);
            
        }

    }*/

    public function index() { 
    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['adtype'] = $this->adtypes->listOfAdType();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('adtypes/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['adtype_code'] = $this->input->post('adtype_code');
        $data['adtype_name'] = $this->input->post('adtype_name');        
        $data['adtype_type'] = $this->input->post('adtype_type');        
        $data['adtype_catad'] = $this->input->post('adtype_catad');        
        $data['adtype_class'] = $this->input->post('adtype_class');        
        $data['adtype_araccount'] = $this->input->post('adtype_araccount');        
        $this->adtypes->saveNewData($data);

        $msg = "You successfully save Ad Type";

        $this->session->set_flashdata('msg', $msg);

        redirect('adtype'); 
    }
    
    public function newdata() { 
        $data['caf'] = $this->adtypes->getAcctList();
        $data['row'] = $this->adtypes->getClassList();
        $data['des'] = $this->adtypes->getArAcctList();
                      
        $response['newdata_view'] = $this->load->view('adtypes/newdata', $data, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misadtype.adtype_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        
        $data['caf'] = $this->adtypes->getAcctList();
        $data['row'] = $this->adtypes->getClassList();
        $data['des'] = $this->adtypes->getArAcctList();
        $data['data'] = $this->adtypes->getData($id);        
        $response['editdata_view'] = $this->load->view('adtypes/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['adtype_code'] = $this->input->post('adtype_code');
        $data['adtype_name'] = $this->input->post('adtype_name');        
        $data['adtype_type'] = $this->input->post('adtype_type');        
        $data['adtype_catad'] = $this->input->post('adtype_catad');        
        $data['adtype_class'] = $this->input->post('adtype_class');        
        $data['adtype_araccount'] = $this->input->post('adtype_araccount'); 
        
        $this->adtypes->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Ad Type";

        $this->session->set_flashdata('msg', $msg);

        redirect('adtype');       
    }
    
    public function removedata($id) {        
        $this->adtypes->removeData(abs($id));
        redirect('adtype');
    }
    
    public function searchdata() { 
        $data['caf'] = $this->adtypes->getAcctList();
        $data['row'] = $this->adtypes->getClassList();
        $data['des'] = $this->adtypes->getArAcctList();
                      
        $response['searchdata_view'] = $this->load->view('adtypes/searchdata', $data, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $searchkey['adtype_code'] = $this->input->post('adtype_code');
                
            $searchkey['adtype_name'] = $this->input->post('adtype_name');
                
            $searchkey['adtype_type'] = $this->input->post('adtype_type');
                
            $searchkey['adtype_catad'] = $this->input->post('adtype_catad');
            
            $searchkey['adtype_araccount'] = $this->input->post('adtype_araccount');
             
            $searchkey['adtype_class'] = $this->input->post('adtype_class');  
            
            $data['adtype'] = $this->adtypes->searched($searchkey);  
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('adtypes/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
    
}
