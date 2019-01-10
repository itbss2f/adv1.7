<?php

class Activitylog extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_activitylogs/activitylogs','model_user/users'));
    }
    
    public function index() 
    
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['logs_report'] = $this->activitylogs->listOfActivitylogs();  
        $data['users'] = $this->users->list_of_logsUser();  
        //print_r2 ($data['users']) ;  exit(); 
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('activitylogs/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatelogs($datefrom, $dateto, $reporttype, $users) {

        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));

        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);
        $reportname = ""; 
        
        if ($reporttype == 1) {
        $reportname = "ACTIVITY LOGS";    
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);        
        $fields = array(
                            array('text' => '#', 'width' => .03, 'align' => 'center', 'bold' => true),
                            array('text' => 'Employee #', 'width' => .08, 'align' => 'center', 'bold' => true),
                            array('text' => 'Name', 'width' => .15, 'align' => 'left', 'bold' => true),
                            array('text' => 'Activity', 'width' => .12, 'align' => 'left'),
                            array('text' => 'Date', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Remarks', 'width' => .30, 'align' => 'left'),
                            array('text' => 'Email', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Branch', 'width' => .05, 'align' => 'center')
                        );
        } 

        $template = $engine->getTemplate();                         
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('REPORT TYPE - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields); 
        
        $list = $this->activitylogs->listOfActivitylogs($datefrom, $dateto, $reporttype ,$users);
        $no = 1;
        if ($reporttype == 1) {
            foreach ($list as $row) {  
                $result[] = array(array("text" => $no, 'align' => 'center'),
                                array("text" => $row['emp_id'], 'align' => 'center'),
                                array("text" => $row['username'], 'align' => 'left'),
                                array("text" => $row['activity'], 'align' => 'left'),
                                array("text" => $row['activitydate'], 'align' => 'left'),
                                array("text" => $row['remarks'], 'align' => 'left'),
                                array("text" => $row['email'], 'align' => 'left'),
                                array("text" => $row['branch'], 'align' => 'center')
                           );
                           $no += 1; 
                        }
                    }
                    
        $template->setData($result);

        $template->setPagination();

        $engine->display();                                        
                         
    } 
    
    public function generatelogs_export () {
        
        $datefrom = $this->input->get("datefrom");
        $dateto = $this->input->get("dateto");
        $reporttype = $this->input->get("reporttype");
        $users = $this->input->get("users");  
        
        $data['dlist'] = $this->activitylogs->listOfActivitylogs($datefrom, $dateto, $reporttype ,$users);
        
        $reportname = "";
        
            if ($reporttype == 1) {
        $reportname = "ACTIVITY LOGS";               
        }
        
        $data['reporttype'] = $reporttype;
        $data['reportname'] = $reportname; 
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;        
        $html = $this->load->view('activitylogs/activitylogs_excel', $data, true); 
        $filename ="REPORT TYPE-".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();    

    }
    
    
    public function generate_textfile() {
        
        $datefrom = $this->input->get("datefrom");
        $dateto = $this->input->get("dateto");
        $reporttype = $this->input->get("reporttype");
        $users = $this->input->get("users");
       
        $data['users'] = $users;
        $data['reporttype'] = $reporttype;
        $data['reportname'] = $reportname; 
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto; 

        $data = $this->activitylogs->listOfActivitylogs($datefrom, $dateto, $reporttype ,$users);
        
        $handle = fopen("file.txt", "w");  
       
        $reportname = "";
        
            if ($reporttype == 1) {
        $reportname = "ACTIVITY LOGS"; 
        echo "PHILIPPINE DAILY INQUIRER".PHP_EOL; 
        echo "REPORT TYPE - ".$reportname.PHP_EOL;
        echo "DATE FROM - ".$datefrom." TO ".$dateto.PHP_EOL;
        echo "".PHP_EOL;     
        echo '# Employee ID           Name          Activity       Date            Remarks             Email       Branch'; 
        echo "".PHP_EOL;     
        }
        
        //print_r2($data); 
         
        $counter = 1;
        $string = "";
        foreach ($data as $row) { 
            $string .= $counter.'   '.$row['emp_id'].'  '.$row['username'].'  '.$row['activity'].'  '.$row['activitydate'].'  '.$row['remarks'].'  '.$row['email'].'  '.$row['branch'].PHP_EOL;
            $counter += 1;
        }
        //echo $string;
       // exit;
        
        fwrite($handle, $string);
        
        
        fclose($handle);
        
        $filename ="REPORT TYPE-".$reportname.".txt";
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$filename);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('file.txt'));
        readfile('file.txt');
        exit;
        
    }
    
    



    
    
    
}  

  