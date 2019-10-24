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
  Modification :

  Date : July 2015		ICT Agency of Sri Lanka (www.icta.lk), Colombo
  Author : Laura Lucas
  Programme Manager: Shriyananda Rathnayake
  Supervisors : Jayanath Liyanage, Erandi Hettiarachchi
  URL: http://www.govforge.icta.lk/gf/project/hhims/
  ----------------------------------------------------------------------------------
 */ // Laura

include("header.php"); ///loads the html HEAD section (JS,CSS)
?>
<?php echo Modules::run('menu'); //runs the available menu option to that usergroup  ?>
<div class="container" style="width:95%;" >
    <div class="row" style="margin-top:55px;">

        <div class="col-md-10 " >
            <?php
            if (isset($PID)) {
                echo Modules::run('patient/banner', $PID);
            }
            if (isset($error)){
				echo '                   <div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Danger!</strong> '.$error.'</div>';
  
			}
            ?>
            
            <div id="myModal" class="modal2">

  <!-- Modal content -->
  <div class="modal-content">
      <div class="modal-header2">
           <span class="close">x</span>
       <h2>Allergy Warning</h2>
  </div>
       <div id="allergy_msg" class="modal-body">
    
     </div>
       <p></p>
  </div>

</div>
            
            <div class="panel panel-default  "  style="padding:2px;margin-bottom:1px;" >
                <div class="panel-heading" ><b> Prescription</b>
                    <?php
                    if (!empty($stock_info)) {
                        if ($stock_info["name"] != "") {
                            echo 'will use ' . $stock_info["name"] . ' stock';
                        } else {
                            echo '!Stock not configured for this visit type';
                        }
                    }
                    $d_dosage = array();
                    //print_r($drug_dosage);
                    
                    if (!empty($drug_dosage)) {
                        $json_dosage = json_encode($drug_dosage);
                    }
                    ?>
                </div>
                <div class="" style="margin-bottom:1px;padding-top:8px;">
                    <?php
                    echo '<table class="table table-condensed table-hover" style="margin-bottom:0px;">';
                    echo '<tr>';
                    echo '<td>';
                    echo 'Complaint / Injuries : <b id="opd_complaint">' . $clinic_patient_info["Complaint"] . '</b>';
                    echo '</td>';
                    echo '<td>';
                    echo 'Visit Date : <b>' . $clinic_patient_info["DateTimeOfVisit"] . '</b>';
                    echo '</td>';
                    echo '<td>';
                    echo 'Visit type : <b>' . $clinic_info["name"] . '</b>';
                    echo '</td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td>';
                    if (isset($clinic_presciption_info["Status"])) {
                        echo 'Status : <b>' . $clinic_presciption_info["Status"] . '</b>';
                    }
                    echo '</td>';
                    echo '<td>';
                    if (isset($clinic_patient_info["Doctor"])) {
                        echo 'Doctor : <b>' . $clinic_patient_info["CreateUser"] . '</b>';
                    }
                    echo '</td>';
                    echo '<td>';
                    if (isset($clinic_presciption_info["PrescribeDate"])) {
                        echo 'Prescribe Date : <b>' . $clinic_presciption_info["PrescribeDate"] . '</b>';
                    }
                    echo '</td>';
                    echo '</tr>';
                    echo '<tr>';
							echo '<td>';
                                                        if (isset($clinic_presciption_info["Status"])) {
								if ($clinic_presciption_info["Status"]=='Dispensed'){
									echo 'Dispensed By : <b>'.$clinic_presciption_info["LastUpDateUser"].'</b>';
                                                        }
								}
							echo '</td>';
                    echo '</table><br>';
                    //print_r($clinic_presciption_info);
                    if (isset($prescribe_items_list)) {
                        echo '<table class="table table-condensed table-hover" style="margin-bottom:0px;">';
                        echo '<tr style="background:#e2e2e2;"><th>#</th><th>Name</th><th>Dose</th><th>Frequency</th><th>Period</th><th>Dose Comment</th>';

                        echo '<th>Delete</th>';
                        echo '<th>Print</th>';

                        echo '</tr>';
                        for ($i = 0; $i < count($prescribe_items_list);  ++$i) {
                            //print_r($prescribe_items_list[$i]);
                            echo '<tr>';
                            echo '<td>';
                            echo ($i + 1);
                            echo '</td>';
                            echo '<td>';
                            echo $prescribe_items_list[$i]["drug_name"];
                            echo '-';
                            echo $prescribe_items_list[$i]["formulation"];
                            echo '-';
                            echo $prescribe_items_list[$i]["dose"];
                            echo '</td>';
                            echo '<td>';
                            echo $prescribe_items_list[$i]["Dosage"];
                            echo '</td>';
                            echo '<td>';
                            echo $prescribe_items_list[$i]["Frequency"];
                            echo '</td>';
                            echo '<td>';
                            echo $prescribe_items_list[$i]["HowLong"];
                            echo '</td>';
                            echo '<td>';
                            echo $prescribe_items_list[$i]["DoseComment"];
                            echo '</td>';
                            echo '<td>';
                            if ($clinic_presciption_info["Status"] == "Draft" || $clinic_presciption_info["Status"] == "Pending") {
                                echo '<button type="button" class="btn btn-default btn-xs" title=" Remove this item" onclick=delete_record("' . $prescribe_items_list[$i]["clinic_prescribe_item_id"] . '"); >
												<span class="glyphicon glyphicon-remove-circle"></span>
												</button>';
                            }
                            echo '</td>';
                            echo '<td><input type="checkbox" name="print[]" id="print" value=' . $prescribe_items_list[$i]["clinic_prescribe_item_id"] . ' /></td>';

                            echo '</tr>';
                        }
                        echo '</table>';

                        //if (!isset($prescribe_items_list)){
                        echo '<a href="' . site_url("clinic/visit_view/" . $clinic_patient_info["clinic_visits_id"]) . '" type="button" class="btn  pull-left btn-warning btn-xs"  >Back to visit</a>';
                        //}
//						echo " <a href=\"#\" onclick=\"openWindow('" . site_url("report/pdf/opdPrescription/print/$prisid") . "')\" type=\"button\" class=\"btn  pull-left btn-warning btn-xs\"  >Print this</a>";
                        if (count($prescribe_items_list) > 0) {
                            echo "<a href=\"#\" onclick=\"printPrescription('" . site_url("report/pdf/ClinicoutsidePrescription/print/") . "')\"  type='button' class=\"btn  pull-left btn-warning btn-xs\"  >Print</a>";
                        }
                        if ($clinic_presciption_info["Status"] == "Pending") {
                            echo '&nbsp;&nbsp;&nbsp;<a href="' . site_url("pharmacy/clinic_cancel_prescription/" . $clinic_presciption_info["clinic_prescription_id"] . "/OPD/" . $clinic_patient_info["clinic_visits_id"]) . '" type="button" class="btn  pull-right btn-danger btn-xs"  >Cancel this prescription</a>';
                        }
                        echo '<br><br>';

                        echo "<script language=\"javascript\">\n";
                        echo "function printPrescription(url){\n";
                        echo "    var data='?';\n";
                        echo "    $.each( $(\"#print:checked\" ), function( key, value ) {\n";
                        echo "        data+='print[]='+$(this).val()+'&';\n";
                        echo "    });\n";
                        echo "    var pId=$clinic_patient_info[PID];\n";
                        echo "    data+='pid='+pId;\n";
                        echo "    var params = \"menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=750,height=700\";\n";
                        echo "    window.open(url + data, \"lookUpW\",params);\n";
                        echo "}\n";
                        echo "</script>\n";
                        if (($clinic_presciption_info["Status"] == "Dispensed") || ($clinic_presciption_info["Status"] == "Pending")) {
                            echo '</div><br><br>';
                            exit;
                        }
                        if (count($prescribe_items_list) > 0) {
                            if ($clinic_presciption_info["Status"] != "Pending") {
                                echo '&nbsp;&nbsp;&nbsp;<a href="' . site_url("pharmacy/clinic_cancel_prescription/" . $clinic_presciption_info["clinic_prescription_id"] . "/clinic/" . $clinic_patient_info["clinic_visits_id"]) . '" type="button" class="btn  pull-right btn-danger btn-xs"  >Discard</a>';
                                echo '<button type="button" class="btn pull-right btn-success btn-xs" onclick=send_to_pharmacy("' . $clinic_presciption_info["clinic_prescription_id"] . '","' . $clinic_patient_info["clinic_visits_id"] . '"); >Send to pharmacy</button>';
                            }
                        }
                    }
                    if (!isset($prescribe_items_list)) {
                        echo '<a href="'.site_url("clinic/visit_view/".$clinic_patient_info["clinic_visits_id"]).'" type="button" class="btn  pull-left btn-warning btn-xs"  >Back to visit</a>';
                    }
                    if (isset($clinic_presciption_info)) {
                        if ($clinic_presciption_info["Status"] == "Pending") {
                            echo '<center><a href="#"  class="" onclick=\'$("#durg_list_div").show();\'>Do you want to add more drugs to this prescription?</a></center><br>';
                        }
                    }
                    ?>				
                </div>
                    
                <div id="durg_list_div"> 
                <?php
                if (isset($clinic_presciption_info)) {
                    if ($clinic_presciption_info["Status"] == "Pending") {
                        echo ' style="display:none" ';
                    }
                }
                ?>	



        </div><br><br>
        <table class="table table-condensed" border=0 style="background:#f4f4f4">
            <tr id="blk_choose">
                <td width=150px>
                    <!-- <b>How to Choose</b>
                    <button type="button" id="btn_by_group" class="btn btn-default btn-sm">By group</button>  -->
                    <button type="button" id="btn_by_name"  class="btn btn-default btn-sm">By name</button>
                    <button type="button" id="btn_by_previous"  class="btn btn-default btn-sm">Previous prescriptions</button>
                    <button type="button" id="btn_by_favour" class="btn btn-default btn-sm">
                        <span class="glyphicon glyphicon-heart"></span>&nbsp;My Favourite Group
