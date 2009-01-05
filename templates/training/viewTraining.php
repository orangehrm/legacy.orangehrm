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

$training = $records['training'];
$authorizeObj = $records['authorizeObj'];
$trainingList = $records['trainingList'];
$assignedEmployees = $records['assignedEmployees'];
$availableEmployees = $records['availableEmployees'];

$trainingId = $training->getId();
$addMode = empty($trainingId);

$supervisorMode = false;
$requestMode = $addMode;

if ($authorizeObj->isSupervisor() && !$requestMode) {
	$supervisorMode = true;
}

if ($addMode) {
	$userDefinedId = Training::getSuggestedId();
} else {
	$userDefinedId = $training->getUserDefinedId();	
}

$styleDir = "../../themes/{$styleSheet}";
$picDir = $styleDir . "/pictures";
$cssDir = $styleDir . "/css";

$statusList = array(Training::STATE_REQUESTED => $lang_Training_Requested,
					Training::STATE_ARRANGED => $lang_Training_Arranged,
					Training::STATE_COMPLETED => $lang_Training_Completed);					    				  
?>
<link href="<?php echo $cssDir;?>/style.css" rel="stylesheet" type="text/css">
<style type="text/css">@import url("<?php echo $cssDir;?>/style.css"); </style>
    
<style type="text/css">
@import url("<?php echo $cssDir;?>/octopus.css");

.roundbox {
	margin-top: 10px;
	margin-left: 10px;
	width:500px;
}

label {
	width: 120px;
}

.roundbox_content {
	padding:5px 5px 20px 5px;
}

.button {
	width: 30px;
}

#txtHoursPerDay {
	width: 2em;
}

#editPanel {
	display: block;
}

.calendarBtn {
    width: auto;
    border-style: none !important;
    border: 0px !important;
}

.dateBtn {
    width: auto;
}
    
</style>
<script type="text/javascript" src="../../scripts/archive.js"></script>
<script type="text/javascript" src="../../scripts/octopus.js"></script>
<?php include ROOT_PATH."/lib/common/calendar.php"; ?>
<script type="text/javascript">
var baseUrl = '?trainingcode=Training&action=';

var idList = new Array();
<?php
	$lowerCaseTrainingId = strtolower($training->getUserDefinedId());
	foreach($trainingList as $item) {
		$itemId = strtolower($item->getUserDefinedId());
		if ($lowerCaseTrainingId != $itemId) {
	   		print "idList.push(\"{$itemId}\");\n";
		}
	}
?>

function goBack() {
	location.href = "./CentralController.php?trainingcode=Training&action=List";
}

function update() {
	err=false;
	msg='<?php echo $lang_Error_PleaseCorrectTheFollowing; ?>\n\n';

	userId = $('txtUserDefinedID').value.trim(); 
	if (userId == '') {
		err=true;
		msg+="\t- <?php echo $lang_Training_Error_PleaseSpecifyId; ?>\n";
	} else if (isIdInUse(userId)) {
		err = true;
		msg += "\t- <?php echo $lang_Training_IdInUse_Error; ?>\n";
	}	

	desc = $('txtDescription').value.trim(); 
	if (desc == '') {
		err=true;
		msg+="\t- <?php echo $lang_Training_Error_PleaseSpecifyDescription; ?>\n";
	}
	
	if (err) {
		alert(msg);
		return false;
	}

	selectAllOptions($('cmbAssignedEmployees'));
	$('frmTraining').action=baseUrl+'Update';
	$('frmTraining').submit();
}


function returnEmpDetail(){
		var popup=window.open('../../templates/hrfunct/emppop.php?reqcode=REP','Employees','height=450,width=400');
        if(!popup.opener) popup.opener=self;
		popup.focus();
}

function isIdInUse(id) {
	n = idList.length;
	lowerCaseId = id.toLowerCase();
	for (var i=0; i<n; i++) {
		if (idList[i] == lowerCaseId) {
			return true;
		}
	}
	return false;
}

function checkId() {
	id = trim($('txtUserDefinedID').value);
	oLink = $('messageCell');

	if (isIdInUse(id)) {
		oLink.innerHTML = "<?php echo $lang_Training_IdInUse_Error; ?>";
	} else {
		oLink.innerHTML = "&nbsp;";
	}
}

function assignEmployees() {
	moveSelectOptions($('cmbAvailableEmployees'), $('cmbAssignedEmployees'), '<?php echo $lang_Training_Error_NoEmployeeSelected; ?>');
}

function removeEmployees() {
	moveSelectOptions($('cmbAssignedEmployees'), $('cmbAvailableEmployees'), '<?php echo $lang_Training_Error_NoEmployeeSelected; ?>');
}


</script>
<h2>
<?php
	if ($addMode) {
		if ($authorizeObj->isSupervisor()) {
			$pageTitle = $lang_Training_RequestTrainingTitle;
		} else {
			$pageTitle = $lang_Training_AddTitle;
		}
	} else {
		$pageTitle = $lang_Training_EditTitle;
	}
	echo $pageTitle;
