  <?php

class Bank extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_bank/banks');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['bank'] = $this->banks->listOfBank();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('banks/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['bmf_code'] = $this->input->post('bmf_code');
        $data['bmf_name'] = $this->input->post('bmf_name');        
        $this->banks->saveNewData($data);

        $msg = "You successfully save Bank";

        $this->session->set_flashdata('msg', $msg);

        redirect('bank'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('banks/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misbmf.bmf_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->banks->getData($id);        
        $response['editdata_view'] = $this->load->view('banks/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['bmf_name'] = $this->input->post('bmf_name');  
        
        $this->banks->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Bank";

        $this->session->set_flashdata('msg', $msg);

        redirect('bank');       
    }
    
    public function removedata($id) {        
        $this->banks->removeData(abs($id));
        redirect('bank');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('banks/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
            $searchkey['bmf_code'] = $this->input->post('bmf_code');
        
            $searchkey['bmf_name'] = $this->input->post('bmf_name');    
    
            $data['bank'] = $this->banks->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('banks/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
}

