<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
 */

/**
 * Description of viewLeaveBalanceReportAction
 */
class viewLeaveBalanceReportAction extends sfAction {

    public function execute($request) {
        $this->form = new LeaveBalanceReportForm();

        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $reportType = $this->form->getValue('report_type');
                if ($reportType != 0) {
                    $values = $this->convertValues($this->form->getValues());
                    $reportId = $reportType == LeaveBalanceReportForm::REPORT_TYPE_LEAVE_TYPE ? 5 : 6;
                    $reportBuilder = new ReportBuilder();
                    $numOfRecords = $reportBuilder->getNumOfRecords($reportId, $values);
                    $maxPageLimit = $reportBuilder->getMaxPageLimit($reportId);
                    
                    $this->pager = new SimplePager('Report', $maxPageLimit);
                    $this->pager->setPage(($request->getParameter('pageNo') != '') ? $request->getParameter('pageNo') : 0);

                    $this->pager->setNumResults($numOfRecords);
                    $this->pager->init();
                    $offset = $this->pager->getOffset();
                    $offset = empty($offset) ? 0 : $offset;
                    $limit = $this->pager->getMaxPerPage();
        
                    $this->resultsSet = $reportBuilder->buildReport($reportId, $offset, $limit, $values);
                    
                    //var_dump($this->resultsSet[1]);
                    $this->reportName = $this->getReportName($reportId);

                    $headers = $reportBuilder->getDisplayHeaders($reportId);
                    $this->tableHeaders = $this->fixTableHeaders($reportType, $headers);

                    $this->headerInfo = $reportBuilder->getHeaderInfo($reportId);

                    $this->tableWidthInfo = $reportBuilder->getTableWidth($reportId);                    

                }
            }
        }
    }
    
    protected function convertValues($values) {
        
        $today = date('Y-m-d');
        $todayTimestamp = strtotime($today);
        
        $fromDate = $values['date']['from'];
        $fromTimestamp = strtotime($fromDate);
        
        $toDate = $values['date']['to'];
        $toTimestamp = strtotime($toDate);
        
        if ($todayTimestamp < $fromTimestamp) {
            $asOfDate = $today;
        } else if ($todayTimestamp > $toTimestamp) {
            $asOfDate = $toDate;
        } else {
            $asOfDate = $today;
        }
        $this->asOfDate = $asOfDate;
        
        
        $convertedValues = array(
            'leaveType' => array($values['leave_type']),
            'empNumber' => array($values['employee']['empId']),
            'fromDate' => array($fromDate),
            'toDate' => array($toDate),
            'asOfDate' => array($asOfDate),            
        );
        
        return $convertedValues;
    }
    
    /**
     * Fix table headings
     * TODO: Improve report engine to support customizable headers (eg: have a variable in the header)
     * and grouping fields from multiple tables.
     * @param type $headers
     * @return string
     */
    protected function fixTableHeaders($reportType, $headers) {

        $tableHeaders = $headers;
        
        /*$nameKey = $reportType == LeaveBalanceReportForm::REPORT_TYPE_LEAVE_TYPE ? 'personalDetails' : 'leavetype';
      
        $nameHeader = $headers[$nameKey];
        $firstHeader = $headers['g1'];
        $lastHeader = $headers['g6'];
        
        unset($headers[$nameKey]);
        unset($headers['g1']);
        unset($headers['g6']);
        
        $date = $this->form->getValue('date');
        $firstHeader['groupHeader'] = __('As of') . ' ' . set_datepicker_date_format($date['from']);
        $lastHeader['groupHeader'] = __('As of') . ' ' . set_datepicker_date_format(date(time()));         

        
        $otherHeaders = array('groupHeader' => __('From') . ' ' . set_datepicker_date_format($date['from']) . ' ' .
                __('To') . ' ' . set_datepicker_date_format($date['to']));
        
        foreach ($headers as $header) {
            foreach ($header as $key => $label) {
                if ($key != 'groupHeader') {
                    $otherHeaders[$key] = $label;                    
                }
            }
        }
        
        $tableHeaders = array('first' => $nameHeader,
                              'second' => $firstHeader,
                              'rest' => $otherHeaders,
                              'last' => $lastHeader);
        //print_r($tableHeaders);
        
        $tableHeaders = array(
            'leavetype' => array('groupHeader' => '', 'leaveType' => 'Leave Type'),
            'g1' => array('groupHeader' => '', 'entitlement' => 'Entitlements valid as of ' . set_datepicker_date_format($this->asOfDate)), 
            'g2' => array('groupHeader' => '',  'entitlement2' => 'Total Entitlments valid for the period'),
            'g3' => array('groupHeader' => '',  'closing' => 'Leave Balance as of ' . set_datepicker_date_format($this->asOfDate)),
            'g4' => array('groupHeader' => '',  'scheduled' => 'Leave Scheduled'),
            'g5' => array('groupHeader' => '',  'taken' => 'Leave Taken')            
        );
        
        */
        return $tableHeaders;
    }
    private function getReportName($reportId) {
        $dao = new ReportDefinitionDao();
        $report = $dao->getReport($reportId);
        $reportName = $report->getName();
        return $reportName;
    }
    
}
