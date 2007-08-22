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

require_once ROOT_PATH . '/lib/controllers/TimeController.php';

function populateProjects($cutomerId, $row) {
	ob_clean();

	$timeController = new TimeController();
	$projects = $timeController->fetchCustomersProjects($cutomerId);

	$objResponse = new xajaxResponse();
	$xajaxFiller = new xajaxElementFiller();
	$element="cmbProject[$row]";

	$objResponse = $xajaxFiller->cmbFillerById($objResponse,$projects,0,'frmTimesheet',$element);

	$objResponse->addScript('document.getElementById("'.$element.'").focus();');

	$objResponse->addAssign('status','innerHTML','');

	return $objResponse->getXML();
}

$objAjax = new xajax();
$objAjax->registerFunction('populateProjects');
$objAjax->processRequests();

$timesheet=$records[0];
$timesheetSubmissionPeriod=$records[1];
$timeExpenses=$records[2];
$customers=$records[3];
$projects=$records[4];
$employee=$records[5];
$self=$records[6];

$status=$timesheet->getStatus();

switch ($status) {
	case Timesheet::TIMESHEET_STATUS_NOT_SUBMITTED : $statusStr = $lang_Time_Timesheet_Status_NotSubmitted;
												break;
	case Timesheet::TIMESHEET_STATUS_SUBMITTED : $statusStr = $lang_Time_Timesheet_Status_Submitted;
												break;
	case Timesheet::TIMESHEET_STATUS_APPROVED : $statusStr = $lang_Time_Timesheet_Status_Approved;
												break;
	case Timesheet::TIMESHEET_STATUS_REJECTED : $statusStr = $lang_Time_Timesheet_Status_Rejected;
												break;
}

$startDate = strtotime($timesheet->getStartDate() . " 00:00:00");
$endDate = strtotime($timesheet->getEndDate() . " 23:59:00");

$row=0;
?>
<script type="text/javascript">
<!--
currFocus = null;
totRows = 0;

var initialAction = "<?php echo $_SERVER['PHP_SELF']; ?>?timecode=Time&id=<?php echo $timesheet->getTimesheetId(); ?>&action=";

function $(id) {
	return document.getElementById(id);
}

function isEmpty(value) {
	value = trim(value);
	return (value == "");
}

function isFieldEmpty(id) {
	return isEmpty($(id).value);
}

function actionSubmit() {
	$("frmTimesheet").action= initialAction+"Submit_Timesheet";

	$("frmTimesheet").submit();
}

function looseCurrFocus(row) {
	currFocus = null;
}

function setCurrFocus(label, row) {
	currFocus = $(label+"["+row+"]");
}

function actionInsertTime() {
	if (!currFocus) {
		currFocus = $("txtStartTime["+totRows+"]");
	}
	if (currFocus.value == "") {
    	currFocus.value = formatDate(new Date(), "yyyy-MM-dd HH:mm");
  	}
  	currFocus.focus();
}

/**
 * Checks that the given date is within the timesheet period.
 * @return true if date within period, false otherwise
 */
function checkDateWithinPeriod(dateToCheck) {

	if (dateToCheck) {

		periodStart = strToTime("<?php echo date("Y-m-d H:i", $startDate); ?>");
		periodEnd = strToTime("<?php echo date("Y-m-d H:i", $endDate); ?>");
		if ((dateToCheck < periodStart) || (dateToCheck > periodEnd)) {
			return false;
		}
	}
	return true;

}

/**
 * checks that the given date and duration are within the timesheet period
 *
 * @return true if within period, false otherwise.
 */
function checkDateAndDuration(dateValue, duration) {

	periodStart = strToTime("<?php echo date("Y-m-d H:i", $startDate); ?>");
	periodEnd = strToTime("<?php echo date("Y-m-d H:i", $endDate); ?>");

	// ignore invalid dates and durations since those are checked separately
	if (dateValue && validateDuration(duration)) {

		if ((dateValue < periodStart) || (dateValue > periodEnd)) {
			return false;
		}

		endTime = new Date();
		endTime.setTime(dateValue + (3600000 * duration));

		if ((endTime < periodStart) || (endTime > periodEnd)) {
			return false;
		}
	}

	return true;
}


