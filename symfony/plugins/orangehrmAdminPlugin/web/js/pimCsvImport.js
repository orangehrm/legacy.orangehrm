$(document).ready(function() {
    
    $("#frmPimCsvImport").validate({

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
   
    $('#btnSave').click(function() {
        
        if ($('#frmPimCsvImport').valid()) {
            $("#btnSave").val(lang_processing);
        }

        $('#frmPimCsvImport').submit()

    });
   
   
});