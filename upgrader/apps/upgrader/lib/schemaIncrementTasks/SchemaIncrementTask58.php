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
                    (80, 'Save Job Title', 2, 'saveJobTitle'),
                    (81, 'Delete Job Title', 2, 'deleteJobTitle'),
                    (82, 'Save Pay Grade', 2, 'payGrade'),
                    (83, 'Delete Pay Grade', 2, 'deletePayGrades'),
                    (84, 'Save Pay Grade Currency', 2, 'savePayGradeCurrency'),
                    (85, 'Delete Pay Grade Currency', 2, 'deletePayGradeCurrency'),
                    (86, 'Add Customer', 2, 'addCustomer'),
                    (87, 'Delete Customer', 2, 'deleteCustomer'),
                    (88, 'Save Project', 2, 'saveProject'),
                    (89, 'Delete Project', 2, 'deleteProject'),
                    (90, 'Add Project Adtivity', 2, 'addProjectActivity'),
                    (91, 'Delete Project Adtivity', 2, 'deleteProjectActivity'),
                    (92, 'Define PIM reports', 1, 'definePredefinedReport'),
                    (93, 'Display PIM reports', 1, 'displayPredefinedReport'),
                    (94, 'Add Job Vacancy', 7, 'addJobVacancy'),
                    (95, 'Delete Job Vacancy', 7, 'deleteJobVacancy'),
                    (96, 'Add Candidate', 7, 'addCandidate'),
                    (97, 'Delete Candidate', 7, 'deleteCandidateVacancies'),
                    (98, 'View Leave Request', 4, 'viewLeaveRequest'),
                    (99, 'Change Leave Status', 4, 'changeLeaveStatus'),
                    (100, 'Terminate Employment', 3, 'terminateEmployement'),
                    (101, 'View Attendance Summary Report', 5, 'displayAttendanceSummaryReport'),
                    (102, 'View Project Activity Details Report', 5, 'displayProjectActivityDetailsReport');";
        
        $sql[7] = "INSERT INTO ohrm_user_role_screen (user_role_id, screen_id, can_read, can_create, can_update, can_delete) VALUES
                    (1, 80, 1, 1, 1, 1),
                    (1, 81, 1, 1, 1, 1),
                    (1, 82, 1, 1, 1, 1),
                    (1, 83, 1, 1, 1, 1),
                    (1, 84, 1, 1, 1, 1),
                    (1, 85, 1, 1, 1, 1),
                    (1, 86, 1, 1, 1, 1),
                    (1, 87, 1, 1, 1, 1),
                    (1, 88, 1, 1, 1, 1),
                    (4, 88, 1, 1, 1, 1),
                    (1, 89, 1, 1, 1, 1),
                    (1, 90, 1, 1, 1, 1),
                    (4, 90, 1, 1, 1, 1),
                    (1, 91, 1, 1, 1, 1),
                    (4, 91, 1, 1, 1, 1),
                    (1, 92, 1, 1, 1, 1),
                    (1, 93, 1, 1, 1, 1),
                    (1, 94, 1, 1, 1, 1),
                    (1, 95, 1, 1, 1, 1),
                    (1, 96, 1, 1, 1, 1),
                    (5, 96, 1, 1, 1, 1),
                    (6, 96, 1, 1, 1, 1),
                    (1, 97, 1, 1, 1, 1),
                    (6, 97, 1, 1, 1, 1),
                    (1, 98, 1, 1, 1, 1),
                    (1, 99, 1, 1, 1, 1),
                    (1, 101, 1, 1, 1, 1),
                    (3, 101, 1, 1, 1, 1),
                    (1, 102, 1, 1, 1, 1),
                    (4, 102, 1, 1, 1, 1);";
        
        $sql[8] = "INSERT INTO ohrm_user_role_screen (user_role_id, screen_id, can_read, can_create, can_update, can_delete) VALUES
                    (42, 'job_titles', 'Admin - Job Titles', 1, 1, 1, 1),
                    (43, 'pay_grades', 'Admin - Pay Grades', 1, 1, 1, 1),
                    (44, 'time_customers', 'Time - Project Info - Customers', 1, 1, 1, 1),
                    (45, 'time_projects', 'Time - Project Info - Projects', 1, 1, 1, 1),
                    (46, 'pim_reports', 'PIM - Reports', 1, 1, 1, 1),
                    (47, 'attendance_configuration', 'Time - Attendance Configuration', 1, 0, 1, 0),
                    (48, 'attendance_manage_records', 'Time - Attendance Manage Records', 1, 0, 0, 0),
                    (49, 'time_project_reports', 'Time - Project Reports', 1, 0, 0, 0),
                    (50, 'time_employee_reports', 'Time - Employee Reports', 1, 0, 0, 0),
                    (51, 'attendance_summary', 'Time - Attendance Summary', 1, 0, 0, 0),
                    (52, 'leave_period', 'Leave - Leave Period', 1, 0, 1, 0),
                    (53, 'leave_types', 'Leave - Leave Types', 1, 1, 1, 1),
                    (54, 'work_week', 'Leave - Work Week', 1, 0, 1, 0),
                    (55, 'holidays', 'Leave - Holidays', 1, 1, 1, 1),
                    (56, 'recruitment_vacancies', 'Recruitment - Vacancies', 1, 1, 1, 1),
                    (57, 'recruitment_candidates', 'Recruitment - Candidates', 1, 1, 1, 1),
                    (58, 'time_manage_employees', 'Time - Employee Timesheets', 1, 0, 0, 0),
                    (59, 'leave_list', 'Leave - Leave List', 1, 0, 0, 0);";
        
        $sql[9] = "INSERT INTO `ohrm_data_group` (`id`, `name`, `description`, `can_read`, `can_create`, `can_update`, `can_delete`) VALUES
                    (42, 'job_titles', 'Admin - Job Titles', 1, 1, 1, 1),
                    (43, 'pay_grades', 'Admin - Pay Grades', 1, 1, 1, 1),
                    (44, 'time_customers', 'Time - Project Info - Customers', 1, 1, 1, 1),
                    (45, 'time_projects', 'Time - Project Info - Projects', 1, 1, 1, 1),
                    (46, 'pim_reports', 'PIM - Reports', 1, 1, 1, 1),
                    (47, 'attendance_configuration', 'Time - Attendance Configuration', 1, 0, 1, 0),
                    (48, 'attendance_manage_records', 'Time - Attendance Manage Records', 1, 0, 0, 0),
                    (49, 'time_project_reports', 'Time - Project Reports', 1, 0, 0, 0),
                    (50, 'time_employee_reports', 'Time - Employee Reports', 1, 0, 0, 0),
                    (51, 'attendance_summary', 'Time - Attendance Summary', 1, 0, 0, 0),
                    (52, 'leave_period', 'Leave - Leave Period', 1, 0, 1, 0),
                    (53, 'leave_types', 'Leave - Leave Types', 1, 1, 1, 1),
                    (54, 'work_week', 'Leave - Work Week', 1, 0, 1, 0),
                    (55, 'holidays', 'Leave - Holidays', 1, 1, 1, 1),
                    (56, 'recruitment_vacancies', 'Recruitment - Vacancies', 1, 1, 1, 1),
                    (57, 'recruitment_candidates', 'Recruitment - Candidates', 1, 1, 1, 1),
                    (58, 'time_manage_employees', 'Time - Employee Timesheets', 1, 0, 0, 0),
                    (59, 'leave_list', 'Leave - Leave List', 1, 0, 0, 0);";
        
        $sql[10] = "INSERT INTO `ohrm_user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`, `self`) VALUES
                    (1, 42, 1, 1, 1, 1, 0),
                    (2, 42, 0, 0, 0, 0, 0),
                    (3, 42, 0, 0, 0, 0, 0),

                    (1, 43, 1, 1, 1, 1, 0),
                    (2, 43, 0, 0, 0, 0, 0),
                    (3, 43, 0, 0, 0, 0, 0),

                    (1, 44, 1, 1, 1, 1, 0),
                    (2, 44, 0, 0, 0, 0, 0),
                    (3, 44, 0, 0, 0, 0, 0),

                    (1, 45, 1, 1, 1, 1, 0),
                    (2, 45, 0, 0, 0, 0, 0),
                    (3, 45, 0, 0, 0, 0, 0),
                    (4, 45, 1, 0, 1, 0, 0),

                    (1, 46, 1, 1, 1, 1, 0),
                    (2, 46, 0, 0, 0, 0, 0),
                    (3, 46, 0, 0, 0, 0, 0),

                    (1, 47, 1, NULL, 1, NULL, 0),
                    (2, 47, 0, 0, 0, 0, 0),
                    (3, 47, 0, 0, 0, 0, 0),

                    (1, 48, 1, 0, 0, 0, 0),
                    (2, 48, 0, 0, 0, 0, 0),
                    (2, 48, 1, 0, 0, 0, 1),
                    (3, 48, 1, 0, 0, 0, 0),

                    (1, 49, 1, 0, 0, 0, 0),
                    (2, 49, 0, 0, 0, 0, 0),
                    (3, 49, 0, 0, 0, 0, 0),
                    (4, 49, 1, 0, 0, 0, 0),

                    (1, 50, 1, 0, 0, 0, 0),
                    (2, 50, 0, 0, 0, 0, 0),
                    (3, 50, 1, 0, 0, 0, 0),

                    (1, 51, 1, 0, 0, 0, 0),
                    (2, 51, 0, 0, 0, 0, 0),
                    (3, 51, 1, 0, 0, 0, 0),

                    (1, 52, 1, NULL, 1, NULL, 0),
                    (2, 52, 0, 0, 0, 0, 0),
                    (3, 52, 0, 0, 0, 0, 0),

                    (1, 53, 1, 1, 1, 1, 0),
                    (2, 53, 0, 0, 0, 0, 0),
                    (3, 53, 0, 0, 0, 0, 0),

                    (1, 54, 1, 0, 1, 0, 0),
                    (2, 54, 0, 0, 0, 0, 0),
                    (3, 54, 0, 0, 0, 0, 0),

                    (1, 55, 1, 1, 1, 1, 0),
                    (2, 55, 0, 0, 0, 0, 0),
                    (3, 55, 0, 0, 0, 0, 0),

                    (1, 56, 1, 1, 1, 1, 0),
                    (2, 56, 0, 0, 0, 0, 0),
                    (3, 56, 0, 0, 0, 0, 0),

                    (1, 57, 1, 1, 1, 1, 0),
                    (6, 57, 1, 1, 1, 1, 0),
                    (5, 57, 1, 0, 1, 0, 0),

                    (1, 58, 1, 0, 0, 0, 0),
                    (2, 58, 0, 0, 0, 0, 0),
                    (2, 58, 1, 0, 0, 0, 1),
                    (3, 58, 1, 0, 0, 0, 0),

                    (1, 59, 1, 0, 0, 0, 0),
                    (2, 59, 1, 0, 0, 0, 1),
                    (3, 59, 1, 0, 0, 0, 0);";
        
        $sql[11] = "INSERT INTO `ohrm_data_group_screen`(`data_group_id`, `screen_id`, `permission`) VALUES
                    (40, 69, 1),
                    (40, 72, 2),
                    (40, 72, 3),
                    (40, 71, 4),

                    (41, 78, 4),

                    (42, 23, 1),
                    (42, 80, 2),
                    (42, 80, 3),
                    (42, 81, 4),

                    (43, 24, 1),
                    (43, 82, 2),
                    (43, 82, 3),
                    (43, 83, 4),
                    (43, 84, 3),
                    (43, 85, 3),

                    (42, 74, 1),
                    (43, 74, 1),

                    (44, 36, 1),
                    (44, 86, 2),
                    (44, 86, 3),
                    (44, 87, 4),

                    (45, 37, 1),
                    (45, 88, 1),
                    (45, 88, 2),
                    (45, 88, 3),
                    (45, 89, 4),
                    (45, 90, 3),
                    (45, 91, 3),

                    (46, 45, 1),
                    (46, 45, 4),
                    (46, 92, 2),
                    (46, 92, 3),
                    (46, 93, 1),

                    (47, 56, 1),
                    (47, 56, 3),

                    (48, 55, 1),

                    (49, 57, 1),
                    (49, 102, 1),

                    (50, 58, 1),

                    (51, 59, 1),
                    (51, 101, 1),

                    (52, 47, 1),
                    (52, 47, 3),

                    (53, 7, 1),
                    (53, 8, 1),
                    (53, 8, 2),
                    (53, 8, 3),
                    (53, 9, 2),
                    (53, 10, 4),

                    (54, 14, 1),
                    (54, 14, 3),

                    (55, 11, 1),
                    (55, 12, 2),
                    (55, 12, 3),
                    (55, 13, 4),

                    (56, 61, 1),
                    (56, 94, 1),
                    (56, 94, 2),
                    (56, 94, 3),
                    (56, 95, 4),

                    (57, 60, 1),
                    (57, 96, 1),
                    (57, 96, 2),
                    (57, 96, 3),
                    (57, 97, 4),

                    (56, 76, 1),
                    (57, 76, 1),

                    (58, 52, 1),

                    (59, 16, 1),
                    (59, 98, 1),
                    (59, 99, 1);";
        
        $sql[12] = "UPDATE ohrm_module_default_page SET action='time/timesheetPeriodNotDefined'
                    WHERE module_id=5 AND user_role_id=2;";
                
        $this->sql = $sql;
    }
    
    public function getNotes() {        
    }
}


