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
 * Changes from 3.1 to 3.1.1
 * 
 * 1) data group, screen changes
 * 2) new table ohrm_data_group_screen
 */
class SchemaIncrementTask58 extends SchemaIncrementTask {

    public $userInputs;

    public function execute() {
        $this->incrementNumber = 58;
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

        $screenId = $this->getNextScreenId();
        $dataGroupId = $this->getNextDataGroupId();

        $sql = array();

        $sql[1] = "CREATE TABLE ohrm_data_group_screen (
                    `id` int AUTO_INCREMENT, 
                    `data_group_id` int, 
                    `screen_id` int, 
                    `permission` int,
                    PRIMARY KEY(`id`)
                ) ENGINE = INNODB DEFAULT CHARSET=utf8;";

        $sql[2] = "alter table ohrm_data_group_screen
                    add foreign key (data_group_id) references ohrm_data_group(id) on delete cascade;";

        $sql[3] = "alter table ohrm_data_group_screen
                    add foreign key (screen_id) references ohrm_screen(id) on delete cascade;";

        $sql[4] = "UPDATE ohrm_screen SET name='View Project Report Criteria'
                    WHERE id=57;";

        $sql[5] = "UPDATE ohrm_screen SET name='View Employee Report Criteria'
                    WHERE id=58;";

        $sql[6] = "INSERT INTO ohrm_screen (`id`, `name`, `module_id`, `action_url`) VALUES
                    (" . ($screenId) . ", 'Save Job Title', 2, 'saveJobTitle'),
                    (" . ($screenId+1) . ", 'Delete Job Title', 2, 'deleteJobTitle'),
                    (" . ($screenId+2) . ", 'Save Pay Grade', 2, 'payGrade'),
                    (" . ($screenId+3) . ", 'Delete Pay Grade', 2, 'deletePayGrades'),
                    (" . ($screenId+4) . ", 'Save Pay Grade Currency', 2, 'savePayGradeCurrency'),
                    (" . ($screenId+5) . ", 'Delete Pay Grade Currency', 2, 'deletePayGradeCurrency'),
                    (" . ($screenId+6) . ", 'Add Customer', 2, 'addCustomer'),
                    (" . ($screenId+7) . ", 'Delete Customer', 2, 'deleteCustomer'),
                    (" . ($screenId+8) . ", 'Save Project', 2, 'saveProject'),
                    (" . ($screenId+9) . ", 'Delete Project', 2, 'deleteProject'),
                    (" . ($screenId+10) . ", 'Add Project Adtivity', 2, 'addProjectActivity'),
                    (" . ($screenId+11) . ", 'Delete Project Adtivity', 2, 'deleteProjectActivity'),
                    (" . ($screenId+12) . ", 'Define PIM reports', 1, 'definePredefinedReport'),
                    (" . ($screenId+13) . ", 'Display PIM reports', 1, 'displayPredefinedReport'),
                    (" . ($screenId+14) . ", 'Add Job Vacancy', 7, 'addJobVacancy'),
                    (" . ($screenId+15) . ", 'Delete Job Vacancy', 7, 'deleteJobVacancy'),
                    (" . ($screenId+16) . ", 'Add Candidate', 7, 'addCandidate'),
                    (" . ($screenId+17) . ", 'Delete Candidate', 7, 'deleteCandidateVacancies'),
                    (" . ($screenId+18) . ", 'View Leave Request', 4, 'viewLeaveRequest'),
                    (" . ($screenId+19) . ", 'Change Leave Status', 4, 'changeLeaveStatus'),
                    (" . ($screenId+20) . ", 'Terminate Employment', 3, 'terminateEmployement'),
                    (" . ($screenId+21) . ", 'View Attendance Summary Report', 5, 'displayAttendanceSummaryReport'),
                    (" . ($screenId+22) . ", 'View Project Activity Details Report', 5, 'displayProjectActivityDetailsReport');";

        $sql[7] = "INSERT INTO ohrm_user_role_screen (user_role_id, screen_id, can_read, can_create, can_update, can_delete) VALUES
                    (1, " . ($screenId) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+1) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+2) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+3) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+4) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+5) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+6) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+7) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+8) . ", 1, 1, 1, 1),
                    (4, " . ($screenId+8) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+9) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+10) . ", 1, 1, 1, 1),
                    (4, " . ($screenId+10) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+11) . ", 1, 1, 1, 1),
                    (4, " . ($screenId+11) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+12) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+13) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+14)  . ", 1, 1, 1, 1),
                    (1, " . ($screenId+15) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+16) . ", 1, 1, 1, 1),
                    (5, " . ($screenId+16) . ", 1, 1, 1, 1),
                    (6, " . ($screenId+16) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+17) . ", 1, 1, 1, 1),
                    (6, " . ($screenId+17) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+18) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+19) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+21) . ", 1, 1, 1, 1),
                    (3, " . ($screenId+21) . ", 1, 1, 1, 1),
                    (1, " . ($screenId+22) . ", 1, 1, 1, 1),
                    (4, " . ($screenId+22) . ", 1, 1, 1, 1);";

