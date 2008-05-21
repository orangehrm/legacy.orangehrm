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
$authorizeObj = $records['authorizeObj'];
$approverMode = ($_SESSION['isApprover'] && ($_SESSION['isAdmin']=='No') && (!$authorizeObj->isSupervisor()));

$perfReviewId = $perfReview->getId();

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

.scoreInput {
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    border-bottom: solid 1px black !important;
  	width: 50px !important;
    margin: 0px 0px 0px 15px !important;    
}
    
table.simpleList {
	margin-left: 10px;
}
</style>
<script type="text/javascript" src="../../scripts/archive.js"></script>
<script type="text/javascript" src="../../scripts/octopus.js"></script>

<script type="text/javascript">
var baseUrl = '?perfcode=PerfReviews&action=';

function goBack() {
	location.href = "./CentralController.php?perfcode=PerfReviews&action=View&id=<?php echo $perfReviewId;?>";
}

function update() {
	err=false;
	msg='<?php echo $lang_Error_PleaseCorrectTheFollowing; ?>\n\n';

	errors = new Array();

	var perfScores = document.frmPerfResults.elements["perfScores[]"];
	if (perfScores) {
		for (var i = 0; i < perfScores.length; i++) {
			score = trim(perfScores[i].value);
			if (score != '') {
				if (!isDecimal(score)) {
					err = true;
					msg = '<?php echo $lang_Performance_Review_Error_ScoreShouldBeDecimal; ?>';
					perfScores[i].focus();
					break;
				}
			}
		}
	}

	if (err) {
		alert(msg);
		return false;
	}

	$('frmPerfResults').action=baseUrl+'Update';
	$('frmPerfResults').submit();
}

</script>
<h2>
<?php echo $lang_Performance_Review_Results_Title;	?>
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
	<form name="frmPerfResults" id="frmPerfResults" method="post" action="?perfcode=PerfReviews&action=">
		<div class="roundbox">
			<label for="cmbRepEmpID"><span class="error">*</span> <?php echo $lang_Performance_Review_EmpNumber; ?></label>									        	
	        
			<input type="text" name="cmbRepEmpID" id="cmbRepEmpID" value="<?php echo  CommonFunctions::escapeHtml($perfReview->getEmployeeName());?>" readonly />
			<input type="hidden" name="txtRepEmpID" id="txtRepEmpID" value="<?php echo $perfReview->getEmpNumber();?>" />
			<br/>
			
	        <label for="txtReviewDate"><span class="error">*</span> <?php echo $lang_Performance_Review_Date; ?></label>
	        <input type="text" id="txtReviewDate" name="txtReviewDate" readonly  
	        	value="<?php echo LocaleUtil::getInstance()->formatDate($perfReview->getReviewDate());?>" size="10" /><br />

	        <?php if (true) {?>
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
					<label for="none"><?php echo $lang_Performance_Review_Status;?></label>
					<input type="text" disabled id="cmbStatusText" name="cmbStatusText"
		        		value="<?php echo (isset($reviewStatusList[$perfReview->getStatus()])) ? $reviewStatusList[$perfReview->getStatus()] : '';?>">
					<br />					
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
		<script type="text/javascript">
		<!--
		    if (document.getElementById && document.createElement) {
		 			initOctopus();
			}
		 -->
		</script>		
		<br /><br />
		<table class="simpleList" >
			<tr>
			   	<th width="125" style="align:left;"><?php echo $lang_Performance_Review_PerformanceMeasure; ?></th>
				<th width="1"/>
			   	<th width="80" style="align:center;"><?php echo $lang_Performance_Review_Score; ?></th>
			</tr>
			<?php
				$odd = false;
				foreach ($assignedPerfMeasures as $perfMeasure) {
					$cssClass = ($odd) ? 'even' : 'odd';
					$odd = !$odd;
			?>
			<tr><td class="<?php echo $cssClass;?>"><input type="hidden" name="cmbAssignedPerfMeasures[]" 
					value="<?php echo $perfMeasure->getId();?>"/><?php echo $perfMeasure->getName();?></td>
				<td class="<?php echo $cssClass;?>"></td>
				<td class="<?php echo $cssClass;?>">
					<input class="scoreInput" type="text" name="perfScores[]" value="<?php echo $perfMeasure->getScore();?>"					
						<?php echo ($approverMode) ? 'readonly' : '' ?> />
				</td>
			</tr>
			<?php } ?>
		</table>					
  </form>
</div>
<br />
<div id="notice"><?php echo preg_replace('/#star/', '<span class="error">*</span>', $lang_Commn_RequiredFieldMark); ?>.</div>
