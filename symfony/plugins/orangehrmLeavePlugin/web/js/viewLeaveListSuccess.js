$(document).ready(function() {
    
    var validator = $("#frmFilterLeave").validate({

        rules: {
            'leaveList[calFromDate]' : {
                valid_date: function() {
                    return {
                        format:datepickerDateFormat,
                        required:false,
                        displayFormat:displayDateFormat
                    }
                }
            },
            'leaveList[calToDate]' : {
                valid_date: function() {
                    return {
                        format:datepickerDateFormat,
                        required:false,
                        displayFormat:displayDateFormat
                    }
                },
                date_range: function() {
                    return {
                        format:datepickerDateFormat,
                        displayFormat:displayDateFormat,
                        fromDate:$('#calFromDate').val()
                    }
                }
            }
        },
        messages: {
            'leaveList[calFromDate]' : {
                valid_date: lang_invalidDate
            },
            'leaveList[calToDate]' : {
                valid_date: lang_invalidDate ,
                date_range: lang_dateError
            }

        }

    });

    //open when the pencil mark got clicked
    $('.dialogInvoker').click(function() {
        $('#ajaxCommentSaveMsg').html('').removeAttr('class');      
        
        //removing errors message in the comment box
        $("#commentError").html("");
        
        /* Extracting the request id */
        var id = $(this).parent().siblings('input[id^="hdnLeaveRequest_"]').val();
        if (!id) {
            id = $(this).parent().siblings('input[id^="hdnLeave_"]').val();
        }
        var comment = $('#hdnLeaveComment-' + id).val();     
        
        $('#leaveId').val(id);
        $('#existingComments').val(comment);
        $('#leaveComment').val('');
        
        // If leave comment is empty , enable the edit mode
//        if( $('#leaveComment').val().trim() =="") {
//            $("#leaveComment").removeAttr("disabled");
//            $("#commentSave").attr("value", lang_save);
//        } else {
//            $("#leaveComment").attr("disabled","disabled");
//            $("#commentSave").attr("value", lang_edit);
//        }
        
        $('#leaveOrRequest').val('request');

        $('#commentDialog').modal();
    });    

//    $('#leaveComment').change(function() {
//        
//        var text = $(this).val();
//        alert(text);
//        if ($('#leaveComment').text().trim().length > 0) {
//            $("#commentSave").removeAttr("disabled");            
//        } else {
//            $("#commentSave").attr('disabled', 'disabled');
//        }
//    });
    
    //on clicking on save button
    $("#commentSave").click(function() {
//        if($("#commentSave").attr("value") == lang_edit) {
//            $("#leaveComment").removeAttr("disabled");
//            $("#commentSave").attr("value", lang_save);
//            return;
//        }

            $('#commentError').html('').removeClass('validation-error');
            var comment = $('#leaveComment').val().trim();
            if(comment.length > 250) {
                $('#commentError').html(lang_length_exceeded_error).addClass('validation-error');
                return;
            } else if (comment.length == 0) {
                $('#commentError').html(lang_Required).addClass('validation-error');
                return;                                
            }

            /* Setting the comment in the label */
            var commentLabel = trimComment(comment);

            /* Posting the comment */
            var url = commentUpdateUrl;
            var data = 'leaveRequestId=' + $('#leaveId').val() + '&leaveComment=' + encodeURIComponent(comment);

            /* This is specially for detailed view */
            if($('#leaveOrRequest').val() == 'leave') {
                data = 'leaveId=' + $('#leaveId').val() + '&leaveComment=' + encodeURIComponent(comment);
            }

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                success: function(data) {
                    $('#ajaxCommentSaveMsg').removeAttr('class').html('');
                    $('.messageBalloon_success').remove();
                    
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

    $('#btnSearch').click(function() {
        $('#frmFilterLeave input.inputFormatHint').val('');
        $('#frmFilterLeave').submit();
    });


    $('#btnReset').click(function(event) {        
        window.location = resetUrl;
        event.preventDefault();
        return false;
    });
    
    $('select.select_action').bind("change",function() {
        $('div#noActionsSelectedWarning').remove();
    });
});    

function trimComment(comment) {
    if (comment.length > 35) {
        comment = comment.substr(0, 35) + '...';
    }
    return comment;
}