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

$GLOBALS['lang_Performance_SalaryReview_SalaryNotFound'] = $lang_Performance_SalaryReview_SalaryNotFound;

/**
 * Ajax call to populate current salary
 *
 * @param int $empNum Employee number of employee
 */
function fetchSalary($empNum) {

	$objResponse = new xajaxResponse();
	$salary = PerformanceController::_getBaseSalary($empNum);

	if ($salary) {
		$xajaxFiller = new xajaxElementFiller();
		$objResponse->addAssign('txtCurrentSalary','value', $salary);
		$objResponse->addScript('employeeSalaryFound = true;');		
	} else {
		$objResponse->addAssign('txtCurrentSalary','value', $GLOBALS['lang_Performance_SalaryReview_SalaryNotFound']);
		$objResponse->addScript('employeeSalaryFound = false;');
	}
	$objResponse->addScript('hideLoading();');
	return $objResponse->getXML();
}

$objAjax = new xajax();
$objAjax->registerFunction('fetchSalary');
$objAjax->processRequests();

$picDir = "../../themes/{$styleSheet}/pictures/";
$iconDir = "../../themes/{$styleSheet}/icons/";

$salaryReview = $records['salaryReview'];
$authorizeObj = $records['authorizeObj'];
$currentSalary = $records['currentSalary'];
$subordinates = $records['subordinates'];

$salaryReviewId = $salaryReview->getId();
$addMode = empty($salaryReviewId);
$isSalaryApprover = $_SESSION['isSalaryApprover'];
$approverOnlyMode = ($isSalaryApprover && (!$authorizeObj->isAdmin()) && (!$authorizeObj->isSupervisor()));

$reviewStatusList = array(SalaryReview::STATUS_PENDING_APPROVAL => $lang_Performance_SalaryReview_PendingApproval,
					  SalaryReview::STATUS_APPROVED => $lang_Performance_SalaryReview_Approved,
					  SalaryReview::STATUS_REJECTED => $lang_Performance_SalaryReview_Rejected);
					  					  
$approved = ($salaryReview->getStatus() == SalaryReview::STATUS_APPROVED);
$rejected = ($salaryReview->getStatus() == SalaryReview::STATUS_REJECTED);
$pendingApproval = ($salaryReview->getStatus() == SalaryReview::STATUS_PENDING_APPROVAL);
					  
?>
<link href="../../themes/<?php echo $styleSheet;?>/css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">@import url("../../themes/<?php echo $styleSheet;?>/css/style.css"); </style>
    
<style type="text/css">
@import url("../../themes/beyondT/css/octopus.css");

.roundbox {
	margin-top: 10px;
	margin-left: 10px;
	width:500px;
}

