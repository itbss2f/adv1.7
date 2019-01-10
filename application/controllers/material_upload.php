<?php

class Material_upload extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url')); 
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_product/products', 'model_material/material_uploading'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();
        $data['prod'] = $this->products->listOfProduct();  
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('uploading/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function findProduct() {
        
        $product = $this->input->post('product');
        $datefrom = $this->input->post('datefrom');
         
        $data['list'] = $this->material_uploading->getProductInfo($product, $datefrom);
        
        #print_r2($data['list']); exit;
        
        $response['fileattachment'] = $this->load->view('uploading/fileattachment', $data, true);
        
        echo json_encode($response);    
        
    }
    
    public function viewAds($id) { 
        
        $layout_boxes_id = $id;  
        
        $data['file'] = $this->material_uploading->getThisFileAttachment($id);
        
        $this->load->view('uploading/loadfiles', $data);   
    }
    
    public function upload() {
        
        $id = $this->input->post('layout_boxes_id');    
        $data['data'] = $this->material_uploading->getThisFileAttachment($id);
        
        $layout_boxes_id = $this->input->post('layout_boxes_id');
        $data['id'] = $layout_boxes_id;  
        
        $response['upload_data_view'] = $this->load->view('uploading/file_upload', $data, true);
        
        echo json_encode($response);
    }
    
    public function upload_data($id) {
        
        $layout_boxes_id = $id;
        $product = $this->input->post('product');
        $datefrom = $this->input->post('datefrom');          
        $material_remarks = $this->input->post('material_remarks');  
                                                
        #$config['upload_path'] = './uploads/fileattachment/';
        $config['upload_path'] = '/var/www/materialupload/';       
        $config['allowed_types'] = 'jpg';        
        $config['max_size']    = '100000';
        $config['max_width']  = '2000';
        $config['max_height']  = '3000';
        $this->load->helper('inflector');
        $file_name = $layout_boxes_id.'_'.Date('mdyhis').'_'.underscore($_FILES['userfile']['name']);
        $config['file_name'] = $file_name;
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload())
        {
            
            $fileData = $this->upload->data(); 
            
            $data['material_filename'] = $fileData['file_name'];
            $data['material_remarks'] = $this->input->post('material_remarks');  
            
            echo $this->upload->display_errors();
               
            //$this->viewdata($product, $datefrom);  
            
        }
        else
        { 
            
            $file = $this->upload->data();

            $this->upload->initialize($config);
            $fileData = $this->upload->data();  
            
            $data['material_filename'] = $file['file_name'];
            $data['material_remarks'] = $this->input->post('material_remarks');
            
            
            $this->material_uploading->savefileupload($layout_boxes_id, $data); 
            
            #print_r2($data); exit;

            //echo "Success";
                        
            $this->viewdata($product, $datefrom);
            
        }
         
    }
    
    public function viewdata($product, $datefrom) {
        
        $navigation['data'] = $this->GlobalModel->moduleList();
        $data['prod'] = $this->products->listOfProduct(); 
        
        $data['product'] = $product; 
        $data['datefrom'] = $datefrom; 
        
        $data['list'] = $this->material_uploading->getProductInfo($product, $datefrom); 
        
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('uploading/file_uploadview', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
        
    }
    
    public function remove($layout_boxes_id, $product, $datefrom) { 
    
       
        $this->material_uploading->removedata($layout_boxes_id);
        
        redirect('material_upload/viewdata/'.$product.'/'.$datefrom);
        
    }
    
}  













