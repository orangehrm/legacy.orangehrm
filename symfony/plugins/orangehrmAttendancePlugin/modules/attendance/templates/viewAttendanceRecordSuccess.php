<?php echo javascript_include_tag('../orangehrmAttendancePlugin/js/viewAttendanceRecordSuccess'); ?>

<div class="box">
    <div class="head">
            <h1><?php echo __('View Attendance Record'); ?></h1>
        </div>
    <div class="inner">
         <?php include_partial('global/flash_messages'); ?>
     
         <form action="<?php echo url_for("attendance/viewAttendanceRecord"); ?>" id="reportForm" method="post" name="frmAttendanceReport">
            <fieldset>
                <ol>
                    <?php
                    if ($form->hasErrors()) {
                        echo $form['employeeName']->renderError();
                    }
                    ?>
                    <?php echo $form->render(); ?>
                    <?php echo $form->renderHiddenFields(); ?>                
                        
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>
                    <p class="formbuttons">
                    <input type="button" class="savebutton" id="btView" value="<?php echo __('View') ?>" />
                    <input type="hidden" name="pageNo" id="pageNo" value="" />
                    <input type="hidden" name="hdnAction" id="hdnAction" value="search" />
                    </p>
            </fieldset> 
        </form>
    </div>
</div>
<div id="recordsTable">

    <div id="msg" ><?php echo isset($messageData) ? templateMessage($messageData) : ''; ?></div>

    <?php include_component('core', 'ohrmList', $parmetersForListCompoment); ?>

    <?php if ($showEdit) : ?>
        <div id="formbuttons">
            <form action="" id="employeeRecordsForm" method="post">
                <?php if ($allowedActions['Edit']) : ?>
                    <input type="button" class="edit" name="button" id="btnEdit"
                           value="<?php echo __('Edit'); ?>" />
                       <?php endif; ?>
                       <?php if ($allowedActions['PunchIn']) : ?>
                    <input type="button" class="punch" name="button" id="btnPunchIn"
                                                      value="<?php echo __('Add Attendance Records'); ?>" />
                       <?php endif; ?>
                       <?php if ($allowedActions['PunchOut']) : ?>
                    <input type="button" class="punch" name="button" id="btnPunchOut"
                            value="<?php echo __('Add Attendance Records'); ?>" />
                       <?php endif; ?>
            </form>
        </div>
    <?php endif; ?>
</div>
<br class="clear">
<div id="punchInOut">

    <br class="clear">
</div>

<!-- Confirmation box HTML: Begins -->
<div class="modal hide" id="dialogBox">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('OrangeHRM - Confirmation Required'); ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo __(CommonMessages::DELETE_CONFIRMATION); ?></p>
    </div>
    <div class="modal-footer">
        <input type="button" class="btn" data-dismiss="modal" id="dialogOk" value="<?php echo __('Ok'); ?>" />
        <input type="button" class="btn reset" data-dismiss="modal" value="<?php echo __('Cancel'); ?>" />
    </div>
</div>
<!-- Confirmation box HTML: Ends -->


<!--<div id="dialogBox" class="dialogBox" title="<?php echo __('OrangeHRM - Confirmation Required'); ?>">
    <?php echo __(CommonMessages::DELETE_CONFIRMATION); ?>

    <div>
        <br class="clear" />&nbsp;&nbsp;&nbsp;<input type="button" id="dialogOk" class="plainbtn okBtn" value="<?php echo __('Ok'); ?>" />
        <input type="button" id="dialogCancel" class="plainbtn cancelBtn" value="<?php echo __('Cancel'); ?>" /></div>

</div>-->

<script type="text/javascript">
    
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var displayDateFormat = '<?php echo str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())); ?>';
    var errorForInvalidFormat='<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var errorMsge;
    var linkForGetRecords='<?php echo url_for('attendance/getRelatedAttendanceRecords'); ?>'
    var linkForProxyPunchInOut='<?php echo url_for('attendance/proxyPunchInPunchOut'); ?>'
    var trigger='<?php echo $trigger; ?>';
    var employeeAll='<?php echo __('All'); ?>';
    var employeeId='<?php echo $employeeId; ?>';
    var dateSelected='<?php echo $date; ?>';
    var actionRecorder='<?php echo $actionRecorder; ?>';
    var employeeSelect = '<?php echo __('Select an Employee') ?>';
    var invalidEmpName = '<?php echo __('Invalid Employee Name') ?>';
    var noEmployees = '<?php echo __('No Employees Available') ?>';
    var typeForHints = '<?php echo __("Type for hints") . '...'; ?>';
    var date='<?php echo $date; ?>';
    var linkToEdit='<?php echo url_for('attendance/editAttendanceRecord'); ?>'
    var linkToDeleteRecords='<?php echo url_for('attendance/deleteAttendanceRecords'); ?>'
    var lang_noRowsSelected='<?php echo __(TopLevelMessages::SELECT_RECORDS); ?>';

    function submitPage(pageNo) {
        document.frmAttendanceReport.pageNo.value = pageNo;
        document.frmAttendanceReport.hdnAction.value = 'paging';
        document.getElementById('reportForm').submit();
    }
</script>