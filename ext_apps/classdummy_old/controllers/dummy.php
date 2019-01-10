<?php
class Dummy extends CI_CONTROLLER 
{    
    public function __construct()
    {
        parent::__construct();

        $this->sess = $this->authlib->validate();   
   
        $this->load->model('Dummies');
        $this->load->model('model_product/products');          
    }
    

    public function index()
    {   
		$data['multipage'] = $this->Dummies->listOfPages();      
		$data['multisect'] = $this->Dummies->listOfSection();    
        $data['multicolor'] = $this->Dummies->getColor();           
		$data['classhead'] = $this->Dummies->class_header();       	
		$data['canDUMSAVE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1).'/'.$this->uri->segment(2), 'DUMSAVE');
		$data['canDUMPRINT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1).'/'.$this->uri->segment(2), 'DUMPRINT');
		$data['canDUMOPEN'] = $this->GlobalModel->moduleFunction($this->uri->segment(1).'/'.$this->uri->segment(2), 'DUMOPEN');
		$data['canDUMADDSEC'] = $this->GlobalModel->moduleFunction($this->uri->segment(1).'/'.$this->uri->segment(2), 'DUMADDSEC');
		$data['canDUMADDPAGE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1).'/'.$this->uri->segment(2), 'DUMADDPAGE');
		$data['canDUMCOLOR'] = $this->GlobalModel->moduleFunction($this->uri->segment(1).'/'.$this->uri->segment(2), 'DUMCOLOR');
		$data['canDUMDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1).'/'.$this->uri->segment(2), 'DUMDELETE');
		$data['canDUMUNFLOW'] = $this->GlobalModel->moduleFunction($this->uri->segment(1).'/'.$this->uri->segment(2), 'DUMUNFLOW');
		$data['canDUMTEMPLATE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1).'/'.$this->uri->segment(2), 'DUMTEMPLATE');
		$data['sect'] = $this->Dummies->listOfSection();
		$this->load->view('dummies/layout', $data);         
    }
    /* Open Module */
    public function opend()
    {
        $data['prod'] = $this->Dummies->listOfProduct();        
        $this->load->view('dummies/nav/open', $data);
    }
        public function ajxRtAds() /* Load Existing Dummy*/
        {        
            $hkey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);
            $product = mysql_escape_string($this->input->post('product'));
            $dateissue = mysql_escape_string($this->input->post('dateissue'));
            $viewing = mysql_escape_string($this->input->post('viewing'));
            $date = date("Y-m-d", strtotime($dateissue));
            $data['dummy'] = $this->Dummies->retrieveDummy($product, $date);                            
            if (!empty($data['dummy'])) {                  
                $this->Dummies->insertPageToTempTable($hkey, $product, $date); # To temp table pages
                $this->Dummies->insertBoxToTemp($hkey, $product, $date); # To temp table pages
                //$this->Dummies->reflow1to0($product, $date);       
                $data2['pages'] = $this->Dummies->getTempPage($hkey, $product, $date, $viewing); # Get temp table pages data
                $data2['key'] = $hkey;
                $data2['product'] = $product;
                $data2['date'] = $date;
                $data2['viewing'] = $viewing;
                $countpage = count($data2['pages']);
                
                $response['pagelayout'] = $this->load->view('dummies/asset/pagelayout', $data2, true);
                $response['valid'] = "true";
            } else {
                $this->Dummies->reflow1to0($product, $date);  
                $response['valid'] = "false";    
                $response['pagelayout'] = "";       
                $countpage = 0;         
            }
            $data['ads'] = $this->Dummies->retrieveAds($product, $date);  
            $issue_date = new DateTime($dateissue);            
            $response['countpage'] = $countpage;
            $response['convertDate'] = $issue_date->format('M d , Y');          
            $response['listad'] = $this->load->view('dummies/asset/listad', $data, true);
            $response['product'] = $product;
            $response['dateissue'] = $dateissue;
            $response['key'] = $hkey;
            echo json_encode($response);
        }
    
    /* End Open Module */
        
        
    public function sectiond()
    {
        $data['sect'] = $this->Dummies->listOfSection();
        $this->load->view('dummies/nav/section', $data);
    }
    
    public function colord()
    {
        $data['color'] = $this->Dummies->getColor();
        $this->load->view('dummies/nav/color', $data);
    }
    
    public function addpaged()
    {
        $data['page'] = $this->Dummies->listOfPages();
        $this->load->view('dummies/nav/addpage', $data);
    }
    
    
    /* Ajax Algo*/
    
    # Layout Box To Page
    public function ajxLayBxTPg()
    {
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        $data['key'] = mysql_escape_string($this->input->post('key'));            
        $data['viewing'] = mysql_escape_string($this->input->post('viewing'));
        $page = explode("x",mysql_escape_string($this->input->post('page')));        
        if (!empty($page)) { $data['page'] = $page[1]; } else { $data['page'] = ""; }
        $data['box'] = mysql_escape_string($this->input->post('box'));
        $data['xpos'] = mysql_escape_string($this->input->post('xpos'));
        $data['ypos'] = mysql_escape_string($this->input->post('ypos'));
        $data['component_type'] = "advertising";
        
            /* Modulus */    
            $xmod = $data['xpos'] / 39;                
            $xpos = $xmod * 35;
            $ymod = $data['ypos'] / 4;                
            $ypos = $ymod * 5;
            
        $data['xpos'] = $xpos;
        $data['ypos'] = $ypos;
        
        $response['todo'] = $this->Dummies->layoutBoxToTempTable($data);
        $this->Dummies->updateAOPTMIsLayout($data); # update aoptm is flow to 1 meaning the ads is already been flow
        #$this->Dummies->updateIsLayout($data);
            
        $data['ads'] = $this->Dummies->retrieveAds($data['product'], $data['date']);
        $response['listad'] = $this->load->view('dummies/asset/listad', $data, true);
        $response['box'] = $this->Dummies->getBox($data);        
        echo json_encode($response);
    }
    
    # Update Position of the box
    
    public function ajxUpdatePost()
    {
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        $data['key'] = mysql_escape_string($this->input->post('key'));    
        $data['viewing'] = mysql_escape_string($this->input->post('viewing'));    
        $page = explode("x",mysql_escape_string($this->input->post('page')));        
        if (!empty($page)) { $data['page'] = $page[1]; } else { $data['page'] = ""; }
        $data['box'] = mysql_escape_string($this->input->post('box'));
                    
        $xpos = mysql_escape_string($this->input->post('xpos'));
        $ypos = mysql_escape_string($this->input->post('ypos'));
        
        $colgut = 0;
        $cm = 0;
        switch ($data['viewing'])
        {
            case 1:
                $colgut = 35;
                $cm = 5;
            break;
            case 2:
                $colgut = 29;
                $cm = 4;
            break;
            case 3:                
                $colgut = 23;
                $cm = 3;
            break;
            case 4:
                $colgut = 17;
                $cm = 2;
            break;
            case 5:
                $colgut = 8;
                $cm = 2;
            break;
            case 6:
                $colgut = 4;                
                $cm = .5;
            break;
            case 7:
                $colgut = 12;                
                $cm = 1.5;
            break;
        }
        
        $xmod = $xpos / $colgut;                
        $data['xpos'] = $xmod * 35;
        $ymod = $ypos / $cm;                
        $data['ypos'] = $ymod * 5;
        
        
        $response['todo'] = $this->Dummies->updateBoxPosition($data);
        echo json_encode($response);
    }
    
    # Layout Box to its corresponding page
    public function ajxRetMyOwnBox()
    {
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        $data['key'] = mysql_escape_string($this->input->post('key'));    
        $data['viewing'] = mysql_escape_string($this->input->post('viewing'));
        $page = explode("x",mysql_escape_string($this->input->post('page')));                
        if (!empty($page)) { $data['page'] = $page[1]; } else { $data['page'] = ""; }
        
        $response['box'] = $this->Dummies->getMyBox($data);
        
        echo json_encode($response);
    }
    
    # Refreshing ads
    public function ajxRefresh()
    {
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        $data['ads'] = $this->Dummies->retrieveAds($data['product'], $data['date']);
        $response['listad'] = $this->load->view('dummies/asset/listad', $data, true);        
        echo json_encode($response);
    }
    
    # Saving Dummy
    public function saveDummyData()
    {
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        $data['key'] = mysql_escape_string($this->input->post('key'));    
        
        $isThereChangeinBox = $this->Dummies->checkChangesBox($data);    
        $this->Dummies->deletePageinDLB($data);            
        if ($isThereChangeinBox['changes'] != 0) {
            $response['change'] = "T";                        
            # Saving of data from temp box to actual box table
            $this->Dummies->saveDataTempBoxToActual($data);            
        } else {
            $response['change'] = "F";
        }        
        
        $isThereChangeinPage = $this->Dummies->checkChangesPage($data);
        // Delete Page
        $this->Dummies->deletePageinDLP($data);
        if ($isThereChangeinPage['changes'] != 0) {
            # Saving of data from temp page to actual page table                       
            $this->Dummies->saveDataTempPageToActual($data);
        }
        
        $this->Dummies->adjustBoxesLayoutId($data);
        
        echo json_encode($response);
    }
    
        /*public function ajxRtAds() 
        {
            $hkey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);
            $product = mysql_escape_string($this->input->post('product'));
            $dateissue = mysql_escape_string($this->input->post('dateissue'));
            $viewing = mysql_escape_string($this->input->post('viewing'));
            $date = date("Y-m-d", strtotime($dateissue));
            $data['dummy'] = $this->Dummies->retrieveDummy($product, $date);                            
            if (!empty($data['dummy'])) {                
                $this->Dummies->insertPageToTempTable($hkey, $product, $date); # To temp table pages
                $this->Dummies->insertBoxToTemp($hkey, $product, $date); # To temp table pages
                $data2['pages'] = $this->Dummies->getTempPage($hkey, $product, $date, $viewing); # Get temp table pages data
                $data2['key'] = $hkey;
                $data2['product'] = $product;
                $data2['date'] = $date;
                $data2['viewing'] = $viewing;
                
                $response['pagelayout'] = $this->load->view('dummies/asset/pagelayout', $data2, true);
                $response['valid'] = "true";
            } else {
                $response['valid'] = "false";    
                $response['pagelayout'] = "";                
            }
            $data['ads'] = $this->Dummies->retrieveAds($product, $date);            
            $response['listad'] = $this->load->view('dummies/asset/listad', $data, true);
            $response['product'] = $product;
            $response['dateissue'] = $dateissue;
            $response['key'] = $hkey;
            echo json_encode($response);
        }*/
    
    
    # Validation of Page and Box Color
    
    public function ajxPgBxVal()
    {
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        $data['key'] = mysql_escape_string($this->input->post('key'));    
        $data['box'] = mysql_escape_string($this->input->post('box'));    
        $page = explode("x",mysql_escape_string($this->input->post('page')));                
        if (!empty($page)) { $data['page'] = $page[1]; } else { $data['page'] = ""; }
        $pagelvl = $this->Dummies->getPageLvl($data);
        $boxlvl = $this->Dummies->getBoxLvl($data);        
        if ($boxlvl > $pagelvl) {
            $response['isColorValid'] = false;
        } else {
            $response['isColorValid'] = true;
        }                
        echo json_encode($response);
    }
    
    public function ajxAllocAreaVal()
    {
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        $data['key'] = mysql_escape_string($this->input->post('key'));    
        $data['box'] = mysql_escape_string($this->input->post('box'));    
        $data['viewing'] = mysql_escape_string($this->input->post('viewing'));    
        $page = explode("x",mysql_escape_string($this->input->post('page')));                
        if (!empty($page)) { $data['page'] = $page[1]; } else { $data['page'] = ""; }
                        
        $xpos = mysql_escape_string($this->input->post('xpos'));
        $ypos = mysql_escape_string($this->input->post('ypos'));
        $data['width'] = mysql_escape_string($this->input->post('width'));    
        $data['height'] = mysql_escape_string($this->input->post('height'));
        
        $colgut = 0;        
        $cm = 0;
        switch ($data['viewing'])
        {
            case 1:
                $colgut = 35;                            
                $cm = 5;
            break;
            case 2:
                $colgut = 29;                        
                $cm = 4;
            break;
            case 3:                
                $colgut = 23;        
                $cm = 3;
            break;
            case 4:
                $colgut = 17;                
                $cm = 2;
            break;
            case 5:
                $colgut = 8;                
                $cm = 1;
            break;
            case 6:
                $colgut = 4;                
                $cm = .5;
            break;
            case 7:
                $colgut = 12;                
                $cm = 1.5;
            break;
        }
        
        $xmod = $xpos / $colgut;                
        $data['xpos'] = $xmod * 35;
        $ymod = $ypos / $cm;                
        $data['ypos'] = $ymod * 5;
                            
        $isAllocatedAreaValid = $this->Dummies->validAllocatedArea($data);        
        $response['allocValid'] = $isAllocatedAreaValid;
        echo json_encode($response);
    }
    
    /* return of xpos, ypos, boxwidth, boxlenght */
    public function ajaxViewingPercent()
    {    
        $hkey = mysql_escape_string($this->input->post('key'));
        $product = mysql_escape_string($this->input->post('product'));
        $dateissue = mysql_escape_string($this->input->post('dateissue'));
        $viewing = mysql_escape_string($this->input->post('viewing'));
        $date = date("Y-m-d", strtotime($dateissue));                
        $data2['pages'] = $this->Dummies->getTempPage($hkey, $product, $date, $viewing); # Get temp table pages data
        $data2['key'] = $hkey;
        $data2['product'] = $product;
        $data2['date'] = $date;
        $data2['viewing'] = $viewing;
                
        $response['pagelayout'] = $this->load->view('dummies/asset/pagelayout', $data2, true);
        $response['valid'] = "true";
        echo json_encode($response);
    }
    
    /* adding new page */
    
    public function ajxAddPage()
    {
        $data['key'] = mysql_escape_string($this->input->post('key'));
        $data['bookname'] = mysql_escape_string($this->input->post('bookname'));
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        $data['numberofpage'] = mysql_escape_string($this->input->post('numberofpage'));
        $data['classtype'] = "D";
        
        
        $data['viewing'] = mysql_escape_string($this->input->post('viewing'));
        $date = date("Y-m-d", strtotime($data['date']));    
        $data['date'] = $date;
        $data['color'] = mysql_escape_string($this->input->post('color'));        
        $data['class_code'] = mysql_escape_string($this->input->post('class_code'));
        $this->Dummies->addNewPage($data);                   
        $data2['pages'] = $this->Dummies->getTempPage($data['key'], $data['product'], $date, $data['viewing']); # Get temp table pages data
        $data2['key'] = $data['key'];
        $data2['product'] = $data['product'];
        $data2['date'] = $date;
        $data2['viewing'] = $data['viewing'];
        $countpage = count($data2['pages']);
        $response['totalpages'] =  $countpage;           
        $response['pagelayout'] = $this->load->view('dummies/asset/pagelayout', $data2, true);
        $response['valid'] = "true";
           
        echo json_encode($response);
    }
    
    /* setting of color */
    
    public function ajxtSetValue()
    {
        $data['key'] = mysql_escape_string($this->input->post('key'));
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        #$data['pageID'] = mysql_escape_string($this->input->post('pageID'));
        $page = explode("x",mysql_escape_string($this->input->post('pageID')));                
        if (!empty($page)) { $data['page'] = $page[1]; } else { $data['page'] = ""; }
        $data['selection'] = mysql_escape_string($this->input->post('selection'));
        
        $validateColor = $this->Dummies->validateColor($data['selection']);        
        $response['validcolor'] = false;
        $response['validcolorrank'] = true;
        if (!empty($validateColor)) {                    
            $color_rank = $validateColor['color_rank'];
            $validatePageToChangeColor = $this->Dummies->validatePageToChangeColor($data, $color_rank);
            if (empty($validatePageToChangeColor)) {
                $data['color_code'] = $validateColor['id'];
                $data['color_html'] = $validateColor['color_html'];
                $this->Dummies->setPageColor($data);
                $response['validcolor'] = true;
                $response['color_html'] = $validateColor['color_html'];
                $response['validcolorrank'] = true;
            } else {
                $response['validcolorrank'] = false;
            } 
        }
        
        if ($data['selection'] == "NoCol"){        
            $color_rank = "0";
            $validatePageToChangeColor = $this->Dummies->validatePageToChangeColor($data, $color_rank);
            if (empty($validatePageToChangeColor)) {
                $data['color_code'] = "0";
                $data['color_html'] = null;
                $this->Dummies->setPageColor($data);
                $response['validcolor'] = true;
                $response['color_html'] = "EDEDED";
            } else {
                $response['validcolor'] = false;
            }
        }
        
        $validateSection = $this->Dummies->validateSection($data['selection']);
        $response['validasection'] = false;
        if (!empty($validateSection)) {
            $data['section'] = $validateSection['class_code'];
            $response['pagecolor'] = $validateSection['class_htmlcolor'];
            $this->Dummies->setPageSection($data);            
            $response['validasection'] = true;
            $response['section'] = $validateSection['class_code'];
        }
        if ($data['selection'] == "NoSect") {
            $data['section'] = "";         
            $response['pagecolor'] = "#FFFFFF";
            $this->Dummies->setPageSection($data);
            $response['validasection'] = true;
            $response['section'] = "";
        }
        
        if ($data['selection'] == "Delete") {
            // Checking if the page has ads
            $chck = $this->Dummies->checkPageHasAds($data);    
            if ($chck == "0") {
                // Delete Page
                $this->Dummies->deletePage($data);
                $response['validdelete'] = false;
            } else {                
                $response['validdelete'] = true;
            }
        }
        
        echo json_encode($response);
    }
    
    /* Unflowing box ads*/
    
    public function unflow()
    {
        $data['key'] = mysql_escape_string($this->input->post('key'));
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        $data['boxid'] = mysql_escape_string($this->input->post('boxid'));
        
        $this->Dummies->unflowBoxAds($data);
        
        $data['ads'] = $this->Dummies->retrieveAds($data['product'], $data['date']);
        $response['listad'] = $this->load->view('dummies/asset/listad', $data, true);
        
        echo json_encode($response);
    }
    
    /* Setting Folio Number */
    
    public function openFolio() {
        $data['key'] = mysql_escape_string($this->input->post('key'));
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));        
        $data['pageID'] = mysql_escape_string($this->input->post('pageID'));
        $data['viewing'] = mysql_escape_string($this->input->post('viewing'));
        
        $page = explode("x",mysql_escape_string($this->input->post('pageID')));                
        if (!empty($page)) { $data['page'] = $page[1]; } else { $data['page'] = ""; }      
    
        $data['pageData'] = $this->Dummies->getPageData($data);
        
        $response['folio'] = $this->load->view('dummies/asset/foliopage', $data, true);
        
        echo json_encode($response);
    }    
    
    public function ajxSetFolio()
    {
        $data['checkfolio'] = mysql_escape_string($this->input->post('checkfolio'));
        $data['folionumber'] = mysql_escape_string($this->input->post('folionumber'));
        $data['key'] = mysql_escape_string($this->input->post('key'));
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));        
        $data['viewing'] = mysql_escape_string($this->input->post('viewing'));                
        $page = explode("x",mysql_escape_string($this->input->post('pageID')));                
        if (!empty($page)) { $data['page'] = $page[1]; } else { $data['page'] = ""; }          
        $this->Dummies->setFolioNumber($data);        
                
        $date = date("Y-m-d", strtotime($data['date']));
        $data2['pages'] = $this->Dummies->getTempPage($data['key'] , $data['product'], $date,$data['viewing']); # Get temp table pages data
        $data2['key'] = $data['key'] ;
        $data2['product'] = $data['product'];
        $data2['date'] = $date;
        $data2['viewing'] = $data['viewing'];
                
        $response['pagelayout'] = $this->load->view('dummies/asset/pagelayout', $data2, true);
        
        echo json_encode($response);
    }    
    