/**
 * Validates the given duration.
 * Checks that it is a positive number.
 *
 * @return true if valid, false otherwise
 */
function validateDuration(value) {

	if (value != "") {
		regExp = /^[0-9]+\.*[0-9]*/;

		if (!regExp.test(value)) {
			return false;
		}
	}
	return true;
}

/**
 * Validates fields
 *
 * @return true if valid, false otherwise
 */
function validate() {

	errorMsgs = new Array();
	err = new Array();
	errFlag = false;

	for (i = 0; i <= totRows; i++) {
		if (i == totRows) {
			lastRow = true;
		} else {
			lastRow = false;
		}

		if (!lastRow || !allEmpty(i)) {
			err[i] = false;

			txtStartTime = trim($("txtStartTime["+i+"]").value);
			txtEndTime = trim($("txtEndTime["+i+"]").value);
			txtReportedDate = trim($("txtReportedDate["+i+"]").value);
			duration = trim($("txtDuration["+i+"]").value);

			startTime = strToTime(txtStartTime);
			endTime = strToTime(txtEndTime);
			reportedDate = strToDate(txtReportedDate);

			// Validate values
			if ($("cmbCustomer["+i+"]").value == 0) {
				errorMsgs[0] = "<?php echo $lang_Time_Errors_CustomerNotSpecified_ERROR; ?>";
				err[i] = true;
			}

			if ($("cmbProject["+i+"]").value == 0) {
				errorMsgs[1] = "<?php echo $lang_Time_Errors_ProjectNotSpecified_ERROR; ?>";
				err[i] = true;
			}

			if ((txtStartTime != "") && !startTime) {
				errorMsgs[2] = "<?php echo $lang_Time_Errors_InvalidStartTime_ERROR; ?>";
				err[i] = true;
			}

			if ((txtEndTime != "") && !endTime) {
				errorMsgs[3] = "<?php echo $lang_Time_Errors_InvalidEndTime_ERROR; ?>";
				err[i] = true;
			}

			if (txtReportedDate == "") {
				errorMsgs[4] = "<?php echo $lang_Time_Errors_ReportedDateNotSpecified_ERROR; ?>";
				err[i] = true;
			} else if (!reportedDate) {
				errorMsgs[5] = "<?php echo $lang_Time_Errors_InvalidReportedDate_ERROR; ?>";
				err[i] = true;
			}

			// 0 not allowed for duration in last row.
			if (!validateDuration(duration) || (lastRow && (duration != "") && (duration == 0))) {
				errorMsgs[6] = "<?php echo $lang_Time_Errors_InvalidDuration_ERROR; ?>";
				err[i] = true;
			}

			// Validate period/interval
			if (txtStartTime == "") {
				if (!isEmpty(duration) && !isEmpty(txtReportedDate) && (txtEndTime == "")) {

					// Only reported date and duration specified. Check duration within timesheet period
					if (!checkDateAndDuration(reportedDate, duration)) {
						errorMsgs[7] = "<?php echo $lang_Time_Errors_EVENT_OUTSIDE_PERIOD_FAILURE; ?>";
						err[i] = true;
					} else if (!lastRow && (validateDuration(duration) && duration == 0)) {

						// Don't allow zero duration (for saved rows)
						errorMsgs[6] = "<?php echo $lang_Time_Errors_InvalidDuration_ERROR; ?>";
						err[i] = true;
					}
				} else {
					errorMsgs[8] = "<?php echo $lang_Time_Errors_NoValidDurationOrInterval_ERROR; ?>";
					err[i] = true;
				}

			} else {
				if (txtEndTime == "") {
					if (duration == "") {

						// start time only. Check that it's within timesheet period
						if (!checkDateWithinPeriod(startTime)) {
							errorMsgs[7] = "<?php echo $lang_Time_Errors_EVENT_OUTSIDE_PERIOD_FAILURE; ?>";
							err[i] = true;
						}
					} else {
						// Only start time and duration specified. Check duration within timesheet period
						if (!checkDateAndDuration(startTime, duration)) {
							errorMsgs[7] = "<?php echo $lang_Time_Errors_EVENT_OUTSIDE_PERIOD_FAILURE; ?>";
							err[i] = true;
						}
					}
				} else {
					if ((duration == "") || (!lastRow)) {

						// start time and end time specified
						if ((startTime && endTime) && (startTime >= endTime)) {
							errorMsgs[9] = "<?php echo $lang_Time_Errors_ZeroOrNegativeIntervalSpecified_ERROR; ?>";
							err[i] = true;
						} else {
							if (!checkDateWithinPeriod(startTime) || !checkDateWithinPeriod(endTime)) {
								errorMsgs[7] = "<?php echo $lang_Time_Errors_EVENT_OUTSIDE_PERIOD_FAILURE; ?>";
								err[i] = true;
							}
						}
					} else {
							errorMsgs[10] = "<?php echo $lang_Time_Errors_NotAllowedToSpecifyDurationAndInterval_ERROR; ?>";
							err[i] = true;
					}
				}
			}

			if (err[i]) {
				errFlag = true;
				$("row["+i+"]").style.background = "#FFAAAA";
			} else {
				$("row["+i+"]").style.background = "#FFFFFF";
			}
		}
	}

	if (errFlag) {
		errStr = "<?php echo $lang_Time_Errors_EncounteredTheFollowingProblems; ?>\n";
		for (i in errorMsgs) {
			errStr += " - " + errorMsgs[i] + "\n";
		}
		alert(errStr);

		return false;
	}

	return true;
}

