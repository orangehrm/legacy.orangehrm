
<?php 
use_stylesheet('../../../symfony/web/themes/default/css/jquery/jquery.autocomplete.css');
use_javascript('../../../scripts/jquery/jquery.autocomplete.js');
use_javascript('../orangehrmAdminPlugin/js/saveProjectSuccess'); 
?>

<div id="addProject" class="box single">
    
    <div class="head">
        <h1 id="addProjectHeading"><?php echo __("Add Project"); ?></h1>
    </div>
    
    <div class="inner">

        <?php include_partial('global/flash_messages'); ?>
        
        <form name="frmAddProject" id="frmAddProject" method="post" action="<?php echo url_for('admin/saveProject'); ?>" >

            <?php echo $form['_csrf_token']; ?>
            <?php echo $form->renderHiddenFields(); ?>
            
            <fieldset>
                
                <ol>
                    
                    <li>
                        <?php echo $form['customerName']->renderLabel(__('Customer Name') . ' <em>*</em>'); ?>
                        <span>
                            <?php echo $form['customerName']->render(array("maxlength" => 52)); ?>
                            <a class="btn2" data-toggle="modal" href="#customerDialog" ><?php echo __('Add Customer') ?></a>
                        </span>
                    </li>
                    
                    <li>
                        <?php echo $form['projectName']->renderLabel(__('Name') . ' <em>*</em>'); ?>
                        <?php echo $form['projectName']->render(array("maxlength" => 52)); ?>
                    </li>
                    
                    <li>
                        <label class="firstLabel"><?php echo __('Project Admin'); ?></label>
                        <span>
                            <?php for ($i = 1; $i <= $form->numberOfProjectAdmins; $i++) { ?>
                                <span class="" id="<?php echo "projectAdmin_" . $i ?>">
                                    <?php echo $form['projectAdmin_' . $i]->render(array("maxlength" => 100)); ?>
                                    <a class="removeText" id=<?php echo "removeButton" . $i ?>><?php echo __('Remove'); ?></a>
                                </span>
                            <?php } ?> 
                            <a class="addText" id='addButton'><?php echo __('Add Another'); ?></a>
                        </span>
                    </li>
                    
                    <li>
                        <?php echo $form['description']->renderLabel(__('Description')); ?>
                        <?php echo $form['description']->render(array("maxlength" => 256)); ?>
                    </li>
                    
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                    
                </ol>
                
                <p>
                    <input type="button" class="" name="btnSave" id="btnSave" value="<?php echo __("Save"); ?>"/>
                    <input type="button" class="reset" name="btnCancel" id="btnCancel" value="<?php echo __("Cancel"); ?>"/>
                </p>
                
            </fieldset>
        
        </form>
    
    </div>

</div>

<?php if (!empty($projectId)) { ?>

<div id="addActivity" class="box single">
    
    <div class="head">
        <h1 id="addActivityHeading"><?php echo __("Add Project Activity"); ?></h1>
    </div>
    
    <div class="inner">
        
        <form name="frmAddActivity" id="frmAddActivity" method="post" action="<?php echo url_for('admin/addProjectActivity'); ?>" >

            <?php echo $activityForm['_csrf_token']; ?>
            <?php echo $activityForm->renderHiddenFields(); ?>
            
            <fieldset>
                
                <ol>
                    
                    <li>
                        <?php echo $activityForm['activityName']->renderLabel(__('Name') . ' <em>*</em>'); ?>
                        <?php echo $activityForm['activityName']->render(array("maxlength" => 102)); ?>
                    </li>
                    
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                    
                </ol>
                
                <p>
                    <input type="button" class="" name="btnActSave" id="btnActSave" value="<?php echo __("Save"); ?>"/>
                    <input type="button" class="reset" name="btnActCancel" id="btnActCancel" value="<?php echo __("Cancel");?>"/>
                </p>
                
            </fieldset>
        
        </form>
        
    </div>

</div>
    
<?php include_component('core', 'ohrmList', $parmetersForListCompoment); ?>

<?php } ?>

