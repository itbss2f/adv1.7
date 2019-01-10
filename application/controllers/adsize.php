<?php
class Adsize extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model('model_adsize/adsizes');
        $this->load->model('model_booking/bookingissuemodel');
    }
    ## Running of rate amount
    /*public function test() {
        $aop = $this->adsizes->getAOPTM();
        
        
        foreach ($aop as $data) {
            # TODO get days of rate  
            $date = new DateTime($data['rundate']);        
            $d = $date->format('w');
            switch ($d)
            {
              case 0:
                 $daysrate = "AND adtyperate_sunday = '1'";                    
                 break;
              case 1:
                 $daysrate = "AND adtyperate_monday = '1'";                
                 break;
              case 2:
                 $daysrate = "AND adtyperate_tuesday = '1'";                
                 break;
              case 3:
                 $daysrate = "AND adtyperate_wednesday = '1'";                
                 break;
              case 4:
                 $daysrate = "AND adtyperate_thursday = '1'";                
                 break;
              case 5:
                 $daysrate = "AND adtyperate_friday = '1'";                
                 break;
              case 6:
                 $daysrate = "AND adtyperate_saturday = '1'";                
                 break;                                                            
            }               
            $rate['ratetype'] = 'R';
            $type = $data['ao_type'];   
            
            if (abs($data['ao_type']) != 0 ) {
                $type = $this->bookingissuemodel->getBookType($data['ao_type']);
            }
            $rate = $this->bookingissuemodel->getRate($data['ao_prod'], $type, $data['ao_class'], $data['ao_adtyperate_code'], $data['rundate'], $daysrate);    
            
            if (!empty($rate)) { $data['rateamt'] = $rate['rate'];  $ratetype = $rate['ratetype'];  } else { $data['rateamt'] = '0.00'; $ratetype = 'R';   }     
            
            $this->adsizes->updateAOPTMrate($data['id'], $data['rateamt']);
        }
        
                        
    }*/
    
    public function ajaxSize()    
    {
        $adsize = abs(mysql_escape_string(trim($this->input->post('adsize'))));
        $response['adsize'] = $this->adsizes->thisSize($adsize);        
        
        echo json_encode($response);
    } 
    
      public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['adsize'] = $this->adsizes->listOfSize(); 
                
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();  
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
          
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('adsizes/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['adsize_code'] = $this->input->post('adsize_code');
        $data['adsize_name'] = $this->input->post('adsize_name'); 
        $data['adsize_width'] = $this->input->post('adsize_width'); 
        $data['adsize_length'] = $this->input->post('adsize_length');        
        $this->adsizes->saveNewData($data);

        $msg = "You successfully save Ad Size";

        $this->session->set_flashdata('msg', $msg);

        redirect('adsize'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('adsizes/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misadsize.adsize_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->adsizes->getData($id);        
        $response['editdata_view'] = $this->load->view('adsizes/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['adsize_code'] = $this->input->post('adsize_code');
        $data['adsize_name'] = $this->input->post('adsize_name');
        $data['adsize_width'] = $this->input->post('adsize_width'); 
        $data['adsize_length'] = $this->input->post('adsize_length'); 
        
        $this->adsizes->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Ad Size";

        $this->session->set_flashdata('msg', $msg);

        redirect('adsize');       
    }
    
    public function removedata($id) {        
        $this->adsizes->removeData(abs($id));
        redirect('adsize');
    }
    
    public function searchdata() 
    {        
        $response['searchdata_view'] = $this->load->view('adsizes/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
            $searchkey['adsize_code'] = $this->input->post('adsize_code');
                
            $searchkey['adsize_name'] = $this->input->post('adsize_name');
            
            $searchkey['adsize_width'] = $this->input->post('adsize_width');
            
            $searchkey['adsize_length'] = $this->input->post('adsize_length');
            
            $data['adsize'] = $this->adsizes->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('adsizes/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
}
