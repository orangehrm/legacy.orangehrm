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
 */

/**
 * Actions class for PIM module updateMembership
 */
class updateReportToDetailAction extends basePimAction {
    
    private $reportingMethodService;
    
    public function getReportingMethodService() {
        
        if (!($this->reportingMethodService instanceof ReportingMethodService)) {
            $this->reportingMethodService = new ReportingMethodService();
        }        
        
        return $this->reportingMethodService;
    }

    public function setReportingMethodService($reportingMethodService) {
        $this->reportingMethodService = $reportingMethodService;
    }

    /**
     * Add / update employee membership
     *
     * @param int $empNumber Employee number
     *
     * @return boolean true if successfully assigned, false otherwise
     */
    public function execute($request) {

        $memberships = $request->getParameter('reportto');
        $empNumber = (isset($memberships['empNumber'])) ? $memberships['empNumber'] : $request->getParameter('empNumber');
        $this->empNumber = $empNumber;

        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        $adminMode = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);
        $essMode = !$adminMode && !empty($loggedInEmpNum) && ($empNumber == $loggedInEmpNum);
        $param = array('empNumber' => $empNumber, 'ESS' => $essMode);

        $this->form = new EmployeeReportToForm(array(), $param, true);

        if ($this->getRequest()->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                
                $this->_checkDuplicateEntry($empNumber);
                
                $value = $this->form->save();
                if ($value[0] == ReportTo::SUPERVISOR) {
                    if ($value[1]) {
                        $this->getUser()->setFlash('templateMessage', array('success', __('Supervisor Updated Successfully')));
                    } else {
                        $this->getUser()->setFlash('templateMessage', array('success', __('Supervisor Added Successfully')));
                    }
                }
                if ($value[0] == ReportTo::SUBORDINATE) {
                    if ($value[1]) {
                        $this->getUser()->setFlash('templateMessage', array('success', __('Subordinate Updated Successfully')));
                    } else {
                        $this->getUser()->setFlash('templateMessage', array('success', __('Subordinate Added Successfully')));
                    }
                }
            }
        }

        $empNumber = $request->getParameter('empNumber');

        $this->redirect('pim/viewReportToDetails?empNumber=' . $empNumber);
    }
    
    protected function _checkDuplicateEntry($empNumber) {

        if (empty($id) && $this->getReportingMethodService()->isExistingReportingMethodName($this->form->getValue('reportingMethod'))) {
            $this->getUser()->setFlash('templateMessage', array('warning', __('Reporting Method Name Exists')));
            $this->redirect('pim/viewReportToDetails?empNumber=' . $empNumber);
        }

    }

}
