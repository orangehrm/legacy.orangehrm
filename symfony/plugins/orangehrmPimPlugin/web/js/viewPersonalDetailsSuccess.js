$(document).ready(function() {

    //form validation
    $("#frmEmpPersonalDetails").validate({
        rules: {
            'personal[txtEmpFirstName]': {required: true },
            'personal[txtEmpLastName]': { required: true },
            'personal[DOB]': { required: false, valid_date: function(){ return {format:datepickerDateFormat, required:false, displayFormat:displayDateFormat} } },
            'personal[txtLicExpDate]': { required: false, valid_date: function(){ return {format:datepickerDateFormat, required:false, displayFormat:displayDateFormat} } }
        },
        messages: {
            'personal[txtEmpFirstName]': { required: lang_firstNameRequired },
            'personal[txtEmpLastName]': { required: lang_lastNameRequired },
            'personal[DOB]': { valid_date: lang_invalidDate },
            'personal[txtLicExpDate]': { valid_date: lang_invalidDate }
        },
        errorElement : 'div',
        errorPlacement: function(error, element) {
            error.insertAfter(element.next(".clear"));
            error.insertAfter(element.next().next(".clear"));
            error.insertAfter(element.next().next().next(".clear"));
            error.insertAfter(element.parent().parent().next(".clear"));
        }
    });

    $(".editable").each(function(){
        $(this).attr("disabled", "disabled");
    });
    
    $(".editable").live("click", function(){
        
        
    });
    
    $("#btnSave").click(function() {
        //if user clicks on Edit make all fields editable
        if($("#btnSave").attr('value') == edit) {
            
            $(".editable").each(function(){
                $(this).removeAttr("disabled");
            });            
            
            // handle read only fields                
            for (var j = 0; j < readOnlyFields.length; j++) {
                var fieldId = '#personal_' + readOnlyFields[j];
                var field = $(fieldId);
                var fieldName = 'personal['+ readOnlyFields[j]+']';
                
                $('input[name="' + fieldName + '"]').attr('disabled', 'disabled');
                field.attr('disabled', 'disabled');
                if (field.hasClass('ohrm_datepicker')) {
                    field.next('.calendarBtn').attr('disabled', 'disabled');
                    
                }
            }

            $("#btnSave").attr('value', save);
            return;
        }

        if($("#btnSave").attr('value') == save) {
            $("#frmEmpPersonalDetails").submit();
        }
    });
});