<?php
if (isset($my_favour)) {
    echo '<span class="badge">' . $my_favour . '</span>';
}
?>
                    </button>

                        <?php
                        if (isset($prescribe_items_list) && (count($prescribe_items_list) > 0)) {
                            echo '<button  ';
                            echo 'onclick=add_to_favour("' . $clinic_presciption_info["clinic_prescription_id"] . '"); ';
                            echo ' type="button" id="btn_add_favour" class="btn btn-primary  btn-sm pull-right"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add above list to My favourites</button>';
                        }
                        ?>
                </td>
            </tr>
            <tr id="blk_2_tr">
                <td id="blk_2">
                    <table >
                        <tr>
                            <td>
                                <table class="" width="10px"  border=0>

                                    <tr id="blk_2_content" >

                                    </tr>
                                </table>
                            </td>
                            <td style="vertical-align:middle">
                                <div id="add_cont" class=""></div>
                            </td>
                        </tr>
                    </table>
                    <form id="drug_form" method="POST" action="<?php echo site_url("clinic/save_prescription"); ?>" >
                        <input type="hidden" name="clinic_prescription_id" id="clinic_prescription_id" value="<?php echo isset($clinic_presciption_info["clinic_prescription_id"]) ? $clinic_presciption_info["clinic_prescription_id"] : null; ?>">
                        <input type="hidden" name="CONTINUE" id="CONTINUE" value="<?php echo isset($_GET["CONTINUE"]) ? $_GET["CONTINUE"] : null; ?>">
                        <input type="hidden" name="clinic_patient_id" id="clinic_patient_id" value="<?php echo $clinic_patient_info["clinic_visits_id"]; ?>">
                        <input type="hidden" name="PID" id="PID" value="<?php echo $PID; ?>">
                        <input type="hidden" name="wd_id"  id="wd_id" value="">
                        <input type="hidden" name="Frequency" id="Frequency" value="">
                        <input type="hidden" name="Dose" id="Dose" value="">
                        <input type="hidden" name="HowLong" id="HowLong" value="">
                        <input type="hidden" name="DoseComment" id="DoseComment" value="">
                        <input type="hidden" name="drug_stock_id" id="drug_stock_id" value="<?php echo $stock_info["drug_stock_id"];?>">
                        <input type="text" id="txtBox">
<?php
echo '<input type="hidden" name="choose_method" id="choose_method" value="';
if (isset($user_info["last_prescription_cmd"])) {
    echo $user_info["last_prescription_cmd"];
} else {
    echo "by_group";
}
echo '" >';
?>	
                    </form>
                      <tr>
		<td><b>Previous prescription<b></td>
            </tr>
            <tr>
                <td width="800px">
                                
                                    <table class="" width="800px"  border=0 id="blk_4_content" >
                                        <tr id="blk_3_content" >
                                        
                                    </tr>
                                    </table>
                                
                            </td>
            </tr>
           
                </td>
            </tr>
        </table>
    </div>	
</div>

<div class="col-md-2" >

    <!-- ALLERGY-->
    <div class="panel  panel-danger"  style="padding:2px;margin-bottom:1px;" >
        <div class="panel-heading" ><b>Allergies</b></div>
