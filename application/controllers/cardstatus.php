<?php
    class Cardstatus extends CI_Controller {     
        /*public function __construct()
        {
             parent::__construct();
             
             #$this->load->model('modelcard');             
        }*/  
        public function __construct()
        {
            parent::__construct();
            $this->sess = $this->authlib->validate(); 
            $this->load->model(array('model_maincustomer/maincustomers', 'model_card/modelcard'));       
            }  
            
            public function index(){   
            #echo "tst"; exit;         
            $navigation['data'] = $this->GlobalModel->moduleList();  
            $data['maincustomer'] = $this->maincustomers->listOfMainCustomerORDERNAME();        
            $data['user_id'] = $this->modelcard->userFiltering(); 
            //print_r2($data['user_id']); exit  ;     
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            $welcome_layout['content'] = $this->load->view('cardstatus/index', $data, true);
            $this->load->view('welcome_index', $welcome_layout);  
            }
                                                                
        public function savelogs(){ 
        
        /* Do the saving */
        
            $data['ctype'] = $this->input->post('ctype');        
            $data['logs_text'] = $this->input->post('logs');
            
            /* Conditioning Data */
            if ($data['ctype'] == 'A') {
                $data['client_id'] = $this->input->post('agencyfrom');    
            } else if ($data['ctype'] == 'C') {
                $data['client_id'] = $this->input->post('c_clientfrom');        
            } else if ($data['ctype'] == 'M') { 
                $data['client_id'] = $this->input->post('ac_mgroup');
            }
                   
            $this->modelcard->savelogs($data);
            /* End saving algo */
            
            /* Do the retreiving of logs */
            $data['user_id'] = $this->input->post('user_id');  
            $data['datefrom'] = $this->input->post('datefrom');        
            $data['dateto'] = $this->input->post('dateto');
            $logsdata['logs'] = $this->modelcard->getClientSpecificLogs($data);     
            
            $response['logsresult'] = $this->load->view('cardstatus/logsdata', $logsdata, true);
            
            
            echo json_encode($response);
    
        }
                
        public function search() {
            $data['ctype'] = $this->input->post('ctype');     
            $data['datefrom'] = $this->input->post('datefrom');        
            $data['dateto'] = $this->input->post('dateto');
            $data['user_id'] = $this->input->post('user_id');
       
             if ($data['ctype'] == 'A') {
                $data['client_id'] = $this->input->post('agencyfrom');    
            } else if ($data['ctype'] == 'C') {
                $data['client_id'] = $this->input->post('c_clientfrom');        
            } else if ($data['ctype'] == 'M') { 
                $data['client_id'] = $this->input->post('ac_mgroup');
            } else if ($data['user_id'] == '0') { 
                $data['user_id'] = $this->input->post('user_id');     
            }
            
            $logsdata['logs'] = $this->modelcard->getClientSpecificLogs($data); 
             
            $response['logsresult'] = $this->load->view('cardstatus/logsdata', $logsdata, true);
            
            echo json_encode($response);
        
        }
        
        public function exportToTxtFile(){
            
            $data['ctype'] = $this->input->get('ctype');     
            $data['datefrom'] = $this->input->get('datefrom');        
            $data['dateto'] = $this->input->get('dateto');
            $data['user_id'] = $this->input->get('userid');   
       
             if ($data['ctype'] == 'A') {
                $data['client_id'] = $this->input->get('agencyfrom');    
            } else if ($data['ctype'] == 'C') {
                $data['client_id'] = $this->input->get('c_clientfrom');        
            } else if ($data['ctype'] == 'M') { 
                $data['client_id'] = $this->input->get('ac_mgroup');
            }      
            $client = $this->modelcard->getClientData($data);
            $logs = $this->modelcard->getClientSpecificLogs($data); 
            
            $filename = 'cardlogs'.Date('YmdHms'); 

            /* Creating Txt File and Force User to download */
            header('Content-disposition: attachment; filename='.$filename.'.txt');
            header('Content-type: text/plain');

            if (empty($logs)) {
                echo "No Logs Result";
            } else {
                echo "CREDIT & COLLECTION CARD STATUS LOGS";
                echo "\r\n";
                echo $client['cname'];
                echo "\r\n\r\n"; 
                
                foreach ($logs as $text) {
                    echo $text['firstname'].' '.$text['lastname'];
                    echo "\r\n";
                    echo $text['logs'];
                    echo "\r\n";
                    echo date_format(date_create($text['dtime']),"l jS \of F Y h:i:s A");
                    echo "\r\n\r\n";
                }
            }
        }
        
    }