<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
--------------------------------------------------------------------------------
HHIMS - Hospital Health Information Management System
Copyright (c) 2011 Information and Communication Technology Agency of Sri Lanka
<http: www.hhims.org/>
----------------------------------------------------------------------------------
This program is free software: you can redistribute it and/or modify it under the
terms of the GNU Affero General Public License as published by the Free Software 
Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along 
with this program. If not, see <http://www.gnu.org/licenses/> 




---------------------------------------------------------------------------------- 
Date : June 2016
Author: Mr. Jayanath Liyanage   jayanathl@icta.lk

Programme Manager: Shriyananda Rathnayake
URL: http://www.govforge.icta.lk/gf/project/hhims/
----------------------------------------------------------------------------------
*/
class Clinic extends MX_Controller {
	 function __construct(){
		parent::__construct();
		$this->checkLogin();
		$this->load->library('session');
		$this->load->helper('text');
		if(isset($_GET["mid"])){
			$this->session->set_userdata('mid', $_GET["mid"]);
		}
	 }

	public function index()
	{
		return;
	}
	public function prescription_add_favour(){
		if ($_POST["clinic_prescription_id"]>0){
			$prisid = $_POST["clinic_prescription_id"];
			$favour_data = array(
						'name'  => $this->input->post("name"),
						'uid'  => $this->session->userdata("UID"),
						'Active' => 1
					);
			$this->load->model('mpersistent');
			$this->load->model('mclinic');		
			$res = $this->mpersistent->create("user_favour_drug", $favour_data);
			if ($res>0){
				$data["prescribe_items_list"] =$this->mclinic->get_prescribe_items($prisid);
				print_r($data["prescribe_items_list"] );
				$d_items = array();
				for ($i=0; $i < count($data["prescribe_items_list"]); ++$i){
					//if ($data["prescribe_items_list"][$i]["drug_list"] == "who_drug"){
						$item = array( 
							"user_favour_drug_id" => $res,
							"who_drug_id"=> $data["prescribe_items_list"][$i]["DRGID"],
                                                        "dosage"=> $data["prescribe_items_list"][$i]["Dosage"],
							"frequency"=> $data["prescribe_items_list"][$i]["Frequency"],
							"how_long"=> $data["prescribe_items_list"][$i]["HowLong"],
                                                        "dose_comment"=> $data["prescribe_items_list"][$i]["DoseComment"],
							"Active"=> 1,
						) ;
						$this->mpersistent->create("user_favour_drug_items", $item);	
					//}
				}
				echo 1;
				return;
			}
		}
		echo -1;
	}
        
        	public function prescribe_all($prsid,$pid,$clnid){
		if (!$prsid){
			echo 1;
			return;
		}
		if (!$pid){
			echo 2;
			return;
		}
		if (!$clnid){
			echo 3;
			return;
		}
		
		$this->load->model('mclinic');
		$this->load->model('mpersistent');
		$data["list"] = $this->mclinic->get_prescribe_items($prsid);
                if (!empty($data["list"])){
		 $pres_data = array(
                'Dept'   => "CLN",
                'clinic_patient_id'  => $clnid,
                'PID'    => $pid,
                'PrescribeDate'   => date("Y-m-d H:i:s"),
                'PrescribeBy' => $this->session->userdata("FullName"),
                'Status'      => "Draft",
                'Active'      => 1
                
            );
			$CPRSID = $this->mpersistent->create("clinic_prescription", $pres_data);
			if ( $CPRSID >0){
				$pres_data = array();
				for ($i=0; $i < count($data["list"]); ++$i){
					$pres_item = array(
							'clinic_prescription_id'        => $CPRSID ,
							'DRGID'  => $data["list"][$i]["DRGID"],
							'HowLong'    => $data["list"][$i]["HowLong"],
							'Frequency'    => $data["list"][$i]["Frequency"],
							'Dosage'    => $data["list"][$i]["Dosage"],
							'Status'           => "Pending",
							'Active'          => 1,
							'LastUpDate'      => date("Y-m-d H:i:s"),
							'LastUpDateUser'  => $this->session->userdata("FullName")
						);
					array_push($pres_data,$pres_item );
				}
				$PRS_ITEM_ID = $this->mpersistent->insert_batch("clinic_prescribe_items", $pres_data);
				echo $CPRSID;
				return;
			}
			echo 0;
			return;
		}
		echo 5;
		return;
	}
        
