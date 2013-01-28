<?php
/*
 *
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

use_stylesheets_for_form($form);
use_javascripts_for_form($form);
?>
<?php if ($form->hasErrors()): ?>
    <div class="messagebar">
        <?php include_partial('global/form_errors', array('form' => $form)); ?>
    </div>
<?php endif; ?>

<div class="box toggableForm" id="leave-list-search">
    <div class="head">
        <h1><?php echo __($form->getTitle());?></h1>
    </div>
    <div class="inner">
        <form id="frmFilterLeave" name="frmFilterLeave" method="post" action="<?php echo url_for($baseUrl); ?>">

            <fieldset>                
                <ol>
                    <?php echo $form->render(); ?>
                </ol>            
                
                <p>
                    <?php
                    $searchActionButtons = $form->getSearchActionButtons();
                    foreach ($searchActionButtons as $id => $button) {
                        echo $button->render($id), "\n";
                    }
                    ?>                    
                    <?php include_component('core', 'ohrmPluginPannel', array('location' => 'listing_layout_navigation_bar_1')); ?>
                    <input type="hidden" name="pageNo" id="pageNo" value="<?php echo $form->pageNo; ?>" />
                    <input type="hidden" name="hdnAction" id="hdnAction" value="search" />
                    
                </p>                
            </fieldset>
            
        </form>
        
    </div> <!-- inner -->
    <a href="#" class="toggle tiptip" title="<?php echo __(CommonMessages::TOGGABLE_DEFAULT_MESSAGE); ?>">&gt;</a>
</div> <!-- leave-list-search -->

<!--this is ajax message place -->
<div id="ajaxCommentSaveMsg"></div>
<!-- end of ajax message place -->

<?php include_component('core', 'ohrmList'); ?>

<!-- comment dialog -->
<div class="modal hide midsize" id="commentDialog">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3><?php echo __('Leave Request Comments'); ?></h3>
  </div>
  <div class="modal-body">
    <p>
    <form action="updateComment" method="post" id="frmCommentSave">
        <input type="hidden" id="leaveId" />
        <input type="hidden" id="leaveOrRequest" />        
        
        <div id="existingComments">  
            <span><?php echo __('Loading') . '...';?></span>
        </div>
        
        <br class="clear" />
        <br class="clear" />
        <textarea name="leaveComment" id="leaveComment" cols="40" rows="4" class="commentTextArea"></textarea>
        <br class="clear" />
        <span id="commentError" style="padding-left: 2px;" class="validation-error"></span>

    </form>        
    </p>
  </div>
  <div class="modal-footer">
    <input type="button" class="btn" id="commentSave" value="<?php echo __('Save'); ?>" />
    <input type="button" class="btn reset" data-dismiss="modal" id="commentCancel" value="<?php echo __('Cancel'); ?>" />
  </div>
</div>
<!-- end of comment dialog-->

<script type="text/javascript">
    //<![CDATA[
    var lang_typeHint = "<?php echo __("Type for hints"); ?>" + "...";
    var resetUrl = '<?php echo url_for($baseUrl . '?reset=1'); ?>';
    var commentUpdateUrl = '<?php echo public_path('index.php/leave/updateComment'); ?>';
    var getCommentsUrl = '<?php echo url_for('leave/getLeaveCommentsAjax'); ?>';
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var lang_dateError = '<?php echo __("To date should be after from date") ?>';
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var lang_comment_successfully_saved = '<?php echo __(TopLevelMessages::SAVE_SUCCESS); ?>';
    var lang_comment_save_failed = '<?php echo __(TopLevelMessages::SAVE_FAILURE); ?>';
    var lang_edit = '<?php echo __('Edit'); ?>';
    var lang_save = '<?php echo __('Save'); ?>';
    var lang_length_exceeded_error = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 255)); ?>';    
    var lang_selectAction = '<?php echo __("Select Action");?>';
    var lang_Close = '<?php echo __('Close');?>';
    var leave_status_pending = '<?php echo PluginLeave::LEAVE_STATUS_LEAVE_PENDING_APPROVAL;?>';
    var ess_mode = '<?php echo ($essMode) ? '1' : '0'; ?>';
    var lang_Required = '<?php echo __(ValidationMessages::REQUIRED);?>';
    var lang_Date = '<?php echo __('Date');?>';
    var lang_Time = '<?php echo __('Time');?>';
    var lang_Author = '<?php echo __('Author');?>';
    var lang_Comment = '<?php echo __('Comment');?>';
    var lang_Loading = '<?php echo __('Loading');?>...';
    
    function submitPage(pageNo) {
        //    location.href = '<?php //echo url_for($baseUrl . '?pageNo='); ?>' + pageNo;
        document.frmFilterLeave.pageNo.value = pageNo;
        document.frmFilterLeave.hdnAction.value = 'paging';
        if ($('#leaveList_txtEmployee_empName').val() == lang_typeHint) {
            $('#leaveList_txtEmployee_empName').val('');
        }
        document.getElementById('frmFilterLeave').submit();        
    }

    function handleSaveButton() {
        $(this).attr('disabled', true);
        
        $('#noActionsSelectedWarning').remove();
        $('#leaveListActionMsg').remove();
        $('#ajaxCommentSaveMsg').removeAttr('class').html('');
              
        var selectedActions = 0;
        
        $('select[name^="select_leave_action_"]').each(function() {
            var id = $(this).attr('id').replace('select_leave_action_', '');
            if ($(this).val() == '') {
                $('#hdnLeaveRequest_' + id).attr('disabled', true);
            } else {
                selectedActions++;
                $('#hdnLeaveRequest_' + id).attr('disabled', false);                
                $('#hdnLeaveRequest_' + id).val('WF' + $(this).val());
            }

            if ($(this).val() == '') {
                $('#hdnLeave_' + id).attr('disabled', true);
            } else {
                $('#hdnLeave_' + id).attr('disabled', false); 
                $('#hdnLeave_' + id).val('WF' + $(this).val());
            }
        });  
    
        if (selectedActions > 0) {
            $('#frmList_ohrmListComponent').submit();
        } else {
            $('div#ajaxCommentSaveMsg').before('<div id="noActionsSelectedWarning" class="messageBalloon_warning"></div>');
            $('#noActionsSelectedWarning').text(lang_selectAction);
            $(this).attr('disabled', false);      
            return false;
        }
    }

    function setPage() {}


    
    //]]>
</script>