    /* Merged Page */
    public function merged() 
    {
                
    }
    
    /* Blockout Page */
    public function blockout()
    {
        $this->load->view('dummies/nav/blockout');
    }
    
    /* Add Blockout Ads */
    public function addblockout() {
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        $data['key'] = mysql_escape_string($this->input->post('key'));            
        $data['viewing'] = mysql_escape_string($this->input->post('viewing'));
        $page = explode("x",mysql_escape_string($this->input->post('pageID')));        
        if (!empty($page)) { $data['page'] = $page[1]; } else { $data['page'] = ""; }
        
        $data['width'] = mysql_escape_string($this->input->post('reservewidth'));
        $data['height'] = mysql_escape_string($this->input->post('reserveheight'));
        $data['box_description'] = mysql_escape_string($this->input->post('reservedescription'));
        $data['component_type'] = "blockout";
        $data['layout_boxes_id'] = "20".substr(str_shuffle(str_repeat('0123456789',5)),0,8);
        $this->Dummies->insertBlockout($data);
        
        $date = date("Y-m-d", strtotime($data['date']));
        $data2['pages'] = $this->Dummies->getTempPage($data['key'] , $data['product'], $date,$data['viewing']); # Get temp table pages data
        $data2['key'] = $data['key'] ;
        $data2['product'] = $data['product'];
        $data2['date'] = $date;
        $data2['viewing'] = $data['viewing'];
                
        $response['pagelayout'] = $this->load->view('dummies/asset/pagelayout', $data2, true);
        
        echo json_encode($response);
    }    
    
