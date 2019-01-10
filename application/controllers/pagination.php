<?php
    
class Pagination extends CI_Controller
{
        
	function __construct()
	{
		parent::__construct();
		$this->sess = $this->authlib->validate(); 
		$this->load->model(array('model_artype/artypes'));	  
	}

	public function index() {
		$this->load->model('model_product/products');
        
        $data['product'] = $this->products->listOfProduct();

		$navigation['data'] = $this->GlobalModel->moduleList();   
		//$data['data_list'] = $this->artypes->data_list(); 
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('paginations/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	public function masspaginate()
	{
	   $this->load->model('model_pagination/paginations');
	   
	   $product = mysql_escape_string($this->input->post('product'));
	   $fromdate = mysql_escape_string($this->input->post('fromdate'));
       $todate = mysql_escape_string($this->input->post('todate'));
	   $booktype = mysql_escape_string($this->input->post('booktype'));
	   
	   $data['massresult'] = $this->paginations->massPagenate($product, $fromdate, $todate, $booktype);
	   
	   $response['massresult'] = $this->load->view('paginations/_massresult', $data, true);
	   
	   echo json_encode($response);
	}

	public function adpaginate($act) 
	{
	   $this->load->model('model_pagination/paginations');            
	   
	   $adnumber = mysql_escape_string($this->input->post('adnumber'));
	   $issuedate = mysql_escape_string($this->input->post('issuedate'));
	   
	   $data['singleresult'] = $this->paginations->adPagenate($adnumber, $issuedate, $act);  
	   
	   $response['singleresult'] = $this->load->view('paginations/_singleresult', $data, true);     
	   
	   echo json_encode($response);
	}

}
