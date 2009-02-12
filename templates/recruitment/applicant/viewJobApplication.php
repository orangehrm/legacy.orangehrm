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
 */

/**
 * Xajax call to get list of provinces for the selected country.
 */

// Unfortunately, the only way to make this variable available to populateStates()
$GLOBALS['lang_Common_Select'] = $lang_Common_Select;

/**
 * Populates the states list based on selected country
 *
 * @param String $country Country code of currently selected country.
 */
function populateStates($country) {

	$objResponse = new xajaxResponse();
	$provinceList = RecruitmentController::getProvinceList($country);

	if ($provinceList) {
		$xajaxFiller = new xajaxElementFiller();
		$xajaxFiller->setDefaultOptionName($GLOBALS['lang_Common_Select']);
		$objResponse->addAssign('state','innerHTML',
				'<select name="txtProvince" id="txtProvince" name="txtProvince" tabindex="8"><option value="0">--- '.$GLOBALS['lang_Common_Select'].' ---</option></select>');
		$objResponse = $xajaxFiller->cmbFillerById($objResponse, $provinceList, 1, 'fromJobApplication.state', 'txtProvince');
	} else {
		$objResponse->addAssign('state','innerHTML','<input type="text" id="txtProvince" name="txtProvince" tabindex="8" >');
	}
	$objResponse->addScript('hideLoading();formJobApplication.txtProvince.focus();');
	return $objResponse->getXML();
}

$objAjax = new xajax();
$objAjax->registerFunction('populateStates');
$objAjax->processRequests();

$vacancy = $records['vacancy'];
$countryList = $records['countryList'];
$company = $records['company'];
$applicationFields=$records['applicationFields'];
$skills=$records['skills'];
$licenses=$records['licensesCodes'];
$languages=$records['language'];
$flencies=$records['fluency'];
$formAction = $_SERVER['PHP_SELF'] . '?recruitcode=ApplicantApply';

$picDir = "../../themes/{$styleSheet}/pictures/";
$iconDir = "../../themes/{$styleSheet}/icons/";

$backImg = $picDir . 'btn_back.gif';
$backImgPressed = $picDir . 'btn_back_02.gif';

$saveImg = $iconDir . 'apply.gif';

$addImg = $picDir . 'btn_add.gif';
$saveImgPressed = $iconDir . 'apply_o.gif';

$clearImg = $picDir . 'btn_clear.gif';
$clearImgPressed = $picDir . 'btn_clear_02.gif';

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="../../scripts/archive.js"></script>
<script type="text/javascript" src="../../scripts/octopus.js"></script>
<?php
	$objAjax->printJavascript();
?>
<?php include ROOT_PATH."/lib/common/calendar.php"; ?>
<script type="text/javascript" src="../../scripts/archive.js"></script>
<script src="../../scripts/time.js"></script>

