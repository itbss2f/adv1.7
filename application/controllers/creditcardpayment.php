<?php
    
    class CreditCardPayment extends CI_Controller
    {
        function __construct()
        {
            
            parent::__construct();
            
        }
        
        function index()
        {
           
            $data['cdcr_type']    = array(
            
                                        array('type'=>''),
                                        array('type'=>'All'),
                                        array('type'=>'Branch'),
                                        array('type'=>'Subs'),
                                        array('type'=>'HO1 Classifieds'),
                                        array('type'=>'PRO'),
                                        array('type'=>'PR1'),
                                        array('type'=>'W/Tax'),
                                        array('type'=>'PR'),
                                        array('type'=>'PR-Branch'),
                                        array('type'=>'PR-All')
  
                                         );    
        //    $data['branches']     = $this->Branches->listOfBranch();                        
            $data['content_page'] = $this->load->view("cdcr/cdcr_index",$data,true);  
            $data['nav']          = $this->GlobalModel->moduleList();
            $data['sidebar_page'] = "";        
            $data['sidebar_page'] = "";
            $this->load->view('welcome_layout', $data); 
            
        }
    }