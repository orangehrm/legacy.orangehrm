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

$perfReview = $records['perfReview'];
$assignedPerfMeasures = $records['AssignedPerfMeasures'];
$availablePerfMeasures = $records['AvailablePerfMeasures'];
$employees = $records['employees'];
$authorizeObj = $records['authorizeObj'];

$perfReviewId = $perfReview->getId();
$addMode = empty($perfReviewId);

$reviewStatusList = array(PerformanceReview::STATUS_SCHEDULED => $lang_Performance_Review_Scheduled,
	PerformanceReview::STATUS_COMPLETED => $lang_Performance_Review_Completed,
	PerformanceReview::STATUS_SUBMITTED_FOR_APPROVAL => $lang_Performance_Review_SubmittedForApproval,
	PerformanceReview::STATUS_APPROVED => $lang_Performance_Review_Approved	);
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
var baseUrl = '?perfcode=PerfReviews&action=';

function goBack() {
	location.href = "./CentralController.php?perfcode=PerfReviews&action=List";
}

function viewReviewResults() {
	location.href = "./CentralController.php?perfcode=PerfReviews&action=ViewResults&id=<?php echo $perfReviewId;?>";
}

function update() {
	err=false;
	msg='<?php echo $lang_Error_PleaseCorrectTheFollowing; ?>\n\n';

	empNum = $('txtRepEmpID').value.trim(); 
	if (empNum == '') {
		err=true;
		msg+="\t- <?php echo $lang_Performance_Review_Error_PleaseSpecifyEmployee; ?>\n";
	}
	
	reviewDateStamp = strToDate($("txtReviewDate").value, YAHOO.OrangeHRM.calendar.format);
	if (!reviewDateStamp) {
		err=true;
		msg+="\t- <?php echo $lang_Performance_Review_Error_PleaseSpecifyValidDate; ?>\n";				
	} else {
	
		reviewDate = new Date();
		reviewDate.setTime(reviewDateStamp);
		if (isDaySaturdayOrSunday(reviewDate)) {
			err=true;
			msg+="\t- <?php echo $lang_Performance_Review_Error_ReviewDateShouldNotBeSaturdayOrSunday; ?>\n";		
		}
	}

	if (err) {
		alert(msg);
		return false;
	}

	selectAllOptions($('cmbAssignedPerfMeasures'));
	$('frmPerfMeasure').action=baseUrl+'Update';
	$('frmPerfMeasure').submit();
}

function assignJobTitle() {
	moveSelectOptions($('cmbAvailablePerfMeasures'), $('cmbAssignedPerfMeasures'), '<?php echo $lang_Performance_Review_Error_NoPerformanceMeasureSelected; ?>');
}

function removeJobTitle() {
	moveSelectOptions($('cmbAssignedPerfMeasures'), $('cmbAvailablePerfMeasures'), '<?php echo $lang_Performance_Review_Error_NoPerformanceMeasureSelected; ?>');
}

function returnEmpDetail(){
		var popup=window.open('../../templates/hrfunct/emppop.php?reqcode=REP','Employees','height=450,width=400');
        if(!popup.opener) popup.opener=self;
		popup.focus();
}

function isDaySaturdayOrSunday(dateVar) {
	var day = dateVar.getDay();
	if ((day == 0) || (day == 6)) {
		return true;
	} else {
		return false;
	}
}

/**
 * Adjusts the given date if it falls on a saturday or sunday.
 */ 
function adjustIfSaturdayOrSunday(dateVar) {
	var day = dateVar.getDay();
	var oneDay = 1000 * 60 * 60 * 24;
	var timeVal = dateVar.getTime();
	
	if (day == 0) {
		dateVar.setTime(timeVal + oneDay);
	} else if (day == 6) {						
		dateVar.setTime(timeVal + (oneDay * 2));
	}
	return dateVar;
}

/** 
 * Set the review date the given number of months to the future
 */
function setReviewDate(numMonths) {
	var d = new Date();
	var thisMonth = d.getMonth();
	d.setMonth(thisMonth + numMonths);
	
	// Check if falling on a saturday or sunday and adjust if so.
	var newDate = d;
	if (isDaySaturdayOrSunday(d)) {
		newDate = adjustIfSaturdayOrSunday(d);
	}
	
	$("txtReviewDate").value = formatDate(newDate, YAHOO.OrangeHRM.calendar.format);
}