/**
 * Checks whether all values in the row are empty.
 */
function allEmpty(row) {

	if (!isFieldEmpty("txtDuration["+row+"]")) {
		return false;
	}

	if ($("cmbCustomer["+row+"]").value != 0) {
		return false;
	}

	if ($("cmbProject["+row+"]").value != 0) {
		return false;
	}

	if (!isFieldEmpty("txtDescription["+row+"]")) {
		return false;
	}

	if (!isFieldEmpty("txtStartTime["+row+"]")) {
		return false;
	}

	if (!isFieldEmpty("txtEndTime["+row+"]")) {
		return false;
	}

	return true;
}

function actionUpdate() {
	if (!validate()) return false;

	$('frmTimesheet').action= initialAction+'Edit_Timesheet';
	$('frmTimesheet').submit();
}

function actionReset() {
	$('frmTimesheet').reset();
}

function deleteTimeEvents() {
	$check = 0;
	with (document.frmTimesheet) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'deleteEvent[]')){
				$check = 1;
			}
		}
	}

	if ($check == 1){
		var res = confirm("<?php echo $lang_Common_ConfirmDelete?>");

		if(!res) return;

		$('frmTimesheet').action= initialAction+'Delete_Timesheet';
		$('frmTimesheet').submit();
	}else{
		alert("<?php echo $lang_Common_SelectDelete; ?>");
	}
}
-->
</script>
<?php $objAjax->printJavascript(); ?>
<h2><?php 	$headingStr = $lang_Time_Timesheet_TimesheetNameForEditTitle;
			if ($self) {
				$headingStr = $lang_Time_Timesheet_TimesheetForEditTitle;
			}
			echo preg_replace(array('/#periodName/', '/#startDate/', '/#name/'),
							array($timesheetSubmissionPeriod->getName(), $timesheet->getStartDate(), "{$employee[2]} {$employee[1]}"),
							$headingStr); ?>
  <hr/>
