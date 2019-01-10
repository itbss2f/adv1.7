<?php

class Customer extends CI_Controller {

	public function __construct()
	{

		parent::__construct();
		$this->sess = $this->authlib->validate();
		$this->load->model(array('model_customer/customers', 'model_country/countries', 'model_zip/zipcities',
                                 'model_categoryads/categoryads', 'model_paytype/paytypes', 'model_vat/vats',
                                 'model_branch/branches', 'model_creditcard/creditcards', 'model_empprofile/employeeprofiles',
                                 'model_collectorarea/collectorareas', 'model_industry/industries', 'model_wtax/wtaxes', 'model_creditterm/creditterms'));

	}

	public function index()
    {
		$navigation['data'] = $this->GlobalModel->moduleList();
		#$data['customer_list'] = $this->customers->list_of_customer();
        $data['canADD'] = $this->GlobalModel->moduleFunction("customer", 'ADD');
        $data['canEDIT'] = $this->GlobalModel->moduleFunction("customer", 'EDIT');
        $data['canDELETE'] = $this->GlobalModel->moduleFunction("customer", 'DELETE');
        $data['canVIEW'] = $this->GlobalModel->moduleFunction("customer", 'VIEW');
        $data['canCUSTOMERCCCONTACT'] = $this->GlobalModel->moduleFunction("customer", 'CUSTOMERCCCONTACT');
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);

        # TODO Pagination

        /* Pagination */
        $page = input_get('page', 1);
        $total = $this->customers->countAll();
        $total = $total->count_id ;
        $limit = 30;
        $data['customer_list'] = $this->customers->list_of_customer(offset($page, $limit = $limit), $limit);
        $data['pages'] = pages($total, $page, $limit);
        /* End Pagination */

		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('customers/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

    function pageselect()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction("customer", 'ADD');
        $data['canEDIT'] = $this->GlobalModel->moduleFunction("customer", 'EDIT');
        $data['canDELETE'] = $this->GlobalModel->moduleFunction("customer", 'DELETE');
        $data['canVIEW'] = $this->GlobalModel->moduleFunction("customer", 'VIEW');
        $data['canCUSTOMERCCCONTACT'] = $this->GlobalModel->moduleFunction("customer", 'CUSTOMERCCCONTACT');
        //$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);

        $page = input_post('page', 1);
        $total = $this->customers->countAll();
        $total = $total->count_id ;
        $limit = 30;
        $data['customer_list'] = $this->customers->list_of_customer(offset($page, $limit = $limit), $limit);
        $data['pages'] = pages($total, $page, $limit);

        $html = $this->load->view('customers/paginate-content', $data, true);


        echo json_encode($html);
    }


    public function newdata()
    {
        $data['country'] = $this->countries->listOfCountry();
        $data['zipcode'] = $this->zipcities->listOfZipCity();
        $data['catad'] = $this->categoryads->listOfCategoryad();
        $data['paytype'] = $this->paytypes->listOfPayType();
        $data['vat'] = $this->vats->listOfVatActive();
        $data['acctexec'] = $this->employeeprofiles->listEmpAcctExec();
        $data['collector'] = $this->employeeprofiles->listEmpCollector();
        $data['collectorasst'] = $this->employeeprofiles->listEmpCollAst();
        $data['collectorarea'] = $this->collectorareas->listOfCollectorArea();
        $data['industry'] = $this->industries->listOfIndustry();
        $data['wtax'] = $this->wtaxes->listOfWtax();
        $data['creditcard'] = $this->creditcards->listOfCreditCard();
        $data['creditterm'] = $this->creditterms->listOfCreditTerm();
        $data['branch'] = $this->branches->listOfBranch();
        $response['newdata_view'] = $this->load->view('customers/newdata', $data, true);
        echo json_encode($response);
    }

    public function validateCode()
    {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[miscmf.cmf_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }
    }

