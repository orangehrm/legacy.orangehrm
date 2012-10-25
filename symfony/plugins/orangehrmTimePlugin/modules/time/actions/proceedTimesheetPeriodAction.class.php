<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of proceedTimesheetPeriodAction
 *
 * @author orangehrm
 */
class proceedTimesheetPeriodAction extends sfAction {

	public function execute($request) {
		/* For highlighting corresponding menu item */
        $request->setParameter('initialActionName', 'viewEmployeeTimesheet');
        
	}

  
}
?>
