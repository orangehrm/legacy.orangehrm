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

$budget = $records['budget'];
$authorizeObj = $records['authorizeObj'];

$budgetId = $budget->getId();
$addMode = empty($budgetId);

$styleDir = "../../themes/{$styleSheet}";
$picDir = $styleDir . "/pictures";
$cssDir = $styleDir . "/css";

$statusList = array(Budget::STATUS_CREATED => $lang_Budget_Created,
								  Budget::STATUS_SUBMITTED_FOR_APPROVAL => $lang_Budget_SubmittedForApproval,
								  Budget::STATUS_NOT_APPROVED => $lang_Budget_NotApproved,
								  Budget::STATUS_APPROVED => $lang_Budget_Approved);		

if ($_SESSION['isBudgetApprover']) {					
	$allowedStatusList = array_keys($statusList);
} else {
	$allowedStatusList = array(Budget::STATUS_CREATED, Budget::STATUS_SUBMITTED_FOR_APPROVAL);	
}
					
$typeList = array(Budget::BUDGET_TYPE_SALARY => $lang_Budget_Salary,
	 			 Budget::BUDGET_TYPE_TRAINING => $lang_Budget_Training,
	 			 Budget::BUDGET_TYPE_EMPLOYEE => $lang_Budget_Employee,
	 			 Budget::BUDGET_TYPE_COMPANY => $lang_Budget_Company);			
	 			 
if (($budget->getStatus() == Budget::STATUS_APPROVED) || ($budget->getStatus() == Budget::STATUS_NOT_APPROVED)) {
	$disabled = 'disabled';
} else {
	$disabled = '';
} 	 			 						    				  
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
var baseUrl = '?budgetcode=Budgets&action=';

function goBack() {
	location.href = "./CentralController.php?budgetcode=Budgets&action=List";
}
		
function update() {
	err=false;
	msg='<?php echo $lang_Error_PleaseCorrectTheFollowing; ?>\n\n';

	type = $('cmbBudgetType').value.trim(); 
	if (type == '-1') {
		err=true;
		msg+="\t- <?php echo $lang_Budget_Error_PleaseSpecifyBudgetType; ?>\n";
	} 

	unit = $('txtBudgetUnit').value.trim(); 
	if (unit == '') {
		err=true;
		msg+="\t- <?php echo $lang_Budget_Error_PleaseSpecifyBudgetUnit; ?>\n";
	}

	value = $('txtBudgetValue').value.trim(); 
	if (value == '') {
		err=true;
		msg+="\t- <?php echo $lang_Budget_Error_PleaseSpecifyBudgetValue; ?>\n";
	}
	
	startDateStamp = strToDate($("txtStartDate").value, YAHOO.OrangeHRM.calendar.format);
	if (!startDateStamp) {
		err=true;
		msg+="\t- <?php echo $lang_Budget_Error_PleaseSpecifyValidStartDate; ?>\n";				
	}
	endDateStamp = strToDate($("txtEndDate").value, YAHOO.OrangeHRM.calendar.format);
	if (!endDateStamp) {
		err=true;
		msg+="\t- <?php echo $lang_Budget_Error_PleaseSpecifyValidEndDate; ?>\n";				
	}
	if (startDateStamp && endDateStamp && (startDateStamp > endDateStamp)) {
		err=true;
		msg+="\t- <?php echo $lang_Budget_Error_StartDateShouldBeBeforeEndDate; ?>\n";						
	}
		
	if (err) {
		alert(msg);
		return false;
	}

	$('frmBudget').action=baseUrl+'Update';
	$('frmBudget').submit();
}