        $sql[8] = "INSERT INTO `ohrm_data_group` (`id`, `name`, `description`, `can_read`, `can_create`, `can_update`, `can_delete`) VALUES
                    (" . ($dataGroupId) . ", 'job_titles', 'Admin - Job Titles', 1, 1, 1, 1),
                    (" . ($dataGroupId+1) . ", 'pay_grades', 'Admin - Pay Grades', 1, 1, 1, 1),
                    (" . ($dataGroupId+2) . ", 'time_customers', 'Time - Project Info - Customers', 1, 1, 1, 1),
                    (" . ($dataGroupId+3) . ", 'time_projects', 'Time - Project Info - Projects', 1, 1, 1, 1),
                    (" . ($dataGroupId+4) . ", 'pim_reports', 'PIM - Reports', 1, 1, 1, 1),
                    (" . ($dataGroupId+5) . ", 'attendance_configuration', 'Time - Attendance Configuration', 1, 0, 1, 0),
                    (" . ($dataGroupId+6) . ", 'attendance_manage_records', 'Time - Attendance Manage Records', 1, 0, 0, 0),
                    (" . ($dataGroupId+7) . ", 'time_project_reports', 'Time - Project Reports', 1, 0, 0, 0),
                    (" . ($dataGroupId+8) . ", 'time_employee_reports', 'Time - Employee Reports', 1, 0, 0, 0),
                    (" . ($dataGroupId+9) . ", 'attendance_summary', 'Time - Attendance Summary', 1, 0, 0, 0),
                    (" . ($dataGroupId+10) . ", 'leave_period', 'Leave - Leave Period', 1, 0, 1, 0),
                    (" . ($dataGroupId+11) . ", 'leave_types', 'Leave - Leave Types', 1, 1, 1, 1),
                    (" . ($dataGroupId+12) . ", 'work_week', 'Leave - Work Week', 1, 0, 1, 0),
                    (" . ($dataGroupId+13) . ", 'holidays', 'Leave - Holidays', 1, 1, 1, 1),
                    (" . ($dataGroupId+14) . ", 'recruitment_vacancies', 'Recruitment - Vacancies', 1, 1, 1, 1),
                    (" . ($dataGroupId+15) . ", 'recruitment_candidates', 'Recruitment - Candidates', 1, 1, 1, 1),
                    (" . ($dataGroupId+16) . ", 'time_manage_employees', 'Time - Employee Timesheets', 1, 0, 0, 0),
                    (" . ($dataGroupId+17) . ", 'leave_list', 'Leave - Leave List', 1, 0, 0, 0);";

        $sql[9] = "INSERT INTO `ohrm_user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`, `self`) VALUES
                    (1, " . ($dataGroupId) . ", 1, 1, 1, 1, 0),
                    (2, " . ($dataGroupId) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId) . ", 0, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+1) . ", 1, 1, 1, 1, 0),
                    (2, " . ($dataGroupId+1) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+1) . ", 0, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+2) . ", 1, 1, 1, 1, 0),
                    (2, " . ($dataGroupId+2) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+2) . ", 0, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+3) . ", 1, 1, 1, 1, 0),
                    (2, " . ($dataGroupId+3) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+3) . ", 0, 0, 0, 0, 0),
                    (4, " . ($dataGroupId+3) . ", 1, 0, 1, 0, 0),