<!-- Add customer window -->
<div class="modal hide" id="customerDialog">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('Add Customer') ?></h3>
    </div>
    <div class="modal-body">
        <form name="frmAddCustomer" id="frmAddCustomer" method="post" action="<?php echo url_for('admin/addCustomer'); ?>" >
<!--        <form name="frmname" id="form1">  -->
            <fieldset>
                <ol>
                    <li>
                        <?php echo $customerForm['customerName']->renderLabel(__('Name') . ' <em>*</em>'); ?>
                        <?php echo $customerForm['customerName']->render(array("maxlength" => 52)); ?>
                    </li>
                    <li>
                        <?php echo $customerForm['description']->renderLabel(__('Description')); ?>
                        <?php echo $customerForm['description']->render(array("maxlength" => 255)); ?>
                    </li>
                    <li class="required">
                        <em>*</em><?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>
            </fieldset>
        </form>
    </div>
    <div class="modal-footer">
        <button type="submit" id="dialogSave" class="btn" data-dismiss="modal"> <?php echo __('Save'); ?> </button>
        <button type="button" id="dialogCancel" class="btn reset" data-dismiss="modal"> <?php echo __('Cancel'); ?> </button>
<!--        
        <button type="submit" class="btn" data-dismiss="modal">Confirm</button>
        <button type="button" class="btn reset" data-dismiss="modal">Cancel</button>-->
    </div>
</div>
<!-- End-of-Add-customer-window -->


<!-- Copy activity -->
<div class="modal hide" id="copyActivity">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('Copy Activity') ?></h3>
    </div>
    <div class="modal-body">
        <form name="frmAddCustomer" id="frmAddCustomer" method="post" action="<?php echo url_for('admin/addCustomer'); ?>" >
<!--        <form name="frmname" id="form1">  -->
            <fieldset>
                <ol>
                    <li>
                        <label for="addProjectActivity_activityName"><? echo __("Project Name"); ?> <em>*</em></label>
                        <input type="text" id="projectName" maxlength="52" class="project" name="projectName">
                    </li>
                    <li>
                        <?php echo $copyActForm['_csrf_token']; ?>
                    </li>
                    <li class="required">
                        <em>*</em><?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>
            </fieldset>
        </form>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn" name="btnCopyDig" id="btnCopyDig" data-dismiss="modal"><?php echo __("Copy"); ?>"</button>
        <button type="button" class="btn reset" name="btnCopyCancel" id="btnCopyCancel" data-dismiss="modal"><?php echo __("Cancel"); ?></button>
<!--        
        <button type="submit" class="btn" data-dismiss="modal">Confirm</button>
        <button type="button" class="btn reset" data-dismiss="modal">Cancel</button>-->
    </div>
</div>
<!-- End-of-copy-activity -->


<!--
<div id="customerDialog" title="<?php echo __('Add Customer') ?>"  style="display:none;">

    <div class="dialogButtons">
        
        <form name="frmAddCustomer" id="frmAddCustomer" method="post" action="<?php echo url_for('admin/addCustomer'); ?>" >

        <div class="newColumn">
                <?php echo $customerForm['customerName']->renderLabel(__('Name') . ' <span class="required">*</span>'); ?>
                <?php echo $customerForm['customerName']->render(array("class" => "formInput", "maxlength" => 52)); ?>
                <div id="errorHolderName"></div>
            </div>
            <br class="clear"/>

            <div class="newColumn">
                <?php echo $customerForm['description']->renderLabel(__('Description')); ?>
                <?php echo $customerForm['description']->render(array("class" => "formInput", "maxlength" => 255)); ?>
                <div id="errorHolderDesc"></div>
            </div>
            <br class="clear"/>
        </form>
        <br class="clear"/>
        <div class="actionbuttons">
            <input type="button" id="dialogSave" class="savebutton" value="<?php echo __('Save'); ?>" />
            <input type="button" id="dialogCancel" class="cancelbutton" value="<?php echo __('Cancel'); ?>" />
            <br class="clear"/>
        </div>
            <div class="DigPaddingLeftRequired"><span class="required">*</span> <?php echo __(CommonMessages::REQUIRED_FIELD); ?></div>
        </div>
</div>-->

