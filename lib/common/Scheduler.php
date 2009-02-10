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
 
if (!defined("ROOT_PATH")) {
    define("ROOT_PATH", "../../");
} 
require_once ROOT_PATH . '/lib/controllers/RecruitmentController.php';

// Call Scheduler::main() if this source file is executed directly.
if (!defined("ORANGEHRM_Scheduler_MAIN_METHOD")) {
    define("ORANGEHRM_Scheduler_MAIN_METHOD", "Scheduler::main");
}

/**
 * Class to handle scheduled tasks run at different times.
 */
 class Scheduler {

	/** This singleton instance */
	private static $instance;

	/**
	 * Private construct
	 */
	private function __construct() {
	}

	/**
	 * Get the singleton instance of this class
	 */
	 public static function getInstance() {

	 	if (!is_a(self::$instance, 'Scheduler')) {
	 		self::$instance = new Scheduler();
	 	}

		return self::$instance;
	 }

     /**
      * Runs events scheduled to run at log-in      
      */
     public function runEventsAtLogin() {
        
        // TODO: Improve this to reduce scheduled runs, eg: keep last run time saved. 
        RecruitmentController::checkShortListedApplicants();
     }
     
     public static function main() {
         $scheduler = Scheduler::getInstance();
         $scheduler->runEventsAtLogin();
     }
}

// Call Scheduler::main() if this source file is executed directly.
if (ORANGEHRM_Scheduler_MAIN_METHOD == "Scheduler::main") {
    Scheduler::main();
}