<script>

    function goBack() {
        location.href = "<?php echo "{$_SERVER['PHP_SELF']}?recruitcode=ApplicantViewJobs"; ?>";
    }

	function validate() {
		err = false;

		var msg = '<?php echo $lang_Error_PleaseCorrectTheFollowing; ?>\n\n';

		if(emplyeeinfoCount==0){
			msg+='Please enter employeement information \n'; err=true;
		}
		if(skillCount==0){
			msg+='Please enter Skills information \n'; err=true;
		}
		if(licenseInfoCount==0){
			msg+='Please enter Licenses information \n'; err=true;
		}
		if(languageInfoCount==0){
			msg+='Please enter Language information \n'; err=true;
		}
		if(educationInfoCount==0){
			msg+='Please enter Educational information \n'; err=true;
		}

		var errors = new Array();
		var fields = new Array("txtFirstName", "txtMiddleName", "txtLastName", "txtStreet1",
						"txtStreet2", "txtCity", "txtCountry", "txtProvince", "txtZip", "txtPhone",
						"txtMobile", "txtEmail");

		var fieldNames = new Array('<?php echo $lang_Recruit_ApplicationForm_FirstName;?>',
						'<?php echo $lang_Recruit_ApplicationForm_MiddleName;?>',
						'<?php echo $lang_Recruit_ApplicationForm_LastName;?>',
						'<?php echo $lang_Recruit_ApplicationForm_Street1;?>',
						'<?php echo $lang_Recruit_ApplicationForm_Street2;?>',
						'<?php echo $lang_Recruit_ApplicationForm_City;?>',
						'<?php echo $lang_Recruit_ApplicationForm_Country;?>',
						'<?php echo $lang_Recruit_ApplicationForm_StateProvince;?>',
						'<?php echo $lang_Recruit_ApplicationForm_Zip;?>',
						'<?php echo $lang_Recruit_ApplicationForm_Phone;?>',
						'<?php echo $lang_Recruit_ApplicationForm_Mobile;?>',
						'<?php echo $lang_Recruit_ApplicationForm_Email;?>',
						'<?php echo $lang_Recruit_ApplicationForm_Qualifications;?>');

		// compulsary fields
		var compFields = new Array(0, 2, 3, 6, 7, 8, 9, 10, 11);
		var emailFields = new Array();
		emailFields[0] = 11;
		var phoneFields = new Array();
		phoneFields[0] = 9;
		phoneFields[1] = 10;

		// validate compulsary fields
		var numCompFields = compFields.length;
		for (var i = 0; i < numCompFields; i++ ) {
			var fieldNdx = compFields[i];
			var fieldName = fields[fieldNdx];
		    var value = $(fieldName).value.trim();
		    if (value == '') {
				err = true;
				msg += "\t- <?php echo $lang_Recruit_ApplicationForm_PleaseSpecify ?>" + fieldNames[fieldNdx] + "\n";
		    }
		}

		if ($('txtCountry').value == '0') {
			err = true;
			msg += "\t- <?php echo $lang_Recruit_ApplicationForm_PleaseSelect . $lang_Recruit_ApplicationForm_Country?>\n";
		}

		if ($('txtProvince').value == '0') {
			err = true;
			msg += "\t- <?php echo $lang_Recruit_ApplicationForm_PleaseSelect . $lang_Recruit_ApplicationForm_StateProvince ?>\n";
		}

		//
		// Only check validation if all compulsary fields have been specified
		//
		if (err == false) {

			// validate email fields
			var numEmailFields = emailFields.length;
			for (var i = 0; i < numEmailFields; i++ ) {
				var fieldNdx = emailFields[i];
				var fieldName = fields[fieldNdx];
			    var value = $(fieldName).value.trim();
			    if (!checkEmail(value)) {
					err = true;
					msg += "\t- <?php echo $lang_Recruit_ApplicationForm_PleaseSpecifyValidEmail ?>" + fieldNames[fieldNdx] + "\n";
			    }
			}

			// validate phone fields
			var numPhoneFields = phoneFields.length;
			for (var i = 0; i < numPhoneFields; i++ ) {
				var fieldNdx = phoneFields[i];
				var fieldName = fields[fieldNdx];
			    var field = $(fieldName);
			    if (!checkPhone(field)) {
					err = true;
					msg += "\t- <?php echo $lang_Recruit_ApplicationForm_PleaseSpecifyValidPhone ?>" + fieldNames[fieldNdx] + "\n";
			    }
			}
		}
		if (err) {
			alert(msg);
			return false;
		} else {
			return true;
		}
	}

    function save() {

		if (validate()) {
        	$('fromJobApplication').submit();
		} else {
			return false;
		}
    }

	function reset() {
		$('fromJobApplication').reset();
	}

	/*
	 * Get list of provinces for the selected country
	 */
	function getProvinceList(country) {
		showLoading();
		xajax_populateStates(country);
	}

	function hideLoading() {
		status = $('status');
		status.style.display = 'none';
	}

	function showLoading() {
		status = $('status');
		status.style.display = 'block';
	}

	var emplyeeinfoCount=0;
	var skillCount=0;
	var licenseInfoCount=0;
	var languageInfoCount=0;
	var educationInfoCount=0;

	function validateListBox(){

	}

	function addMore(cat) {
		var err=false;
		var msg='';
		tbody = document.getElementById(cat);
		var tableRow = document.createElement('tr');
		str='';
		if(cat=='employeement_info'){
			 emplyeeinfoCount++;
			 elementName=cat+'_'+emplyeeinfoCount;
			 tableRow.setAttribute('id',elementName);

			 td=document.createElement('td');
			 value=document.getElementById('form_employer').value;
			 td.innerHTML="<input type='hidden' id='employer[]' name='employer[]' value='"+value+"' />"+value;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 value=document.getElementById('form_job_title').value;
			 td.innerHTML="<input type='hidden' id='job_title[]' name='job_title[]' value='"+value+"' />"+value;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 value=document.getElementById('form_start_date').value;
			 td.innerHTML="<input type='hidden' id='start_date[]' name='start_date[]' value='"+value+"' />"+value;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 value=document.getElementById('form_end_date').value;
			 td.innerHTML="<input type='hidden' id='end_date[]' name='end_date[]' value='"+value+"' />"+value;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 value=document.getElementById('form_duties').value;
			 td.innerHTML="<input type='hidden' id='duties[]' name='duties[]' value='"+value+"'>"+value;
			 td.setAttribute('style', 'white-space:pre');
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 td.innerHTML="<input type='button' value='delete' onclick=\"deleteRow('"+emplyeeinfoCount+"','"+cat+"')\" style='width:50px;'/>";
			 tableRow.appendChild(td);
		}

		if(cat=='skill_info'){
			 skillCount++;
			 elementName=cat+'_'+skillCount;
			 tableRow.setAttribute('id',elementName);

			 td=document.createElement('td');
			 elm=document.getElementById("form_skill");
			 value=elm.value;
			 text= elm.options[elm.selectedIndex].text;
			 if(value< 0){
			 	//err=true; msg='Please select a Skill';
				 if(value< 0){
					alert('Please select a skill');
					exit;
				}
			 }
			 td.innerHTML="<input type='hidden' id='skill[]' name='skill[]' value='"+value+"' />"+text;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 value=document.getElementById('form_skill_years_of_experience').value;
			 if(!numeric(document.getElementById('form_skill_years_of_experience'))|| value==''){
					alert('Please enter a numeric vlaue for the year');
					document.getElementById('form_skill_years_of_experience').focus();
					exit;
			 }
			 td.innerHTML="<input type='hidden' id='skill_years_of_experience[]' name='skill_years_of_experience[]' value='"+value+"' />"+value;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 value=document.getElementById('form_skill_comments').value;
			 td.innerHTML="<input type='hidden' id='skill_comments[]' name='skill_comments[]' value='"+value+"'>"+value;
			 td.setAttribute('style', 'white-space:pre');
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 td.innerHTML="<input type='button' value='delete' onclick=\"deleteRow('"+skillCount+"','"+cat+"')\" style='width:50px;'/>";
			 tableRow.appendChild(td);
		}

		if(cat=='license_info'){
			 licenseInfoCount++;
			 elementName=cat+'_'+licenseInfoCount;
			 tableRow.setAttribute('id',elementName);

			 td=document.createElement('td');
			 elm=document.getElementById("form_license_type");
			 value=elm.value;
			 text= elm.options[elm.selectedIndex].text;
			 if(value< 0){
			 	err=true; msg='Please select a license type';
			 }
			 td.innerHTML="<input type='hidden' id='license_type[]' name='license_type[]' value='"+value+"' />"+text;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 value=document.getElementById('form_licens_exp_date').value;
			 td.innerHTML="<input type='hidden' id='licens_exp_date[]' name='licens_exp_date[]' value='"+value+"' />"+value;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 td.innerHTML="<input type='button' value='delete' onclick=\"deleteRow('"+licenseInfoCount+"','"+cat+"')\" style='width:50px;'/>";
			 tableRow.appendChild(td);
		}

		if(cat=='language_info'){
			 languageInfoCount++;
			 elementName=cat+'_'+languageInfoCount;
			 tableRow.setAttribute('id',elementName);

			 td=document.createElement('td');
			 elm=document.getElementById("form_language_language");
			 value=elm.value;
			 text= elm.options[elm.selectedIndex].text;
			 if(value< 0){
			 	err=true; msg='Please select a Language';
			 }
			 td.innerHTML="<input type='hidden' id='language_language[]' name='language_language[]' value='"+value+"' />"+text;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 elm=document.getElementById("form_language_fluency");
			 value=elm.value;
			 text= elm.options[elm.selectedIndex].text;
			 if(value< 0){
			 	err=true; msg='Please select a Fluency';
			 }
			 td.innerHTML="<input type='hidden' id='language_fluency[]' name='language_fluency[]' value='"+value+"' />"+text;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 td.innerHTML="<input type='button' value='delete' onclick=\"deleteRow('"+languageInfoCount+"','"+cat+"')\" style='width:50px;'/>";
			 tableRow.appendChild(td);
		}

		if(cat=='education_info'){
			 educationInfoCount++;
			 elementName=cat+'_'+educationInfoCount;
			 tableRow.setAttribute('id',elementName);

			 td=document.createElement('td');
			 value=document.getElementById('form_education_education').value;
			 td.innerHTML="<input type='hidden' id='education_education[]' name='education_education[]' value='"+value+"' />"+value;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 value=document.getElementById('form_education_major').value;
			 td.innerHTML="<input type='hidden' id='education_major[]' name='education_major[]' value='"+value+"' />"+value;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 value=document.getElementById('form_education_year').value;
			 if(!numeric(document.getElementById('form_education_year'))|| value==''){
					alert('Please enter a numeric vlaue for the year');
					document.getElementById('form_education_year').focus();
					exit;
			 }
			 td.innerHTML="<input type='hidden' id='education_year[]' name='education_year[]' value='"+value+"' />"+value;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 value=document.getElementById('form_education_score').value;
			 if(!numeric(document.getElementById('form_education_score'))|| value==''){
					alert('Please enter a numeric vlaue for the average score ');
					document.getElementById('form_education_score').focus();
					exit;
			 }
			 td.innerHTML="<input type='hidden' id='education_score[]' name='education_score[]' value='"+value+"' />"+value;
			 tableRow.appendChild(td);

			 td=document.createElement('td');
			 td.innerHTML="<input type='button' value='delete' onclick=\"deleteRow('"+educationInfoCount+"','"+cat+"')\" style='width:50px;'/>";
			 tableRow.appendChild(td);
		}
		if(value< 0){
			 	err=true; msg='Please select a Fluency';
			 }
			 if(err){
			 	alert(msg);
			 }else{
			 	tbody.appendChild(tableRow);
			 }

	}

	function deleteRow(applicatonInfo,cat){
		tbody =  $(cat);
		var element = document.getElementById(cat+'_'+applicatonInfo);
		tbody.removeChild(element);
		eval("applicatonInfo=applicatonInfo-1");

	}

