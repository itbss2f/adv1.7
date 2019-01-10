<?php 

class Mainmodule extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_main_module/model_mainmodule'));      
    }

    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();           
        $data['mainmodule'] = $this->model_mainmodule->listMainModule();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('mainmodules/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }

    public function newdata() {        
        $response['newdata_view'] = $this->load->view('mainmodules/newdata', null, true);
        echo json_encode($response);
    }

    public function save() {
        $data['name'] = $this->input->post('mainmodulename');
        $data['description'] = $this->input->post('description');        
        $data['order'] = $this->input->post('order');        
        $data['icon'] = $this->input->post('icon');

        $this->model_mainmodule->saveNewData($data);

        $msg = "You successfully save Main Module";

        $this->session->set_flashdata('msg', $msg);

        redirect('mainmodule');
    }

    public function removedata($id) {        
        $this->model_mainmodule->removeData(abs($id));
        redirect('mainmodule');
    }

    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->model_mainmodule->getData($id);        
        $response['editdata_view'] = $this->load->view('mainmodules/editdata', $data, true);
        echo json_encode($response);
    }

    public function update($id) {    
        $data['name'] = $this->input->post('mainmodulename');
        $data['description'] = $this->input->post('description');        
        $data['order'] = $this->input->post('order');        
        $data['icon'] = $this->input->post('icon');
        
        $this->model_mainmodule->saveupdateNewData($data, abs($id));

        $msg = "You successfully update Main Module";

        $this->session->set_flashdata('msg', $msg);

        redirect('mainmodule');
    }
    
    public function setfunc() {
        
        $id = $this->input->post('id');
        $data['module_id'] = $id;
        $data['modfunc'] = $this->model_mainmodule->getMainModuleFunction($id);        
        $data['func'] = $this->model_mainmodule->getFunction();        
        $response['setfunc_view'] = $this->load->view('mainmodules/setfunc', $data, true);
        echo json_encode($response);
    }
    
    public function saveModuleFunction($modid) {
        $data['funct'] = $this->input->post('funct');        
        $this->model_mainmodule->saveMainModuleFunction($data, abs($modid));        
        $msg = "You successfully set Main Module Function";

        $this->session->set_flashdata('msg', $msg);

        redirect('mainmodule');    
    }
}
