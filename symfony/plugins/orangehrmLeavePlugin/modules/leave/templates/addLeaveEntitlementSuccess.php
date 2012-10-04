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
                </p>                
            </fieldset>
            
        </form>
        
    </div> <!-- inner -->
    
</div> <!-- employee-information -->

<!-- Confirmation box HTML: Ends -->

<script type="text/javascript">
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var displayDateFormat = '<?php echo str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())); ?>';
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var lang_dateError = '<?php echo __("To date should be after from date") ?>';
        
    $(document).ready(function() {        
        
        $('#btnSave').click(function() {
            $('#frmLeaveEntitlementAdd').submit();
        });        

        /* Delete confirmation controls: End */
        
        $('#frmLeaveEntitlementAdd').validate({
                rules: {
                    'entitlements[employee][empName]': {
                        required: true,
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
