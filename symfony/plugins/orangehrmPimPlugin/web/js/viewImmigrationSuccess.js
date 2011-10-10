$(document).ready(function() {

    function loadDefaultDateMasks() {

        var passportIssueDate = $("#immigration_passport_issue_date");

        if(trim(passportIssueDate.val()) == ''){
            passportIssueDate.val(dateDisplayFormat);
        }

        var passportExpireDate = $("#immigration_passport_expire_date");

        if(trim(passportExpireDate.val()) == ''){
            passportExpireDate.val(dateDisplayFormat);
        }

        var i9ReviewDate = $("#immigration_i9_review_date");

        if(trim(i9ReviewDate.val()) == ''){
            i9ReviewDate.val(dateDisplayFormat);
        }
    
    }

    //Load default Mask if empty
    loadDefaultDateMasks();

    $("#btnSave").click(function() {
        $("#frmEmpImmigration").submit();
    })

    //form validation
    $("#frmEmpImmigration").validate({
        rules: {
            'immigration[number]': {required: true},
            'immigration[passport_issue_date]': {valid_date: function(){return {format:jsDateFormat, displayFormat:dateDisplayFormat, required:false}},validFromDate:true},
            'immigration[passport_expire_date]' : {valid_date: function(){return {format:jsDateFormat, displayFormat:dateDisplayFormat, required:false}}},
            'immigration[i9_review_date]' : {valid_date: function(){return {format:jsDateFormat, displayFormat:dateDisplayFormat, required:false}}},
            'immigration[comments]': {maxlength: 250}
        },
        messages: {
            'immigration[number]': {required: lang_numberRequired},
            'immigration[passport_issue_date]': {valid_date: lang_invalidDate, validFromDate: lang_issuedGreaterExpiry},
            'immigration[passport_expire_date]' : {valid_date: lang_invalidDate},
            'immigration[i9_review_date]' : {valid_date: lang_invalidDate},
            'immigration[comments]': {maxlength: lang_commentLength}
        },

        errorElement : 'label',
        errorPlacement: function(error, element) {

            error.insertBefore(element.next(".clear"));

            //these are specially for date boxes
            error.insertBefore(element.next().next(".clear"));
        }
    });

    daymarker.bindElement("#immigration_passport_issue_date",
        {onSelect: function(date){
            $("#immigration_passport_issue_date").valid();
            },
        dateFormat:jsDateFormat
        });

    $('#passportIssueDateBtn').click(function() {
        daymarker.show("#immigration_passport_issue_date");
    });

    daymarker.bindElement("#immigration_passport_expire_date",
        {onSelect: function(date){
            $("#immigration_passport_expire_date").valid();
            },
        dateFormat:jsDateFormat
        });

    $('#passportExpireDateBtn').click(function() {
        daymarker.show("#immigration_passport_expire_date");
    });

    daymarker.bindElement("#immigration_i9_review_date",
        {onSelect: function(date){
            $("#immigration_i9_review_date").valid();
            },
        dateFormat:jsDateFormat
        });

    $('#i9ReviewDateBtn').click(function() {
        daymarker.show("#immigration_i9_review_date");
    });
    
     /* Valid From Date */
    $.validator.addMethod("validFromDate", function(value, element) {

        var fromdate	=	$('#immigration_passport_issue_date').val();
        fromdate = (fromdate).split("-");

        var fromdateObj = new Date(parseInt(fromdate[0],10), parseInt(fromdate[1],10) - 1, parseInt(fromdate[2],10));
        var todate		=	$('#immigration_passport_expire_date').val();
        todate = (todate).split("-");
        var todateObj	=	new Date(parseInt(todate[0],10), parseInt(todate[1],10) - 1, parseInt(todate[2],10));

        if(fromdateObj > todateObj){
            return false;
        }
        else{
            return true;
        }
    });

    //enable, dissable views on loading
    //this is to findout whether passport details already entered
    if($("form#frmImmigrationDelete table tbody input.checkbox").length > 0) {
        $(".paddingLeftRequired").hide();
        $("#immigrationDataPane").hide();
    } else {
        $("#btnCancel").hide();
        $("#immigrationHeading").text(lang_addImmigrationHeading);
        $(".paddingLeftRequired").show();
        $("#immigrationDataPane").show();
        $("#immidrationList").hide();
    }

    //on clicking of add button
    $("#btnAdd").click(function(){
        loadDefaultDateMasks();
        $('div#immigrationDataPane label.error').hide();
        $("#immigrationHeading").text(lang_addImmigrationHeading);
        $(".paddingLeftRequired").show();
        $("#immigrationDataPane").show();
        $('form#frmImmigrationDelete table.data-table input.checkbox').hide();
        $("form#frmImmigrationDelete div.actionbar").hide();
        removeEditLinks();
        $("#messagebar").attr("class", "").text('');                
    });

    //on clicking cancel button
    $("#btnCancel").click(function() {
        $('div#immigrationDataPane label.error').hide();
        
        //clearing all entered values
        var controls = new Array("number", "passport_issue_date", "seqno", "passport_expire_date", "i9_status", "country", "i9_review_date", "comments");
        $("#immigration_type_flag_1").attr("checked", "checked");
        for(i=0; i < controls.length; i++) {
            $("#immigration_" + controls[i]).val("");
        }

        $(".paddingLeftRequired").hide();
        $("#immigrationDataPane").hide();
        $('form#frmImmigrationDelete table.data-table input.checkbox').show();
        $("form#frmImmigrationDelete div.actionbar").show();
        addEditLinks();
        $("#messagebar").attr("class", "").text('');                
    });

    //on clicking of delete button
    $("#btnDelete").click(function() {
        var ticks = $('input[@class=check]:checked').length;

        if(ticks > 1) {
            $("#frmImmigrationDelete").submit();
            return;
        }
        $("#messagebar").attr("class", "messageBalloon_notice");
        $("#messagebar").text(lang_deleteErrorMsg);

    });

    $.validator.addMethod("validdate", function(value, element) {
        if(value == "") {
            return true;
        }
        var dt = value.split("-");
        return validateDate(parseInt(dt[2], 10), parseInt(dt[1], 10), parseInt(dt[0], 10));
    });
    
     $('form#frmImmigrationDelete td.document a').live('click', function() {
        $('div#immigrationDataPane label.error').hide();
        
        var code = $(this).closest("tr").find('input.checkbox:first').val();
        fillDataToImmigrationDataPane(code);
        $('form#frmImmigrationDelete table.data-table input.checkbox').hide();
        $("form#frmImmigrationDelete div.actionbar").hide();
        $("#messagebar").attr("class", "").text('');        
     });
     
    //if check all button clicked
    $("#immigrationCheckAll").click(function() {
        $("form#frmImmigrationDelete table tbody .checkbox").removeAttr("checked");
        if($("#immigrationCheckAll").attr("checked")) {
            $("form#frmImmigrationDelete table tbody .checkbox").attr("checked", "checked");
        }
    });

    //remove tick from the all button if any checkbox unchecked
    $("form#frmImmigrationDelete table tbody .checkbox").click(function() {
        $("#immigrationCheckAll").removeAttr('checked');
        if($("form#frmImmigrationDelete table tbody .checkbox").length == $("form#frmImmigrationDelete table tbody .checkbox:checked").length) {
            $("#immigrationCheckAll").attr('checked', 'checked');
        }
    });     
    
    function addEditLinks() {
        // called here to avoid double adding links - When in edit mode and cancel is pressed.
        removeEditLinks();
        $('form#frmImmigrationDelete table tbody td.document').wrapInner('<a href="#"/>');
    }

    function removeEditLinks() {
        $('form#frmImmigrationDelete table tbody td.document a').each(function(index) {
            $(this).parent().text($(this).text());
        });
    }
    

});

//function to load data for updating
function fillDataToImmigrationDataPane(seqno) {

    var controls = new Array("number", "passport_issue_date", "passport_expire_date", "i9_status", "country", "i9_review_date", "comments");
    for(i=0; i < controls.length; i++) {
        //this is to say something like $('#immigration_number').val($("#number_" + seqno).val());
        $("#immigration_" + controls[i]).val($("#" + controls[i] + "_" + seqno).val());
    }
    $("#immigration_seqno").val(seqno);

    var typeFlag = $("#type_flag_" + seqno).val();
    $("#immigration_type_flag_" + typeFlag).attr("checked", "checked");

    $(".paddingLeftRequired").show();
    $("#immigrationHeading").text(lang_editImmigrationHeading);
    $("#immigrationDataPane").show();
}
