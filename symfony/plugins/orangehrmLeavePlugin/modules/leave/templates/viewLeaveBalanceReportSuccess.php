<?php

use_javascripts_for_form($form);
use_stylesheets_for_form($form);

use_stylesheet(public_path('themes/default/css/jquery/jquery.autocomplete.css'));
use_javascript('../../../scripts/jquery/jquery.autocomplete.js');

?>

<?php if ($form->hasErrors()): ?>
    <div class="messagebar">
        <?php include_partial('global/form_errors', array('form' => $form)); ?>
    </div>
<?php endif; ?>
<div class="box single" id="leave-balance-report">
    <div class="head">
        <h1><?php echo __("Leave Balance Report");?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>
        <form id="frmLeaveBalanceReport" name="frmLeaveBalanceReport" method="post" action="">

            <fieldset>                
                <ol>
                    <?php echo $form->render(); ?>
                </ol>                   
                <p>
                    <input type="submit" name="submit" id="viewBtn" value="<?php echo __('View');?>"/>                    
                </p>
            </fieldset>
        </form>
        
    </div> <!-- inner -->    
</div> 

<div id="report_content">
<?php //echo $report_content;?>    
</div>

<script type="text/javascript">
    var employeeReport = <?php echo LeaveBalanceReportForm::REPORT_TYPE_EMPLOYEE;?>;
    var leaveTypeReport = <?php echo LeaveBalanceReportForm::REPORT_TYPE_LEAVE_TYPE;?>;
    
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var displayDateFormat = '<?php echo str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())); ?>';
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var lang_dateError = '<?php echo __("To date should be after from date") ?>';    
    
    function toggleReportType(reportType) {
        
        var reportType = $("#leave_balance_report_type").val();
        var reportTypeLi = $('#leave_balance_leave_type').parent('li');
        var employeeNameLi = $('#leave_balance_employee_empName').parent('li');
        var dateLi = $('#date_from').parent('li');
        var viewBtn = $('#viewBtn');

        if (reportType == employeeReport) {
            reportTypeLi.hide();
            employeeNameLi.show(); 
            dateLi.show();
            viewBtn.show();
        } else if (reportType == leaveTypeReport) {
            reportTypeLi.show();
            employeeNameLi.hide();           
            dateLi.show();
            viewBtn.show();
        } else {
            reportTypeLi.hide();
            employeeNameLi.hide();                    
            dateLi.hide();
            viewBtn.hide();
        }        
    }
   
    $(document).ready(function() {        
        
        toggleReportType();
        
        $("#leave_balance_report_type").change(function() {          
            toggleReportType();
        });
        
        $('#frmLeaveBalanceReport').validate({
                rules: {
                    'leave_balance[employee][empName]': {
                        required: function(element) {
                            return $("#leave_balance_report_type").val() == leaveTypeReport;
                        },
                        no_default_value: function(element) {
                            return {
                                defaults: $(element).data('typeHint')
                            }
                        }
                    },
                    'leave_balance[leave_type]':{required: function(element) {
                            return $("#leave_balance_report_type").val() == employeeReport;
                        } 
                    },
                    'leave_balance[date][from]': {
                        required: true,
                        valid_date: function() {
                            return {
                                required: true,                                
                                format:datepickerDateFormat,
                                displayFormat:displayDateFormat
                            }
                        }
                    },
                    'leave_balance[date][to]': {
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
                                fromDate:$("#date_from").val()
                            }
                        }
                    }
                    
                },
                messages: {
                    'leave_balance[employee][empName]':{
                        required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                        no_default_value:'<?php echo __(ValidationMessages::REQUIRED); ?>'
                    },
                    'leave_balance[leave_type]':{
                        required:'<?php echo __(ValidationMessages::REQUIRED); ?>'
                    },
                    'leave_balance[date][from]':{
                        required:lang_invalidDate,
                        valid_date: lang_invalidDate
                    },
                    'leave_balance[date][to]':{
                        required:lang_invalidDate,
                        valid_date: lang_invalidDate ,
                        date_range: lang_dateError
                    }                  
            }

        });        

    });

</script>

