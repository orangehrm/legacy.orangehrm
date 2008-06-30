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

$riskAssessment = $records['riskAssessment'];
$authorizeObj = $records['authorizeObj'];

$assessmentId = $riskAssessment->getId();
$addMode = empty($assessmentId);

$statusList = array(RiskAssessment::STATUS_UNRESOLVED => $lang_Health_RiskAssessment_Unresolved,
				    RiskAssessment::STATUS_RESOLVED => $lang_Health_RiskAssessment_Resolved);			
				    
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
var baseUrl = '?healthcode=RiskAssessments&action=';

function goBack() {
	location.href = "./CentralController.php?healthcode=RiskAssessments&action=List";
}

function update() {
	err=false;
	msg='<?php echo $lang_Error_PleaseCorrectTheFollowing; ?>\n\n';

	subDivisionId = $('cmbLocation').value.trim(); 
	if (subDivisionId == '') {
		err=true;
		msg+="\t- <?php echo $lang_Health_RiskAssessment_Error_PleaseSpecifySubDivision; ?>\n";
	}
	
	startDate = $("txtStartDate").value.trim();
	startDateStamp = false;		
	
	if ((startDate != '') && (startDate != YAHOO.OrangeHRM.calendar.formatHint.format)) {
	startDateStamp = strToDate(startDate, YAHOO.OrangeHRM.calendar.format);
		if (!startDateStamp) {
			err=true;
			msg+="\t- <?php echo $lang_Health_RiskAssessment_Error_PleaseSpecifyValidStartDate; ?>\n";				
		}
	}
	
	endDate = $("txtEndDate").value.trim();
	endDateStamp = false;			
	
	if ((endDate != '') && (endDate != YAHOO.OrangeHRM.calendar.formatHint.format)) {
		endDateStamp = strToDate(endDate, YAHOO.OrangeHRM.calendar.format);
		if (!endDateStamp) {
			err=true;
			msg+="\t- <?php echo $lang_Health_RiskAssessment_Error_PleaseSpecifyValidEndDate; ?>\n";				
		}
	}
	
	if (startDateStamp && endDateStamp && (startDateStamp > endDateStamp)) {
		err=true;
		msg+="\t- <?php echo $lang_Health_RiskAssessment_Error_StartDateShouldBeBeforeEndDate; ?>\n";						
	}
	
	if (err) {
		alert(msg);
		return false;
	}

	$('frmRiskReview').action=baseUrl+'Update';
	$('frmRiskReview').submit();
}

function viewSubDivisionPopUp(){
	var popup=window.open('CentralController.php?uniqcode=CST&VIEW=MAIN&esp=1','Locations','height=450,width=400,resizable=1');
    if(!popup.opener) popup.opener=self;
}

YAHOO.OrangeHRM.container.init();
</script>
<h2>
<?php echo ($addMode) ? $lang_Health_RiskAssessment_AddTitle :	$lang_Health_RiskAssessment_EditTitle;	?>
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
	<form name="frmEmp" id="frmRiskReview" method="post" action="?healthcode=RiskAssessment&action=">
		<div class="roundbox">
			<label for="txtLocation"><span class="error">*</span> <?php echo $lang_Health_RiskAssessment_SubDivision; ?></label>									        		        
			<input type="text" name="txtLocation" id="txtLocation" disabled 
				value="<?php echo $riskAssessment->getSubdivisionName(); ?>"/>
			<input type="hidden" name="cmbLocation" id="cmbLocation" value="<?php echo $riskAssessment->getSubdivisionId(); ?>"/>				
			<input class="button" type="button" value="..." onclick="viewSubDivisionPopUp();" />				
			<br/>
			
	        <label for="txtStartDate"><?php echo $lang_Health_RiskAssessment_StartDate; ?></label>
	        <input type="text" id="txtStartDate" name="txtStartDate" 
	        	value="<?php echo LocaleUtil::getInstance()->formatDate($riskAssessment->getStartDate());?>" size="10" tabindex="1" />
	        <input type="button" id="btnStartDate" name="btnStartDate" value="  " class="calendarBtn"/>
	       	<br />
	        <label for="txtEndDate"><?php echo $lang_Health_RiskAssessment_EndDate; ?></label>
	        <input type="text" id="txtEndDate" name="txtEndDate" 
	        	value="<?php echo LocaleUtil::getInstance()->formatDate($riskAssessment->getEndDate());?>" size="10" tabindex="1" />
	        <input type="button" id="btnEndDate" name="btnEndDate" value="  " class="calendarBtn"/>

			<br/>
			
        	<label for="txtDescription"><?php echo $lang_Health_RiskAssessment_Description; ?></label>	        	
        	<textarea id="txtDescription" name="txtDescription" > <?php echo CommonFunctions::escapeHtml($riskAssessment->getDescription()); ?></textarea>	        	
			<br />
			
        	<label for="cmbStatus"><?php echo $lang_Health_RiskAssessment_Status; ?></label>	        	
			<select name="cmbStatus" id="cmbStatus" >
		  		<?php 
		  			foreach ($statusList as $statusCode=>$statusDesc) {
		  				$selected = ($statusCode == $riskAssessment->getStatus()) ? 'selected' : ''; 
		  				echo "<option value='" . $statusCode . "' $selected >" .$statusDesc. "</option>";
		  			} ?>
			</select>			  	        	
        	<br />

	        <label for="none">&nbsp;</label>
	        <input type="hidden" id="txtId" name="txtId" value="<?php echo $riskAssessment->getId(); ?>"/>
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
