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

$ergonomicAssessment = $records['ergonomicAssessment'];
$authorizeObj = $records['authorizeObj'];

$assessmentId = $ergonomicAssessment->getId();
$addMode = empty($assessmentId);

$statusList = array(ErgonomicAssessment::STATUS_INCOMPLETE => $lang_Health_ErgonomicAssessment_Incomplete,
				    ErgonomicAssessment::STATUS_COMPLETE => $lang_Health_ErgonomicAssessment_Complete);			
				    
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
var baseUrl = '?healthcode=ErgonomicAssessments&action=';

function goBack() {
	location.href = "./CentralController.php?healthcode=ErgonomicAssessments&action=List";
}

function update() {
	err=false;
	msg='<?php echo $lang_Error_PleaseCorrectTheFollowing; ?>\n\n';

	empNum = $('txtRepEmpID').value.trim(); 
	if (empNum == '') {
		err=true;
		msg+="\t- <?php echo $lang_Health_ErgonomicAssessment_Error_PleaseSpecifyEmployee; ?>\n";
	}
	
	startDateStamp = strToDate($("txtStartDate").value, YAHOO.OrangeHRM.calendar.format);
	if (!startDateStamp) {
		err=true;
		msg+="\t- <?php echo $lang_Health_ErgonomicAssessment_Error_PleaseSpecifyValidStartDate; ?>\n";				
	}
	endDateStamp = strToDate($("txtEndDate").value, YAHOO.OrangeHRM.calendar.format);
	if (!endDateStamp) {
		err=true;
		msg+="\t- <?php echo $lang_Health_ErgonomicAssessment_Error_PleaseSpecifyValidEndDate; ?>\n";				
	}
	if (startDateStamp && endDateStamp && (startDateStamp > endDateStamp)) {
		err=true;
		msg+="\t- <?php echo $lang_Health_ErgonomicAssessment_Error_StartDateShouldBeBeforeEndDate; ?>\n";						
	}
	
	if (err) {
		alert(msg);
		return false;
	}

	$('frmErgonomicReview').action=baseUrl+'Update';
	$('frmErgonomicReview').submit();
}


function returnEmpDetail(){
		var popup=window.open('../../templates/hrfunct/emppop.php?reqcode=REP','Employees','height=450,width=400');
        if(!popup.opener) popup.opener=self;
		popup.focus();
}

YAHOO.OrangeHRM.container.init();
</script>
<h2>
<?php echo ($addMode) ? $lang_Health_ErgonomicAssessment_AddTitle :	$lang_Health_ErgonomicAssessment_EditTitle;	?>
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
	<form name="frmEmp" id="frmErgonomicReview" method="post" action="?healthcode=ErgonomicAssessment&action=">
		<div class="roundbox">
			<label for="cmbRepEmpID"><span class="error">*</span> <?php echo $lang_Health_ErgonomicAssessment_EmpName; ?></label>									        		        
			<input type="text" name="cmbRepEmpID" id="cmbRepEmpID" disabled 
				value="<?php echo $ergonomicAssessment->getEmpName(); ?>"/>
			<input class="button" type="button" value="..." onclick="returnEmpDetail();" />				
			<input type="hidden" name="txtRepEmpID" id="txtRepEmpID" />
			<br/>
			
	        <label for="txtStartDate"><span class="error">*</span> <?php echo $lang_Health_ErgonomicAssessment_StartDate; ?></label>
	        <input type="text" id="txtStartDate" name="txtStartDate" 
	        	value="<?php echo LocaleUtil::getInstance()->formatDate($ergonomicAssessment->getStartDate());?>" size="10" tabindex="1" />
	        <input type="button" id="btnStartDate" name="btnStartDate" value="  " class="calendarBtn"/>
	       	<br />
	        <label for="txtEndDate"><span class="error">*</span> <?php echo $lang_Health_ErgonomicAssessment_EndDate; ?></label>
	        <input type="text" id="txtEndDate" name="txtEndDate" 
	        	value="<?php echo LocaleUtil::getInstance()->formatDate($ergonomicAssessment->getEndDate());?>" size="10" tabindex="1" />
	        <input type="button" id="btnEndDate" name="btnEndDate" value="  " class="calendarBtn"/>

			<br/>
        	<label for="cmbStatus"><?php echo $lang_Health_ErgonomicAssessment_Status; ?></label>	        	
			<select name="cmbStatus" id="cmbStatus" >
		  		<?php 
		  			foreach ($statusList as $statusCode=>$statusDesc) {
		  				$selected = ($statusCode == $ergonomicAssessment->getStatus()) ? 'selected' : ''; 
		  				echo "<option value='" . $statusCode . "' $selected >" .$statusDesc. "</option>";
		  			} ?>
			</select>			  	        	
        	<br />

        	<label for="txtNotes"><?php echo $lang_Health_ErgonomicAssessment_Notes; ?></label>	        	
        	<textarea id="txtNotes" name="txtNotes" > <?php echo CommonFunctions::escapeHtml($ergonomicAssessment->getNotes()); ?></textarea>	        	
			<br />
	        <label for="none">&nbsp;</label>
	        <input type="hidden" id="txtId" name="txtId" value="<?php echo $ergonomicAssessment->getId(); ?>"/>
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