    /* Filtering */
    
    public function ajxFiltering()
    {
        $data['section'] = mysql_escape_string($this->input->post('section'));
        $data['color'] = mysql_escape_string($this->input->post('color'));
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $date = mysql_escape_string($this->input->post('dateissue'));
        $data['date'] = date("Y-m-d", strtotime($date));    
        $data['name'] = mysql_escape_string($this->input->post('name'));
        $data['show'] = mysql_escape_string($this->input->post('show'));
        $data['code'] = mysql_escape_string($this->input->post('code'));
        $data['aonum'] = mysql_escape_string($this->input->post('aonum'));
        $data['width'] = mysql_escape_string($this->input->post('width'));
        $data['height'] = mysql_escape_string($this->input->post('height'));        
        $data['ads'] = $this->Dummies->filtering($data);
        $response['listad'] = $this->load->view('dummies/asset/listad', $data, true);        
        echo json_encode($response);        
    }
    
    /* Merge and Unmerge*/
    public function ajxUnmergePage()
    {
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        $data['key'] = mysql_escape_string($this->input->post('key'));            
        $data['viewing'] = mysql_escape_string($this->input->post('viewing'));
        $page = explode("x",mysql_escape_string($this->input->post('pageID')));        
        if (!empty($page)) { $data['page'] = $page[1]; } else { $data['page'] = ""; }
                
        $chckIfHaveAds = $this->Dummies->checkPageHasAds($data);        
        $response['error'] = false;        
        if (empty($chckIfHaveAds)) {
            $this->Dummies->unmergePage($data);                    
            $date = date("Y-m-d", strtotime($data['date']));
            $data2['pages'] = $this->Dummies->getTempPage($data['key'] , $data['product'], $date,$data['viewing']); # Get temp table pages data
            $data2['key'] = $data['key'] ;
            $data2['product'] = $data['product'];
            $data2['date'] = $date;
            $data2['viewing'] = $data['viewing'];
                    
            $response['pagelayout'] = $this->load->view('dummies/asset/pagelayout', $data2, true);
            $response['error'] = true;
        }                 
        echo json_encode($response);
    }
    
