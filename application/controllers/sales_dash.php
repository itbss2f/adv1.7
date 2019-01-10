<?php

class Sales_dash extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model('model_aereport/aereports');
    }
    
    
    public function getAEInfomation($ae = null) {
        
        $navigation['data'] = $this->GlobalModel->moduleList();          
        #$data['adsize'] = $this->adsizes->listOfSize(); 
        $datefrom = date('Y-m-01');
        $dateto = date('Y-m-d');
        $aeid = $ae;
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $limit = 10;

        $data['aeid'] = $ae;
        $data['aename'] = $this->aereports->getAEName($ae);
        $data_topclient['data'] = $this->aereports->getAETopClient($datefrom, $dateto, $aeid, $limit, 'A');
        $data_topagency['data'] = $this->aereports->getAETopAgency($datefrom, $dateto, $aeid, $limit, 'A');
        $data_topdirectads['data'] = $this->aereports->getAETopDirectads($datefrom, $dateto, $aeid, $limit, 'A');
        $data_topadtype['data'] = $this->aereports->getAETopAdtype($datefrom, $dateto, $aeid, $limit, 'A'); 
        $data['totalsales'] = $this->aereports->getAETotalSales($datefrom, $dateto, $aeid, $limit, 'A');
        
        $data['topclient'] = $this->load->view('sales_dash/topclient', $data_topclient, true);   
        $data['topagency'] = $this->load->view('sales_dash/topagency', $data_topagency, true);   
        $data['topdirectads'] = $this->load->view('sales_dash/topdirectads', $data_topdirectads, true);   
        $data['topadtype'] = $this->load->view('sales_dash/topadtype', $data_topadtype, true);
 
      
        $response['aeinfo'] =  $this->load->view('sales_dash/aeinfo', $data, true);   
        
        echo json_encode($response);
    }
    
    public function aeSalesInformation() {    
        $navigation['data'] = $this->GlobalModel->moduleList();          
        #$data['adsize'] = $this->adsizes->listOfSize(); 
        $datefrom = date('Y-m-01');
        $dateto = date('Y-m-d');
        $aeid = $this->input->post('aeid');
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $limit = 10;
        
        $data_topclient['data'] = $this->aereports->getAETopClient($datefrom, $dateto, $aeid, $limit, 'A');
        $data_topagency['data'] = $this->aereports->getAETopAgency($datefrom, $dateto, $aeid, $limit, 'A');
        $data_topdirectads['data'] = $this->aereports->getAETopDirectads($datefrom, $dateto, $aeid, $limit, 'A');
        $data_topadtype['data'] = $this->aereports->getAETopAdtype($datefrom, $dateto, $aeid, $limit, 'A'); 
        $data['totalsales'] = $this->aereports->getAETotalSales($datefrom, $dateto, $aeid, 'A');
        
        $data['topclient'] = $this->load->view('sales_dash/topclient', $data_topclient, true);   
        $data['topagency'] = $this->load->view('sales_dash/topagency', $data_topagency, true);   
        $data['topdirectads'] = $this->load->view('sales_dash/topdirectads', $data_topdirectads, true);   
        $data['topadtype'] = $this->load->view('sales_dash/topadtype', $data_topadtype, true);
 
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('sales_dash/index3', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        #$data['adsize'] = $this->adsizes->listOfSize(); 
        $datefrom = date('Y-m-01');
        $dateto = date('Y-m-d');
        $aeid = $this->session->userdata('authsess')->sess_id;   
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $limit = 10;
        
        $data_topclient['data'] = $this->aereports->getAETopClient($datefrom, $dateto, $aeid, $limit, 'A');
        $data_topagency['data'] = $this->aereports->getAETopAgency($datefrom, $dateto, $aeid, $limit, 'A');
        $data_topdirectads['data'] = $this->aereports->getAETopDirectads($datefrom, $dateto, $aeid, $limit, 'A');
        $data_topadtype['data'] = $this->aereports->getAETopAdtype($datefrom, $dateto, $aeid, $limit, 'A'); 
        $data['totalsales'] = $this->aereports->getAETotalSales($datefrom, $dateto, $aeid, $limit, 'A');
        
        $data['topclient'] = $this->load->view('sales_dash/topclient', $data_topclient, true);   
        $data['topagency'] = $this->load->view('sales_dash/topagency', $data_topagency, true);   
        $data['topdirectads'] = $this->load->view('sales_dash/topdirectads', $data_topdirectads, true);   
        $data['topadtype'] = $this->load->view('sales_dash/topadtype', $data_topadtype, true);
 
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('sales_dash/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function filterData() {
        $rettype = $this->input->post('rettype');
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $limit = $this->input->post('toprank');
        $actual = $this->input->post('actual');
        $aeid = $this->input->post('aeid');
        
        if ($rettype == 0) {
            $data_topclient['data'] = $this->aereports->getAETopClient($datefrom, $dateto, $aeid, $limit, $actual);
            $data_topagency['data'] = $this->aereports->getAETopAgency($datefrom, $dateto, $aeid, $limit, $actual);  
            $data_topdirectads['data'] = $this->aereports->getAETopDirectads($datefrom, $dateto, $aeid, $limit, $actual); 
            $data_topadtype['data'] = $this->aereports->getAETopAdtype($datefrom, $dateto, $aeid, $limit, $actual);     
            $response['topclient'] = $this->load->view('sales_dash/topclient', $data_topclient, true);    
            $response['topagency'] = $this->load->view('sales_dash/topagency', $data_topagency, true);    
            $response['topdirectads'] = $this->load->view('sales_dash/topdirectads', $data_topdirectads, true);    
            $response['topadtype'] = $this->load->view('sales_dash/topadtype', $data_topadtype, true);    
            $response['totalsales'] = $this->aereports->getAETotalSales($datefrom, $dateto, $aeid, $limit, $actual);     
            $response['datefrom'] = $datefrom;     
            $response['dateto'] = $dateto;    
            $total = $this->aereports->getAETotalSales($datefrom, $dateto, $aeid, $limit, $actual);      
            $response['totalsales'] = number_format($total, 2, '.',',');   
        } else if ($rettype == 1) {
            $data_topclient['data'] = $this->aereports->getAETopClient($datefrom, $dateto, $aeid, $limit, $actual);   
            $response['topclient'] = $this->load->view('sales_dash/topclient', $data_topclient, true);             
        } else if ($rettype == 2) {
            $data_topagency['data'] = $this->aereports->getAETopAgency($datefrom, $dateto, $aeid, $limit, $actual);  
            $response['topagency'] = $this->load->view('sales_dash/topagency', $data_topagency, true);   
        } else if ($rettype == 5) {
            $response['datefrom'] = $datefrom;
            $response['dateto'] = $dateto;
            $total = $this->aereports->getAETotalSales($datefrom, $dateto, $aeid, $limit, $actual);     
            $response['totalsales'] = number_format($total, 2, '.',',');
        } else if ($rettype == 3) {
            $data_topdirectads['data'] = $this->aereports->getAETopDirectads($datefrom, $dateto, $aeid, $limit, $actual);   
            $response['topdirectads'] = $this->load->view('sales_dash/topdirectads', $data_topdirectads, true);    
        } else if ($rettype == 4) {
            $data_topadtype['data'] = $this->aereports->getAETopAdtype($datefrom, $dateto, $aeid, $limit, $actual);    
            $response['topadtype'] = $this->load->view('sales_dash/topadtype', $data_topadtype, true); 
        }
        
        echo json_encode($response);
   }
   
   
      public function exceltopclient() {
          
        $datefrom = $this->input->get('datefrom');
        $dateto = $this->input->get('dateto');   
        $reporttype = $this->input->get('reporttype');   
        $aeid = $this->input->get('aeid');
        $actual = $this->input->get("actual");
        $limit = 2000;      

        $data['data'] = $this->aereports->getAESalesExport($datefrom, $dateto, $aeid, $limit, $reporttype, $actual); 
        #print_r2($data); exit;

        $reportname = "";

        if ($actual == 'A') {
        $reportname = "ACTUAL";               
        } else if ($actual == 'F') {
        $reportname = "FORECAST";   
        }

        
        $reporttype = "Top Client";      
        
        $data['reporttype'] = $reporttype;   
        $data['reportname'] = $reportname;   
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data['toprank'] = $limit;
        $data['actual'] = $actual; 

        $html = $this->load->view('sales_dash/exceltopclient', $data, true); 
        $filename ="Sales".$reporttype.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
      }
   
   
       public function exceltopagency() {
          
        $datefrom = $this->input->get('datefrom'); 
        $dateto = $this->input->get('dateto');   
        $reporttype = $this->input->get('reporttype');   
        $aeid = $this->input->get('aeid');
        $actual = $this->input->get("actual");
        $limit = 2000;      

        $data['data'] = $this->aereports->getAESalesExport($datefrom, $dateto, $aeid, $limit, $reporttype, $actual); 

        $reportname = "";
        
        if ($actual == 'A') {
        $reportname = "ACTUAL";               
        } else if ($actual == 'F') {
        $reportname = "FORECAST";   
        }

        $reporttype = "Top Agency";      
         
        $data['reporttype'] = $reporttype;    
        $data['reportname'] = $reportname; 
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data['toprank'] = $limit;
        $data['actual'] = $actual;
        $html = $this->load->view('sales_dash/exceltopagency', $data, true); 
        $filename ="Sales".$reporttype.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
      }
   
       public function exceltopdirect() {
          
        $datefrom = $this->input->get('datefrom');
        $dateto = $this->input->get('dateto');   
        $reporttype = $this->input->get('reporttype');   
        $aeid = $this->input->get('aeid');
        $actual = $this->input->get("actual");
        $limit = 2000;      
        
        $data['data'] = $this->aereports->getAESalesExport($datefrom, $dateto, $aeid, $limit, $reporttype, $actual);

        $reportname = "";
        
        if ($actual == 'A') {
        $reportname = "ACTUAL";               
        } else if ($actual == 'F') {
        $reportname = "FORECAST";   
        }
         
        $reporttype = "Top Direct Ads";      
         
        $data['reporttype'] = $reporttype;    
        $data['reportname'] = $reportname; 
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data['toprank'] = $limit;
        $data['actual'] = $actual;
        $html = $this->load->view('sales_dash/exceltopdirect', $data, true); 
        $filename ="Sales".$reporttype.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
    }

    public function exceltopadtype() {
          
        $datefrom = $this->input->get('datefrom');
        $dateto = $this->input->get('dateto');   
        $reporttype = $this->input->get('reporttype');   
        $aeid = $this->input->get('aeid');
        $actual = $this->input->get("actual");
        $limit = 2000;      
        $data['data'] = $this->aereports->getAESalesExport($datefrom, $dateto, $aeid, $limit, $reporttype, $actual); 

        $reportname = "";
        
        if ($actual == 'A') {
        $reportname = "ACTUAL";               
        } else if ($actual == 'F') {
        $reportname = "FORECAST";   
        }

        $reporttype = "Top Adtype";  
           
        $data['reporttype'] = $reporttype;  
        $data['reportname'] = $reportname;   
        $data['datefrom'] = $datefrom;  
        $data['dateto'] = $dateto;
        $data['toprank'] = $limit; 
        $data['actual'] = $actual; 
        $html = $this->load->view('sales_dash/exceltopadtype', $data, true);  
        $filename ="Sales".$reporttype.".xls";     
        header("Content-type: application/vnd.ms-excel");     
        header('Content-Disposition: attachment; filename='.$filename);     
        echo $html ;                        
        exit();            
    }

    public function viewmyclient() {
        $this->load->model('model_customer/customers');
        
        $aeid = $this->session->userdata('authsess')->sess_id;    
        
        $data['list'] = $this->customers->getCustomerPerAE($aeid);
        
        $this->load->view('sales_dash/myclient', $data);
                    
    }
    
    public function editAEClient() {
        $this->load->model('model_customer/customers');    
        $this->load->model('model_industry/industries');    
        
        $id = $this->input->post('id');  
        $data['data'] = $this->customers->getDataCustomerPerAE($id);
        $data['industry'] = $this->industries->listOfIndustry(); 
        $response['editdata_view'] = $this->load->view('sales_dash/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function aeupdate($id) {
        $this->load->model('model_customer/customers');    
        # AE Contact Detailed

        $data['ae_name1'] = $this->input->post('contact1_name');
        $data['ae_position1'] = $this->input->post('contact1_position');
        $data['ae_email1'] = $this->input->post('contact1_email');
        $data['ae_contact1'] = $this->input->post('contact1_contactnumber');
        $data['ae_name2'] = $this->input->post('contact2_name');
        $data['ae_position2'] = $this->input->post('contact2_position');
        $data['ae_email2'] = $this->input->post('contact2_email'); 
        $data['ae_contact2'] = $this->input->post('contact2_contactnumber');
        
        $data['cmf_industry'] = $this->input->post('industry');
        $this->customers->updateccAEDetail($data, $id);
  
        redirect('sales_dash/viewmyclient');
        /*$reporttype = "Top Direct";      
         
        $data['reporttype'] = $reporttype;   
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data['toprank'] = $limit;
        
        $html = $this->load->view('sales_dash/exceltopdirect', $data, true); 
        $filename ="Sales".$reporttype.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);     
        echo $html ;
        exit();*/
     }
    
}

?>







