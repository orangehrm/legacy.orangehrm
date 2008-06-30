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

$injury = $records['injury'];
$authorizeObj = $records['authorizeObj'];

$assessmentId = $injury->getId();
$addMode = empty($assessmentId);

$styleDir = "../../themes/{$styleSheet}";
$picDir = $styleDir . "/pictures";
$cssDir = $styleDir . "/css";
				    				  
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
var baseUrl = '?healthcode=Injuries&action=';

function goBack() {
	location.href = "./CentralController.php?healthcode=Injuries&action=List";
}

function update() {
	err=false;
	msg='<?php echo $lang_Error_PleaseCorrectTheFollowing; ?>\n\n';

	empNum = $('txtRepEmpID').value.trim(); 
	if (empNum == '') {
		err=true;
		msg+="\t- <?php echo $lang_Health_Injury_Error_PleaseSpecifyEmployee; ?>\n";
	}

	injury = $('txtInjury').value.trim(); 
	if (injury == '') {
		err=true;
		msg+="\t- <?php echo $lang_Health_Injury_Error_PleaseSpecifyInjury; ?>\n";
	}
	
	incidentDate = $("txtIncidentDate").value.trim();
	incidentDateStamp = false;	
	if ((incidentDate != '') && (incidentDate != YAHOO.OrangeHRM.calendar.formatHint.format)) {
		incidentDateStamp = strToDate(incidentDate, YAHOO.OrangeHRM.calendar.format);
		if (!incidentDateStamp) {
			err=true;
			msg+="\t- <?php echo $lang_Health_Injury_Error_PleaseSpecifyValidIncidentDate; ?>\n";				
		}
	}
	
	reportedDate = $("txtReportedDate").value.trim();	
	reportedDateStamp = false;
	if ((reportedDate != '') && (reportedDate != YAHOO.OrangeHRM.calendar.formatHint.format)) {
		reportedDateStamp = strToDate(reportedDate, YAHOO.OrangeHRM.calendar.format);
		if (!reportedDateStamp) {
			err=true;
			msg+="\t- <?php echo $lang_Health_Injury_Error_PleaseSpecifyValidReportedDate; ?>\n";				
		}
	}
	
	if (incidentDateStamp && reportedDateStamp && (incidentDateStamp > reportedDateStamp)) {
		err=true;
		msg+="\t- <?php echo $lang_Health_Injury_Error_IncidentDateShouldBeBeforeReportedDate; ?>\n";						
	}

	timeOff = $('txtTimeOffWork').value.trim(); 
	if (timeOff != '') {
		if (!isDecimal(timeOff)) {
			err=true;
			msg+="\t- <?php echo $lang_Health_Injury_Error_TimeOffWorkShouldBeDecimal; ?>\n";
		}
	}		
	
	if (err) {
		alert(msg);
		return false;
	}

	$('frmInjury').action=baseUrl+'Update';
	$('frmInjury').submit();
}


function returnEmpDetail(){
		var popup=window.open('../../templates/hrfunct/emppop.php?reqcode=REP','Employees','height=450,width=400');
        if(!popup.opener) popup.opener=self;
		popup.focus();
}

YAHOO.OrangeHRM.container.init();
</script>
<h2>
<?php echo ($addMode) ? $lang_Health_Injury_AddTitle :	$lang_Health_Injury_EditTitle;	?>
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
	<form name="frmEmp" id="frmInjury" method="post" action="?healthcode=ErgonomicAssessment&action=">
		<div class="roundbox">
			<label for="cmbRepEmpID"><span class="error">*</span> <?php echo $lang_Health_Injury_EmpName; ?></label>									        		        
			<input type="text" name="cmbRepEmpID" id="cmbRepEmpID" disabled 
				value="<?php echo $injury->getEmpName(); ?>"/>
			<input class="button" type="button" value="..." onclick="returnEmpDetail();" />				
			<input type="hidden" name="txtRepEmpID" id="txtRepEmpID" value="<?php echo $injury->getEmpNumber(); ?>"/>
			<br/>

        	<label for="txtInjury"><span class="error">*</span> <?php echo $lang_Health_Injury_Injury; ?></label>	  
        	<input type="text" name="txtInjury" id="txtInjury" 
        		value="<?php echo CommonFunctions::escapeHtml($injury->getInjury()); ?>" />	        	
			<br />
			
	        <label for="txtIncidentDate"><?php echo $lang_Health_Injury_DateOfIncident; ?></label>
	        <input type="text" id="txtIncidentDate" name="txtIncidentDate" 
	        	value="<?php echo LocaleUtil::getInstance()->formatDate($injury->getIncidentDate());?>" size="10" tabindex="1" />
	        <input type="button" id="btnIncidentDate" name="btnIncidentDate" value="  " class="calendarBtn"/>
	       	<br />
	       	
	        <label for="txtReportedDate"><?php echo $lang_Health_Injury_DateReported; ?></label>
	        <input type="text" id="txtReportedDate" name="txtReportedDate" 
	        	value="<?php echo LocaleUtil::getInstance()->formatDate($injury->getReportedDate());?>" size="10" tabindex="1" />
	        <input type="button" id="btnReportedDate" name="btnReportedDate" value="  " class="calendarBtn"/>
			<br/>

        	<label for="txtDescription"><?php echo $lang_Health_Injury_Description; ?></label>	        	
        	<textarea id="txtDescription" name="txtDescription" > <?php echo CommonFunctions::escapeHtml($injury->getDescription()); ?></textarea>	        	
			<br />
			
        	<label for="txtTimeOffWork"><?php echo $lang_Health_Injury_TimeOffWork; ?> (<?php echo $lang_Health_Injury_Number_of_days; ?>)</label>	  
        	<input type="text" name="txtTimeOffWork" id="txtTimeOffWork" 
        		value="<?php echo CommonFunctions::escapeHtml($injury->getTimeOffWork()); ?>" />	
        	        	
			<br />

        	<label for="txtResult"><?php echo $lang_Health_Injury_Result; ?></label>	  
        	<input type="text" name="txtResult" id="txtResult" 
        		value="<?php echo CommonFunctions::escapeHtml($injury->getResult()); ?>" />	        	
			<br />
			
	        <label for="none">&nbsp;</label>
	        <input type="hidden" id="txtId" name="txtId" value="<?php echo $injury->getId(); ?>"/>
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
  </form>
</div>
<div id="notice"><?php echo preg_replace('/#star/', '<span class="error">*</span>', $lang_Commn_RequiredFieldMark); ?>.</div>
<div id="cal1Container" style="position:absolute;" ></div>