    public function ajxMergePage()
    {
        $data['product'] = mysql_escape_string($this->input->post('product'));
        $data['date'] = mysql_escape_string($this->input->post('date'));
        $data['key'] = mysql_escape_string($this->input->post('key'));            
        $data['viewing'] = mysql_escape_string($this->input->post('viewing'));
        $data['mergedID'] = mysql_escape_string($this->input->post('mergedID'));        
        $page = explode("x",mysql_escape_string($this->input->post('pageID')));                
        if (!empty($page)) { $data['page'] = $page[1]; } else { $data['page'] = ""; }                
        $chckIfHaveAds = $this->Dummies->checkPageHasAds($data);        
        $response['error'] = false;        
        if (empty($chckIfHaveAds)) {    
            $response['error'] = true;
            $mergeID = $this->Dummies->getThisMergeFolio($data);    
            $data['merge'] = $mergeID['layout_id'];
            $data['color'] = $mergeID['color_code'];
            $chckIfCanMerge = $this->Dummies->chckIfCanMerge($data);            
            $response['error2'] = false;        
            if (!empty($chckIfCanMerge)) {            
                $this->Dummies->mergePage($data);                    
                $date = date("Y-m-d", strtotime($data['date']));
                $data2['pages'] = $this->Dummies->getTempPage($data['key'] , $data['product'], $date,$data['viewing']); # Get temp table pages data
                $data2['key'] = $data['key'] ;
                $data2['product'] = $data['product'];
                $data2['date'] = $date;
                $data2['viewing'] = $data['viewing'];
                        
                $response['pagelayout'] = $this->load->view('dummies/asset/pagelayout', $data2, true);    
                $response['error2'] = true;                    
            }            
        }                 
        echo json_encode($response);
    }
    
