<?php
use_javascripts_for_form($form);
use_stylesheets_for_form($form);
use_stylesheet('../orangehrmLeavePlugin/css/assignLeaveSuccess.css');
?>

<?php include_partial('overlapping_leave', array('overlapLeave' => $overlapLeave));?>

<div class="box" id="assign-leave">
    <div class="head">
        <h1><?php echo __('Assign Leave') ?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>
        <?php if ($form->hasErrors()): ?>
                <?php include_partial('global/form_errors', array('form' => $form)); ?>
        <?php endif; ?>        
<?php if (count($leaveTypes) > 0) : ?>        
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

<!-- leave balance details HTML: Begins -->
<div class="modal hide" id="balance_details">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3><?php echo __('OrangeHRM - Leave Balance Details'); ?></h3>
  </div>
  <div class="modal-body">
    <p><?php echo __('As of date:'); ?> <span id="balance_as_of"></span></p>
    <table border="0" cellspacing="0" cellpadding="0" class="table">
        <tbody>
                <tr class="odd">
                    <td><?php echo __('Entitled'); ?></td>
                    <td id="balance_entitled">0</td>
                </tr>
                <tr class="even">
                    <td><?php echo __('Used'); ?></td>
                    <td id="balance_used">0</td>
                </tr>
                <tr class="odd">
                    <td><?php echo __('Scheduled'); ?></td>
                    <td id="balance_scheduled">0</td>
                </tr>
                <tr class="even">
                    <td><?php echo __('Pending Approval'); ?></td>
                    <td id="balance_pending">0</td>
                </tr>                    
        </tbody>
        <tfoot>
            <tr class="total">
                <td><?php echo __('Balance');?></td>
                <td id="balance_total"></td>
            </tr>
        </tfoot>          
    </table>
  </div>
  <div class="modal-footer">
    <input type="button" class="btn" data-dismiss="modal" id="closeButton" value="<?php echo __('Ok'); ?>" />
  </div>
</div>
<?php include_component('core', 'ohrmPluginPannel', array('location' => 'assign-leave-javascript'))?>
<!-- leave balance details HTML: Ends -->

<?php

    $dateFormat = get_datepicker_date_format($sf_user->getDateFormat());
    $displayDateFormat = str_replace('yy', 'yyyy', $dateFormat);
?>

<script type="text/javascript">
//<![CDATA[    
    var haveLeaveTypes = <?php echo count($leaveTypes) > 0 ? 'true' : 'false'; ?>;
    var datepickerDateFormat = '<?php echo $dateFormat; ?>';
    var displayDateFormat = '<?php echo $displayDateFormat; ?>';
    var leaveBalanceUrl = '<?php echo url_for('leave/getLeaveBalanceAjax'); ?>';
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => $displayDateFormat)) ?>';
    var lang_dateError = '<?php echo __("To date should be after from date") ?>';
    var lang_details = '<?php echo __("view details") ?>';
    var lang_Required = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_CommentLengthExceeded = "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 250)); ?>";
    var lang_FromTimeLessThanToTime = "<?php echo __('From time should be less than To time'); ?>";
    var lang_DurationShouldBeLessThanWorkshift = "<?php echo __('Duration should be less than work shift length'); ?>";
//]]>    
</script>    
    