$(document).ready(function() {

    disableWidgets()
    //form validation
    $("#frmGenInfo").validate({
        rules: {
            'organization[name]': {
                required: true
            },
            'organization[phone]': {
                phone: true
            },
            'organization[fax]': {
                phone: true
            },
            'organization[email]' : {
                email: true
            },
            'organization[note]' : {
                maxlength: 250
            }
        },
        messages: {
            'organization[name]': {
                required: nameRequired
            },
            'organization[phone]' : {
                phone: invalidPhoneNumber
            },
            'organization[fax]' : {
                phone: invalidFaxNumber
            },
            'organization[email]' : {
                email: incorrectEmail
            },
            'organization[note]' : {
                maxlength: lang_exceed255Chars
            }
        }
    });

    $.validator.addMethod("phone", function(value, element) {
        return (checkPhone(element));
    });

    $('#btnSaveGenInfo').click(function() {
        //if user clicks on Edit make all fields editable
        if($("#btnSaveGenInfo").attr('value') == edit) {
            enableWidgets()
            $("#btnSaveGenInfo").attr('value', save)
        }
        else {
            $("#frmGenInfo").submit()
        }
    });
    
});

function disableWidgets(){
    $('input[type=text]').attr('disabled', 'disabled')
    $('select').attr('disabled', 'disabled')
    $('textarea').attr('disabled', 'disabled')
}

function enableWidgets(){
    $('input[type=text]').removeAttr('disabled')
    $('select').removeAttr('disabled')
    $('textarea').removeAttr('disabled')
}

