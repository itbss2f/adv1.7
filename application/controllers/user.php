<?php

class User extends CI_Controller {

    public function __construct()
    {

        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_user/users', 'model_main_module/main_modules', 'model_branch/branches'));
      
    }

    public function index() 
    {
        $navigation['data'] = $this->GlobalModel->moduleList(); 

        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        // $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        $data['canSETUSERACCESS'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'SETUSERACCESS'); 
        $data['canEXPORT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EXPORT'); 

        $data['user_list'] = $this->users->list_of_user();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('users/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function newdata() 
    {    
        $data['branch'] = $this->branches->listOfBranch();        
        $response['newdata_view'] = $this->load->view('users/newdata', $data, true);
        echo json_encode($response);
    }

    public function access_view() 
    {
        $data['userid'] = $this->input->post('userid');
        $data['main_module'] = $this->main_modules->listOfMainModule();
        $response['access_view'] = $this->load->view('users/-accessview', $data, true);
        echo json_encode($response);
    }

    public function module_function_list() 
    {
        $main_module = $this->input->post('main_module');
        $userid = $this->input->post('userid');
        $data['userid'] = $userid;
        $data['module_list'] = $this->main_modules->main_modules_list($main_module, $userid);

        $response['modulelistview'] = $this->load->view('users/-modulelistview', $data, true);
        echo json_encode($response);
    }

    public function setaccess() 
    {
        $userid = $this->input->post('userid');
        $module_function = $this->input->post('module_function');
        $this->main_modules->setModuleFunctionAccess($module_function, $userid);
    }
    
    public function saveUser() 
    {
        $data['emp_id'] = mysql_escape_string($this->input->post('empno'));    
        $data['firstname'] = ucwords(strtolower(mysql_escape_string($this->input->post('firstname'))));    
        $data['middlename'] = ucwords(strtolower(mysql_escape_string($this->input->post('middlename'))));    
        $data['lastname'] = ucwords(strtolower(mysql_escape_string($this->input->post('lastname'))));    
        $data['userlevel_id'] = mysql_escape_string($this->input->post('userlevel'));    
        $data['branch'] = strtoupper(mysql_escape_string($this->input->post('branch')));    
        $data['dept_id'] = mysql_escape_string($this->input->post('department'));    
        $data['position'] = strtoupper(mysql_escape_string($this->input->post('position')));    
        $data['email'] = mysql_escape_string($this->input->post('emailadd'));    
        $data['username'] = mysql_escape_string($this->input->post('username'));    
        $data['userpass'] = md5($this->input->post('password'));    
        $data['expiration_date'] = mysql_escape_string($this->input->post('expirydate'));   

        
        $this->users->saveNewData($data);

        $msg = "You successfully save New User";

        $this->session->set_flashdata('msg', $msg);

        redirect('user'); 
        
    }
    
    public function validateUsername() 
    {
        $this->form_validation->set_rules('empno', 'Employee#', 'trim|is_unique[users.emp_id]');
        $this->form_validation->set_rules('username', 'Username', 'trim|is_unique[users.username]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }   
    }
    
    public function editdata() 
    {    
        $id = $this->input->post('id');
        $data['data'] = $this->users->getUser($id);
        $data['userstat'] = $this->users->getUserStatus();
        $data['branch'] = $this->branches->listOfBranch();        
        $response['editdata_view'] = $this->load->view('users/editdata', $data, true);
        
        echo json_encode($response);
    }


    public function saveUpdateUser($id) 
    {   
        
        $data['emp_id'] = mysql_escape_string($this->input->post('empno'));    
        $data['firstname'] = ucwords(strtolower(mysql_escape_string($this->input->post('firstname'))));    
        $data['middlename'] = ucwords(strtolower(mysql_escape_string($this->input->post('middlename'))));    
        $data['lastname'] = ucwords(strtolower(mysql_escape_string($this->input->post('lastname'))));    
        $data['userlevel_id'] = mysql_escape_string($this->input->post('userlevel'));    
        $data['branch'] = strtoupper(mysql_escape_string($this->input->post('branch')));    
        $data['dept_id'] = mysql_escape_string($this->input->post('department'));    
        $data['position'] = strtoupper(mysql_escape_string($this->input->post('position')));    
        $data['email'] = mysql_escape_string($this->input->post('emailadd'));    
        $data['username'] = mysql_escape_string($this->input->post('username'));    
        $data['expiration_date'] = mysql_escape_string($this->input->post('expirydate'));   
        $data['expire'] = $this->input->post('userstat');   
        
        $this->users->saveUpdateNewData($data, $id);

        $msg = "You successfully update User";

        $this->session->set_flashdata('msg', $msg);

        redirect('user'); 
        
    }
    
    public function searchdata()
    {   
        $data['branch'] = $this->branches->listOfBranch(); 
        $response['searchdata_view'] = $this->load->view('users/searchdata', $data, true);
        echo json_encode($response);  
    }
    
    public function search()
    {

            $searchkey['emp_id'] = $this->input->post('emp_id');
                
            $searchkey['firstname'] = $this->input->post('firstname');
                
            $searchkey['middlename'] = $this->input->post('middlename');
                
            $searchkey['lastname'] = $this->input->post('lastname');
            
            $searchkey['branch'] = $this->input->post('branch');
                
            $searchkey['email'] = $this->input->post('email');
                
            $searchkey['username'] = $this->input->post('username');
                
            $searchkey['position'] = $this->input->post('position');

            $searchkey['userstat'] = $this->input->post('userstat');
            
            $data['user_list'] = $this->users->search($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();

            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
            $data['canSETUSERACCESS'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'SETUSERACCESS'); 
            $data['canEXPORT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EXPORT'); 

            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('users/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);

    }
    
    public function changepassword()       
    {
        $data['oldpassword'] = $this->input->post("oldpassword");   
        
        $oldpassword = $this->users->checkoldpassword($data);
           
        if($oldpassword == 0)
        {
           $jsonmessage['message'] = "Old password incorrect."; 
           $jsonmessage['status'] = "fail"; 
            
           echo json_encode($jsonmessage);  
        }
        
        else
        {
           $data['password'] = $this->input->post("newpassword");   
        
           $this->users->changepassword($data);
            
           $jsonmessage['message'] = "Password changed."; 
           $jsonmessage['status'] = "success"; 
                
           echo json_encode($jsonmessage);  
        }
        
     
         
    }
    
   public function passform()
    {
        $html = $this->load->view("changepassword/passform",null,true);
        
        echo json_encode($html);
    } 
    
    /*public function autoname()
    {
        $lastname = trim($this->input->post('lastname'));
        $middlename = trim($this->input->post('middlename'));
        $firstname = trim($this->input->post('firstname'));        
        
        $data  = $this->users->findEmployee($lastname, $middlename, $firstname);            
        echo json_encode($data);
    }*/

    public function duplicate() {

        $id = $this->input->post('id');
        //echo $id ; exit;
        $data['data'] = $this->users->getUser($id);

        $response['duplicate_view'] = $this->load->view('users/duplicatedata', $data, true);

        echo json_encode($response);
    }

    public function searchingforemployee() {

        $searchkey['username'] = $this->input->post('username');

        $id = $this->input->post('user_by');

        $dlist['user_by'] = $this->users->getUserDetails($id);

        #print_r2( $dlist['user_by']); exit;

        $dlist['dlist'] = $this->users->searchempdetails($searchkey);

        $response['search_empdetails'] = $this->load->view('users/searchdetails', $dlist, true);

        echo json_encode($response);
    }

    public function saveduplicate($id, $user_id = null) {

        $this->users->getuseraccess($id, $user_id);

        $msg = "You successfully update User";

        $this->session->set_flashdata('msg', $msg);

        redirect('user'); 

    }

    public function generateexcel() {


        $data['dlist'] = $this->users->list_of_user_access();

        #print_r2($data['dlist']) ; exit;

        $html = $this->load->view('users/export/excel_useraccess', $data, true); 
        $filename ="USER_ACCESS".".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();

    }
}