</script>

    <link href="../../themes/<?php echo $styleSheet;?>/css/style.css" rel="stylesheet" type="text/css">
    <style type="text/css">@import url("../../themes/<?php echo $styleSheet;?>/css/style.css"); </style>

    <style type="text/css">
    <!--

    label,select,input,textarea {
        display: block;  /* block float the labels to left column, set a width */
        width: 200px;
        float: left;
        margin: 8px 0px 2px 0px; /* set top margin same as form input - textarea etc. elements */
    }
    input[type=checkbox] {
		width: 15px;
		background-color: transparent;
		vertical-align: bottom;
    }

    /* this is needed because otherwise, hidden fields break the alignment of the other fields */
    input[type=hidden] {
        display: none;
        border: none;
        background-color: red;
    }

    label {
        text-align: left;
        width: 150px;
        padding-left: 10px;
    }

    select,input,textarea {
        margin-left: 10x;
    }

    input,textarea {
        padding-left: 4px;
        padding-right: 4px;
    }

    textarea {
        width: 500px;
        height: 90px;
    }

    form {
        min-width: 550px;
        max-width: 900px;
    }

    br {
        clear: left;
    }

    .roundbox {
        margin-top: 10px;
        margin-left: auto;
        margin-right: auto;
        width: 900px;
    }

    body {
    	margin-top: 10px;
        margin-left: auto;
        margin-right: auto;
        width: 900px;
    }

    .roundbox_content {
        padding:5px;
    }

	.hidden {
		display: none;
	}

	.display-block {
		display: block;
	}

	.positionApplyingFor {
        padding-left: 17px;
        padding-top: 10px;
	}
    -->
</style>
</head>
<body>
	<p><h2 class="moduleTitle"><?php echo $lang_Recruit_ApplicationForm_Heading; echo empty($company) ? "({$lang_Recruit_Application_CompanyNameNotSet})" : $company; ?></h2></p>
	<div id="status" style="float:right;display:none;">
		<image src='<?php echo $iconDir; ?>/loading.gif' width='20' height='20' style="vertical-align: bottom;">
		<?php echo $lang_Commn_PleaseWait;?>
	</div>
  	<div id="navigation" style="margin:0;">
  		<img title="<?php echo $lang_Common_Back;?>" onMouseOut="this.src='<?php echo $backImg; ?>';"
  			 onMouseOver="this.src='<?php echo $backImgPressed;?>';" src="<?php echo $backImg;?>"
  			 onClick="goBack();">
	</div>
    <?php $message =  isset($_GET['message']) ? $_GET['message'] : null;
    	if (isset($message)) {
			$col_def = CommonFunctions::getCssClassForMessage($message);
			$message = "lang_Common_" . $message;
	?>
	<div class="message">
		<font class="<?php echo $col_def?>" size="-1" face="Verdana, Arial, Helvetica, sans-serif">
			<?php echo (isset($$message)) ? $$message: ""; ?>
		</font>
	</div>
	<?php }	?>
  <div class="roundbox">
  <form name="fromJobApplication" id="fromJobApplication" method="post" action="<?php echo $formAction;?>" enctype="multipart/form-data">
  		<div class="positionApplyingFor">
  		<?php echo $lang_Recruit_ApplicationForm_Position . ' : ' . $vacancy->getJobTitleName(); ?><br/>
  		</div>
		<input type="hidden" id="txtVacancyId" name="txtVacancyId" value="<?php echo $vacancy->getId();?>"/>

		<label for="txtFirstName"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_FirstName; ?></label>
        <input type="text" id="txtFirstName" name="txtFirstName" tabindex="1" >

		<label for="txtMiddleName"><span class="error">&nbsp;</span> <?php echo $lang_Recruit_ApplicationForm_MiddleName; ?></label>
		<input type="text" id="txtMiddleName" name="txtMiddleName" tabindex="2" >
		<br/>

		<label for="txtLastName"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_LastName; ?></label>
        <input type="text" id="txtLastName" name="txtLastName" tabindex="3" >

        <label for="date_of_birth"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_DateOfBirth; ?></label>
        <input type="text" id="date_of_birth" name="date_of_birth" readonly onchange="fillToDate();" onfocus="fillToDate();" >
        <input type="button" name="Submit" value="" class="calendarBtn" style="width:20px;"/><br/>

		<label for="txtStreet1"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_Street1; ?></label>
        <input type="text" id="txtStreet1" name="txtStreet1" tabindex="4" >

		<label for="txtStreet2"><span class="error">&nbsp;</span> <?php echo $lang_Recruit_ApplicationForm_Street2; ?></label>
        <input type="text" id="txtStreet2" name="txtStreet2" tabindex="5"><br/>

		<label for="txtCity"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_City; ?></label>
        <input type="text" id="txtCity" name="txtCity" tabindex="6" >

		<label for="txtCountry"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_Country; ?></label>
		<select  id="txtCountry" name="txtCountry" tabindex="7"
			onChange="getProvinceList(this.value);">
	  		<option value="0">-- <?php echo $lang_districtinformation_selectcounlist?> --</option>
			<?php
				  foreach($countryList as $country) {
	    				echo "<option value='" . $country[0] . "'>" . $country[1] . "</option>";
				  }
		    ?>
		 </select><br/>

		<label for="txtProvince"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_StateProvince; ?></label>
        <div id="state"><input type="text" id="txtProvince" name="txtProvince" tabindex="8" ></div>

		<label for="txtZip"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_Zip; ?></label>
        <input type="text" id="txtZip" name="txtZip" tabindex="9" ></br>

		<label for="txtPhone"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_Phone; ?></label>
        <input type="text" id="txtPhone" name="txtPhone" tabindex="10" >

		<label for="txtMobile"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_Mobile; ?></label>
        <input type="text" id="txtMobile" name="txtMobile" tabindex="11" ><br/>

		<label for="txtEmail"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_Email; ?></label>
        <input type="text" id="txtEmail" name="txtEmail" tabindex="12" >

        <label for="salary_expected"><span class="error">&nbsp;</span> <?php echo $lang_Recruit_ApplicationForm_Salary_Expected; ?></label>
        <input type="text" id="salary_expected" name="salary_expected" ><br/>

        <label for="it_experience"><span class="error">&nbsp;</span> <?php echo $lang_Recruit_ApplicationForm_It_Experience; ?></label>
        <input type="text" id="it_experience" name="it_experience" >

        <label for="availability_to_start"><span class="error">&nbsp;</span> <?php echo $lang_Recruit_ApplicationForm_Availability_To_Start; ?></label>
        <input type="text" id="availability_to_start" name="availability_to_star" readonly onchange="fillToDate();" onfocus="fillToDate();" >
        <input type="button" name="Submit" value="" class="calendarBtn" style="width:20px;"/><br/>

        <label for="basis_of_employeement"><span class="error">&nbsp;</span> <?php echo $lang_Recruit_ApplicationForm_Basis_Of_Employement; ?></label>
        <input type="text" id="basis_of_employeement" name="basis_of_employeement" >

        <label for="do_u_have_a_car"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_Do_You_Have_A_Car; ?></label>
        Yes&nbsp;&nbsp;<input type="radio" id="do_u_have_a_car" name="do_u_have_a_car" value="y" checked="checked" style="margin-right: 20px;">
        No<input type="radio" id="do_u_have_a_car" name="do_u_have_a_car" value="n" >
        <br/>

        <label for="gender"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_Gender; ?></label>
        Male&nbsp;&nbsp;<input type="radio" id="gender" name="gender" value="m" checked="checked" style="margin-right: 20px;">
        Female<input type="radio" id="gender" name="gender" value="f" >
        <br/>


        <!-- Employment Information----------------------------------------->
        <label for="txtEmail"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_Employment_Infomation; ?></label><br/>
        <div style="margin-left: 20px;">
	        <label for="employer"><?php echo $lang_hrEmpMain_employer; ?></label> <input type="text" id="form_employer" name="form_employer" >
	        <label for="job_title"><?php echo $lang_empview_JobTitle; ?></label> <input type="text" id="form_job_title" name="form_job_title" ><br/>
	        <label for="start_date"><?php echo $lang_hrEmpMain_startdate; ?></label> <input type="text" readonly id="form_start_date" name="form_start_date"  onchange="fillToDate();" onfocus="fillToDate();" >
	        	<input type="button" name="Submit" value="" class="calendarBtn" style="width:20px;"  />
	        <label for="end_date"><?php echo $lang_hrEmpMain_enddate; ?></label> <input type="text" readonly id="form_end_date" name="form_end_date"  onchange="fillToDate();" onfocus="fillToDate();" >
	        	<input type="button" name="Submit" value="" class="calendarBtn" style="width:20px;"/><br/>
	        <label for="duties"><?php echo $lang_jobspec_duties ; ?></label> <textarea type="text" id="form_duties" name="form_duties"></textarea><br/>
		<img onClick="addMore('employeement_info');" id="saveBtn"	src="<?php echo $addImg;?>">
		<table border="1" cellpadding="4" style="vertical-align: top; border-style: solid;border-color: gray;border-collapse: collapse">
		<tbody id="employeement_info" >

		</tbody>
		</table>
		</div>

		<!-- Skills ----------------------------------------->
		<label for="txtEmail"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_Skills; ?></label><br/>
        <div style="margin-left: 20px;">
	        <label for="skill"><?php echo $lang_Recruit_ApplicationForm_Skills; ?></label>
	        <select id="form_skill" name="form_skill">
	        <option value="-1" selected="selected">-select-</option>
	        <?php	if(sizeof($skills)>0){
	        			foreach ($skills as $skill){?>
	        				<option value="<?php echo $skill[0] ?>"><?php echo $skill[1] ?></option>
	        <?php 		}
	       			}  ?>
	        </select>
	        <label for="form_skill_years_of_experience"><?php echo $lang_Recruit_ApplicationForm_Years_of_experience; ?></label> <input type="text" id="form_skill_years_of_experience" name="form_skill_years_of_experience" ><br/>
	        <label for="skill_comments"><?php echo $lang_Recruit_ApplicationForm_Skill_Comments; ?></label> <textarea type="text" id="form_skill_comments" name="form_skill_comments"></textarea><br/>
		<img onClick="addMore('skill_info');" id="saveBtn"	src="<?php echo $addImg;?>">
		<table border="1" cellpadding="4" style="vertical-align: top; border-style: solid;border-color: gray;border-collapse: collapse">
		<tbody id="skill_info" >

		</tbody>
		</table>
		</div>

		<!-- License Information ----------------------------------------->
		<label for="txtEmail"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_License_Information; ?></label><br/>
        <div style="margin-left: 20px;">
	        <label for="form_license_type"><?php echo $lang_hremplicenses_licentype; ?></label>
	        <select id="form_license_type" name="form_license_type">
	        <option value="-1" selected="selected">-select-</option>
	        <?php	if(sizeof($licenses)>0){
	        			foreach ($licenses as $license){?>
	        				<option value="<?php echo $license[0] ?>"><?php echo $license[1] ?></option>
	        <?php 		}
	       			}  ?>
	        </select>
	        <label for="form_licens_exp_date"><?php echo $lang_Recruit_ApplicationForm_License_Expiry_Date; ?></label> <input type="text" readonly id="form_licens_exp_date" name="form_licens_exp_date"  onchange="fillToDate();" onfocus="fillToDate();" >
	        	<input type="button" name="Submit" value="" class="calendarBtn" style="width:20px;"  /><br/>
		<img onClick="addMore('license_info');" id="saveBtn"	src="<?php echo $addImg;?>">
		<table border="1" cellpadding="4" style="vertical-align: top; border-style: solid;border-color: gray;border-collapse: collapse">
		<tbody id="license_info" >

		</tbody>
		</table>
		</div>


		<!--Language Information ----------------------------------------->
		<label for="txtEmail"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_Language_Information; ?></label><br/>
        <div style="margin-left: 20px;">
	        <label for="form_language_language"><?php echo $lang_Recruit_ApplicationForm_Language; ?></label>
	         <select id="form_language_language" name="form_language_language">
	        <option value="-1" selected="selected">-select-</option>
	        <?php	if(!empty($languages)){
	        			foreach ($languages as $language){?>
	        				<option value="<?php echo $language[0] ?>"><?php echo $language[1] ?></option>
	        <?php 		}
	       			}  ?>
	        </select>

	        <label for="form_language_fluency"><?php echo $lang_Recruit_ApplicationForm_Fluency; ?></label>
	        <select id="form_language_fluency" name="form_language_fluency">
	        <option value="-1" selected="selected">-select-</option>
	        <?php	if(sizeof($flencies)>0){
	        			foreach ($flencies as $flency){?>
	        				<option value="<?php echo $flency[0] ?>"><?php echo $flency[1] ?></option>
	        <?php 		}
	       			}  ?>
	        </select><br/>
		<img onClick="addMore('language_info');" id="saveBtn"	src="<?php echo $addImg;?>"><br/>
		<table border="1" cellpadding="4" style="vertical-align: top; border-style: solid;border-color: gray;border-collapse: collapse">
		<tbody id="language_info" >

		</tbody>
		</table>
		</div>

		<!--Education Information ----------------------------------------->
		<label for="txtEmail"><span class="error">*</span> <?php echo $lang_Recruit_ApplicationForm_Education_Information; ?></label><br/>
        <div style="margin-left: 20px;">
	        <label for="form_education_education"><?php echo $lang_Recruit_ApplicationForm_Education; ?></label> <input type="text" id="form_education_education" name="form_education_education" />
	        <label for="form_education_major"><?php echo $lang_hrEmpMain_major; ?></label> <input type="text" id="form_education_major" name="form_education_major" /><br/>
	         <label for="form_education_year"><?php echo $lang_Recruit_ApplicationForm_YearCompleted; ?></label> <input type="text" id="form_education_year" name="form_education_year" />
	        <label for="form_education_score"><?php $lang_Recruit_ApplicationForm_AverageScore; ?></label> <input type="text" id="form_education_score" name="form_education_score" /><br/>
		<img onClick="addMore('education_info');" id="saveBtn"	src="<?php echo $addImg;?>">
		<table border="1" cellpadding="4" style="vertical-align: top; border-style: solid;border-color: gray;border-collapse: collapse">
		<tbody id="education_info" >

		</tbody>
		</table>
		</div>
		 <label for="form_upload_cv"><?php echo $lang_Recruit_ApplicationForm_UploadCV; ?></label> <input type="file" id="cv" name="cv" /><br/>
		<?php if(sizeof($applicationFields)>0){ ?>
				<strong style="margin-left: 10px;"><?php echo $lang_Recruit_ApplicationForm_QuestionsForApplicant;?></strong><br/>
		<?php } ?>
		 <table border="0">
		 <?php foreach ($applicationFields as $field){ ?>
		 	<tr>
		 	<td><span class="error"><?php echo ($field->getRequired())?"*":""?></span><?php echo $field->getLable(); ?></td>
		 	<td>
			<?php
				echo $field->drawElement()."</td></tr>";
			 } ?>
		 </table>
        <input type="hidden" id="txtQualifications" name="txtQualifications" tabindex="13" />
		<br/><br/>
        <div align="left">
            <img onClick="save();" id="saveBtn"
				onMouseOut="this.src='<?php echo $saveImg;?>';"
            	onMouseOver="this.src='<?php echo $saveImgPressed;?>';"
            	src="<?php echo $saveImg;?>">
			<img onClick="reset(); id="resetBtn"
				onMouseOut="this.src='<?php echo $clearImg;?>';"
            	onMouseOver="this.src='<?php echo $clearImgPressed;?>';"
            	src="<?php echo $clearImg;?>">
        </div>
	</form>
    </div>
    <script type="text/javascript">
        <!--
        	if (document.getElementById && document.createElement) {
   	 			initOctopus();
   	 			$('txtFirstName').focus();
			}
        -->
    </script>

    <div id="notice"><?php echo preg_replace('/#star/', '<span class="error">*</span>', $lang_Commn_RequiredFieldMark); ?>.</div>
<div id="cal1Container" style="position:absolute;" ></div>
</body>
</html>
