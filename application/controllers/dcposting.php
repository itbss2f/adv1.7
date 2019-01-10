<?php

class DCPosting extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model('model_dbmemo/dbmemos');
        #$this->load->model('model_booking/bookingissuemodel');
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('dcposting/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);    
    } 
    
    public function postthisdmcm() {
        $datefrom = $this->input->post('fromdate');
        $todate = $this->input->post('todate');
        
        $data['list'] = $this->dbmemos->postDMCM($datefrom, $todate);
        
        $response['viewresult'] = $this->load->view('dcposting/viewresult', $data, true);
         
        echo json_encode($response); 
    } 
}    

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">

<html>
<head>
  <meta name="generator" content=
  "HTML Tidy for Windows (vers 14 February 2006), see www.w3.org">

  <title></title>
</head>

<body>
</body>
</html>
