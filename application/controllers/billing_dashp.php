<?php
class  Billing_Dashp extends CI_Controller 
{     
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_billing_dash/modelbillingdashp');          
    }
    
    public function index ()
    {       

        $navigation['data'] = $this->GlobalModel->moduleList();          
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $datefrom = DATE('Y-m-01');
        $dateto = DATE('Y-m-d');
        
        $data1['salesAEBM']  = $this->modelbillingdashp->getsalesAEBM($datefrom, $dateto);  
        $data2['salesPerBranch']  = $this->modelbillingdashp->getsalesPerBranch($datefrom, $dateto);  
        $data3['BookingCounter']  = $this->modelbillingdashp->getBookingCounter($datefrom, $dateto);  
        $data4['aeproduction']  = $this->modelbillingdashp->getAEProduction($datefrom, $dateto);  
        $data5['libre']  = $this->modelbillingdashp->getLibre($datefrom, $dateto);  
        $data6['SuperCeeding']  = $this->modelbillingdashp->getSuperCeeding($datefrom, $dateto);  
        $data7['supplement']  = $this->modelbillingdashp->getSupplement($datefrom, $dateto);  
        
        $data['salesaebmview'] = $this->load->view('billing_dashp/salesaebm', $data1, true);
        $data['salesperbranchview'] = $this->load->view('billing_dashp/salesperbranch', $data2, true);
        $data['BookingCounterview'] = $this->load->view('billing_dashp/bookingcounterclass', $data3, true);
        $data['aeproddisplayview'] = $this->load->view('billing_dashp/aeproddisplay', $data4, true);
        $data['libreview'] = $this->load->view('billing_dashp/aeprodlibre', $data5, true);
        $data['superceedview'] = $this->load->view('billing_dashp/superced', $data6, true); 
        $data['supplementview'] = $this->load->view('billing_dashp/supplement', $data7, true);         
        // display
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('billing_dashp/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);     
    }
    
    public function getRealtimeDash() {
        
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        
        $data1['salesAEBM']  = $this->modelbillingdashp->getsalesAEBM($datefrom, $dateto);  
        $data2['salesPerBranch']  = $this->modelbillingdashp->getsalesPerBranch($datefrom, $dateto);  
        $data3['BookingCounter']  = $this->modelbillingdashp->getBookingCounter($datefrom, $dateto);  
        $data4['aeproduction']  = $this->modelbillingdashp->getAEProduction($datefrom, $dateto);  
        $data5['libre']  = $this->modelbillingdashp->getLibre($datefrom, $dateto);  
        $data6['SuperCeeding']  = $this->modelbillingdashp->getSuperCeeding($datefrom, $dateto);    
        $data7['supplement']  = $this->modelbillingdashp->getSupplement($datefrom, $dateto);  
        
        $response['salesaebmview'] = $this->load->view('billing_dashp/salesaebm', $data1, true);
        $response['salesperbranchview'] = $this->load->view('billing_dashp/salesperbranch', $data2, true);
        $response['BookingCounterview'] = $this->load->view('billing_dashp/bookingcounterclass', $data3, true);
        $response['aeproddisplayview'] = $this->load->view('billing_dashp/aeproddisplay', $data4, true);
        $response['libreview'] = $this->load->view('billing_dashp/aeprodlibre', $data5, true);
        $response['superceedview'] = $this->load->view('billing_dashp/superced', $data6, true);
        $response['supplementview'] = $this->load->view('billing_dashp/supplement', $data7, true);
        
        
        echo json_encode($response);  
        
    }
}