<?php
class Cdrform extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_cdr/Cdrforms');
    }
    
    public function index()
    {
          $navigation['data'] = $this->GlobalModel->moduleList();
    
          $layout['navigation'] = $this->load->view('navigation', $navigation, true);                                       
            
          $data['breadcrumb'] = $this->load->view('breadcrumb', null, true); 
       
          $layout['content'] = $this->load->view('cdrform/index', $data, true);
       
          $this->load->view('welcome_index', $layout);  
    }
    
    public function generate()
    {
        $data['ao_num'] = $this->input->post('ao_no');
        
        $data['issue_date'] = $this->input->post('issue_date');
        
        $data['result'] = $this->Cdrforms->generate($data);
        
        $html = $this->load->view('cdrform/list',$data,true);
        
        echo json_encode($html);
    }
    
    public function form()
    {
        $data['id'] = $this->input->post('id');   
                                                 
        $data['cdr_num'] = $this->Cdrforms->getlastcdrno();    
                                                       
        $data['cdr_type'] = $this->Cdrforms->getAllCdr();
        
      /*  $data['type_ad'] = $this->Cdrforms->getAdtype(); 
                                                             */
        
        $data['result'] = $this->Cdrforms->form($data);     
            
        $html = $this->load->view('cdrform/cdrform',$data,true);
        
        echo json_encode($html);
    }
    
    public function savecdr()
    {
        $data["id"] = $this->input->post("ao_p_id");
        
        $data["cdr_type"] = $this->input->post("cdr_type");
        
        $data["cdr_no"] = $this->input->post("cdr_no");
        
        $data["cdrdate"] = $this->input->post("cdrdate");
        
        $data["nature_of_complaint"] = $this->input->post("nature_of_complaint");
        
        $data["findings"] = $this->input->post("findings");
        
        $data["responsible"] = $this->input->post("responsible");
        
        $this->Cdrforms->updateCdr($data);
        
        return "TRUE";
    }
    
    public function printCdr()
    {
        //  $data['result'] = $this->Exdeal_reports->$report_type($data);   
        
        $data["id"] = $this->input->get("ao_p_id");     
        
        $data['result'] = $this->Cdrforms->form($data);           
         
        $html = $this->load->view("cdrform/print",$data,true);  
        
        $css = $this->css();   
     
        $export_data = $css." ".$html;                 
                
        require_once('thirdpartylib/htmlpdf/html2pdf.class.php');

        $html2pdf = new HTML2PDF('P','LEGAL','fr');
        
        $html2pdf->WriteHTML($export_data);
        
        $html2pdf->Output('report.pdf');  
    }
    
    public function search()      
    {                                         
     
     $data['type_ad'] = $this->Cdrforms->getAdtype();
     $data['cdrtype'] = $this->Cdrforms->getCdrtype();  
     $html = $this->load->view('cdrform/searchdata', $data, true); 
                                                        
     echo json_encode($html);    
                                   
    }
       
    public function searchdata() 
    {    
        $data['cdr_no'] = $this->input->post('cdr_no');               
        $data['datefrom'] = $this->input->post('datefrom');               
        $data['dateto'] = $this->input->post('dateto');         
        $data['client_name'] = $this->input->post('client_name');         
        $data['agency_name'] = $this->input->post('agency_name');
        $data['type_ad'] = $this->input->post('type_ad');
        $data['cdrtype'] = $this->input->post('cdrtype');
        
        //$data['type_ad'] = $this->Cdrforms->getAdtype();  
                                                                                                         
        $data['result'] = $this->Cdrforms->searchData($data);      

        $html = $this->load->view('cdrform/list',$data,true);
        
        echo json_encode($html);
    }
    
      
      
      
    public function css()
    {
             $css = "<style> 
                           
                 
                    #report_header_name {
                      position:relative;
                      top:-18px; 
                    }
                         
                    table td {
                    
                    font-size:9px;
                    padding-top:3px;
                    padding-bottom:3px;
                    border:none;
                    
                    
                    }
                    
                    table th {
                    margin-left:0px;
                    margin-right:0px;
                    text-align:center;  
                    padding-top:5px;
                    padding-bottom:5px;
                    border-top:3px solid #000;
                    border-bottom:3px solid #000;
                    font-size:12px;
                    } 
                    
                                          
                   </style>

                   
                   "; 
                   return $css;
        }


    public function generateexport() {
    
        $issue_date = $this->input->get("issue_date");  

        $data['ao_num'] = $this->input->get('ao_num');
        $data['issue_date'] = $this->input->get('issue_date');
        
        $data['result'] = $this->Cdrforms->generate($data); 
        
        #print_r2($data['result']); exit;
        
        $data['issue_date'] = $issue_date; 
       
        $html = $this->load->view('cdrform/cdrform_excel', $data, true); 
        $filename ="CDRFORM-REPORT".".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
              
    }        
    
}