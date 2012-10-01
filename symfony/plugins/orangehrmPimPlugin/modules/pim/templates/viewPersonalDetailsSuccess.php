<?php use_javascripts_for_form($form) ?>
<?php use_stylesheets_for_form($form) ?>

<div class="box single main" id="employee-details">
    
    <?php include_partial('pimLeftMenu', array('empNumber' => $empNumber, 'form' => $form));?>
    
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
                    </li>
                </ol>
                <ol>
                    <li>
                        <label for="Employee_ID"><?php echo __('Employee Id'); ?></label>
                        <?php echo $form['txtEmployeeId']->render(array("maxlength" => 10, "class" => "editable")); ?>
                    </li>
                    <li>
                        <label for="Other_ID"><?php echo __('Other Id'); ?></label>
                        <?php echo $form['txtOtherID']->render(array("maxlength" => 30, "class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="License_Number"><?php echo __("Driver's License Number"); ?></label>
                        <?php echo $form['txtLicenNo']->render(array("maxlength" => 30, "class" => "editable")); ?>
                    </li>
                    <li>
                        <label for="Expiry_Date"><?php echo __('License Expiry Date'); ?></label>
                        <?php echo $form['txtLicExpDate']->render(array("class"=>"calendar editable")); ?>
                    </li>
                </ol>
                <ol>
                    <li class="radio">
                        <label for="Gender" class="block"><?php echo __("Gender"); ?></label>
                        <?php echo $form['optGender']->render(array("class"=>"editable")); ?>
                    </li>
                    <li>
                        <label for="Marital_Status"><?php echo __('Marital Status'); ?></label>
                        <?php echo $form['cmbMarital']->render(array("class"=>"editable")); ?>
                    </li>
                    <li class="new">
                        <label for="Nationality"><?php echo __("Nationality"); ?></label>
                        <?php echo $form['cmbNation']->render(array("class"=>"editable")); ?>
                    </li>
                    <li class="line">
                        <label for="Date_of_Birth"><?php echo __("Date of Birth"); ?></label>
                        <?php echo $form['DOB']->render(array("class"=>"editable")); ?>
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
 
<?php //echo stylesheet_tag('orangehrm.datepicker.css') ?>
<?php //echo javascript_include_tag('orangehrm.datepicker.js')?>

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

<?php //echo include_component('pim', 'customFields', array('empNumber'=>$empNumber, 'screen' => CustomField::SCREEN_PERSONAL_DETAILS));?>
<?php //echo include_component('pim', 'attachments', array('empNumber'=>$empNumber, 'screen' => EmployeeAttachment::SCREEN_PERSONAL_DETAILS));?>


<?php echo javascript_include_tag('../orangehrmPimPlugin/js/viewPersonalDetailsSuccess'); ?>
