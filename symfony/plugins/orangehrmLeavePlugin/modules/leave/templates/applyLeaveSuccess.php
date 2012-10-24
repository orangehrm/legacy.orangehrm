
<?php

use_javascripts_for_form($applyLeaveForm);
use_stylesheets_for_form($applyLeaveForm);

use_stylesheet(public_path('themes/default/css/jquery/jquery.autocomplete.css'));
use_javascript('../../../scripts/jquery/jquery.autocomplete.js');

?>

<?php if ($applyLeaveForm->hasErrors()): ?>
    <div class="messagebar">
        <?php include_partial('global/form_errors', array('form' => $applyLeaveForm)); ?>
    </div>
<?php endif; ?>

<?php if (!empty($overlapLeave)) {
?>
<div class="box single">
    <div class="head"><h1><?php echo __('Overlapping Leave Request Found') ?></h1></div>
    <div class="inner">

    <table cellspacing="0" cellpadding="0" class="table">
        <thead>
            <tr>
                <th width="100px"><?php echo __("Date") ?></th>
                <th width="100px"><?php echo __("No of Hours") ?></th>
                <th width="90px"><?php echo __("Leave Type") ?></th>
                <th width="200px"><?php echo __("Status") ?></th>
                <th width="150px"><?php echo __("Comments") ?></th>
            </tr>
        </thead>
        <tbody>

            <?php 
            $oddRow = true;
            foreach ($overlapLeave as $leave) {
                $class = $oddRow ? 'odd' : 'even';
                $oddRow = !$oddRow;
            ?>
                <tr class="<?php echo $class;?>">
                    <td><?php echo set_datepicker_date_format($leave->getDate()) ?></td>
                    <td><?php echo $leave->getLengthHours() ?></td>
                    <td><?php echo $leave->getLeaveRequest()->getLeaveType()->getName() ?></td>
                    <td><?php echo __($leave->getTextLeaveStatus()); ?></td>
                    <td><?php echo $leave->getComments() ?></td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    </div>
</div>        
<?php } ?>
<div class="box single" id="apply-leave">
    <div class="head">
        <h1><?php echo __('Apply Leave') ?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>
        <?php if (count($leaveTypes) > 1) : ?>           
        <form id="frmLeaveApply" name="frmLeaveApply" method="post" action="">

            <fieldset>                
                <ol>
                    <?php echo $applyLeaveForm->render(); ?>
                    <li class="required new">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>                      
                </ol>            
                
                <p>
                    <input type="button" id="applyBtn" value="<?php echo __("Apply") ?>"/>
                </p>                
            </fieldset>
            
        </form>
        <?php endif ?>           
    </div> <!-- inner -->
    
</div> <!-- apply leave -->

    <script type="text/javascript">
    //<![CDATA[        
        var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
        var displayDateFormat = '<?php echo str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())); ?>';
        var leaveBalanceUrl = '<?php echo url_for('leave/getLeaveBalanceAjax');?>';
        var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
        var lang_dateError = '<?php echo __("To date should be after from date") ?>';

        $(document).ready(function() {            

            showTimeControls(false);


        updateLeaveBalance();
        
        $('#applyleave_txtFromDate').change(function() {
            fromDateBlur($(this).val());
            updateLeaveBalance();
        });
        
        $('#applyleave_txtToDate').change(function() {
            toDateBlur($(this).val());
        });        

        //Show From if same date
        if(trim($("#applyleave_txtFromDate").val()) != displayDateFormat && trim($("#applyleave_txtToDate").val()) != displayDateFormat){
            if( trim($("#applyleave_txtFromDate").val()) == trim($("#applyleave_txtToDate").val())) {
                showTimeControls(true);
            }
        }

        // Bind On change event of From Time
        $('#applyleave_txtFromTime').change(function() {
            fillTotalTime();
        });

        // Bind On change event of To Time
        $('#applyleave_txtToTime').change(function() {
            fillTotalTime();
        });

        function updateLeaveBalance() {
            var leaveType = $('#applyleave_txtLeaveType').val();
            var startDate = $('#applyleave_txtFromDate').val();
            if (leaveType == "") {
                $('#applyleave_leaveBalance').text('--');
            } else {
                $('#applyleave_leaveBalance').append('');
                $.ajax({
                    type: 'GET',
                    url: leaveBalanceUrl,
                    data: '&leaveType=' + leaveType + '&startDate=' + startDate,
                    dataType: 'json',
                    success: function(data) {
                        if ($('#leaveBalance').length == 0) {
                           $('#applyleave_leaveBalance').text(data);
                       }

                    }
                });
           }
        }

        // Fetch and display available leave when leave type is changed
        $('#applyleave_txtLeaveType').change(function() {
            updateLeaveBalance();
        });

        //Validation
        $("#frmLeaveApply").validate({
            rules: {
                'applyleave[txtLeaveType]':{required: true },
                'applyleave[txtFromDate]': {
                    required: true,
                    valid_date: function() {
                        return {
                            required: true,
                            format:datepickerDateFormat,
                            displayFormat:displayDateFormat
                        }
                    }
                },
                'applyleave[txtToDate]': {
                    required: true,
                    valid_date: function() {
                        return {
                            required: true,
                            format:datepickerDateFormat,
                            displayFormat:displayDateFormat
                        }
                    },
                    date_range: function() {
                        return {
                            format:datepickerDateFormat,
                            displayFormat:displayDateFormat,
                            fromDate:$("#applyleave_txtFromDate").val()
                        }
                    }
                },
                'applyleave[txtComment]': {maxlength: 250},
                'applyleave[txtLeaveTotalTime]':{ required: false , number: true , min: 0.01, validWorkShift : true,validTotalTime : true},
                'applyleave[txtToTime]': {validToTime: true}
            },
            messages: {
                'applyleave[txtLeaveType]':{
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>'
                },
                'applyleave[txtFromDate]':{
                    required:lang_invalidDate,
                    valid_date: lang_invalidDate
                },
                'applyleave[txtToDate]':{
                    required:lang_invalidDate,
                    valid_date: lang_invalidDate ,
                    date_range: lang_dateError
                },
                'applyleave[txtComment]':{
                    maxlength:"<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 250)); ?>"
                },
                'applyleave[txtLeaveTotalTime]':{
                    number:"<?php echo __('Should be a number'); ?>",
                    min : "<?php echo __("Should be greater than %amount%", array("%amount%" => '0.01')); ?>",
                    max : "<?php echo __("Should be less than %amount%", array("%amount%" => '24')); ?>",
                    validTotalTime : "<?php echo __(ValidationMessages::REQUIRED); ?>",
                    validWorkShift : "<?php echo __('Should be less than work shift length'); ?>"
                },
                'applyleave[txtToTime]':{
                    validToTime:"<?php echo __('From time should be less than To time'); ?>"
                }
            }
        });

        $.validator.addMethod("validTotalTime", function(value, element) {
            var totalTime	=	$('#applyleave_txtLeaveTotalTime').val();
            var fromdate	=	$('#applyleave_txtFromDate').val();
            var todate		=	$('#applyleave_txtToDate').val();

            if((fromdate==todate) && (totalTime==''))
                return false;
            else
                return true;

        });

        $.validator.addMethod("validWorkShift", function(value, element) {
            var totalTime	=	$('#applyleave_txtLeaveTotalTime').val();
            var fromdate	=	$('#applyleave_txtFromDate').val();
            var todate		=	$('#applyleave_txtToDate').val();
            var workShift	=	$('#applyleave_txtEmpWorkShift').val();

            if((fromdate==todate) && (parseFloat(totalTime) > parseFloat(workShift)))
                return false;
            else
                return true;

        });

        $.validator.addMethod("validToTime", function(value, element) {

            var fromdate	=	$('#applyleave_txtFromDate').val();
            var todate		=	$('#applyleave_txtToDate').val();
            var fromTime	=	$('#applyleave_txtFromTime').val();
            var toTime		=	$('#applyleave_txtToTime').val();

            var fromTimeArr = (fromTime).split(":");
            var toTimeArr = (toTime).split(":");
            var fromdateArr	=	(fromdate).split("-");

            var fromTimeobj	=	new Date(fromdateArr[0],fromdateArr[1],fromdateArr[2],fromTimeArr[0],fromTimeArr[1]);
            var toTimeobj	=	new Date(fromdateArr[0],fromdateArr[1],fromdateArr[2],toTimeArr[0],toTimeArr[1]);

            if((fromdate==todate) && (fromTime !='') && (toTime != '') && (fromTimeobj>=toTimeobj))
                return false;
            else
                return true;

        });

        //Click Submit button
        $('#applyBtn').click(function(){
            if($('#applyleave_txtFromDate').val() == displayDateFormat){
                $('#applyleave_txtFromDate').val("");
            }
            if($('#applyleave_txtToDate').val() == displayDateFormat){
                $('#applyleave_txtToDate').val("");
            }
            $('#frmLeaveApply').submit();
        });
    });

    function showTimeControls(show) {

        var timeControlIds = ['applyleave_txtFromTime', 'applyleave_txtToTime', 'applyleave_txtLeaveTotalTime'];
        
        $.each(timeControlIds, function(index, value) {

            if (show) {
                $('#' + value).parent('li').show();
            } else {
                $('#' + value).parent('li').hide();
            }
        });
    }

    function showTimepaneFromDate(theDate, displayDateFormat){
        var Todate = trim($("#applyleave_txtToDate").val());
        if(Todate == displayDateFormat) {
            $("#applyleave_txtFromDate").val(theDate);
            $("#applyleave_txtToDate").val(theDate);
            showTimeControls(true);
        } else {
            showTimeControls((Todate == theDate));
        }
        $("#applyleave_txtFromDate").valid();
        $("#applyleave_txtToDate").valid();
    }

    function showTimepaneToDate(theDate){
        var fromDate	=	trim($("#applyleave_txtFromDate").val());

        showTimeControls((fromDate == theDate));

        $("#applyleave_txtFromDate").valid();
        $("#applyleave_txtToDate").valid();
    }

    //Calculate Total time
    function fillTotalTime(){
        var fromTime = ($('#applyleave_txtFromTime').val()).split(":");
        var fromdate = new Date();
        fromdate.setHours(fromTime[0],fromTime[1]);

        var toTime = ($('#applyleave_txtToTime').val()).split(":");
        var todate = new Date();
        todate.setHours(toTime[0],toTime[1]);


        if (fromdate < todate) {
            var difference = todate - fromdate;
            var floatDeference	=	parseFloat(difference/3600000) ;
            $('#applyleave_txtLeaveTotalTime').val(Math.round(floatDeference*Math.pow(10,2))/Math.pow(10,2));
        }

        $("#applyleave_txtToTime").valid();
    }

    function fromDateBlur(date){
        var fromDateValue 	= 	trim(date);
        if(fromDateValue != displayDateFormat && fromDateValue != "") {
            var toDateValue	=	trim($("#applyleave_txtToDate").val());
            if(validateDate(fromDateValue, datepickerDateFormat)){
                if(fromDateValue == toDateValue) {
                    showTimeControls(true);
                }

                if(!validateDate(toDateValue, datepickerDateFormat)){
                    $('#applyleave_txtToDate').val(fromDateValue);
                        showTimeControls(true);
                    }
                } else {
                    showTimeControls(false);
                    $('#applyleave_txtLeaveTotalTime').show();
                }
        }
    }

    function toDateBlur(date){
        var toDateValue	=	trim(date);
        if(toDateValue != displayDateFormat && toDateValue != ""){
            var fromDateValue = trim($("#applyleave_txtFromDate").val());

            if(validateDate(fromDateValue, datepickerDateFormat) && validateDate(toDateValue, datepickerDateFormat)){

                showTimeControls((fromDateValue == toDateValue));
            }
        }
    }
    //]]>
</script>