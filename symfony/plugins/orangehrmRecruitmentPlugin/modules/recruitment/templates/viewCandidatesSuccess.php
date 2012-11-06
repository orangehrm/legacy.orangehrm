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
 *
 */
?>

<div class="box toggableForm" id="srchCandidates">
    <div class="head">
        <h1><?php echo __('Candidates'); ?></h1>
    </div>

    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>

        <form name="frmSrchCandidates" id="frmSrchCandidates" method="post" action="<?php echo url_for('recruitment/viewCandidates'); ?>">
            <?php echo $form['_csrf_token']; ?>
            <fieldset>
                <?php echo $form['_csrf_token']; ?>
                <ol>
                    <li>
                        <?php echo $form['jobTitle']->renderLabel(__('Job Title')); ?>
                        <?php echo $form['jobTitle']->render(array("class" => "drpDown", "maxlength" => 50)); ?>
                    </li>
                    <li>
                        <?php echo $form["selectedCandidate"]->render(); ?>
                    </li>
                    <li>
                        <?php echo $form['jobVacancy']->renderLabel(__('Vacancy')); ?>
                        <?php echo $form['jobVacancy']->render(array("class" => "drpDown", "maxlength" => 50)); ?>
                    </li>
                    <li>
                        <?php echo $form['hiringManager']->renderLabel(__('Hiring Manager')); ?>
                        <?php echo $form['hiringManager']->render(array("class" => "drpDown", "maxlength" => 50)); ?>
                    </li>
                    <li>
                        <?php echo $form['candidateName']->renderLabel(__('Candidate Name')); ?>
                        <?php echo $form['candidateName']->render(array("class" => "formInput", "style" => $textBoxWidth)); ?>
                    </li>
                    <li>
                        <?php echo $form['keywords']->renderLabel(__('Keywords')); ?>
                        <?php echo $form['keywords']->render(array("class" => "formInput", "maxlength" => 50, "style" => $textBoxWidth)); ?>
                    </li>
                    <li>
                        <?php echo $form['status']->renderLabel(__('Status')); ?>
                        <?php echo $form['status']->render(array("class" => "drpDown", "maxlength" => 50)); ?>
                    </li>
                    <li>
                        <?php echo $form['modeOfApplication']->renderLabel(__('Method of Application')); ?>
                        <?php echo $form['modeOfApplication']->render(array("class" => "drpDown", "maxlength" => 50)); ?>
                    </li>
                    <li>
                        <?php echo $form['fromDate']->renderLabel(__('Date of Application')); ?>
                        <?php echo $form['fromDate']->render(array("class" => "formDateInput")); ?>
                    </li>
                    <li>
                        <?php echo __('From'); ?>
<?php echo $form['toDate']->render(array("class" => "formDateInput")); ?>
                    </li>
                    <li>

                    </li>
                    <li>

                    </li>
                </ol>
                
                <p>
                    <input type="button" id="btnSrch" value="<?php echo __("Search") ?>" name="btnSrch" />
                    <input type="button" class="reset" id="btnRst" value="<?php echo __("Reset") ?>" name="btnSrch" />                    
                </p>
            </fieldset>


            <?php include_component('core', 'ohrmPluginPannel', array('location' => 'listing_layout_navigation_bar_1')); ?>

            <div class="actionbar" style="border-top: 1px solid #FAD163; margin-top: 3px">
                <div class="actionbuttons">
                    <input type="button" class="searchbutton" name="btnSrch" id="btnSrch"
                           value="<?php echo __("Search"); ?>"onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                    <input type="button" class="resetbutton" name="btnSrch" id="btnRst"
                           value="<?php echo __("Reset"); ?>"onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                           <?php include_component('core', 'ohrmPluginPannel', array('location' => 'listing_layout_navigation_bar_2')); ?>
                </div>
                <br class="clear"/>
            </div>
            <br class="clear"/>
        </form>
    </div>
</div>
<div id="messagebar" class="<?php echo isset($messageType) ? "messageBalloon_{$messageType}" : ''; ?>" >
    <span style="font-weight: bold;"><?php echo isset($message) ? $message : ''; ?></span>
</div>

<div id="candidatesSrchResults">
    <?php include_component('core', 'ohrmList', $parmetersForListCompoment); ?>
</div>


<!-- confirmation box -->
<div id="deleteConfirmation" title="<?php echo __('OrangeHRM - Confirmation Required'); ?>" style="display: none;">

    <?php echo __(CommonMessages::DELETE_CONFIRMATION); ?>

    <div class="dialogButtons">
        <input type="button" id="dialogDeleteBtn" class="savebutton" value="<?php echo __('Ok'); ?>" />
        <input type="button" id="dialogCancelBtn" class="savebutton" value="<?php echo __('Cancel'); ?>" />
    </div>
</div>
<form name="frmHiddenParam" id="frmHiddenParam" method="post" action="<?php echo url_for('recruitment/viewCandidates'); ?>">
    <input type="hidden" name="pageNo" id="pageNo" value="<?php //echo $form->pageNo;        ?>" />
    <input type="hidden" name="hdnAction" id="hdnAction" value="search" />
</form>

<script type="text/javascript">

    function submitPage(pageNo) {

        document.frmHiddenParam.pageNo.value = pageNo;
        document.frmHiddenParam.hdnAction.value = 'paging';
        document.getElementById('frmHiddenParam').submit();

    }
    //<![CDATA[
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var lang_validDateMsg = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var candidates = <?php echo str_replace('&#039;', "'", $form->getCandidateListAsJson()) ?> ;
    var vacancyListUrl = '<?php echo url_for('recruitment/getVacancyListForJobTitleJson?jobTitle='); ?>';
    var hiringManagerListUrlForJobTitle = '<?php echo url_for('recruitment/getHiringManagerListJson?jobTitle='); ?>';
    var hiringManagerListUrlForVacancyId = '<?php echo url_for('recruitment/getHiringManagerListJson?vacancyId='); ?>';
    var addCandidateUrl = '<?php echo url_for('recruitment/addCandidate'); ?>';
    var lang_all = '<?php echo __("All") ?>';
    var lang_dateError = '<?php echo __("To date should be after from date") ?>';
    var lang_helpText = '<?php echo __("Click on a candidate to perform actions") ?>';
    var candidatesArray = eval(candidates);
    var lang_enterValidName = '<?php echo __(ValidationMessages::INVALID) ?>';
    var lang_typeForHints = '<?php echo __("Type for hints") . "..."; ?>';
    var lang_enterCommaSeparatedWords = '<?php echo __("Enter comma separated words") . "..."; ?>';
    var allowedCandidateListToDelete = <?php echo json_encode($form->allowedCandidateListToDelete); ?>;

    //]]>
</script>

