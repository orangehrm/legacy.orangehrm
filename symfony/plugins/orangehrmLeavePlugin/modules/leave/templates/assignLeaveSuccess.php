<?php
use_javascripts_for_form($assignLeaveForm);
use_stylesheets_for_form($assignLeaveForm);

use_stylesheet(public_path('themes/default/css/jquery/jquery.autocomplete.css'));
use_javascript('../../../scripts/jquery/jquery.autocomplete.js');

?>

<?php if ($assignLeaveForm->hasErrors()): ?>
    <div class="messagebar">
        <?php include_partial('global/form_errors', array('form' => $assignLeaveForm)); ?>
    </div>
<?php endif; ?>


<?php if (!empty($overlapLeave)) {
?>
<div class="box single">
    <div class="head"><h1><?php echo __('Overlapping Leave Request Found') ?></h1></div>
    <div class="inner">
<table border="0" cellspacing="0" cellpadding="0" class="table">
    <thead>
        <tr>
            <tr>
                <th width="100px"><?php echo __("Date") ?></th>
                <th width="100px"><?php echo __("No of Hours") ?></th>
                <th width="90px"><?php echo __("Leave Type") ?></th>
                <th width="200px"><?php echo __("Status") ?></th>
                <th width="150px"><?php echo __("Comments") ?></th>
            </tr>
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
        <h1><?php echo __('Assign Leave') ?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>
<?php if (count($leaveTypes) > 1) : ?>        
        <form id="frmLeaveApply" name="frmLeaveApply" method="post" action="">

            <fieldset>                
                <ol>
                    <?php echo $assignLeaveForm->render(); ?>
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


<script type="text/javascript">
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var displayDateFormat = '<?php echo str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())); ?>';
    var leaveBalanceUrl = '<?php echo url_for('leave/getLeaveBalanceAjax'); ?>';
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
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
                'assignleave[txtLeaveTotalTime]':{ required: false , number: true , min: 0.01, validWorkShift : true,validTotalTime : true},
                'assignleave[txtToTime]': {validToTime: true}
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
                'assignleave[txtLeaveTotalTime]':{
                    number:"<?php echo __('Should be a number'); ?>",
                    min : "<?php echo __("Should be greater than %amount%", array("%amount%" => '0.01')); ?>",
                    max : "<?php echo __("Should be less than %amount%", array("%amount%" => '24')); ?>"	,
                    validTotalTime : "<?php echo __(ValidationMessages::REQUIRED); ?>",
                    validWorkShift : "<?php echo __('Should be less than work shift length'); ?>"
                },
                'assignleave[txtToTime]':{
                    validToTime:"<?php echo __('From time should be less than To time'); ?>"
                }
            }
        });
        
        $.validator.addMethod("validTotalTime", function(value, element) {
            var totalTime	=	$('#assignleave_txtLeaveTotalTime').val();
            var fromdate	=	$('#assignleave_txtFromDate').val();
            var todate		=	$('#assignleave_txtToDate').val();
            
            if((fromdate==todate) && (totalTime==''))
                return false;
            else
                return true;
            
        });
        
        $.validator.addMethod("validWorkShift", function(value, element) {
            var totalTime	=	$('#assignleave_txtLeaveTotalTime').val();
            var fromdate	=	$('#assignleave_txtFromDate').val();
            var todate		=	$('#assignleave_txtToDate').val();
            var workShift	=	$('#assignleave_txtEmpWorkShift').val();
            
            if((fromdate==todate) && (parseFloat(totalTime) > parseFloat(workShift)))
                return false;
            else
                return true;
            
        });
        
        $.validator.addMethod("validToTime", function(value, element) {
            
            var fromdate	=	$('#assignleave_txtFromDate').val();
            var todate		=	$('#assignleave_txtToDate').val();
            var fromTime	=	$('#assignleave_txtFromTime').val();
            var toTime		=	$('#assignleave_txtToTime').val();
            
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
        
        var timeControlIds = ['assignleave_txtFromTime', 'assignleave_txtToTime', 'assignleave_txtLeaveTotalTime'];
        
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
    function fillTotalTime(){
        var fromTime = ($('#assignleave_txtFromTime').val()).split(":");
        var fromdate = new Date();
        fromdate.setHours(fromTime[0],fromTime[1]);
        
        var toTime = ($('#assignleave_txtToTime').val()).split(":");
        var todate = new Date();
        todate.setHours(toTime[0],toTime[1]);
        
        
        if (fromdate < todate) {
            var difference = todate - fromdate;
            var floatDeference	=	parseFloat(difference/3600000) ;
            $('#assignleave_txtLeaveTotalTime').val(Math.round(floatDeference*Math.pow(10,2))/Math.pow(10,2));
        }
        
        $("#assignleave_txtToTime").valid();
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