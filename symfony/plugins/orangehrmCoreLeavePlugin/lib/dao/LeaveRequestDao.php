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

class LeaveRequestDao extends BaseDao {

    private static $doneMarkingApprovedLeaveAsTaken = false;

    /**
     *
     * @param LeaveRequest $leaveRequest
     * @return boolean
     */
    public function saveLeaveRequest(LeaveRequest $leaveRequest, $leaveList) {
        try {
            if ($leaveRequest->getLeaveRequestId() == '') {

                $idGenService = new IDGeneratorService();
                $idGenService->setEntity($leaveRequest);
                $leaveRequest->setLeaveRequestId($idGenService->getNextID());
            }

            $leaveRequest->save();

            foreach ($leaveList as $leave) {
                if ($leave->getLeaveId() == '') {

                    $idGenService = new IDGeneratorService();
                    $idGenService->setEntity($leave);
                    $leave->setLeaveId($idGenService->getNextID());
                }
                $leave->setLeaveRequestId($leaveRequest->getLeaveRequestId());
                $leave->setLeaveTypeId($leaveRequest->getLeaveTypeId());
                $leave->setEmployeeId($leaveRequest->getEmpNumber());

                $leave->save();
            }

            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function saveLeave(Leave $leave) {
        try {
            if ($leave->getLeaveId() == '') {
                $idGenService = new IDGeneratorService();
                $idGenService->setEntity($leave);
                $leave->setLeaveId($idGenService->getNextID());
            }
            $leave->save();
            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Modify Overlap leave request
     * @param LeaveRequest $leaveRequest
     * @return boolean
     */
    public function modifyOverlapLeaveRequest(LeaveRequest $leaveRequest, $leaveList, $leavePeriod = null) {
        try {
            $nextLeavePeriod = false;
            $nextLeaveRequest = false;
            if ($leavePeriod == null)
                $leavePeriod = Doctrine :: getTable('LeavePeriod')->find($leaveRequest->getLeavePeriodId());

            foreach ($leaveList as $leave) {

                if ($leave->getLeaveDate() > $leavePeriod->getEndDate()) {
                    if (!($nextLeavePeriod instanceof LeavePeriod)) {

                        $leavePeriodService = new LeavePeriodService();
                        $leavePeriodService->setLeavePeriodDao(new LeavePeriodDao());

                        $nextLeavePeriod = $leavePeriodService->createNextLeavePeriod($leave->getLeaveDate());

                        $nextLeaveRequest = new LeaveRequest();
                        $idGenService = new IDGeneratorService();
                        $idGenService->setEntity($leaveRequest);
                        $nextLeaveRequest->setLeaveRequestId($idGenService->getNextID());

                        $nextLeaveRequest->setLeaveTypeId($leaveRequest->getLeaveTypeId());
                        $nextLeaveRequest->setDateApplied($leaveRequest->getDateApplied());
                        $nextLeaveRequest->setLeavePeriodId($nextLeavePeriod->getLeavePeriodId());
                        $nextLeaveRequest->setLeaveTypeName($leaveRequest->getLeaveTypeName());
                        $nextLeaveRequest->setEmpNumber($leaveRequest->getEmpNumber());
                        $nextLeaveRequest->setLeaveComments($leaveRequest->getLeaveComments());

                        $nextLeaveRequest->save();
                    }

                    $q = Doctrine_Query::create()
                            ->update('Leave l')
                            ->set('l.leave_request_id=', $nextLeaveRequest->getLeaveRequestId())
                            ->where('l.leave_id = ?', $leave->getLeaveId());


                    $q->execute();
                }
            }
            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param $leaveStartDate
     * @param $leaveEndDate
     * @return Leave
     */
    public function getOverlappingLeave($leaveStartDate, $leaveEndDate, $empId, $startTime = '00:00:00', $endTime='23:59:00', $hoursPerday = null) {

        try {
            $q = Doctrine_Query::create()
                    ->from('Leave l');

            $q->andWhere('l.employee_id =' . $empId);
            $q->andWhereNotIn('l.leave_status', array(Leave::LEAVE_STATUS_LEAVE_CANCELLED, Leave::LEAVE_STATUS_LEAVE_REJECTED, Leave::LEAVE_STATUS_LEAVE_WEEKEND, Leave::LEAVE_STATUS_LEAVE_HOLIDAY));

            if ($leaveStartDate == $leaveEndDate) {

                $or [] = "('" . $leaveStartDate . " " . $startTime . "'<= CONCAT(leave_date,' ',start_time) AND CONCAT(leave_date,' ',end_time) <='" . $leaveEndDate . " " . $endTime . "')";
                $or [] = "(CONCAT(leave_date,' ',start_time) <='" . $leaveStartDate . " " . $startTime . "' AND '" . $leaveEndDate . " " . $endTime . "' <= CONCAT(leave_date,' ',end_time))";
                $or [] = "('" . $leaveStartDate . " " . $startTime . "'< CONCAT(leave_date,' ',start_time) AND CONCAT(leave_date,' ',start_time) <'" . $leaveEndDate . " " . $endTime . "')";
                $or [] = "('" . $leaveStartDate . " " . $startTime . "'< CONCAT(leave_date,' ',end_time) AND CONCAT(leave_date,' ',end_time) <'" . $leaveEndDate . " " . $endTime . "')";
                $or [] = "('" . $leaveStartDate . " " . $startTime . "'= CONCAT(leave_date,' ',end_time) AND CONCAT(leave_date,' ',end_time) ='" . $leaveEndDate . " " . $endTime . "')";
                $or [] = "((leave_date ='" . $leaveEndDate . "') AND ((start_time = '00:00:00' AND end_time='00:00:00')))";

                $orString = implode(" OR ", $or);
                $orString = "(" . $orString . ")";
            } else {

                $or [] = "('" . $leaveStartDate . "'<= leave_date AND leave_date <='" . $leaveEndDate . "')";
                $or [] = "( leave_date <='" . $leaveStartDate . "' AND '" . $leaveEndDate . "'<= leave_date )";
                $or [] = "('" . $leaveStartDate . "'< leave_date AND leave_date <'" . $leaveEndDate . "')";
                $or [] = "('" . $leaveStartDate . "'< leave_date AND leave_date <'" . $leaveEndDate . "')";
                $or [] = "('" . $leaveStartDate . "'= leave_date OR leave_date ='" . $leaveEndDate . "')";

                $orString = implode(" OR ", $or);
                $orString = "(" . $orString . ")";
            }


            $q->andWhere($orString);
            $leaveListArray = $q->execute();
            return $leaveListArray;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param type $employeeId
     * @param type $date
     * @return type 
     */
    public function getTotalLeaveDuration($employeeId, $date) {
        $this->_markApprovedLeaveAsTaken();

        $leaveStatusNotConsider = array(Leave::LEAVE_STATUS_LEAVE_CANCELLED, Leave::LEAVE_STATUS_LEAVE_REJECTED, Leave::LEAVE_STATUS_LEAVE_WEEKEND, Leave::LEAVE_STATUS_LEAVE_HOLIDAY);

        $q = Doctrine_Query::create()
                ->select('SUM(leave_length_hours) as total_duration')
                ->from('Leave')
                ->where("employee_id =?", $employeeId)
                ->andWhereNotIn("leave_status ", $leaveStatusNotConsider)
                ->andWhere("leave_date =?", $date);

        $diration = $q->fetchOne();

        return $diration->getTotalDuration();
    }

    /**
     * Count leave records in the Leave table
     * @return integer $count
     */
    public function getLeaveRecordCount() {
        try {

            $q = Doctrine_Query::create()
                    ->from('Leave');
            $count = $q->count();
            return $count;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param $empId
     * @param $leaveTypeId
     * @return int
     */
    public function getNumOfLeave($empId, $leaveTypeId) {
        try {


            $q = Doctrine_Query::create()
                    ->addSelect('sum(leave_length_days) as daysLength')
                    ->from('Leave l')
                    ->andWhere("l.employee_id = ?", $empId)
                    ->andWhere("l.leave_type_id = ?", $leaveTypeId);


            $record = $q->fetchOne();

            return $record['daysLength'];
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param $empId
     * @param $leaveTypeId
     * @return int
     */
    public function getNumOfAvaliableLeave($empId, $leaveTypeId) {
        try {


            $q = Doctrine_Query::create()
                    ->addSelect('sum(leave_length_days) as daysLength')
                    ->from('Leave l')
                    ->andWhere("l.employee_id = ?", $empId)
                    ->andWhere("l.leave_type_id = ?", $leaveTypeId)
                    ->andWhereNotIn('l.leave_status', array(Leave::LEAVE_STATUS_LEAVE_CANCELLED, Leave::LEAVE_STATUS_LEAVE_PENDING_APPROVAL, Leave::LEAVE_STATUS_LEAVE_REJECTED));


            $record = $q->fetchOne();

            return $record['daysLength'];
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * 
     * @param LeavePeriod $leavePeriod
     * @return unknown_type
     */
    public function getLeavePeriodOverlapLeaves(LeavePeriod $leavePeriod) {
        try {
            $q = Doctrine_Query::create()
                    ->from('Leave l')
                    ->andWhere('l.leave_date > ?', $leavePeriod->getEndDate())
                    ->groupBy('l.leave_request_id');

            $leaveList = $q->execute();
            return $leaveList;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param DateRange $dateRange
     * @param array $status
     * @param int $page
     * @return array
     */
    public function searchLeaveRequests($searchParameters, $page = 1, $isCSVPDFExport = false, $isMyLeaveList = false) {
        $this->_markApprovedLeaveAsTaken();

        $limit = !is_null($searchParameters->getParameter('noOfRecordsPerPage')) ? $searchParameters->getParameter('noOfRecordsPerPage') : sfConfig::get('app_items_per_page');
        $offset = ($page > 0) ? (($page - 1) * $limit) : 0;

        $list = array();

        $q = Doctrine_Query::create()
                ->select('lr.*')
                ->from('LeaveRequest lr')
                ->leftJoin('lr.Leave l');

        $dateRange = $searchParameters->getParameter('dateRange');
        $statuses = $searchParameters->getParameter('statuses');
        $employeeFilter = $searchParameters->getParameter('employeeFilter');
        $leavePeriod = $searchParameters->getParameter('leavePeriod');
        $leaveType = $searchParameters->getParameter('leaveType');
        $withTerminatedEmployee = $searchParameters->getParameter('cmbWithTerminated');

        $fromDate = $dateRange->getFromDate();
        $toDate = $dateRange->getToDate();

        if ((!empty($fromDate) && !empty($toDate)) || !empty($statuses)) {

            if (!empty($fromDate) && !empty($toDate)) {
                $q->andWhere("l.leave_date >= '{$fromDate}'");
                $q->andWhere("l.leave_date <= '{$toDate}'");
            }

            if (!empty($statuses)) {
                $q->whereIn("l.leave_status", $statuses);
            }
        }

        if (!empty($employeeFilter)) {
            if (is_numeric($employeeFilter) && $employeeFilter > 0) {
                $q->andWhere('lr.empNumber = ?', (int) $employeeFilter);
            } elseif ($employeeFilter instanceof Employee) {
                $q->andWhere('lr.empNumber = ?', $employeeFilter->getEmpNumber());
            } elseif (is_array($employeeFilter)) {
                $empNumbers = array();
                foreach ($employeeFilter as $employee) {
                    $empNumbers[] = ($employee instanceof Employee) ? $employee->getEmpNumber() : $employee;
                }
                $q->whereIn('lr.empNumber', $empNumbers);
            }
        } else {
            if (is_array($employeeFilter)) {
                $q->andWhere('lr.empNumber = ?', -1);
            }
        }

        if (trim($fromDate) == "" && trim($toDate) == "" && !empty($leavePeriod)) {
            $leavePeriodId = ($leavePeriod instanceof LeavePeriod) ? $leavePeriod->getLeavePeriodId() : $leavePeriod;
            $q->andWhere('lr.leave_period_id = ?', (int) $leavePeriodId);
        }

        if (!empty($leaveType)) {
            $leaveTypeId = ($leaveType instanceof LeaveType) ? $leaveType->getLeaveTypeId() : $leaveType;
            $q->andWhere('lr.leave_type_id = ?', $leaveTypeId);
        }
        if (!$isMyLeaveList) {
            if (empty($withTerminatedEmployee)) {
                $q->leftJoin('lr.Employee em')
                        ->andWhere("em.termination_id IS NULL");
            }
        }
        $q->orderBy('l.leave_date DESC');

        $count = $q->count();

        if ($isCSVPDFExport) {
            $limit = $count;
            $offset = 0;
        }
        $q->offset($offset);
        $q->limit($limit);

        $list = $q->execute();

        return $isCSVPDFExport ? $list : array('list' => $list, 'meta' => array('record_count' => $count));
    }

    /**
     *
     * @param int $leaveRequestId
     * @return array
     */
    public function fetchLeave($leaveRequestId) {

        $q = Doctrine_Query::create()
                ->select('*')
                ->from('Leave l')
                ->where('leave_request_id = ?', $leaveRequestId);

        return $q->execute();
    }

    /**
     *
     * @param int $leaveId
     * @return array
     */
    public function readLeave($leaveId) {

        $q = Doctrine_Query::create()
                ->select('*')
                ->from('Leave l')
                ->where('leave_id = ?', $leaveId);

        return $q->fetchOne();
    }

    public function fetchLeaveRequest($leaveRequestId) {
        $this->_markApprovedLeaveAsTaken();

        $q = Doctrine_Query::create()
                ->select('*')
                ->from('LeaveRequest lr')
                ->where('leave_request_id = ?', $leaveRequestId);

        return $q->fetchOne();
    }

    public function getLeaveById($leaveId) {
        $this->_markApprovedLeaveAsTaken();

        $q = Doctrine_Query::create()
                ->select('*')
                ->from('Leave l')
                ->where('leave_id = ?', $leaveId);

        return $q->fetchOne();
    }

    public function getScheduledLeavesSum($employeeId, $leaveTypeId, $leavePeriodId) {
        $this->_markApprovedLeaveAsTaken();

        try {

            $q = Doctrine_Query::create()
                    ->select('SUM(lea.leave_length_days) as scheduledSum')
                    ->from('Leave lea')
                    ->leftJoin('lea.LeaveRequest lr')
                    ->where("lea.employee_id = '$employeeId'")
                    ->andWhere("lea.leave_type_id = '$leaveTypeId'")
                    ->andWhere("lea.leave_status = " . Leave::LEAVE_STATUS_LEAVE_APPROVED)
                    ->andWhere("lr.leave_period_id = $leavePeriodId")
            ;

            $record = $q->fetchOne();

            return $record['scheduledSum'];
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function getTakenLeaveSum($employeeId, $leaveTypeId, $leavePeriodId) {

        $this->_markApprovedLeaveAsTaken();

        $q = Doctrine_Query::create()
                ->select('SUM(lea.leave_length_days) as scheduledSum')
                ->from('Leave lea')
                ->leftJoin('lea.LeaveRequest lr')
                ->where("lea.employee_id = $employeeId")
                ->andWhere("lea.leave_type_id = '$leaveTypeId'")
                ->andWhere("lea.leave_status = " . Leave::LEAVE_STATUS_LEAVE_TAKEN)
                ->andWhere("lr.leave_period_id = $leavePeriodId")
        ;

        $record = $q->fetchOne();

        return $record['scheduledSum'];
    }

    private function _markApprovedLeaveAsTaken() {
        if (self::$doneMarkingApprovedLeaveAsTaken) {
            return;
        } else {

            $date = date('Y-m-d');
            $time = date('H:i:s');

            $q = Doctrine_Query::create()
                    ->update('Leave l')
                    ->set('l.leave_status', Leave::LEAVE_STATUS_LEAVE_TAKEN)
                    ->where('l.leave_status = ?', Leave::LEAVE_STATUS_LEAVE_APPROVED)
                    ->andWhere('l.leave_date <= ?', $date)
                    ->andWhere('l.end_time <= ?', $time);

            $q->execute();

            self::$doneMarkingApprovedLeaveAsTaken = true;
        }
    }

}
