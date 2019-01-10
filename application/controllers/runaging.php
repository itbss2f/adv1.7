<?php


class Runaging extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        #$this->sess = $this->authlib->validate(); 
        $this->load->model('model_runaging/model_runage');
        
    }  
    
    public function runAge($param = null) {
        /* Running of Aging to be inserted in customer master file */
        if ($param == 'iesadv3rtising') {
            $this->model_runage->processRun();
            sleep(5);
            echo "finish process run \n";  
            $this->runCustomerAge();
            sleep(5);
            echo "finish process run 2 \n";  
            $this->runCustomerStatus();
            sleep(5);
            echo "finish customer status \n";  
            $this->runCustomerUnbill();
            
            echo "finish process\n";
            
            sleep(5);
        }
        
    }  
    
    public function runCustomerAge() {
        
        $this->model_runage->processCustomerAge();  
    }
    
    public function runCustomerStatus() {
        
        $this->model_runage->processCustomerStatus();
    }
    
    public function runCustomerUnbill() {
        $this->model_runage->processCustomerUnbilledAmt();
    }
    
}

?>
