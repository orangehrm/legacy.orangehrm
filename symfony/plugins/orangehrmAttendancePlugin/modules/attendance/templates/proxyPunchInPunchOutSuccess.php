<?php echo javascript_include_tag('../orangehrmAttendancePlugin/js/proxyPunchInPunchOutSuccess'); ?>

<?php

$isPunchOutAllowed = false;

if (in_array(PluginWorkflowStateMachine::ATTENDANCE_ACTION_PUNCH_OUT, $sf_data->getRaw('allowedActions'))) {
    
    $isPunchOutAllowed = true;
    
    $dateArray = explode(" ", $punchInTime);
    $punchInDisplayTime = set_datepicker_date_format($dateArray[0]) . " " . $dateArray[1];
    
}

?>

<div class="box">
    
    <div class="head">
        <h1><?php echo ($action['PunchIn']) ? __('Punch In') : __('Punch Out') ; ?></h1>
    </div>
    
    <div class="inner">
        
        <div id="validationMsg">
            <?php echo isset($messageData[0]) ? displayMainMessage($messageData[0], $messageData[1]) : ''; ?>
        </div>
        
        <form  id="punchTimeForm" method="post">
            <fieldset>
                <ol>
                    <?php if ($isPunchOutAllowed) : ?>
                    <li>
                        <label><?php echo __('Punched in Time'); ?></label>
                        <label class="line"><?php echo $punchInDisplayTime; ?></label>
                    </li>
                    <?php if (!empty($punchInNote)) : ?>
                    <li>
                        <label><?php echo __('Punched in Note'); ?></label>
                        <label class="line"><?php echo $punchInNote; ?></label>
                    </li>
                    <?php endif; ?> 
                    <?php endif; ?>                    
                    
                    <?php echo $form->render(); ?>
                    
                </ol>
                <?php if (in_array(PluginWorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_IN, $sf_data->getRaw('allowedActions'))) : ?>
                    <p><input type="button" class="punchInbutton" name="button" id="btnPunch" value="<?php echo __('In'); ?>" /></p>
                <?php endif; ?>
                                
                <?php if (in_array(PluginWorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_OUT, $sf_data->getRaw('allowedActions'))) : ?>
                    <p><input type="button" class="punchOutbutton" name="button" id="btnPunch" value="<?php echo __('Out'); ?>" /></p>
                <?php endif; ?>
  
            </fieldset>
        </form>
        
    </div>
    
</div>

<script type="text/javascript">
    //<![CDATA[
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var employeeId='<?php echo $employeeId; ?>';
    var selectedDate='<?php echo $date; ?>';
    var currentTime='<?php echo $currentTime; ?>';
    var currentDate='<?php echo $currentDate; ?>';
    var linkForProxyPunchAction='<?php echo url_for('attendance/proxyPunchInPunchOut') ?>';
    var linkForOverLappingValidation='<?php echo url_for('attendance/validatePunchOutOverLapping') ?>';
    var linkForPunchInOverlappingValidation='<?php echo url_for('attendance/validatePunchInOverLapping') ?>';
    var errorForInvalidTime='<?php echo __('Punch out Time Should Be Higher Than Punch in Time'); ?>';
    var errorForInvalidFormat="<?php echo __('Should Be a Valid Time in %format% Format', array('%format%' => 'HH:MM')) ?>";
    var errorForInvalidTimeFormat="<?php echo __('Should Be a Valid Time in %format% Format', array('%format%' => 'HH:MM')) ?>";
    var getCurrentTimeLink='<?php echo url_for('attendance/getCurrentTime') ?>';
    var errorForInvalidDateFormat='<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => get_datepicker_date_format($sf_user->getDateFormat()))) ?>';
    var errorForOverLappingTime="<?php echo __('Overlapping Records Found'); ?>";
    var errorForInvalidNote='<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 250)) ?>';
    var actionRecorder='<?php echo $actionRecorder; ?>';
    var punchOut =false;
    punchOut='<?php echo $action['PunchOut'] ?>'
    var punchInTime='<?php echo $punchInTime; ?>';
    var punchInNote='<?php echo json_encode($punchInNote); ?>';
    var punchInUtcTime='<?php echo $punchInUtcTime; ?>';       
    var closeText = '<?php echo __('Close');?>';
</script>