<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TimeFlowMapper
 *
 * @author nusky
 */
class TimeFlowMapper {

    private $selenium;

    public function __construct($selenuim) {
        $this->selenium = $selenuim;
    }

    public function getFlowObject($string) {

        switch ($string) {

            case 'LogIn':
                $LogIn = new LogInFlow($this->selenium);
                return $LogIn;
            case 'PunchIn':
                $punchIn = new PunchIn($this->selenium);
                return $punchIn;
            case 'PunchOut':
                $punchOut = new PunchOut($this->selenium);
                return $punchOut;
            case 'addTimeSheet':
                $addTimeSheet = new AddTimeSheetFlow($this->selenium);
                return $addTimeSheet;
            case 'viewTimeSheet':
                $viewTimeSheet = new viewTimeSheetFlow($this->selenium);
                return $viewTimeSheet;
            case 'editTimeSheet':
                $editTimeSheet = new EditTimeSheetFlow($this->selenium);
                return $editTimeSheet;
            case 'saveTimeSheet':
                $saveTimeSheet = new SaveTimeSheetFlow($this->selenium);
                return $saveTimeSheet;
            case 'resetTimeSheet':
                $resetTimeSheet = new ResetTimeSheetFlow($this->selenium);
                return $resetTimeSheet;
            case 'cancelTimeSheet':
                $cancelTimeSheet = new CancelTimeSheetFlow($this->selenium);
                return $cancelTimeSheet;
            case 'viewTimeSheetESS':
                $viewTimeSheetESS = new ViewTimeSheetESSFlow($this->selenium);
                return $viewTimeSheetESS;
            case 'submitTimeSheet':
                $submitTimeSheet = new SubmitTimeSheetFlow($this->selenium);
                return $submitTimeSheet;
            case 'viewMyAttendanceRecord':
                $viewMyAttendanceRecord = new ViewMyAttendanceRecordFlow($this->selenium);
                return $viewMyAttendanceRecord;
            case 'viewAttendanceRecord':
                $viewAttendanceRecord = new ViewAttendanceRecordFlow($this->selenium);
                return $viewAttendanceRecord;
            case 'editAttendance':
                $editAttendance = new EditAttendanceRecordFlow($this->selenium);
                return $editAttendance;
            case 'addTimeSheetRecord':
                $addTimeSheetRecord = new AddTimeSheetRecordFlow($this->selenium);
                return $addTimeSheetRecord;
            case 'approveTimeSheet':
                $approveTimeSheet = new ApproveTimeSheetFlow($this->selenium);
                return $approveTimeSheet;
            case 'selectTimeSheet':
                $selectTimeSheet = new SelectTimeSheetFlow($this->selenium);
                return $selectTimeSheet;
            case 'rejectTimeSheet':
                $rejectTimeSheet = new RejectTimeSheetFlow($this->selenium);
                return $rejectTimeSheet;
            case 'viewProjectReport':
                $viewProjectReport = new ViewProjectReportFlow($this->selenium);
                return $viewProjectReport;
            case 'viewEmployeeReport':
                $viewEmployeeReport = new ViewEmployeeReportFlow($this->selenium);
                return $viewEmployeeReport;
            case 'addAttendanceRecord':
                $addAttendanceRecord = new AddAttendanceRecordFlow($this->selenium);
                return $addAttendanceRecord;
            case 'punchInSubOrdinate':
                $punchInSubOrdinate = new PunchInSubordinateAttendanceFlow($this->selenium);
                return $punchInSubOrdinate;
            case 'punchOutSubOrdinate':
                $punchOutSubOrdinate = new PunchOutSubordinateAttendanceFlow($this->selenium);
                return $punchOutSubOrdinate;
            case 'viewAttendanceSummary':
                $viewAttendanceSummary = new ViewAttendanceSummaryReportFlow($this->selenium);
                return $viewAttendanceSummary;
            case 'viewSubOrdinateEmployeeReport':
                $viewSubOrdinateEmployeeReport = new ViewEmployeeReportAsSupervisorFlow($this->selenium);
                return $viewSubOrdinateEmployeeReport;
        }
    }

}