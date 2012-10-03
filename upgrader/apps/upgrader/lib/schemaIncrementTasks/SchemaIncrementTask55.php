<?php

/*
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

include_once 'SchemaIncrementTask.php';

class SchemaIncrementTask54 extends SchemaIncrementTask {

    public $userInputs;

    public function execute() {
        $this->incrementNumber = 54;
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

        $sql[0] = "CREATE TABLE ohrm_leave_type (
                    `id` int unsigned not null auto_increment,
                    `name` varchar(50) not null,
                    `deleted` tinyint(1) not null default 0,
                    `operational_country_id` int unsigned default null,
                    primary key  (`id`)
                  ) engine=innodb default charset=utf8;";

        $sql[1] = "CREATE TABLE ohrm_leave_entitlement (
                    `id` int not null auto_increment,
                    emp_number int(7) not null,
                    no_of_days int not null,
                    leave_type_id int unsigned not null,
                    from_date datetime not null,
                    to_date datetime,
                    credited_date datetime,
                    note varchar(255) default null, 
                    entitlement_type int not null,
                    `deleted` tinyint(1) not null default 0,
                    PRIMARY KEY(`id`)
                  ) ENGINE = INNODB DEFAULT CHARSET=utf8;";

        $sql[2] = "alter table ohrm_leave_entitlement
                    add foreign key (leave_type_id)
                        references ohrm_leave_type(id) on delete cascade;";

        $sql[3] = "alter table ohrm_leave_entitlement
                    add foreign key (emp_number)
                        references hs_hr_employee(emp_number) on delete cascade;";


       i       

        $this->sql = $sql;
    }

    public function getNotes() {
        return array();
    }

}