YAHOO.OrangeHRM.container.init();
</script>
<h2>
<?php echo ($addMode) ? $lang_Performance_Review_ScheduleTitle :	$lang_Performance_Review_EditTitle;	?>
</h2>
<hr/>
<div class="navigation">
	<img title="Back" onMouseOut="this.src='../../themes/beyondT/pictures/btn_back.gif';" onMouseOver="this.src='../../themes/beyondT/pictures/btn_back_02.gif';"  src="../../themes/beyondT/pictures/btn_back.gif" onClick="goBack();">
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
	<form name="frmEmp" id="frmPerfMeasure" method="post" action="?perfcode=PerfReviews&action=">
		<div class="roundbox">
			<label for="cmbRepEmpID"><span class="error">*</span> <?php echo $lang_Performance_Review_EmpNumber; ?></label>									        	
	        
			<?php if ($addMode && ($authorizeObj->isAdmin())) { ?>
				<input type="text" name="cmbRepEmpID" id="cmbRepEmpID" disabled />
				<input class="button" type="button" value="..." onclick="returnEmpDetail();" />				
				<input type="hidden" name="txtRepEmpID" id="txtRepEmpID" />
			<?php } else { ?>
				<input type="text" name="cmbRepEmpID" id="cmbRepEmpID" value="<?php echo  CommonFunctions::escapeHtml($perfReview->getEmployeeName());?>" disabled />
				<input type="hidden" name="txtRepEmpID" id="txtRepEmpID" value="<?php echo $perfReview->getEmpNumber();?>" />
	        <?php } ?>
			<br/>
	        <label for="txtReviewDate"><span class="error">*</span> <?php echo $lang_Performance_Review_Date; ?></label>
	        <input type="text" id="txtReviewDate" name="txtReviewDate" 
	        	value="<?php echo LocaleUtil::getInstance()->formatDate($perfReview->getReviewDate());?>" size="10" tabindex="1" />
	        <input type="button" id="btnReviewDate" name="btnReviewDate" value="  " class="calendarBtn"/>
	        
			<input type="button" id="btn3months" name="btn3months" class="dateBtn" value="3m" onclick="setReviewDate(3)" />
			<input type="button" id="btn6months" name="btn6months" class="dateBtn" value="6m" onclick="setReviewDate(6)" />
			<input type="button" id="btn6months" name="btn6months" class="dateBtn" value="12m" onclick="setReviewDate(12)" /><br/>
			
	        <?php if (false) {?>
	        	<label for="cmbStatus"><?php echo $lang_Performance_Review_Status; ?></label>	        	
				<select name="cmbStatus" id="cmbStatus" >
			  		<?php 
			  			foreach ($reviewStatusList as $statusCode=>$statusDesc) {
			  				$selected = ($statusCode == $perfReview->getStatus()) ? 'selected' : ''; 
			  				echo "<option value='" . $statusCode . "' $selected >" .$statusDesc. "</option>";
			  			} ?>
				</select>			  	        	
	        	<br />
	        	<label for="txtNotes"><?php echo $lang_Performance_Review_Notes; ?></label>	        	
	        	<textarea id="txtNotes" name="txtNotes" > <?php echo CommonFunctions::escapeHtml($perfReview->getReviewNotes()); ?></textarea>	        	
			<?php } else { ?>
			<?php     if (!$addMode) { ?>
					<label for="none"><?php echo $lang_Performance_Review_Status;?></label>
					<input type="text" disabled id="cmbStatusText" name="cmbStatusText"
		        		value="<?php echo (isset($reviewStatusList[$perfReview->getStatus()])) ? $reviewStatusList[$perfReview->getStatus()] : '';?>">
					<br />				
			<?php     } ?>	
					<input type="hidden" id="cmbStatus" name="cmbStatus" value="<?php echo $perfReview->getStatus(); ?>"/>
					<input type="hidden" id="txtNotes" name="txtNotes" 
						value="<?php echo CommonFunctions::escapeHtml($perfReview->getReviewNotes()); ?>" />
			<?php } ?>
			<br />
	        <label for="none">&nbsp;</label>
	        <input type="hidden" id="txtReviewId" name="txtReviewId" value="<?php echo $perfReview->getId(); ?>"/>
	   	</div><br />
        <img onClick="update();"
             onMouseOut="this.src='../../themes/beyondT/pictures/btn_save.gif';"
             onMouseOver="this.src='../../themes/beyondT/pictures/btn_save_02.gif';"
             src="../../themes/beyondT/pictures/btn_save.gif"
        >
		<?php if (!$addMode) { ?>        
        <a href="javascript:viewReviewResults();"><?php echo $lang_Performance_Review_Results; ?></a>
		<?php } ?>        
		<script type="text/javascript">
		<!--
		    if (document.getElementById && document.createElement) {
		 			initOctopus();
			}
		 -->
		</script>
		<br /><br /><span><?php echo $lang_Performance_Review_AssignPerformanceMeasures;?></span>
		<br /><br />
		<table border="0">
		<tr>
		   	<th width="100" style="align:center;"><?php echo $lang_Performance_Review_AvailablePerformanceMeasures; ?></th>
			<th width="100"/>
		   	<th width="125" style="align:center;"><?php echo $lang_Performance_Review_AssignedPerformanceMeasures; ?></th>
		</tr>
		<tr><td width="100" >
			<select size="10" id="cmbAvailablePerfMeasures" name="cmbAvailablePerfMeasures[]" style="width:125px;"
					multiple="multiple">
       			<?php
       				foreach($availablePerfMeasures as $measure) {
           				echo "<option value='{$measure->getId()}'>{$measure->getName()}</option>";
       				}
				?>
			</select></td>
			<td align="center" width="100">
				<input type="button" name="btnassignJobTitle" id="btnassignJobTitle" onClick="assignJobTitle();" value=" <?php echo $lang_compstruct_add; ?> >" style="width:80%"><br><br>
				<input type="button" name="btnremoveJobTitle" id="btnremoveJobTitle" onClick="removeJobTitle();" value="< <?php echo $lang_Leave_Common_Remove; ?>" style="width:80%">
			</td>
			<td>
			<select size="10" name="cmbAssignedPerfMeasures[]" id="cmbAssignedPerfMeasures" style="width:125px;"
			        multiple="multiple">
       			<?php
       				foreach($assignedPerfMeasures as $measure) {
           				echo "<option value='{$measure->getId()}'>{$measure->getName()}</option>";
       				}
				?>
			</select></td>
		</tr>

	</table>
  </form>
</div>
<div id="notice"><?php echo preg_replace('/#star/', '<span class="error">*</span>', $lang_Commn_RequiredFieldMark); ?>.</div>
<div id="cal1Container" style="position:absolute;" ></div>
