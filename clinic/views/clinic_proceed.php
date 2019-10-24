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
with this program. If not, see <http://www.gnu.org/licenses/> 
---------------------------------------------------------------------------------- 
Date : June 2016
Author: Mr. Jayanath Liyanage   jayanathl@icta.lk

Programme Manager: Shriyananda Rathnayake
URL: http://www.govforge.icta.lk/gf/project/hhims/
__________________________________________________________________________________
SNOMED Modification :

Date : July 2015		ICT Agency of Sri Lanka (www.icta.lk), Colombo
Author : Laura Lucas
Programme Manager: Shriyananda Rathnayake
Supervisors : Jayanath Liyanage, Erandi Hettiarachchi
URL: http://www.govforge.icta.lk/gf/project/hhims/
----------------------------------------------------------------------------------
*/
echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
echo "\n<html xmlns='http://www.w3.org/1999/xhtml'>";
echo "\n<head>";
echo "\n<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>";
echo "\n<meta http-equiv='refresh' content='60' > ";
echo "\n<title>".$this->config->item('title')."</title>";
echo "\n<link rel='icon' type='". base_url()."/image/ico' href='images/mds-icon.png'>";
echo "\n<link rel='shortcut icon' href='". base_url()."/images/mds-icon.png'>";
echo "\n<link href='". base_url()."/css/mdstheme_navy.css' rel='stylesheet' type='text/css'>";
echo "\n<link href='". base_url()."/css/jquery-ui-1.8.9.custom.css' rel='stylesheet' type='text/css'>";
echo "\n<link href='". base_url()."/css/jquery.ui.datetimepicker.css' rel='stylesheet' type='text/css'>";
echo "\n<link href='". base_url()."/css/mds_k.css' rel='stylesheet' type='text/css'>";
echo "\n<link href='". base_url()."/css/layout_k.css' rel='stylesheet' type='text/css'>";
echo "\n<link rel='stylesheet' type='text/css' media='screen' href='". base_url()."/css/themes/ui.jqgrid.css' />";


echo "\n<script type='text/javascript' src='". base_url()."/js/jquery.js'></script>";
echo "\n<script type='text/javascript' src='". base_url()."/js/ui.js'></script>";
echo "\n<script type='text/javascript' src='". base_url()."/js/mdsCore.js'></script> ";
echo "\n<script type='text/javascript' src='". base_url()."/js/mdsmailer.js'></script> ";
echo "\n<script type='text/javascript' src='". base_url()."/js/jquery.hotkeys-0.7.9.min.js'></script>";
echo "\n<script type='text/javascript' src='". base_url()."/js/jquery.ui.datetimepicker.min.js'></script>";

echo "\n    <script type='text/javascript' src='".base_url()."js/bootstrap/js/bootstrap.min.js' ></script>";
echo "\n    <link href='". base_url()."js/bootstrap/css/bootstrap.min.css' rel='stylesheet' type='text/css' />";
echo "\n    <link href='". base_url()."js/bootstrap/css/bootstrap-theme.min.css' rel='stylesheet' type='text/css' />";

echo "\n</head>";
	