YAHOO.OrangeHRM.container.init();
</script>
<h2>
<?php echo ($addMode) ? $lang_Budget_AddTitle :	$lang_Budget_EditTitle;	?>
</h2>
<hr/>
<div class="navigation">
	<img title="Back" onMouseOut="this.src='<?php echo $picDir; ?>/btn_back.gif';" onMouseOver="this.src='<?php echo $picDir; ?>/btn_back_02.gif';"  src="<?php echo $picDir; ?>/btn_back.gif" onClick="goBack();">
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
	<form name="frmEmp" id="frmBudget" method="post" action="?budgetcode=Budgets&action=">
		<div class="roundbox">

        	<label for="cmbBudgetType"><span class="error">*</span> <?php echo $lang_Budget_Type; ?></label>	  	        	
			<select name="cmbBudgetType" id="cmbBudgetType" <?php echo $disabled; ?>>
		  		<?php 
		  			foreach ($typeList as $typeCode=>$typeDesc) {
		  				$selected = ($typeCode == $budget->getBudgetType()) ? 'selected' : ''; 
		  				echo "<option value='" . $typeCode . "' $selected >" .$typeDesc. "</option>";
		  			} ?>
			</select>			  	        	
        	<br />        	
        	
        	<label for="txtBudgetUnit"><span class="error">*</span> <?php echo $lang_Budget_Unit; ?></label>
        	<input type="text" name="txtBudgetUnit" id="txtBudgetUnit" <?php echo $disabled; ?>
        		value="<?php echo CommonFunctions::escapeHtml($budget->getBudgetUnit()); ?>"/>
        	<br />

        	<label for="txtBudgetValue"><span class="error">*</span> <?php echo $lang_Budget_Value; ?></label>
        	<input type="text" name="txtBudgetValue" id="txtBudgetValue" <?php echo $disabled; ?>
        		value="<?php echo CommonFunctions::escapeHtml($budget->getBudgetValue()); ?>"/>
        	<br />
			
	        <label for="txtStartDate"><span class="error">*</span> <?php echo $lang_Budget_StartDate; ?></label>
	        <input type="text" id="txtStartDate" name="txtStartDate" <?php echo $disabled; ?>
	        	value="<?php echo LocaleUtil::getInstance()->formatDate($budget->getStartDate());?>" size="10" tabindex="1" />
	        <input type="button" id="btnStartDate" name="btnStartDate" value="  " class="calendarBtn" <?php echo $disabled; ?>/>
	       	<br />
	        <label for="txtEndDate"><span class="error">*</span> <?php echo $lang_Budget_EndDate; ?></label>
	        <input type="text" id="txtEndDate" name="txtEndDate" <?php echo $disabled; ?>
	        	value="<?php echo LocaleUtil::getInstance()->formatDate($budget->getEndDate());?>" size="10" tabindex="1" />
	        <input type="button" id="btnEndDate" name="btnEndDate" value="  " class="calendarBtn" <?php echo $disabled; ?>/>
			
			<br/>
			
        	<label for="cmbStatus"><?php echo $lang_Budget_Status; ?></label>	        	
			<select name="cmbStatus" id="cmbStatus" <?php echo $disabled; ?>
		  		<?php 
		  			foreach ($statusList as $statusCode=>$statusDesc) {
		  				$selected = ($statusCode == $budget->getStatus()) ? 'selected' : ''; 
		  				
		  				/* Skip not allowed options, except when that option is set */
		  				if (in_array($statusCode, $allowedStatusList) || !empty($selected)) {		  				
		  					echo "<option value='" . $statusCode . "' $selected >" .$statusDesc. "</option>";
		  				}
		  			} ?>
			</select>			  	        	
        	<br />

        	<label for="txtNotes"><?php echo $lang_Budget_Notes; ?></label>	        	
        	<textarea id="txtNotes" name="txtNotes" <?php echo $disabled; ?>> <?php echo CommonFunctions::escapeHtml($budget->getNotes()); ?></textarea>	        	
			<br />
						
	        <label for="none">&nbsp;</label>
	        <input type="hidden" id="txtId" name="txtId" value="<?php echo $budget->getId(); ?>"/>
	   	</div><br />
	   	
	   	<?php if (!$disabled) { ?>
        <img onClick="update();" 
             onMouseOut="this.src='<?php echo $picDir; ?>/btn_save.gif';"
             onMouseOver="this.src='<?php echo $picDir; ?>/btn_save_02.gif';"
             src="<?php echo $picDir; ?>/btn_save.gif"
        >
		<?php } ?>
		<script type="text/javascript">
		<!--
		    if (document.getElementById && document.createElement) {
		 			initOctopus();
			}
		 -->
		</script>
		
  </form>
</div>
<div id="notice"><?php echo preg_replace('/#star/', '<span class="error">*</span>', $lang_Commn_RequiredFieldMark); ?>.</div>
<div id="cal1Container" style="position:absolute;" ></div>
