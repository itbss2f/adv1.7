<?php 

class YMS_Product_Budget extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->sess = $this->authlib->validate(); 
		$this->load->model(array('model_yms_products/model_yms_products', 'model_yms_product_budget/model_yms_product_budgets'));	  
	}

	public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList();   
		$data['budget'] = $this->model_yms_product_budgets->list_Budget();
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('yms_product_budgets/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	public function newdata() {		
		$data['ymsproduct'] = $this->model_yms_products->list_ymsproducts();	
		$data['adtype_account'] = $this->model_yms_product_budgets->list_Adtype_Account();
		$response['newdata_view'] = $this->load->view('yms_product_budgets/newdata', $data, true);
		echo json_encode($response);
	}

	public function validateCode() {  
		$pbyear = $this->input->post('pbyear');
		$pbproduct = $this->input->post('pbproduct');      
		$pbaccount = $this->input->post('pbaccount');

		$check = $this->model_yms_product_budgets->checkUnique($pbyear, $pbproduct, $pbaccount);
		if (!empty($check)) {
			echo "true";
		}  
	}

	public function save() {
		$data['budget_year'] = $this->input->post('pb_year');
		$data['yms_product_id'] = $this->input->post('pb_ymsproduct');
		$data['account'] = $this->input->post('pb_adtype_account');
		$data['remarks'] = $this->input->post('pb_remarks');
		$data['formula'] = $this->input->post('pb_formula');
		$data['method1'] = $this->input->post('pb_method1');
		$data['method2'] = $this->input->post('pb_method2');
		$data['sales_jan'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsjan')));
		$data['sales_feb'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsfeb')));
		$data['sales_mar'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsmar')));
		$data['sales_apr'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsapr')));
		$data['sales_may'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsmay')));
		$data['sales_jun'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsjun')));
		$data['sales_jul'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsjul')));
		$data['sales_aug'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsaug')));
		$data['sales_sep'] = mysql_escape_string(str_replace(",","",$this->input->post('pbssep')));
		$data['sales_oct'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsoct')));
		$data['sales_nov'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsnov')));
		$data['sales_dec'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsdec')));
		$data['sales_total'] = mysql_escape_string(str_replace(",","",$this->input->post('pbstotal')));
		$data['cm_jan'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmjan')));
		$data['cm_feb'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmfeb')));
		$data['cm_mar'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmmar')));
		$data['cm_apr'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmapr')));
		$data['cm_may'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmmay')));
		$data['cm_jun'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmjun')));
		$data['cm_jul'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmjul')));
		$data['cm_aug'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmaug')));
		$data['cm_sep'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmsep')));
		$data['cm_oct'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmoct')));
		$data['cm_nov'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmnov')));
		$data['cm_dec'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmdec')));
		$data['cm_total'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmtotal')));
		
		$this->model_yms_product_budgets->saveNewData($data);

		$msg = "You successfully save YMS - Product Budget";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_product_budget');
	}

	public function compute() {
		$total = 0;
		for ($x = 1; $x <= 12; $x++) {
			$total +=  mysql_escape_string(str_replace(",","",$this->input->post('amt'.$x)));
		}
		
		$response['total'] = number_format($total, 2, '.',',');
		echo json_encode($response);
	}

	public function editdata() {		
		$id = $this->input->post('id');
		$data['ymsproduct'] = $this->model_yms_products->list_ymsproducts();	
		$data['adtype_account'] = $this->model_yms_product_budgets->list_Adtype_Account();
		$data['data'] = $this->model_yms_product_budgets->getData(abs($id));
		$response['editdata_view'] = $this->load->view('yms_product_budgets/editdata', $data, true);
		echo json_encode($response);
	}

	public function detaildata() {
		$mainid = $this->input->post('mainid');
		$month = $this->input->post('month');
		
		$data['main'] = $this->model_yms_product_budgets->getDataMain($mainid);
		$data['month'] = date ("F", strtotime('1980-'.$month.'-01'));
		$data['m'] = date ("M", strtotime('1980-'.$month.'-01'));
		$data['data'] = $this->model_yms_product_budgets->getDetailSale($mainid, $month);

		$response['detaildata_view'] = $this->load->view('yms_product_budgets/detaildata', $data, true);
		echo json_encode($response);
	}

	public function saveDetail() {
		$arr = $this->input->post('arr');
		$arr2 = $this->input->post('arr2');
		$arr3 = $this->input->post('arr3');
		
		$this->model_yms_product_budgets->saveDetail($arr, $arr2, $arr3);
	}

	public function update($id) {
		$data['remarks'] = $this->input->post('pb_remarks');
		$data['formula'] = $this->input->post('pb_formula');
		$data['method1'] = $this->input->post('pb_method1');
		$data['method2'] = $this->input->post('pb_method2');
		$data['sales_jan'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsjan')));
		$data['sales_feb'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsfeb')));
		$data['sales_mar'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsmar')));
		$data['sales_apr'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsapr')));
		$data['sales_may'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsmay')));
		$data['sales_jun'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsjun')));
		$data['sales_jul'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsjul')));
		$data['sales_aug'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsaug')));
		$data['sales_sep'] = mysql_escape_string(str_replace(",","",$this->input->post('pbssep')));
		$data['sales_oct'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsoct')));
		$data['sales_nov'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsnov')));
		$data['sales_dec'] = mysql_escape_string(str_replace(",","",$this->input->post('pbsdec')));
		$data['sales_total'] = mysql_escape_string(str_replace(",","",$this->input->post('pbstotal')));
		$data['cm_jan'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmjan')));
		$data['cm_feb'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmfeb')));
		$data['cm_mar'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmmar')));
		$data['cm_apr'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmapr')));
		$data['cm_may'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmmay')));
		$data['cm_jun'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmjun')));
		$data['cm_jul'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmjul')));
		$data['cm_aug'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmaug')));
		$data['cm_sep'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmsep')));
		$data['cm_oct'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmoct')));
		$data['cm_nov'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmnov')));
		$data['cm_dec'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmdec')));
		$data['cm_total'] = mysql_escape_string(str_replace(",","",$this->input->post('pbcmtotal')));
		
		$this->model_yms_product_budgets->saveupdateData(abs($id), $data);

		$msg = "You successfully save update YMS - Product Budget";

		$this->session->set_flashdata('msg', $msg);

		redirect('yms_product_budget');
	}

	public function computeDetailed() {
		$arr = $this->input->post('arr');
		$countarr = count($arr);
		$totalamt = 0;
		for ($x = 0; $x < $countarr; $x++) {
			$amt = mysql_escape_string(str_replace(",","",$arr[$x]));

			$totalamt += $amt;	
		}
		$response['total'] = number_format($totalamt, 2, '.',',');
		echo json_encode($response);
	}

	public function dupcomputeDetailed() {
		$arr1 = $this->input->post('arr1');
		$arr2 = $this->input->post('arr2');
		$countarr = count($arr1);
		$totalamt1 = 0;
		$totalamt2 = 0;
		for ($x = 0; $x < $countarr; $x++) {
			$amt1 = mysql_escape_string(str_replace(",","",$arr1[$x]));
			$amt2 = mysql_escape_string(str_replace(",","",$arr2[$x]));

			$totalamt1 += $amt1;
			$totalamt2 += $amt2;		
		}
		$response['total1'] = number_format($totalamt1, 2, '.',',');
		$response['total2'] = number_format($totalamt2, 2, '.',',');
		echo json_encode($response);
	}

	public function removedata($id) {		
		$this->model_yms_product_budgets->removeData(abs($id));
		redirect('yms_product_budget');
	}

}

