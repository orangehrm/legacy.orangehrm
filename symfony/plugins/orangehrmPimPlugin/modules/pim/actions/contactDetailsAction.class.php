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
class contactDetailsAction extends basePimAction {

    /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }
    
    public function execute($request) {
        $this->showBackButton = true;
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        
        $contact = $request->getParameter('contact');
        $empNumber = (isset($contact['empNumber']))?$contact['empNumber']:$request->getParameter('empNumber');
        $this->empNumber = $empNumber;
        
        //hiding the back button if its self ESS view
        if($loggedInEmpNum == $empNumber) {

            $this->showBackButton = false;
        }
                
        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }

        $param = array('empNumber' => $empNumber);
        $this->setForm(new EmployeeConactDetailsForm(array(), $param, true));

        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }
        
        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $employee = $this->form->getEmployee();
                $this->getEmployeeService()->saveContactDetails($employee, false);
                $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
                $this->redirect('pim/contactDetails?empNumber='. $empNumber);
            }
        }
    }

}