    public function makeTemplate() {
    	$newdate = mysql_escape_string($this->input->post('datetext'));
    	$olddate = mysql_escape_string($this->input->post('date'));
    	$product = mysql_escape_string($this->input->post('product'));
        
        echo $x = $this->Dummies->createTemplatePage($newdate, $olddate, $product);
        if ($x) {
            $response['valid']= 'yes';
        } else {$response['valid']= 'no';}
        
        echo json_encode($response);
    }
    
    public function productremarksview() {
        
        $this->load->model('model_booking/bookings');       
        
        $boxid = abs($this->input->post('boxid'));
        
        $data['remarks'] = $this->bookings->getProductionRemarks($boxid);
        
        $response['productremarksview'] = $this->load->view('dummies/asset/productremark', $data, true);
        
        echo json_encode($response);
    }
    
    public function saveProductionRem(){
        
        $this->load->model('model_booking/bookings');       
        
        $boxid = abs($this->input->post('boxid'));       
        $data['ao_part_production'] = $this->input->post('productionrem');
        
        $this->bookings->saveProductionRem($boxid, $data);
    }
    
    public function do_lock() {    
        $boxid = abs($this->input->post('boxid'));   
        
        $this->Dummies->dolockbox($boxid);           
    }
    
    public function do_unlock() {    
        $boxid = abs($this->input->post('boxid'));   
        
        $this->Dummies->dounlockbox($boxid);           
    }
}
?>