?>
</h2>
<hr/>
<div class="navigation">
<?php if (!($addMode && $authorizeObj->isSupervisor())) { ?>
	<img title="Back" onMouseOut="this.src='<?php echo $picDir; ?>/btn_back.gif';" onMouseOver="this.src='<?php echo $picDir; ?>/btn_back_02.gif';"  src="<?php echo $picDir; ?>/btn_back.gif" onClick="goBack();">
<?php } ?>
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
	<form name="frmEmp" id="frmTraining" method="post" action="?trainingcode=Training&action=">
		<div class="roundbox">

        	<label for="txtUserDefinedID"><span class="error">*</span> <?php echo $lang_Training_Id; ?></label>	  
        	<input type="text" name="txtUserDefinedID" id="txtUserDefinedID" <?php echo ($supervisorMode) ? 'readonly' : ''; ?>
        		value="<?php echo CommonFunctions::escapeHtml($userDefinedId); ?>"        		
				onkeyup="checkId();" />
            <div id="messageCell" class="error" style="display:block; float: left; margin:10px;">&nbsp;</div>     			        	
			<br />
			
        	<label for="txtDescription"><span class="error">*</span> <?php echo $lang_Training_Description; ?></label>	        	
        	<textarea id="txtDescription" name="txtDescription" <?php echo ($supervisorMode) ? 'readonly' : ''; ?>> <?php echo CommonFunctions::escapeHtml($training->getDescription()); ?></textarea>	        	
			<br />
<?php if (!$requestMode) { ?>						
        	<label for="txtTrainingCourse"><?php echo $lang_Training_TrainingCourse; ?></label>	  
        	<input type="text" name="txtTrainingCourse" id="txtTrainingCourse" <?php echo ($supervisorMode) ? 'readonly' : ''; ?>
        		value="<?php echo CommonFunctions::escapeHtml($training->getTrainingCourse()); ?>" />	
        	        	
			<br />

        	<label for="txtCost"><?php echo $lang_Training_Cost; ?></label>	  
        	<input type="text" name="txtCost" id="txtCost" <?php echo ($supervisorMode) ? 'readonly' : ''; ?>
        		value="<?php echo CommonFunctions::escapeHtml($training->getCost()); ?>" />	        	
			<br />

        	<label for="txtCompany"><?php echo $lang_Training_Company; ?></label>	
			<input type="text" name="txtCompany" id="txtCompany" <?php echo ($supervisorMode) ? 'readonly' : ''; ?> 
        		value="<?php echo CommonFunctions::escapeHtml($training->getCompany()); ?>" />	        	
			<br />        	        	

        	<label for="txtNotes"><?php echo $lang_Training_Notes; ?></label>	        	
        	<textarea id="txtNotes" name="txtNotes"  <?php echo ($supervisorMode) ? 'readonly' : ''; ?> > 
        		<?php echo CommonFunctions::escapeHtml($training->getNotes()); ?></textarea>	        	
			<br />
			
        	<label for="cmbState"><?php echo $lang_Training_State; ?></label>	        	
			<select name="cmbState" id="cmbState" >
		  		<?php 
		  			foreach ($statusList as $statusCode=>$statusDesc) {
		  				$selected = ($statusCode == $training->getState()) ? 'selected' : ''; 
		  				echo "<option value='" . $statusCode . "' $selected >" .$statusDesc. "</option>";
		  			} ?>
			</select>			  	        	
        	<br />
<?php } ?>			
	        <label for="none">&nbsp;</label>
	        <input type="hidden" id="txtId" name="txtId" value="<?php echo $training->getId(); ?>"/>
	   	</div><br />
	   	
        <img onClick="update();"
             onMouseOut="this.src='<?php echo $picDir; ?>/btn_save.gif';"
             onMouseOver="this.src='<?php echo $picDir; ?>/btn_save_02.gif';"
             src="<?php echo $picDir; ?>/btn_save.gif"
        >

		<script type="text/javascript">
		<!--
		    if (document.getElementById && document.createElement) {
		 			initOctopus();
			}
		 -->
		</script>
		
		<br /><br /><span><?php echo $lang_Training_AssignEmployees;?></span>
		<br /><br />
		<table border="0">
		<tr>
		   	<th width="130" style="align:center;"><?php echo $lang_Training_AvailableEmployees; ?></th>
			<th width="100"/>
		   	<th width="130" style="align:center;"><?php echo $lang_Training_AssignedEmployees; ?></th>
		</tr>
		<tr><td width="130" >
			<select size="10" id="cmbAvailableEmployees" name="cmbAvailableEmployees[]" style="width:125px;"
					<?php echo ($supervisorMode) ? 'readonly' : ''; ?>
					multiple="multiple">
       			<?php

       				foreach($availableEmployees as $available) {
       					$empNumber = $available['emp_number'];
       					$empName = $available['emp_name'];       					
           				echo "<option value='{$empNumber}'>{$empName}</option>";
       				}
				?>
			</select></td>
			<td align="center" width="100">
				<input type="button" name="btnassignEmployee" id="btnassignEmployee" onClick="assignEmployees();"
					<?php echo ($supervisorMode) ? 'disabled' : ''; ?>	 
					value=" <?php echo $lang_compstruct_add; ?> >" style="width:80%">
				<br><br>
				<input type="button" name="btnremoveEmployee" id="btnremoveEmployee" onClick="removeEmployees();"
					<?php echo ($supervisorMode) ? 'disabled' : ''; ?> 
					value="< <?php echo $lang_Leave_Common_Remove; ?>" style="width:80%">
			</td>
			<td width="130">
			<select size="10" name="cmbAssignedEmployees[]" id="cmbAssignedEmployees" style="width:125px;"
					<?php echo ($supervisorMode) ? 'readonly' : ''; ?>
			        multiple="multiple">
       			<?php
       				foreach($assignedEmployees as $assigned) {
       					$empNumber = $assigned['emp_number'];
       					$empName = $assigned['emp_name'];
           				echo "<option value='{$empNumber}'>{$empName}</option>";
       				}
				?>
			</select></td>
		</tr>

	</table>
		
		
  </form>
</div>
<div id="notice"><?php echo preg_replace('/#star/', '<span class="error">*</span>', $lang_Commn_RequiredFieldMark); ?>.</div>