<?php
//print_r($patient_allergy_list);
if ((!isset($patient_allergy_list)) || (empty($patient_allergy_list))) {
    echo " - NO DATA - ";
} else {
    echo '<table class="table table-condensed"  style="font-size:0.95em;margin-bottom:0px;">';
    for ($i = 0; $i < count($patient_allergy_list); ++$i) {
        echo '<tr >';
        echo '<td>';
        echo $patient_allergy_list[$i]["Name"];
        echo '</td>';
        echo '<td>';
        if ($patient_allergy_list[$i]["Status"] == "Current") {
            echo '<span class="label label-danger">' . $patient_allergy_list[$i]["Status"] . '</span>';
        } else {
            echo '<span class="label label-warning">' . $patient_allergy_list[$i]["Status"] . '</span>';
        }
        echo '</td>';
        echo '<td>';
        echo $patient_allergy_list[$i]["Remarks"];
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}
?>
    </div>	
    <!-- END ALLERGY-->

</div>

</div>
</div>
<script language="javascript">



//$('#btn_by_name').click();

   

    window.onload = load_name();
    
//var index = $('#blk_drug_name_list').index($('#tabId'));
//$('#blk_drug_name_list').tabs('load', 0);
//$('#blk_drug_name').val('80');

    var current_drug = {
        'wd_id': null,
        'name': null,
        'formulation': null,
        'frequency': null,
        'period': null
    };



    var json_dosage = jQuery.parseJSON('<?php echo $json_dosage;  ?>');
    var drug_list = new Array();
    var favdrug_list=new Array();
    
    function AddDoseComment() {
    var comment = prompt("Please enter your Comment", "");
    if (comment != null) {

 current_drug.comment = comment;

    }
}

    function disable_fav() {

//document.getElementById("blk_favdrug_name_list").disabled = true; 

       
        $('#blk_favdrug_name').hide();
        $('#blk_favdrug_name_list').hide();


    }

    function disable_drugname() {

//document.getElementById("blk_favdrug_name_list").disabled = true; 

        
        $('#blk_drug_name').hide();
        $('#blk_drug_name_list').hide();
        //var favval=$("blk_favdrug_name_list").val();
        //alert(favval);
        


    }

    function load_group() {
        current_drug = {
            'wd_id': null,
            'name': null,
            'formulation': null,
            'frequency': null,
            'period': null
        };
        $("#choose_method").val("by_group");
        $("#btn_by_group").removeClass("btn-primary").addClass("btn-success");
        $("#btn_by_name").removeClass("btn-success");
        $("#btn_by_favour").removeClass("btn-success");
        $("#btn_by_previous").removeClass("btn-success");
        $("#add_cont").html('');
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/lookup/get_drugs_groups/",
            type: "post"
        });
        request.done(function (response, textStatus, jqXHR) {
            var data = eval('(' + response + ')');
            if (data.length > 0) {
                $("#blk_2_content").html('<td id="blk_group" width=1%></td>');
                $("#blk_group").append('<b>Group</b><br>');
                $("#blk_group").append('<select class="input" id="blk_group_list" onchange=load_sub_group(this.value) size=15 style="height:300px;width:200px;"></select>');
                try {
                    for (var i = 0; i < data.length; i++) {
                        $("#blk_group_list").append('<option value="' + data[i]["wd_id"] + '"  title="' + data[i]["group"] + '">' + data[i]["group"] + '</option>');
                    }
                } catch (e) {
                }

            }
        });
    }
    function load_sub_group(id) {
   
        current_drug = {
            'wd_id': null,
            'name': null,
            'formulation': null,
            'frequency': null,
            'period': null
        };

        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/lookup/get_drugs_sub_groups/" + id,
            type: "post"
        });
        request.done(function (response, textStatus, jqXHR) {
            var data = eval('(' + response + ')');
            //if (data.length>0){
            if (!$("#blk_sub_group").get(0)) {
                $("#blk_2_content").append('<td id="blk_sub_group" width=1%></td>');
            }
            $("#blk_sub_group").html('');
            $("#blk_drug_name").html('');
            $("#blk_drug_formulation_list").html('');
            $("#add_cont").html('');
            $("#blk_sub_group").append('<b>Sub group</b><br>');
            $("#blk_sub_group").append('<select class="input" id="blk_sub_group_list" onchange=load_drug_name(this.value) size=15 style="height:300px;width:180px;"></select>');
            $("#blk_sub_group_list").html('');
            try {
                for (var i = 0; i < data.length; i++) {
                    $("#blk_sub_group_list").append('<option value="' + data[i]["wd_id"] + '"  title="' + data[i]["sub_group"] + '">' + data[i]["sub_group"] + '</option>');
                }
            } catch (e) {
            }
            //}
        });
    }

   function load_drug_name(id) {
        current_drug = {
            'wd_id': null,
            'name': null,
            'dose': null,
            'formulation': null,
            'frequency': null,
            'period': null
        };
        PRES_ID = null;
        var stock_id = $("#drug_stock_id").val();
        $("#selected_sub_group").html($("#blk_sub_group_list option:selected").text());
        ;
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/lookup/get_drug_name",
            type: "post",
            data: {"id": id, "drug_stock_id": stock_id}
        });
        request.done(function (response, textStatus, jqXHR) {
            var data = eval('(' + response + ')');
            drug_list = data;
            //if (data.length>0){
            if (!$("#blk_drug_name").get(0)) {
                $("#blk_2_content").append('<td id="blk_drug_name" width=1% ></td>');
            }
            $("#blk_drug_name").html('');
            $("#blk_drug_formulation_list").html('');
            $("#blk_drug_name").append('<b>Name</b><div id="selected_name" class="selected"><div>');
            $("#add_cont").html('');
            if ($("#choose_method").val() == "by_name") {
                $("#blk_drug_name").append('<select class="input" id="blk_drug_name_list" onchange=select_formulation(this.value) onclick=disable_fav() onkeypress=disable_fav() size=15 style="height:300px;width:375px;" autofocus></select>');
                           } else {
              
                $("#blk_drug_name").append('<select class="input"  id="blk_drug_name_list" onchange=select_formulation(this.value) onclick=disable_fav() size=15 style="height:300px;width:375px;" ></select>');
            }
            $("#blk_drug_name_list").html('');
            try {
                for (var i = 0; i < data.length; i++) {
                    var option = '<option value="' + data[i]["wd_id"] + '" ';
                    var drug_level = parseInt("+<?php echo $this->config->item('drug_alert_count'); ?>");

                    if (data[i]["who_drug_count"] <= drug_level) {
                        option += ' style="color:red;font-size:16px" ';
                        option += 'title="' + data[i]["name"] + ' ' + data[i]["formulation"] + ' (Not in Stock)"';
                        option += '>' + data[i]["name"] + '-' + data[i]["formulation"] + '-' +' </option>';//+'-'+data[i]["default_num"]
                    } else {
                        option += ' style="color:blue;font-size:16px" ';
                        option += 'title="' + data[i]["name"] + ' (' + data[i]["who_drug_count"] + ')"';
                        option += '>' + data[i]["name"] + '-' + data[i]["formulation"] +' (' + data[i]["who_drug_count"] + ')</option>';//+'-'+data[i]["default_num"]
                    }
                    //'/'+data[i]["who_drug_count"]+'/'+drug_level+
                    //option += '>'+data[i]["name"]+' ('+data[i]["who_drug_count"]+ ')</option>';
                    $("#blk_drug_name_list").append(option);
                }
            } catch (e) {
            }
            
            load_previous_bottom(<?php echo $clinic_patient_info["clinic"];?>);
        });
    }

    function load_favourite(id) {  //load favourite drug name by individually
        current_drug = {
            'wd_id': null,
            'name': null,
            'dose': null,
            'formulation': null,
            'frequency': null,
            'period': null
        };
        PRES_ID = null;
        var stock_id = $("#drug_stock_id").val();
       // $("#selected_sub_group").html($("#blk_sub_group_list option:selected").text());
        ;
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/lookup/get_favdrug_name",
            type: "post",
            data: {"id": id, "drug_stock_id": stock_id}
        });
       
        request.done(function (response, textStatus, jqXHR) {
            var data = eval('(' + response + ')');
            favdrug_list = data;
            //if (data.length>0){
            if (!$("#blk_drug_name").get(0)) {
                $("#blk_2_content").append('<td id="blk_favdrug_name" width=0.5% ></td>');
            }
            $("#blk_favdrug_name").html('');
            $("#blk_favdrug_formulation_list").html('');
            $("#blk_favdrug_name").append('<b>My Favourite</b><div id="selected_favname" class="selected"><div>');
            $("#add_cont").html('');
            if ($("#choose_method").val() == "by_name") {
                $("#blk_favdrug_name").append('<select  class="input" id="blk_favdrug_name_list" onchange=select_formulation(this.value) onclick=disable_drugname() size=15 style="height:300px;width:400px;"></select>');
            } else {
                $("#blk_drug_name").append('<select class="input"  id="blk_favdrug_name_list"  onchange=select_formulation(this.value) onclick=disable_drugname() size=15 style="height:300px;width:400px;"></select>');
            }
            $("#blk_drug_name_list").html('');
            try {
                for (var i = 0; i < data.length; i++) {
                    var option = '<option value="' + data[i]["wd_id"] + '" ';
                    var drug_level = parseInt("+<?php echo $this->config->item('drug_alert_count'); ?>");

                    if (data[i]["who_drug_count"] <= drug_level) {
                        option += ' style="color:red;font-size:16px"';
                        option += 'title="' + data[i]["name"] + ' ' + data[i]["formulation"] + ' (Not in Stock)"';
                        option += '>' + data[i]["name"] + '-' + data[i]["formulation"] +' </option>';//+'-'+data[i]["default_num"]
                    } else {
                        option += ' style="color:blue;font-size:16px"';
                        option += 'title="' + data[i]["name"] + ' (' + data[i]["who_drug_count"] + ')"';
                        option += '>' + data[i]["name"] + '-' + data[i]["formulation"] + ' (' + data[i]["who_drug_count"] + ')</option>';//+'-'+data[i]["default_num"]
                    }
                    //'/'+data[i]["who_drug_count"]+'/'+drug_level+
                    //option += '>'+data[i]["name"]+' ('+data[i]["who_drug_count"]+ ')</option>';
                    $("#blk_favdrug_name_list").append(option);
                }
            } catch (e) {
            }
            //}
            load_drug_name("");
            $('#txtBox').hide();
        });

    }

    function load_formulation(id) {
        current_drug.wd_id = id;
        //if(document.getElementById("blk_drug_name_list").style.visibility="visible"){current_drug.name = $("#blk_drug_name_list option:selected").text();}
        //else if(document.getElementById("blk_favdrug_name_list").style.visibility="visible"){
        var dname = $("#blk_drug_name_list option:selected").text();
        if(dname==''){ current_drug.name=$("#blk_favdrug_name_list option:selected").text();}
        else{current_drug.name=$("#blk_drug_name_list option:selected").text();}
        
        current_drug.formulation = null;
        current_drug.frequency = null;
        current_drug.dose = null;
        current_drug.period = null;
        current_drug.comment = null;
        var PRES_ID = null;

        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/lookup/get_formulation/" + id,
            type: "post"
        });
        request.done(function (response, textStatus, jqXHR) {
            var data = eval('(' + response + ')');
            //if (data.length>0){
            if (!$("#blk_drug_formulation").get(0)) {
                $("#blk_2_content").append('<td id="blk_drug_formulation" width=1%></td>');
            }
            $("#blk_drug_formulation").html('');
            $("#blk_drug_formulation").append('<b>Formulation</b><br>');
            $("#blk_drug_formulation").append('<select class="input" id="blk_drug_formulation_list" onchange=select_formulation(this.value) size=15 style="height:300px;width:150px;"></select>');
            $("#blk_drug_formulation_list").html('');
            //$("#add_cont").html('');
            try {
                for (var i = 0; i < data.length; i++) {
                    $("#blk_drug_formulation_list").append('<option value="' + data[i]["wd_id"] + '" title="' + data[i]["formulation"] + '">' + data[i]["formulation"] + '</option>');
                }
            } catch (e) {
            }
            //}

        });
        //enable_button();
    }


    function select_formulation(wd_id) {
    
        current_drug.formulation = $("#blk_drug_formulation_list option:selected").text();
        current_drug.name = $("#blk_drug_name_list option:selected").text();
        if(current_drug.name=='')current_drug.name = $("#blk_favdrug_name_list option:selected").text();
        current_drug.wd_id = wd_id;
        //alert(wd_id);
      //alert(current_drug.name);
        //return false;
        current_drug.dose = null;
        current_drug.frequency = null;
        current_drug.period = null;
        current_drug.comment = null;
       
        $("#selected_name").html($("#blk_drug_name_list option:selected").text());
        
        $("#selected_favname").html($("#blk_favdrug_name_list option:selected").text());
        
        
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/lookup/get_frequency",
            type: "post"
        });
         
        load_dose();
        load_DoseComment();
        request.done(function (response, textStatus, jqXHR) {
            var data = eval('(' + response + ')');
            //if (data.length>0){
            if (!$("#blk_drug_fq").get(0)) {
                $("#blk_2_content").append('<td id="blk_drug_fq" width=1%></td>');
            }
            $("#blk_drug_fq").html('');
            //$("#add_cont").html('');
            $("#blk_drug_fq").append('<b>Frequency</b><div id="selected_frequency" class="selected"><div>');
            $("#blk_drug_fq").append('<select class="input" id="blk_drug_fq_list" onchange=select_fq(this.value) size=15 style="height:300px;width:100px;"></select>');
            $("#blk_drug_fq_list").html('');
            try {
                for (var i = 0; i < data.length; i++) {
                    $("#blk_drug_fq_list").append('<option value="' + data[i]["Frequency"] + '" title="' + data[i]["Frequency"] + '">' + data[i]["Frequency"] + '</option>');
                }
            } catch (e) {
            }

            load_period(wd_id);
           
            //}
        });

        enable_button();
        check_alergy_alert(wd_id);
    }

    function set_default_value(wid) {
    
      //alert(drug_list.length);
        if (drug_list.length > 0) {
          
         
            for (i in drug_list) {
             
                if (drug_list[i].wd_id == wid) {
                    
                    if (drug_list[i].default_dcomment == null){
                    default_dcomment="As usual";
                         }
                    else{
                     default_dcomment=drug_list[i].default_dcomment;   
                        
                    }
                    
                    $("#blk_drug_dose_list").val(drug_list[i].default_num);
                    $("#blk_drug_period_list").val(drug_list[i].DPeriod);
                    $("#blk_drug_comment_list").val(default_dcomment);
                    $("#blk_drug_fq_list").val(drug_list[i].default_timing);


                    current_drug.dose = drug_list[i].default_num;
                    current_drug.frequency = drug_list[i].default_timing;
                    current_drug.period = drug_list[i].DPeriod;
                    current_drug.comment = default_dcomment;
                    update_form();

                    $("#selected_period").html(current_drug.period);
                    $("#selected_comment").html(current_drug.comment);
                    $("#selected_frequency").html(current_drug.frequency);
                    $("#selected_dose").html(current_drug.dose);
                    //$("#blk_drug_fq_list").filter(function() {
                    //	return $(this).text()=="per day"; 
                    //}).prop('selected', true);
                }
               
            }
        }
       
    }
    
    function set_favdefault_value(wid) {
      
        if (favdrug_list.length > 0) {
           
            for (i in favdrug_list) {
             
                if (favdrug_list[i].wd_id == wid) {
                   
                     if (drug_list[i].default_dcomment == null){
                    default_dcomment="As usual";
                         }
                    else{
                     default_dcomment=drug_list[i].default_dcomment;   
                        
                    }
                    
                    $("#blk_drug_dose_list").val(drug_list[i].default_num);
                    $("#blk_drug_period_list").val(drug_list[i].DPeriod);
                    $("#blk_drug_comment_list").val(default_dcomment);
                    $("#blk_drug_fq_list").val(drug_list[i].default_timing);


                    current_drug.dose = favdrug_list[i].default_num;
                    current_drug.frequency = favdrug_list[i].default_timing;
                    current_drug.period = drug_list[i].DPeriod;
                    current_drug.comment = default_dcomment;
                    update_form();

                    $("#selected_period").html(current_drug.period);
                    $("#selected_comment").html(current_drug.comment);
                    $("#selected_frequency").html(current_drug.frequency);
                    $("#selected_dose").html(current_drug.dose);
                    //$("#blk_drug_fq_list").filter(function() {
                    //	return $(this).text()=="per day"; 
                    //}).prop('selected', true);
                }
               
            }
        }
       
    }


    function load_dose() {
        current_drug.dose = null;
        var period = new Array();
        var period_val = new Array();

        if ((String(current_drug.name).search("-Tablet") > 0) || (String(current_drug.name).search("-Capsule") > 0)) {
            if (json_dosage){
             for (var i in json_dosage){
             if (json_dosage[i]["Type"] == "Tablet"){
             period.push(json_dosage[i]["Dosage"]);
             period_val.push(json_dosage[i]["Factor"]);
             }
             }
             }
        } 
             else if ((String(current_drug.name).search("-Liquid") > 0)) {
                
            if (json_dosage){
             for (var i in json_dosage){
             if (json_dosage[i]["Type"] == "Liquid"){
             period.push(json_dosage[i]["Dosage"]);
             period_val.push(json_dosage[i]["Factor"]);
             }
             }
             }
        }
        
        else {
            
            if (json_dosage){
             for (var i in json_dosage){
             if (json_dosage[i]["Type"] != "Tablet" && json_dosage[i]["Type"] != "Liquid"){
             period.push(json_dosage[i]["Dosage"]);
             period_val.push(json_dosage[i]["Factor"]);
             }
             }
             } 
        }

        if (!$("#blk_drug_dose").get(0)) {
            $("#blk_2_content").append('<td id="blk_drug_dose" width=1%></td>');

        }
        $("#blk_drug_dose").html('');
        $("#blk_drug_dose").append('<b>Dose</b><div id="selected_dose" class="selected">');
        $("#blk_drug_dose").append('<select class="input" id="blk_drug_dose_list" onchange=select_dose(this.value) size=15 style="height:300px;width:80px;"></select>');
        $("#blk_drug_period_list").html('');
        $("#blk_drug_comment_list").html('');
        $("#add_cont").html('');
        try {
            for (var i = 0; i < period.length; i++) {
                $("#blk_drug_dose_list").append('<option value="' + period[i] + '" title="' + period[i] + '">' + period[i] + '</option>');
            }
        } catch (e) {
        }
       
        enable_button();
    }


    function load_period(wd_id) {
     
       current_drug.period = null;
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/lookup/get_period",
            type: "post"
        });
        
          request.done(function (response, textStatus, jqXHR) {
        
         var data = eval('(' + response + ')');
           //if (data.length>0){
            //var period = new Array('For 1 day', 'For 2 days', 'For 3 days');
            if (!$("#blk_drug_period").get(0)) {
            $("#blk_2_content").append('<td id="blk_drug_period" width=1%></td>');
        }
        $("#blk_drug_period").html('');
        $("#blk_drug_period").append('<b>Period</b><div id="selected_period" class="selected">');
        $("#blk_drug_period").append('<select class="input" id="blk_drug_period_list" onchange=select_period(this.value) size=15 style="height:300px;width:90px;"></select>');
        $("#blk_drug_period_list").html('');
            try {
                for (var i = 0; i < data.length; i++) {
                    $("#blk_drug_period_list").append('<option value="' + data[i]["Period"] + '" title="' + data[i]["Period"] + '">' + data[i]["Period"] + '</option>');
                  // $("#blk_drug_period_list").append('<option value="' + period[i] + '" title="' + period[i] + '">' + period[i] + '</option>');
                }
            } catch (e) {
            }
            if($("#blk_favdrug_name_list option:selected").text()=='') set_default_value(wd_id);
            if($("#blk_drug_name_list option:selected").text()=='') set_favdefault_value(wd_id);
             });
              
       
    }
    
     function load_DoseComment() {
        /*current_drug.comment = null;
        var period = new Array('Before Meal', 'After Meal','As usual');
        if (!$("#blk_drug_comment").get(0)) {
            $("#blk_2_content").append('<td id="blk_drug_comment" width=1%></td>');
        }
        $("#blk_drug_comment").html('');
        $("#blk_drug_comment").append('<b>Dose Comment</b><div id="selected_comment" class="selected">');
        $("#blk_drug_comment").append('<select class="input" id="blk_drug_comment_list" onchange=select_comment(this.value) size=15 style="height:300px;width:100px;"></select>');
        $("#blk_drug_comment_list").html('');
        $("#add_cont").html('');
        try {
            for (var i = 0; i < period.length; i++) {
                $("#blk_drug_period_list").append('<option value="' + period[i] + '" title="' + period[i] + '">' + period[i] + '</option>');
            }
        } catch (e) {
        }
        enable_button();
        */
        current_drug.comment = null;
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/lookup/get_DoseComment",
            type: "post"
        });
        
          request.done(function (response, textStatus, jqXHR) {
        
         var data = eval('(' + response + ')');
           //if (data.length>0){
            //var period = new Array('For 1 day', 'For 2 days', 'For 3 days');
            if (!$("#blk_drug_comment").get(0)) {
            $("#blk_2_content").append('<td id="blk_drug_comment" nowrap></td>');
        }
        $("#blk_drug_comment").html('');
        $("#blk_drug_comment").append('<b>Dose Comment</b><div id="selected_comment" class="selected">');
        $("#blk_drug_comment").append('<select class="input" id="blk_drug_comment_list" onchange=select_comment(this.value) size=15 style="height:300px;width:90px;"></select>');
        $("#blk_drug_comment_list").html('');
            try {
                for (var i = 0; i < data.length; i++) {
                    $("#blk_drug_comment_list").append('<option value="' + data[i]["Comment"] + '" title="' + data[i]["Comment"] + '">' + data[i]["Comment"] + '</option>');
                  // $("#blk_drug_period_list").append('<option value="' + period[i] + '" title="' + period[i] + '">' + period[i] + '</option>');
                }
                $("#blk_drug_comment_list").append('<option value="Custom" title="">' + 'Custom' + '</option>');
            } catch (e) {
            }
          enable_button();
             });
               
         
         
        
    }


    function select_fq(fq_id) {
        current_drug.frequency = $("#blk_drug_fq_list option:selected").text();
        $("#selected_frequency").html($("#blk_drug_fq_list option:selected").text());
        ;
        update_form();
    }

    function select_dose(dose_id) {
        current_drug.dose = $("#blk_drug_dose  option:selected").val();
        $("#Dose").val(current_drug.dose);
        update_form();
    }

    function select_period(period) {
        current_drug.period = $("#blk_drug_period_list option:selected").text();
        $("#selected_period").html($("#blk_drug_period_list option:selected").text());
        update_form();
    }
     function select_comment(comment) {
        current_drug.comment = $("#blk_drug_comment_list option:selected").text();
        $("#selected_comment").html($("#blk_drug_comment_list option:selected").text());
        if($("#blk_drug_comment_list option:selected").text()=='Custom'){
           AddDoseComment();
                   }
        enable_button();
    }


    function enable_button() {
        var prescribe_text = 'ADD: ';
        prescribe_text += current_drug.name;
        //alert(prescribe_text);
        if (current_drug.formulation) {
            prescribe_text += ' / ' + current_drug.formulation;
        }

        if (current_drug.frequency) {
            prescribe_text += ' / ' + current_drug.frequency;
        }
        if (current_drug.period) {
            prescribe_text += ' / ' + current_drug.period + ' ? ';
        }
         if (current_drug.comment) {
            prescribe_text += ' / ' + current_drug.comment + ' ? ';
        }
        if ($("#add_btn").length == 0) {
            //$("#blk_2_content").append('<button type="button" class="btn btn-primary btn-lg btn-block" onclick=$("#drug_form").submit(); id="add_btn" >+Add</button>')
        }
        $("#add_cont").html('<button type="button" class="btn btn-primary btn-lg btn-block" onclick=$("#drug_form").submit(); >+ADD</button>');
        if (PRES_ID > 0) {
            // $("#add_cont").append('<button type="button" class="btn btn-primary btn-lg btn-block" onclick=prescribe_all("' + PRES_ID + '"); >+Repeat All</button>');
        }
        update_form();
    }

    function update_form() {
        $("#Frequency").val(current_drug.frequency);
        $("#HowLong").val(current_drug.period);
        $("#DoseComment").val(current_drug.comment);
        $("#Dose").val(current_drug.dose);
        var fav_val=$("#blk_favdrug_name_list").val();
        if(fav_val>0){
        $("#wd_id").val(fav_val);
    }
    else{
        $("#wd_id").val($("#blk_drug_name_list").val());
        
    }      

        $("#selected_period").html(current_drug.period);
        $("#selected_comment").html(current_drug.comment);
        $("#selected_frequency").html(current_drug.frequency);
        $("#selected_dose").html(current_drug.dose);
            }

    function load_name() {
    
        $("#choose_method").val("by_name");
        $("#btn_by_name").removeClass("btn-primary").addClass("btn-success");
        $("#btn_by_group").removeClass("btn-success");
        $("#btn_by_favour").removeClass("btn-success");
        $("#btn_by_previous").removeClass("btn-success");
        $("#blk_2_content").html('');
        load_favourite("");
        //load_drug_name("");
    }

    function load_favour() {
        $("#choose_method").val("by_favour");
        $("#btn_by_favour").removeClass("btn-primary").addClass("btn-success");
        $("#btn_by_name").removeClass("btn-success");
        $("#btn_by_group").removeClass("btn-success");
        $("#btn_by_previous").removeClass("btn-success");
        $("#blk_2_content").html('');
        PRES_ID = null;
        current_drug = {
            'wd_id': null,
            'name': null,
            'formulation': null,
            'frequency': null,
            'period': null
        };
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/user/get_my_favour/",
            type: "post"
        });
        request.done(function (response, textStatus, jqXHR) {
            var data = eval('(' + response + ')');
            drug_list = data;
            if (data.length > 0) {
                $("#blk_2_content").html('<td id="blk_group" width=1%></td>');
                $("#blk_group").append('<b>My favourites</b><br>');
                $("#blk_group").append('<select class="input" id="blk_group_list" onclick=load_favour_drug_item(this.value) size=15 style="height:300px;width:200px;"></select>');
                try {
                    for (var i = 0; i < data.length; i++) {

                        $("#blk_group_list").append('<option value="' + data[i]["user_favour_drug_id"] + '"  title="' + data[i]["name"] + '">' + data[i]["name"] + '</option>');
                    }
                } catch (e) {
                }

            }
        });
    }

    function load_previous(vid) {
  
        $("#choose_method").val("by_previous");
        $("#btn_by_previous").removeClass("btn-primary").addClass("btn-success");
        $("#btn_by_name").removeClass("btn-success");
        $("#btn_by_group").removeClass("btn-success");
        $("#btn_by_favour").removeClass("btn-success");
        $("#blk_2_content").html('');
        current_drug = {
            'wd_id': null,
            'name': null,
            'formulation': null,
            'frequency': null,
            'period': null
        };
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/clinic/get_previous_prescription_for_patient/" + vid + "/" + $("#PID").val(),
            type: "post"
        });
        request.done(function (response, textStatus, jqXHR) {
            var data = eval('(' + response + ')');
            drug_list = data;
            
        if (data.length > 0) {
                $("#blk_2_content").html('<td id="blk_group" width=1%></td>');
                $("#blk_group").append('<b>Previous prescription</b><br>');
                $("#blk_group").append('<select class="input" id="blk_drug_name_list" onchange=select_formulation(this.value) size=15 style="height:300px;width:400px;"></select>');
                $("#blk_drug_name_list").html('');
               
                try {
                    CPRSID = null;
                    for (var i = 0; i < data.length; i++) {
                        CPRSID = data[i]["CPRSID"];
                        //alert(PRES_ID);
                        var option = '<option value="' + data[i]["wd_id"] + '" ';
                        var drug_level = parseInt("+<?php echo $this->config->item('drug_alert_count'); ?>");

                        if (data[i]["who_drug_count"] <= drug_level) {
                            option += ' style="color:red" ';
                            option += 'title="' + data[i]["name"] + ' (Not in Stock)"';
                            option += '>' + data[i]["name"] + '-' + data[i]["formulation"] +  '</option>';
                        } else {
                            option += ' style="color:blue" ';
                            option += 'title="' + data[i]["name"] + '"';
                            option += '>' + data[i]["name"] + '-' + data[i]["formulation"] + '</option>';
                        }
                        //'/'+data[i]["who_drug_count"]+'/'+drug_level+
                        //option += '>'+data[i]["name"]+'</option>';
                        $("#blk_drug_name_list").append(option);
                        //$("#blk_drug_name_list").append('<option value="'+data[i]["who_drug_id"]+'"  title="'+data[i]["name"]+'">'+data[i]["name"]+'</option>');
                    }
                    $("#add_cont").html('<button type="button" class="btn btn-primary btn-lg btn-block" onclick=prescribe_all("' + CPRSID + '"); >+Repeat All</button>');
                } catch (e) {
                }

            }
        });
    }
    
    function load_previous_bottom(vid) {
             
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/clinic/get_previous_prescription_for_patient/" + vid + "/" + $("#PID").val(),
            type: "post"
        });
        
            
     
          var request2 = $.ajax({
            url: "<?php echo base_url(); ?>index.php/lookup/get_period",
            type: "post"
        });
        
        
        request.done(function (response, textStatus, jqXHR) {
                 request2.done(function (response2, textStatus, jqXHR) {
      
         var data2 = eval('(' + response2 + ')');
            if (!$("#pre_drug").get(0)) {
            $("#blk_3_content").append('<td id="pre_drug" width="200px"></td>');
            $("#blk_3_content").append('<td id="pre_dose" width="100px"></td>');
            $("#blk_3_content").append('<td id="pre_dose_cmnt" width="150px"></td>');
            $("#blk_3_content").append('<td id="pre_frequency" width="100px"></td>');
            $("#blk_3_content").append('<td id="pre_period" width="150px"></td>');
            $("#blk_3_content").append('<td id="pre_continue" width="200px"></td>');
        }
        
        var data = eval('(' + response + ')');
                
        $("#pre_drug").html('');
        $("#pre_drug").append('<b>Drug</b>');
      
        $("#pre_dose").html('');
        $("#pre_dose").append('<b>Dose</b>');
       
        $("#pre_dose_cmnt").html('');
        $("#pre_dose_cmnt").append('<b>Comment</b>');
        
        $("#pre_frequency").html('');
        $("#pre_frequency").append('<b>Frequency</b>');
        
        $("#pre_period").html('');
        $("#pre_period").append('<b>Period</b>');
         
        $("#pre_continue").html('');
        $("#pre_continue").append('<b></b>');
        $("#pre_continue").append('<button type="button" style="background-color: #008CBA;font-size: 14px;color: white;" onclick=prescribe_all("' + data[0]["CPRSID"] + '"); >+Repeat All</button>');
        
               
            try {
                for (var i = 0; i < data.length; i++) {
                  
                      
                     $("#blk_4_content").append('<tr id="pre_drug_list' + i + '" nowrap>');
                     
                                         
                     $("#pre_drug_list"+ i).append('<td id="pre_drug_name' + i + '" nowrap></td>');
                     $("#pre_drug_name"+ i).html('');
                     $("#pre_drug_name"+ i).append( data[i]["name"]) ;
                     
                                   
                     $("#pre_drug_list"+ i).append('<td id="pre_dose_list' + i + '" nowrap></td>');
                     $("#pre_dose_list"+ i).html('');
                     $("#pre_dose_list"+ i).append( data[i]["Dosage"]) ;
                     
                     $("#pre_drug_list"+ i).append('<td id="pre_dose_cmnt_list' + i + '" nowrap></td>');
                     $("#pre_dose_cmnt_list"+ i).html('');
                     $("#pre_dose_cmnt_list"+ i).append( data[i]["DoseComment"]) ;
                     
                     $("#pre_drug_list"+ i).append('<td id="pre_frequency_list' + i + '" nowrap></td>');
                     $("#pre_frequency_list"+ i).html('');
                     $("#pre_frequency_list"+ i).append(data[i]["Frequency"]) ;
                     
                     $("#pre_drug_list"+ i).append('<td id="pre_period_list' + i + '" nowrap></td>');
                     $("#pre_period_list"+ i).html('');
                     $("#pre_period_list"+ i).append('<select class="input" id="pre_period_edit_list' + i +'" onchange=update_period('+ data[i]["PRES_ID"] +',this.value)></select>');
                     for (var j = 0; j < data2.length; j++) {
                     $("#pre_period_edit_list"+ i).append('<option val="data2[j]["Period"]">' + data2[j]["Period"] + '</option>');

                 }
                 $("#pre_period_edit_list"+ i).val(data[i]["HowLong"]);
                 
                                   
                     $("#pre_drug_list"+ i).append('<td id="pre_continue_list' + i + '" nowrap></td>');
                     $("#pre_continue_list"+ i).html('');
                     $("#pre_continue_list"+ i).append('<td nowrap><span class="continue"  onmouseover="drugSelect('+ data[i]["wd_id"] +','+ data[i]["Dosage"] +',\'' + data[i]["DoseComment"] +'\',\'' + data[i]["Frequency"] +'\',\'' + data[i]["HowLong"] +'\')"   onclick=$("#drug_form").submit(); >Continue this</span></td>') ;
               
                     $("#blk_4_content").append('</tr>');
                    // PRES_ID = data[i]["PRES_ID"];
                
                }
            } catch (e) {
            }
           }); 
        });
    }
    
    function update_period(clinic_prs_id,new_period) {

       var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/clinic/update_period/" + clinic_prs_id + "/" + new_period,
            type: "post"
        });
    
    request.done(function (response, textStatus, jqXHR) {
                 
        
     });
    
    }
    
    function drugSelect(wd_id,dose,cmnt,frequency,period) {
      
    	$('#blk_drug_name_list option[value=' + wd_id + ']').attr('selected', true); 
        $('#blk_favdrug_name_list option[value=' + wd_id + ']').attr('selected', true);
      
                    $("#blk_drug_dose_list").val(dose);
                    $("#blk_drug_period_list").val(period);
                    $("#blk_drug_comment_list").val(cmnt);
                    $("#blk_drug_fq_list").val(frequency);
                    //alert ($("#blk_drug_period_list").val());


                    current_drug.dose = dose;
                    current_drug.frequency = frequency;
                    current_drug.period = period;
                    current_drug.comment = cmnt;
                    update_form();
                    
         }


    function load_favour_drug_item(favour_id) {
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/user/get_my_favour_drug_list/" + favour_id + "/" + $("#drug_stock_id").val(),
            type: "post"
        });
        request.done(function (response, textStatus, jqXHR) {
            var data = eval('(' + response + ')');
            drug_list=data;
            
            if (data.length > 0) {
                if (!$("#blk_sub_group").get(0)) {
                    $("#blk_2_content").append('<td id="blk_sub_group" width=1%></td>');
                }
                $("#blk_sub_group").html('<b>Drug List</b><br>');
                $("#blk_sub_group").append('<select class="input" id="blk_drug_name_list" onchange=select_formulation(this.value) size=15 style="height:300px;width:280px;"></select>');
                $("#blk_drug_name_list").html('');
                $("#blk_drug_dose").html('');
                $("#blk_drug_fq").html('');
                $("#blk_drug_period").html('');

                try {
                    for (var i = 0; i < data.length; i++) {
                        var option = '<option value="' + data[i]["wd_id"] + '" ';
                        var drug_level = parseInt("+<?php echo $this->config->item('drug_alert_count'); ?>");

                        if (data[i]["who_drug_count"] <= drug_level) {
                            option += ' style="color:red" ';
                            option += 'title="' + data[i]["name"] + ' Dose:' + data[i]["dosage"] + ', Frequency:' + data[i]["frequency"] + ',HowLong:' + data[i]["how_long"] + ' (Not in Stock)"';
                            option += '>' + data[i]["name"] + '-' + data[i]["formulation"] + '-' + data[i]["dosage"] + '</option>';
                        } else {
                            option += ' style="color:blue" ';
                            option += 'title="' + data[i]["name"] + ' Dose:' + data[i]["dosage"] + ', Frequency:' + data[i]["frequency"] + ',HowLong:' + data[i]["how_long"] + ')"';
                            option += '>' + data[i]["name"] + '-' + data[i]["formulation"] + '-' + data[i]["dosage"] + ' (' + data[i]["who_drug_count"] + ')</option>';
                        }
                        //'/'+data[i]["who_drug_count"]+'/'+drug_level+
                        //option += '>'+data[i]["name"]+'</option>';
                        $("#blk_drug_name_list").append(option);
                        //$("#blk_drug_name_list").append('<option value="'+data[i]["who_drug_id"]+'"  title="'+data[i]["name"]+'">'+data[i]["name"]+'</option>');
                    }
                    $("#add_cont").html('<button type="button" class="btn btn-primary btn-lg btn-block" onclick=prescribe_all_favour("' + favour_id + '",' + data.length + '); >+Prescribe All</button>');
                } catch (e) {
                }
            }
        })

    }

    function prescribe_all_favour(favour_id, count) {
        if (confirm("Do you want to prescribe all " + count + " drugs from this list?")) {
           
            var request = $.ajax({
                url: "<?php echo base_url(); ?>index.php/clinic/prescribe_all_favour/" + favour_id + "/" + $("#PID").val() + "/" + $("#clinic_patient_id").val(),
                type: "post"
            });
            request.done(function (response, textStatus, jqXHR) {
                
                if (response > 0) {
                    self.document.location = "<?php echo base_url(); ?>index.php/clinic/prescription/" + response + "?CONTINUE=clinic/view/" + $("#clinic_patient_id").val();
                }
            });
        }
    }
    function add_to_favour(id) {
        if (id == "")
            return;
        
        var name = prompt("Name of the list?", $("#opd_complaint").html());
        if ($.trim(name) == "") {
            alert("List name invalid");
            return;
        }
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/clinic/prescription_add_favour/" + id,
            type: "post",
            data: {"name": name, "clinic_prescription_id": id}
        });
        request.done(function (response, textStatus, jqXHR) {
           
            if (response == 1) {
                location.reload();
            }
        });
    }
    function init() {
        $("#btn_by_group").click(function () {
            load_group();
        });
        $("#btn_by_name").click(function () {
            load_name();
        });
        $("#btn_by_favour").click(function () {
            load_favour();
        });
        $("#btn_by_previous").click(function () {
            
            load_previous(<?php echo $clinic_patient_info["clinic"];?>);
        });
        if ($("#choose_method").val() == "by_group") {
            load_group();
        } else if ($("#choose_method").val() == "by_name") {
            load_name();
        } else if ($("#choose_method").val() == "by_previous") {
            load_previous(<?php echo $clinic_patient_info["clinic"];?>);
        } else {
            load_favour();
        }

    }
    $(function () {
        init();

    });
    function prescribe_all(id) {
          
        if (id == "")
            return;
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/clinic/prescribe_all/" + id + "/" + $("#PID").val() + "/" + $("#clinic_patient_id").val(),
            type: "post"
        });
        request.done(function (response, textStatus, jqXHR) {
        
            if (response > 0) {
                self.document.location = "<?php echo base_url(); ?>index.php/clinic/prescription/" + response + "?CONTINUE=clinic/view/" + $("#clinic_patient_id").val();
            }
        });
    }
    function delete_record(id) {
        if (id == "")
            return;
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/clinic/prescription_item_delete/" + id,
            type: "post"
        });
        request.done(function (response, textStatus, jqXHR) {
            location.reload();
        });
    }

    function send_to_pharmacy(id, clnid) {
        if (id == "")
            return;
        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/clinic/prescription_send/" + id,
            type: "post"
        });
        request.done(function (response, textStatus, jqXHR) {
            if (response == 1) {
                location.reload();
            }
        });
    }
    
    var modal = document.getElementById('myModal');
    var span = document.getElementsByClassName("close")[0];
    
    function check_alergy_alert(wd_id){
            
            pid=$("#PID").val();
            var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/opd/check_alergy_alert",
            type: "post",
            data: {"drgid": wd_id, "pid": pid}
        });
        
        request.done(function (response, textStatus, jqXHR) {
            var data = eval('(' + response + ')');
            if (data.length>0){
            modal.style.display = "block";
            for (var i = 0; i < data.length; i++) {
            $("#allergy_msg").append('<p style="font-size:20px">Patient has  ' + data[i]["Name"] + '  in the list of allergies</p>');
        }
            }
        });
    
    }
    
    window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

span.onclick = function() {
    modal.style.display = "none";
}

$(document).keyup(function(e) {
     if (e.keyCode == 27) { // escape key maps to keycode `27`
        modal.style.display = "none";
    }
});
   

</script>