<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
 */


$jobTitleConfig = $records['jobTitleConfig'];
$assignedJobTitles = $records['AssignedJobTitles'];
$availableJobTitles = $records['AvailableJobTitles'];
$roleList = $records['roleList'];

$allowedRoles = array(JobTitleConfig::ROLE_REVIEW_APPROVER => $lang_Performance_Review_JobTitleApprovePerformanceReview);

?>
<link href="../../themes/<?php echo $styleSheet;?>/css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">@import url("../../themes/<?php echo $styleSheet;?>/css/style.css"); </style>
    
<style type="text/css">
@import url("../../themes/beyondT/css/octopus.css");

.roundbox {
	margin-top: 10px;
	margin-left: 10px;
	width:300px;
}

label {
	width: 80px;
}

.roundbox_content {
	padding:5px 5px 20px 5px;
}

#txtHoursPerDay {
	width: 2em;
}

#editPanel {
	display: block;
}
</style>
<script type="text/javascript" src="../../scripts/archive.js"></script>
<script type="text/javascript" src="../../scripts/octopus.js"></script>

<script type="text/javascript">
var baseUrl = '?perfcode=JobTitleConfig&action=';

function update() {
	selectAllOptions($('cmbAssignedJobTitles'));
	$('frmJobTitleConfig').action=baseUrl+'Update';
	$('frmJobTitleConfig').submit();
}

function assignJobTitle() {
	moveSelectOptions($('cmbAvailableJobTitles'), $('cmbAssignedJobTitles'), '<?php echo $lang_Performance_Measure_Error_NoJobTitleSelected; ?>');
}

function removeJobTitle() {
	moveSelectOptions($('cmbAssignedJobTitles'), $('cmbAvailableJobTitles'), '<?php echo $lang_Performance_Measure_Error_NoJobTitleSelected; ?>');
}


</script>
<h2><?php echo $lang_Performance_Review_JobTitleConfigTitle; ?></h2>
<hr/>
<div class="navigation">
<?php
if (isset($_GET['message']) && !empty($_GET['message'])) {

	$expString  = $_GET['message'];
	$col_def = CommonFunctions::getCssClassForMessage($expString);
	$expString = 'lang_Time_Errors_'.$expString;

	$message = isset($$expString) ? $$expString : CommonFunctions::escapeHtml($_GET['message']);
?>
	<font class="<?php echo $col_def?>" size="-1" face="Verdana, Arial, Helvetica, sans-serif">
<?php echo $message; ?>
	</font>
<?php }	?>
</div>
<div id="editPanel">
	<form name="frmJobTitleConfig" id="frmJobTitleConfig" method="post" action="?perfcode=PerfMeasure&action=">
		<div class="roundbox">
			<label for="cmbRole" style="width:auto;"><span class="error">*</span> <?php echo $lang_Performance_JobTitleRoleLabel; ?></label>
			<select style="width:auto;" name="cmbRole" id="cmbRole" >
		  		<?php 
		  			foreach ($allowedRoles as $role=>$roleDesc) {
		  				$selected = ($role == $jobTitleConfig->getRole()) ? 'selected' : ''; 
		  				echo "<option value='" . $role . "' $selected >" .$roleDesc. "</option>";
		  			} ?>
			</select>			  	        	
			<br/>
	   	</div><br />
        <img onClick="update();"
             onMouseOut="this.src='../../themes/beyondT/pictures/btn_save.gif';"
             onMouseOver="this.src='../../themes/beyondT/pictures/btn_save_02.gif';"
             src="../../themes/beyondT/pictures/btn_save.gif"
        >
		<script type="text/javascript">
		<!--
		    if (document.getElementById && document.createElement) {
		 			initOctopus();
			}
		 -->
		</script>
		<br /><br />
		<table border="0">
		<tr>
		   	<th width="100" style="align:center;"><?php echo $lang_Performance_Measure_AvailableJobTitles; ?></th>
			<th width="100"/>
		   	<th width="125" style="align:center;"><?php echo $lang_Performance_Measure_AssignedJobTitles; ?></th>
		</tr>
		<tr><td width="100" >
			<select size="10" id="cmbAvailableJobTitles" name="cmbAvailableJobTitles[]" style="width:125px;"
					multiple="multiple">
       			<?php

       				foreach($availableJobTitles as $jobTitle) {
       					$jobTitleCode = $jobTitle[0];
       					$name = $jobTitle[1];
           				echo "<option value='{$jobTitleCode}'>{$name}</option>";
       				}
				?>
			</select></td>
			<td align="center" width="100">
				<input type="button" name="btnassignJobTitle" id="btnassignJobTitle" onClick="assignJobTitle();" value=" <?php echo $lang_compstruct_add; ?> >" style="width:80%"><br><br>
				<input type="button" name="btnremoveJobTitle" id="btnremoveJobTitle" onClick="removeJobTitle();" value="< <?php echo $lang_Leave_Common_Remove; ?>" style="width:80%">
			</td>
			<td>
			<select size="10" name="cmbAssignedJobTitles[]" id="cmbAssignedJobTitles" style="width:125px;"
			        multiple="multiple">
       			<?php
       				foreach($assignedJobTitles as $jobTitle) {
       					$jobTitleCode = $jobTitle['jobtit_code'];
       					$name = $jobTitle['jobtit_name'];
           				echo "<option value='{$jobTitleCode}'>{$name}</option>";
       				}
				?>
			</select></td>
		</tr>

	</table>
  </form>
</div>
