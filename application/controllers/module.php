<?php 

class Module extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_modules/model_module'));   
        $this->load->model(array('model_main_module/model_mainmodule'));    
    }

    public function index() 
    {
        $navigation['data'] = $this->GlobalModel->moduleList();   

        //$data['main_module_list'] = $this->model_mainmodule->list_of_main_modules_function();  
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        $data['canSETFUNCTION'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'SETFUNCTION'); 
        $data['canEXPORT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EXPORT'); 

        $data['module'] = $this->model_module->listModule();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('modules/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }

    public function newdata()
     {
        $data['mainmodule'] = $this->model_module->listMainModule();            
        $response['newdata_view'] = $this->load->view('modules/newdata', $data, true);
        echo json_encode($response);
    }

    public function save() 
    {
        $data['main_module_id'] = $this->input->post('mainmodule');
        $data['name'] = $this->input->post('modulename');        
        $data['description'] = $this->input->post('description');        
        $data['segment_path'] = $this->input->post('segment');

        $this->model_module->saveNewData($data);

        $msg = "You successfully save Module";

        $this->session->set_flashdata('msg', $msg);

        redirect('module');
    } 

    public function removedata($id) 
    {        
        $this->model_module->removeData(abs($id));
        redirect('module');
    }

    public function editdata() 
    {
        $id = $this->input->post('id');
        $data['data'] = $this->model_module->getData($id);
        $data['mainmodule'] = $this->model_module->listMainModule();            
        $response['editdata_view'] = $this->load->view('modules/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function setfunc() 
    {
        $id = $this->input->post('id');
        $data['module_id'] = $id;
        $data['modfunc'] = $this->model_module->getModuleFunction($id);        
        $data['func'] = $this->model_module->getFunction();        
        $response['setfunc_view'] = $this->load->view('modules/setfunc', $data, true);
        echo json_encode($response);
    }

    public function update($id) 
    {    
        $data['main_module_id'] = $this->input->post('mainmodule');
        $data['name'] = $this->input->post('modulename');        
        $data['description'] = $this->input->post('description');        
        $data['segment_path'] = $this->input->post('segment');
        
        $this->model_module->saveupdateNewData($data, abs($id));

        $msg = "You successfully update Module";

        $this->session->set_flashdata('msg', $msg);

        redirect('module');
    }
    
    public function saveModuleFunction($modid) 
    {
        $data['funct'] = $this->input->post('funct');        
        $this->model_module->saveModuleFunction($data, abs($modid));        
        $msg = "You successfully set Module Function";

        $this->session->set_flashdata('msg', $msg);

        redirect('module');    
    }
    
    public function searchdata()
    {
        $data['mainmodule'] = $this->model_module->listMainModule();            
        $response['searchdata_view'] = $this->load->view('modules/searchdata', $data, true);
        echo json_encode($response);   
    }
    
    public function search() 
    { 
            $searchkey['main_module_id'] = $this->input->post('mainmodule');
                
            $searchkey['name'] = $this->input->post('modulename');
                
            $searchkey['description'] = $this->input->post('description');
                
            $searchkey['segment_path'] = $this->input->post('segment');
            
            $data['module'] = $this->model_module->search($searchkey); 

            $navigation['data'] = $this->GlobalModel->moduleList();  

            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
            $data['canSETFUNCTION'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'SETFUNCTION'); 
            $data['canEXPORT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EXPORT');             
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('modules/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout); 
    }

    public function generateexcel() {


        $data['dlist'] = $this->model_mainmodule->list_of_main_modules_function();

        #print_r2($data['dlist']) ; exit;

        $html = $this->load->view('modules/export/excel_mainmodule', $data, true); 
        $filename ="MAIN_MODULE_LIST".".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();



    }


}