        	public function refer_clinic($cid=null){
        
        		$prefix=$this->config->item('clinic_nuber_prefix');
       $qry = "SELECT opd_visits.OPDID as OPDID, 
			CONCAT(patient.Full_Name_Registered,' ', patient.Personal_Used_Name) , 
			opd_visits.CreateDate , 
			opd_visits.Complaint , 
			visit_type.Name as VisitType,
                        clinic.name as Clinic	
			from opd_visits 
			LEFT JOIN `patient` ON patient.PID = opd_visits.PID 
			LEFT JOIN `visit_type` ON visit_type.VTYPID = opd_visits.VisitType
                        LEFT JOIN `clinic` ON clinic.clinic_id = opd_visits.referred_clinic_id	
			where (opd_visits.is_refered_clinic) =1 AND(opd_visits.referred_clinic_id='$cid')
			";
        $this->load->model('mpager',"visit_page");
        $visit_page = $this->visit_page;
        $visit_page->setSql($qry);
        $visit_page->setDivId("patient_list"); //important
        $visit_page->setDivClass('');
        $visit_page->setRowid('OPDID');
        $this->load->model('mclinic');
        $cname = $this->mclinic->get_clinic_info($cid);
        $caption = $cname['name']. "  Referred patient list";
        $visit_page->setCaption($caption);
        $visit_page->setShowPager(false);
        $visit_page->setColNames(array("","Patient", "Referred Date", "Complaint","OPD Type","Clinic"));
        $visit_page->setRowNum(25);
        $visit_page->setColOption("OPDID", array("search" => false, "hidden" => true));
	$visit_page->setColOption("CreateDate", array("search" => true, "hidden" => false, "width" => 50));
        //$visit_page->setColOption("patient_name", array("search" => true, "hidden" => false, "width" => 70));
        $visit_page->setColOption("VisitType", array("search" => true, "hidden" => false, "width" => 50));
        $visit_page->setColOption("Complaint", array("search" => true, "hidden" => false, "width" => 50));
        $visit_page->setColOption("Clinic", array("search" => true, "hidden" => false, "width" => 50));
        $visit_page->setColOption("CreateDate", $visit_page->getDateSelector());
           $visit_page->gridComplete_JS
            = "function() {
        $('#patient_list .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
			var clid =$(this)[0].lastElementChild.innerText;
            window.location='".site_url("/clinic/proceed")."/'+rowId;
        });
        }"; 
        $visit_page->setOrientation_EL("L");
		$data['pager'] = $visit_page->render(false);
		$this->load->vars($data);
        $this->load->view('search/clinic_refer_search');
        
       
	}
        
        	public function refers(){

        
        		$prefix=$this->config->item('clinic_nuber_prefix');
       $qry = "SELECT opd_visits.OPDID as OPDID, 
			CONCAT(patient.Full_Name_Registered,' ', patient.Personal_Used_Name) , 
			opd_visits.CreateDate , 
			opd_visits.Complaint , 
			visit_type.Name as VisitType,
                        clinic.name as Clinic	
			from opd_visits 
			LEFT JOIN `patient` ON patient.PID = opd_visits.PID 
			LEFT JOIN `visit_type` ON visit_type.VTYPID = opd_visits.VisitType
                        LEFT JOIN `clinic` ON clinic.clinic_id = opd_visits.referred_clinic_id	
			where opd_visits.is_refered_clinic =1
			";
        $this->load->model('mpager',"visit_page");
        $visit_page = $this->visit_page;
        $visit_page->setSql($qry);
        $visit_page->setDivId("patient_list"); //important
        $visit_page->setDivClass('');
        $visit_page->setRowid('OPDID');
        $visit_page->setCaption("Clinic Referred patient list");
        $visit_page->setShowPager(false);
        $visit_page->setColNames(array("","Patient", "Referred Date", "Complaint","OPD Type","Clinic"));
        $visit_page->setRowNum(25);
        $visit_page->setColOption("OPDID", array("search" => false, "hidden" => true));
	$visit_page->setColOption("CreateDate", array("search" => true, "hidden" => false, "width" => 50));
        //$visit_page->setColOption("patient_name", array("search" => true, "hidden" => false, "width" => 70));
        $visit_page->setColOption("VisitType", array("search" => true, "hidden" => false, "width" => 50));
        $visit_page->setColOption("Complaint", array("search" => true, "hidden" => false, "width" => 50));
        $visit_page->setColOption("Clinic", array("search" => true, "hidden" => false, "width" => 50));
        $visit_page->setColOption("CreateDate", $visit_page->getDateSelector());
           $visit_page->gridComplete_JS
            = "function() {
        $('#patient_list .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
			var clid =$(this)[0].lastElementChild.innerText;
            window.location='".site_url("/clinic/proceed")."/'+rowId;
        });
        }"; 
        $visit_page->setOrientation_EL("L");
		$data['pager'] = $visit_page->render(false);
		$this->load->vars($data);
        $this->load->view('search/clinic_refer_search');
        
       
	}
        
        public function proceed($opdid){
		$data = array();
		if(!isset($opdid) ||(!is_numeric($opdid) )){
			$data["error"] = "OPD visit not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		$this->load->model('mpersistent');
		$this->load->model('mopd');
		$this->load->model('mpatient');
                $this->load->model('mclinic');
		$this->load->helper('form');
        $this->load->library('form_validation');
        $data["opd_visits_info"] = $this->mopd->get_info($opdid);

		if ($data["opd_visits_info"]["PID"] >0){
			$data["patient_info"] = $this->mpersistent->open_id($data["opd_visits_info"]["PID"], "patient", "PID");
		}
		else{
			$data["error"] = "OPD Patient  not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		if (empty($data["patient_info"])){
			$data["error"] ="OPD Patient not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');
			return;
		}
		if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age',$data["patient_info"]["DateOfBirth"]);
        }
                $uid=$this->session->userdata('UID');
		$data["patient_info"]["HIN"] = Modules::run('patient/print_hin',$data["patient_info"]["HIN"]);
		$data["doctor_list"] = $this->mpersistent->table_select("
		SELECT UID,CONCAT(Title,FirstName,' ',OtherName ) as Doctor 
		FROM user WHERE (Active = TRUE) AND ((UserGroup = 'Doctor') OR (UserGroup = 'Programmer')) AND (UID='$uid')
		");		
		
		$data["clinic_list"] = $this->mpersistent->table_select("
		SELECT clinic_id,name 
		FROM clinic WHERE (Active = TRUE)
		 ORDER BY name 
		");

		$data["PID"] = $data["opd_visits_info"]["PID"];
        $data["Clinic_Name"]= $this->mclinic->get_clinic_info($data["opd_visits_info"]["referred_clinic_id"]);
		$data["OPDID"] = $opdid;
		
		$this->load->vars($data);
                $this->load->view('clinic_proceed');		
	}
        
        public function appointment(){
		$data = array();
		$this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model("mpersistent");
        $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
        $this->form_validation->set_rules("next_visit_date", "next_visit_date", "required");

        if ($this->form_validation->run() == FALSE) {
            $this->load->vars($data);
            echo Modules::run('clinic/proceed',$this->input->post("referred_visit_id") );
                    }       

		else {
                       
                    //$app_data = array();
					$app_data = array(
							'next_visit_date'    => $this->input->post("next_visit_date") ,
							'referred_visit_id'  => $this->input->post("referred_visit_id"),
							'PID'    => $this->input->post("PID"),
							'clinic_id'    => $this->input->post("clinic_id"),
							'status'           => "Refered",
							'Active'          => 1,
							'CreateDate'      => date("Y-m-d H:i:s"),
							'CreateUser'  => $this->session->userdata("FullName")
						);
				
                                $opdid=substr($this->input->post("referred_visit_id"), 3);
				$clinic_patient_id=$this->mpersistent->create("clinic_patient", $app_data);
                                $this->mpersistent->update("opd_visits", "OPDID",$opdid,array("is_refered_clinic"=>"0"));
                                
            $data["clinic_info"] = $this->mpersistent->open_id($this->input->post("clinic_id"), "clinic", "clinic_id");
            $data["app_date"] = $this->input->post("next_visit_date");
            $data["clinic_patient_id"]=$clinic_patient_id;
            if (!empty($data["clinic_info"])) {
                $data["patient_info"] = $this->mpersistent->open_id($this->input->post("PID"), "patient", "PID");
            }
            $this->load->vars($data);
            $this->load->view('clinic_appointment_bar_code');
							
			/*$this->session->set_flashdata(
				'msg', 'REC: ' . 'Appointment Saved'
			);
				header("Status: 200");
				header("Location: ".site_url('clinic/refers'));
				return;  */
			} 
	}
        
                
        	public function prescribe_all_favour($favid,$pid,$clnid){
		if (!$favid){
			echo 0;
			return;
		}
		if (!$pid){
			echo 0;
			return;
		}
		if (!$clnid){
			echo 0;
			return;
		}
		
		$this->load->model('mclinic');
		$this->load->model('mpersistent');
                $this->load->model('muser');
		$data["list"] = $this->muser->get_favour_drug_list($favid);
                
		if (!empty($data["list"])){
		 $pres_data = array(
                'Dept'   => "CLN",
                'clinic_patient_id'  => $clnid,
                'PID'    => $pid,
                'PrescribeDate'   => date("Y-m-d H:i:s"),
                'PrescribeBy' => $this->session->userdata("FullName"),
                'Status'      => "Draft",
                'Active'      => 1
                
            );
			$PRSID = $this->mpersistent->create("clinic_prescription", $pres_data);
			if ( $PRSID >0){
				$pres_data = array();
				for ($i=0; $i < count($data["list"]); ++$i){
					$pres_item = array(
							'clinic_prescription_id' => $PRSID ,
							'DRGID'  => $data["list"][$i]["wd_id"],
							'HowLong'    => $data["list"][$i]["how_long"],
							'Frequency'    => $data["list"][$i]["frequency"],
							'Dosage'    => $data["list"][$i]["dose"],
                                                        'DoseComment'    => $data["list"][$i]["dose_comment"],
							'Status'           => "Pending",
							'Active'          => 1,
                                                        'CreateDate'      =>  date("Y-m-d H:i:s"),
                                                        'CreateUser'  => $this->session->userdata("FullName"),
							'LastUpDate'      => date("Y-m-d H:i:s"),
							'LastUpDateUser'  => $this->session->userdata("FullName")
						);
					array_push($pres_data,$pres_item );
				}
				$PRS_ITEM_ID = $this->mpersistent->insert_batch("clinic_prescribe_items", $pres_data);
				echo $PRSID;
				return;
			}
			echo 0;
			return;
		}
		echo 0;
		return;
	}
        
        
        public function update_period($clinic_prs_id=null,$new_period=null){

                $np=urldecode($new_period);
            	$this->load->model('mpersistent');
		$st=$this->mpersistent->update("clinic_prescribe_items", "clinic_prescribe_item_id",$clinic_prs_id,array("HowLong"=>$np));
                echo $np ;
        }
        
        
              	public function get_previous_prescription_for_patient($vid=null,$pid=null){
               	if (!$pid){
			echo 0;
		}
		if (!$vid){
			echo 0;
		}
		$this->load->model('mclinic');
		$data["last_prescription"] = $this->mclinic->get_last_prescription_for_patient($vid,$pid);		//200439 //200439 ,
		//print_r($data["last_prescription"]);exit;
		if (isset($data["last_prescription"]["PRSID"])){
			$data["prescribe_items_list"] = $this->mclinic->get_drug_item($data["last_prescription"]["PRSID"]);
			//print_r($data["prescribe_items_list"]);exit;
			$this->load->model('mpersistent');
			for ($i=0;$i<count($data["prescribe_items_list"]); ++$i){
				//if ($data["prescribe_items_list"][$i]["drug_list"] == "who_drug"){
					/*$drug_info = $this->mpersistent->open_id($data["prescribe_items_list"][$i]["wd_id"], "who_drug", "wd_id");
					$data["prescribe_items_list"][$i]["name"] = isset($drug_info["name"])?$drug_info["name"]:'';
					$data["prescribe_items_list"][$i]["formulation"] = isset($drug_info["formulation"])?$drug_info["formulation"]:'';
					$data["prescribe_items_list"][$i]["default_num"] = isset($drug_info["default_num"])?$drug_info["default_num"]:'';
                                        $data["prescribe_items_list"][$i]["default_timing"] = isset($drug_info["default_timing"])?$drug_info["default_timing"]:'';
                                        $data["prescribe_items_list"][$i]["DPeriod"] = isset($drug_info["DPeriod"])?$drug_info["DPeriod"]:'';*/
                        
                                        $drug_info = $this->mpersistent->open_id($data["prescribe_items_list"][$i]["wd_id"], "who_drug", "wd_id");
					$data["prescribe_items_list"][$i]["name"] = isset($drug_info["name"])?$drug_info["name"]:'';
					$data["prescribe_items_list"][$i]["formulation"] = isset($drug_info["formulation"])?$drug_info["formulation"]:'';
					/*$data["prescribe_items_list"][$i]["default_num"] = $data["prescribe_items_list"][$i]["Dosage"];
                                        $data["prescribe_items_list"][$i]["default_timing"] = $data["prescribe_items_list"][$i]["Frequency"];
                                        $data["prescribe_items_list"][$i]["DPeriod"] = $data["prescribe_items_list"][$i]["HowLong"];
                                        $data["prescribe_items_list"][$i]["default_dcomment"] =$data["prescribe_items_list"][$i]["DoseComment"];*/
                            
                            //Frequency,HowLong,Dosage,DoseComment
				//}	
				/*else{ //for old version drugs comes from table "drugs"
					$drug_info = $this->mpersistent->open_id($data["prescribe_items_list"][$i]["wd_id"], "drugs", "DRGID");
					$data["prescribe_items_list"][$i]["name"] = isset($drug_info["Name"])?$drug_info["Name"]:'';
					$data["prescribe_items_list"][$i]["formulation"] =  isset($drug_info["Type"])?$drug_info["Type"]:'';
					$data["prescribe_items_list"][$i]["dose"] = ' ';
				}*/
			}
			$json = json_encode($data["prescribe_items_list"]);
			echo $json ;
		}
		else{
			echo 0;
		}
                
	}
        
	public function patient(){
		$prefix=$this->config->item('clinic_nuber_prefix');
       $qry = "SELECT clinic_patient.PID as PID, 			
	   patient.HIN as HIN, 
	   patient.other_id as other_id,
	   clinic_patient.clinic_patient_id,
	   patient.Full_Name_Registered as Full_Name_Registered , 
	   patient.Personal_Used_Name as Personal_Used_Name , 
			next_visit_date,
			clinic.name  ,
			clinic.clinic_id  
			from clinic_patient 
			
			LEFT JOIN `patient` ON patient.PID = clinic_patient.PID 
			LEFT JOIN `clinic` ON clinic.clinic_id = clinic_patient.clinic_id 
			where clinic_patient.status = 'Refered'
			";
        $this->load->model('mpager',"visit_page");
        $visit_page = $this->visit_page;
        $visit_page->setSql($qry);
        $visit_page->setDivId("patient_list"); //important
        $visit_page->setDivClass('');
        $visit_page->setRowid('PID');
        $visit_page->setCaption("Visit List");
        $visit_page->setShowPager(false);
        $visit_page->setColNames(array("","HIN","PMU ID","", "Patient","Initial", "Next visit date","Clinic ",""));
        $visit_page->setRowNum(25);
        $visit_page->setColOption("PID", array("search" => false, "hidden" => true));
		$visit_page->setColOption("clinic_id", array("search" => false, "hidden" => true));
		if($this->config->item('purpose') == "PC"){
			$visit_page->setColOption("other_id", array("search" => true, "hidden" => false, "height" => 100,"width"=>100));
		}
		else{
			$visit_page->setColOption("other_id", array("search" => true, "hidden" => true, "height" => 100,"width"=>100));
		}
        
        $visit_page->setColOption("HIN", array("search" => false, "hidden" => false, "width" => 75));
        $visit_page->setColOption("clinic_patient_id", array("search" => false, "hidden" => true));
        $visit_page->setColOption("Full_Name_Registered", array("search" => true, "hidden" => false));
        $visit_page->setColOption("Personal_Used_Name", array("search" => true, "hidden" => false));
        //$visit_page->setColOption("next_visit_date", array("search" => false, "hidden" => false, "width" => 75));
		 $visit_page->setColOption("next_visit_date", $visit_page->getDateSelector(date("Y-m-d")));
        //$page->setColOption("Collection_Status", array("stype" => "select", "searchoptions" => array("value" => ":All;Pending:Pending;Done:Done","defaultValue"=>"Pending")));

        $visit_page->setColOption("name", array("search" => false, "hidden" => false, "width" => 75));
        $visit_page->gridComplete_JS
            = "function() {
        $('#patient_list .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
			var clid =$(this)[0].lastElementChild.innerText;
            window.location='".site_url("/clinic/view")."/'+rowId+'?clinic='+clid;
        });
        }";
        $visit_page->setOrientation_EL("L");
		$data['pager'] = $visit_page->render(false);
		$this->load->vars($data);
        $this->load->view('search/clinic_search');	
       
	}
        
        	public function patient_search($cid){
		$prefix=$this->config->item('clinic_nuber_prefix');
       $qry = "SELECT clinic_patient.PID as PID, 			
	   patient.HIN as HIN, 
	   patient.other_id as other_id,
	   clinic_patient.clinic_patient_id,
	   patient.Full_Name_Registered as Full_Name_Registered , 
	   patient.Personal_Used_Name as Personal_Used_Name , 
			next_visit_date,
			clinic.name  ,
			clinic.clinic_id  
			from clinic_patient 
			
			LEFT JOIN `patient` ON patient.PID = clinic_patient.PID 
			LEFT JOIN `clinic` ON clinic.clinic_id = clinic_patient.clinic_id 
			where (clinic_patient.status = 'Refered') AND ((clinic_patient.clinic_id = '$cid'))
			";
        $this->load->model('mpager',"visit_page");
        $visit_page = $this->visit_page;
        $visit_page->setSql($qry);
        $visit_page->setDivId("patient_list"); //important
        $visit_page->setDivClass('');
        $visit_page->setRowid('PID');
        $this->load->model('mclinic');
        $cname = $this->mclinic->get_clinic_info($cid);
        $caption = $cname['name']. "  Visit List";
        $visit_page->setCaption($caption);
       // $visit_page->setCaption("Previous visits");
        $visit_page->setShowPager(false);
        $visit_page->setColNames(array("","HIN","PMU ID","", "Patient","Initial", "Next visit date","Clinic ",""));
        $visit_page->setRowNum(25);
        $visit_page->setColOption("PID", array("search" => false, "hidden" => true));
		$visit_page->setColOption("clinic_id", array("search" => false, "hidden" => true));
		if($this->config->item('purpose') == "PC"){
			$visit_page->setColOption("other_id", array("search" => true, "hidden" => false, "height" => 100,"width"=>100));
		}
		else{
			$visit_page->setColOption("other_id", array("search" => true, "hidden" => true, "height" => 100,"width"=>100));
		}
        
        $visit_page->setColOption("HIN", array("search" => false, "hidden" => false, "width" => 75));
        $visit_page->setColOption("clinic_patient_id", array("search" => false, "hidden" => true));
        $visit_page->setColOption("Full_Name_Registered", array("search" => true, "hidden" => false));
        $visit_page->setColOption("Personal_Used_Name", array("search" => true, "hidden" => false));
        //$visit_page->setColOption("next_visit_date", array("search" => false, "hidden" => false, "width" => 75));
		 $visit_page->setColOption("next_visit_date", $visit_page->getDateSelector(date("Y-m-d")));
        //$page->setColOption("Collection_Status", array("stype" => "select", "searchoptions" => array("value" => ":All;Pending:Pending;Done:Done","defaultValue"=>"Pending")));

        $visit_page->setColOption("name", array("search" => false, "hidden" => false, "width" => 75));
        $visit_page->gridComplete_JS
            = "function() {
        $('#patient_list .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
			var clid =$(this)[0].lastElementChild.innerText;
            window.location='".site_url("/clinic/view")."/'+rowId+'?clinic='+clid;
        });
        }";
        $visit_page->setOrientation_EL("L");
		$data['pager'] = $visit_page->render(false);
		$this->load->vars($data);
        $this->load->view('search/clinic_search');	
       
	}
        
	public function create($pid){
		$data = array();
		$this->load->vars($data);
        $this->load->view('opd_new');	
	}

	public function reffer_to_admission($opdid){
		$data = array();
		if(!isset($opdid) ||(!is_numeric($opdid) )){
			$data["error"] = "OPD visit not found";
			$this->load->vars($data);
			$this->load->view('opd_error');	
			return;
		}
		$this->load->model('mpersistent');
		$this->load->model('mopd');
		$this->load->model('mpatient');
		$this->load->helper('form');
        $this->load->library('form_validation');
        $data["opd_visits_info"] = $this->mopd->get_info($opdid);

		if ($data["opd_visits_info"]["PID"] >0){
			$data["patient_info"] = $this->mpersistent->open_id($data["opd_visits_info"]["PID"], "patient", "PID");
		}
		else{
			$data["error"] = "OPD Patient  not found";
			$this->load->vars($data);
			$this->load->view('opd_error');	
			return;
		}
		if (empty($data["patient_info"])){
			$data["error"] ="OPD Patient not found";
			$this->load->vars($data);
			$this->load->view('opd_error');
			return;
		}
		if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age',$data["patient_info"]["DateOfBirth"]);
        }
		$data["patient_info"]["HIN"] = Modules::run('patient/print_hin',$data["patient_info"]["HIN"]);
		$data["doctor_list"] = $this->mpersistent->table_select("
		SELECT UID,CONCAT(Title,FirstName,' ',OtherName ) as Doctor 
		FROM user WHERE (Active = TRUE) AND ((Post = 'OPD Doctor') OR (Post = 'Consultant'))
		");		
		
		$data["ward_list"] = $this->mpersistent->table_select("
		SELECT WID,Name  as Ward 
		FROM ward WHERE (Active = TRUE)
		 ORDER BY Name 
		");

		$data["PID"] = $data["opd_visits_info"]["PID"];
		$data["OPDID"] = $opdid;
		
		$this->load->vars($data);
        $this->load->view('opd_reffer_admission');		
	}
	
	public function new_visit($clinic_patient_id){
		$this->open($clinic_patient_id,null,"NEW");
	}
	
	public function close($clinic_patient_id,$pid){
		$data = array();
		if(!isset($clinic_patient_id) ||(!is_numeric($clinic_patient_id) )){
			$data["error"] = "Clinic visit not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		if(!isset($pid) ||(!is_numeric($pid) )){
			$data["error"] = "Patient not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		$this->load->model('mpersistent');
		$st=$this->mpersistent->update("clinic_patient", "clinic_patient_id",$clinic_patient_id,array("status"=>"Closed"));
		if ( $st >0){
					//echo Modules::run('opd/new_prescribe',$this->input->post("OPDID"));
					$this->session->set_flashdata('msg', 'Clinic closed!' );
					$new_page   =   base_url()."index.php/patient/clinic/".$pid;
					header("Status: 200");
					header("Location: ".$new_page);
				}
	}
	
	public function view($pid){
		$data = array();
		if(!isset($pid) ||(!is_numeric($pid) )){
			$data["error"] = "patient not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		$this->load->model('mpersistent');
		$this->load->model('mclinic');
		if (isset($pid)){
			$data["patient_info"] = $this->mpersistent->open_id($pid, "patient", "PID");
			$data["clinic_visit_list"] = $this->mclinic->get_clinic_visit_list($pid);
		}
		if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age',$data["patient_info"]["DateOfBirth"]);
        }
		$data["patient_info"]["HIN"] = Modules::run('patient/print_hin',$data["patient_info"]["HIN"]);
			$data["pid"] = $pid;
                        if(isset($_GET['CONTINUE'])){
                            $data["clinic_id"]=$_GET['CONTINUE']; 
                        }
                        else{
                          $data["clinic_id"]=$_GET['clinic'];   
                            
                        }
	
		$this->load->vars($data);
		$this->load->view('clinic_view1');
	}
	
	function close_visit(){
		$data = array();
		if(!isset($_POST) ||(!is_numeric($_POST["clinic_visits_id"]) )){
			$data["error"] = "Clinic visit not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		$clinic_visits_id =$_POST["clinic_visits_id"];
		$PID = isset($_POST['PID']) ? $_POST['PID'] : null;
		$next_visit_date = isset($_POST['next_visit_date']) ? $_POST['next_visit_date'] : null;
		$clinic_id = isset($_POST['clinic_id']) ? $_POST['clinic_id'] : null;
		
		$this->load->model('mpersistent');
		if ($PID && $next_visit_date && $clinic_id){
			$this->load->model('mclinic');
			$clinic_pattient = $this->mclinic->is_patient_assigned($PID,$clinic_id );
			if (!empty($clinic_pattient)){
				$this->mpersistent->update("clinic_patient", "clinic_patient_id",$clinic_pattient["clinic_patient_id"],array("next_visit_date"=>$next_visit_date));
			}
 else {
     
     					$app_data = array(
							'next_visit_date'    => $next_visit_date,
							'PID'    => $PID,
							'clinic_id'    => $clinic_id,
							'status'           => "Refered",
							'Active'          => 1,
							'CreateDate'      => date("Y-m-d H:i:s"),
							'CreateUser'  => $this->session->userdata("FullName")
						);

				$clinic_patient_id=$this->mpersistent->create("clinic_patient", $app_data);
     }
                        
        		}
		if ( $this->mpersistent->update("clinic_visits", "clinic_visits_id",$clinic_visits_id,array("status"=>"close")) ){
			/*$this->session->set_flashdata('msg', 'visit closed created!' );
			$new_page   =   base_url()."index.php/clinic/visit_view/".$clinic_visits_id;
			header("Status: 200");
			header("Location: ".$new_page);*/
                        
            $data["clinic_info"] = $this->mpersistent->open_id($clinic_id, "clinic", "clinic_id");
            $data["app_date"] = $next_visit_date;
            $data["clinic_patient_id"]=$clinic_pattient["clinic_patient_id"];
            if (!empty($data["clinic_info"])) {
                $data["patient_info"] = $this->mpersistent->open_id($PID, "patient", "PID");
            }
            $this->load->vars($data);
            $this->load->view('clinic_appointment_bar_code');
                        
		}
	}
	
	function close_visit_confirm($clinic_visit_id){
		$data = array();
		if(!isset($clinic_visit_id) ||(!is_numeric($clinic_visit_id) )){
			$data["error"] = "Clinic visit not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		$this->load->model('mpersistent');
        $data["clinic_visit_info"] = $this->mpersistent->open_id($clinic_visit_id,"clinic_visits", "clinic_visits_id");
		 $data["clinic_info"] = $this->mpersistent->open_id($data["clinic_visit_info"]["clinic"],"clinic", "clinic_id");
		if (empty($data["clinic_info"])){
			$data["error"] ="clinic_info  not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');
			return;
		}
		$pid = $data["clinic_visit_info"]["PID"];

		if (isset($pid)){
			$data["patient_info"] = $this->mpersistent->open_id($pid, "patient", "PID");
			//$data["patient_allergy_list"] = $this->mpatient->get_allergy_list($pid);
			//$data["patient_exams_list"] = $this->mpatient->get_exams_list($pid);
			//$data["patient_history_list"] = $this->mpatient->get_history_list($pid);
			//$data["patient_lab_order_list"] = $this->mpatient->get_lab_order_list($pid);
		}
		else{
			$data["error"] = "Clinic Patient  not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		if (empty($data["patient_info"])){
			$data["error"] ="Clinic Patient not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');
			return;
		}
		if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age',$data["patient_info"]["DateOfBirth"]);
        }
		$data["patient_info"]["HIN"] = Modules::run('patient/print_hin',$data["patient_info"]["HIN"]);
		
		$data["pid"] = $pid;
		$data["clinic_id"] = $data["clinic_visit_info"]["clinic"];
		//$data["clinic_patient_id"] = $clinic_patient_id;
		
		$this->load->vars($data);
	    $this->load->view('clinic_close');		
	}	
	
	
	
	public function visit_view($clinic_visit_id){
		$data = array();
		if(!isset($clinic_visit_id) ||(!is_numeric($clinic_visit_id) )){
			$data["error"] = "Clinic visit not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		$this->load->model('mpersistent');
		$this->load->model('mclinic');
		$this->load->model('mpatient');
		$this->load->model('mquestionnaire');
        $data["clinic_visit_info"] = $this->mpersistent->open_id($clinic_visit_id,"clinic_visits", "clinic_visits_id");
		if (empty($data["clinic_visit_info"])){
			$data["error"] ="clinic_visit_info  not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');
			return;
		}
		
        $data["clinic_info"] = $this->mpersistent->open_id($data["clinic_visit_info"]["clinic"],"clinic", "clinic_id");
		if (empty($data["clinic_info"])){
			$data["error"] ="clinic_info  not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');
			return;
		}
		$pid = $data["clinic_visit_info"]["PID"];

		if (isset($pid)){
			$data["patient_info"] = $this->mpersistent->open_id($pid, "patient", "PID");
			//$data["patient_allergy_list"] = $this->mpatient->get_allergy_list($pid);
			//$data["patient_exams_list"] = $this->mpatient->get_exams_list($pid);
			//$data["patient_history_list"] = $this->mpatient->get_history_list($pid);
			//$data["patient_lab_order_list"] = $this->mpatient->get_lab_order_list($pid);
		}
		else{
			$data["error"] = "Clinic Patient  not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		if (empty($data["patient_info"])){
			$data["error"] ="Clinic Patient not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');
			return;
		}
		if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age',$data["patient_info"]["DateOfBirth"]);
        }
		$data["patient_info"]["HIN"] = Modules::run('patient/print_hin',$data["patient_info"]["HIN"]);
		//$data["last_clinic_prsid"] = $this->mclinic->get_last_prescription_for_patient($data["clinic_visit_info"]["clinic_visits_id"],$pid); 
		
		$data["last_clinic_prsid"] = $this->mclinic->get_last_prescription_for_patient($data["clinic_visit_info"]["clinic"],$pid);		//200439 //200439 ,
		//print_r($data["last_clinic_prsid"]["PRSID"]);exit;
		//echo $data["last_clinic_prsid"]["PRSID"];
		if (isset($data["last_clinic_prsid"]["PRSID"])){
			$data["last_clinic_prescription"] = $this->mclinic->get_drug_item_clinic($data["last_clinic_prsid"]["PRSID"]);
		}
		
		//$data["last_clinic_prescription"] = $this->mclinic->get_prescribe_items($data["last_clinic_prsid"]["PRSID"]);
		$data["patient_prescription_list"] =$this->mclinic->get_prescription_list($data["clinic_visit_info"]["clinic_visits_id"]);
		$data["patient_procedure_list"] =$this->mclinic->get_procedure_list($data["clinic_visit_info"]["clinic_visits_id"]);
		
		//$data["clinic_questionnaire_list"] = $this->mquestionnaire->get_questionnaire_list("patient");
		$data["patient_questionnaire_list"] = $this->mquestionnaire->get_questionnaire_list("patient",$data["patient_info"]["Gender"]);
		//$data["patient_questionnaire_answer_list"] = $this->mquestionnaire->get_answer_list($data["clinic_visit_info"]["PID"],"patient");
		
		$data["clinic_questionnaire_list"] = $this->mquestionnaire->get_clinic_questionnaire_list($data["clinic_info"]["clinic_id"],$data["patient_info"]["Gender"]);
		$data["clinic_previous_record_list"] = $this->mquestionnaire->get_previous_record_list($data["clinic_visit_info"]["clinic_visits_id"],1,10);
		/*
		if (!empty($data["clinic_previous_record_list"])){
			for($i=0;$i<count($data["clinic_previous_record_list"]);++$i){
				$data["clinic_previous_record_list"][$i]["data"] = $this->mquestionnaire->get_clinic_patient_answer_list($data["clinic_previous_record_list"][$i]["qu_quest_answer_id"]);
				if (!empty($data["clinic_previous_record_list"][$i]["data"])){
					for($j=0;$j<count($data["clinic_previous_record_list"][$i]["data"]);++$j){
						if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "Select"){ //answer type select
							$ans = $this->mpersistent->open_id($data["clinic_previous_record_list"][$i]["data"][$j]["answer"],"qu_select", "qu_select_id");
							if (isset($ans["select_text"])){
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = $ans["select_text"];
								$data["clinic_previous_record_list"][$i]["data"][$j]["select_default"] = $ans["select_default"];
							}
							else {
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = '';
							}
						}
						if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "MultiSelect"){ //answer type multi-select
							$user_answeres = explode(",", $data["clinic_previous_record_list"][$i]["data"][$j]["answer"]);
							
							$output_answer = '';
							for ($ua=0; $ua < count($user_answeres); ++$ua){
								if ($user_answeres[$ua] >0){
									$ans = $this->mpersistent->open_id($user_answeres[$ua],"qu_select", "qu_select_id");
									$output_answer .=$ans["select_text"].', ';
								}
							}
							if (isset($output_answer)){
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] =$output_answer;
							}
							else {
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = '';
							}
						}
						
						if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "PAIN_DIAGRAM"){
							$data['pain_diagram_info'] = $this->mquestionnaire->get_diagram_info($data["clinic_previous_record_list"][$i]["data"][$j]["qu_question_id"]);
							if (!empty($data['pain_diagram_info'])){ 
								//$data['clinic_diagram_info'] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
								$data['diagram'.$data['clinic_previous_record_list'][$i]["data"][$j]['qu_question_id']] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
						
							}
						}
					}
				}
			}
		}*/
		/*if (!empty($data["patient_questionnaire_answer_list"])){
			for($i=0;$i<count($data["patient_questionnaire_answer_list"]);++$i){
				$data["patient_questionnaire_answer_list"][$i]["data"] = $this->mquestionnaire->get_clinic_patient_answer_list($data["patient_questionnaire_answer_list"][$i]["qu_quest_answer_id"]);
				if (!empty($data["patient_questionnaire_answer_list"][$i]["data"])){
					for($j=0;$j<count($data["patient_questionnaire_answer_list"][$i]["data"]);++$j){
						if ($data["patient_questionnaire_answer_list"][$i]["data"][$j]["question_type"] == "Select"){ //answer type select
							$ans = $this->mpersistent->open_id($data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"],"qu_select", "qu_select_id");
							if (isset($ans["select_text"])){
								$data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"] = $ans["select_text"];
							}
							else {
								$data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"] = '';
							}
						}
						if ($data["patient_questionnaire_answer_list"][$i]["data"][$j]["question_type"] == "MultiSelect"){ //answer type multi-select
							$user_answeres = explode(",", $data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"]);
							
							$output_answer = '';
							for ($ua=0; $ua < count($user_answeres); ++$ua){
								if ($user_answeres[$ua] >0){
									$ans = $this->mpersistent->open_id($user_answeres[$ua],"qu_select", "qu_select_id");
									$output_answer .=$ans["select_text"].', ';
								}
							}
							if (isset($output_answer)){
								$data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"] =$output_answer;
							}
							else {
								$data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"] = '';
							}
						}
						
						if ($data["patient_questionnaire_answer_list"][$i]["data"][$j]["question_type"] == "PAIN_DIAGRAM"){
							$data['pain_diagram_info'] = $this->mquestionnaire->get_diagram_info($data["patient_questionnaire_answer_list"][$i]["data"][$j]["qu_question_id"]);
							if (!empty($data['pain_diagram_info'])){ 
								//$data['clinic_diagram_info'] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
								$data['diagram'.$data['patient_questionnaire_answer_list'][$i]["data"][$j]['qu_question_id']] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
						
							}
						}
					}
				}
			}
		}*/
		$data["pid"] = $pid;
		$data["clinic_id"] = $data["clinic_visit_info"]["clinic"];
		//$data["clinic_patient_id"] = $clinic_patient_id;
		
		$this->load->vars($data);
	    $this->load->view('clinic_view');	
	}
	
	public function open($clinic_patient_id,$pid=null,$ops=null){
		$data = array();
		if(!isset($clinic_patient_id) ||(!is_numeric($clinic_patient_id) )){
			$data["error"] = "Clinic visit not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		$this->load->model('mpersistent');
		$this->load->model('mclinic');
		$this->load->model('mpatient');
		$this->load->model('mquestionnaire');
        $data["clinic_patient_info"] = $this->mpersistent->open_id($clinic_patient_id,"clinic_patient", "clinic_patient_id");
		if (empty($data["clinic_patient_info"])){
			$data["error"] ="clinic_patient_info  not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');
			return;
		}
		
        $data["clinic_info"] = $this->mpersistent->open_id($data["clinic_patient_info"]["clinic_id"],"clinic", "clinic_id");
		if (empty($data["clinic_info"])){
			$data["error"] ="clinic_info  not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');
			return;
		}
		if(!$pid){
			$pid = $data["clinic_patient_info"]["PID"];
		}
		if (isset($pid)){
			$data["patient_info"] = $this->mpersistent->open_id($pid, "patient", "PID");
			//$data["patient_allergy_list"] = $this->mpatient->get_allergy_list($pid);
			//$data["patient_exams_list"] = $this->mpatient->get_exams_list($pid);
			//$data["patient_history_list"] = $this->mpatient->get_history_list($pid);
			//$data["patient_lab_order_list"] = $this->mpatient->get_lab_order_list($pid);
		}
		else{
			$data["error"] = "Clinic Patient  not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		if (empty($data["patient_info"])){
			$data["error"] ="Clinic Patient not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');
			return;
		}
		if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age',$data["patient_info"]["DateOfBirth"]);
        }
		$data["patient_info"]["HIN"] = Modules::run('patient/print_hin',$data["patient_info"]["HIN"]);
		$data["clinic_questionnaire_list"] = null;
		//$data["clinic_questionnaire_list"] = $this->mquestionnaire->get_questionnaire_list("patient");
		$data["clinic_questionnaire_list"] = $this->mquestionnaire->get_clinic_questionnaire_list($data["clinic_info"]["clinic_id"]);
		$data["clinic_previous_record_list"] = $this->mquestionnaire->get_clinic_previous_record_list($clinic_patient_id,1,10);
		if (!empty($data["clinic_previous_record_list"])){
			for($i=0;$i<count($data["clinic_previous_record_list"]);++$i){
				$data["clinic_previous_record_list"][$i]["data"] = $this->mquestionnaire->get_clinic_patient_answer_list($data["clinic_previous_record_list"][$i]["qu_quest_answer_id"]);
				if (!empty($data["clinic_previous_record_list"][$i]["data"])){
					for($j=0;$j<count($data["clinic_previous_record_list"][$i]["data"]);++$j){
						if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "Select"){ //answer type select
							$ans = $this->mpersistent->open_id($data["clinic_previous_record_list"][$i]["data"][$j]["answer"],"qu_select", "qu_select_id");
							if (isset($ans["select_text"])){
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = $ans["select_text"];
							}
							else {
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = '';
							}
						}
						if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "MultiSelect"){ //answer type multi-select
							$user_answeres = explode(",", $data["clinic_previous_record_list"][$i]["data"][$j]["answer"]);
							
							$output_answer = '';
							for ($ua=0; $ua < count($user_answeres); ++$ua){
								if ($user_answeres[$ua] >0){
									$ans = $this->mpersistent->open_id($user_answeres[$ua],"qu_select", "qu_select_id");
									$output_answer .=$ans["select_text"].', ';
								}
							}
							if (isset($output_answer)){
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] =$output_answer;
							}
							else {
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = '';
							}
						}
						
						if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "PAIN_DIAGRAM"){
							$data['pain_diagram_info'] = $this->mquestionnaire->get_diagram_info($data["clinic_previous_record_list"][$i]["data"][$j]["qu_question_id"]);
							if (!empty($data['pain_diagram_info'])){ 
								//$data['clinic_diagram_info'] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
								$data['diagram'.$data['clinic_previous_record_list'][$i]["data"][$j]['qu_question_id']] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
						
							}
						}
					}
				}
			}
		}
		$data["pid"] = $pid;
		$data["clinic_id"] = $data["clinic_patient_info"]["clinic_id"];
		$data["clinic_patient_id"] = $clinic_patient_id;
		
		$this->load->vars($data);
		if ($ops == "NEW"){
			$this->load->view('clinic_new');	
		}
		else{
		    $this->load->view('clinic_view');	
		}	
	}
