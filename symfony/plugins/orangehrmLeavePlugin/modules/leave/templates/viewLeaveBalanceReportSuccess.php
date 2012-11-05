<?php

use_javascripts_for_form($form);
use_stylesheets_for_form($form);

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
        <form id="frmLeaveBalanceReport" name="frmLeaveBalanceReport" method="post" 
              action="<?php echo url_for('leave/viewLeaveBalanceReport') ?>">

            <fieldset>                
                <ol>
                    <?php echo $form->render(); ?>
                </ol>                   
                <p>
                    <input type="button" name="view" id="viewBtn" value="<?php echo __('View');?>"/>                    
                </p>
            </fieldset>
        </form>
        
    </div> <!-- inner -->    
</div> 

<?php if (!empty($resultsSet)) { ?>
    <div id="report-results" class="box simple" style="display: inline-block">
        <div class="inner">
            <?php if ($pager->haveToPaginate()):?>
            <div class="top" style="padding-top:25px;">
                <?php include_partial('report/report_paging', array('pager' => $pager));?>                
            </div>
            <?php endif; ?> 
            <table class="table" width="<?php echo $tableWidthInfo["tableWidth"];?>" cellspacing="0" cellpadding="0" style="table-layout: fixed;">

            <?php $headers = $sf_data->getRaw('tableHeaders');
                  $headerInfo = $sf_data->getRaw('headerInfo');?>

                <thead class="fixedHeader">
                <tr class="heading">
                    <?php 
                          foreach($headers as $mainHeader => $subHeaders):  
                              $subHead = array_shift($subHeaders);
                    ?>                      
                    <th class="header" colspan="<?php echo count($subHeaders);?>" style="text-align: center;"><?php echo __($subHead);?></th>
                    <?php endforeach;?>
                </tr>
                <tr class="subHeading">
                    <?php $i = 0; foreach($headers as $subHeaders): array_shift($subHeaders);?>

                            <?php foreach($subHeaders as $subHeader):?>
                    <th class="header" style="text-align: center;" width="<?php echo $tableWidthInfo["columnWidth"][$i]; $i++;?>"><?php echo __($subHeader);?></th>
                            <?php endforeach;?>                    
                    <?php endforeach;?>
                </tr>
                </thead>
                <?php                
                    $rowCssClass = "even";
                    $results = $sf_data->getRaw('resultsSet');?>                
                <tbody class="scrollContent"> 
                <?php foreach ($results as $row):              
                        $rowCssClass = ($rowCssClass === 'odd') ? 'even' : 'odd';?>                      
                <tr class="<?php echo $rowCssClass;?>">
                <?php foreach ($row as $key => $column):                            
                         $info = $headerInfo[$key];
                         if(is_array($column)):
                            foreach ($column as $colKey => $colVal):
                                $headInf = $info[$colKey];                                                                            
                                if(($headInf["groupDisp"] == "true") && ($headInf["display"] == "true")):?>
                                    <!--<td><table>-->
                                    <td width="<?php echo ($headInf["width"] ); ?>"><ul>                                      
                                        <ul>                                         
                                        <?php foreach($colVal as $data):?>
                                               <!--<tr style="height: 10px;"><td headers="10"><?php // echo __($data);?></td></tr>-->                                               
                                               <li><?php echo __($data);?></li>                                        
                                        <?php endforeach;?>
                                        </ul>                                    
                                     </td>
                                     <!--</table></td>-->
                            <?php endif;                                                                                      
                             endforeach;
                         else:
                            if(($info["groupDisp"] == "true") && ($info["display"] == "true")):?>
                            <td width="<?php echo ($info["width"]);?>"><?php if(($column == "") || is_null($column)):
                                    echo "---";
                                else :
                                    echo __($column);
                                endif;?></td>
                      <?php endif;
                         endif;?>                            
                 <?php endforeach;?>
                 </tr>             
                 <?php endforeach;?>
                </tbody>
            </table>
        </div>    
    </div>
<?php } ?>

<script type="text/javascript">
    var employeeReport = <?php echo LeaveBalanceReportForm::REPORT_TYPE_EMPLOYEE;?>;
    var leaveTypeReport = <?php echo LeaveBalanceReportForm::REPORT_TYPE_LEAVE_TYPE;?>;
    
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var displayDateFormat = '<?php echo str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())); ?>';
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var lang_dateError = '<?php echo __("To date should be after from date") ?>';    
    
    function submitPage(pageNo) {
        var actionUrl = $('#frmLeaveBalanceReport').attr('action') + '?pageNo=' + pageNo;
        $('#frmLeaveBalanceReport').attr('action', actionUrl).submit(); 
    }
    
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
        
        $('#viewBtn').click(function() {
            $('#frmLeaveBalanceReport').submit();
        });
        
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

