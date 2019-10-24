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
Date : June 2017
Author: Mr. sachinda Liyanage   sachindal@icta.lk

Programme Manager: Shriyananda Rathnayake
URL: http://www.govforge.icta.lk/gf/project/hhims/
----------------------------------------------------------------------------------
*/

	include("header.php");	///loads the html HEAD section (JS,CSS)
?>
<?php echo Modules::run('menu'); //runs the available menu option to that usergroup ?>
<div class="container" style="width:95%;">
	<div class="row" style="margin-top:55px;">
	  <div class="col-md-2 ">
	  </div>
		<div class="col-md-4" >
		<div class="panel panel-default"  >
			<div class="panel-heading"><b>Clinic Appointment</b>
			</div>
				<div style="padding:10px;">
				<?php
					echo "Patient : ".$patient_info["Personal_Title"].' '.$patient_info["Full_Name_Registered"].' ' .$patient_info["Personal_Used_Name"]."<br>";
					echo "HIN : ".$patient_info["HIN"]."<br>";
					echo "Clinic : <b>".$clinic_info["name"]."</B><br>";
					echo "Appointment date : ".$app_date."<br>";
		                    echo "<a class='btn btn-default' onclick=\"openWindow('" . site_url(
                    "report/pdf/clinicToken/print/".$clinic_patient_id."/".$clinic_info["clinic_id"]
                ) . "')\" href='#'>Print </a>";
				?>



					<a  class="btn btn-default" href="<?php echo site_url("clinic/refers"); ?>">Back to List</a>
					
				</div>
			</div>
		</div>
	</div>
</div>