                    (1, " . ($dataGroupId+4) . ", 1, 1, 1, 1, 0),
                    (2, " . ($dataGroupId+4) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+4) . ", 0, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+5) . ", 1, NULL, 1, NULL, 0),
                    (2, " . ($dataGroupId+5) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+5) . ", 0, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+6) . ", 1, 0, 0, 0, 0),
                    (2, " . ($dataGroupId+6) . ", 0, 0, 0, 0, 0),
                    (2, " . ($dataGroupId+6) . ", 1, 0, 0, 0, 1),
                    (3, " . ($dataGroupId+6) . ", 1, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+7) . ", 1, 0, 0, 0, 0),
                    (2, " . ($dataGroupId+7) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+7) . ", 0, 0, 0, 0, 0),
                    (4, " . ($dataGroupId+7) . ", 1, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+8) . ", 1, 0, 0, 0, 0),
                    (2, " . ($dataGroupId+8) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+8) . ", 1, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+9) . ", 1, 0, 0, 0, 0),
                    (2, " . ($dataGroupId+9) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+9) . ", 1, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+10) . ", 1, NULL, 1, NULL, 0),
                    (2, " . ($dataGroupId+10) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+10) . ", 0, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+11) . ", 1, 1, 1, 1, 0),
                    (2, " . ($dataGroupId+11) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+11) . ", 0, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+12) . ", 1, 0, 1, 0, 0),
                    (2, " . ($dataGroupId+12) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+12) . ", 0, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+13) . ", 1, 1, 1, 1, 0),
                    (2, " . ($dataGroupId+13) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+13) . ", 0, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+14) . ", 1, 1, 1, 1, 0),
                    (2, " . ($dataGroupId+14) . ", 0, 0, 0, 0, 0),
                    (3, " . ($dataGroupId+14) . ", 0, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+15) . ", 1, 1, 1, 1, 0),
                    (6, " . ($dataGroupId+15) . ", 1, 1, 1, 1, 0),
                    (5, " . ($dataGroupId+15) . ", 1, 0, 1, 0, 0),

                    (1, " . ($dataGroupId+16) . ", 1, 0, 0, 0, 0),
                    (2, " . ($dataGroupId+16) . ", 0, 0, 0, 0, 0),
                    (2, " . ($dataGroupId+16) . ", 1, 0, 0, 0, 1),
                    (3, " . ($dataGroupId+16) . ", 1, 0, 0, 0, 0),

                    (1, " . ($dataGroupId+17) . ", 1, 0, 0, 0, 0),
                    (2, " . ($dataGroupId+17) . ", 1, 0, 0, 0, 1),
                    (3, " . ($dataGroupId+17) . ", 1, 0, 0, 0, 0);";

        $sql[10] = "INSERT INTO `ohrm_data_group_screen`(`data_group_id`, `screen_id`, `permission`) VALUES
                    (40, 69, 1),
                    (40, 72, 2),
                    (40, 72, 3),
                    (40, 71, 4),

                    (41, 78, 1),

                    (" . ($dataGroupId) . ", 23, 1),
                    (" . ($dataGroupId) . ", " . ($screenId) . ", 2),
                    (" . ($dataGroupId) . ", " . ($screenId) . ", 3),
                    (" . ($dataGroupId) . ", " . ($screenId+1) . ", 4),

                    (" . ($dataGroupId+1) . ", 24, 1),
                    (" . ($dataGroupId+1) . ", " . ($screenId+2) . ", 2),
                    (" . ($dataGroupId+1) . ", " . ($screenId+2) . ", 3),
                    (" . ($dataGroupId+1) . ", " . ($screenId+3) . ", 4),
                    (" . ($dataGroupId+1) . ", " . ($screenId+4) . ", 2),
                    (" . ($dataGroupId+1) . ", " . ($screenId+4) . ", 3),
                    (" . ($dataGroupId+1) . ", " . ($screenId+5) . ", 2),
                    (" . ($dataGroupId+1) . ", " . ($screenId+5) . ", 3),

                    (" . $dataGroupId . ", 74, 1),
                    (" . ($dataGroupId+1) . ", 74, 1),

                    (" . ($dataGroupId+2) . ", 36, 1),
                    (" . ($dataGroupId+2) . ", " . ($screenId+6) . ", 2),
                    (" . ($dataGroupId+2) . ", " . ($screenId+6) . ", 3),
                    (" . ($dataGroupId+2) . ", " . ($screenId+7) . ", 4),

                    (" . ($dataGroupId+3) . ", 37, 1),
                    (" . ($dataGroupId+3) . ", " . ($screenId+8) . ", 1),
                    (" . ($dataGroupId+3) . ", " . ($screenId+8) . ", 2),
                    (" . ($dataGroupId+3) . ", " . ($screenId+8) . ", 3),
                    (" . ($dataGroupId+3) . ", " . ($screenId+9) . ", 4),
                    (" . ($dataGroupId+3) . ", " . ($screenId+10) . ", 2),
                    (" . ($dataGroupId+3) . ", " . ($screenId+10) . ", 3),
                    (" . ($dataGroupId+3) . ", " . ($screenId+11) . ", 2),
                    (" . ($dataGroupId+3) . ", " . ($screenId+11) . ", 3),

                    (" . ($dataGroupId+4) . ", 45, 1),
                    (" . ($dataGroupId+4) . ", 45, 4),
                    (" . ($dataGroupId+4) . ", " . ($screenId+12) . ", 2),
                    (" . ($dataGroupId+4) . ", " . ($screenId+12) . ", 3),
                    (" . ($dataGroupId+4) . ", " . ($screenId+13) . ", 1),