    public function save()
    {
        $data['cmf_code'] = $this->input->post('customercode');
        $data['cmf_name'] = strtoupper($this->input->post('customername'));
        $data['cmf_add1'] = $this->input->post('address1');
        $data['cmf_add2'] = $this->input->post('address2');
        $data['cmf_add3'] = $this->input->post('address3');
        $data['cmf_title'] = $this->input->post('title2');
        $data['cmf_branch'] = $this->input->post('branch');
        $data['cmf_country'] = $this->input->post('country');
        $data['cmf_zip'] = $this->input->post('zipcode');
        $data['cmf_telprefix1'] = $this->input->post('telephone1');
        $data['cmf_tel1'] = $this->input->post('telephoneno1');
        $data['cmf_telprefix2'] = $this->input->post('telephone2');
        $data['cmf_tel2'] = $this->input->post('telephoneno2');
        $data['cmf_celprefix'] = $this->input->post('cellphone');
        $data['cmf_cel'] = $this->input->post('cellphoneno');
        $data['cmf_faxprefix'] = $this->input->post('fax');
        $data['cmf_fax'] = $this->input->post('faxno');
        $data['cmf_catad'] = $this->input->post('agencydirect');
        $data['cmf_paytype'] = $this->input->post('paytype');
        $data['cmf_vatcode'] = $this->input->post('vatcode');
        #cmf_vatrate
        $data['cmf_aef'] = $this->input->post('acctexec');
        $data['cmf_coll'] = $this->input->post('collector');
        $data['cmf_collarea'] = $this->input->post('collectorarea');
        $data['cmf_collasst'] = $this->input->post('collectorasst');
        $data['cmf_pana'] = $this->input->post('pana');
        $data['cmf_gov'] = $this->input->post('govt');

        $data['cmf_tin'] = str_replace("-","",$this->input->post('tin'));

        $data['cmf_industry'] = $this->input->post('industry');
        $data['cmf_contact'] = $this->input->post('contactperson');
        $data['cmf_position'] = $this->input->post('position');
        $data['cmf_salutation'] = $this->input->post('title');
        $data['cmf_email'] = $this->input->post('email');
        $data['cmf_url'] = $this->input->post('url');
        $data['cmf_wtaxcode'] = $this->input->post('wtax');
        #cmf_wtaxrate
        $data['cmf_rem'] = $this->input->post('remarks');
        $data['cmf_cardholder'] = $this->input->post('cardholder');
        $data['cmf_cardtype'] = $this->input->post('cardtype');
        $data['cmf_cardnumber'] = $this->input->post('cardno');
        $data['cmf_authorisationno'] = $this->input->post('authorizationno');
        $data['cmf_expirydate'] = $this->input->post('expirydate');
        $data['cmf_crstatus'] = $this->input->post('creditstatus');
        $data['cmf_crlimit'] = str_replace(",","",$this->input->post('creditlimit'));
        $data['cmf_crf'] = $this->input->post('creditterms');
        $data['cmf_crrem'] = $this->input->post('creditrem');
        $data['cmf_adrem'] = $this->input->post('adrem');

        $this->customers->saveNewData($data);

        $msg = "You successfully save New Customer";

        $this->session->set_flashdata('msg', $msg);

        redirect('customer');
    }

    public function editdata()
    {

        $id = $this->input->post('id');
        $data['country'] = $this->countries->listOfCountry();
        $data['zipcode'] = $this->zipcities->listOfZipCity();
        $data['catad'] = $this->categoryads->listOfCategoryad();
        $data['paytype'] = $this->paytypes->listOfPayType();
        $data['vat'] = $this->vats->listOfVatActive();
        $data['acctexec'] = $this->employeeprofiles->listEmpAcctExec();
        $data['collector'] = $this->employeeprofiles->listEmpCollector();
        $data['collectorasst'] = $this->employeeprofiles->listEmpCollAst();
        $data['collectorarea'] = $this->collectorareas->listOfCollectorArea();
        $data['industry'] = $this->industries->listOfIndustry();
        $data['wtax'] = $this->wtaxes->listOfWtax();
        $data['creditcard'] = $this->creditcards->listOfCreditCard();
        $data['creditterm'] = $this->creditterms->listOfCreditTerm();
        $data['branch'] = $this->branches->listOfBranch();
        $data['data'] = $this->customers->thisCustomerCurrentData($id);
        $response['editdata_view'] = $this->load->view('customers/editdata', $data, true);
        echo json_encode($response);
    }

