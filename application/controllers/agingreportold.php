<?php

class Agingreport extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->sess = $this->authlib->validate(); 		
        $this->load->model(array('model_agingreport/agingreports'));   	 
        
	}
    
    public function index() {
    
        $navigation['data'] = $this->GlobalModel->moduleList();               
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['adtype'] = $this->agingreports->listAdtype();         
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('agingreports/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom = null, $reporttype = 0) {
        set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library'))); 
        
        $this->load->library('Crystal', null, 'Crystal');
        $reportname = "";
        $datename = date("F d, Y", strtotime($datefrom));
        $fields = array();
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        
        $datestring = 'as of : '.$datename;        
        switch ($reporttype) {
            case 1:
            $reportname = "(Detailed)";
            break;
        }
        
        $fields = array(
                            array('text' => 'particulars', 'width' => .27, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'total amount due', 'width' => .08, 'align' => 'right'),
                            array('text' => 'current', 'width' => .08, 'align' => 'right'),
                            array('text' => '30 days', 'width' => .08, 'align' => 'right'),
                            array('text' => '60 days', 'width' => .08, 'align' => 'right'),
                            array('text' => '90 days', 'width' => .08, 'align' => 'right'),
                            array('text' => '120 days', 'width' => .08, 'align' => 'right'),
                            array('text' => 'over 120 days', 'width' => .08, 'align' => 'right'),
                            array('text' => 'over-payment', 'width' => .08, 'align' => 'right')
                        );
        
        $template = $engine->getTemplate();          
            
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);

        $template->setText('AGING OF ACCOUNTS RECEIVABLE '.$reportname, 10);
        
        $template->setText($datestring, 9);                

        $template->setFields($fields);
         
        $result = array();   
        
        $val['datefrom'] = $datefrom;        
        $val['reporttype'] = abs($reporttype);
                
        $data_value = $this->agingreports->age_query($val);
              
        //$result[] = array('Global Inter Active Solutions Inc.', '00555802', '154,486.18', '8,888,888.88');    
        $data = $data_value; #$data_value['data']; 
        
        
        /* Variables */
        $totalamountdue = 0; 
        
        /* End Variables */
        foreach ($data as $x => $rowhead) {
            $result[] = array(array("text" => "     ".strtoupper($x), "align" => "left"));
            $totalamountdue = 0;
            foreach ($rowhead as $row) {
                $result[] = array("", $row["ao_sinum"], 
                                      number_format($row["totalamountdue"], 2, ".", ","),
                                      number_format($row["aging_current"], 2, ".", ","), 
                                      number_format($row["aging_30"], 2, ".", ","),  
                                      number_format($row["aging_60"], 2, ".", ","),  
                                      number_format($row["aging_90"], 2, ".", ","),  
                                      number_format($row["aging_120"], 2, ".", ","),  
                                      number_format($row["aging_over120"], 2, ".", ","),  
                                      "");
                $totalamountdue += $row["totalamountdue"];
            }
            $result[] = array(array("text" => "     sub-total --- ".strtoupper($x), "align" => "left"), "", array("text" => number_format($totalamountdue, 2, ".", ","), "style" => true));
            $result[] = array("");            
        }
        
        //exit;
        
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }
    
    public function test($date = null) {
        $this->agingreports->statement_aging_for_account_receivable_advertising($date);
    }

	/*public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList();   	
		$data['reports'] = $this->agingreports->list_Aging_Reports();		
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('agingreports/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}
	
	public function newdata() {
		$response['newdata_view'] = $this->load->view('agingreports/newdata', null, true);
		echo json_encode($response);
	}

	public function savenewreports() {
		$data['title'] = $this->input->post('title');
		$data['description'] = $this->input->post('description');
		$data['sql_query'] = $this->input->post('sqlquery');
		$data['formula'] = $this->input->post('formula');
		$data['field_name'] = $this->input->post('fname');
		$data['field_align'] = $this->input->post('falign');
		$data['field_size'] = $this->input->post('fsize');
		
		$this->agingreports->saveNewData($data);

		$msg = "You successfully save Aging - Report";

		$this->session->set_flashdata('msg', $msg);

		redirect('agingreport');
	}

	public function editdata() {		
		$id = $this->input->post('id');
		$data['data'] = $this->agingreports->getData($id);
		$response['editdata_view'] = $this->load->view('agingreports/editdata', $data, true);
		echo json_encode($response);
	}

	public function update($id) {	
		$data['title'] = $this->input->post('title');
		$data['description'] = $this->input->post('description');
		$data['sql_query'] = $this->input->post('sqlquery');
		$data['formula'] = $this->input->post('formula');
		$data['field_name'] = $this->input->post('fname');
		$data['field_align'] = $this->input->post('falign');
		$data['field_size'] = $this->input->post('fsize');
		$this->agingreports->saveupdateNewData($data, abs($id));

		$msg = "You successfully update Aging - Report";

		$this->session->set_flashdata('msg', $msg);

		redirect('agingreport');
	}
	
	public function removedata($id) {		
		$this->agingreports->removeData(abs($id));
		redirect('agingreport');
	}*/           //Old
}
