<?php

class Billing_dash extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_billing_dash/mod_billing_dash');
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['datefrom'] = DATE('Y-m-01');
        $data['dateto'] = DATE('Y-m-d');
        $salesadtype['sales'] = $this->mod_billing_dash->getSalesAdtype($data['datefrom'], $data['dateto']);
        $data['salesadtype'] =  $this->load->view('billing_dashboard/salesadtype', $salesadtype, true); 
        $data['totalsalesadtype'] =  $this->mod_billing_dash->totalSaleAdtype($data['datefrom'], $data['dateto']);      
        $chargewoinv['chargewoinv'] = $this->mod_billing_dash->getChargeswoInv($data['datefrom'], $data['dateto']); 
        //$data['totalcountinv'] = count($chargewoinv['chargewoinv']);
        $data['chargeswoinv'] =  $this->load->view('billing_dashboard/chargeswoinv', $chargewoinv, true);       
        $unpag['unpagdata'] = $this->mod_billing_dash->getUnpaginatedAds($data['datefrom'], $data['dateto']); 
        $data['unpaganate'] =  $this->load->view('billing_dashboard/unpaganatedata', $unpag, true); 
        $data['totalcountunpag'] = count($unpag['unpagdata']);   
        $bookcount['booklist'] = $this->mod_billing_dash->getUserBookingCount($data['datefrom'], $data['dateto']); 
        $data['userbookingcounter'] =  $this->load->view('billing_dashboard/userbookingcounter', $bookcount, true);    
        $data['totalcountbook'] = $this->mod_billing_dash->countUserBooking($data['datefrom'], $data['dateto']);    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);   
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('billing_dashboard/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
        
    }
    
    public function realTimeRetrieve() {

        /*$newdata['book'] = $this->mod_billing_dash->getNewBooking();
        $response['newbooking'] = $this->load->view('billing_dashboard/newbooking', $newdata, true);
        
        $dataenu['totalbookingenu'] = $this->mod_billing_dash->getTotalBookingEnu();
        $dataenu['totalbookingenutoday'] = $this->mod_billing_dash->getTotalBookingEnuToday(); 
        $dataenu['totalbooking'] = $this->mod_billing_dash->getTotalBooking();  
        $response['newbookingreal'] = $this->load->view('billing_dashboard/newbooking-real', $dataenu, true);
        
        $datapaytype['bookpaytype'] = $this->mod_billing_dash->getTotalBookingPayType();   
        $response['bookingpaytype'] = $this->load->view('billing_dashboard/booking-paytype', $datapaytype, true); */   
        
        $data['datefrom'] = DATE('Y-m-01');
        $data['dateto'] = DATE('Y-m-d');
        $salesadtype['sales'] = $this->mod_billing_dash->getSalesAdtype($data['datefrom'], $data['dateto']);
        $response['salesadtype'] =  $this->load->view('billing_dashboard/salesadtype', $salesadtype, true);   
        
        $chargewoinv['chargewoinv'] = $this->mod_billing_dash->getChargeswoInv($data['datefrom'], $data['dateto']); 
        //$response['chargewoinvtotalcount'] = count($chargewoinv['chargewoinv']);
        $response['chargeswoinv'] =  $this->load->view('billing_dashboard/chargeswoinv', $chargewoinv, true); 
        
        $unpag['unpagdata'] = $this->mod_billing_dash->getUnpaginatedAds($data['datefrom'], $data['dateto']); 
        $response['unpaganate'] =  $this->load->view('billing_dashboard/unpaganatedata', $unpag, true); 
        $response['totalcountunpag'] = count($unpag['unpagdata']);    
        
        $bookcount['booklist'] = $this->mod_billing_dash->getUserBookingCount($data['datefrom'], $data['dateto']); 
        $response['userbookingcounter'] =  $this->load->view('billing_dashboard/userbookingcounter', $bookcount, true);    
        //$response['totalcountbook'] = count($bookcount['booklist']);                               
         
           
        echo json_encode($response);
    }
    
}
  
?>
