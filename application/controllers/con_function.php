<?php 

class Con_function extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_function/model_function'));      
    }

    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();           
        $data['func'] = $this->model_function->listFunction();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('functions/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }

    public function newdata() {        
        $response['newdata_view'] = $this->load->view('functions/newdata', null, true);
        echo json_encode($response);
    }

    public function save() {        
        $data['name'] = $this->input->post('functionname');        
        $data['description'] = $this->input->post('description');                

        $this->model_function->saveNewData($data);

        $msg = "You successfully save Function";

        $this->session->set_flashdata('msg', $msg);

        redirect('con_function');
    }

    public function removedata($id) {        
        $this->model_function->removeData(abs($id));
        redirect('con_function');
    }

    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->model_function->getData($id);        
        $response['editdata_view'] = $this->load->view('functions/editdata', $data, true);
        echo json_encode($response);
    }

    public function update($id) {    
        $data['name'] = $this->input->post('functionname');        
        $data['description'] = $this->input->post('description');                
        
        $this->model_function->saveupdateNewData($data, abs($id));

        $msg = "You successfully update Function";

        $this->session->set_flashdata('msg', $msg);

        redirect('con_function');
    }
}
