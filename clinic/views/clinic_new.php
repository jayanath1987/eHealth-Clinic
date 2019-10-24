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

	include("header.php");	///loads the html HEAD section (JS,CSS)
	echo Modules::run('menu'); //runs the available menu option to that usergroup
?>
<div class="container" style="width:95%;">
	<div class="row" style="margin-top:55px;">
	  <div class="col-md-2 ">
		<?php echo Modules::run('leftmenu/clinic_new',$clinic_id,$pid,$clinic_patient_info,$clinic_questionnaire_list); //runs the available left menu for preferance ?>
	  </div>
	  <div class="col-md-10 " >
	  <div class="panel panel-default"  style="margin-bottom:0px;">
			<div class="panel-heading"><b><?php
			echo $clinic_info["name"]; 
			//echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->config->item('clinic_nuber_prefix').$clinic_patient_info["clinic_patient_id"];
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
			<?php echo Modules::run('patient/banner_full',$clinic_patient_info["PID"]); ?>
			<?php 
			//print_r($opd_visits_info); 
			//print_r($this->session)?>
			<div class="panel panel-default"  style="padding:2px;margin-bottom:1px;" >
				<div class="panel-heading" ><b>Previous clinic records</b></div>
					<?php
						/*
						if ($opd_visits_info["referred_admission_id"] >0){
							echo '&nbsp;<span class="label label-info"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;This visit referred to admission </span>';
							echo '<a class="btn btn-default btn-xs" href="'.site_url("admission/view/".$opd_visits_info["referred_admission_id"]).'"> Open </a>';
						}
						*/
						//print_r($clinic_previous_record_list);
						echo '<table class="table "  style="font-size:0.95em;margin-bottom:0px;">';
							if (!empty($clinic_previous_record_list)){
								for($i=0;$i<count($clinic_previous_record_list);++$i){
									echo '<tr>';
										echo '<td>';echo '<b class="" style="cursor:pointer;display:block" onclick=$("#data_'.$i.'").toggle(); >';
										//print_r($clinic_previous_record_list[$i]);
											if ($clinic_previous_record_list[$i]["soap_type"]!=""){
											$letter = substr($clinic_previous_record_list[$i]["soap_type"],2,1);
											//echo $letter;
											if (isset($bcl[$letter])){
													echo '<span style="padding:6px;background:'.$bcl[$letter].';margin:2px;" >'.$letter.'</span>';
												}
											}
											echo $clinic_previous_record_list[$i]["qu_name"].' '.$clinic_previous_record_list[$i]["CreateDate"].' ';
											if(Modules::run("security/check_delete_access","qu_quest_answer","can_delete")==1){
												if ($clinic_patient_info["status"] == "Refered")
													echo '<a  title = "Delete this? " class="pull-right glyphicon glyphicon-remove-sign" href="'.site_url("questionnaire/delete/".$clinic_previous_record_list[$i]["qu_quest_answer_id"].'/'.$pid.'/'.$clinic_previous_record_list[$i]["link_id"]).'"></a>';
													ECHO '</b>';
											}
											echo '<hr style="margin:0px;">';// By: '.$clinic_previous_record_list[$i]["CreateUser"].'
											if (!empty($clinic_previous_record_list[$i]["data"])){
												echo '<div id="data_'.$i.'" style="display:none">';
												echo '<table class="table table-condensed table-striped table-hover" style="margin-bottom: 2px">';
												for($j=0;$j<count($clinic_previous_record_list[$i]["data"]);++$j){
													if ($clinic_previous_record_list[$i]["data"][$j]["answer"]=="") continue;
													echo '<tr>';
														echo '<td nowrap width=300px>';
															if($clinic_previous_record_list[$i]["data"][$j]["answer_type"] == "Footer"){
																continue;
															}
															elseif($clinic_previous_record_list[$i]["data"][$j]["answer_type"] == "Header"){
																echo '<b style="text-align:center;">'.$clinic_previous_record_list[$i]["data"][$j]["question"].'</b>';
															}
															else{
																echo $clinic_previous_record_list[$i]["data"][$j]["question"];
															}
														echo '</td>';
														echo '<td>';
															if($clinic_previous_record_list[$i]["data"][$j]["answer_type"]=="PAIN_DIAGRAM"){
																$var = 'diagram'.$clinic_previous_record_list[$i]["data"][$j]["qu_question_id"];
																$clinic_diagram_info = $$var;
																//print_r($clinic_diagram_info);
																if (isset($clinic_diagram_info )){
																	echo '<a target="_blank" href="javascript:void()" onclick=open_diagram("'.$clinic_diagram_info["clinic_diagram_id"].'","'.$patient_info["PID"].'","'.$clinic_previous_record_list[$i]["data"][$j]["qu_answer_id"].'");>Open Diagram</a>';
																}
															}
															else{
																echo $clinic_previous_record_list[$i]["data"][$j]["answer"];
															}
														echo '</td>';
													echo '</tr>';	
												}
												echo '</table>';
												
												//print_r($clinic_previous_record_list);
												if ($clinic_patient_info["status"] == "Refered")
												echo '<a class="btn pull-right" href="'.site_url("questionnaire/edit/".$clinic_previous_record_list[$i]["qu_questionnaire_id"].'/'.$pid.'/'.$clinic_previous_record_list[$i]["link_type"].'/'.$clinic_previous_record_list[$i]["link_id"].'/'.$clinic_previous_record_list[$i]["qu_quest_answer_id"].'?CONTINUE=clinic/open/'.$clinic_previous_record_list[$i]["link_id"]).'">Edit</a>';
											
												echo '</div>';
																	}	
										echo '</td>';				
									echo '</tr>';				
								}	
							}
						echo '</table>';
					?>
			</div>	<!-- END OPD INFO-->
			<!-- NOTES-->
				<?php //echo Modules::run('opd/get_nursing_notes',$patient_info["PID"],'clinic/open/'.$clinic_patient_info["clinic_patient_id"],"HTML"); ?>
			<!-- END NOTES-->	
			<!-- ALLERGY-->
				<?php //echo Modules::run('patient/get_previous_allergy',$patient_info["PID"],'clinic/open/'.$clinic_patient_info["clinic_patient_id"],"HTML"); ?>
			<!-- END ALLERGY-->			
			<!-- PAST HISTORY-->
				<?php //echo Modules::run('patient/get_previous_history',$patient_info["PID"],'clinic/open/'.$clinic_patient_info["clinic_patient_id"],"HTML"); ?>
			<!-- END PAST HISTORY-->
			<!-- EXAMINATION-->
               <?php //echo Modules::run('patient/get_previous_exams',$patient_info["PID"],'clinic/open/'.$clinic_patient_info["clinic_patient_id"],"HTML"); ?>
			<!-- END EXAMINATION-->
			<!-- LAB-->
				<?php //echo Modules::run('patient/get_previous_lab',$patient_info["PID"],'clinic/open/'.$clinic_patient_info["clinic_patient_id"],"HTML"); ?>
			<!-- END LAB-->				
			<!-- END TREATMENT-->
			<!-- Ijection-->
				<?php //echo Modules::run('patient/get_previous_injection',$patient_info["PID"],'clinic/open/'.$clinic_patient_info["clinic_patient_id"],"HTML"); ?>
			<!-- ENDIjection-->		
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