<!--
<div id="copyActivity" title="<?php echo __('Copy Activity') ?>"  style="display:none;">
    <br class="clear"/>
    <label for="addProjectActivity_activityName"><? echo __("Project Name"); ?> <span class="required">*</span></label>
    <input type="text" id="projectName" maxlength="52" class="project" name="projectName">
    <div id="errorHolderCopy"></div>
    <br class="clear">
    <br class="clear">
    <form name="frmCopyAct" id="frmCopyAct" method="post" action="<?php echo url_for('admin/copyActivity?projectId=' . $projectId); ?>">
        <?php echo $copyActForm['_csrf_token']; ?>
                <div id="copyActivityList">

                </div>
                <br class="clear">
                <div class="actionbuttons">
                    <input type="button" class="savebutton" name="btnCopyDig" id="btnCopyDig"
                           value="<?php echo __("Copy"); ?>"onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                    <input type="button" class="cancelbutton" name="btnCopyCancel" id="btnCopyCancel"
                           value="<?php echo __("Cancel"); ?>"onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>

                </div>
		<div class="DigPaddingLeftRequired"><span class="required">*</span> <?php echo __(CommonMessages::REQUIRED_FIELD); ?></div>
            </form>
        </div>-->


        <script type="text/javascript">
            var employees = <?php echo str_replace('&#039;', "'", $form->getEmployeeListAsJson()) ?> ;
            var employeeList = eval(employees);
            var customers = <?php echo str_replace('&#039;', "'", $form->getCustomerListAsJson()); ?> ;
            var customerList = eval(customers);
            var customerProjects = <?php echo str_replace('&#039;', "'", $form->getCustomerProjectListAsJson()); ?> ;
            var customerProjectsList = eval(customerProjects);
	    <?php if($projectId > 0) { ?>
	    var activityList = <?php echo str_replace('&#039;', "'", $form->getActivityListAsJson($projectId)); ?>;
	    <?php } ?>
            var numberOfProjectAdmins = <?php echo $form->numberOfProjectAdmins; ?>;
            var lang_typeHint = '<?php echo __("Type for hints") . "..."; ?>';
            var lang_nameRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
            var lang_activityNameRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
            var lang_validCustomer = '<?php echo __(ValidationMessages::INVALID); ?>';
            var lang_projectRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
            var lang_exceed50Chars = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 50)); ?>';
            var lang_exceed255Chars = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 250)); ?>';
            var lang_exceed100Chars = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 100)); ?>';
            var custUrl = '<?php echo url_for("admin/saveCustomerJson"); ?>';
            var projectUrl = '<?php echo url_for("admin/saveProject"); ?>';
            var urlForGetActivity = '<?php echo url_for("admin/getActivityListJason?projectId="); ?>';
            var urlForGetProjectList = '<?php echo url_for("admin/getProjectListJson?customerId="); ?>';
            var deleteActivityUrl = '<?php echo url_for("admin/deleteProjectActivity"); ?>';
            var cancelBtnUrl = '<?php echo url_for("admin/viewProjects"); ?>';
            var lang_enterAValidEmployeeName = '<?php echo __(ValidationMessages::INVALID); ?>';
            var lang_identical_rows = '<?php echo __(ValidationMessages::ALREADY_EXISTS); ?>';
            var lang_noActivities = "<?php echo __("No assigned activities"); ?>";
            var lang_noActivitiesSelected = "<?php echo __("No activities selected"); ?>";
            var projectId = '<?php echo $projectId; ?>';
            var custId = '<?php echo $custId; ?>';
            var lang_edit = '<?php echo __("Edit"); ?>';
            var lang_save = "<?php echo __("Save"); ?>";
            var lang_editProject = '<?php echo __("Edit Project"); ?>';
            var lang_Project = '<?php echo __("Project"); ?>';
	    var lang_uniqueCustomer = '<?php echo __(ValidationMessages::ALREADY_EXISTS); ?>';
	    var lang_uniqueName = '<?php echo __(ValidationMessages::ALREADY_EXISTS); ?>';
	    var lang_editActivity = '<?php echo __("Edit Project Activity"); ?>';
	    var lang_addActivity = '<?php echo __("Add Project Activity"); ?>';
	    var isProjectAdmin = '<?php echo $isProjectAdmin; ?>';
</script>