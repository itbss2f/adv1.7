<?php

class File_upload extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_fileupload/fileupload');
    }
    
     
    public function index() 
    
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                                    
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('file_upload/_fileupload', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function searchFile() {
        
        $aonum = $this->input->post('aonum');
        
        $info = $this->fileupload->getAOData($aonum); 
        
        $response['invalid'] = true;
        if (empty($info)) {
            $response['invalid'] = false;
        }
        //$data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                                    
        $dataattach['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        $dataattach['list'] = $this->fileupload->getFileAttachment($aonum);
        
        $response['info'] = $info;
        $response['fileattachment'] = $this->load->view('file_upload/fileattachment', $dataattach, true);
        
        echo json_encode($response);    
    }
    
    public function upload_data() {
        
        $aonum = $this->input->post('aonum');
        
        ini_set('memory_limit', -1);    
                                                                
        $config['upload_path'] = '/var/www/fileattachment/';
        $config['allowed_types'] = 'gif|jpg|png|doc|xls|pdf|csv|xml|txt|ppt';        
        $config['max_size']    = '200000';
        $config['max_width']  = '4000';
        $config['max_height']  = '3000';
        $this->load->helper('inflector');
        $file_name = $aonum.'_'.Date('mdyhis').'_'.underscore($_FILES['userfile']['name']);
        $config['file_name'] = $file_name;
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload())
        {
            $data['ao_num'] = $this->input->post('aonum');
            $fileData = $this->upload->data(); 
            
            $data['filename'] = $fileData['file_name'];
            $data['filetype'] = $fileData['file_ext'];
            
            echo $this->upload->display_errors();
            
            echo "error";
            
            exit;
            
            echo "Error";
               
            redirect('file_upload/viewdata/'.$data['ao_num']); 
            
        }
        else
        {
            
            $data['ao_num'] = $this->input->post('aonum');

            $file = $this->upload->data();
  
            #$new_name = $data['ao_num'].'_'.Date('mdyhis').'_'.$file['file_name'];  
            #$config['file_name'] = $new_name;
            $this->upload->initialize($config);
            $fileData = $this->upload->data(); 
          
            #echo "<pre>";
            #echo $fileData['file_name'];
            
            #var_dump($file); exit;
            
            #$this->upload->do_upload();  
            
            $data['filename'] = $file['file_name'];
            $data['filetype'] = $file['file_ext'];
            
            $this->fileupload->savefileupload($data);    
            
            echo "Success";
                        
            
            
            redirect('file_upload/viewdata/'.$data['ao_num']);       
            
        }
         
    }
    
    public function viewdata($aonum) { 
        
        $navigation['data'] = $this->GlobalModel->moduleList();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['aonum'] = $aonum;
        $data['info'] = $this->fileupload->getAOData($aonum); 
        $data['list'] = $this->fileupload->getFileAttachment($aonum);  
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                                    
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');   
        
        if (empty($data['info'])) {
            redirect('file_upload');
        }                                  
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('file_upload/_fileuploadview', $data, true);
        $this->load->view('welcome_index', $welcome_layout);    
    }
    
    public function viewfile($id = null) { 
        
        $data['file'] = $this->fileupload->getThisFileAttachment($id);
        
        $this->load->view('file_upload/loadfile', $data);   
    } 
    public function removedata($id, $aonum) {
        
        $this->fileupload->removeData($id);
        
        redirect('file_upload/viewdata/'.$aonum);       
        
    }
    
}


    
   