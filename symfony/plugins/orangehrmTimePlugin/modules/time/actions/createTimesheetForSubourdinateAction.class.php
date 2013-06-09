<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of createTimesheetForSubourdinateAction
 *
 * @author nirmal
 */
class createTimesheetForSubourdinateAction extends baseTimeAction{
    public function execute($request) {
        $request->setParameter('initialActionName', 'viewEmployeeTimesheet');
        
        $this->employeeId = $request->getParameter('employeeId');
        $this->timesheetManagePermissions = $this->getDataGroupPermissions('time_manage_employees', $this->employeeId);

        $this->userObj = $this->getContext()->getUser()->getAttribute('user');
        $userId = $this->userObj->getUserId();
        $userEmployeeNumber = $this->userObj->getEmployeeNumber();
        
        $userRoleFactory = new UserRoleFactory();
        $decoratedUser = $userRoleFactory->decorateUserRole($userId, $this->employeeId, $userEmployeeNumber);
        $this->allowedToCreateTimesheets = $decoratedUser->getAllowedActions(PluginWorkflowStateMachine::FLOW_TIME_TIMESHEET, PluginTimesheet::STATE_INITIAL);

        $this->createTimesheetForm = new CreateTimesheetForm();
        $this->currentDate = date('Y-m-d');
        if ($this->getContext()->getUser()->hasFlash('errorMessage')) {

            $this->messageData = array('error', __($this->getContext()->getUser()->getFlash('errorMessage')));
        }
    }
}

?>
