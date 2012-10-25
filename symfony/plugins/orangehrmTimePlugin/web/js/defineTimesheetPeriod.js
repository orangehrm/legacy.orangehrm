/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function(){

	$("#btnSave").click(function(){

		if($('#time_startingDays').val()==""){
			$('#btnSave').attr('disabled', 'disabled');
		}else{
			$('form#definePeriod').attr({
				action:linkTodefineTimesheetPeriod
			});
			$('form#definePeriod').submit();
		}
	});

	$('#time_startingDays').change(function() {
		if($('#time_startingDays').val()==""){
			$('#btnSave').attr('disabled', 'disabled');
		}else{
			$('#btnSave').removeAttr('disabled');
		}
	});
});