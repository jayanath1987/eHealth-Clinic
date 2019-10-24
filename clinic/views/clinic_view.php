<?php
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
with this program. If not, see <http://www.gnu.org/licenses/> or write to:
Free Software  HHIMS
ICT Agency,
160/24, Kirimandala Mawatha,
Colombo 05, Sri Lanka
---------------------------------------------------------------------------------- 
Author: Author: Mr. Jayanath Liyanage   jayanathl@icta.lk
                 
URL: http://www.govforge.icta.lk/gf/project/hhims/
----------------------------------------------------------------------------------
*/
echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
echo "\n<html xmlns='http://www.w3.org/1999/xhtml'>";
echo "\n<head>";
echo "\n<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>";
echo "\n<meta http-equiv='refresh' content='60' > ";
echo "\n<title>".$this->config->item('title')."</title>";
echo "\n<link rel='icon' type='". base_url()."image/ico' href='images/mds-icon.png'>";
echo "\n<link rel='shortcut icon' href='". base_url()."images/mds-icon.png'>";
echo "\n<link href='". base_url()."/css/mdstheme_navy.css' rel='stylesheet' type='text/css'>";
echo "\n<script type='text/javascript' src='". base_url()."js/jquery.js'></script>";
echo "\n<script type='text/javascript' src='". base_url()."/js/jquery.hotkeys-0.7.9.min.js'></script>";
echo "\n    <script type='text/javascript' src='".base_url()."js/bootstrap/js/bootstrap.min.js' ></script>";
echo "\n    <link href='". base_url()."js/bootstrap/css/bootstrap.min.css' rel='stylesheet' type='text/css' />";
echo "\n    <link href='". base_url()."js/bootstrap/css/bootstrap-theme.min.css' rel='stylesheet' type='text/css' />";  
echo "\n<script type='text/javascript' src='". base_url()."/js/mdsCore.js'></script> ";
echo "\n</head>";
	
