<?php use_stylesheet('../orangehrmLeavePlugin/css/defineLeavePeriodSuccess.css'); ?>
<?php use_javascript('../orangehrmLeavePlugin/js/defineLeavePeriodSuccess.js'); ?>

<div id="location" class="box single">

    <div class="head">
        <h1 id="locationHeading"><?php echo __("Leave Period"); ?></h1>
    </div>

    <div class="inner">

        <?php include_partial('global/flash_messages'); ?>

        <form id="frmLeavePeriod" name="frmLeavePeriod" action="" method="post">

            <fieldset>

                <ol>
                    <?php echo $form->render(); ?>
                    <li>
                        <label>
                            <?php echo __("End Date"); ?>

                        </label>
                        <label id="labelEndDate">
                            <span id="lblEndDate" class="valueLabel"><?php echo $endDate; ?></span>&nbsp;<span id="lblEndDateFollowingYear" class="valueLabel"></span>

                        </label>
                    </li>
                    <?php if ($isLeavePeriodDefined) { ?>
                    <li>
                        <label>
                            <?php echo __("Current Leave Period"); ?>
                        </label>
                        <label>
                            <?php echo set_datepicker_date_format($currentLeavePeriod->getStartDate()) . " " . __("to") . " " . set_datepicker_date_format($currentLeavePeriod->getEndDate());?>
                        </label>
                    </li>
                    <?php } ?>
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>

                <p>
                    <input type="button" class="addbutton" name="btnEdit" id="btnEdit" value="<?php echo ($isLeavePeriodDefined) ? __("Edit") : __("Save"); ?>"/>
                    <?php if ($isLeavePeriodDefined) { ?>
                        <input type="button" class="reset" name="btnReset" id="btnReset" value="<?php echo __("Cancel"); ?>"/>
                    <?php } ?>
                </p>

            </fieldset>

        </form>
    </div>
</div>
<script type="text/javascript">
    var isLeavePeriodDefined = <?php echo ($isLeavePeriodDefined) ? 'true' : 'false' ?>;
    var isEditMode = <?php echo ($isLeavePeriodDefined) ? 'false' : 'true' ?>;
    var initValues = null;
    var start_month_value = "<?php echo ($isLeavePeriodDefined) ? $startMonthValue : 0 ?>";
    var start_date_value = "<?php echo ($isLeavePeriodDefined) ? $startDateValue : 0 ?>";

    var lang_StartMonthIsRequired = "<?php echo __(""); ?>";
    var lang_StartMonthForNonLeapYearIsRequired = "<?php echo __(""); ?>";
    var url_date_of_months = "<?php echo url_for('leave/loadDatesforMonth'); ?>";
    var url_leave_period_end_date = "<?php echo url_for('leave/loadLeavePeriodEndDate'); ?>";
    var url_current_start_date = "<?php echo url_for('leave/getCurrentStartDate'); ?>";
    var lang_save = "<?php echo __("Save"); ?>";
    var lang_error_date_grater_than = "<?php echo __("Selected date is greater than the leave period end date. Maximum allowed start date is selected") ?>";
    var lang_following_year = "<?php echo __('Following Year') ?>";
    var lang_required = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    
</script>