                    (" . ($dataGroupId+5) . ", 56, 1),
                    (" . ($dataGroupId+5) . ", 56, 3),

                    (" . ($dataGroupId+6) . ", 55, 1),

                    (" . ($dataGroupId+7) . ", 57, 1),
                    (" . ($dataGroupId+7) . ", " . ($screenId+22) . ", 1),

                    (" . ($dataGroupId+8) . ", 58, 1),

                    (" . ($dataGroupId+9) . ", 59, 1),
                    (" . ($dataGroupId+9) . ", " . ($screenId+21) . ", 1),

                    (" . ($dataGroupId+10) . ", 47, 1),
                    (" . ($dataGroupId+10) . ", 47, 3),

                    (" . ($dataGroupId+11) . ", 7, 1),
                    (" . ($dataGroupId+11) . ", 8, 1),
                    (" . ($dataGroupId+11) . ", 8, 2),
                    (" . ($dataGroupId+11) . ", 8, 3),
                    (" . ($dataGroupId+11) . ", 9, 2),
                    (" . ($dataGroupId+11) . ", 10, 4),

                    (" . ($dataGroupId+12) . ", 14, 1),
                    (" . ($dataGroupId+12) . ", 14, 3),

                    (" . ($dataGroupId+13) . ", 11, 1),
                    (" . ($dataGroupId+13) . ", 12, 2),
                    (" . ($dataGroupId+13) . ", 12, 3),
                    (" . ($dataGroupId+13) . ", 13, 4),

                    (" . ($dataGroupId+14) . ", 61, 1),
                    (" . ($dataGroupId+14) . ", " . ($screenId+14) . ", 1),
                    (" . ($dataGroupId+14) . ", " . ($screenId+14) . ", 2),
                    (" . ($dataGroupId+14) . ", " . ($screenId+14) . ", 3),
                    (" . ($dataGroupId+14) . ", " . ($screenId+15) . ", 4),

                    (" . ($dataGroupId+15) . ", 60, 1),
                    (" . ($dataGroupId+15) . ", " . ($screenId+16) . ", 1),
                    (" . ($dataGroupId+15) . ", " . ($screenId+16) . ", 2),
                    (" . ($dataGroupId+15) . ", " . ($screenId+16) . ", 3),
                    (" . ($dataGroupId+15) . ", " . ($screenId+17) . ", 4),

                    (" . ($dataGroupId+14) . ", 76, 1),
                    (" . ($dataGroupId+15) . ", 76, 1),

                    (" . ($dataGroupId+16) . ", 52, 1),

                    (" . ($dataGroupId+17) . ", 16, 1),
                    (" . ($dataGroupId+17) . ", " . ($screenId+18) . ", 1),
                    (" . ($dataGroupId+17) . ", " . ($screenId+19) . ", 1);";

        $sql[11] = "UPDATE ohrm_module_default_page SET action='time/timesheetPeriodNotDefined'
                    WHERE module_id=5 AND user_role_id=2;";


        // Allow null in reviewer_id
        $sql[12] = "ALTER TABLE hs_hr_performance_review 
                        CHANGE reviewer_id reviewer_id int(13) null;";
        
        // Delete records with invalid employee_id (linked to deleted ids)        
        $sql[13] = "delete from hs_hr_performance_review where employee_id not in (select emp_number from hs_hr_employee);";
        
        // Set reviewer_id = null where reviewer employee is deleted
        $sql[14] = "update hs_hr_performance_review set reviewer_id = null where reviewer_id not in (select emp_number from hs_hr_employee);";
        
        // Add constraints
        $sql[15] = "alter table hs_hr_performance_review
                        add constraint foreign key (employee_id)
                            references hs_hr_employee (emp_number) on delete cascade;";

        $sql[16] = "alter table hs_hr_performance_review
                        add constraint foreign key (reviewer_id)
                            references hs_hr_employee (emp_number) on delete set null;";

        $this->sql = $sql;
    }

    public function getNotes() {
        
    }

    protected function getScalarValueFromQuery($query) {
        $result = $this->upgradeUtility->executeSql($query);
        $row = mysqli_fetch_row($result);

        $logMessage = print_r($row, true);
        UpgradeLogger::writeLogMessage($logMessage);
        $value = $row[0];
        UpgradeLogger::writeLogMessage('value = ' . $value . ' value + 1 = ' . ($value + 1));

        return $value + 1;
    }

    public function getNextScreenId() {
        return $this->getScalarValueFromQuery('SELECT MAX(id) FROM ohrm_screen');
    }

    public function getNextDataGroupId() {
        return $this->getScalarValueFromQuery('SELECT MAX(id) FROM ohrm_data_group');
    }

}