    public function saveupdate($id)
    {

        //$data['cmf_name'] = $this->input->post('customername');
        $data['cmf_name'] = strtoupper($this->input->post('customername'));
        $data['cmf_add1'] = $this->input->post('address1');
        $data['cmf_add2'] = $this->input->post('address2');
        $data['cmf_add3'] = $this->input->post('address3');
        $data['cmf_title'] = $this->input->post('title2');
        $data['cmf_branch'] = $this->input->post('branch');
        $data['cmf_country'] = $this->input->post('country');
        $data['cmf_zip'] = $this->input->post('zipcode');
        $data['cmf_telprefix1'] = $this->input->post('telephone1');
        $data['cmf_tel1'] = $this->input->post('telephoneno1');
        $data['cmf_telprefix2'] = $this->input->post('telephone2');
        $data['cmf_tel2'] = $this->input->post('telephoneno2');
        $data['cmf_celprefix'] = $this->input->post('cellphone');
        $data['cmf_cel'] = $this->input->post('cellphoneno');
        $data['cmf_faxprefix'] = $this->input->post('fax');
        $data['cmf_fax'] = $this->input->post('faxno');
        $data['cmf_catad'] = $this->input->post('agencydirect');
        $data['cmf_paytype'] = $this->input->post('paytype');
        $data['cmf_vatcode'] = $this->input->post('vatcode');
        #cmf_vatrate
        $data['cmf_aef'] = $this->input->post('acctexec');
        $data['cmf_coll'] = $this->input->post('collector');
        $data['cmf_collarea'] = $this->input->post('collectorarea');
        $data['cmf_collasst'] = $this->input->post('collectorasst');
        $data['cmf_pana'] = $this->input->post('pana');
        $data['cmf_gov'] = $this->input->post('govt');
        $data['cmf_tin'] = str_replace("-","",$this->input->post('tin'));
        $data['cmf_industry'] = $this->input->post('industry');
        $data['cmf_contact'] = $this->input->post('contactperson');
        $data['cmf_position'] = $this->input->post('position');
        $data['cmf_salutation'] = $this->input->post('title');
        $data['cmf_email'] = $this->input->post('email');
        $data['cmf_url'] = $this->input->post('url');
        $data['cmf_wtaxcode'] = $this->input->post('wtax');
        #cmf_wtaxrate
        $data['cmf_rem'] = $this->input->post('remarks');
        $data['cmf_cardholder'] = $this->input->post('cardholder');
        $data['cmf_cardtype'] = $this->input->post('cardtype');
        $data['cmf_cardnumber'] = $this->input->post('cardno');
        $data['cmf_authorisationno'] = $this->input->post('authorizationno');
        $data['cmf_expirydate'] = $this->input->post('expirydate');
        $data['cmf_crstatus'] = $this->input->post('creditstatus');
        $data['cmf_crlimit'] = str_replace(",","",$this->input->post('creditlimit'));
        $data['cmf_crf'] = $this->input->post('creditterms');
        $data['cmf_crrem'] = $this->input->post('creditrem');
        $data['cmf_adrem'] = $this->input->post('adrem');

        $data['bipps_status'] = $this->input->post('bipps');

        $this->customers->saveupdateNewData($data, abs($id));
				#print_r2($data); exit;
        $msg = "You successfully update Customer";

        $this->session->set_flashdata('msg', $msg);

        redirect('customer');
    }
	