private function check_alergy_alert($wd_id,$pid){
	 $this->load->model("mpersistent");
	 $this->load->model("mpatient");
	 $drug_info = $this->mpersistent->open_id($wd_id,"who_drug","wd_id");
	 $alert_info = $this->mpatient->check_alergy_alert($drug_info["name"],$pid);
	 return $alert_info;
}
public function save_prescription(){
       
		$alergy_data = $this->check_alergy_alert($this->input->post("wd_id"),$this->input->post("PID"));
		
                if (!empty($alergy_data)){
			$data["error"] = "Patient has '" .$alergy_data[0]["Name"]. "' in the list of allergies.";
			$this->load->vars($data);
			if ($this->input->post("clinic_prescription_id")>0){
				$this->prescription($this->input->post("clinic_prescription_id"));	
			}
			else{
				$this->new_prescribe($this->input->post("clinic_patient_id"));	
			}
			return;
		}
        //echo $this->input->post("clinic_patient_id");
        //return false;
               
	$this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model("mpersistent");
        $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');

        $this->form_validation->set_rules("clinic_patient_id", "clinic_patient_id", "numeric|xss_clean");
        $this->form_validation->set_rules("PID", "PID", "numeric|xss_clean");
        $this->form_validation->set_rules("wd_id", "Age", "numeric|xss_clean");
		$data = array();
		//Array ( [clinic_prescription_id] => [CONTINUE] => clinic/open/27 [clinic_patient_id] => 27 [PID] => 187 [Frequency] => tds [Dose] => 2/3 [HowLong] => For 4 days [drug_stock_id] => 2 [choose_method] => by_name )
		//print_r($_POST);
		//exit;
        if ($this->form_validation->run() == FALSE) {
            $data["error"] = "Save not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
        } else {	
			if($this->input->post("clinic_prescription_id")>0){
				$this->add_durg_item();
				return;
			}
                      
			 $pres_data = array(
                'Dept'   => "CLN",
                'clinic_patient_id'  => $this->input->post("clinic_patient_id"),
                'PID'    => $this->input->post("PID"),
                'PrescribeDate'   => date("Y-m-d H:i:s"),
                'PrescribeBy' => $this->session->userdata("FullName"),
                'Status'      => "Draft",
                'Active'      => 1
            );
			$clinic_prescription_id = $this->mpersistent->create("clinic_prescription", $pres_data);
			if ( $clinic_prescription_id >0){
				$pres_item_data = array(
					'clinic_prescription_id'        => $clinic_prescription_id ,
					'DRGID'  => $this->input->post("wd_id"),
					'HowLong'    => $this->input->post("HowLong"),
                                        'DoseComment'    => $this->input->post("DoseComment"),
					'Frequency'    => $this->input->post("Frequency"),
					'Dosage'    => $this->input->post("Dose"),
					'Status'           => "Pending",
					'Active'                   => 1
				);
				$clinic_prescribe_item_id = $this->mpersistent->create("clinic_prescribe_items", $pres_item_data);
				if ( $clinic_prescribe_item_id >0){
					//echo Modules::run('opd/new_prescribe',$this->input->post("OPDID"));
					$this->session->set_flashdata('msg', 'Prescription created!' );
					$new_page   =   base_url()."index.php/clinic/prescription/".$clinic_prescription_id."?CONTINUE=".$this->input->post("CONTINUE")."";
					header("Status: 200");
					header("Location: ".$new_page);
				}
			}
			else{
				$data["error"] = "Save not found";
				$this->load->vars($data);
				$this->load->view('clinic_error');	
				return;
			}
		}		
	}
        
        public function prescription_item_delete($pres_item_id){
		if ($pres_item_id>0){
			$this->load->model("mpersistent");
			$data["pres"] = $this->mpersistent->open_id($pres_item_id, "clinic_prescribe_items", "clinic_prescribe_item_id");
			if ($data["pres"]["clinic_prescribe_item_id"]>0){
				if ($this->mpersistent->delete($pres_item_id, "clinic_prescribe_items", "clinic_prescribe_item_id")){
					$this->session->set_flashdata('msg', 'Drug deleted!' );
					echo 1;
				}
			}
			echo 0;
			
		}
		echo 0;
	}



	public function prescription($clinic_prescription_id){
		if(!isset($clinic_prescription_id) ||(!is_numeric($clinic_prescription_id) )){
			$data["error"] = "Prescription  not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		$this->load->model('mpersistent');
		$this->load->model('mopd');
		$this->load->model('mclinic');
		$this->load->model('mpatient');
		$this->load->helper('string');
                $data['clinic_prescription_id']=$clinic_prescription_id;
		$data["clinic_presciption_info"] = $this->mpersistent->open_id($clinic_prescription_id, "clinic_prescription", "clinic_prescription_id");
		$data["clinic_patient_info"] = $this->mpersistent->open_id($data["clinic_presciption_info"]["clinic_patient_id"] , "clinic_visits", "clinic_visits_id");
		$data["prescribe_items_list"] =$this->mclinic->get_prescribe_items($clinic_prescription_id);
		if(isset($data["prescribe_items_list"])){
			for ($i=0;$i<count($data["prescribe_items_list"]); ++$i){
				$drug_info = $this->mpersistent->open_id($data["prescribe_items_list"][$i]["DRGID"], "who_drug", "wd_id");
				$data["prescribe_items_list"][$i]["drug_name"] = isset($drug_info["name"])?$drug_info["name"]:'';
				$data["prescribe_items_list"][$i]["formulation"] = isset($drug_info["formulation"])?$drug_info["formulation"]:'';
				$data["prescribe_items_list"][$i]["dose"] = isset($drug_info["DStrength"])?$drug_info["DStrength"]:'';
			}
		}
		$data["my_favour"] = $this->mopd->get_favour_drug_count($this->session->userdata("UID"));
		$data["user_info"] = $this->mpersistent->open_id($this->session->userdata("UID"), "user", "UID");
		///if ($data["clinic_presciption_info"]["clinic_patient_id"]>0){
			//$data["clinic_presciption_info"] = $this->mopd->get_info($data["opd_presciption_info"]["OPDID"]);
		//}
          
		if ($data["clinic_presciption_info"]["PID"] >0){
			$data["patient_info"] = $this->mpersistent->open_id($data["clinic_presciption_info"]["PID"], "patient", "PID");
			$data["patient_allergy_list"] = $this->mpatient->get_allergy_list($data["clinic_presciption_info"]["PID"]);
		}
		else{
			$data["error"] = "OPD Patient  not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');	
			return;
		}
		if (empty($data["patient_info"])){
			$data["error"] ="OPD Patient not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');
			return;
		}
		if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age',$data["patient_info"]["DateOfBirth"]);
        }
        //$data["clinic_visits_info"] = $this->mopd->get_info($opdid);
		if(isset($data["clinic_patient_info"]["clinic"])){
			$data["clinic_info"] = $this->mclinic->get_clinic_info($data["clinic_patient_info"]["clinic"]);
			$data["stock_info"] = $this->mpersistent->open_id($data["clinic_info"]["drug_stock"],"drug_stock", "drug_stock_id");
		}
		$data["PID"] = $data["clinic_presciption_info"]["PID"];
		$data["clinic_patient_id"] = $data["clinic_presciption_info"]["clinic_patient_id"];
                $data["drug_dosage"] = $this->mopd->get_drug_dosage();
		$this->load->vars($data);
                $this->load->view('clinic_new_prescribe');			
	}
	
	public function add_durg_item(){
		//print_r($_POST);
		if ($_POST["clinic_prescription_id"]>0){
			$pres_item_data = array(
						'clinic_prescription_id'        => $_POST["clinic_prescription_id"] ,
						'DRGID'  => $this->input->post("wd_id"),
						'HowLong'    => $this->input->post("HowLong"),
                                                'DoseComment'    => $this->input->post("DoseComment"),
						'Frequency'    => $this->input->post("Frequency"),
						'Dosage'    => $this->input->post("Dose"),
						'Status'           => "Pending",
						'Active'                   => 1
					);
			$clinic_prescribe_item_id = $this->mpersistent->create("clinic_prescribe_items", $pres_item_data);
			if ( $clinic_prescribe_item_id >0){
				//echo Modules::run('opd/new_prescribe',$this->input->post("OPDID"));
				$this->session->set_flashdata('msg', 'Drug added!' );
				//($table=null,$key_field=null,$id=null,$data)
				
				if ($this->input->post("choose_method")){
					$this->mpersistent->update("user", "UID",$this->session->userdata("UID"),array("last_prescription_cmd"=>$this->input->post("choose_method")));
				}
				$this->session->set_userdata("choose_method",$this->input->post("choose_method"));
				$new_page   =   base_url()."index.php/clinic/prescription/".$_POST["clinic_prescription_id"]."?CONTINUE=".$this->input->post("CONTINUE")."";
				header("Status: 200");
				header("Location: ".$new_page);
			}
		}
	}


	public function prescription_send($prsid){
			$this->load->model("mpersistent");
			 $pres_data = array(
                'PrescribeDate'   => date("Y-m-d H:i:s"),
                'Status'      => "Pending",
                'Active'      => 1
            );
			//update($table=null,$key_field=null,$id=null,$data)
			if( $this->mpersistent->update("clinic_prescription","clinic_prescription_id",$prsid, $pres_data) > 0 ){
				$this->session->set_flashdata('msg', 'Prescription sent!' );
				echo 1;
			}
			else{
				echo 0;
			}
	}
	
