<div id="processing"></div>

<!--this is ajax message place -->
<div id="msgPlace"></div>
<!-- end of ajax message place -->

<?php include_component('core', 'ohrmList', array('requestComments' => $requestComments)); ?>
<input type="hidden" name="hdnMode" value="<?php echo $mode; ?>" />

<!-- comment dialog -->
<div class="modal hide" id="commentDialog">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3><?php echo __('Leave Comments'); ?></h3>
  </div>
  <div class="modal-body">
    <p>
    <form action="updateComment" method="post" id="frmCommentSave">
        <input type="hidden" id="leaveId" />
        <input type="hidden" id="leaveOrRequest" />        
        <textarea id="existingComments" cols="40" rows="10" class="commentTextArea" disabled="disabled"></textarea>
        <br class="clear" />
        <br class="clear" />
        <textarea name="leaveComment" id="leaveComment" cols="40" rows="4" class="commentTextArea"></textarea>
        <span id="commentError"></span>

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

    var leaveRequestId = <?php echo $leaveRequestId; ?>;
    var leave_status_pending = 'Pending Approval'; // TO DO: Fix, check if compatible with localization
    var ess_mode = '<?php echo ($essMode) ? '1' : '0'; ?>';
    var lang_Required = '<?php echo __(ValidationMessages::REQUIRED);?>';
    var lang_comment_successfully_saved = '<?php echo __(TopLevelMessages::SAVE_SUCCESS); ?>';
    var lang_comment_save_failed = '<?php echo __(TopLevelMessages::SAVE_FAILURE); ?>';    
    var lang_Close = '<?php echo __('Close');?>';
    
    function handleSaveButton() {
        $('#processing').html('');
        $('.messageBalloon_success').remove();
        $('.messageBalloon_warning').remove();
        $(this).attr('disabled', true);
        $('select[name^="select_leave_action_"]').each(function() {
            var id = $(this).attr('id').replace('select_leave_action_', '');
            if ($(this).val() == '') {
                $('#hdnLeaveRequest_' + id).attr('disabled', true);
            } else {
                $('#hdnLeaveRequest_' + id).val($(this).val());
            }
            
            if ($(this).val() == '') {
                $('#hdnLeave_' + id).attr('disabled', true);
            } else {
                $('#hdnLeave_' + id).val($(this).val());
            }
        });
        
        var action = $('#frmList_ohrmListComponent').attr('action');
        action = action + '/id/' + leaveRequestId;
        
        $('#frmList_ohrmListComponent').attr('action', action);
        
        $('#helpText').before('<div class="message success">' + '<?php echo __('Processing'); ?>...</div>');
        
        // check the correct url here
        $('#frmList_ohrmListComponent').submit();
    }

    function handleBackButton() {
        window.location = '<?php echo url_for($backUrl); ?>';
        return false;
    }

    var mode = 'detailed';



    $(document).ready(function(){

        //open when the pencil mark got clicked
        $('.dialogInvoker').click(function() {

            //removing errors message in the comment box
            $('#commentError').html('').removeClass('validation-error');

            /* Extracting the request id */
            var id = $(this).parent().siblings('input[id^="hdnLeaveRequest_"]').val();
            if (!id) {
                var id = $(this).parent().siblings('input[id^="hdnLeave_"]').val();
            }            
            
            var comment = $('#hdnLeaveComment-' + id).val();
            var typeOfView = (mode == 'compact') ? 'request' : 'leave';

            $('#leaveId').val(id);
            $('#existingComments').val(comment);
            $('#leaveComment').val('');

            $('#commentDialog').modal();
        });                

        //closes the dialog
        $("#commentCancel").click(function() {
            $("#commentDialog").modal('hide');
        });

        //on clicking on save button
        $("#commentSave").click(function() {

                $('#commentError').html('').removeClass('validation-error');
                var comment = $('#leaveComment').val().trim();
                if(comment.length > 255) {
                    $('#commentError').html('<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 255)); ?>').addClass('validation-error');
                    return;
                } else if (comment.length == 0) {
                    $('#commentError').html(lang_Required).addClass('validation-error');
                    return;                                
                }

                /* Setting the comment in the label */
                var commentLabel = trimComment(comment);

                /* If there is no-change between original and updated comments then don't show success message */
                if($('#hdnLeaveComment-' + $("#leaveId").val()).val().trim() == comment) {
                    $('#commentDialog').modal('hide');
                    return;
                }

                /* Posting the comment */
                var url = '<?php echo public_path('index.php/leave/updateComment'); ?>';
                var data = 'leaveId=' + $('#leaveId').val() + '&leaveComment=' + encodeURIComponent(comment);

                /* This is specially for detailed view */
                if($('#leaveOrRequest').val() == 'leave') {
                    data = 'leaveId=' + $('#leaveId').val() + '&leaveComment=' + encodeURIComponent(comment);
                }

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: function(data) {
                        $('#msgPlace').removeAttr('class');
                        $('.messageBalloon_success').remove();
                        $('#msgPlace').html('');
                        
                        if(data != 0) {
                            var id = $('#leaveId').val();
                            $('#commentContainer-' + id).html(commentLabel);

                            var currentComment = $('#hdnLeaveComment-' + id).val();
                            var newComment = data + "\n\n" + currentComment;
                            $('#hdnLeaveComment-' + id).val(newComment);
                            $('#noActionsSelectedWarning').remove();

                            //$('#helpText').before(content);

                            //$('#ajaxCommentSaveMsg')
                            $('#helpText').before('<div class="message success fadable">' + lang_comment_successfully_saved + '<a href="#" class="messageCloseButton">' + lang_Close + '</a></div>');
                        } else {
                            $('#helpText').before('<div class="message warning fadable">' + lang_comment_save_failed + '<a href="#" class="messageCloseButton">' + lang_Close + '</a></div>');                        
                        }
                        setTimeout(function(){
                            $("div.fadable").fadeOut("slow", function () {
                                $("div.fadable").remove();
                            });
                        }, 2000);
                    
                    }
                });

                $("#commentDialog").modal('hide');
                return;

        });



        $('select.select_action').bind("change",function() {

            var requestId = $(this).attr('name').substring(20);

            if (mode == 'detailed') {
                $('#leave-'+requestId).val($(this).val());
            } else {
                $('#leaveRequest-'+requestId).val($(this).val());
            }

        });


    });
    
    function trimComment(comment) {
        if (comment.length > 35) {
            comment = comment.substr(0, 35) + '...';
        }
        return comment;
    }

    //]]>
</script>

