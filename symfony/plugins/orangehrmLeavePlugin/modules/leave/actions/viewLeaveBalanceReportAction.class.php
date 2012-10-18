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
                    $values = array();
                    $reportId = $reportType == LeaveBalanceReportForm::REPORT_TYPE_LEAVE_TYPE ? 4 : 3;
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
                    $this->reportName = $this->getReportName($reportId);

                    $this->tableHeaders = $reportBuilder->getDisplayHeaders($reportId);
                    $this->headerInfo = $reportBuilder->getHeaderInfo($reportId);
                    $this->tableWidthInfo = $reportBuilder->getTableWidth($reportId);                    

                }
            }
        }
    }
    
    private function getReportName($reportId) {
        $dao = new ReportDefinitionDao();
        $report = $dao->getReport($reportId);
        $reportName = $report->getName();
        return $reportName;
    }
    
}