	public function validated_id($id) {

	    //echo $id ; exit;
		$data = $this->customers->getvalidation($id);
        //print_r2($data) ; exit;
		
        foreach($data as $row)  
        { 
            $ao_amf = $row['ao_amf']; $ao_cmf = $row['ao_cmf'];
            $pr_amf = $row['pr_amf']; $pr_cmf = $row['pr_cmf'];
            $or_amf = $row['or_amf']; $or_cmf = $row['or_cmf'];
            $dc_amf = $row['dc_amf']; $dc_cmf = $row['dc_cmf'];
        }
		
        if ($ao_amf != null || $ao_cmf != null || $pr_amf != null || $pr_cmf != null || $or_amf != null || $or_cmf != null || $dc_amf != null || $dc_cmf != null)   
        {
            echo "true";

        }   else {

                echo "false"; 
                $this->customers->removeData(abs($id));
            }        
		
	}

    public function removedata($id)
    {
        $this->customers->removeData(abs($id));
        redirect('customer');
    }

    public function searchdata()
    {
        $data['country'] = $this->countries->listOfCountry();
        $data['zipcode'] = $this->zipcities->listOfZipCity();
        $data['catad'] = $this->categoryads->listOfCategoryad();
        $data['paytype'] = $this->paytypes->listOfPayType();
        $data['vat'] = $this->vats->listOfVat();
        $data['acctexec'] = $this->employeeprofiles->listEmpAcctExec();
        $data['collector'] = $this->employeeprofiles->listEmpCollector();
        $data['collectorasst'] = $this->employeeprofiles->listEmpCollAst();
        $data['collectorarea'] = $this->collectorareas->listOfCollectorArea();
        $data['industry'] = $this->industries->listOfIndustry();
        $data['wtax'] = $this->wtaxes->listOfWtax();
        $data['creditcard'] = $this->creditcards->listOfCreditCard();
        $data['creditterm'] = $this->creditterms->listOfCreditTerm();
        $data['branch'] = $this->branches->listOfBranch();
        $response['searchdata_view'] = $this->load->view('customers/searchdata', $data, true);
        echo json_encode($response);
    }

     public function search()
    {
            $searchkey['cmf_code'] = $this->input->post('customercode');

            $searchkey['cmf_name'] = $this->input->post('customername');

            $searchkey['cmf_branch'] = $this->input->post('branch');

            $searchkey['cmf_catad'] = $this->input->post('agencydirect');

            $searchkey['cmf_paytype'] = $this->input->post('paytype');

            $searchkey['cmf_vatcode'] = $this->input->post('vatcode');

            $searchkey['cmf_aef'] = $this->input->post('acctexec');

            $searchkey['cmf_coll'] = $this->input->post('collector');

            $searchkey['cmf_collarea'] = $this->input->post('collectorarea');

            $searchkey['cmf_collasst'] = $this->input->post('collectorasst');

            $searchkey['cmf_industry'] = $this->input->post('industry');

            $searchkey['cmf_crf'] = $this->input->post('creditterms');

            $searchkey['id'] = $this->input->post('id');

            $searchkey['cmf_crlimit'] = $this->input->post('crlimit');

            $searchkey['cmf_crstatusname'] = $this->input->post('statusname');

            $data['customer_list'] = $this->customers->search($searchkey);

            $navigation['data'] = $this->GlobalModel->moduleList();

            #$data['customer_list'] = $this->customers->list_of_customer();

            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);

            $page = input_get('page', 1);

            $total = $this->customers->countAll();

            $total = $total->count_id ;

            $limit = 15;

