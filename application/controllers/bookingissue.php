<?php
  
class BookingIssue extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->sess = $this->authlib->validate();           
		$this->load->model(array('model_booking/bookings', 'model_booking/bookingissuemodel','model_subtype/subtypes','model_classification/classifications',
                                 'model_adsize/adsizes','model_color/colors', 'model_position/positions',
						   'model_adtypecharge/adtypecharges'));	      
	   
	}

	public function selectedDate() {
		$data['datetext'] = mysql_escape_string($this->input->post('dateText'));
		$data['type'] = mysql_escape_string($this->input->post('type'));
		$data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
		$data['product'] = mysql_escape_string($this->input->post('product'));
		$data['classification'] = mysql_escape_string($this->input->post('classification'));
		$data['subclassification'] = mysql_escape_string($this->input->post('subclassification'));
		$data['ratecode'] = mysql_escape_string($this->input->post('ratecode'));
		$data['width'] = mysql_escape_string($this->input->post('width'));
		$data['length'] = mysql_escape_string($this->input->post('length'));
		$data['vatcode'] = mysql_escape_string($this->input->post('vatcode'));
		$data['misc1'] = mysql_escape_string($this->input->post('misc1'));
		$data['misc2'] = mysql_escape_string($this->input->post('misc2'));
		$data['misc3'] = mysql_escape_string($this->input->post('misc3'));
		$data['misc4'] = mysql_escape_string($this->input->post('misc4'));
		$data['misc5'] = mysql_escape_string($this->input->post('misc5'));
		$data['misc6'] = mysql_escape_string($this->input->post('misc6'));
		$data['miscper1'] = mysql_escape_string($this->input->post('miscper1'));
		$data['miscper2'] = mysql_escape_string($this->input->post('miscper2'));
		$data['miscper3'] = mysql_escape_string($this->input->post('miscper3'));
		$data['miscper4'] = mysql_escape_string($this->input->post('miscper4'));
		$data['miscper5'] = mysql_escape_string($this->input->post('miscper5'));
		$data['miscper6'] = mysql_escape_string($this->input->post('miscper6'));
		$data['totalprem'] = mysql_escape_string($this->input->post('totalprem'));
		$data['totaldisc'] = mysql_escape_string($this->input->post('totaldisc'));
		$data['pagemin'] = mysql_escape_string($this->input->post('pagemin'));
		$data['pagemax'] = mysql_escape_string($this->input->post('pagemax'));

		$data['eps'] = mysql_escape_string($this->input->post('eps'));
		$data['color'] = mysql_escape_string($this->input->post('color'));
		$data['position'] = mysql_escape_string($this->input->post('position'));
		$data['totalsize'] = mysql_escape_string($this->input->post('totalsize'));
		$data['adsize'] = mysql_escape_string($this->input->post('adsize'));
		$data['records'] = mysql_escape_string($this->input->post('records'));
		$data['production'] = mysql_escape_string($this->input->post('production'));
		$data['followup'] = mysql_escape_string($this->input->post('followup'));
		$data['billing'] = mysql_escape_string($this->input->post('billing'));
		

		# TODO get days of rate
		$date = new DateTime($data['datetext']);        
		$d = $date->format('w');
		switch ($d)
		{
		  case 0:
			 $daysrate = "AND adtyperate_sunday = '1'";                    
			 break;
		  case 1:
			 $daysrate = "AND adtyperate_monday = '1'";                
			 break;
		  case 2:
			 $daysrate = "AND adtyperate_tuesday = '1'";                
			 break;
		  case 3:
			 $daysrate = "AND adtyperate_wednesday = '1'";                
			 break;
		  case 4:
			 $daysrate = "AND adtyperate_thursday = '1'";                
			 break;
		  case 5:
			 $daysrate = "AND adtyperate_friday = '1'";                
			 break;
		  case 6:
			 $daysrate = "AND adtyperate_saturday = '1'";                
			 break;                                                            
		}               
		$rate['ratetype'] = 'R';
        $type = $data['type'];   
        
        if (abs($data['type']) != 0 ) {
            $type = $this->bookingissuemodel->getBookType($data['type']);
        }
		$rate = $this->bookingissuemodel->getRate($data['product'], $type, $data['classification'], $data['ratecode'], $data['datetext'], $daysrate);                

		# Added inserts
		if (!empty($rate)) { $data['rateamt'] = $rate['rate'];  $ratetype = $rate['ratetype'];  } else { $data['rateamt'] = '0.00'; $ratetype = 'R';   }

		# Important Variable 
		$vatrate = $this->bookingissuemodel->getVatRate($data['vatcode']);
		$vatcode = $data['vatcode'];
		$agencycommisionrate = mysql_escape_string($this->input->post('duepercent')); 
		$paytype = mysql_escape_string($this->input->post('paytype')); 

		# Variables        
		$issuerate = $data['rateamt'];
		$vatrate = ($vatrate / 100);
		$totalsize = $data['totalsize'];
		$agencycommisionrate = ($agencycommisionrate / 100);

		# Start for compute for computedamount
		$charges['misc1'] = $data['misc1'];
		$charges['misc2'] = $data['misc2'];
		$charges['misc3'] = $data['misc3'];
		$charges['misc4'] = $data['misc4'];
		$charges['misc5'] = $data['misc5'];
		$charges['misc6'] = $data['misc6'];
        

		$computedata =  $this->computeTotalCost($data['product'], $type, $data['rateamt'], $ratetype, $data['classification'], $data['totalsize'], $issuerate, $charges, $data['datetext']);        
		if ($data['rateamt'] > 800) {
            $baseamount = $data['rateamt'];     
        } else {                                
            $baseamount = $totalsize * $data['rateamt']; 
        }
           
		if ($paytype == 6) {
		$totalcost = 0;    
		} else {
		$totalcost = $computedata['totalcost'];
		}        
		$computedamount = $computedata['totalcost'];
		# End for compute for computedamount

		$agencycommision = $totalcost * $agencycommisionrate;  
		$netvatablesale = 0; $vatexempt = 0;  $vatzero = 0;  
		if ($vatcode == "1" || $vatcode == "2" || $vatcode == "3") {
		$netvatablesale = $totalcost - $agencycommision;   
		} else if ($vatcode == "4") {
		$vatexempt = $totalcost - $agencycommision;      
		} else if ($vatcode == "5") {
		$vatzero = $totalcost - $agencycommision;      
		}
		$vatamt = ($netvatablesale + $vatexempt + $vatzero) * $vatrate;
		$amountdue = ($netvatablesale + $vatexempt + $vatzero) + $vatamt;
		   
		$data['premiumamt'] = $computedata['surcharge'];
		$data['discountamt'] = $computedata['discount'];
		$data['baseamt'] = $baseamount;       
		$data['computedamt'] =  $computedamount; // vat gross amount                      
		$data['totalcost'] = $totalcost; // net vatable sales
		$data['agencycom'] = $agencycommision; // vat amount
		$data['nvs'] = $netvatablesale; // vat sales        
		$data['vatexempt'] = $vatexempt; // vat exempt  
		$data['vatzerorate'] = $vatzero; // vat zero        
		$data['vatamt'] = $vatamt; // vat   
		$data['amtdue'] = $amountdue; // amount due         

		$result['datelist'] = $this->bookingissuemodel->selectedDateAlgo($data);	
  		$result['mykeyid'] = $data['mykeyid'];	
		$result['type'] = $data['type'];	
		$result['product'] = $data['product'];	
		$result['vatcode'] = $data['vatcode'];
		$result['duepercent'] = mysql_escape_string($this->input->post('duepercent')); 

		if (!empty($result['datelist'])) :
			$response['datelist'] = $result['datelist'];
			$response['startdate'] = $result['datelist'][0]['myissuedate'];
			$response['enddate'] = $result['datelist'][count($result['datelist']) - 1]['myissuedate'];
			$response['totalissueno'] = count($result['datelist']);
			
		else :
			$response['datelist'] = null;					
			$response['startdate'] = "";
			$response['enddate'] = "";
			$response['totalissueno'] = 0; 	
		endif;	
        
        $summarydata = $this->bookingissuemodel->getSummarydata($data['mykeyid']);	
        $response['total_computedamt'] = $summarydata['total_computedamt'];
        $response['total_totalcost'] = $summarydata['total_totalcost'];
        $response['total_agencycom'] = $summarydata['total_agencycom'];
        $response['total_nvs'] = $summarydata['total_nvs'];
        $response['total_vatamt'] = $summarydata['total_vatamt'];
        $response['total_vatexempt'] = $summarydata['total_vatexempt'];
        $response['total_vatzerorate'] = $summarydata['total_vatzerorate'];
        $response['total_amtdue'] = $summarydata['total_amtdue'];
		$response['issuedate_data'] = $this->load->view('bookings/issuedate_Append', $result, true);
		echo json_encode($response);
	}

	public function getdaysofproduct() {
        
		$this->load->model('model_product/products'); 
		$getdays = $this->products->getProductDays(mysql_escape_string($this->input->post('product')));    
		$sun="";$mon="";$tue="";$wed="";$thu="";$fri="";$sat="";        
		if (!empty($getdays)) {		
			if ($getdays['sun'] == '1') {
				$sun ="(date.getDay() == 0) ||";
			}if ($getdays['mon'] == '1') {
				$mon = "(date.getDay() == 1) ||";
			}if ($getdays['tue'] == '1') {
				$tue = "(date.getDay() == 2) ||";
			}if ($getdays['wed'] == '1') {
				$wed = "(date.getDay() == 3) ||";
			}if ($getdays['thu'] == '1') {
				$thu = "(date.getDay() == 4) ||";
			}if ($getdays['fri'] == '1') {
				$fri = "(date.getDay() == 5) ||";
			}if ($getdays['sat'] == '1') {
				$sat = "(date.getDay() == 6) ||";
			}
		}
		$myvaliddays = $sun."".$mon."".$tue."".$wed."".$thu."".$fri."".$sat;
		$data['type'] = $this->input->post('type');
		$data['daysofissue'] = substr($myvaliddays, 0, -2);
		$data['ext_issuedate'] = "";
		$data['aonum'] = $this->input->post('aonum');
        $type = $data['type'];   
        
        if (abs($data['type']) != 0 ) {
            $type = $this->bookingissuemodel->getBookType($data['type']);
        }
        
		$data['canANTIDATE'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGANTIDATE');		
		$response['calendar'] = $this->load->view('bookings/script_issuedate', $data, true);
        echo json_encode($response);
        
	}


	/* Computation of totalcost and computedamount
	*  totalsize  = width * length
	*  issuerate = the rate of the ads base on the day of issue  e.g [mon,tue,wed,thu,fri,sat,sun]
	*  comples computation of surcharge and discount base of what is the given charges and the rank of the charges
	*  to compute
	* 
	*  computedamount cannot be edit. This amount is base on system generated computation
	*  totalcos (gross amount) can be edit due to overriding of amount. e.g base on packages or transcation of ae 
	*/
	public function computeTotalCost($product, $type, $rate, $ratetype, $classification, $totalsize, $issuerate, $charges, $date) {        
       $totalcost = 0;
	   if ($ratetype == 'A') {
		  $totalcost = $rate;            
	   } else if ($ratetype == 'R') {
		  $totalcost = $totalsize * $rate;                      
	   }        
	   $surchargerate = 0;
	   $surchargeamt = 0;
	   $discountrate = 0;
	   $discountamt = 0;          
	   $charge = $this->bookingissuemodel->getCharges($product, $type, $classification, $charges, $date);          
	   for ($x = 0; $x < count($charge); $x++) {
		  if ($charge[$x]['computerank'] == "A") {                
		      if ($charge[$x]['operator'] == "ADD") {
		          $surchargerate = $totalcost * ($charge[$x]['totalrate']/100); 
		          $totalcost = $totalcost + $surchargerate;  
		      } else if ($charge[$x]['operator'] == "SUB") {                    
		          $discountrate = $totalcost * ($charge[$x]['totalrate']/100); 
		          $totalcost = $totalcost - $discountrate;
		      }
		  } else if ($charge[$x]['computerank'] == "Z") {                
		       if ($charge[$x]['operator'] == "ADD") {
		          $surchargeamt = $totalcost * ($charge[$x]['totalrate']/100); 
		          $totalcost = $totalcost + $surchargeamt;  
		      } else if ($charge[$x]['operator'] == "SUB") {
		          $discountamt = $totalcost * ($charge[$x]['totalrate']/100); 
		          $totalcost = $totalcost - $discountamt;
		      }    
		  }
	   }
	   
	   $surcharge = $surchargerate + $surchargeamt; 
	   $discount = $discountrate + $discountamt;                        
	   return array('totalcost' => $totalcost, 'surcharge' => $surcharge, 'discount' => $discount);    
	}

	public function issue_edit() {	

		$issuedate = $this->input->post('issuedate');		
		$mykeyid = $this->input->post('mykeyid');
		$type = $this->input->post('type');
		$data['mykeyid'] = $mykeyid;
		$data['issuedate'] = $issuedate;
		$data['type'] = $type;
		$data['duepercent'] = $this->input->post('duepercent');
		$data['vatcode'] = $this->input->post('vatcode');
		$data['product'] = $this->input->post('product');

		$data['canBOOKINGOVERRIDE'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGOVERRIDE');
		
		$data['class'] = $this->classifications->listOfClassificationPerType($type);          
	     $data['subclass'] = $this->classifications->listOfSubClassificationType();  
		$data['adsize'] = $this->adsizes->listOfSize();  	     
	     $data['color'] = $this->colors->listOfColor();     
	     $data['position'] = $this->positions->listOfPosition();
		$data['misccharges'] = $this->adtypecharges->listOfMiscChargesByType($type);	
        
        
		$data['data'] = $this->bookingissuemodel->getEditableIssueFields($mykeyid, $issuedate);
        
        $data['paginatedTransac'] = $this->bookings->countInvoicePaginated($data['data']['ao_num']);   
		$response['issuedate_detailed'] = $this->load->view('bookings/issuedate_detailed', $data, true);

		echo json_encode($response);
	}
	
	public function overrideamt() {
		$override_amt = mysql_escape_string(str_replace(",","",$this->input->post('override_amt'))); 	
		$totalcost = $override_amt;
		$issuedate = $this->input->post('issuedate');		
		$mykeyid = $this->input->post('mykeyid');
		$data['vatcode'] = mysql_escape_string($this->input->post('vatcode'));;
		$data['mykeyid'] = $mykeyid;
		
	     # Important Variable 
		$vatcode = $data['vatcode'];
		$vatrate = $this->bookingissuemodel->getVatRate($data['vatcode']);		
		$agencycommisionrate = mysql_escape_string($this->input->post('duepercent')); 
		
		$vatrate = ($vatrate / 100);
		$agencycommisionrate = ($agencycommisionrate / 100);

		$agencycommision = $totalcost * $agencycommisionrate;
		$netvatablesale = 0; $vatexempt = 0;  $vatzero = 0;  
		if ($vatcode == "1" || $vatcode == "2" || $vatcode == "3") {
		$netvatablesale = $totalcost - $agencycommision;   
		} else if ($vatcode == "4") {
		$vatexempt = $totalcost - $agencycommision;      
		} else if ($vatcode == "5") {
		$vatzero = $totalcost - $agencycommision;      
		}
		$vatamt = ($netvatablesale + $vatexempt + $vatzero) * $vatrate;
		$amountdue = ($netvatablesale + $vatexempt + $vatzero) + $vatamt;
		   		
		$data['totalcost'] = $totalcost; // net vatable sales
		$data['agencycom'] = $agencycommision; // vat amount
		$data['nvs'] = $netvatablesale; // vat sales        
		$data['vatexempt'] = $vatexempt; // vat exempt  
		$data['vatzerorate'] = $vatzero; // vat zero        
		$data['vatamt'] = $vatamt; // vat   
		$data['amtdue'] = $amountdue; // amount due  

		$response['totalcost'] = number_format($totalcost, 2, ".", ",")." Total Cost";
		$response['agencycom'] = number_format($agencycommision, 2, ".", ",")." Agency Comm.";
		$response['vatamt'] = number_format($vatamt, 2, ".", ",")." VAT Amount";
		$response['amtdue'] = number_format($amountdue, 2, ".", ",");

		$this->bookingissuemodel->overrideAmount($mykeyid, $issuedate, $data);

 		# TODO
		/* Viewing update in tables and update billing information */

		$result['datelist'] = $this->bookingissuemodel->retrieveIssueData($data);	
  		$result['mykeyid'] = $mykeyid;	
		$result['type'] = $this->input->post('type');
		$result['product'] = $this->input->post('product');
		$result['vatcode'] = $vatcode;
		$result['duepercent'] = mysql_escape_string($this->input->post('duepercent')); 
		$summarydata = $this->bookingissuemodel->getSummarydata($mykeyid);	  	
		$response['total_computedamt'] = $summarydata['total_computedamt'];
		$response['total_totalcost'] = $summarydata['total_totalcost'];
		$response['total_agencycom'] = $summarydata['total_agencycom'];
		$response['total_nvs'] = $summarydata['total_nvs'];
		$response['total_vatamt'] = $summarydata['total_vatamt'];
		$response['total_vatexempt'] = $summarydata['total_vatexempt'];
		$response['total_vatzerorate'] = $summarydata['total_vatzerorate'];
		$response['total_amtdue'] = $summarydata['total_amtdue'];
		$response['issuedate_data'] = $this->load->view('bookings/issuedate_Append', $result, true);

 		echo json_encode($response);
	}
    
    public function overrideall() {
        $override_amt = mysql_escape_string(str_replace(",","",$this->input->post('override_amt')));     
        $totalcost = $override_amt;
        $issuedate = $this->input->post('issuedate');        
        $mykeyid = $this->input->post('mykeyid');
        $data['vatcode'] = mysql_escape_string($this->input->post('vatcode'));;
        $data['mykeyid'] = $mykeyid;
        
         # Important Variable 
        $vatcode = $data['vatcode'];
        $vatrate = $this->bookingissuemodel->getVatRate($data['vatcode']);        
        $agencycommisionrate = mysql_escape_string($this->input->post('duepercent')); 
        
        $vatrate = ($vatrate / 100);
        $agencycommisionrate = ($agencycommisionrate / 100);

        $agencycommision = $totalcost * $agencycommisionrate;
        $netvatablesale = 0; $vatexempt = 0;  $vatzero = 0;  
        if ($vatcode == "1" || $vatcode == "2" || $vatcode == "3") {
        $netvatablesale = $totalcost - $agencycommision;   
        } else if ($vatcode == "4") {
        $vatexempt = $totalcost - $agencycommision;      
        } else if ($vatcode == "5") {
        $vatzero = $totalcost - $agencycommision;      
        }
        $vatamt = ($netvatablesale + $vatexempt + $vatzero) * $vatrate;
        $amountdue = ($netvatablesale + $vatexempt + $vatzero) + $vatamt;
                   
        $data['totalcost'] = $totalcost; // net vatable sales
        $data['agencycom'] = $agencycommision; // vat amount
        $data['nvs'] = $netvatablesale; // vat sales        
        $data['vatexempt'] = $vatexempt; // vat exempt  
        $data['vatzerorate'] = $vatzero; // vat zero        
        $data['vatamt'] = $vatamt; // vat   
        $data['amtdue'] = $amountdue; // amount due  

        $response['totalcost'] = number_format($totalcost, 2, ".", ",")." Total Cost";
        $response['agencycom'] = number_format($agencycommision, 2, ".", ",")." Agency Comm.";
        $response['vatamt'] = number_format($vatamt, 2, ".", ",")." VAT Amount";
        $response['amtdue'] = number_format($amountdue, 2, ".", ",");

        $this->bookingissuemodel->overrideAmountAll($mykeyid, $issuedate, $data);

         # TODO
        /* Viewing update in tables and update billing information */

        $result['datelist'] = $this->bookingissuemodel->retrieveIssueData($data);    
          $result['mykeyid'] = $mykeyid;    
        $result['type'] = $this->input->post('type');
        $result['product'] = $this->input->post('product');
        $result['vatcode'] = $vatcode;
        $result['duepercent'] = mysql_escape_string($this->input->post('duepercent')); 
        $summarydata = $this->bookingissuemodel->getSummarydata($mykeyid);          
        $response['total_computedamt'] = $summarydata['total_computedamt'];
        $response['total_totalcost'] = $summarydata['total_totalcost'];
        $response['total_agencycom'] = $summarydata['total_agencycom'];
        $response['total_nvs'] = $summarydata['total_nvs'];
        $response['total_vatamt'] = $summarydata['total_vatamt'];
        $response['total_vatexempt'] = $summarydata['total_vatexempt'];
        $response['total_vatzerorate'] = $summarydata['total_vatzerorate'];
        $response['total_amtdue'] = $summarydata['total_amtdue'];
        $response['issuedate_data'] = $this->load->view('bookings/issuedate_Append', $result, true);

         echo json_encode($response);    
    }
    
    public function overrideAmountSuperced() {
        $override_amt = mysql_escape_string(str_replace(",","",$this->input->post('override_amt')));     
        $totalcost = $override_amt;
        $issuedate = $this->input->post('issuedate');        
        $mykeyid = $this->input->post('mykeyid');
        $data['vatcode'] = mysql_escape_string($this->input->post('vatcode'));;
        $data['mykeyid'] = $mykeyid;
        
         # Important Variable 
        $vatcode = $data['vatcode'];
        $vatrate = $this->bookingissuemodel->getVatRate($data['vatcode']);        
        $agencycommisionrate = mysql_escape_string(abs($this->input->post('duepercent'))); 
        
        $vatrate = ($vatrate / 100);
        $agencycommisionrate = ($agencycommisionrate / 100);

        $agencycommision = $totalcost * $agencycommisionrate;
        $netvatablesale = 0; $vatexempt = 0;  $vatzero = 0;  
        if ($vatcode == "1" || $vatcode == "2" || $vatcode == "3") {
        $netvatablesale = $totalcost - $agencycommision;   
        } else if ($vatcode == "4") {
        $vatexempt = $totalcost - $agencycommision;      
        } else if ($vatcode == "5") {
        $vatzero = $totalcost - $agencycommision;      
        }
        $vatamt = ($netvatablesale + $vatexempt + $vatzero) * $vatrate;
        $amountdue = ($netvatablesale + $vatexempt + $vatzero) + $vatamt;
                   
        $data['totalcost'] = $totalcost; // net vatable sales
        $data['agencycom'] = $agencycommision; // vat amount
        $data['nvs'] = $netvatablesale; // vat sales        
        $data['vatexempt'] = $vatexempt; // vat exempt  
        $data['vatzerorate'] = $vatzero; // vat zero        
        $data['vatamt'] = $vatamt; // vat   
        $data['amtdue'] = $amountdue; // amount due  

        $response['totalcost'] = number_format($totalcost, 2, ".", ",")." Total Cost";
        $response['agencycom'] = number_format($agencycommision, 2, ".", ",")." Agency Comm.";
        $response['vatamt'] = number_format($vatamt, 2, ".", ",")." VAT Amount";
        $response['amtdue'] = number_format($amountdue, 2, ".", ",");

        $this->bookingissuemodel->overrideAmountSuperced($mykeyid, $issuedate, $data);

         # TODO
        /* Viewing update in tables and update billing information */

        $result['datelist'] = $this->bookingissuemodel->retrieveIssueData($data);    
        
        //print_r2($result);
        $result['mykeyid'] = $mykeyid;    
        $result['type'] = $this->input->post('type');
        $result['product'] = $this->input->post('product');
        $result['vatcode'] = $vatcode;
        $result['duepercent'] = mysql_escape_string($this->input->post('duepercent')); 
        $summarydata = $this->bookingissuemodel->getSummarydata($mykeyid);          
        $response['total_computedamt'] = $summarydata['total_computedamt'];
        $response['total_totalcost'] = $summarydata['total_totalcost'];
        $response['total_agencycom'] = $summarydata['total_agencycom'];
        $response['total_nvs'] = $summarydata['total_nvs'];
        $response['total_vatamt'] = $summarydata['total_vatamt'];
        $response['total_vatexempt'] = $summarydata['total_vatexempt'];
        $response['total_vatzerorate'] = $summarydata['total_vatzerorate'];
        $response['total_amtdue'] = $summarydata['total_amtdue'];
        $response['issuedate_data'] = $this->load->view('bookings/superced_append', $result, true);

         echo json_encode($response);
    }

	public function updatedetailed() {
		$data['classification'] = mysql_escape_string($this->input->post('classification'));
		$data['subclassification'] = mysql_escape_string($this->input->post('subclassification'));
		$data['width'] = mysql_escape_string($this->input->post('width'));
		$data['length'] = mysql_escape_string($this->input->post('length'));
		$data['vatcode'] = mysql_escape_string($this->input->post('vatcode'));
		$data['misc1'] = mysql_escape_string($this->input->post('misc1'));
		$data['misc2'] = mysql_escape_string($this->input->post('misc2'));
		$data['misc3'] = mysql_escape_string($this->input->post('misc3'));
		$data['misc4'] = mysql_escape_string($this->input->post('misc4'));
		$data['misc5'] = mysql_escape_string($this->input->post('misc5'));
		$data['misc6'] = mysql_escape_string($this->input->post('misc6'));
		$data['miscper1'] = mysql_escape_string($this->input->post('miscper1'));
		$data['miscper2'] = mysql_escape_string($this->input->post('miscper2'));
		$data['miscper3'] = mysql_escape_string($this->input->post('miscper3'));
		$data['miscper4'] = mysql_escape_string($this->input->post('miscper4'));
		$data['miscper5'] = mysql_escape_string($this->input->post('miscper5'));
		$data['miscper6'] = mysql_escape_string($this->input->post('miscper6'));
		$data['totalprem'] = mysql_escape_string($this->input->post('totalprem'));
		$data['totaldisc'] = mysql_escape_string($this->input->post('totaldisc'));
		$data['pagemin'] = mysql_escape_string($this->input->post('pagemin'));
		$data['pagemax'] = mysql_escape_string($this->input->post('pagemax'));

		$data['eps'] = mysql_escape_string($this->input->post('eps'));

		$data['color'] = mysql_escape_string($this->input->post('color'));
		$data['position'] = mysql_escape_string($this->input->post('position'));
		$data['totalsize'] = mysql_escape_string($this->input->post('totalsize'));
		$data['adsize'] = mysql_escape_string($this->input->post('adsize'));
		$data['records'] = mysql_escape_string($this->input->post('records'));
		$data['production'] = mysql_escape_string($this->input->post('production'));
		$data['followup'] = mysql_escape_string($this->input->post('followup'));
		$data['billing'] = mysql_escape_string($this->input->post('billing'));	

		$data['rateamt'] = $this->input->post('rateamt');
		$data['vatcode'] = $this->input->post('vatcode');

		$data['datetext'] = mysql_escape_string($this->input->post('issuedate'));
		$data['type'] = mysql_escape_string($this->input->post('type'));
		$data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
		$data['product'] = mysql_escape_string($this->input->post('product'));

		# Important Variable 
		$vatrate = $this->bookingissuemodel->getVatRate($data['vatcode']);
		$vatcode = $data['vatcode'];
		$agencycommisionrate = mysql_escape_string($this->input->post('duepercent')); 		

		# Variables        
		$issuerate = $data['rateamt'];
		$vatrate = ($vatrate / 100);
		$totalsize = $data['totalsize'];
		$agencycommisionrate = ($agencycommisionrate / 100);

		# Start for compute for computedamount
		$charges['misc1'] = $data['misc1'];
		$charges['misc2'] = $data['misc2'];
		$charges['misc3'] = $data['misc3'];
		$charges['misc4'] = $data['misc4'];
		$charges['misc5'] = $data['misc5'];
		$charges['misc6'] = $data['misc6'];

		$rate['ratetype'] = 'R';
		$computedata =  $this->computeTotalCost($data['product'], $data['type'], $data['rateamt'], $rate['ratetype'], $data['classification'], $data['totalsize'], $issuerate, $charges, $data['datetext']);        
		 
        if ($data['rateamt'] > 800) {
            $baseamount = $data['rateamt'];     
        } else {                                
            $baseamount = $totalsize * $data['rateamt'];
        }
        
        $paytype = mysql_escape_string($this->input->post('paytype')); 
        if ($paytype == 6) {
        $totalcost = 0;    
        } else {
        $totalcost = $computedata['totalcost'];
        }        
  
		#$totalcost = $computedata['totalcost'];    
		$computedamount = $computedata['totalcost'];
		# End for compute for computedamount

		$agencycommision = $totalcost * $agencycommisionrate;  
		$netvatablesale = 0; $vatexempt = 0;  $vatzero = 0;  
		if ($vatcode == "1" || $vatcode == "2" || $vatcode == "3") {
		$netvatablesale = $totalcost - $agencycommision;   
		} else if ($vatcode == "4") {
		$vatexempt = $totalcost - $agencycommision;      
		} else if ($vatcode == "5") {
		$vatzero = $totalcost - $agencycommision;      
		}
		$vatamt = ($netvatablesale + $vatexempt + $vatzero) * $vatrate;
		$amountdue = ($netvatablesale + $vatexempt + $vatzero) + $vatamt;
		   
		$data['premiumamt'] = $computedata['surcharge'];
		$data['discountamt'] = $computedata['discount'];
		$data['baseamt'] = $baseamount;       
		$data['computedamt'] =  $computedamount; // vat gross amount                      
		$data['totalcost'] = $totalcost; // net vatable sales
		$data['agencycom'] = $agencycommision; // vat amount
		$data['nvs'] = $netvatablesale; // vat sales        
		$data['vatexempt'] = $vatexempt; // vat exempt  
		$data['vatzerorate'] = $vatzero; // vat zero        
		$data['vatamt'] = $vatamt; // vat   
		$data['amtdue'] = $amountdue; // amount due         

		$this->bookingissuemodel->updateDetailedBooking($data);

		$response['totalcost'] = number_format($totalcost, 2, ".", ",")." Total Cost";
		$response['agencycom'] = number_format($agencycommision, 2, ".", ",")." Agency Comm.";
		$response['vatamt'] = number_format($vatamt, 2, ".", ",")." VAT Amount";
		$response['amtdue'] = number_format($amountdue, 2, ".", ",");

		# Update issue detailed viewing

		$result['datelist'] = $this->bookingissuemodel->retrieveIssueData($data);	
  		$result['mykeyid'] = $data['mykeyid'];	
		$result['type'] = $data['type'];	
		$result['product'] = $data['product'];	
		$result['vatcode'] = $data['vatcode'];
		$result['duepercent'] = mysql_escape_string($this->input->post('duepercent')); 

	     $summarydata = $this->bookingissuemodel->getSummarydata($data['mykeyid']);	  	
			$response['total_computedamt'] = $summarydata['total_computedamt'];
			$response['total_totalcost'] = $summarydata['total_totalcost'];
			$response['total_agencycom'] = $summarydata['total_agencycom'];
			$response['total_nvs'] = $summarydata['total_nvs'];
			$response['total_vatamt'] = $summarydata['total_vatamt'];
			$response['total_vatexempt'] = $summarydata['total_vatexempt'];
			$response['total_vatzerorate'] = $summarydata['total_vatzerorate'];
			$response['total_amtdue'] = $summarydata['total_amtdue'];
		$response['issuedate_data'] = $this->load->view('bookings/issuedate_Append', $result, true);


		echo json_encode($response);
	}

	public function refreshCalendarIssuedate() {
		$data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));

		$response['datelist'] = $this->bookingissuemodel->retrieveIssueData($data);	
		echo json_encode($response);
	}

	public function validateFlow_Paginate() {
		$aonum = mysql_escape_string($this->input->post('aonum'));
		$date = mysql_escape_string($this->input->post('date'));

		$isflow = $this->bookingissuemodel->validateFlowandPaginate($aonum, $date);       
		$response['isflow'] = "n";
		$response['paginated'] = 0;    
		if (!empty($isflow)) {
			if ($isflow['is_flow'] == 2) {
			$response['isflow'] = "y";
			}  

			if ($isflow['ao_paginated_status'] == 1) {
			$response['paginated'] = 1;            
			}
		} 
		echo json_encode($response);
	}
	
	public function supercedissue_edit() {

		$issuedate = $this->input->post('issuedate');
		$mykeyid = $this->input->post('mykeyid');
		$type = $this->input->post('type');
		$data['mykeyid'] = $mykeyid;
		$data['type'] = $type;
		$data['duepercent'] = $this->input->post('duepercent');
		$data['vatcode'] = $this->input->post('vatcode');
		$data['product'] = $this->input->post('product');
        $data['aoptmid'] = $issuedate;
		$data['class'] = $this->classifications->listOfClassificationPerType($type);          

		$data['canBOOKINGOVERRIDE'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGOVERRIDE');
	
		$data['misccharges'] = $this->adtypecharges->listOfMiscChargesByType($type);	 		
		$data['data'] = $this->bookingissuemodel->getEditableSupercedIssueFields($mykeyid, $issuedate);
        
        #print_r2($data['data']); exit;
        $data['issuedate'] = $issuedate;      
		$response['supercedissuedate_detailed'] = $this->load->view('bookings/supercedissuedate_detailed', $data, true);

		echo json_encode($response);
	}

	public function updatedetailed_superced() {	
		$data['classification'] = mysql_escape_string($this->input->post('classification'));

		$data['width'] = mysql_escape_string($this->input->post('width'));
		$data['length'] = mysql_escape_string($this->input->post('length'));
		$data['vatcode'] = mysql_escape_string($this->input->post('vatcode'));
		$data['misc1'] = mysql_escape_string($this->input->post('misc1'));
		$data['misc2'] = mysql_escape_string($this->input->post('misc2'));
		$data['misc3'] = mysql_escape_string($this->input->post('misc3'));
		$data['misc4'] = mysql_escape_string($this->input->post('misc4'));
		$data['misc5'] = mysql_escape_string($this->input->post('misc5'));
		$data['misc6'] = mysql_escape_string($this->input->post('misc6'));
		$data['miscper1'] = mysql_escape_string($this->input->post('miscper1'));
		$data['miscper2'] = mysql_escape_string($this->input->post('miscper2'));
		$data['miscper3'] = mysql_escape_string($this->input->post('miscper3'));
		$data['miscper4'] = mysql_escape_string($this->input->post('miscper4'));
		$data['miscper5'] = mysql_escape_string($this->input->post('miscper5'));
		$data['miscper6'] = mysql_escape_string($this->input->post('miscper6'));
		$data['totalprem'] = mysql_escape_string($this->input->post('totalprem'));
		$data['totaldisc'] = mysql_escape_string($this->input->post('totaldisc'));
		
		$data['totalsize'] = mysql_escape_string($this->input->post('totalsize'));
				
        $data['billing'] = mysql_escape_string($this->input->post('billing'));    
        $data['billing_prodtitle'] = mysql_escape_string($this->input->post('d_prodtitle'));    
		$data['billing_remarks'] = mysql_escape_string($this->input->post('d_remarks'));	

		$data['rateamt'] = $this->input->post('rateamt');
		$data['vatcode'] = $this->input->post('vatcode');

		$data['datetext'] = mysql_escape_string($this->input->post('issuedate'));
		$data['type'] = mysql_escape_string($this->input->post('type'));
		$data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
		$data['product'] = mysql_escape_string($this->input->post('product'));

		# Important Variable 
		$vatrate = $this->bookingissuemodel->getVatRate($data['vatcode']);
		$vatcode = $data['vatcode'];
		$agencycommisionrate = mysql_escape_string($this->input->post('duepercent')); 		

		# Variables        
		$issuerate = $data['rateamt'];
		$vatrate = ($vatrate / 100);
		$totalsize = $data['totalsize'];
		$agencycommisionrate = ($agencycommisionrate / 100);

		# Start for compute for computedamount
		$charges['misc1'] = $data['misc1'];
		$charges['misc2'] = $data['misc2'];
		$charges['misc3'] = $data['misc3'];
		$charges['misc4'] = $data['misc4'];
		$charges['misc5'] = $data['misc5'];
		$charges['misc6'] = $data['misc6'];

		$rate['ratetype'] = 'R';
		$datedate = mysql_escape_string($this->input->post('datedate'));
		$computedata =  $this->computeTotalCost($data['product'], $data['type'], $data['rateamt'], $rate['ratetype'], $data['classification'], $data['totalsize'], $issuerate, $charges, $datedate);        
		//$baseamount = $totalsize * $data['rateamt']; 
        if ($data['rateamt'] > 800) {
            $baseamount = $data['rateamt'];     
        } else {                                
            $baseamount = $totalsize * $data['rateamt'];
        }
        
		$totalcost = $computedata['totalcost'];    
		$computedamount = $computedata['totalcost'];
		# End for compute for computedamount

		$agencycommision = $totalcost * $agencycommisionrate;  
		$netvatablesale = 0; $vatexempt = 0;  $vatzero = 0;  
		if ($vatcode == "1" || $vatcode == "2" || $vatcode == "3") {
		$netvatablesale = $totalcost - $agencycommision;   
		} else if ($vatcode == "4") {
		$vatexempt = $totalcost - $agencycommision;      
		} else if ($vatcode == "5") {
		$vatzero = $totalcost - $agencycommision;      
		}
		$vatamt = ($netvatablesale + $vatexempt + $vatzero) * $vatrate;
		$amountdue = ($netvatablesale + $vatexempt + $vatzero) + $vatamt; 
		   
		$data['premiumamt'] = $computedata['surcharge'];
		$data['discountamt'] = $computedata['discount'];
		$data['baseamt'] = $baseamount;       
		$data['computedamt'] =  $computedamount; // vat gross amount                      
		$data['totalcost'] = $totalcost; // net vatable sales
		$data['agencycom'] = $agencycommision; // vat amount
		$data['nvs'] = $netvatablesale; // vat sales        
		$data['vatexempt'] = $vatexempt; // vat exempt  
		$data['vatzerorate'] = $vatzero; // vat zero        
		$data['vatamt'] = $vatamt; // vat   
		$data['amtdue'] = $amountdue; // amount due         

		$data['newinvoice'] = mysql_escape_string($this->input->post('newinvoice'));
        $data['newinvoicedate'] = mysql_escape_string($this->input->post('newinvoicedate'));
		$data['issuedate'] = mysql_escape_string($this->input->post('dissuedate'));

		$this->bookingissuemodel->updateDetailedBooking_Superced($data); 

		$response['totalcost'] = number_format($totalcost, 2, ".", ",")." Total Cost";
		$response['agencycom'] = number_format($agencycommision, 2, ".", ",")." Agency Comm.";
		$response['vatamt'] = number_format($vatamt, 2, ".", ",")." VAT Amount";
		$response['amtdue'] = number_format($amountdue, 2, ".", ",");

		# Update issue detailed viewing

		$result['datelist'] = $this->bookingissuemodel->retrieveIssueData($data);	
  		$result['mykeyid'] = $data['mykeyid'];	
		$result['type'] = $data['type'];	
		$result['product'] = $data['product'];	
		$result['vatcode'] = $data['vatcode'];
		$result['duepercent'] = mysql_escape_string($this->input->post('duepercent')); 

	     $summarydata = $this->bookingissuemodel->getSummarydata($data['mykeyid']);	  	
			$response['total_computedamt'] = $summarydata['total_computedamt'];
			$response['total_totalcost'] = $summarydata['total_totalcost'];
			$response['total_agencycom'] = $summarydata['total_agencycom'];
			$response['total_nvs'] = $summarydata['total_nvs'];
			$response['total_vatamt'] = $summarydata['total_vatamt'];
			$response['total_vatexempt'] = $summarydata['total_vatexempt'];
			$response['total_vatzerorate'] = $summarydata['total_vatzerorate'];
			$response['total_amtdue'] = $summarydata['total_amtdue'];
		$response['supercedissuedate_data'] = $this->load->view('bookings/superced_append', $result, true);

		echo json_encode($response);
	}
    
    public function removesupercedissuedate() {
        
        $mykeyid = $this->input->post('mykeyid');
        $data['rateamt'] = $this->input->post('rateamt');
        $data['vatcode'] = $this->input->post('vatcode');

        $data['aoptmid'] = mysql_escape_string($this->input->post('aoptmid'));
        $data['datetext'] = mysql_escape_string($this->input->post('datedate'));
        $data['type'] = mysql_escape_string($this->input->post('type'));
        $data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['mykeyid'] = $mykeyid;
        
        # TODO
        /* Viewing update in tables and update billing information */

        $this->bookingissuemodel->removeSupercedRundate($data['datetext'], $data['mykeyid'], $data['aoptmid']);
        #$this->bookingissuemodel->selectedDateAlgo($data);         
        
        $result['datelist'] = $this->bookingissuemodel->retrieveIssueData($data);           
        
        //print_r2($result);
         $result['mykeyid'] = $data['mykeyid'];    
        $result['type'] = $data['type'];    
        $result['product'] = $data['product'];    
        $result['vatcode'] = $data['vatcode'];
        $result['duepercent'] = mysql_escape_string($this->input->post('duepercent'));     
        $summarydata = $this->bookingissuemodel->getSummarydata($mykeyid);          
        $response['total_computedamt'] = $summarydata['total_computedamt'];
        $response['total_totalcost'] = $summarydata['total_totalcost'];
        $response['total_agencycom'] = $summarydata['total_agencycom'];
        $response['total_nvs'] = $summarydata['total_nvs'];
        $response['total_vatamt'] = $summarydata['total_vatamt'];
        $response['total_vatexempt'] = $summarydata['total_vatexempt'];
        $response['total_vatzerorate'] = $summarydata['total_vatzerorate'];
        $response['total_amtdue'] = $summarydata['total_amtdue'];
        $response['issuedate_data'] = $this->load->view('bookings/superced_append', $result, true);

         echo json_encode($response);
    
    }
    
}