?>
<?php echo Modules::run('menu'); //runs the available menu option to that usergroup ?>
<div class="container" style="width:95%;">
	<div class="row" style="margin-top:55px;">
	  <div class="col-md-2 ">
	  </div>
	  <div class="col-md-10 " >
		<?php
		$patInfo ="";
		//$mdsPermission = MDSPermission::GetInstance();
		//if ($mdsPermission->haveAccess($_SESSION["UGID"],"patient_Edit"))
		$tools = "<img src='".base_url()."/images/patient.jpg' width=100 height=100 style='padding:2px;'>";
		echo  "<div id ='patientBanner' class='well'  style='padding:0px;'>\n";
		echo  "<table width=100% border=0 class='' style='font-size:0.95em;'>\n";
		echo  "<tr><td  rowspan=5 valign=top align=left width=10>".$tools."</td><td>Full Name:</td><td><b>";
		echo  $patient_info["Personal_Title"];
		echo  $patient_info["Personal_Used_Name"]."&nbsp;";
		echo  $patient_info["Full_Name_Registered"];
		echo "</b></td><td>HIN:</td><td><b>".$patient_info["HIN"]."</b>";
		echo  "<td  rowspan=5 valign=top align=left width=10>";
		//echo  "<input type='button' class='btn btn-xs btn-warning pull-right' onclick=self.document.location='".site_url('form/edit/patient/'.$patient_info["PID"])."' value='Edit'>";
		echo  "<tr><td>Gender:</td><td><b>".$patient_info["Gender"]."</b></td>";
		echo  "<td>NIC:</td><td>".$patient_info["NIC"]."</td></tr>\n";
		echo  "<tr><td>Date of birth:</td><td><b>".$patient_info["DateOfBirth"]."</b></td><td >Address:</td><td rowspan=3 valign=top>";
		echo  $patient_info["Address_Street"]."&nbsp;";
		echo  $patient_info["Address_Street1"]."<br>";
		echo  $patient_info["Address_Village"]."<br>";
		//echo  $patient_info["Address_DSDivision"]."<br>";
		echo  $patient_info["Address_District"]."<br>";
		echo  "</td></tr>\n";
		echo  "<tr><td>Age:</td><td><b>~";
		if ($patient_info["Age"]["years"]>0){
			echo  $patient_info["Age"]["years"]."Yrs&nbsp;";
		}
		echo  $patient_info["Age"]["months"]."Mths&nbsp;";
		echo  $patient_info["Age"]["days"]."Dys&nbsp;";
		echo  "</b></td><td></td></tr>\n";
		echo  "<tr><td>Civil Status:</td><td>".$patient_info["Personal_Civil_Status"]."</td><td></td></tr>\n";
		echo  "</table></div>\n";
		?>

			<?php 
			//print_r($opd_visits_info); 
			//print_r($this->session)?>
			<div class="panel panel-default"  style="padding:2px;margin-bottom:1px;" >
				<div class="panel-heading" ><b>Refering OPD information</b></div>
					<?php
						echo '<table class="table table-condensed"  style="font-size:0.95em;margin-bottom:0px;">';
							echo '<tr>';
								echo '<td>';
									//echo 'Type: '.$opd_visits_info["VisitType"];
								echo '</td>';
								echo '<td>';
									echo 'Date & Time of visit: '.$opd_visits_info["DateTimeOfVisit"];
								echo '</td>';
								echo '<td>';
									echo 'Onset Date: '.$opd_visits_info["OnSetDate"];
								echo '</td>';
								echo '<td>';
									echo 'Doctor: '.$opd_visits_info["Doctor"];
									//echo  "<input type='button' class='btn btn-xs btn-warning pull-right' onclick=self.document.location='".site_url('form/edit/opd_visits/'.$opd_visits_info["OPDID"])."' value='Edit'>";
								echo '</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>';
									echo 'Complaint: <b>'.$opd_visits_info["Complaint"].'</b>';
								echo '</td>';
								echo '<td>';
									echo 'Notify: ';
									echo ($opd_visits_info["isNotify"]==1)?"YES":"NO";
								echo '</td>';
								echo '<td colspan=2>';
									echo 'ICD: '.$opd_visits_info["ICD_Text"];
								echo '</td>';
							echo '</tr>';								
							echo '<tr>';
								echo '<td colspan=2>';
									echo 'Remarks: '.$opd_visits_info["Remarks"];
								echo '</td>';
								echo '<td >';
									echo 'CreatedBy: '.character_limiter($opd_visits_info["CreateUser"],15);
								echo '</td>';
								echo '<td >';
									if ($opd_visits_info["LastUpDateUser"] !=""){
										echo 'Last Access By: '.character_limiter($opd_visits_info["LastUpDateUser"],15);
									}
								echo '</td>';
							echo '</tr>';				
						echo '</table>';
					?>
			</div>	<!-- END OPD INFO-->
			<div class="panel panel-default"  style="padding:2px;margin-bottom:1px;" >
				<div class="panel-heading" ><b>Give an Appointment for <?php echo $Clinic_Name["name"]; ?></b></div>
<form  method="POST" action="<?php echo site_url("clinic/appointment/"); ?>">
			<div class="well well-sm">If you want to give an appointment , please select the date.
			<input  class="form-control input-sm" type="text" id="next_visit_date" name="next_visit_date" value="" placeholder="Date of next visit" class="input" style="" rules="required|xss_clean" onmousedown="onmousedown=$('#next_visit_date').datetimepicker({changeMonth: true,changeYear: true,yearRange: 'c-0:c+40',dateFormat: 'yyyy-mm-dd HH:MM:ss ',maxDate: '+30D',minDate:-1});"  >
			</div>
			<input  type="hidden" id="referred_visit_id" name="referred_visit_id" value="<?php echo 'OPD'.$opd_visits_info["OPDID"]; ?>" placeholder=""    >
			<input  type="hidden" id="PID" name="PID" value="<?php echo $opd_visits_info["PID"]; ?>"    >
			<input  type="hidden" id="clinic_id" name="clinic_id" value="<?php echo $opd_visits_info["referred_clinic_id"]; ?>"    >
			<button type="submit" class="btn btn-primary btn-lg btn-block" >Give an Appointment for <?php echo $Clinic_Name["name"]; ?></button>
			</form>
			</div>	<!-- END OPD INFO-->			
		</div>
	</div>
</div>
