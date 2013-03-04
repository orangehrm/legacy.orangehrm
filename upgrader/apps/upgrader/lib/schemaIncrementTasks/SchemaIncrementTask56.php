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
 * Changes from 3.0 to 3.0.1
 * 
 * 1) decimal points in leave entitlement related tables
 * 2) default home page tables
 *
 */
class SchemaIncrementTask56 extends SchemaIncrementTask {
    public $userInputs;

    public function execute() {
        $this->incrementNumber = 56;
        parent::execute();

        $result = array();

        foreach ($this->sql as $sql) {
            $result[] = $this->upgradeUtility->executeSql($sql);
        }

        $this->checkTransactionComplete($result);
        $this->updateOhrmUpgradeInfo($this->transactionComplete, $this->incrementNumber);
        $this->upgradeUtility->finalizeTransaction($this->transactionComplete);
        $this->upgradeUtility->closeDbConnection();
    }

    public function getUserInputWidgets() {
        
    }

    public function setUserInputs() {
        
    }

    public function loadSql() {

        $sql = array();   
        $sql[] = "ALTER TABLE ohrm_workflow_state_machine MODIFY COLUMN `id`  `id` bigint(20) NOT NULL AUTO_INCREMENT;";
             
        $this->sql = $sql;
    }

    public function getNotes() {
        return array();
    }
}