?>
<?php echo Modules::run('menu'); //runs the available menu option to that usergroup ?>
<div class="container" style="width:95%;">
	<div class="row" style="margin-top:55px;">
	  <div class="col-md-2 ">
		<?php echo Modules::run('leftmenu/clinic_new',$clinic_id,$patient_info,$clinic_visit_info,$clinic_questionnaire_list,$patient_questionnaire_list); //runs the available left menu for preferance ?>
	  </div>
	  <div class="col-md-10 " >
	  <div class="panel panel-default" style="margin-bottom:0px;">
			<div class="panel-heading"><b><?php
			echo $clinic_info["name"].'&nbsp;visit'; 
			//echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->config->item('clinic_nuber_prefix').$clinic_visit_info["clinic_visits_id"];
			if ($clinic_visit_info["status"]==null){
				echo '<td><span class="label label-success pull-right">Opened</span></td>';
			}
			else{
				echo '<td><span class="label label-danger pull-right">Closed</span></td>';
			}
			?></b>
			</div>
				<?php
				$bcl["S"] = 'rgb(127,128,24)';
				$bcl["O"] = 'rgb(180,179,0)';
				$bcl["A"] = 'rgb(205,204,0)';
				$bcl["P"] = 'rgb(230,229,76)';
				$bcl["G"] = 'rgb(127, 112, 216)';
				
			?>
			</div>
			<?php echo Modules::run('patient/banner_full',$clinic_visit_info["PID"]); ?>
			<?php echo Modules::run('patient/get_pomr',$patient_info["PID"],'clinic/visit_view/'.$clinic_visit_info["clinic_visits_id"],"HTML"); ?>
			
			<?php   if ((isset($last_clinic_prescription))&&(!empty($last_clinic_prescription))){			
					echo '<div class="panel panel-default"  style="padding:2px;margin-bottom:1px;" >';
						echo '<div class="panel-heading" ><b>Prescriptions for Last visit to  ' .$clinic_info["name"];
						echo '</b></div>' ;
							echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
							for ($i=0;$i<count($last_clinic_prescription); ++$i){	
								echo '<tr>';
								echo '<td width=40%>';
								echo $last_clinic_prescription[$i]["name"];
								echo '</td>';
								echo '<td>';
								echo $last_clinic_prescription[$i]["Dosage"];
								echo '</td>';
                                                                echo '<td>';
								echo $last_clinic_prescription[$i]["Frequency"];
								echo '</td>';
								echo '<td>';
							        echo $last_clinic_prescription[$i]["HowLong"];
								echo '</td>';
                                                                echo '<td>';
							        echo 'Dispensed by:'; echo '&nbsp&nbsp'; echo $last_clinic_prescription[$i]["LastUpDateUser"];
								echo '</td>';
								echo '</tr>';
							}
							echo '</table>';
					echo '</div>';	
				}
			
			 echo Modules::run('questionnaire/get_answer_list',$patient_info["PID"],'clinic',$clinic_visit_info); ?>
			<?php echo Modules::run('questionnaire/get_SOAP_answer_list',$clinic_visit_info["clinic_visits_id"],'clinic_visits',$clinic_visit_info); ?>
			
			
			
			<!-- NOTES-->
				<?php echo Modules::run('opd/get_nursing_notes',$patient_info["PID"],'clinic/visit_view/'.$clinic_visit_info["clinic_visits_id"],"HTML"); ?>
			<!-- END NOTES-->	
			<!-- ALLERGY-->
				<?php echo Modules::run('patient/get_previous_allergy',$patient_info["PID"],'clinic/visit_view/'.$clinic_visit_info["clinic_visits_id"],"HTML"); ?>
			<!-- END ALLERGY-->			
			<!-- PAST HISTORY-->
				<?php echo Modules::run('patient/get_previous_history',$patient_info["PID"],'clinic/visit_view/'.$clinic_visit_info["clinic_visits_id"],"HTML"); ?>
			<!-- END PAST HISTORY-->
			<?php echo Modules::run('patient/get_treatment',$patient_info["PID"],'clinic/visit_view/'.$clinic_visit_info["clinic_visits_id"],"HTML"); ?>
			<!-- EXAMINATION-->
               <?php echo Modules::run('patient/get_previous_exams',$patient_info["PID"],'clinic/visit_view/'.$clinic_visit_info["clinic_visits_id"],"HTML"); ?>
			<!-- END EXAMINATION-->
			<!-- LAB-->
				<?php echo Modules::run('patient/get_previous_lab',$patient_info["PID"],'clinic/visit_view/'.$clinic_visit_info["clinic_visits_id"],"HTML"); ?>
			<!-- END LAB-->				
			<!-- END TREATMENT-->
			<!-- Ijection-->
				<?php echo Modules::run('patient/get_previous_injection',$patient_info["PID"],'clinic/visit_view/'.$clinic_visit_info["clinic_visits_id"],"HTML"); ?>
			<!-- ENDIjection-->	
			<?php
                        
                if ((isset($patient_prescription_list))&&(!empty($patient_prescription_list))){			
					echo '<div class="panel panel-default"  style="padding:2px;margin-bottom:1px;" >';
						echo '<div class="panel-heading" ><b>Prescriptions for this visit</b></div>';
							echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
							for ($i=0;$i<count($patient_prescription_list); ++$i){
								echo '<tr onclick="self.document.location=\''.site_url("clinic/prescription/".$patient_prescription_list[$i]["clinic_prescription_id"]).'?CONTINUE=clinic/visit_view/'.$clinic_visit_info["clinic_visits_id"].'\';">';
								echo '<td>';
								echo $patient_prescription_list[$i]["CreateDate"];
								echo '</td>';
								echo '<td>';
								echo $patient_prescription_list[$i]["PrescribeBy"];
								echo '</td>';
								echo '<td>';
								if ($patient_prescription_list[$i]["Status"] == "Dispensed"){
									echo '<span class="glyphicon glyphicon-check"></span>';
								}
								else if($patient_prescription_list[$i]["Status"] == "Pending"){
									echo '<span class="glyphicon glyphicon-time"></span>';
								}
								else{
									echo '<span class="glyphicon glyphicon-edit"></span>';
								}
								
								echo '&nbsp'.$patient_prescription_list[$i]["Status"];
								echo '</td>';
								echo '</tr>';
							}
							echo '</table>';
					echo '</div>';	
				}
				
				if ((isset($patient_procedure_list))&&(!empty($patient_procedure_list))){			
					echo '<div class="panel panel-default"  style="padding:2px;margin-bottom:1px;" >';
						echo '<div class="panel-heading" ><b>Ordered procedures for this visit</b></div>';
							echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
							for ( $i = 0; $i<count($patient_procedure_list); ++$i){
								echo '<tr onclick="self.document.location=\''.site_url("form/edit/clinic_treatment/".$patient_procedure_list[$i]["clinic_treatment_id"]).'?CONTINUE=clinic/visit_view/'.$clinic_visit_info["clinic_visits_id"].'\';">';
								echo '<td>';
								echo $patient_procedure_list[$i]["CreateDate"];
								echo '</td>';
								echo '<td>';
								echo $patient_procedure_list[$i]["Treatment"];
								echo '</td>';
								echo '<td>';
								if ($patient_procedure_list[$i]["Status"] == "Done"){
									echo '<span class="glyphicon glyphicon-check"></span>';
								}
								else if($patient_procedure_list[$i]["Status"] == null){
									echo '<span class="glyphicon glyphicon-time"></span>';
								}
								echo '&nbsp'.$patient_procedure_list[$i]["Status"];
								echo '</td>';
								echo '<td>';
								echo $patient_procedure_list[$i]["Remarks"];
								echo '</td>';
								echo '</tr>';
							}
							echo '</table>';
					echo '</div>';	
				}
			?>		
			
		</div>
	</div>
</div>
<script>
	function open_diagram(diagram_id,pid,ans_id){
		var url='<?php echo site_url('diagram/view/'); ?>';
		url+='/'+diagram_id+'/'+pid+'/view_data/'+ans_id;
		var win = window.open(url,'d_win','fullscreen=yes,location=no,menubar=no');
	}
</script>

