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
 * Description of LeaveMailerTest
 */
class LeaveMailerTest extends PHPUnit_Framework_TestCase {
    protected $mailer;
    
    protected function setUp() {
        $this->mailer = new LeaveMailer();
    }
    
    public function testListenOneWorkflow() {
        
        $workFlow = new WorkflowStateMachine();
        $workFlow->setAction('apply');
        $workFlow->setRolesToNotify('ESS,Supervisor,ABC');
        
        $eventData = array('workFlow' => $workFlow);
        $leaveTypes = array('leave.apply.ess', 'leave.apply.supervisor', 'leave.apply.abc');
        $mockService = $this->getMock('EmailService', array('sendEmailNotifications'));
        $mockService->expects($this->once())
                ->method('sendEmailNotifications')
                ->with($leaveTypes, $eventData);
        
        $this->mailer->setEmailService($mockService);
        $sfEvent = new sfEvent($this, 'test', $eventData);
        $this->mailer->listen($sfEvent);
    }  
}
