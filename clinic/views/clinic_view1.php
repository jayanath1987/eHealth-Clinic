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
		<?php echo Modules::run('leftmenu/clinic',$pid,$clinic_id); //runs the available left menu for preferance ?>
	  </div>
	  <div class="col-md-10 " >
	  <div class="panel panel-default" style="margin-bottom:0px;">
			<div class="panel-heading"><b><?php
			echo "Clinic overview"; 
			//echo $clinic_info["name"]; 
			//echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->config->item('clinic_nuber_prefix').$clinic_patient_info["clinic_patient_id"];
			?></b>
			</div>
		</div>
			<?php echo Modules::run('patient/banner_full',$pid); ?>
			<?php 
			//print_r($opd_visits_info); 
			//print_r($this->session)?>
			<?php echo Modules::run('patient/get_pomr',$patient_info["PID"],'clinic/view/'.$patient_info["PID"],"HTML"); ?>
			<div class="panel panel-default"  style="padding:2px;margin-bottom:1px;" >
			
				<div class="panel-heading" ><b>Clinic visits</b></div>
					<?php
						//print_r($clinic_visit_list);
						if (!empty($clinic_visit_list)){
							echo '<table class ="table table-condensed table-hover">';
								for ($i=0; $i < count($clinic_visit_list); ++$i){
									echo '<tr ';
										echo 'style="cursor:pointer;"  onclick="self.document.location=\''.site_url("clinic/visit_view/".$clinic_visit_list[$i]["clinic_visits_id"]).'?CONTINUE=\';"';
									echo '>';
										echo '<td>'.$clinic_visit_list[$i]["DateTimeOfVisit"].'</td>';
										echo '<td>'.$clinic_visit_list[$i]["clinic_name"].'</td>';
										echo '<td>'.$clinic_visit_list[$i]["Title"].' '.$clinic_visit_list[$i]["FirstName"].' '.$clinic_visit_list[$i]["OtherName"].'</td>';
										if ($clinic_visit_list[$i]["status"] == null){
											echo '<td><span class="label label-success">Opened</span></td>';
										}
										else{
											echo '<td><span class="label label-danger">Closed</span></td>';
										}
									echo '</tr>';
								}
							echo '</table>';
						}
					?>
			</div>	
				
				<?php echo Modules::run('opd/get_nursing_notes',$patient_info["PID"],'clinic/view/'.$patient_info["PID"],"HTML"); ?>
				<?php echo Modules::run('patient/get_previous_allergy',$patient_info["PID"],'clinic/view/'.$patient_info["PID"],"HTML"); ?>
				<?php echo Modules::run('patient/get_previous_history',$patient_info["PID"],'clinic/view/'.$patient_info["PID"],"HTML"); ?>
				<?php echo Modules::run('patient/get_treatment',$patient_info["PID"],'clinic/view/'.$patient_info["PID"],"HTML"); ?>
				<?php echo Modules::run('patient/get_previous_lab',$patient_info["PID"],'clinic/view/'.$patient_info["PID"],"HTML"); ?>
				<?php echo Modules::run('patient/get_previous_injection',$patient_info["PID"],'clinic/view/'.$patient_info["PID"],"HTML"); ?>
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
