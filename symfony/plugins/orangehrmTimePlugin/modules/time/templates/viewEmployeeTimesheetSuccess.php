
<?php 
use_stylesheet('../../../symfony/web/themes/default/css/jquery/jquery.autocomplete.css');
use_javascript('../../../scripts/jquery/jquery.autocomplete.js');
use_javascript('../orangehrmTimePlugin/js/viewEmployeeTimesheet'); 
?>

<div class="box single">
    <div class="head">
        <h1><?php echo __("Select Employee");?></h1>
    </div>
	<div class="inner">
        <form action="<?php echo url_for("time/viewEmployeeTimesheet"); ?>" id="employeeSelectForm" name="employeeSelectForm" method="post">
            <?php echo $form->renderHiddenFields(); ?>
            <fieldset>
                <ol>
                    <li>
                        <?php echo $form['employeeName']->renderLabel(__('Employee Name'). ' <em>*</em>'); ?>
                        <?php echo $form['employeeName']->render(); ?>
                        <?php echo $form['employeeName']->renderError(); ?>
                    </li>
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>
                <p>
                    <input type="button" class="" id="btnView" value="<?php echo __('View') ?>" />
                </p>
            </fieldset>
		</form>
	</div>
</div>

<!-- Employee-pending-submited-timesheets -->
<?php if (!($pendingApprovelTimesheets == null)): ?>
<div class="box simple">
    
    <div class="head">
        <h1><?php echo __("Timesheets Pending Action"); ?></h1>
    </div>
    
    <div class="inner">
        <form action="<?php echo url_for("time/viewPendingApprovelTimesheet"); ?>" id="viewTimesheetForm" method="post" >        
            <table  border="0" cellpadding="5" cellspacing="0" width="100%" class="table">
                <thead>
                    <tr>
                        <th id="tablehead" width="40%"><?php echo __('Employee name'); ?></th>
                        <th id="tablehead" width="54%"><?php echo __('Timesheet Period'); ?></th>
                        <th width="6%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sf_data->getRaw('pendingApprovelTimesheets') as $pendingApprovelTimesheet): ?>
                    <tr>
                        <input type="hidden" name="timesheetId" value="<?php echo $pendingApprovelTimesheet['timesheetId']; ?>" />
                        <input type="hidden" name="employeeId" value="<?php echo $pendingApprovelTimesheet['employeeId']; ?>" />
                        <input type="hidden" name="startDate" value="<?php echo $pendingApprovelTimesheet['timesheetStartday']; ?>" />
                        <td>
                        <?php echo $pendingApprovelTimesheet['employeeFirstName'] . " " . $pendingApprovelTimesheet['employeeLastName']; ?>
                        </td>
                        <td>
                            <?php echo set_datepicker_date_format($pendingApprovelTimesheet['timesheetStartday']) . " " . __("to") . " " . set_datepicker_date_format($pendingApprovelTimesheet['timesheetEndDate']) ?>
                        </td>
                        <td align="center" class="<?php echo $pendingApprovelTimesheet['timesheetId'] . "##" . $pendingApprovelTimesheet['employeeId'] . "##" . $pendingApprovelTimesheet['timesheetStartday'] ?>">
                            <a href="" class="editLink" id="viewSubmitted"><?php echo __("View"); ?></a>
                        </td>
                    </tr>                        
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
</div>
<?php endif; ?>

<script type="text/javascript">
    var employees = <?php echo str_replace('&#039;', "'", $form->getEmployeeListAsJson()) ?> ;
    var employeesArray = eval(employees);
    var errorMsge;
    var lang_typeForHints = '<?php echo __("Type for hints") . '...'; ?>';
    
    $(document).ready(function() {
        $("#employee").autocomplete(employees, {
            formatItem: function(item) {
                return item.name;
            }
            ,matchContains:true
        }).result(function(event, item) {
        });
        
        $('#employeeSelectForm').submit(function(){
            $('#validationMsg').removeAttr('class');
            $('#validationMsg').html("");
            var projectFlag = validateInput();
            if(!projectFlag) {
                $('#btnSave').attr('disabled', 'disabled');
                $('#validationMsg').attr('class', "messageBalloon_failure");
                $('#validationMsg').html(errorMsge);
                return false;
            }
        });

        $('#viewSubmitted').click(function() {
            var data = $(this).parent().attr("class").split("##");
            // var ids = ($(this).attr("id")).split("_");
            var url = 'viewPendingApprovelTimesheet?timesheetId='+data[0]+'&employeeId='+data[1]+'&timesheetStartday='+data[2];
            $(location).attr('href',url);
        });    
        
        $('#btnView').click(function() {          
            $('#employeeSelectForm').submit();
        });
    });
    
    function validateInput(){
        var errorStyle = "background-color:#FFDFDF;";
        var empDateCount = employeesArray.length;
        var temp = false;
        var i;
        if(empDateCount==0){
            errorMsge = '<?php echo __("No Employees Available"); ?>';
            return false;
        }
        for (i=0; i < empDateCount; i++) {
            empName = $.trim($('#employee').val()).toLowerCase();
            arrayName = employeesArray[i].name.toLowerCase();
            if (empName == arrayName) {
                $('#time_employeeId').val(employeesArray[i].id);
                temp = true
                break;
            }
        }
        if(temp){
            return true;
        }else if(empName == "" || empName == $.trim("<?php echo __('Type for hints') . '...'; ?>").toLowerCase()){
            errorMsge = '<?php echo __("Select an Employee"); ?>';
            return false;
        }else{
            errorMsge = '<?php echo __("Invalid Employee Name"); ?>';
            return false;
        }
    }
    
</script>

