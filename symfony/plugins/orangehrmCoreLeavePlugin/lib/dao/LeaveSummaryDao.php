<?php
/*
 *
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
class LeaveSummaryDao extends BaseDao {

    private static $doneSyncingLeaveEntitlements = false;

    public function __construct() {
    }

    /**
     * Get Leave Type list
     * @return LeaveType Collection
     */
    /*public function getLeaveTypeList(){
		try {
            	$q = Doctrine_Query::create()
			    ->from('LeaveType lt')
			    ->where('lt.availableFlag = 1')
			    ->orderBy('lt.leaveTypeId');
			    
			$leaveTypeList	=	$q->execute();
			
			return $leaveTypeList ;
		
        } catch( Exception $e) {
            throw new DaoException( $e->getMessage());
        }
	}*/

    public function fetchRawLeaveSummaryRecords($clues, $offset=0, $limit=20, $includeTerminated = false) {
        
        $q = "SELECT a.emp_number AS empNumber, a.emp_firstname AS empFirstName,
              a.emp_lastname AS empLastName, b.leave_type_id AS leaveTypeId,
              b.leave_type_name AS leaveTypeName, b.available_flag AS availableFlag, a.emp_status As empStatus FROM
              (hs_hr_employee a, hs_hr_leavetype b)";

        if (!empty($clues['cmbLocation'])) {
            $q .= " LEFT JOIN hs_hr_emp_locations c ON a.emp_number = c.emp_number";
        }
        
        $where = array();
        
        if (!empty($clues['cmbEmpId'])) {
            $where[] = "a.emp_number = '{$clues['cmbEmpId']}'";
        } elseif ($clues['userType'] == 'Supervisor') {
            $where[] = "a.emp_number IN(".implode(",", $clues['subordinates']).")";
        }

        if (!empty($clues['cmbLeaveType'])) {
            $where[] = "b.leave_type_id = '{$clues['cmbLeaveType']}'";
        }

        if (!empty($clues['cmbSubDivision'])) {
            $where[] = "a.work_station = '{$clues['cmbSubDivision']}'";
        }

        if (!empty($clues['cmbJobTitle'])) {
            $where[] = "a.job_title_code = '{$clues['cmbJobTitle']}'";
        }

        if (!empty($clues['cmbLocation'])) {
            $where[] = "c.loc_code = '{$clues['cmbLocation']}'";
        }
                
        if(!$includeTerminated && empty($clues['cmbWithTerminated'])) {
            $status = PluginEmployee::EMPLOYEE_STATUS_TERMINATED;
            $where[] = "(a.emp_status !='{$status}' OR a.emp_status IS NULL)";           
        }
        
        //$where[] = "b.available_flag = 1";
        if(count($where) > 0) {
            $q .= ' WHERE '.implode(' AND ',$where);
        }

        $q .= " ORDER By a.emp_number, b.leave_type_id";

        // Removed - To make it compatible with CSV/PDF export plugins
        //        $limitArray = array(20, 50, 100, 200);
        //
        //        if (!in_array($limit, $limitArray)) {
        //            $limit = 20;
        //        }

        $q .= " LIMIT $offset,$limit";

        $pdo = Doctrine_Manager::connection()->getDbh();
        $res = $pdo->query($q);
        
        return $res;

    }

    public function fetchRawLeaveSummaryRecordsCount($clues, $includeTerminated = false) {

        $q = "SELECT COUNT(*) FROM (hs_hr_employee a, hs_hr_leavetype b)";

        if (!empty($clues['cmbLocation'])) {
            $q .= " LEFT JOIN hs_hr_emp_locations c ON a.emp_number = c.emp_number";
        }
        
        $where = array();

        if (!empty($clues['cmbEmpId'])) {
            $where[] = "a.emp_number = '{$clues['cmbEmpId']}'";
        } elseif ($clues['userType'] == 'Supervisor') {
            $where[] = "a.emp_number IN(".implode(",", $clues['subordinates']).")";
        }

        if (!empty($clues['cmbLeaveType'])) {
            $where[] = "b.leave_type_id = '{$clues['cmbLeaveType']}'";
        }

        if (!empty($clues['cmbSubDivision'])) {
            $where[] = "a.work_station = '{$clues['cmbSubDivision']}'";
        }

        if (!empty($clues['cmbJobTitle'])) {
            $where[] = "a.job_title_code = '{$clues['cmbJobTitle']}'";
        }

        if (!empty($clues['cmbLocation'])) {
            $where[] = "c.loc_code = '{$clues['cmbLocation']}'";
        }
        
        if(!$includeTerminated && empty($clues['cmbWithTerminated'])) {
            $status = PluginEmployee::EMPLOYEE_STATUS_TERMINATED;
            $where[] = "(a.emp_status !='{$status}' OR a.emp_status IS NULL)";           
        }
        //$where[] = "b.available_flag = 1";
        if(count($where) > 0) {
            $q .= ' WHERE '.implode(' AND ',$where);
        }

        $pdo = Doctrine_Manager::connection()->getDbh();
        $res = $pdo->query($q);
        
        $row = $res->fetch();

        return $row[0];

    }
    
    /**
     * @tutorial this is a performance imporoved version of fetchRawLeaveSummaryRecords method
     * @param array $clues
     * @return array 
     */
    public function fetchRawLeaveSummaryRecordsImproved($clues) {
      
       $q = "SELECT a.emp_lastname, a.emp_middle_name, a.emp_firstname, b.leave_type_name, q.no_of_days_allotted, q.leave_brought_forward,
            (SELECT CONCAT (q.no_of_days_allotted + q.leave_brought_forward - SUM(l.leave_length_days) + q.leave_carried_forward ,'_',  sum(IF(l.leave_status = ".Leave::LEAVE_STATUS_LEAVE_APPROVED.", leave_length_days, 0)),'_',  sum(IF(l.leave_status = ".Leave::LEAVE_STATUS_LEAVE_TAKEN.", leave_length_days, 0)))
             FROM hs_hr_leave_requests lr 
             LEFT JOIN hs_hr_leave l ON lr.leave_request_id = l.leave_request_id 
             WHERE lr.leave_type_id = b.leave_type_id AND lr.employee_id = a.emp_number AND lr.leave_period_id = q.leave_period_id 
             AND leave_status IN (".Leave::LEAVE_STATUS_LEAVE_APPROVED.",".Leave::LEAVE_STATUS_LEAVE_TAKEN.")) AS leave_info 
       
             FROM
             (hs_hr_employee a, hs_hr_leavetype b) 
             LEFT JOIN hs_hr_employee_leave_quota q ON a.emp_number = q.employee_id AND b.leave_type_id = q.leave_type_id
";
        
        $q .= " ";        
           
        if (!empty($clues['cmbLocation'])) {
            $q .= " LEFT JOIN hs_hr_emp_locations c ON a.emp_number = c.emp_number";
        }
        
        $where = array();
        $where [] = " b.available_flag = 1 ";
        
        
        if (!empty($clues['cmbEmpId'])) {
            $where[] = "a.emp_number = '{$clues['cmbEmpId']}'";
        } elseif ($clues['userType'] == 'Supervisor') {
            $where[] = "a.emp_number IN(".implode(",", $clues['subordinates']).")";
        }

        if (!empty($clues['cmbLeaveType'])) {
            $where[] = "b.leave_type_id = '{$clues['cmbLeaveType']}'";
        }

        if (!empty($clues['cmbSubDivision'])) {
            $where[] = "a.work_station = '{$clues['cmbSubDivision']}'";
        }

        if (!empty($clues['cmbJobTitle'])) {
            $where[] = "a.job_title_code = '{$clues['cmbJobTitle']}'";
        }

        if (!empty($clues['cmbLocation'])) {
            $where[] = "c.loc_code = '{$clues['cmbLocation']}'";
        }
                
        if(!$includeTerminated && empty($clues['cmbWithTerminated'])) {
            $status = PluginEmployee::EMPLOYEE_STATUS_TERMINATED;
            $where[] = "(a.emp_status !='{$status}' OR a.emp_status IS NULL)";           
        }
       
        if(count($where) > 0) {
            $q .= ' WHERE '.implode(' AND ',$where);
        }
        
        $q .= " ORDER By a.emp_number, b.leave_type_id"; 
        
        $pdo = Doctrine_Manager::connection()->getDbh();      
        return $pdo->query($q)->fetchAll();         

    }


}