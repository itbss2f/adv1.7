<?php
class RFA extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        $this->load->model(array('model_rfatype/rfatypes','model_rfa/rfas','model_empprofile/employeeprofiles'));
    }                                                          

    
    public function index() {
		#$this->load->model('model_empprofile/employeeprofiles');

		$data['rfalist'] = null;
        $data['aelist'] = $this->employeeprofiles->listEmpAcctExec();
		$data['rfatypes'] = $this->rfatypes->listOfRFATypes();

        #var_dump($data) ;  exit(); 
        
		$navigation['data'] = $this->GlobalModel->moduleList();   		
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$data['aiform'] = $this->load->view('aiforms/-noaiform', null, true);      
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('RFA/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);  
    }
    
    public function updateAllAdjustment() {   
        
        #$this->load->model('model_rfa/rfas');
        
        $id = mysql_escape_string($this->input->post('id'));     
        $data['ao_rfa_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('adjustment_amount')));
        $data['ao_rfa_adjstatus'] = mysql_escape_string($this->input->post('rfa_adjstatus')); 
        $data['ao_rfa_finalstatus'] = mysql_escape_string($this->input->post('rfa_finalstatus')); 
        $data['ao_rfa_aistatus'] = mysql_escape_string($this->input->post('rfa_aistatus')); 
        $data['ao_rfa_supercedingai'] = mysql_escape_string($this->input->post('rfa_supercedai')); 
        
        $this->rfas->updateThisAdjustment($data, $id);
        
        echo "Adjustment Successfully Updated!.";
    }
    
    public function updateOnlyAdjustment() {   
        
        #$this->load->model('model_rfa/rfas');
        
        $id = mysql_escape_string($this->input->post('id'));             
        $data['ao_rfa_aistatus'] = mysql_escape_string($this->input->post('rfa_aistatus')); 
        $data['ao_rfa_supercedingai'] = mysql_escape_string($this->input->post('rfa_supercedai')); 
        
        $this->rfas->updateThisAdjustment($data, $id);
        
        echo "Adjustment Successfully Updated!.";
    }
    
    public function saveRFA() {
        
        #$this->load->model('model_rfa/rfas');         
        
        $id = $this->uri->segment(3);    
        $data['ao_rfa_status'] = mysql_escape_string($this->input->post('foradjustment'));
        $data['ao_rfa_num'] = $this->rfas->maxRFANO();    
        $data['ao_rfa_date'] = mysql_escape_string($this->input->post('rfa_date'));
        $data['ao_rfa_type'] = mysql_escape_string($this->input->post('rfa_typecode'));
        $data['ao_rfa_amt'] = mysql_escape_string($this->input->post('rfa_amount'));
        $data['ao_rfa_findings'] = mysql_escape_string($this->input->post('rfa_findings'));
        $data['ao_rfa_adjustment'] = mysql_escape_string($this->input->post('rfa_possible'));
        $data['ao_rfa_person'] = mysql_escape_string($this->input->post('person'));
        $data['ao_rfa_reason'] = mysql_escape_string($this->input->post('responsiblename'));
        
        $this->rfas->saveRFA($data, $id);

    }
    
    public function ajxsaveRFA(){
        
        #$this->load->model('model_rfa/rfas');         
        
        $id = mysql_escape_string($this->input->post('id'));        
        $invoiceno = mysql_escape_string($this->input->post('invoiceno'));        
                
        if (mysql_escape_string($this->input->post('rfano')) == '' || mysql_escape_string($this->input->post('rfano')) == 0) {
            $data['ao_rfa_num'] = $this->rfas->maxRFANO();              
        } else {
            $data['ao_rfa_num'] = mysql_escape_string($this->input->post('rfano'));    
        }
        
        $data['ao_rfa_status'] = 1;   
        $data['ao_rfa_date'] = DATE('Y-m-d');   
        $data['ao_rfa_type'] = mysql_escape_string($this->input->post('typecode'));        
        $data['ao_rfa_findings'] = mysql_escape_string($this->input->post('findings'));
        $data['ao_rfa_adjustment'] = mysql_escape_string($this->input->post('possibleadjustment'));
        $data['ao_rfa_person'] = mysql_escape_string($this->input->post('person'));
        $data['ao_rfa_reason'] = mysql_escape_string($this->input->post('responsiblename'));
        
        $data['ao_rfa_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('adjustmentamt')));
        $data['ao_rfa_finalstatus'] = mysql_escape_string($this->input->post('signatories'));  
          
        $data['ao_rfa_aistatus'] = mysql_escape_string($this->input->post('rfastatus'));    
        $data['ao_rfa_supercedingai'] = mysql_escape_string($this->input->post('supercedingai'));            
        
        $this->rfas->updateRFA($id, $invoiceno, $data);  
    }
    
    public function pdfview() {
        
        $data['rfatype'] = $this->rfatypes->listOfRFATypes(); 
        
        $response['rfa_pdf'] = $this->load->view('rfa/pdfview_result', $data, true);

        echo json_encode($response);
        
        
    }
    
    public function searchRFA() {
        
        #$this->load->model('model_rfa/rfas');
        
        $find['complaint'] = mysql_escape_string($this->input->post('complaint'));
        $find['advertisername'] = mysql_escape_string($this->input->post('advertisername'));
        $find['agencyname'] = mysql_escape_string($this->input->post('agencyname'));
        $find['accountexec'] = mysql_escape_string($this->input->post('accountexec'));
        $find['invoiceno'] = mysql_escape_string($this->input->post('invoiceno'));
        $find['issuedatefrom'] = mysql_escape_string($this->input->post('issuedatefrom'));
        $find['issuedateto'] = mysql_escape_string($this->input->post('issuedateto'));
        $find['rfano'] = mysql_escape_string($this->input->post('rfano'));
        $find['rfano2'] = mysql_escape_string($this->input->post('rfano2'));
        $find['rfadatefrom'] = mysql_escape_string($this->input->post('rfadatefrom'));
        $find['rfadateto'] = mysql_escape_string($this->input->post('rfadateto'));
        $find['person'] = mysql_escape_string($this->input->post('person'));
        $find['responsible'] = mysql_escape_string($this->input->post('responsible'));
        $find['rfatypes'] = mysql_escape_string($this->input->post('rfatypes')); 
        
        $data['result'] = $this->rfas->searchRFA_List($find);
        $response['searchresult'] = $this->load->view('RFA/-searchresult', $data, true);
        
        #var_dump($data); echo'searchresult'; exit();
        
                   
        echo json_encode($response);
    }
    
   public function printToPDF(){
       
        $response['printtopdf_view'] = $this->load->view('RFA/pdfview_result', null, true);
        
        echo json_encode($response);

   } 
   
   public function createPDFFile($complaint,$advertisername,$agencyname,$accountexec,$invoiceno,$issuedatefrom,$issuedateto,$rfano,$rfadatefrom,$rfadateto,$person,$responsible,$rfatypes) {
        
       #$find = $this->load->model('model_rfa/rfas');
       
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));

        ini_set('memory_limit', -1);
        
        set_time_limit(0);    
        
        $find['complaint'] = $complaint; 
        $find['advertisername'] = $advertisername; 
        $find['agencyname'] = $agencyname; 
        $find['accountexec'] = $accountexec; 
        $find['invoiceno'] = $invoiceno; 
        $find['issuedatefrom'] = $issuedatefrom; 
        $find['issuedateto'] = $issuedateto; 
        $find['rfano'] = $rfano; 
        $find['rfadatefrom'] = $rfadatefrom; 
        $find['rfadateto'] = $rfadateto; 
        $find['person'] = $person; 
        $find['responsible'] = $responsible; 
        $find['rfatypes'] = $rfatypes;     
                                         
        if ($find['complaint'] == "null") {
            $find['complaint'] = null;    
        }
        if ($find['advertisername'] == "null") {
            $find['advertisername'] = null;    
        }
        if ($find['agencyname'] == "null") {
            $find['agencyname'] = null;    
        }
        if ($find['accountexec'] == "null"){
            $find['accountexec'] = null;
        }
        if ($find['invoiceno'] == "null"){
            $find['invoiceno'] = null;
        }
        if ($find['issuedatefrom'] == "null"){
            $find['issuedatefrom'] = null;
        }
        if ($find['issuedateto'] == "null"){
            $find['issuedateto'] = null;
        }
        if ($find['rfano'] == "null"){
            $find['rfano'] = null;
        }
        if ($find['rfadatefrom'] == "null"){
            $find['rfadatefrom'] = null;
        }
        if ($find['rfadateto'] == "null"){
            $find['rfadateto'] = null;
        }
        if ($find['person'] == "null"){
            $find['person'] = null;
        }
        if ($find['responsible'] == "null"){
            $find['responsible'] = null;
        }
        if ($find['rfatypes'] == "null"){
            $find['rfatypes'] = null;
        } 
        
/*        $find['complaint'] = $this->input->post('complaint');
        $find['advertisername'] = mysql_escape_string($this->input->post('advertisername'));
        $find['agencyname'] = mysql_escape_string($this->input->post('agencyname'));
        $find['accountexec'] = mysql_escape_string($this->input->post('accountexec'));
        $find['invoiceno'] = mysql_escape_string($this->input->post('invoiceno'));
        $find['issuedatefrom'] = mysql_escape_string($this->input->post('issuedatefrom'));
        $find['issuedateto'] = mysql_escape_string($this->input->post('issuedateto'));
        $find['rfano'] = mysql_escape_string($this->input->post('rfano'));
        $find['rfadatefrom'] = mysql_escape_string($this->input->post('rfadatefrom'));
        $find['rfadateto'] = mysql_escape_string($this->input->post('rfadateto'));
        $find['person'] = mysql_escape_string($this->input->post('person'));
        $find['responsible'] = mysql_escape_string($this->input->post('responsible'));
        $find['rfatypes'] = mysql_escape_string($this->input->post('rfatypes')); */
                   

        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);             

        $fields = array(array('text' => '#', 'width' => .01, 'align' => 'left', 'bold' => true),
                        array('text' => 'AO #', 'width' => .05, 'align' => 'left'),
                        array('text' => 'AO Date', 'width' => .05, 'align' => 'left'),
                        array('text' => 'ID', 'width' => .04, 'align' => 'left'),
                        array('text' => 'AO #', 'width' => .03, 'align' => 'left'),
                        array('text' => 'Issue Date', 'width' => .05, 'align' => 'left'),
                        array('text' => 'Rfa #', 'width' => .03, 'align' => 'left'),
                        array('text' => 'Rfa Date', 'width' => .05, 'align' => 'left'),
                        array('text' => 'Client Name', 'width' => .13, 'align' => 'left'),
                        array('text' => 'Agency Name', 'width' => .13, 'align' => 'left'),
                        array('text' => 'AE', 'width' => .08, 'align' => 'left'),
                        array('text' => 'Invoice No', 'width' => .05, 'align' => 'left'),
                        array('text' => 'Invoice Date', 'width' => .05, 'align' => 'left'), 
                        array('text' => 'RFA Findings', 'width' => .18, 'align' => 'left'),
                        array('text' => 'RFA Types', 'width' => .06, 'align' => 'left')
                               
                        );
        
        
        
        $template = $engine->getTemplate();                                                
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('Request for Adjustment', 10);
        #$template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                          
        $template->setFields($fields); 
                                             
        $data = $this->rfas->searchRFA_List($find);
        
        
            
                $no = 1;                                 
                foreach ($data as $row)  {          
                $result[] = array(array("text" => $no, 'align' => 'center'),
                                array("text" => $row['ao_sinum'],  'align' => 'left'),
                                array("text" => $row['ao_sidate'],  'align' => 'left'),
                                array("text" => $row['id'],  'align' => 'left'),
                                array("text" => $row['ao_num'],  'align' => 'left'),
                                array("text" => $row['ao_issuefrom'],  'align' => 'left'),
                                array("text" => $row['ao_rfa_num'],  'align' => 'left'),
                                array("text" => $row['ao_rfa_date'],  'align' => 'left'),
                                array("text" => $row['ao_payee'],  'align' => 'left'),
                                array("text" => $row['cmf_name'],  'align' => 'left'),
                                array("text" => $row['ae'],  'align' => 'left'),
                                array("text" => $row['ao_sinum'],  'align' => 'left'),
                                array("text" => $row['ao_sidate'],  'align' => 'left'),
                                array("text" => $row['ao_rfa_findings'],  'align' => 'left'),
                                array("text" => $row['rfatype_name'],  'align' => 'left')
                                                     
                           ); 
                                           
                           $no += 1;   
                } 
                
        
                                       
        $template->setData($result);
        $template->setPagination();
        $engine->display();
        
        #exit;
        
   }
   
   
   public function exportToExcel(){
       
        

        $find['complaint'] = $this->input->get("complaint"); 
        $find['advertisername'] = $this->input->get("advertisername");      
        $find['agencyname'] = $this->input->get("agencyname");      
        $find['accountexec'] = $this->input->get("accountexec");      
        $find['invoiceno'] = $this->input->get("invoiceno");      
        $find['issuedatefrom'] = $this->input->get("issuedatefrom");      
        $find['issuedateto'] = $this->input->get("issuedateto");      
        $find['rfano'] = $this->input->get("rfano");      
        $find['rfadatefrom'] = $this->input->get("rfadatefrom");      
        $find['rfadateto'] = $this->input->get("rfadateto");      
        $find['person'] = $this->input->get("searchperson");      
        $find['responsible'] = $this->input->get("responsible");      
        $find['rfatypes'] = $this->input->get("rfatypes");       
        
        
        if ($find['complaint'] == "null") {
            $find['complaint'] = null;    
        }
        if ($find['advertisername'] == "null") {
            $find['advertisername'] = null;    
        }
        if ($find['agencyname'] == "null") {
            $find['agencyname'] = null;    
        }
        if ($find['accountexec'] == "null"){
            $find['accountexec'] = null;
        }
        if ($find['invoiceno'] == "null"){
            $find['invoiceno'] = null;
        }
        if ($find['issuedatefrom'] == "null"){
            $find['issuedatefrom'] = null;
        }
        if ($find['issuedateto'] == "null"){
            $find['issuedateto'] = null;
        }
        if ($find['rfano'] == "null"){
            $find['rfano'] = null;
        }
        if ($find['rfadatefrom'] == "null"){
            $find['rfadatefrom'] = null;
        }
        if ($find['rfadateto'] == "null"){
            $find['rfadateto'] = null;
        }
        if ($find['person'] == "null"){
            $find['person'] = null;
        }
        if ($find['responsible'] == "null"){
            $find['responsible'] = null;
        }
        if ($find['rfatypes'] == "null"){
            $find['rfatypes'] = null;
        }  

        $data['complaint'] = $this->input->get('complaint');           
        $data['advertisername'] = $this->input->get('advertisername');           
        $data['agencyname'] = $this->input->get('agencyname');           
        $data['accountexec'] = $this->input->get('accountexec');           
        $data['invoiceno'] = $this->input->get('invoiceno');           
        $data['issuedatefrom'] = $this->input->get('issuedatefrom');           
        $data['issuedateto'] = $this->input->get('issuedateto');           
        $data['rfano'] = $this->input->get('rfano');           
        $data['rfadatefrom'] = $this->input->get('rfadatefrom');           
        $data['rfadateto'] = $this->input->get('rfadateto');           
        $data['searchperson'] = $this->input->get('searchperson');           
        $data['responsible'] = $this->input->get('responsible');           
        $data['rfatype s'] = $this->input->get('rfatypes');    
        
        
               
        
       
                    
        $data['dlist'] = $this->rfas->searchRFA_List($find);
        
       /* $find['issuedatefrom'] = $this->input->get("issuedatefrom");      
        $find['issuedateto'] = $this->input->get("issuedateto");*/
        
       
        
        #var_dump($data); echo $find ; exit();
   
                                           
        $html = $this->load->view('RFA/excelfile', $data, true); 
        $filename ="Request for Adjustment.xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();    
   
   
   
   
     
                                  
   }
    
              
    
}