            $data['pages'] = pages($total, $page, $limit);

            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);

            $data['canADD'] = $this->GlobalModel->moduleFunction("customer", 'ADD');
            $data['canEDIT'] = $this->GlobalModel->moduleFunction("customer", 'EDIT');
            $data['canDELETE'] = $this->GlobalModel->moduleFunction("customer", 'DELETE');
            $data['canVIEW'] = $this->GlobalModel->moduleFunction("customer", 'VIEW');
            $data['canCUSTOMERCCCONTACT'] = $this->GlobalModel->moduleFunction("customer", 'CUSTOMERCCCONTACT');

            $welcome_layout['content'] = $this->load->view('customers/index', $data, true);

            $this->load->view('welcome_index', $welcome_layout);
    }

    public function viewCustData() {

        $custid = $this->input->post('id');
        $data['data'] = $this->customers->getCustomerDataDetailed($custid);

        $response['view'] = $this->load->view('customers/viewcustomer', $data, true);

        echo json_encode($response);

    }

    public function uploadcustdata($id) {

        $navigation['data'] = $this->GlobalModel->moduleList();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);

        $data['info'] = $this->customers->thisCustomerCurrentData($id);

        $data['list'] = $this->customers->getFileAttachmentofClientData($id);

        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        //$data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');

        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('customers/customers_upload/uploadcustdata', $data, true);
        $this->load->view('welcome_index', $welcome_layout);


    }

     public function uploading() {

        $custid = $this->input->post('custid');

        ini_set('memory_limit', -1);

        $config['upload_path'] = '/var/www/customerattachment/';
        $config['allowed_types'] = 'gif|jpg|png|doc|xls|pdf|csv|xml|txt|ppt';
        $config['max_size']    = '200000';
        $config['max_width']  = '4000';
        $config['max_height']  = '3000';
        $this->load->helper('inflector');
        $file_name = $custid.'_'.Date('mdyhis').'_'.underscore($_FILES['userfile']['name']);
        $config['file_name'] = $file_name;
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload())
        {
            $data['custid'] = $this->input->post('custid');
            $fileData = $this->upload->data();

            $data['filename'] = $fileData['file_name'];
            $data['filetype'] = $fileData['file_ext'];

            redirect('customer/uploadcustdata/'.$data['custid']);

        }
        else
        {

            $data['custid'] = $this->input->post('custid');

            $file = $this->upload->data();

            $this->upload->initialize($config);
            $fileData = $this->upload->data();

            $data['filename'] = $file['file_name'];
            $data['filetype'] = $file['file_ext'];

            $this->customers->saveDataUpload($data);

            redirect('customer/uploadcustdata/'.$data['custid']);

        }

    }

    public function viewcustdatafile($id = null) {

        $data['file'] = $this->customers->getFileattachmentofClientDataUpload($id);

        $this->load->view('customers/customers_upload/loadfileview', $data);

    }

    public function removeDataUpload($id, $custid) {
        //Customer Uploading

        $this->customers->removeupload(abs($id));

        redirect('customer/uploadcustdata/'.$custid);

    }

    public function ccContactCustData() {

        $custid = $this->input->post('id');
        $data['data'] = $this->customers->getCCCustomerData($custid);

        $response['view'] = $this->load->view('customers/cccontactdetailview', $data, true);

        echo json_encode($response);

    }

    public function ccupdate($id) {

        # Collection Contact Detailed

        $data['cc_name1'] = $this->input->post('contact1_name');
        $data['cc_position1'] = $this->input->post('contact1_position');
        $data['cc_email1'] = $this->input->post('contact1_email');
        $data['cc_contact1'] = $this->input->post('contact1_contactnumber');
        $data['cc_address1'] = $this->input->post('contact1_address');
        $data['cc_name2'] = $this->input->post('contact2_name');
        $data['cc_position2'] = $this->input->post('contact2_position');
        $data['cc_email2'] = $this->input->post('contact2_email');
        $data['cc_contact2'] = $this->input->post('contact2_contactnumber');
        $data['cc_address2'] = $this->input->post('contact2_address');
        $data['cc_name3'] = $this->input->post('contact3_name');
        $data['cc_position3'] = $this->input->post('contact3_position');
        $data['cc_email3'] = $this->input->post('contact3_email');
        $data['cc_contact3'] = $this->input->post('contact3_contactnumber');
        $data['cc_address3'] = $this->input->post('contact3_address');

        $this->customers->updateccCollectionDetail($data, $id);

        $msg = "You successfully update Collection Contact Detail";

        $this->session->set_flashdata('msg', $msg);

        redirect('customer');
    }

}