</h2>
<div id="status"></div>
<?php if (isset($_GET['message'])) {

		$expString  = $_GET['message'];
		$expString = explode ("_",$expString);
		$length = count($expString);

		$col_def=strtolower($expString[$length-1]);

		$expString='lang_Time_Errors_'.$_GET['message'];
?>
		<font class="<?php echo $col_def?>" size="-1" face="Verdana, Arial, Helvetica, sans-serif">
<?php echo $$expString; ?>
		</font>
<?php }	?>
<form id="frmTimesheet" name="frmTimesheet" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?timecode=Time&id=<?php echo $timesheet->getTimesheetId(); ?>&action=">
<table border="0" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th class="tableTopLeft"></th>
	    	<th class="tableTopMiddle"></th>
	    	<th class="tableTopMiddle"></th>
	    	<th class="tableTopMiddle"></th>
	    	<th class="tableTopMiddle"></th>
	    	<th class="tableTopMiddle"></th>
	    	<th class="tableTopMiddle"></th>
	    	<th class="tableTopMiddle"></th>
	    	<th class="tableTopMiddle"></th>
			<th class="tableTopRight"></th>
		</tr>
		<tr>
			<th class="tableMiddleLeft"></th>
			<th class="tableMiddleMiddle"></th>
			<th width="80px" class="tableMiddleMiddle"><?php echo $lang_Time_Timesheet_Customer; ?></th>
			<th width="95px" class="tableMiddleMiddle"><?php echo $lang_Time_Timesheet_ProjectActivity; ?></th>
			<th width="150px" class="tableMiddleMiddle"><?php echo $lang_Time_Timesheet_StartTime; ?></th>
			<th width="150px" class="tableMiddleMiddle"><?php echo $lang_Time_Timesheet_EndTime; ?></th>
			<th width="150px" class="tableMiddleMiddle"><?php echo $lang_Time_Timesheet_ReportedDate; ?></th>
			<th width="150px" class="tableMiddleMiddle"><?php echo $lang_Time_Timesheet_Duration; ?> <?php echo $lang_Time_Timesheet_DurationUnits; ?></th>
			<th width="150px" class="tableMiddleMiddle"><?php echo $lang_Time_Timesheet_Decription; ?></th>
			<th class="tableMiddleRight"></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (isset($timeExpenses) && is_array($timeExpenses)) {

			$customerObj = new Customer();
			$projectObj = new Projects();
			foreach ($timeExpenses as $timeExpense) {
				$projectId = $timeExpense->getProjectId();

				$projectDet = $projectObj->fetchProject($projectId);

				$customerDet = $customerObj->fetchCustomer($projectDet->getCustomerId(), true);
			?>
			<tr id="row[<?php echo $row; ?>]">
				<td class="tableMiddleLeft"></td>
				<td ><input type="checkbox" id="deleteEvent[]" name="deleteEvent[]" value="<?php echo $timeExpense->getTimeEventId(); ?>" /></td>
				<td ><select id="cmbCustomer[<?php echo $row; ?>]" name="cmbCustomer[]" onfocus="looseCurrFocus();" onchange="$('status').innerHTML='<?php echo $lang_Common_Loading;?>...'; xajax_populateProjects(this.value, <?php echo $row; ?>);">
				<?php if (is_array($customers)) { ?>
						<option value="0">--<?php echo $lang_Leave_Common_Select;?>--</option>
				<?php	foreach ($customers as $customer) {
							$selected="";
							if ($customerDet->getCustomerId() == $customer->getCustomerId()) {
								$selected="selected";
							}
				?>
						<option <?php echo $selected; ?> value="<?php echo $customer->getCustomerId(); ?>"><?php echo $customer->getCustomerName(); ?></option>
				<?php 	}
					} else { ?>
						<option value="0">- <?php echo $lang_Time_Timesheet_NoCustomers;?> -</option>
				<?php } ?>
					</select>
				</td>
				<td ><select id="cmbProject[<?php echo $row; ?>]" name="cmbProject[]" onfocus="looseCurrFocus();">
				<?php if (is_array($projects)) { ?>
						<option value="0">--<?php echo $lang_Leave_Common_Select;?>--</option>
				<?php	foreach ($projects as $project) {
							$selected="";
							if ($projectDet->getProjectId() == $project->getProjectId()) {
								$selected="selected";
							}
				?>
						<option <?php echo $selected; ?> value="<?php echo $project->getProjectId(); ?>"><?php echo $project->getProjectName() ?></option>
				<?php 	}
					} else { ?>
						<option value="0">- <?php echo $lang_Time_Timesheet_NoProjects;?> -</option>
				<?php } ?>
					</select>
				</td>
				<td><input type="text" <?php echo ($timeExpense->getStartTime() == null)?'readonly="readonly"':''; ?> id="txtStartTime[<?php echo $row; ?>]" name="txtStartTime[]" value="<?php echo $timeExpense->getStartTime(); ?>" onfocus="setCurrFocus('txtStartTime', <?php echo $row; ?>);" /></td>
				<td><input type="text" <?php echo ($timeExpense->getStartTime() == null)?'readonly="readonly"':''; ?> id="txtEndTime[<?php echo $row; ?>]" name="txtEndTime[]" value="<?php echo $timeExpense->getEndTime(); ?>" onfocus="setCurrFocus('txtEndTime', <?php echo $row; ?>);" /></td>
				<td><input type="text" id="txtReportedDate[<?php echo $row; ?>]" name="txtReportedDate[]" value="<?php echo $timeExpense->getReportedDate(); ?>" onfocus="looseCurrFocus();" /></td>
				<td><input type="text" <?php echo ($timeExpense->getStartTime() == null)?'':'readonly="readonly"'; ?> id="txtDuration[<?php echo $row; ?>]" name="txtDuration[]" value="<?php echo round($timeExpense->getDuration()/36)/100; ?>" onfocus="looseCurrFocus();" /></td>
				<td><textarea type="text" id="txtDescription[<?php echo $row; ?>]" name="txtDescription[]" onfocus="looseCurrFocus();" ><?php echo $timeExpense->getDescription(); ?></textarea>
					<input type="hidden" id="txtTimeEventId[<?php echo $row; ?>]" name="txtTimeEventId[]" value="<?php echo $timeExpense->getTimeEventId(); ?>" />
				</td>
				<td class="tableMiddleRight"></td>
			</tr>
		<?php
				$row++;
			}
		}?>
			<tr id="row[<?php echo $row; ?>]">
				<td class="tableMiddleLeft"></td>
				<td ><input type="checkbox" id="deleteEvent[]" name="deleteEvent[]" disabled="disabled" /></td>
				<td ><select id="cmbCustomer[<?php echo $row; ?>]" name="cmbCustomer[]" onfocus="looseCurrFocus();" onchange="$('status').innerHTML='<?php echo $lang_Common_Loading;?>...'; xajax_populateProjects(this.value, <?php echo $row; ?>);" >
				<?php if (is_array($customers)) { ?>
						<option value="0">--<?php echo $lang_Leave_Common_Select;?>--</option>
				<?php	foreach ($customers as $customer) { ?>
						<option value="<?php echo $customer->getCustomerId(); ?>"><?php echo $customer->getCustomerName(); ?></option>
				<?php 	}
					} else { ?>
						<option value="0">- <?php echo $lang_Time_Timesheet_NoCustomers;?> -</option>
				<?php } ?>
					</select>
				</td>
				<td ><select id="cmbProject[<?php echo $row; ?>]" name="cmbProject[]" onfocus="looseCurrFocus();">
				<?php if (is_array($projects)) { ?>
						<option value="0">--<?php echo $lang_Leave_Common_Select;?>--</option>
				<?php	foreach ($projects as $project) { ?>
						<option value="<?php echo $project->getProjectId(); ?>"><?php echo $project->getProjectName() ?></option>
				<?php 	}
					} else { ?>
						<option value="0">- <?php echo $lang_Time_Timesheet_NoProjects;?> -</option>
				<?php } ?>
					</select>
				</td>
				<td><input type="text" id="txtStartTime[<?php echo $row; ?>]" name="txtStartTime[]" onfocus="setCurrFocus('txtStartTime', <?php echo $row; ?>);" /></td>
				<td><input type="text" id="txtEndTime[<?php echo $row; ?>]" name="txtEndTime[]" onfocus="setCurrFocus('txtEndTime', <?php echo $row; ?>);" /></td>
				<td><input type="text" id="txtReportedDate[<?php echo $row; ?>]" name="txtReportedDate[]" value="<?php echo date('Y-m-d'); ?>" onfocus="looseCurrFocus();" /></td>
				<td><input type="text" id="txtDuration[<?php echo $row; ?>]" name="txtDuration[]" onfocus="looseCurrFocus();" /></td>
				<td><textarea type="text" id="txtDescription[<?php echo $row; ?>]" name="txtDescription[]" onfocus="looseCurrFocus();" ></textarea></td>
				<td class="tableMiddleRight"></td>
			</tr>
	</tbody>
	<tfoot>
	  	<tr>
			<td class="tableBottomLeft"></td>
			<td class="tableBottomMiddle"></td>
			<td class="tableBottomMiddle"></td>
			<td class="tableBottomMiddle"></td>
			<td class="tableBottomMiddle"></td>
			<td class="tableBottomMiddle"></td>
			<td class="tableBottomMiddle"></td>
			<td class="tableBottomMiddle"></td>
			<td class="tableBottomMiddle"></td>
			<td class="tableBottomRight"></td>
		</tr>
  	</tfoot>
