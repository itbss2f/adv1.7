<?php
class Exdeal_contract extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model("model_exdeal_advertisermaingroup/Exdeal_advertisingmaingroups");
         
        $this->load->model("model_exdeal_docreq/Exdeal_docreqs");
         
        $this->load->model("model_exdeal_contract/Exdeal_contracts"); 
        
        $this->load->model("model_exdeal_parameterfile/Exdeal_parameterfiles");   
         
            
    }
    
    public function index()
    {
       $navigation['data'] = $this->GlobalModel->moduleList();
       $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
       $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
       $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
       $layout['navigation'] = $this->load->view('navigation', $navigation, true);
       
       $data['results'] = $this->Exdeal_contracts->getcontracts();
       
       $data['breadcrumb'] = $this->load->view('breadcrumb', null, true); 
       
       $layout['content'] = $this->load->view('exdeal_contract/index', $data, true);
       
       $this->load->view('welcome_index', $layout);
    }  

    public function openform()
    {
       $data['action'] = $this->input->post('action'); 
       
       $data['id'] = $this->input->post('id');
       
       $data['advertiser_group'] = $this->Exdeal_advertisingmaingroups->getalladvertisergroup(); 
       
       $data['doc_req'] = $this->Exdeal_docreqs->getdocreqs(); 
       
       $data['approver'] = $this->Exdeal_parameterfiles->getall();
       
       
       //var_dump($data['contractnum']);    exit;
       if($data['action'] == 'update')
       {
           $data['agency'] = $this->Exdeal_advertisingmaingroups->getassignedadvertiser($data['id']); 
           
           $data['result']  = $this->Exdeal_contracts->selectcontract($data['id']);
           
           $data['barter_con']  = $this->Exdeal_contracts->getBarterAgreement($data['id']);
          
       }
        
       $html = $this->load->view('exdeal_forms/contract',$data,true); 
       
       echo json_encode($html);  
    }
    
    public function getgroupdetails()
    {
        $id = $this->input->post("id");
        
        $result['group_details'] = $this->Exdeal_advertisingmaingroups->getadvertisergroup($id);
        
        $data['result'] = $this->Exdeal_advertisingmaingroups->getassignedadvertiserlist($id);
        
        $result['html'] = $this->load->view('exdeal_contract/advertiser_list',$data,true);
        
        echo json_encode($result);
        
    }
    
    public function validateContractNo()
    {
       
        $contract_no = $this->db->escape_str($this->input->post('contract_no'));
        
        $con =  $this->Exdeal_contracts->validateContractNo($contract_no);   
      
        if ($con == TRUE)
        {
          echo "true";
        }
    }
    
    public function save()
    {
         $action = $this->input->post('form_action');
         
         $data = array(
                       'contract_id' => $this->db->escape_str($this->input->post('contract_id')),
                       'contract_type' => $this->db->escape_str($this->input->post('contract_type')),
                       'contract_no' => $this->db->escape_str($this->input->post('contract_no')),
                       'contract_date' => $this->db->escape_str($this->input->post('contract_date')),
                       'advertiser_group_id' => $this->db->escape_str($this->input->post('group_id')),
                       'advertising_agency' => $this->db->escape_str($this->input->post('advertising_agency')),
                       'advertiser_id' => $this->db->escape_str($this->input->post('advertiser_id')),
                       'amount' => str_replace(',','', $this->input->post('amount')),
                       'contact_person' => $this->db->escape_str($this->input->post('contact_person')),
                       'telephone' => $this->db->escape_str($this->input->post('telephone')),
                       'barter_ratio' => $this->db->escape_str($this->input->post('barter_ratio')),
                       'cash_ratio' => $this->db->escape_str($this->input->post('cash_ratio')),
                       'barter_request' => $this->db->escape_str($this->input->post('barter_request')),
                       'remarks' => $this->db->escape_str($this->input->post('remarks')),
                       'barter_condition' => $this->db->escape_str($this->input->post('barter_condition')),
                       'condition_type' => $this->db->escape_str($this->input->post('condition_type')),
                       'status' => $this->db->escape_str($this->input->post('status')),
                       'doc_req_id' => $this->db->escape_str($this->input->post('doc_req')),
                       'approver' => $this->db->escape_str($this->input->post('approver'))
                     
                       );
          $this->Exdeal_contracts->$action($data); 
          
    }
    
    public function remove()
    {
         $id = $this->input->post("id");
        
         $this->Exdeal_contracts->remove($id);
    }
    
    public function refresh()
    {
       $data['results'] = $this->Exdeal_contracts->getcontracts();
        
       $html = $this->load->view('exdeal_contract/contract_list', $data, true); 
       
       echo json_encode($html);
    }
    
    public function upload_file()
    {
        $error = "";
        $msg = "";
        $fileElementName = 'fileToUpload';
        $path = './uploads/exdeal/'; 
        $action = $this->input->post('action');
        $id = $this->input->post('id');
        if($action=='insert')
        {
           if(!empty($_FILES['fileToUpload']['name']))
           {
              $data['filename'] = $_FILES['fileToUpload']['name']; 
              $this->Exdeal_contracts->saveupload($_FILES['fileToUpload']['name']);                          
              $location = $path . $_FILES['fileToUpload']['name']; 
              move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $location); 
           } 
        }
        else
        {
            if(!empty($_FILES['fileToUpload']['name']))
            {
              $data['filename'] = $_FILES['fileToUpload']['name']; 
              $data['id'] = $id; 
              $this->Exdeal_contracts->updateAttachment($data);    
              $location = $path . $_FILES['fileToUpload']['name']; 
              move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $location);  
            } 
             
        }

         
    
    }
    
    public function search_customer()
    {
          
          $data['search'] = $this->input->post('search');   
          
          $result  = $this->Exdeal_contracts->search_customer($data);
            
          $entries = array();
          
          foreach($result as $result)
          {
              array_push($entries,  array('id'=>$result->id,'label'=>$result->cmf_name,'value'=>$result->cmf_name) );
          }  
        
          echo json_encode($entries);
    }
    
     public function search_contract()
    {
          
          $data['search'] = $this->input->post('search');   
          
          $data['advertiser'] = $this->input->post('advertiser');   
          
          $result  = $this->Exdeal_contracts->search_contract($data);
            
          $entries = array();
          
          foreach($result as $result)
          {
              array_push($entries,  array('id'=>$result->contract_no,'label'=>$result->contract_no) );
          }  
        
          echo json_encode($entries);
    }
    
    public function retrieve_barter_condition()
    {
         $id = $this->input->post('id');
         
         if(empty($id)){ $id = array("0"); } 

         $data['result'] = $this->Exdeal_contracts->retrieve_barter_condition($id);
         
         $html = $this->load->view('exdeal_contract/exdeal_bartercondition_list_by_cat',$data,true);
         
         echo json_encode($html);
          
    }

    
    public function contract_form($id = null)
    {
      
      $data['result'] = $this->Exdeal_contracts->selectcontract2($id); 
      
      $data['condition_list'] = $this->Exdeal_contracts->getConditionList($id);

      $html = $this->load->view("exdeal_forms/exdeal_agreement",$data,true);
                    
      require_once('thirdpartylib/htmlpdf/html2pdf.class.php');

      $html2pdf = new HTML2PDF('P','LETTER','fr');
            
      $html2pdf->WriteHTML($html);
            
      $html2pdf->Output('report.pdf');
    }

    public function autonumber() {
        $type = $this->input->post('let');
        $data['contractnum'] = $this->Exdeal_contracts->autonumbering($type);     
        
         echo json_encode($data);   
    }
    
}