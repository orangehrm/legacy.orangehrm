function toggleFilters(show) {
        if (show) {
           $('ol#filter li:not(:first)').show();                
        } else {
            $('ol#filter li:not(:first)').hide();
        }        
    }
    
    
    function updateFilterMatches() {
        
        var params = '';
        
        $('ol#filter li:not(:first)').find('input,select').each(function(index, element) {
            var name = $(this).attr('name');
            name = name.replace('entitlements[filters][', '');
            name = name.replace(']', '');
            var value = $(this).val();

            params = params + '&' + name + '=' + value;
        });
        
        $.ajax({
            type: 'GET',
            url: getCountUrl,
            data: params,
            dataType: 'json',
            success: function(data) {
                filterMatchingEmployees = data;
                
                $('span#ajax_count').remove();
                var text = lang_matchesMany.replace('%count%', data);
                if (data == 1) {
                    text = lang_matchesOne;
                } else if (data == 0) {
                    text = lang_matchesNone;
                }

                $('ol#filter li:first').append('<span id="ajax_count">(' + text + ')</span>');
            }
        });
    }

    function updateEmployeeList() {
        
        var params = '';
        
        $('ol#employee_list').html('').append($('<li></li>').text(lang_Loading)); 
                
        $('ol#filter li:not(:first)').find('input,select').each(function(index, element) {
            var name = $(this).attr('name');
            name = name.replace('entitlements[filters][', '');
            name = name.replace(']', '');
            var value = $(this).val();

            params = params + '&' + name + '=' + value;
        });
        params = params + '&lt=' + $('#entitlements_leave_type').val() + '&fd='+$('#date_from').val()+ '&td='+ $('#date_to').val()+'&ent='+$('#entitlements_entitlement').val();
        $.ajax({
            type: 'GET',
            url: getEmployeeUrl,
            data: params,
            dataType: 'json',
            success: function(data) {                
                
                var count = data.length;
                var rows = $('ol#employee_list li').length;
                $('ol#employee_list').html('');
                var html = "<table class='table'><tr><th>"+lang_employee+"</th><th>"+lang_old_entitlement+"</th><th>"+lang_new_entitlement+"</th></tr>";
                for (var i = 0; i < count; i++) {
                    var css = "odd";
                    rows++;
                    if (rows % 2) {
                        css = "even";
                    }
                    var decodedName = $("<div/>").html(data[i][0]).text();
                    html = html + '<tr class="' + css + '"><td>'+decodedName+'</td><td>'+data[i][1]+'</td><td>'+data[i][2]+'</td></tr>';
                }
                html = html + '</table>';
                $('ol#employee_list').append(html);
            }
        });
    }

    $(document).ready(function() {               
        
        if(mode == 'update'){
            $('#filter').hide();
        }
        
        if ($('#entitlements_filters_bulk_assign').is(':checked')) {
            toggleFilters(true);    
            $('#entitlements_employee_empName').parent('li').hide();
        } else {
            toggleFilters(false);
        }
        
                
        $('#btnSave').click(function() {
            if ($('#entitlements_filters_bulk_assign').is(':checked')) {
                
                if (filterMatchingEmployees == 0) {
                    $('#noselection').modal();
                } else {
                    var valid = $('#frmLeaveEntitlementAdd').valid();
                    if (valid) {                      
                        updateEmployeeList();
                        
                        $('#preview').modal();
                    }
                }
            } else {
                if(!($('#entitlements_id').val() > 0)){
                    var valid = $('#frmLeaveEntitlementAdd').valid();
                        if (valid) {   
                            var params = '';

                            params = 'empId='+$('#entitlements_employee_empId').val()+'&lt=' + $('#entitlements_leave_type').val() + '&fd='+$('#date_from').val()+ '&td='+ $('#date_to').val()+'&ent='+$('#entitlements_entitlement').val();

                            $.ajax({
                                type: 'GET',
                                url: getEmployeeEntitlementUrl,
                                data: params,
                                dataType: 'json',
                                success: function(data) {                
                                    if( !isNaN(data[0]) && parseInt(data[0])!=0 ){
                                        $('ol#employee_entitlement_update').html(''); 
                                        var html = '<span>Existing Entitlement value '+ data[0]+' will be updated to '+ data[1] +'</span>'
                                        $('ol#employee_entitlement_update').append(html);
                                        $('#employeeEntitlement').modal();
                                    }else{
                                        $('#frmLeaveEntitlementAdd').submit();
                                    }

                                }
                            });

                        }

                    }else{
                        $('#frmLeaveEntitlementAdd').submit();
                    }
            }
        });        
        
        $('#dialogConfirmBtn').click(function() {
            $('#frmLeaveEntitlementAdd').submit();
        });
        
        $('#dialogUpdateEntitlementConfirmBtn').click(function() {
            $('#frmLeaveEntitlementAdd').submit();
        });

        $('#btnCancel').click(function() {
            window.location.href = listUrl;
        });        
 
        $('#entitlements_filters_bulk_assign').click(function(){     
            
            if ($('span#ajax_count').length == 0) {
                updateFilterMatches();
            }
            
            var checked = $(this).is(':checked');
            toggleFilters(checked);
            if (checked) {
                $('#entitlements_employee_empName').parent('li').hide();
            } else {
                $('#entitlements_employee_empName').parent('li').show();
                $('span#ajax_count').remove();
            }
        });
        
        $('ol#filter li:not(:first)').find('input,select').change(function(){
           updateFilterMatches(); 
        });
        
    $.validator.addMethod("twoDecimals", function(value, element, params) {
        
        var isValid = false;

        var match = value.match(/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/);
        if(match) {
            isValid = true;
        }
        if (value == ""){
            isValid = true;
        }
        return isValid;
    });        
 
        $('#frmLeaveEntitlementAdd').validate({
                rules: {
                    'entitlements[employee][empName]': {
                        required: function(element) {
                            return !$('#entitlements_filters_bulk_assign').is(':checked');
                        },
                        no_default_value: function(element) {

                            return {
                                defaults: $(element).data('typeHint')
                            }
                        }
                    },
                    'entitlements[leave_type]':{required: true },
                    'entitlements[date_from]': {
                        required: true,
                        valid_date: function() {
                            return {
                                required: true,                                
                                format:datepickerDateFormat,
                                displayFormat:displayDateFormat
                            }
                        }
                    },
                    'entitlements[date_to]': {
                        required: true,
                        valid_date: function() {
                            return {
                                required: true,
                                format:datepickerDateFormat,
                                displayFormat:displayDateFormat
                            }
                        },
                        date_range: function() {
                            return {
                                format:datepickerDateFormat,
                                displayFormat:displayDateFormat,
                                fromDate:$("#date_from").val()
                            }
                        }
                    },
                    'entitlements[entitlement]': {
                        required: true,
                        number: true,
                        twoDecimals: true,
                        remote: {
                            url: validEntitlemnetUrl,
                            data: {
                                id: $('#entitlements_id').val()
                            }
                        }
                    }
                    
                },
                messages: {
                    'entitlements[employee][empName]':{
                        required:lang_required,
                        no_default_value:lang_required
                    },
                    'entitlements[leave_type]':{
                        required:lang_required
                    },
                    'entitlements[date_from]':{
                        required:lang_invalidDate,
                        valid_date: lang_invalidDate
                    },
                    'entitlements[date_to]':{
                        required:lang_invalidDate,
                        valid_date: lang_invalidDate ,
                        date_range: lang_dateError
                    },
                    'entitlements[entitlement]': {
                        required: lang_required,
                        number: lang_number,
                        remote : lang_valid_entitlement,
                        twoDecimals: lang_number
                    }                    
            }

        });
        
    });


