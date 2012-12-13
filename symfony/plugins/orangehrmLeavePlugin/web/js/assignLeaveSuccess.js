$(document).ready(function() {
        
    if (haveLeaveTypes) {
        showTimeControls(false);
        
        // Auto complete
        $("#assignleave_txtEmployee_empName").autocomplete(employees_assignleave_txtEmployee, {
            formatItem: function(item) {
                return item.name;
            }
            ,
            matchContains:true
        }).result(function(event, item) {
            $('#assignleave_txtEmployee_empId').val(item.id);
            setEmployeeWorkshift(item.id);
            updateLeaveBalance();
        }
        );
    
    
//        $("#assignleave_txtEmployee_empName").result(function(event, item) {
//            alert(item.id);
//        });
        
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
        $('#assignleave_time_from').change(function() {
            fillTotalTime();
        });
        
        // Bind On change event of To Time
        $('#assignleave_time_to').change(function() {
            fillTotalTime();
        });
        
        // Fetch and display available leave when leave type is changed
        $('#assignleave_txtLeaveType').change(function() {
            updateLeaveBalance();
        });
        
        $("#assignleave_txtFromTime").datepicker({
            onClose: function() {
                $("#assignleave_txtFromTime").valid();
            }
        });        
        
        //Validation
        $("#frmLeaveApply").validate({
            rules: {
                'assignleave[txtEmployee][empName]':{
                    required: true
                },
                'assignleave[txtLeaveType]':{
                    required: true
                },
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
                'assignleave[txtComment]': {
                    maxlength: 250
                },
                'assignleave[time][from]':{
                    required: false, 
                    validWorkShift : true, 
                    validTotalTime: true, 
                    validToTime: true
                },
                'assignleave[time][to]':{
                    required: false, 
                    validTotalTime: true
                }
            },
            messages: {
                'assignleave[txtEmployee][empName]':{
                    required:lang_Required
                },
                'assignleave[txtLeaveType]':{
                    required:lang_Required
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
                    maxlength: lang_CommentLengthExceeded
                },
                'assignleave[time][from]':{
                    validTotalTime : lang_Required,
                    validWorkShift : lang_DurationShouldBeLessThanWorkshift,
                    validToTime: lang_FromTimeLessThanToTime
                },
                'assignleave[time][to]':{
                    validTotalTime : lang_Required
                }
            }
        });
        
        $.validator.addMethod("validTotalTime", function(value, element) {
            var valid = true;
            var fromdate = $('#assignleave_txtFromDate').val();
            var todate = $('#assignleave_txtToDate').val();
            
            if (fromdate == todate) {
                             
                if (value == '') {
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
            
            if (fromdate == todate) {
                var totalTime = getTotalTime();
                if (parseFloat(totalTime) <= 0) {
                    valid = false;
                }

            }
            
            return valid;  
        });
        
        //Click Submit button
       $('#assignBtn').click(function(event) {
        	event.preventDefault();
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
    }
});
    
function updateLeaveBalance() {
    var leaveType = $('#assignleave_txtLeaveType').val();
    var empId = $('#assignleave_txtEmployee_empId').val();
    var startDate = $('#assignleave_txtFromDate').val();
    var endDate =  $('#assignleave_txtToDate').val();
    $('#assignleave_leaveBalance').text('--');
    $('#leaveBalance_details_link').remove();            

    if (leaveType == "" || empId == "") {
    //$('#assignleave_leaveBalance').text('--');
    //$('#leaveBalance_details_link').remove();
    } else {
        $('#assignleave_leaveBalance').append('');
        $.ajax({
            type: 'GET',
            url: leaveBalanceUrl,
            data: '&leaveType=' + leaveType+'&empNumber=' + empId + '&startDate=' + startDate + '&endDate=' + endDate,
            dataType: 'json',
            success: function(data) {
                var balance = data.balance;
                var asAtDate = data.asAtDate;
                var balanceDays = balance.entitled - balance.used;
                $('#assignleave_leaveBalance').text(balanceDays)
                .append('<a href="#balance_details" data-toggle="modal" id="leaveBalance_details_link">' + 
                    lang_details + '</a>');

                $('#balance_as_of').text(asAtDate);
                $('#balance_entitled').text(balance.entitled);
                $('#balance_used').text(balance.used);
                $('#balance_scheduled').text(balance.scheduled);
                $('#balance_pending').text(balance.pending);
                $('#balance_total').text(balanceDays);                        
            }
        });
    }
}
        
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
    if (isNaN(total)) {
        total = '';
    }

    $('input.time_range_duration').val(total);
    $('#assignleave_time_from').valid();
    $('#assignleave_time_to').valid();
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