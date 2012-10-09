<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
?>
<style type="text/css">
    ol#filter {
        border-bottom: 0px;
    }
    
    ol#filter li {
        float: left;
        width: auto;
        margin-right: 20px;
    }    
    ol#filter li:first-child {
        float: left;
        width: 100%;
    }
    ol#filter li:first-child label {
        width: 200px;
    }    
    ol#filter span#ajax_count {
        margin-left: 5px;
    }
    
    ol#employee_list {
        margin-top: 20px;
        height: 200px;
        overflow-y: scroll;
        margin-left: 20px;
        margin-right: 20px;
    }
    ol#employee_list li {
        padding-top: 3px;
        padding-bottom: 3px;
        padding-left: 2px;
        padding-right: 2px;
        
    }    
    ol#employee_list li.odd {
        background-color: #EAEAEA;
    }
    
    ol#employee_list li.even {
        
    }
    
</style>
    
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

<div class="box single" id="add-leave-entitlement">
    <div class="head">
        <h1><?php echo $addMode ? __("Add Leave Entitlement") : __('Edit Leave Entitlement');?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>
        <form id="frmLeaveEntitlementAdd" name="frmLeaveEntitlementAdd" method="post" action="">

            <fieldset>                
                <ol>
                    <?php echo $form->render(); ?>
                </ol>            
                
                <p>
                    <input type="button" id="btnSave" value="<?php echo __("Save") ?>"/>
                    <input type="button" id="btnCancel" class="cancel" value="<?php echo __("Cancel") ?>"/>
                </p>                
            </fieldset>
            
        </form>
        
    </div> <!-- inner -->
    
</div> <!-- employee-information -->

<!-- Confirmation box HTML: Begins -->
<div class="modal hide" id="noselection">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3><?php echo __('OrangeHRM - No matching employees'); ?></h3>
  </div>
  <div class="modal-body">
    <p><?php echo __('No employees match the selected filters'); ?></p>
  </div>
  <div class="modal-footer">
    <input type="button" class="btn" data-dismiss="modal" id="dialogDeleteBtn" value="<?php echo __('Ok'); ?>" />
  </div>
</div>
<!-- Confirmation box HTML: Ends -->
<div class="modal hide" id="preview">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3><?php echo __('OrangeHRM - Matching employees'); ?></h3>
  </div>
  <div class="modal-body">
      <span><?php echo __('The selected leave entitlement will be applied to the following employees.');?></span>
      <ol id="employee_list">          
      </ol>
  </div>
  <div class="modal-footer">
    <input type="button" class="btn" data-dismiss="modal" id="dialogConfirmBtn" value="<?php echo __('Confirm'); ?>" />
    <input type="button" class="cancel" data-dismiss="modal" id="dialogCancelBtn" value="<?php echo __('Cancel'); ?>" />
  </div>
</div>

