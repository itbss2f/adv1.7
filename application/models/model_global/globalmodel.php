<?php
    
class GlobalModel extends CI_Model {  
    
    public function checkMonthEndClosing($date) {
        #echo $date = '2015-02-31';
        #echo $stmt = "SELECT COUNT(*) AS stat FROM misglpf WHERE last_yr = YEAR('$date') AND last_mon = MONTH('$date') AND id = 1";  exit;
        $stmt = "SELECT COUNT(*) AS stat, last_yr,  IF (last_yr > YEAR('$date'), 'OVER', 'EQUAL') AS yrsign, last_mon FROM misglpf WHERE id = 1";       
        $result = $this->db->query($stmt)->row_array();
        #print_r2($result);         exit;
        if ($result['yrsign'] == 'OVER') {
            #echo "over 1";
            return 1;
        } else if ($result['yrsign'] == 'EQUAL') {
            #echo "equal";
            #echo date('m', strtotime($date));
            #echo $result['last_mon'] ;
            
            if (date('Y', strtotime($date)) > $result['last_yr']) {
                //echo "0";     
                return 0;       
            } else if ($result['last_mon'] >= date('m', strtotime($date))) {
                //echo "1";
                return 1;
            } else {
                //echo "0";
                return 0;
            }
            
        }

        //exit;
        return $result['stat'];
    } 
    