</table>
<p id="controls">

<input type="hidden" name="txtTimesheetId" value="<?php echo $timesheet->getTimesheetId(); ?>" />
<input type="hidden" name="txtEmployeeId" value="<?php echo $timesheet->getEmployeeId(); ?>" />

<input src="../../themes/beyondT/icons/update.png"
		onmouseover="this.src='../../themes/beyondT/icons/update_o.png';"
		onmouseout="this.src='../../themes/beyondT/icons/update.png';"
		onclick="actionUpdate(); return false;"
		name="btnUpdate" id="btnUpdate"
		height="20" width="65"  type="image" alt="Update" />
<input src="../../themes/beyondT/icons/reset.gif"
		onmouseover="this.src='../../themes/beyondT/icons/reset_o.gif';"
		onmouseout="this.src='../../themes/beyondT/icons/reset.gif';"
		onclick="actionReset(); return false;"
		name="btnReset" id="btnReset"
		height="20" width="65" type="image" alt="Reset"/>
<input src="../../themes/beyondT/icons/insertTime.png"
		onmouseover="this.src='../../themes/beyondT/icons/insertTime_o.png';"
		onmouseout="this.src='../../themes/beyondT/icons/insertTime.png';"
		onclick="actionInsertTime(); return false;"
		name="btnInsert" id="btnInsert"
		height="20" width="90" type="image" alt="Insert Time" />
<input src="../../themes/beyondT/pictures/btn_delete.jpg"
		onmouseover="this.src='../../themes/beyondT/pictures/btn_delete_02.jpg';"
		onmouseout="this.src='../../themes/beyondT/pictures/btn_delete.jpg';"
		onclick="deleteTimeEvents(); return false;"
		name="btnDelete" id="btnDelete"
		type="image" alt="Delete" />
</form>
</p>
<script type="text/javascript">
	totRows = <?php echo $row; ?>;
	currFocus = $("cmbCustomer[<?php echo $row; ?>]");
	currFocus.focus();
</script>