<script type="text/javascript">
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var displayDateFormat = '<?php echo str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())); ?>';
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var lang_dateError = '<?php echo __("To date should be after from date") ?>';
    var listUrl = '<?php echo url_for('leave/viewLeaveEntitlements?savedsearch=1');?>';
    var getCountUrl = '<?php echo url_for('leave/getFilteredEmployeeCountAjax');?>';
    var getEmployeeUrl = '<?php echo url_for('leave/getFilteredEmployeesAjax');?>';
    var lang_matchesOne = '<?php echo __('Matches one employee');?>';
    var lang_matchesMany = '<?php echo __('Matches %count% employees');?>';
    var lang_matchesNone = '<?php echo __('No matching employees');?>';
        
    var filterMatchingEmployees = 0;
    
    function toggleFilters(show) {
        if (show) {
           $('ol#filter li:not(:first)').show();                
        } else {
            $('ol#filter li:not(:first)').hide();
        }        
    }
    
    function updateFilterMatches() {
        
        var params = '';
        
        $('ol#filter li:not(:first)').find('input,select').each(function(index, element) {
            var name = $(this).attr('name');
            name = name.replace('entitlements[filters][', '');
            name = name.replace(']', '');
            var value = $(this).val();

            params = params + '&' + name + '=' + value;
        });
        
        $.ajax({
            type: 'GET',
            url: getCountUrl,
            data: params,
            dataType: 'json',
            success: function(data) {
                filterMatchingEmployees = data;
                $('span#ajax_count').remove();
                var text = lang_matchesMany.replace('%count%', data);
                if (data == 1) {
                    text = lang_matchesOne;
                } else if (data == 0) {
                    text = lang_matchesNone;
                }

                $('ol#filter li:last').append('<span id="ajax_count">' + text + '</span>');
            }
        });
    }

    function updateEmployeeList() {
        
        var params = '';
        
        $('ol#filter li:not(:first)').find('input,select').each(function(index, element) {
            var name = $(this).attr('name');
            name = name.replace('entitlements[filters][', '');
            name = name.replace(']', '');
            var value = $(this).val();

            params = params + '&' + name + '=' + value;
        });
        
        $.ajax({
            type: 'GET',
            url: getEmployeeUrl,
            data: params,
            dataType: 'json',
            success: function(data) {
                $('ol#employee_list').html('');
                
                
                var count = data.length;
                
                for (var i = 0; i < count; i++) {
                    var css = "odd";
                    if (i % 2) {
                        css = "even";
                    }
                    $('ol#employee_list').append('<li class="' + css + '">' + data[i] + '</li>');
                }
            }
        });
    }

    $(document).ready(function() {               
        
        if ($('#entitlements_filters_bulk_assign').is(':checked')) {
            toggleFilters(true);    
            $('#entitlements_employee_empName').parent('li').hide();
        } else {
            toggleFilters(false);
        }
        
                
        $('#btnSave').click(function() {
            if ($('#entitlements_filters_bulk_assign').is(':checked')) {
                
                if (filterMatchingEmployees == 0) {
                    $('#noselection').modal();
                } else {
                    var valid = $('#frmLeaveEntitlementAdd').valid();
                    if (valid) {
                        updateEmployeeList();
                        $('#preview').modal();
                    }
                }
            } else {
                $('#frmLeaveEntitlementAdd').submit();
            }
        });        
        
        $('#dialogConfirmBtn').click(function() {
            $('#frmLeaveEntitlementAdd').submit();
        });

        $('#btnCancel').click(function() {
            window.location.href = listUrl;
        });        
 
        $('#entitlements_filters_bulk_assign').click(function(){     
            
            if ($('span#ajax_count').length == 0) {
                updateFilterMatches();
            }
            
            var checked = $(this).is(':checked');
            toggleFilters(checked);
            if (checked) {
                $('#entitlements_employee_empName').parent('li').hide();
            } else {
                $('#entitlements_employee_empName').parent('li').show();
            }
        });
        
        $('ol#filter li:not(:first)').find('input,select').change(function(){
           updateFilterMatches(); 
        });
 
        $('#frmLeaveEntitlementAdd').validate({
                rules: {
                    'entitlements[employee][empName]': {
                        required: function(element) {
                            return !$('#entitlements_filters_bulk_assign').is(':checked');
                        },
                        no_default_value: function(element) {

                            return {
                                defaults: $(element).data('typeHint')
                            }
                        }
                    },
                    'entitlements[leave_type]':{required: true },
                    'entitlements[date_from]': {
                        required: true,
                        valid_date: function() {
                            return {
                                required: true,                                
                                format:datepickerDateFormat,
                                displayFormat:displayDateFormat
                            }
                        }
                    },
                    'entitlements[date_to]': {
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
                    },
                    'entitlements[entitlement]': {
                        required: true,
                        number: true
                    }
                    
                },
                messages: {
                    'entitlements[employee][empName]':{
                        required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                        no_default_value:'<?php echo __(ValidationMessages::REQUIRED); ?>'
                    },
                    'entitlements[leave_type]':{
                        required:'<?php echo __(ValidationMessages::REQUIRED); ?>'
                    },
                    'entitlements[date_from]':{
                        required:lang_invalidDate,
                        valid_date: lang_invalidDate
                    },
                    'entitlements[date_to]':{
                        required:lang_invalidDate,
                        valid_date: lang_invalidDate ,
                        date_range: lang_dateError
                    },
                    'entitlements[entitlement]': {
                        required: '<?php echo __(ValidationMessages::REQUIRED); ?>',
                        number: '<?php echo __("Should be a number"); ?>'
                    }                    
            }

        });
        
    });

</script>
