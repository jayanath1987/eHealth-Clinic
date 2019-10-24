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
	  <div class="panel panel-default" style="margin-bottom:0px;">
			<div class="panel-heading"><b><?php
			echo $clinic_info["name"]; 
			echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->config->item('clinic_nuber_prefix').$clinic_visit_info["clinic_visits_id"];
			?></b>
			</div>
			</div>
			<?php echo Modules::run('patient/banner_full',$clinic_visit_info["PID"]); 
				//print_r($clinic_visit_info);
			?>
			<p>	
			<div class="alert alert-danger"><b>Warning:</b>After closing the clinic visit you cant edit or add any more information to this visit!<br>
				<a href="<?php echo site_url("clinic/visit_view/".$clinic_visit_info["clinic_visits_id"]); ?>">Return to the visit</a>
			</div>
				
			</p>
			
			
			<form  method="POST" action="<?php echo site_url("clinic/close_visit/"); ?>">
			<div class="well well-sm">If you want to give an appointment for next visit, please select the date.
			<input  class="form-control input-sm" type="text" id="next_visit_date" name="next_visit_date" value="" placeholder="Date of next visit" class="input" style="" rules="required|xss_clean" onmousedown="onmousedown=$('#next_visit_date').datetimepicker({changeMonth: true,changeYear: true,yearRange: 'c-0:c+40',dateFormat: 'yyyy-mm-dd HH:MM:ss ',maxDate: '+30D'});"  >
			</div>
			<input  type="hidden" id="clinic_visits_id" name="clinic_visits_id" value="<?php echo $clinic_visit_info["clinic_visits_id"]; ?>" placeholder=""    >
			<input  type="hidden" id="PID" name="PID" value="<?php echo $clinic_visit_info["PID"]; ?>"    >
			<input  type="hidden" id="clinic_id" name="clinic_id" value="<?php echo $clinic_visit_info["clinic"]; ?>"    >
			<button type="submit" class="btn btn-warning btn-lg btn-block" >Close this visit </button>
			</form>
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