    public function getMonthEnd() {
        $stmt = "SELECT last_yr, last_mon, DAY(LAST_DAY(CONCAT(last_yr,'-',last_mon,'-01'))) AS last_d, DATE_FORMAT(CONCAT('1990-',last_mon,'-00'), '%M') AS lastmon, (CONCAT(last_yr,'-',last_mon,'-31')) AS lastmonthend  FROM misglpf WHERE id = 1";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function CloseMonthEnd($endyear, $endmonth) {
        $data['last_yr'] = $endyear;
        $data['last_mon'] = $endmonth;
        
        $this->db->where('id', 1);
        $this->db->update('misglpf', $data);
        
        $stmt = "SELECT last_yr, DATE_FORMAT(CONCAT('1990-',last_mon,'-00'), '%M') AS lastmon FROM misglpf WHERE id = 1";
        $result = $this->db->query($stmt)->row_array();
        
        $data2['user_id'] = $this->session->userdata('authsess')->sess_id;     
        $data2['activity'] = 'Month End Closing';
        $data2['remarks'] = 'Month Closed for '.$endyear.' '.$endmonth;  
        
        $this->db->insert('advprod_db02logs.activitylogs', $data2);  
        
        return $result;
    }
    
    public function refixed_date($date) {
        return  date ("Y-m-15" , strtotime ( $date ));
    }
    
    public function cal_days($dateasof, $agedate) {
         $now = strtotime($dateasof);         // or your date as well    2013-03-22
         $your_date = strtotime($agedate);    // 2013-03-21
         $datediff = $now - $your_date;
         $x = floor($datediff/(60*60*24));
         
         return intval($x);
    }


	public function getUserData($userid) {
		$stmt = "select b.branch_bnacc, c.baf_bank, c.baf_bnch, b.id 
				from users as a
				inner join misbranch as b on b.branch_code = a.branch
				inner join misbaf as c on b.branch_bnacc  = c.id
				where a.id = '$userid'";
		
		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function getUserAccountName($userid) {
		$stmt = "select username from users where id='$userid'";

		$result = $this->db->query($stmt)->row_array();

		return $result['username'];
	}  
	
	public function moduleFunction($module, $function) {
		
		$userid = $this->session->userdata('authsess')->sess_id;
		
		$stmt = "SELECT mf.module_id, mf.function_id
				FROM user_module_functions AS mf
				INNER JOIN modules AS m ON m.id = mf.module_id
				INNER JOIN functions AS f on f.id = mf.function_id
				AND m.is_deleted = 0
				AND f.is_deleted = 0
				AND mf.user_id = '$userid'
				AND m.segment_path = '$module'
				AND f.name = '$function'";
		/*echo "<pre>";
        echo $stmt; exit; */
		$result = $this->db->query($stmt)->row_array();
                
		return $result ? TRUE : FALSE;
	}
    
    public function parameter($companycode) {
        $stmt = "SELECT * FROM misglpf WHERE com_code = '$companycode'";   
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function myMenu() {
    	
    	$user_id =  $this->session->userdata('authsess')->sess_id;
    	$stmt = "SELECT main_modules.name AS main_module_name, modules.name AS module_name, modules.segment_path, main_modules.icon
    	                FROM main_modules AS main_modules
    	                INNER JOIN modules AS modules ON modules.main_module_id =  main_modules.id
    	                INNER JOIN user_module_functions AS user_module_functions ON user_module_functions.module_id =  modules.id
    	                WHERE user_module_functions.user_id = '$user_id'
    	                GROUP BY modules.id 
    	                ORDER BY main_modules.order, modules.name ASC";
    	$result = $this->db->query($stmt)->result_array();    	
    	
    	$menulist = null;
    	
    	foreach($result as $row) {
    		$menulist[$row['main_module_name']][] = array('module_name' => $row['module_name'], 'segment_path' => $row['segment_path']);
    	}
    	
    	return $menulist;
    }
    
    public function moduleList() {
        $user_id =  $this->session->userdata('authsess')->sess_id;
        $stmt = "SELECT main_modules.name AS main_module_name, modules.name AS module_name, modules.segment_path, main_modules.icon
                FROM main_modules AS main_modules
                INNER JOIN modules AS modules ON modules.main_module_id =  main_modules.id
                INNER JOIN user_module_functions AS user_module_functions ON user_module_functions.module_id =  modules.id
                WHERE user_module_functions.user_id = '$user_id' AND modules.is_deleted = 0
                GROUP BY modules.id 
                ORDER BY main_modules.order, modules.name ASC";        
                
    
        $result = $this->db->query($stmt)->result_array();
        
        $module = null;
        $group = array();

        foreach ($result as $row) {                    
            $group[$row['main_module_name']][] = $row;                        
        }
        #var_dump($group);
        foreach ($group as $x => $rowgroup) { 
        //print_r2($group);              
            $module .= "<li class='openable'><a href='#'><span class='".$rowgroup[0]['icon']."'></span><span class='text'>".$x."</span></a>";    
            foreach ($rowgroup as $row) {
                $module .= "<ul>";
                if ($row['segment_path'] == 'displaydummy/dummy' || $row['segment_path'] == 'classdummy/dummy') {
                    //<a href="" onclick="window.open('help.html','', 'width=400, height=250, location=no, menubar=no, status=no,toolbar=no, scrollbars=no, resizable=no');
                    $atts = array(
                                  'width'      => '1350',
                                  'height'     => '640',                
                                  'scrollbars' => 'no',
                                  'status'     => 'no',
                                  'resizable'  => 'no',
                                  'screenx'    => '0',
                                  'screeny'    => '0'
                             );

                    #$module .= "<li class='nav_li_sub'><a href='".site_url($row['segment_path'])."'>".$row['module_name']."</a></li>";                                 
                    $module .= "<li>".anchor_popup($row['segment_path'], "<span class='icon-picture'></span><span class='text'>".$row['module_name']."</span>", $atts)."</li>";                                                    
                } else {
                    $module .= "<li><a href='".site_url($row['segment_path'])."'><span class='icon-picture'></span><span class='text'>".$row['module_name']."</span></a></li>";                                 
                }
                $module .= "</ul>";  
            }                                               
            $module .= "</li>";                
        }        
        return $module;
        
    }

    /*public function moduleList() {
        $user_id =  $this->session->userdata('authsess')->sess_id;
        $stmt = "SELECT main_modules.name AS main_module_name, modules.name AS module_name, modules.segment_path
                FROM main_modules AS main_modules
                INNER JOIN modules AS modules ON modules.main_module_id =  main_modules.id
                INNER JOIN user_module_functions AS user_module_functions ON user_module_functions.module_id =  modules.id
                WHERE user_module_functions.user_id = '$user_id' AND modules.is_deleted = 0
                GROUP BY modules.id 
                ORDER BY main_modules.order, modules.name ASC";                
        $result = $this->db->query($stmt)->result_array();
        
        $module = null;
        $group = array();

        foreach ($result as $row) {                    
            $group[$row['main_module_name']][] = $row;                        
        }
        #var_dump($group);
        foreach ($group as $x => $rowgroup) {    
            $module .= "<ul class='nav_ul'>";      
            $module .= "<li class='nav_li_main'>".$x."</li>";    
            foreach ($rowgroup as $row) {
                if ($row['segment_path'] == 'displaydummy/dummy') {
                    //<a href="" onclick="window.open('help.html','', 'width=400, height=250, location=no, menubar=no, status=no,toolbar=no, scrollbars=no, resizable=no');
                    $atts = array(
                                  'width'      => '1350',
                                  'height'     => '640',                
                                  'scrollbars' => 'no',
                                  'status'     => 'no',
                                  'resizable'  => 'no',
                                  'screenx'    => '0',
                                  'screeny'    => '0'
                             );

                    #$module .= "<li class='nav_li_sub'><a href='".site_url($row['segment_path'])."'>".$row['module_name']."</a></li>";                                 
                    $module .= "<li class='nav_li_sub'>".anchor_popup($row['segment_path'], $row['module_name'], $atts)."</li>";                                 
                } else {
                    $module .= "<li class='nav_li_sub'><a href='".site_url($row['segment_path'])."'>".$row['module_name']."</a></li>";                                 
                }
            }                                               
            $module .= "</ul>";                
        }
        $module .= "<div class='clear'></div>";             
        return $module;
        
    }*/
}
