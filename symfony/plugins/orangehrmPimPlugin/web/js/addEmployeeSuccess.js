$(document).ready(function() {

    $("#chkLogin").attr("checked", "checked");
    var loginStyle = { 'width' : '100%', 'margin' : '0', 'padding' : '0' }
    $("label[for='loginStart']").css(loginStyle);
    $("#photofile").next('br').remove();

    if(createUserAccount == 0) {
        //hiding login section by default
        $("#lineSeperator").hide();
        $("#loginSection").hide();

        $("#chkLogin").removeAttr("checked");
    }

    //default edit button behavior
    $("#btnSave").click(function() {

        $("#frmAddEmp").submit();

    });

    $("#chkLogin").click(function() {
        $("#lineSeperator").hide();
        $("#loginSection").hide();

        $("#user_name").val("");
        $("#user_password").val("");
        $("#re_password").val("");
        $("#status").val("");

        if($("#chkLogin").attr('checked') == true) {
            $("#lineSeperator").show();
            $("#loginSection").show();
        }
    });

        //form validation
    $("#frmAddEmp").validate({
        rules: {
            'firstName': {required: true },
            'lastName': { required: true },
            'user_name': { validateLoginName: true },
            'user_password': {validatePassword: true},
            're_password': {validateReCheckPassword: true},
            'status': {validateStatusRequired: true },
            'location': {required: true }
        },
        messages: {
            'firstName': { required: lang_firstNameRequired },
            'lastName': { required: lang_lastNameRequired },
            'user_name': { validateLoginName: lang_userNameRequired },
            'user_password': {validatePassword: lang_passwordRequired},
            're_password': {validateReCheckPassword: lang_unMatchingPassword},
            'status': {validateStatusRequired: lang_statusRequired },
            'location': {required: lang_locationRequired }
        }
    });

    $.validator.addMethod("validateLoginName", function(value, element) {
        if($("#chkLogin").attr('checked') == true && !ldapInstalled) {
            if(value.length < 5) {
                return false;
            }
        } else if ($("#chkLogin").attr('checked') == true && ldapInstalled) {
            if(value.length < 1) {
                return false;
            }
		}
        return true;
    });

    $.validator.addMethod("validatePassword", function(value, element) {
        if($("#chkLogin").attr('checked') == true && !ldapInstalled) {
            if(value.length < 4) {
                return false;
            }
        }
        return true;
    });

    $.validator.addMethod("validateReCheckPassword", function(value, element) {
        if($("#chkLogin").attr('checked') == true && !ldapInstalled) {
            if(value != $("#user_password").val()) {
                return false;
            }
        }
        return true;
    });

    $.validator.addMethod("validateStatusRequired", function(value, element) {
        if($("#chkLogin").attr('checked') == true) {
            if(value == "") {
                return false;
            }
        }
        return true;
    });

    $("#btnCancel").click(function(){
       navigateUrl("viewEmployeeList");
    });
});