$(document).ready(function() {
          
    $('#btnAdd').click(function() {
        window.location.replace(addLocationUrl);
    });
    
    $('#btnDelete').attr('disabled', 'disabled');

        
    $("#ohrmList_chkSelectAll").click(function() {
        if($(":checkbox").length == 1) {
            $('#btnDelete').attr('disabled','disabled');
        }
        else {
            if($("#ohrmList_chkSelectAll").is(':checked')) {
                $('#btnDelete').removeAttr('disabled');
            } else {
                $('#btnDelete').attr('disabled','disabled');
            }
        }
    });
    
    $(':checkbox[name*="chkSelectRow[]"]').click(function() {
        if($(':checkbox[name*="chkSelectRow[]"]').is(':checked')) {
            $('#btnDelete').removeAttr('disabled');
        } else {
            $('#btnDelete').attr('disabled','disabled');
        }
    });
    
    $('#btnReset').click(function(){
        window.location.replace(viewLocationUrl);
    })
    
    $('#btnSearch').click(function(){
        $('#frmSearchLocation').submit()
    });
    
    $('#btnDelete').click(function(){
        $('#frmList_ohrmListComponent').submit(function(){
            $('#deleteConfirmation').dialog('open');
            return false;
        });
    });

    $('#frmList_ohrmListComponent').attr('name','frmList_ohrmListComponent');
    $('#dialogDeleteBtn').click(function() {
        document.frmList_ohrmListComponent.submit();
    });
    $('#dialogCancelBtn').click(function() {
        $("#deleteConfirmation").dialog("close");
    });
    
     /* Toggling search form: Begins */
        $("#location-information .inner").hide();
        
        $("#location-information .toggle").click(function () {
            $("#location-information .inner").slideToggle('slow', function() {
                if($(this).is(':hidden')) {
                    $('#location-information .tiptip').tipTip({content:'Expand for options'});
                } else {
                    $('#location-information .tiptip').tipTip({content:'Hide options'});
                }
            });
            $(this).toggleClass("activated");
        }); 

        $("#search-results .toggle").click(function () {
            $("#search-results .inner").slideToggle();
        });
        /* Toggling search form: Ends */
    
});