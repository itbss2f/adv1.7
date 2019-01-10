<?php 

class Dbmemo extends CI_Controller {

	public function __construct()
	{

		parent::__construct();
		$this->sess = $this->authlib->validate(); 
		$this->load->model(array('model_dcsubtype/dcsubtypes', 'model_adtype/adtypes', 'model_branch/branches', 'model_dbmemo/dbmemos', 'model_customer/customers'));

	}

	public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();   	
        $data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);   
        
        $data['canADD'] = $this->GlobalModel->moduleFunction("dbmemo", 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction("dbmemo", 'EDIT');                  
        $data['canDCAPPINV'] = $this->GlobalModel->moduleFunction("dbmemo", 'DCAPPINV');                  
        $data['canDCAPPDM'] = $this->GlobalModel->moduleFunction("dbmemo", 'DCAPPDM');                  
        $data['monthend'] = $this->GlobalModel->getMonthEnd();                                                  
        $data['dcsubtype'] = $this->dcsubtypes->listOfDCSub();
        $data['adtype'] = $this->adtypes->listOfAdtype();   
        $data['branch'] = $this->branches->listOfBranch();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('dbmemos/indexauto', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
	}
    
    public function importinvoice() {
        $cmf_code = mysql_escape_string($this->input->post('cmf_code'));  
        $amf_id = mysql_escape_string($this->input->post('amf_id'));
        $id = $this->input->post('ids');     
        $data['available_invoice'] = $this->dbmemos->do_importInvoice($cmf_code, $amf_id, $id);
        $response['importinvoice'] = $this->load->view('dbmemos/importinvoice', $data, true);
        
        echo json_encode($response);    
    }
    
    public function loadimportinvoice() {        
        
        $ids = $this->input->post('ids');
        $data['loadresult'] = $this->dbmemos->loadImportList($ids);  
        
        $response['assignview'] = $this->load->view('dbmemos/-loadimportinvoice', $data, true);
        echo json_encode($response);     
    }
    
    public function importdm() {
        $payee = mysql_escape_string($this->input->post('payee'));
        $id = $this->input->post('ids');     
        $data['dmlist'] = $this->dbmemos->getDMList($payee, $id);        
        $response['dmview'] = $this->load->view('dbmemos/-dmview', $data, true);
        echo json_encode($response);     
    }
    
    public function loadimportdm() {
        $ids = $this->input->post('ids');
        
        $data['loadresult'] = $this->dbmemos->loadImportDM($ids);
        
        $response['assignview'] = $this->load->view('dbmemos/-loadimportinvoice', $data, true);
        echo json_encode($response);   
    }
    
    public function ajaxComputationAll() {

        $amt = $this->input->post('amt');
        $nvat = $this->input->post('nvat');
        $vat = $this->input->post('vat');

        $totalamt = 0;
        $totalgross = 0;
        $totalvat = 0;
        
        for ($x = 0; $x < count($amt); $x++) {
            $totalamt += mysql_escape_string(str_replace(",", "", $amt[$x]));       
            $totalgross += mysql_escape_string(str_replace(",", "", $nvat[$x]));         
            $totalvat += mysql_escape_string(str_replace(",", "", $vat[$x]));       
        }
        /*foreach ($amt as $row) {
            
            $rrow = mysql_escape_string(str_replace(",", "", $row));     
            $totalamt += $rrow;
                    
            $g = floatval($rrow) / floatval(1 + ($vat/100));   
            $v = floatval($g) * floatval($vat/100);    
        
            $totalgross += $g;                 
            $totalvat += $v;                 
        }*/

        $response['assigneamount'] = number_format($totalamt, 2, '.',',');                               
        $response['totalamt'] = number_format($totalamt, 2, '.',',');                 
        $response['totalgrossamt'] = number_format($totalgross, 2, '.',',');                 
        $response['totalvatamt'] = number_format($totalvat, 2, '.',',');   
        //print_r2($response); exit;              
        echo json_encode($response);
    }
    
    public function ajaxComputation() {
        $vat = mysql_escape_string($this->input->post('vat'));
        $val = mysql_escape_string(str_replace(",", "", $this->input->post('val')));  
        $amt = $this->input->post('amt');
        $vatableamt = $this->input->post('vatableamt');
        $vatamt = $this->input->post('vatamt');
        
        $gross =  floatval($val) / floatval(1 + ($vat/100));      
        $vatamt = floatval($gross) * floatval($vat/100);                 
        
        $totalamt = 0;
        $totalgross = 0;
        $totalvat = 0;
        foreach ($amt as $row) {
            
            $rrow = mysql_escape_string(str_replace(",", "", $row));     
            $totalamt += $rrow;
                    
            $g = floatval($rrow) / floatval(1 + ($vat/100));   
            $v = floatval($g) * floatval($vat/100);    
        
            $totalgross += $g;                 
            $totalvat += $v;                 
        }

        $response['assigneamount'] = number_format($totalamt, 2, '.',',');                 
        $response['gross'] = number_format($gross, 2, '.',',');                 
        $response['vat'] = number_format($vatamt, 2, '.',',');                 
        $response['totalamt'] = number_format($totalamt, 2, '.',',');                 
        $response['totalgrossamt'] = number_format($totalgross, 2, '.',',');                 
        $response['totalvatamt'] = number_format($totalvat, 2, '.',',');   
        //print_r2($response); exit;              
        echo json_encode($response);
    }
    
    public function ajaxComputationthis() {
        $vat = mysql_escape_string($this->input->post('vat'));
        $val = mysql_escape_string(str_replace(",", "", $this->input->post('val')));  
        $amt = $this->input->post('amt');
        $vatableamt = $this->input->post('vatableamt');
        $vatamt = $this->input->post('vatamt');
        
        $gross =  floatval($val) / floatval(1 + ($vat/100));      
        $vatamt = floatval($gross) * floatval($vat/100);                 
        
        $totalamt = 0;
        $totalgross = 0;
        $totalvat = 0;
        $rrow = mysql_escape_string(str_replace(",", "", $amt));     
        $totalamt += $rrow;
                
        $g = floatval($rrow) / floatval(1 + ($vat/100));   
        $v = floatval($g) * floatval($vat/100);    

        $totalgross += $g;                 
        $totalvat += $v;  

        //$response['assigneamount'] = number_format($totalamt, 2, '.',',');                 
        $response['gross'] = number_format($gross, 2, '.',',');                 
        $response['vat'] = number_format($vatamt, 2, '.',',');                 
        //$response['totalamt'] = number_format($totalamt, 2, '.',',');                 
        //$response['totalgrossamt'] = number_format($totalgross, 2, '.',',');                 
        //$response['totalvatamt'] = number_format($totalvat, 2, '.',',');   
        //print_r2($response); exit;              
        echo json_encode($response);
    }
    
    public function autocustomer() {
        $this->load->model(array('model_customer/customers', 'model_agencyclient/agencyclients'));
        
        $data['customer_code'] = mysql_escape_string(trim($this->input->post('cust_code')));
        $data['customer_name'] = mysql_escape_string(trim($this->input->post('cust_name')));
        
        $response = $this->customers->findCustomerSuggestion($data);                  
        
        echo json_encode($response);
    }
    
    public function ajaxAgency(){
        $this->load->model('model_agencyclient/agencyclients');      
        
        $cust_id = mysql_escape_string($this->input->post('cust_id'));
        $response['agency'] = $this->agencyclients->customerAgency($cust_id);
        
        echo json_encode($response);
    }
      
    public function lookup() {         
        $data['dcsubtype'] = $this->dcsubtypes->listOfDCSub();
        $data['adtype'] = $this->adtypes->listOfAdtype(); 
        $data['branch'] = $this->branches->listOfBranch();          
        $response['lookup_view'] = $this->load->view('dbmemos/-lookup', $data, true);
        echo json_encode($response);
    }    
    
    public function find_lookup() {
        $data['dctype'] = $this->input->post('dctype');
        $data['dcnumber'] = $this->input->post('dcnumber');
        $data['dcdate'] = $this->input->post('dcdate');
        $data['dcsubtype'] = $this->input->post('dcsubtype');
        $data['adtype'] = $this->input->post('adtype');
        $data['clientcode'] = $this->input->post('clientcode');
        $data['clientname'] = $this->input->post('clientname');
        $data['dcamount'] = $this->input->post('dcamount');
        $data['branch'] = $this->input->post('branch');
        
        $data['data'] = $this->dbmemos->searchDBMemo($data);
        
        $response['lookup_list'] = $this->load->view('dbmemos/lookup_list', $data, true);                 
        
        echo json_encode($response);
    }
    
    public function setAcountingEntry() {
        $dctype = $this->input->post('dctype');
        $data['dcsubtype'] = $this->input->post('dcsubtype');
        $data['dcadtype'] = $this->input->post('dcadtype');
        $data['id'] = $this->input->post('ids');
        $data['ass_amt'] = $this->input->post('ass_amts');
        $data['gross_amt'] = $this->input->post('gross_amts');
        $data['vat_amt'] = $this->input->post('vat_amts');    
        $data['id2'] = $this->input->post('ids2');
        $data['ass_amt2'] = $this->input->post('ass_amts2');
        $data['gross_amt2'] = $this->input->post('gross_amts2');
        $data['vat_amt2'] = $this->input->post('vat_amts2');         
        $data['hkey'] = $this->input->post('hkey');            
        $data['newassamt'] = array();
        $data['newassamt2'] = array();
        $data['newgrossamt'] = array();
        $data['newgrossamt2'] = array();
        $data['newvatamt'] = array();
        $data['newvatamt2'] = array();
        
        if (!empty($data['id'])) { 
        $data['newassamt'] = array_combine($data['id'], $data['ass_amt']);        
        $data['newgrossamt'] = array_combine($data['id'], $data['gross_amt']);        
        $data['newvatamt'] = array_combine($data['id'], $data['vat_amt']);        
        }
        
        if (!empty($data['id2'])) {
        $data['newassamt2'] = array_combine($data['id2'], $data['ass_amt2']);        
        $data['newgrossamt2'] = array_combine($data['id2'], $data['gross_amt2']);        
        $data['newvatamt2'] = array_combine($data['id2'], $data['vat_amt2']);        
        }
                
        $dcamt = mysql_escape_string(str_replace(",","",$this->input->post('dcamount')));     
        $assamt = mysql_escape_string(str_replace(",","",$this->input->post('dcassamt')));    
        
        # TODO automatic accounting entry done
        $this->dbmemos->deleteExistingTempAcctEntry($data['hkey']);
        $this->dbmemos->saveTempAccountingEntry($data);      
        # TODO accounting entry for debit memo
        $this->dbmemos->saveTempAccountingEntryDebitMemo($data); 
              
        if ($assamt < $dcamt) {            
            $this->dbmemos->saveTempAccountingEntryWithAdtype($data, $dcamt, $assamt);  
        }  
        
        $val['data'] = $this->dbmemos->getTempAccountingEntry($data['hkey'], $data['dcsubtype']);
        
        $totaldebit = 0;
        $totalcredit = 0;
        
        foreach ($val['data'] as $row) {
            if ($row['dcstatus'] == 'D') {
                $totaldebit += $row['amount'];
            } else if ($row['dcstatus'] == 'C') {
                $totalcredit += $row['amount'];
            }
        }
        
        $response['totaldebit'] = number_format($totaldebit, 2, '.', ',');
        $response['totalcredit'] = number_format($totalcredit, 2, '.', ',');        
        $response['acctentry_list'] = $this->load->view('dbmemos/acctentry_list', $val, true);
        
        echo json_encode($response);
    }
    
    public function setManualAcountingEntry() {
        $this->load->model('model_bank/banks');
        $data['acct'] = $this->dbmemos->getAcctList();        
        $data['dept'] = $this->dbmemos->getDeptList();        
        $data['brnch'] = $this->dbmemos->getBranchList();        
        $data['bank'] = $this->banks->listOfBankBranch();    
#        print_r2($data['bank']); exit;    
        $response['manualacctentry_list'] = $this->load->view('dbmemos/-manualacctentry_list', $data, true);
        
        echo json_encode($response);    
    }
    
    public function ajxAccountValidation() {
        $acct = $this->input->post('acct');
        
        $x = $this->dbmemos->ajxAccountValidation($acct);        
        $show = "";  
        $response["D"] = "N";
        $response["B"] = "N";
        $response["E"] = "N";
        if (substr($x['caf_code'], 0, 1) == '5') {
            $response["D"] = "Y";   
            $substring = substr($x['caf_code'], 0, 2);               
        } else if (substr($x['caf_code'], 0, 1) == '4') {            
            $response["B"] = "Y";   
        } else if (substr($x['caf_code'], 0, 4) == '1141') {
            $response["E"] = "Y";   
        }       
        echo json_encode($response);
    }
    
    public function ajxDeptStatBranch() {
        $dept = $this->input->post('department');
        $db = $this->dbmemos->getDeptStatForBranch($dept);
        $response["B"] = "N";
        if ($db['dept_branchstatus'] == 'Y') {
            $response["B"] = "Y";            
        } 
        
        echo json_encode($response);      
    }
   
    public function saveManualAcountingEntry() {
        $data['acct'] = $this->input->post('acct');
        $data['branch'] = $this->input->post('branch');
        $data['customer'] = $this->input->post('customer');        
        $data['department'] = $this->input->post('department');
        $data['bank'] = $this->input->post('bank');
        $data['emp'] = $this->input->post('emp');
        $data['empname'] = $this->input->post('empname');
        $data['mancredit'] =  mysql_escape_string(str_replace(",","",$this->input->post('mancredit')));
        $data['mandebit'] =  mysql_escape_string(str_replace(",","",$this->input->post('mandebit')));
        $data['hkey'] = $this->input->post('hkey');                
        $data['dcsubtype'] = $this->input->post('dcsubtype');                        
        $this->dbmemos->saveManualAcountingEntry($data);
        
        $val['data'] = $this->dbmemos->getExistingTempAccountingEntry($data['hkey'], $data['dcsubtype']);
        $totaldebit = 0;
        $totalcredit = 0;        
        
        foreach ($val['data'] as $row) {
            if ($row['dcstatus'] == 'D') {
                $totaldebit += $row['amount'];
            } else if ($row['dcstatus'] == 'C') {
                $totalcredit += $row['amount'];
            }
        }
        
        $response['totaldebit'] = number_format($totaldebit, 2, '.', ',');
        $response['totalcredit'] = number_format($totalcredit, 2, '.', ',');        
        $response['acctentry_list'] = $this->load->view('dbmemos/acctentry_list', $val, true);
        
        echo json_encode($response);
    } 
    
    public function removeAccountingEntry() {
        $id = $this->input->post('id');             
        $data['dcsubtype'] = $this->input->post('dcsubtype');             
        $data['hkey'] = $this->input->post('hkey');     
        
        $this->dbmemos->removeTempAccountingEntry($id);
        
        $val['data'] = $this->dbmemos->getExistingTempAccountingEntry($data['hkey'], $data['dcsubtype']);
        $totaldebit = 0;
        $totalcredit = 0;        
        
        foreach ($val['data'] as $row) {
            if ($row['dcstatus'] == 'D') {
                $totaldebit += $row['amount'];
            } else if ($row['dcstatus'] == 'C') {
                $totalcredit += $row['amount'];
            }
        }
        
        $response['totaldebit'] = number_format($totaldebit, 2, '.', ',');
        $response['totalcredit'] = number_format($totalcredit, 2, '.', ',');        
        $response['acctentry_list'] = $this->load->view('dbmemos/acctentry_list', $val, true);
        
        echo json_encode($response);    
    }
    
    public function editAccountingEntryView() {
        $this->load->model('model_bank/banks');         
        $id = $this->input->post('id');
        
        $data['acct'] = $this->dbmemos->getAcctList();        
        $data['dept'] = $this->dbmemos->getDeptList();        
        $data['brnch'] = $this->dbmemos->getBranchList();    
        $data['bank'] = $this->banks->listOfBankBranch();           
        $data['data'] = $this->dbmemos->thisTempAccountingEntry($id);
        
        $x = $this->dbmemos->ajxAccountValidation($data['data']['cafid']);  
        
        $data["D"] = "N";
        $data["B"] = "N";
        $data["BN"] = "N";
        $data["E"] = "N";
        if (substr($x['caf_code'], 0, 1) == '5') {
            $data["D"] = "Y";   
            $substring = substr($x['caf_code'], 0, 2);               
        } else if (substr($x['caf_code'], 0, 1) == '4') {            
            $data["B"] = "Y";   
        } else if (substr($x['caf_code'], 0, 4) == '1141') {
            $data["E"] = "Y";   
        } else if ($x['caf_code'] == '111200') {
            $data["BN"] = "Y";
        }
            
        $response['editacctentry_list'] = $this->load->view('dbmemos/-editacctentry_list', $data, true);
        
        echo json_encode($response);    
    }
    
    public function saveEditAcountingEntry() {
        $id = $this->input->post('id');
        $data['acct'] = $this->input->post('acct');
        $data['branch'] = $this->input->post('branch');
        $data['customer'] = $this->input->post('customer');        
        $data['department'] = $this->input->post('department');
        $data['emp'] = $this->input->post('emp');
        $data['bank'] = $this->input->post('bank');
        $data['mancredit'] =  mysql_escape_string(str_replace(",","",$this->input->post('mancredit')));
        $data['mandebit'] =  mysql_escape_string(str_replace(",","",$this->input->post('mandebit')));
        $data['hkey'] = $this->input->post('hkey');                
        $data['dcsubtype'] = $this->input->post('dcsubtype');         
        $this->dbmemos->saveEditAccountingEntry($data, $id);       
        $val['data'] = $this->dbmemos->getExistingTempAccountingEntry($data['hkey'], $data['dcsubtype']); 
        $totaldebit = 0;
        $totalcredit = 0;        
        
        foreach ($val['data'] as $row) {
            if ($row['dcstatus'] == 'D') {
                $totaldebit += $row['amount'];
            } else if ($row['dcstatus'] == 'C') {
                $totalcredit += $row['amount'];
            }
        }
        
        $response['totaldebit'] = number_format($totaldebit, 2, '.', ',');
        $response['totalcredit'] = number_format($totalcredit, 2, '.', ',');        
        $response['acctentry_list'] = $this->load->view('dbmemos/acctentry_list', $val, true);
        
        echo json_encode($response);                
    }
    
    public function saveDBMemo() {
        $datam['dc_type'] = $this->input->post('dctype');
        $datam['dc_num'] = $this->input->post('dcnumber');
        $datam['dc_date'] = $this->input->post('dcdate');
        $datam['dc_argroup'] = 'A';
        $datam['dc_artype'] = '1';
        $datam['dc_subtype'] = $this->input->post('dcsubtype');
        $datam['dc_adtype'] = $this->input->post('dcadtype');
        $datam['dc_payee'] = $this->input->post('clientcode');
        $datam['dc_payeename'] = $this->input->post('clientname');        
        $datam['dc_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('dcamount')));       
        $datam['dc_assignamt'] = mysql_escape_string(str_replace(",","",$this->input->post('assigneamount')));       
        $datam['dc_amtword'] = $this->input->post('dcamountinwords');
        $datam['dc_branch'] = $this->input->post('branch_m');
        $datam['dc_part'] = $this->input->post('particulars');     
        $datam['dc_comment'] = $this->input->post('comments');    
        $datam['dc_payeetype'] = 1;   
        $datam['dc_amf'] = $this->input->post('agency');   
        $datam['dc_refnum'] = $this->input->post('refnum');   
        
        $datam['dc_habol'] = $this->input->post('habol');  
        
        if ($datam['dc_habol'] == 1) { 
            $datam['dc_haboldate'] = $this->input->post('haboldate');   
        }
        
        // Validate Month End Closing 
        $monthend = $this->GlobalModel->checkMonthEndClosing($datam['dc_date']); 
        
        if ($monthend == 1) {
            echo "Sorry transaction date already close!. ";
            echo anchor(site_url('dbmemo'), 'Click to return');
            exit;
        } 
        
        //$datam['dc_agency'] = $this->input->post('agency');     
        $this->dbmemos->saveDBMemo_M($datam); 
                
        $datad['dc_type'] = $datam['dc_type'];        
        $datad['dc_num'] = $datam['dc_num'];                
        $datad['dc_date'] = $datam['dc_date'];                
        $datad['dc_argroup'] = $datam['dc_argroup'];                
        $datad['dc_artype'] = $datam['dc_artype'];                
        $datad['dc_id'] = $this->input->post('hiddenassignid');     
        $datad['dc_prod'] = $this->input->post('hiddenassignprod');     
        $datad['dc_issuefrom'] = $this->input->post('hiddenassigndate');     
        $datad['dc_issueto'] = $this->input->post('hiddenassigndate');     
        $datad['dc_doctype'] = $this->input->post('hiddenassigndoctype');     
        $datad['dc_adtype'] = $this->input->post('hiddenassignadtype');     
        $datad['dc_docbal'] = $this->input->post('hiddenassignbal');                 
        $datad['dc_docnum'] = $this->input->post('hiddenassignornum');                 
        $datad['dc_assignamt'] = $this->input->post('assignamt');             
        $datad['dc_assigngrossamt'] = $this->input->post('assigngross');             
        $datad['dc_assignvatamt'] = $this->input->post('assigngvatamt');              
        $datad['dc_cmfvatcode'] = $this->input->post('hiddenassignvatcode');     
        $datad['dc_cmfvatrate'] = $this->input->post('hiddenassignvatrate');     
        $datad['dc_width'] = $this->input->post('hiddenassignwidth');     
        $datad['dc_length'] = $this->input->post('hiddenassignlength');             
        
        if ($datam['dc_type'] == 'C') {      
        $this->dbmemos->saveDBMemo_D($datad);     
        }
        $acctentry = count($this->input->post('hiddenacctid'));  
        if ($acctentry > 1) {
            $dataa['dc_type'] = $datam['dc_type'];
            $dataa['dc_num'] = $datam['dc_num'];
            $dataa['dc_date'] = $datam['dc_date'];
            $dataa['dc_acct'] = $this->input->post('hiddenacctid');         
            $dataa['dc_branch'] = $this->input->post('hiddenacctbranch');         
            $dataa['dc_dept'] = $this->input->post('hiddenacctdept');         
            $dataa['dc_emp'] = $this->input->post('hiddenacctemp');     
            $dataa['dc_empname'] = $this->input->post('hiddenacctempname');             
            $dataa['dc_bank'] = $this->input->post('hiddenacctbank');         
            $dataa['dc_cmf'] = $this->input->post('hiddenacctcmf');         
            $dataa['dc_code'] = $this->input->post('hiddenacctdcstatus');         
            $dataa['dc_amt'] = $this->input->post('hiddenacctamt');
            $this->dbmemos->saveDBMemo_A($dataa);
        }
        
           
        
        $msg = "You successfully save CM/DM";

        $this->session->set_flashdata('msg', $msg);        
        
        redirect('dbmemo/view/'.$datam['dc_type'].'/'.$datam['dc_num']);
                    
    }
    
    
    public function saveDBMemoAuto() {
        $datam['dc_type'] = $this->input->post('dctype');
        $datam['dc_num'] = $this->dbmemos->getLastDMCMNumber($datam['dc_type']); 
        $datam['dc_date'] = $this->input->post('dcdate');
        $datam['dc_argroup'] = 'A';
        $datam['dc_artype'] = '1';
        $datam['dc_subtype'] = $this->input->post('dcsubtype');
        $datam['dc_adtype'] = $this->input->post('dcadtype');
        $datam['dc_payee'] = $this->input->post('clientcode');
        $datam['dc_payeename'] = $this->input->post('clientname');        
        $datam['dc_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('dcamount')));       
        $datam['dc_assignamt'] = mysql_escape_string(str_replace(",","",$this->input->post('assigneamount')));       
        $datam['dc_amtword'] = $this->input->post('dcamountinwords');
        $datam['dc_branch'] = $this->input->post('branch_m');
        $datam['dc_part'] = $this->input->post('particulars');     
        $datam['dc_comment'] = $this->input->post('comments');    
        $datam['dc_payeetype'] = 1;   
        $datam['dc_amf'] = $this->input->post('agency');           
        $datam['dc_habol'] = $this->input->post('habol');  
        $datam['dc_refnum'] = $this->input->post('refnum');  
        
        if ($datam['dc_habol'] == 1) { 
            $datam['dc_haboldate'] = $this->input->post('haboldate');   
        }
        
        #print_r2($datam); exit;     
        // Validate Month End Closing 
        $monthend = $this->GlobalModel->checkMonthEndClosing($datam['dc_date']); 
        
        if ($monthend == 1) {
            echo "Sorry transaction date already close!. ";
            echo anchor(site_url('dbmemo'), 'Click to return');
            exit;
        } 
        
        //$datam['dc_agency'] = $this->input->post('agency');     
        $this->dbmemos->saveDBMemo_M($datam); 
                
        $datad['dc_type'] = $datam['dc_type'];        
        $datad['dc_num'] = $datam['dc_num'];                
        $datad['dc_date'] = $datam['dc_date'];                
        $datad['dc_argroup'] = $datam['dc_argroup'];                
        $datad['dc_artype'] = $datam['dc_artype'];                
        $datad['dc_id'] = $this->input->post('hiddenassignid');     
        $datad['dc_prod'] = $this->input->post('hiddenassignprod');     
        $datad['dc_issuefrom'] = $this->input->post('hiddenassigndate');     
        $datad['dc_issueto'] = $this->input->post('hiddenassigndate');     
        $datad['dc_doctype'] = $this->input->post('hiddenassigndoctype');     
        $datad['dc_adtype'] = $this->input->post('hiddenassignadtype');     
        $datad['dc_docbal'] = $this->input->post('hiddenassignbal');                 
        $datad['dc_docnum'] = $this->input->post('hiddenassignornum');                 
        $datad['dc_assignamt'] = $this->input->post('assignamt');             
        $datad['dc_assigngrossamt'] = $this->input->post('assigngross');             
        $datad['dc_assignvatamt'] = $this->input->post('assigngvatamt');              
        $datad['dc_cmfvatcode'] = $this->input->post('hiddenassignvatcode');     
        $datad['dc_cmfvatrate'] = $this->input->post('hiddenassignvatrate');     
        $datad['dc_width'] = $this->input->post('hiddenassignwidth');     
        $datad['dc_length'] = $this->input->post('hiddenassignlength');             
        
        if ($datam['dc_type'] == 'C') {      
        $this->dbmemos->saveDBMemo_D($datad);     
        }
        $acctentry = count($this->input->post('hiddenacctid'));  
        if ($acctentry > 1) {
            $dataa['dc_type'] = $datam['dc_type'];
            $dataa['dc_num'] = $datam['dc_num'];
            $dataa['dc_date'] = $datam['dc_date'];
            $dataa['dc_acct'] = $this->input->post('hiddenacctid');         
            $dataa['dc_branch'] = $this->input->post('hiddenacctbranch');         
            $dataa['dc_dept'] = $this->input->post('hiddenacctdept');         
            $dataa['dc_emp'] = $this->input->post('hiddenacctemp');         
            $dataa['dc_empname'] = $this->input->post('hiddenacctempname');         
            $dataa['dc_cmf'] = $this->input->post('hiddenacctcmf');         
            $dataa['dc_code'] = $this->input->post('hiddenacctdcstatus');         
            $dataa['dc_amt'] = $this->input->post('hiddenacctamt');
            $this->dbmemos->saveDBMemo_A($dataa);
        }
        
           
        
        $msg = "You successfully save CM/DM";

        $this->session->set_flashdata('msg', $msg);        
        
        redirect('dbmemo/view/'.$datam['dc_type'].'/'.$datam['dc_num']);
                    
    }
    
    public function view($dctype, $dcnum) {
        
        $navigation['data'] = $this->GlobalModel->moduleList();              
   
        $data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);                                          
        $data['dcsubtype'] = $this->dcsubtypes->listOfDCSub();
        $data['adtype'] = $this->adtypes->listOfAdtype();   
        $data['branch'] = $this->branches->listOfBranch();
        
        $data['canADD'] = $this->GlobalModel->moduleFunction("dbmemo", 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction("dbmemo", 'EDIT');                  
        $data['canEDITDMTYPE'] = $this->GlobalModel->moduleFunction("dbmemo", 'EDITDMTYPE');                  
        $data['canDCAPPINV'] = $this->GlobalModel->moduleFunction("dbmemo", 'DCAPPINV');                  
        $data['canDCAPPDM'] = $this->GlobalModel->moduleFunction("dbmemo", 'DCAPPDM');        
        $data['monthend'] = $this->GlobalModel->getMonthEnd();                                              
        
        # Restore remove invoice status
        $this->dbmemos->restoreInvoice($dctype, $dcnum);
        
        $data['main'] = $this->dbmemos->getDBMemo_M($dctype, $dcnum);
        $this->load->model('model_agencyclient/agencyclients');      
        $agy = $this->customers->getCustomerData($data['main']['dc_payee']);  
        $data['dataagency'] = $this->agencyclients->customerAgency($agy['id']);          
        
        if (empty($data['main'])) { redirect('dbmemo'); }
        $data['totalassignamt'] = 0;  
        $data['totalgrossamt'] = 0;  
        $data['totalvatamt'] = 0;  
        $detail['status'] = $data['main']['status'];   
        if ($dctype == "C") {
            $detail['loadresult'] = $this->dbmemos->getDBMemo_D($dctype, $dcnum);                
            foreach ($detail['loadresult'] as $row) {
                $data['totalassignamt'] += floatval($row['dc_assignamt']);
                $data['totalgrossamt'] += floatval($row['dc_assigngrossamt']);
                $data['totalvatamt'] += floatval($row['dc_assignvatamt']);
            }  
            
            $data['assignview'] = $this->load->view('dbmemos/-loadimportinvoice', $detail, true);
        } else {
            $detail['loadresult'] = $this->dbmemos->getDMDBMemo_D($dctype, $dcnum);    
            
            foreach ($detail['loadresult'] as $row) {
                $data['totalassignamt'] += $row['assignamt'];
                $data['totalgrossamt'] += $row['assigngrossamt'];
                $data['totalvatamt'] += $row['assignvatamt'];
            }  
            $data['assignview'] = $this->load->view('dbmemos/-loadimportinvoicedm', $detail, true);        
        }
        $data['countapplied'] = count($detail['loadresult']);
        $data['accounting'] = $this->dbmemos->getDBMemo_A($dctype, $dcnum, $data['hkey']);      
        
        $val['data'] = $this->dbmemos->getExistingTempAccountingEntry($data['hkey'], $data['dcsubtype']);
        $totaldebit = 0;
        $totalcredit = 0;        
        
        foreach ($val['data'] as $row) {
            if ($row['dcstatus'] == 'D') {
                $totaldebit += $row['amount'];
            } else if ($row['dcstatus'] == 'C') {
                $totalcredit += $row['amount'];
            }
        }
        $val['status'] = $data['main']['status']; 
        $data['totaldebit'] = number_format($totaldebit, 2, '.', ',');
        $data['totalcredit'] = number_format($totalcredit, 2, '.', ',');        
        $data['acctentry_list'] = $this->load->view('dbmemos/acctentry_list', $val, true);
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('dbmemos/view', $data, true);
        $this->load->view('welcome_index', $welcome_layout);    
    }
    
    public function updateDBMemo($dctype, $dcnum) {

        $poststat = $this->input->post('poststat');
        $datam['dc_date'] = $this->input->post('dcdate');
        $datam['dc_argroup'] = 'A';
        $datam['dc_artype'] = '1';
        $datam['dc_subtype'] = $this->input->post('dcsubtype');
       
        //$datam['dc_payee'] = $this->input->post('clientcode');
       // $datam['dc_payeename'] = $this->input->post('clientname');        
        $datam['dc_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('dcamount')));       
        $datam['dc_assignamt'] = mysql_escape_string(str_replace(",","",$this->input->post('assigneamount')));       
        $datam['dc_amtword'] = $this->input->post('dcamountinwords');
        
        $datam['dc_part'] = $this->input->post('particulars');     
        $datam['dc_comment'] = $this->input->post('comments');    
        $datam['dc_payeetype'] = 1;           
        $datam['dc_refnum'] = $this->input->post('refnum');   
        
        
        
        if ($poststat != 'O') {
            $datam['dc_amf'] = $this->input->post('agency');        
            $datam['dc_adtype'] = $this->input->post('dcadtype');
            $datam['dc_branch'] = $this->input->post('branch_m');        
            $datam['dc_habol'] = $this->input->post('habol');  
            
            if ($datam['dc_habol'] == 1) { 
                $datam['dc_haboldate'] = $this->input->post('haboldate');   
            }
        }
        
        #print_r2($datam); exit;      
               
        $this->dbmemos->upateDBMemo_M($datam, $dctype, $dcnum);               
              
        # Delete Invoice
        
        $datad['dc_date'] = $datam['dc_date'];                
        $datad['dc_argroup'] = $datam['dc_argroup'];                
        $datad['dc_artype'] = $datam['dc_artype'];                
        $datad['dc_id'] = $this->input->post('hiddenassignid');     
        $datad['dc_prod'] = $this->input->post('hiddenassignprod');     
        $datad['dc_issuefrom'] = $this->input->post('hiddenassigndate');     
        $datad['dc_issueto'] = $this->input->post('hiddenassigndate');     
        $datad['dc_doctype'] = $this->input->post('hiddenassigndoctype');     
        $datad['dc_adtype'] = $this->input->post('hiddenassignadtype');     
        $datad['dc_docbal'] = $this->input->post('hiddenassignbal');                 
        $datad['dc_docnum'] = $this->input->post('hiddenassignornum');                 
        $datad['dc_assignamt'] = $this->input->post('assignamt');             
        $datad['dc_assigngrossamt'] = $this->input->post('assigngross');             
        $datad['dc_assignvatamt'] = $this->input->post('assigngvatamt');              
        $datad['dc_cmfvatcode'] = $this->input->post('hiddenassignvatcode');     
        $datad['dc_cmfvatrate'] = $this->input->post('hiddenassignvatrate');     
        $datad['dc_width'] = $this->input->post('hiddenassignwidth');     
        $datad['dc_length'] = $this->input->post('hiddenassignlength');             
        $datad['did'] = $this->input->post('hiddenassigndid');    
        
        $this->dbmemos->upateDBMemo_D($datad, $dctype, $dcnum);    
        $this->dbmemos->deleteInvoiceApplied($dctype, $dcnum);                  

        $dataa['dc_date'] = $datam['dc_date'];        
        $dataa['didd'] = $this->input->post('hiddenassigndidd');         
        $dataa['dc_acct'] = $this->input->post('hiddenacctid');         
        $dataa['dc_branch'] = $this->input->post('hiddenacctbranch');         
        $dataa['dc_dept'] = $this->input->post('hiddenacctdept');         
        $dataa['dc_emp'] = $this->input->post('hiddenacctemp');         
        $dataa['dc_bank'] = $this->input->post('hiddenacctbank');         
        $dataa['dc_cmf'] = $this->input->post('hiddenacctcmf');         
        $dataa['dc_code'] = $this->input->post('hiddenacctdcstatus');         
        $dataa['dc_amt'] = $this->input->post('hiddenacctamt');

        $this->dbmemos->upateDBMemo_A($dataa, $dctype, $dcnum);  
        
        $msg = "You successfully save updated CM/DM";

        $this->session->set_flashdata('msg', $msg);                     
        
        redirect('dbmemo/view/'.$dctype.'/'.$dcnum);  
    }
    
    public function view_lookupinv() {
        
        $response['view'] = $this->load->view('dbmemos/lookup_invoice', null, true);
        
        echo json_encode($response);
    }
    
    public function view_lookupao() {
        
        $response['view'] = $this->load->view('dbmemos/lookup_ao', null, true);
        
        echo json_encode($response);
    }
    
    public function view_lookuporcm() {
        
        $response['view'] = $this->load->view('dbmemos/lookup_orcm', null, true);
        
        echo json_encode($response);
    }
    
    public function searchinvoicelist() {
        $inv = mysql_escape_string($this->input->post('inv'));
        
        $data['invoice'] = $this->dbmemos->loadImportListByInv($inv);  

        $response['result'] = $this->load->view('dbmemos/-lookup_invoicelist', $data, true);
        
        echo json_encode($response);
    }
    
    public function searchaolist() {
        $ao = mysql_escape_string($this->input->post('ao'));
        
        $data['invoice'] = $this->dbmemos->loadImportListByAO($ao);  

        $response['result'] = $this->load->view('dbmemos/-lookup_aolist', $data, true);
        
        echo json_encode($response);
    }
    
    public function searchorcmlist() {
        $orcm = mysql_escape_string($this->input->post('orcm'));
        $type = mysql_escape_string($this->input->post('type'));
        
        $data['list'] = $this->dbmemos->loadImportListByORCM($orcm, $type);  

        $response['result'] = $this->load->view('dbmemos/-lookup_orcmlist', $data, true);
        
        echo json_encode($response);
    }

    public function loadimportinvoiceINV() {  
    
        $this->load->model('model_agencyclient/agencyclients');      
        
        $ids = $this->input->post('ids');
        $data['loadresult'] = $this->dbmemos->loadImportList($ids);  
        
        $cust_id = $this->customers->getCustomerIDBYAOPID(@$ids[0]);
    
        $response['customercode'] = @$cust_id['id']; #$this->agencyclients->customerAgency($cust_id['id']); 
        
        #$response['agency'] = array('id' => $data['loadresult'][0]['ao_amf'], 'agencycode' => $data['loadresult'][0]['agencycode'], 'agencyname' => $data['loadresult'][0]['agencyname']);
        

        $response['assignview'] = $this->load->view('dbmemos/-loadimportinvoice', $data, true);
        echo json_encode($response);     
    }
    
    public function loadimportinvoiceORCM() {  
      
        $ids = $this->input->post('ids');
        
        $var = explode('x', $ids);
        
        $num = $var[0];
        $type = $var[1];
      
        $data['loadresult'] = $this->dbmemos->importListByORCM($num, $type);  
        
      
        #$response['agency'] = array('id' => $data['loadresult'][0]['ao_amf'], 'agencycode' => $data['loadresult'][0]['agencycode'], 'agencyname' => $data['loadresult'][0]['agencyname']);
        
        $response['defaultdata'] = $data['loadresult'];
        #$response['assignview'] = $this->load->view('dbmemos/-loadimportinvoice', $data, true);
        echo json_encode($response);     
    }
    
    public function view_singleinve() {
        
        $response['view'] = $this->load->view('dbmemos/lookup_singleinvoice', null, true);
        
        echo json_encode($response);
    }
    
    
    public function invoicenofind() {
        $invoiceno = $this->input->post('invoiceno');
        $id = $this->input->post('ids');     
       
        $data['invoice_list'] = $this->dbmemos->invoicenofind($invoiceno, $id);    
        
        $response['empty']  = 1;
        
        if (empty($data['invoice_list'])) {
            $response['empty']  = 0;       
        }
        
        $response['invoice_list'] = $this->load->view('dbmemos/-invoice_list', $data, true);

        echo json_encode($response);    
    }
    
    public function removeinvoice() {
        $id = $this->input->post('id');
        $this->dbmemos->removeInvoice($id);    
    }
    
    public function changetype() {
        $dcnumber = $this->input->post('dcnumber');
        $typ = $this->input->post('typ');
        $tt = $this->input->post('tt');
        
        $this->dbmemos->changeDCType($dcnumber, $typ, $tt);
        
    }
    
    public function validateDCNumber() {
        $dcno = mysql_escape_string($this->input->post('num'));

        $chck = $this->dbmemos->validateDCNum($dcno);
        
        echo $chck;
    }
    
    public function saveNEWDCNUmber() {
        $old = mysql_escape_string($this->input->post('old'));   
        $new = mysql_escape_string($this->input->post('num')); 
        $typ = $this->input->post('typ');   
        
        $this->dbmemos->saveChangeNewNumber($old, $new, $typ);
    }
                                      
}
