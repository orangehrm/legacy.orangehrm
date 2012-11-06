<?php
use_javascripts_for_form($form);
use_stylesheets_for_form($form);
?>

<?php if ($form->hasErrors()): ?>
    <div class="messagebar">
        <?php include_partial('global/form_errors', array('form' => $form)); ?>
    </div>
<?php endif; ?>

<?php include_partial('overlapping_leave', array('overlapLeave' => $overlapLeave));?>

<div class="box single" id="assign-leave">
    <div class="head">
        <h1><?php echo __('Assign Leave') ?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>
<?php if (count($leaveTypes) > 1) : ?>        
        <form id="frmLeaveApply" name="frmLeaveApply" method="post" action="">
            <fieldset>                
                <ol>
                    <?php echo $form->render(); ?>
                    <li class="required new">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>                      
                </ol>                            
                <p>
                    <input type="button" id="assignBtn" value="<?php echo __("Assign") ?>"/>
                </p>                
            </fieldset>            
        </form>
<?php endif ?>        
    </div> <!-- inner -->
    
</div> <!-- assign leave -->

<?php
    $dateFormat = get_datepicker_date_format($sf_user->getDateFormat());
    $displayDateFormat = str_replace('yy', 'yyyy', $dateFormat);
?>

<script type="text/javascript">
    var datepickerDateFormat = '<?php echo $dateFormat; ?>';
    var displayDateFormat = '<?php echo $displayDateFormat; ?>';
    var leaveBalanceUrl = '<?php echo url_for('leave/getLeaveBalanceAjax'); ?>';
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => $displayDateFormat)) ?>';
    var lang_dateError = '<?php echo __("To date should be after from date") ?>';
    
    
    $(document).ready(function() {
        
        showTimeControls(false);
        
        // Auto complete
        $("#assignleave_txtEmployee_empName").autocomplete(employees_assignleave_txtEmployee, {
            formatItem: function(item) {
                return item.name;
            }
            ,matchContains:true
        }).result(function(event, item) {
            $('#assignleave_txtEmployee_empId').val(item.id);
            setEmployeeWorkshift(item.id);
            updateLeaveBalance();
        }
    );
        
        updateLeaveBalance();
        
        $('#assignleave_txtFromDate').change(function() {
            fromDateBlur($(this).val());
            updateLeaveBalance();
        });
        
        $('#assignleave_txtToDate').change(function() {
            toDateBlur($(this).val());
        });          
        
        //Show From if same date
        if(trim($("#assignleave_txtFromDate").val()) != displayDateFormat && trim($("#assignleave_txtToDate").val()) != displayDateFormat){
            if( trim($("#assignleave_txtFromDate").val()) == trim($("#assignleave_txtToDate").val())) {
                showTimeControls(true);
            }
        }
        
        // Bind On change event of From Time
        $('#assignleave_txtFromTime').change(function() {
            fillTotalTime();
        });
        
        // Bind On change event of To Time
        $('#assignleave_txtToTime').change(function() {
            fillTotalTime();
        });
        
        // Fetch and display available leave when leave type is changed
        $('#assignleave_txtLeaveType').change(function() {
            updateLeaveBalance();
        });
        
        $("#assignleave_txtFromTime").datepicker({
            onClose: function() {
                console.log("Closing");
                $("#assignleave_txtFromTime").valid();
            }
        });
        
        function updateLeaveBalance() {
            var leaveType = $('#assignleave_txtLeaveType').val();
            var empId = $('#assignleave_txtEmployee_empId').val();
            var startDate = $('#assignleave_txtFromDate').val();
            if (leaveType == "" || empId == "") {
                $('#assignleave_leaveBalance').text('--');
            } else {
                $('#assignleave_leaveBalance').append('');
                $.ajax({
                    type: 'GET',
                    url: leaveBalanceUrl,
                    data: '&leaveType=' + leaveType+'&empNumber=' + empId + '&startDate=' + startDate,
                    dataType: 'json',
                    success: function(data) {
                        if ($('#leaveBalance').length == 0) {
                            $('#assignleave_leaveBalance').text(data);
                        }
                        
                    }
                });
            }
        }
        
        //Validation
        $("#frmLeaveApply").validate({
            rules: {
                'assignleave[txtEmployee][empName]':{required: true },
                'assignleave[txtLeaveType]':{required: true },
                'assignleave[txtFromDate]': {
                    required: true,
                    valid_date: function() {
                        return {
                            required: true,
                            format:datepickerDateFormat,
                            displayFormat:displayDateFormat
                        }
                    }
                },
                'assignleave[txtToDate]': {
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
                            fromDate:$("#assignleave_txtFromDate").val()
                        }
                    }
                },
                'assignleave[txtComment]': {maxlength: 250},
                'assignleave[time][from]':{ required: false, validWorkShift : true, validTotalTime: true, validToTime: true}
            },
            messages: {
                'assignleave[txtEmployee][empName]':{
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>'
                },
                'assignleave[txtLeaveType]':{
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>'
                },
                'assignleave[txtFromDate]':{
                    required:lang_invalidDate,
                    valid_date: lang_invalidDate
                },
                'assignleave[txtToDate]':{
                    required:lang_invalidDate,
                    valid_date: lang_invalidDate ,
                    date_range: lang_dateError
                },
                'assignleave[txtComment]':{
                    maxlength:"<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 250)); ?>"
                },
                'assignleave[time][from]':{
                    validTotalTime : "<?php echo __(ValidationMessages::REQUIRED); ?>",
                    validWorkShift : "<?php echo __('Should be less than work shift length'); ?>",
                    validToTime:"<?php echo __('From time should be less than To time'); ?>"
                }
            }
        });
        
        $.validator.addMethod("validTotalTime", function(value, element) {
            var valid = true;
            var fromdate = $('#assignleave_txtFromDate').val();
            var todate = $('#assignleave_txtToDate').val();
            
            if (fromdate == todate) {
                var fromTime = $('#assignleave_time_from');
                var toTime = $('#assignleave_time_to');
                if ((fromTime == '') || (toTime == '')) {
                    valid = false;
                }
            }
            
            return valid;
        });
        
        $.validator.addMethod("validWorkShift", function(value, element) {
            var valid = true;
            var fromdate = $('#assignleave_txtFromDate').val();
            var todate = $('#assignleave_txtToDate').val();
            
            if (fromdate == todate) {
                var totalTime = getTotalTime();
                var workShift = $('#assignleave_txtEmpWorkShift').val();
                if (parseFloat(totalTime) > parseFloat(workShift)) {
                    valid = false;
                }

            }
            
            return valid;            
        });
        
        $.validator.addMethod("validToTime", function(value, element) {
            var valid = true;
            
            var fromdate = $('#assignleave_txtFromDate').val();
            var todate = $('#assignleave_txtToDate').val();
            var fromTime	=	$('#assignleave_txtFromTime').val();
            var toTime		=	$('#assignleave_txtToTime').val();
            
            if (fromdate == todate) {
                var totalTime = getTotalTime();
                if (parseFloat(totalTime) <= 0) {
                    valid = false;
                }

            }
            
            return valid;  
        });
        
        //Click Submit button
        $('#assignBtn').click(function() {
            if($('#assignleave_txtFromDate').val() == displayDateFormat ){
                $('#assignleave_txtFromDate').val("");
            }
            if($('#assignleave_txtToDate').val() == displayDateFormat ){
                $('#assignleave_txtToDate').val("");
            }
            $('#frmLeaveApply').submit();
        });
        
        $("#assignleave_txtEmployee_empName").change(function(){
            autoFill('assignleave_txtEmployee_empName', 'assignleave_txtEmployee_empId', employees_assignleave_txtEmployee);
            updateLeaveBalance();
        });
        
        function autoFill(selector, filler, data) {
            $("#" + filler).val("");
            $.each(data, function(index, item){
                if(item.name.toLowerCase() == $("#" + selector).val().toLowerCase()) {
                    $("#" + filler).val(item.id);
                    return true;
                }
            });
        }
    });
    
    function showTimeControls(show) {
        
        var timeControlIds = ['assignleave_time_from'];
        
        $.each(timeControlIds, function(index, value) {
            
            if (show) {
                $('#' + value).parent('li').show();
            } else {
                $('#' + value).parent('li').hide();
            }
        });
    }
    
    function showTimepaneFromDate(theDate, datepickerDateFormat){
        var Todate = trim($("#assignleave_txtToDate").val());
        if(Todate == datepickerDateFormat) {
            $("#assignleave_txtFromDate").val(theDate);
            $("#assignleave_txtToDate").val(theDate);
        } else{
            showTimeControls((Todate == theDate));
        }
        $("#assignleave_txtFromDate").valid();
        $("#assignleave_txtToDate").valid();
    }
    
    function showTimepaneToDate(theDate){
        var fromDate	=	trim($("#assignleave_txtFromDate").val());
        
        showTimeControls((fromDate == theDate));
        
        $("#assignleave_txtFromDate").valid();
        $("#assignleave_txtToDate").valid();
    }
    
    //Calculate Total time
    function fillTotalTime() {        
        var total = getTotalTime();
        $('#assignleave_time_to').next('input.time_range_duration').val(total);
    }
    
    function getTotalTime() {
        var total = 0;
        var fromTime = ($('#assignleave_time_from').val()).split(":");
        var fromdate = new Date();
        fromdate.setHours(fromTime[0],fromTime[1]);
        
        var toTime = ($('#assignleave_time_to').val()).split(":");
        var todate = new Date();
        todate.setHours(toTime[0],toTime[1]);        
        
        var difference = todate - fromdate;
        var floatDeference	=	parseFloat(difference/3600000) ;
        total = Math.round(floatDeference*Math.pow(10,2))/Math.pow(10,2);
        
        return total;        
    }
    
    function fromDateBlur(date){
        var fromDateValue 	= 	trim(date);
        if(fromDateValue != displayDateFormat && fromDateValue != ""){
            var toDateValue	=	trim($("#assignleave_txtToDate").val());
            if(validateDate(fromDateValue, datepickerDateFormat)){
                if(fromDateValue == toDateValue) {
                    showTimeControls(true);
                }
                
                if(!validateDate(toDateValue, datepickerDateFormat)){
                    $('#assignleave_txtToDate').val(fromDateValue);
                    showTimeControls(true);
                }
            } else {
                showTimeControls(false);
            }
        }
    }
    
    function toDateBlur(date){
        var toDateValue	=	trim(date);
        if(toDateValue != displayDateFormat && toDateValue != ""){
            var fromDateValue = trim($("#assignleave_txtFromDate").val());
            
            if(validateDate(fromDateValue, datepickerDateFormat) && validateDate(toDateValue, datepickerDateFormat)){
                
                showTimeControls((fromDateValue == toDateValue));
            }
        }
    }
    
    function setEmployeeWorkshift(empNumber) {
        
        $.ajax({
            url: "getWorkshiftAjax",
            data: "empNumber="+empNumber,
            dataType: 'json',
            success: function(data){
                $('#assignleave_txtEmpWorkShift').val(data.workshift);
            }
        });
        
    }    
    
</script>