label {width: auto;
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

.imgbutton {
    margin: 0px 5px 0px 5px;
}

.updatebutton {
	margin: 10px 5px 0px 10px;
}
width: auto;
    
</style>
<script type="text/javascript" src="../../scripts/archive.js"></script>
<script type="text/javascript" src="../../scripts/octopus.js"></script>
<?php
	$objAjax->printJavascript();
?>
<script type="text/javascript">
var baseUrl = '?perfcode=SalaryReview&action=';

var employeeSalaryFound = false;

function goBack() {
	location.href = "./CentralController.php?perfcode=SalaryReview&action=List";
}

function update() {
	err=false;
	msg='<?php echo $lang_Error_PleaseCorrectTheFollowing; ?>\n\n';

	empNum = $('txtRepEmpID').value.trim(); 
	if (empNum == '') {
		err=true;
		msg+="\t- <?php echo $lang_Performance_SalaryReview_Error_PleaseSpecifyEmployee; ?>\n";
	} 
	
	score = $('txtIncrease').value.trim();
	if ((score == '') || !isDecimal(score)) {
		err = true;
		msg+="\t- <?php echo $lang_Performance_SalaryReview_Error_IncreaseShouldBeDecimal; ?>\n";		
	}

	empNum = $('txtNotes').value.trim(); 
	if (empNum == '') {
		err=true;
		msg+="\t- <?php echo $lang_Performance_SalaryReview_Error_NotesShouldBeSpecified; ?>\n";
	}
	
	if (err) {
		alert(msg);
		return false;
	}

	$('frmSalaryReview').action=baseUrl+'Update';
	$('frmSalaryReview').submit();
}

function approve() {
	changeStatus('Approve');
}

function reject() {
	changeStatus('Reject');
}

function changeStatus(Action) {
	err=false;
	msg='<?php echo $lang_Error_PleaseCorrectTheFollowing; ?>\n\n';

	empNum = $('txtNotes').value.trim(); 
	if (empNum == '') {
		err=true;
		msg+="\t- <?php echo $lang_Performance_SalaryReview_Error_NotesShouldBeSpecified; ?>\n";
	}
	
	if (err) {
		alert(msg);
		return false;
	}

	$('frmSalaryReview').action=baseUrl+Action;
	$('frmSalaryReview').submit();	
}

function returnEmpDetail(){
		var popup=window.open('../../templates/hrfunct/emppop.php?reqcode=REP','Employees','height=450,width=400');
        if(!popup.opener) popup.opener=self;
		popup.focus();
}

/*
 * Update salary of currently selected employee
 */
function checkSalary() {
	var empNumber = $('txtRepEmpID').value;
	if (empNumber == '') {
		alert('<?php echo $lang_Performance_SalaryReview_Error_PleaseSpecifyEmployee; ?>');
	} else {
		showLoading();
		xajax_fetchSalary(empNumber);
	}
}
	
function hideLoading() {
	var statusVar = $('status');
	statusVar.style.display = 'none';
}

function showLoading() {
	var statusVar = $('status');
	statusVar.style.display = 'block';
}
		
</script>
<h2>
<?php echo ($addMode) ? $lang_Performance_SalaryReview_AddTitle :	$lang_Performance_SalaryReview_EditTitle;	?>
</h2>
<hr/>
<div id="status" style="float:right;display:none;">
	<image src='<?php echo $iconDir; ?>/loading.gif' width='20' height='20' style="vertical-align: bottom;">
	<?php echo $lang_Commn_PleaseWait;$rejected?>
</div>

<div class="navigation">
	<img title="Back" onMouseOut="this.src='../../themes/beyondT/pictures/btn_back.gif';" onMouseOver="this.src='../../themes/beyondT/pictures/btn_back_02.gif';"  src="../../themes/beyondT/pictures/btn_back.gif" onClick="goBack();">
<?php
if (isset($_GET['message']) && !empty($_GET['message'])) {

	$expString  = $_GET['message'];
	$col_def = CommonFunctions::getCssClassForMessage($expString);
	$expString = 'lang_Performance_Errors_'.$expString;

	$message = isset($$expString) ? $$expString : CommonFunctions::escapeHtml($_GET['message']);
?>
	<font class="<?php echo $col_def?>" size="-1" face="Verdana, Arial, Helvetica, sans-serif">
<?php echo $message; ?>
	</font>
<?php }	?>
</div>
<div id="editPanel">
	<form name="frmEmp" id="frmSalaryReview" method="post" action="?perfcode=SalaryReview&action=">
		<div class="roundbox">
			<label for="cmbRepEmpID"><span class="error">*</span> <?php echo $lang_Performance_SalaryReview_EmployeeName; ?></label>									        	
	        
			<?php if ($addMode) {
				      if ($authorizeObj->isAdmin()) { 
			?>
					
				<input type="text" name="cmbRepEmpID" id="cmbRepEmpID" readonly />
				<input class="button" type="button" value="..." onclick="returnEmpDetail();" />				
				<input type="hidden" name="txtRepEmpID" id="txtRepEmpID" tabindex="1"/>
			<?php     } else if ($authorizeObj->isSupervisor()) { ?>
				<select name="txtRepEmpID" id="txtRepEmpID">
					<option value="-1">-<?php echo $lang_Leave_Common_Select;?>-</option>
					<?php
			   			if (is_array($subordinates)) {
			   				sort($subordinates);
			   				foreach ($subordinates as $employee) {
			        ?>
			 		<option value="<?php echo $employee[0] ?>"><?php echo $employee[1] ?></option>
			  <?php 		}
			   			}
	          ?>		
	          	</select><br />		
            <?php     
                      } 
				 } else { 
            ?>
				<input type="text" name="cmbRepEmpID" id="cmbRepEmpID" value="<?php echo  CommonFunctions::escapeHtml($salaryReview->getEmployeeName());?>" readonly />
				<input type="hidden" name="txtRepEmpID" id="txtRepEmpID" value="<?php echo $salaryReview->getEmpNumber();?>" />
	        <?php } ?>
			<br />
			<label for="txtCurrentSalary"><?php echo $lang_Performance_SalaryReview_CurrentSalary; ?></label>
			<input type="text" id="txtCurrentSalary" name="txtCurrentSalary" value="<?php echo $currentSalary;?>" readonly />
			<?php if ($addMode) { ?>
		        <img onClick="checkSalary();"
		             onMouseOut="this.src='../../themes/beyondT/icons/update.gif';"
		             onMouseOver="this.src='../../themes/beyondT/icons/update_o.gif';"
		             src="../../themes/beyondT/icons/update.gif"
		             class="updatebutton"
		             title="<?php echo $lang_Performance_SalaryReview_FetchCurrentSalaryForSelectedEmployee;?>"
		             alt="<?php echo $lang_Performance_SalaryReview_FetchCurrentSalaryForSelectedEmployee;?>"
		        >				
			<?php } ?>
			<br />
			
	        <label for="txtIncrease"><span class="error">*</span> <?php echo $lang_Performance_SalaryReview_SalaryIncrease; ?></label>
	        <input type="text" id="txtIncrease" name="txtIncrease" 
	        	<?php echo ($approverOnlyMode || $approved || $rejected) ? 'readonly' : '';?>
	        	value="<?php echo $salaryReview->getIncrease();?>" size="10" tabindex="2" />	        
			<br/>
        	<label for="txtNotes"><span class="error">*</span> <?php echo $lang_Performance_SalaryReview_ReviewNotes; ?></label>	        	
        	<textarea id="txtNotes" name="txtNotes" tabindex="3"
        		<?php echo (!$isSalaryApprover && !$pendingApproval) ? 'readonly' : ''; ?>
        		> <?php echo CommonFunctions::escapeHtml($salaryReview->getDescription()); ?></textarea>
        	<br />	        	
			
			<?php if (!$addMode) { ?>
				<label for="none"><?php echo $lang_Performance_Review_Status;?></label>
				<input type="text" disabled id="txtStatusText" name="txtStatusText"
					value="<?php echo (isset($reviewStatusList[$salaryReview->getStatus()])) ? $reviewStatusList[$salaryReview->getStatus()] : '';?>">
				<br />						        				
			<?php } ?>
	        <label for="none">&nbsp;</label>
	        <input type="hidden" id="txtReviewId" name="txtReviewId" value="<?php echo $salaryReview->getId(); ?>"/>
						
	   	</div><br />
		<?php if (($authorizeObj->isSupervisor() || $authorizeObj->isAdmin()) && ($pendingApproval)) { ?>	   	
        <img onClick="update();"
             onMouseOut="this.src='../../themes/beyondT/pictures/btn_save.gif';"
             onMouseOver="this.src='../../themes/beyondT/pictures/btn_save_02.gif';"
             src="../../themes/beyondT/pictures/btn_save.gif"
        >
        <?php } ?>
		<?php if ($isSalaryApprover) { ?>        	
	        <?php if ($pendingApproval || $rejected) { ?>
	        <img onClick="approve();"
	             onMouseOut="this.src='../../themes/beyondT/icons/approve.gif';"
	             onMouseOver="this.src='../../themes/beyondT/icons/approve_o.gif';"
	             src="../../themes/beyondT/icons/approve.gif"
				 class="imgbutton"
	        >
	        <?php } ?>
	        <?php if ($pendingApproval || $approved) { ?>        
	        <img onClick="reject();"
	             onMouseOut="this.src='../../themes/beyondT/icons/reject.gif';"
	             onMouseOver="this.src='../../themes/beyondT/icons/reject_o.gif';"
	             src="../../themes/beyondT/icons/reject.gif"
	             class="imgbutton"
	        >        
	        <?php } ?>        	                	
		<?php } ?>
		<script type="text/javascript">
		<!--
		    if (document.getElementById && document.createElement) {
		 			initOctopus();
			}
		 -->
		</script>
		<br /><br />
  </form>
</div>
<div id="notice"><?php echo preg_replace('/#star/', '<span class="error">*</span>', $lang_Commn_RequiredFieldMark); ?>.</div>
