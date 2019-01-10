<?php 

class Transaction_logs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_transaction_logs/model_transactions_logs');
        $this->load->model('model_user/users');
    }
    
    public function index () {
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['audit'] = $this->model_transactions_logs->getDataReportList();
        //$data['user_list'] = $this->model_transactions_logs->getTransactionUser();
        //print_r2($data['user_list']); exit;
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('transactions_logs/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);
        
    }
    
    
    public function getTransactionUser() {
        $reporttype = $this->input->post('reporttype');
        
        $response['user_list'] = $this->model_transactions_logs->getTransactionUser($reporttype);
        
        echo json_encode($response);
    }
   
   /* public function getData() {
        
        $search = $this->input->post('search');
        
        $response ['audit'] = $this->model_transactions_logs->list_of_user_id($search);

        echo json_encode($response);
        
        
    }
    */
    
    public function generatereport($datefrom, $dateto, $reporttype, $user_id){
        
        set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));     
        #set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        $reportname = ""; 
        
        switch ($reporttype) {
            case 1:
                $reportname = "BOOKING MAIN";        
            break;   
            case 2:
                $reportname = "BOOKING DETAILED";        
            break;
            case 3:
                $reportname = "OR MAIN";        
            break;   
            case 4:
                $reportname = "OR PAYMENT TYPE";        
            break;
            case 5:
                $reportname = "OR APPLICATION";        
            break;  
    
        }
        
        if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3 || $reporttype == 4 || $reporttype == 5) {
            $fields = array(
                            array('text' => 'USER ID', 'width' => .10, 'align' => 'center'),
                            array('text' => 'DATE/TIME', 'width' => .10, 'align' => 'center'),
                            array('text' => 'ACTIONS', 'width' => .10, 'align' => 'center'),
                            array('text' => 'USER', 'width' => .10, 'align' => 'center'),
                            array('text' => 'AUDIT TRAIL', 'width' => .10, 'align' => 'center'),
                            );
        } 
        
        $template = $engine->getTemplate();                         
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('LOGS - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields);
        
        
        $list = $this->model_transactions_logs->getDataReportList($datefrom, $dateto, $reporttype, $user_id);
         
        if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3 || $reporttype == 4 || $reporttype == 5){

            foreach ($list as $row) {
                        
                        $result[] = array(
                                    array('text' => $row['user_id'], 'align' => 'left'),
                                    array('text' => $row['datetime'], 'align' => 'left'),
                                    array('text' => $row['actions'], 'align' => 'left'),
                                    array('text' => $row['fullname'], 'align' => 'left'),    
                                    array('text' => $row['audittrail'], 'align' => 'left')    
                                    );
                                                                                            
                }  
            }

        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
        
    }
       
}             














?>