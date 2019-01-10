<?php

class Gl_datauploading extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('PHPExcel');
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_gl_datauploading/gl_datauploading_mod');
    }
    
     
    public function index() 
    
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('accounting/gl_uploading', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    
    public function uploaddata() {
        
        ini_set('memory_limit', -1);        
        
        //$config['upload_path'] = 'D:\\test\\';
        $config['upload_path'] = '/tmp/dummy_layout_output/';
        $config['allowed_types'] = 'xls';        
        $config['max_size']    = '100000';
        $config['max_width']  = '1024';
        $config['max_height']  = '1024';

        $this->load->library('upload', $config);
        $datatype = $this->input->post('uploadtype');
        $datadate = $this->input->post('uploaddate');
        if ( ! $this->upload->do_upload())
        {

            echo $this->upload->display_errors();
            
            echo "error";
            
            exit;
            
            
        }
        else
        {

            $file = $this->upload->data();
            #print_r2($file); exit;
            
            $filename = $file['full_path'];
            $datatype = $this->input->post('uploadtype');
            $datadate = $this->input->post('uploaddate');
            
            $this->gl_datauploading_mod->glcheckexistingdata($datatype, $datadate);
       
            //read file from path
            $objPHPExcel = PHPExcel_IOFactory::load($filename);
            //get only the Cell Collection
            $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

            //extract to a PHP readable array format
            foreach ($cell_collection as $cell) {
                
                $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                //header will/should be in row 1 only. of course this can be modified to suit your need.
                if ($row == 1) {
                    $header[$row][$column] = $data_value;
                } else {
                    if ($column == "B") {
                        $arr_data[$row][$column] = $objPHPExcel->getActiveSheet()->getCell($cell)->getFormattedValue();         
                    } else {
                    $arr_data[$row][$column] = $data_value;          
                    }
                }
                
            }

            $datax['header'] = $header;
            $datax['values'] = $arr_data;
            //print_r2($arr_data);  
            //exit; 
            $amount = 0;
            foreach ($arr_data as $row) {
                $data['datatype'] = $datatype;
                $data['datanumber'] = $row['A'];
                $data['datadate'] = $row['B'];
                $data['payeename'] = $row['C'];
                $data['account'] = $row['D'];
                $data['datacode'] = $row['E']; 
                $data['amount'] = $row['F'];

                $amount += $row['F'];
                #print_r2($data); exit;
                $this->gl_datauploading_mod->insertGLData($data);
                //$this->db->query();
            }    
            
            $this->session->set_flashdata('process', 'Done Uploading');
            
            redirect('gl_datauploading'); 
            #  exit;
            
           # redirect('file_upload/viewdata/'.$data['ao_num']);       
            
        }
    }
    
                            
    /*public function uploadexcel($datatype, $datadate, $filename){
        
        #echo "test";
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        $datatype = $this->input->post('uploadtype');
        $datadate = $this->input->post('uploaddate');
        //$filename = $this->input->post('uploadfile');
                                          
        // Check and delete existing data 
        $this->gl_datauploading_mod->glcheckexistingdata($datatype,$datadate);
       
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($filename);
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
            //header will/should be in row 1 only. of course this can be modified to suit your need.
            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                if ($column == "B") {
                    $arr_data[$row][$column] = $objPHPExcel->getActiveSheet()->getCell($cell)->getFormattedValue();         
                } else {
                $arr_data[$row][$column] = $data_value;          
                }
            }
            
        }

        $datax['header'] = $header;
        $datax['values'] = $arr_data;
        //print_r2($arr_data);  
        //exit; 
        $amount = 0;
        foreach ($arr_data as $row) {
            $data['datatype'] = $datatype;
            $data['datanumber'] = $row['A'];
            $data['datadate'] = $row['B'];
            $data['payeename'] = $row['C'];
            $data['account'] = $row['D'];
            $data['datacode'] = $row['E']; 
            $data['amount'] = $row['F'];

            $amount += $row['F'];
            #print_r2($data); exit;
            $this->gl_datauploading_mod->insertGLData($data);
            //$this->db->query();
        }    
        
        $this->session->set_flashdata('process', 'Done Uploading');
        
        redirect('gl_datauploading'); 
    }*/
    
    public function uploadsl() {
        $datatype = $this->input->post('uploadtypesl');
        $datadate = $this->input->post('uploaddatesl');
        // Check and delete existing data 
        $this->gl_datauploading_mod->slcheckexistingdata($datatype,$datadate);
        
        
        
        $this->gl_datauploading_mod->insertSLData($datatype, $datadate);    
        
        $this->session->set_flashdata('process2', 'Done Uploading');   
        
        redirect('gl_datauploading');    
    }
    
}