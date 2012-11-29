$(document).ready(function() {
   
    $('#btnSave').click(function() {
        if(isValidForm()){
            $("#btnSave").val('Processing');
            $('#frmPimCsvImport').submit()
        }
    });
   
   
});

function isValidForm(){
 
    var validator = $("#frmPimCsvImport").validate({

        rules: {
            'pimCsvImport[csvFile]' : {
                required:true
            }

        },
        messages: {
            'pimCsvImport[csvFile]' : {
                required:lang_csvRequired
            }

        }

    });
    return true;
}