public function new_prescribe($clnid){
		if(!isset($clnid) ||(!is_numeric($clnid) )){
			$data["error"] = "OPD visit not found";
			$this->load->vars($data);
			$this->load->view('opd_error');	
			return;
		}
		$this->load->model('mpersistent');
		$this->load->model('mclinic');
		$this->load->model('mopd');
		$this->load->model('mpatient');
		 $data["clinic_patient_info"] = $this->mpersistent->open_id($clnid,"clinic_visits", "clinic_visits_id");
		 $data["clinic_info"] = $this->mclinic->get_clinic_info($data["clinic_patient_info"]["clinic"]);
		 $data["stock_info"] = $this->mpersistent->open_id($data["clinic_info"]["drug_stock"],"drug_stock", "drug_stock_id");
		 if (empty($data["clinic_patient_info"])){
			$data["error"] ="clinic_patient_info  not found";
			$this->load->vars($data);
			$this->load->view('clinic_error');
			return;
		}
		//if(isset($data["opd_visits_info"]["visit_type_id"])){
			//$data["stock_info"] = $this->mopd->get_stock_info($data["opd_visits_info"]["visit_type_id"]);
		//}
		if ($data["clinic_patient_info"]["PID"] >0){
			$data["patient_info"] = $this->mpersistent->open_id($data["clinic_patient_info"]["PID"], "patient", "PID");
			$data["patient_allergy_list"] = $this->mpatient->get_allergy_list($data["clinic_patient_info"]["PID"]);
		}
		else{
			$data["error"] = "OPD Patient  not found";
			$this->load->vars($data);
			$this->load->view('opd_error');	
			return;
		}
		if (empty($data["patient_info"])){
			$data["error"] ="OPD Patient not found";
			$this->load->vars($data);
			$this->load->view('opd_error');
			return;
		}
		if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age',$data["patient_info"]["DateOfBirth"]);
        }
		$data["user_info"] = $this->mpersistent->open_id($this->session->userdata("UID"), "user", "UID");
		$data["my_favour"] = $this->mopd->get_favour_drug_count($this->session->userdata("UID"));
		$data["PID"] = $data["clinic_patient_info"]["PID"];
                $data["drug_dosage"] = $this->mopd->get_drug_dosage();
		$data["CLNID"] = $clnid;
		$this->load->vars($data);
                $this->load->view('clinic_new_prescribe');	
	}	
	
	
} 


//////////////////////////////////////////

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */