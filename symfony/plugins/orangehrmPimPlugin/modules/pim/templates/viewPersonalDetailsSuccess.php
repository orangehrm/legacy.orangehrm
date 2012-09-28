<?php use_javascripts_for_form($form) ?>
<?php use_stylesheets_for_form($form) ?>

<div class="box single main" id="employee-details">
    
    <div id="sidebar">

        <div id="profile-pic">
            <h1>Kayla Abbey</h1>
            <img src="<?php echo public_path('../../symfony/web/themes/default/images/profile-pic.png')?>" width="201" height="208" alt="">
        </div>

        <ul id="sidenav">
            <li class="selected"><a href="#">Personal Details</a></li>
            <li><a href="#">Contact Details</a></li>
            <li><a href="#">Emergency Contacts</a></li>
            <li><a href="#">Dependents</a></li>
            <li><a href="#">Immigration</a></li>
            <li><a href="#">Job</a></li>
            <li><a href="#">Salary</a></li>
            <li><a href="#">Report-to</a></li>
            <li><a href="#">Qualifications</a></li>
            <li><a href="#">Membership</a></li>
        </ul>
        
    </div> <!-- sidebar -->
    
    <div class="head">
        <h1><?php echo __('Personal Details'); ?></h1>
    </div> <!-- head -->
    
    <div class="inner">
        
        <?php echo isset($message) ? displayMainMessage($messageType, $message) : ''; ?>
        
        <form id="frmEmpPersonalDetails" method="post" action="<?php echo url_for('pim/viewPersonalDetails'); ?>">
            
            <?php echo $form['_csrf_token']; ?>
            <?php echo $form['txtEmpID']->render(); ?>
            
            <fieldset>
                <ol>
                    <li class="line">
                        <label for="Full_Name"><?php echo __('Full Name'); ?></label>
                        <?php echo $form['txtEmpFirstName']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('First Name'))); ?>
                        <?php echo $form['txtEmpMiddleName']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                        <?php echo $form['txtEmpLastName']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                        <!--<input type="text" name="First_Name" id="First_Name" title="First Name" class="block default">
                        <input type="text" name="Middle_Name" id="Middle_Name" title="Middle Name" class="block default">
                        <input type="text" name="Last_Name" id="Last_Name" title="Last Name" class="block default">-->
                    </li>
                </ol>
                <ol>
                    <li>
                        <label for="Employee_ID"><?php echo __('Employee Id'); ?></label>
                        <?php echo $form['txtEmployeeId']->render(array("maxlength" => 10, "class" => "editable")); ?>
                        <!--<input type="text" name="Employee_ID" id="Employee_ID">-->
                    </li>
                    <li>
                        <label for="Other_ID"><?php echo __('Other Id'); ?></label>
                        <?php echo $form['txtOtherID']->render(array("maxlength" => 30, "class" => "editable")); ?>
                        <!--<input type="text" name="Other_ID" id="Other_ID">-->
                    </li>
                    <li class="long">
                        <label for="License_Number"><?php echo __("Driver's License Number"); ?></label>
                        <?php echo $form['txtLicenNo']->render(array("maxlength" => 30, "class" => "editable")); ?>
                        <!--<input type="text" name="License_Number" id="License_Number">-->
                    </li>
                    <li>
                        <label for="Expiry_Date"><?php echo __('License Expiry Date'); ?></label>
                        <?php echo $form['txtLicExpDate']->render(array("class"=>"calendar editable")); ?>
                        <!--<input type="text" name="Expiry_Date" id="Expiry_Date" class="calendar">-->
                    </li>
                </ol>
                <ol>
                    <li class="radio">
                        <label for="Gender" class="block"><?php echo __("Gender"); ?></label>
                        <?php echo $form['optGender']->render(array("class"=>"editable")); ?>
                        <!--
                        <label for="Male"><input type="radio" id="Male" name="Gender"> Male</label>
                        <label for="Female"><input type="radio" id="Female" name="Gender"> Female</label>
                        -->
                    </li>
                    <li>
                        <label for="Marital_Status"><?php echo __('Marital Status'); ?></label>
                        <?php echo $form['cmbMarital']->render(array("class"=>"editable")); ?>
                        <!--
                        <select id="Marital_Status" name="Marital_Status">
                        <option value="" selected="selected">-- Select --</option> 
                        <option value="Test">Test</option>
                        </select>
                        -->
                    </li>
                    <li class="new">
                        <label for="Nationality"><?php echo __("Nationality"); ?></label>
                        <?php echo $form['cmbNation']->render(array("class"=>"editable")); ?>
                        <!--
                        <select id="Nationality" name="Nationality">
                        <option value="" selected="selected">-- Select --</option> 
                        <option value="Test">Test</option>
                        </select>
                        -->
                    </li>
                    <li class="line">
                        <label for="Date_of_Birth"><?php echo __("Date of Birth"); ?></label>
                        <?php echo $form['DOB']->render(array("class"=>"editable")); ?>
                        <!--<input type="text" name="Date_of_Birth" id="Date_of_Birth" class="calendar">-->
                    </li>
                    <li class="required">
                        <em>*</em> required field
                    </li>
                </ol>
                <p><input type="button" id="btnSave" value="<?php echo __("Edit"); ?>" /></p>
            </fieldset>
        </form>
    </div> <!-- inner -->

</div> <!-- employee-details -->
 
<!--
<link href="<?php echo public_path('../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css')?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.core.js')?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.datepicker.js')?>"></script>
-->
<?php //echo stylesheet_tag('orangehrm.datepicker.css') ?>
<?php //echo javascript_include_tag('orangehrm.datepicker.js')?>

<?php //echo stylesheet_tag('../orangehrmPimPlugin/css/viewPersonalDetailsSuccess'); ?>

<script type="text/javascript">
    //<![CDATA[
    //we write javascript related stuff here, but if the logic gets lengthy should use a seperate js file
    var edit = "<?php echo __("Edit"); ?>";
    var save = "<?php echo __("Save"); ?>";
    var lang_firstNameRequired = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_lastNameRequired = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_selectGender = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';

    var fileModified = 0;
    
    var readOnlyFields = <?php echo json_encode($form->getReadOnlyWidgetNames());?>

    //]]>
</script>

<!-- common table structure to be followed -->
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td width="5">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <!-- this space is reserved for menus - dont use -->
        <td width="200" valign="top">
        <?php include_partial('leftmenu', array('empNumber' => $empNumber, 'form' => $form));?></td>
        <td valign="top">
            <table cellspacing="0" cellpadding="0" border="0" width="90%">
                <tr>
                    <td valign="top" width="750">
                        <!-- this space is for contents -->
                        <div id="messagebar" class="<?php echo isset($messageType) ? "messageBalloon_{$messageType}" : ''; ?>" style="margin-left: 16px;width: 700px;">
                            <span style="font-weight: bold;"><?php echo isset($message) ? $message : ''; ?></span>
                        </div>
                        <div class="outerbox">
                            <div class="mainHeading"><h2><?php echo __('Personal Details'); ?></h2></div>
                            <?php if ($personalInformationPermission->canRead()) {?>
                            <div>
                                
                                <form id="frmEmpPersonalDetails" method="post" action="<?php echo url_for('pim/viewPersonalDetails'); ?>">
                                    <?php echo $form['_csrf_token']; ?>
                                    <table cellspacing="0" cellpadding="0" border="0" class="tableArrange">
                                        <?php echo $form['txtEmpID']->render(); 
                                        ?>
                                        <tr>
                                            <!-- section for full name -->
                                            <td>
                                                <table width="100%">
                                                    <tr>
                                                        <td><?php echo __('Full Name'); ?></td>
                                                        <td valign="top"><?php echo $form['txtEmpFirstName']->render(array("class" => "formInputText", "maxlength" => 30)); ?><br class="clear" /></td>
                                                        <td valign="top"><?php echo $form['txtEmpMiddleName']->render(array("class" => "formInputText", "maxlength" => 30)); ?></td>
                                                        <td valign="top"><?php echo $form['txtEmpLastName']->render(array("class" => "formInputText", "maxlength" => 30)); ?><br class="clear" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td class="helpText"><?php echo __('First Name'); ?><span class="required">*</span></td>
                                                        <td class="helpText"><?php echo __('Middle Name'); ?></td>
                                                        <td class="helpText"><?php echo __('Last Name'); ?><span class="required">*</span></td>
                                                    </tr>
                                                </table>
                                                <div class="hrLine" >&nbsp;</div>
                                            </td>
                                        </tr>
                                        
                                        <tr>                
                                            <td>
                                                <!-- section for rest of the contents -->
                                                <table border="0" width="100%">
                                                    <tr>
                                                        <td><?php echo __('Employee Id'); ?></td>
                                                        <td><?php echo $form['txtEmployeeId']->render(array("class" => "formInputText", "maxlength" => 10)); ?></td>
                                                        <td <?php echo $showSSN ? '' : "class='hideTr'";?>><?php echo __('SSN Number'); ?></td>
                                                        <td <?php echo $showSSN ? '' : "class='hideTr'";?>><?php echo $form['txtNICNo']->render(array("class" => "formInputText", "maxlength" => 30)); ?></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td><?php echo __('Other Id'); ?></td>
                                                        <td><?php echo $form['txtOtherID']->render(array("class" => "formInputText", "maxlength" => 30)); ?></td>
                                                        <td <?php echo $showSIN ? '' : "class='hideTr'";?>><?php echo __('SIN Number'); ?></td>
                                                        <td <?php echo $showSIN ? '' : "class='hideTr'";?>><?php echo $form['txtSINNo']->render(array("class" => "formInputText", "maxlength" => 30)); ?></td>

                                                    </tr>
                                                    <tr>
                                                        <td><?php echo __("Driver's License Number"); ?></td>
                                                        <td><?php echo $form['txtLicenNo']->render(array("class" => "formInputText", "maxlength" => 30)); ?></td>
                                                        <td><?php echo __('License Expiry Date'); ?></td>
                                                        <td><?php echo $form['txtLicExpDate']->render(array('class'=>'formInputText')); ?>
                                                            <br class="clear" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4"><br /> <div class="hrLine" >&nbsp;</div></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo __("Gender"); ?></td>
                                                        <td valign="top"><?php echo $form['optGender']->render(); ?> <br class="clear" /></td>
                                                        <td><?php echo __('Marital Status'); ?></td>
                                                        <td><?php echo $form['cmbMarital']->render(array("class" => "formInputText")); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo __("Nationality"); ?></td>
                                                        <td><?php echo $form['cmbNation']->render(array("class" => "formInputText")); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo __("Date of Birth"); ?></td>
                                                        <td><?php echo $form['DOB']->render(array("class" => "formInputText")); ?>
                                                            <br class="clear" />
                                                        </td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>

                                                    <tr <?php if(!$showDeprecatedFields) {
                                                        echo "class='hideTr'";
                                                        }?> >
                                                        <td colspan="4"><br /> <div class="hrLine" >&nbsp;</div></td>
                                                    </tr>
                                                    <tr <?php if(!$showDeprecatedFields) {
                                                        echo "class='hideTr'";
                                                        }?> >
                                                        <td><?php echo __("Nick Name"); ?></td>
                                                        <td><?php echo $form['txtEmpNickName']->render(array("class" => "formInputText", "maxlength" => 30)); ?></td>
                                                        <td><?php echo __('Smoker'); ?>&nbsp;<?php echo $form['chkSmokeFlag']->render(); ?></td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr <?php if(!$showDeprecatedFields) {
                                                        echo "class='hideTr'";
                                                        }?> >
                                                        <td><?php echo __("Military Service"); ?></td>
                                                        <td><?php echo $form['txtMilitarySer']->render(array("class" => "formInputText", "maxlength" => 30)); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="formbuttons">
                                        <?php  if ($personalInformationPermission->canUpdate()) { ?>
                                                    <input type="button" class="savebutton" id="btnSave" value="<?php echo __("Edit"); ?>" tabindex="2" />
                                        <?php } ?>
                                    </div>
                                </form>
                                
                            </div>
                            <?php }else{
                                ?>
                            <div class="paddingLeftRequired"><?php echo __(CommonMessages::DONT_HAVE_ACCESS); ?></div>
                            <?php
                            }
?>
                        </div>
                        <?php if ($personalInformationPermission->canRead()) {?>
                        <div class="paddingLeftRequired"><span class="required">*</span> <?php echo __(CommonMessages::REQUIRED_FIELD); ?></div>
                        <?php }?>
                        <?php //echo include_component('pim', 'customFields', array('empNumber'=>$empNumber, 'screen' => CustomField::SCREEN_PERSONAL_DETAILS));?>
                        <?php //echo include_component('pim', 'attachments', array('empNumber'=>$empNumber, 'screen' => EmployeeAttachment::SCREEN_PERSONAL_DETAILS));?>
                        
                    </td>
                    <td valign="top" align="center">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php echo javascript_include_tag('../orangehrmPimPlugin/js/viewPersonalDetailsSuccess'); ?>
