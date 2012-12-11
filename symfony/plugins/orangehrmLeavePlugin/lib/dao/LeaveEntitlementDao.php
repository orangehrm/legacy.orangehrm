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
 * Leave Entitlement Dao class
 */
class LeaveEntitlementDao extends BaseDao {
    
    public function searchLeaveEntitlements(LeaveEntitlementSearchParameterHolder $searchParameters) {
        try {

            $q = Doctrine_Query::create()->from('LeaveEntitlement le');

            $deletedFlag    = $searchParameters->getDeletedFlag();
            $leaveTypeId    = $searchParameters->getLeaveTypeId();
            $empNumber      = $searchParameters->getEmpNumber();
            $fromDate       = $searchParameters->getFromDate();            
            $toDate         = $searchParameters->getToDate();
            $idList         = $searchParameters->getIdList();
            $orderField = $searchParameters->getOrderField();
            $order = $searchParameters->getOrderBy();
            
            $params = array();
            
            if ($deletedFlag === true) { 
                $q->addWhere('le.deleted = 1');
            } else if ($deletedFlag === false) {
                $q->addWhere('le.deleted = 0');
            }
            
            if (!is_null($leaveTypeId)) {
                $q->addWhere('le.leave_type_id = :leaveTypeId');
                $params[':leaveTypeId'] = $leaveTypeId;
            }            

            if (!is_null($empNumber)) {
                $q->addWhere('le.emp_number = :empNumber');
                $params[':empNumber'] = $empNumber;
            }
            
            if (!empty($fromDate) && !empty($toDate)) {
                $q->addWhere('(le.from_date BETWEEN :fromDate AND :toDate)');
                
                $params[':fromDate'] = $fromDate;
                $params[':toDate'] = $toDate;
            }
            if(!empty( $idList )){
                $q->andWhereIn('le.id',$idList);
                
            }

            // We need leave type name
            $q->leftJoin('le.LeaveType l');
            
            $orderClause = '';
            
            switch ($orderField) {
                case 'leave_type' : 
                    $orderClause = 'l.name ' . $order;
                    break;
                case 'employee_name':
                    $q->leftJoin('le.Employee e');
                    $orderClause = 'e.emp_lastname ' . $order . ', e.emp_firstname ' . $order;
                    break;
                default:
                    $orderClause = $orderField . ' ' . $order;
                    $orderClause = trim($orderClause);
                    break;
            }
            
            // get predictable sorting
            if (!empty($orderClause)) {
                $orderClause .= ', le.id ASC';
            }
            
            $q->addOrderBy($orderClause);

            $results = $q->execute($params);
            return $results;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), 0, $e);
        }
    }
    
    public function getLeaveEntitlement($id) {
        try {
            $leaveEntitlement = Doctrine::getTable('LeaveEntitlement')->find($id);
            return ($leaveEntitlement === false) ? null : $leaveEntitlement;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), 0, $e);
        }        
    }
    
    public function saveLeaveEntitlement(LeaveEntitlement $leaveEntitlement) {
        $conn = Doctrine_Manager::connection();
        $conn->beginTransaction();  
        
        try {
            $leaveEntitlement->save();            
            $balance = $leaveEntitlement->getNoOfDays() - $leaveEntitlement->getDaysUsed();
            $entitlementId = $leaveEntitlement->getId();
            
            if ($balance > 0) {
                $leaveList = $this->getLeaveWithoutEntitlements($leaveEntitlement->getEmpNumber(), 
                        $leaveEntitlement->getLeaveTypeId(), $leaveEntitlement->getFromDate(), $leaveEntitlement->getToDate());
                
                foreach ($leaveList as $leave) {
                    $daysLeft = $leave['days_left'];
                    $leaveId = $leave['id'];
                    $daysToAssign = $daysLeft > $balance ? $balance : $daysLeft;
                    
                    $leaveEntitlement->setDaysUsed($leaveEntitlement->getDaysUsed() - $daysToAssign);
                    $balance -= $daysToAssign;
                    
                    // assign to leave
                    $entitlementAssignment = Doctrine_Query::create()
                            ->from('LeaveLeaveEntitlement l')
                            ->where('l.leave_id = ?', $leaveId)
                            ->andWhere('l.entitlement_id = ?', $entitlementId)
                            ->fetchOne(); 

                    if ($entitlementAssignment === false) {
                        $entitlementAssignment = new LeaveLeaveEntitlement();
                        $entitlementAssignment->setLeaveId($leaveId);
                        $entitlementAssignment->setEntitlementId($entitlementId);
                        $entitlementAssignment->setLengthDays($daysToAssign);                         
                    } else {
                        $entitlementAssignment->setLengthDays($entitlementAssignment->getLengthDays() + $daysToAssign); 
                    }
                    $entitlementAssignment->save();                    
                    
                    if ($balance <= 0) {
                        break;
                    }                                        
                }
            }
            
            $conn->commit();            
            return $leaveEntitlement;
        } catch (Exception $e) {
            $conn->rollback();
            throw new DaoException($e->getMessage(), 0, $e);
        }        
    }
    
    
    public function deleteLeaveEntitlements($ids) {
        try {
            $q = Doctrine_Query::create()
                    ->update('LeaveEntitlement le')
                    ->set('le.deleted', 1)
                    ->whereIn('le.id', $ids);

            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), 0, $e);
        }
    }
    
    
    
    public function bulkAssignLeaveEntitlements($employeeNumbers, LeaveEntitlement $leaveEntitlement) {
        $savedCount = 0;
        
        $leaveEntitlementSearchParameterHolder = new LeaveEntitlementSearchParameterHolder();
          
        foreach ($employeeNumbers as $empNumber) {
            
            
            $leaveEntitlementSearchParameterHolder->setEmpNumber($empNumber);
            $leaveEntitlementSearchParameterHolder->setFromDate($leaveEntitlement->getFromDate());
            $leaveEntitlementSearchParameterHolder->setLeaveTypeId($leaveEntitlement->getLeaveTypeId());
            $leaveEntitlementSearchParameterHolder->setToDate($leaveEntitlement->getToDate());
            
            $entitlementList = $this->searchLeaveEntitlements( $leaveEntitlementSearchParameterHolder );
            if(count($entitlementList) > 0){
                $entitlement  = $entitlementList->getFirst();
                $noOfDays  = $leaveEntitlement->getNoOfDays()+ $existingEntitlement->getNoOfDays();
               
            }else{
                $entitlement    = new LeaveEntitlement(); 
                $noOfDays       =   $leaveEntitlement->getNoOfDays();
            }
            
            $entitlement->setEmpNumber($empNumber);
            $entitlement->setLeaveTypeId($leaveEntitlement->getLeaveTypeId());
            
            $entitlement->setCreditedDate($leaveEntitlement->getCreditedDate());
            $entitlement->setCreatedById($leaveEntitlement->getCreatedById());
            $entitlement->setCreatedByName($leaveEntitlement->getCreatedByName());        
            
            $entitlement->setEntitlementType($leaveEntitlement->getEntitlementType());
            $entitlement->setDeleted(0);            
        
            $entitlement->setNoOfDays($noOfDays);
            $entitlement->setFromDate($leaveEntitlement->getFromDate());
            $entitlement->setToDate($leaveEntitlement->getToDate());            
            
            $this->saveLeaveEntitlement($entitlement);
            $savedCount++;
        }
        
        return $savedCount;
        
    }   
    
    public function getValidLeaveEntitlements($empNumber, $leaveTypeId, $fromDate, $toDate, $orderField, $order) {
        try {

            $params = array(
                ':leaveTypeId' => $leaveTypeId,
                ':empNumber' => $empNumber,
                ':fromDate' => $fromDate,
                ':toDate' => $toDate                
            );
            $q = Doctrine_Query::create()->from('LeaveEntitlement le')
                    ->addWhere('le.deleted = 0')
                    ->addWhere('le.leave_type_id = :leaveTypeId')
                    ->addWhere('le.emp_number = :empNumber')
                    ->addWhere('(le.no_of_days - le.days_used) > 0')
                    ->addWhere('(:fromDate BETWEEN le.from_date AND le.to_date) OR ' .
                               '(:toDate BETWEEN le.from_date AND le.to_date) OR ' .
                               '(le.from_date BETWEEN :fromDate AND :toDate)');

            $orderClause = $orderField . ' ' . $order;
            $orderClause = trim($orderClause);
            
            $q->addOrderBy($orderClause);

            $results = $q->execute($params);
            return $results;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), 0, $e);
        }        
    }
    
    public function getLinkedLeaveRequests($entitlementIds, $statuses) {
        try {
            $q = Doctrine_Query::create()->from('Leave l')
                    ->leftJoin('l.LeaveEntitlements le')
                    ->andWhereIn('le.id', $entitlementIds)
                    ->andWhereIn('l.status', $statuses)
                    ->addOrderBy('l.id ASC');

            $results = $q->execute();
            return $results;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), 0, $e);
        }  
    } 
    
    
    /**
     * Get leave balance as a LeaveBalance object with the following components
     *    * entitlements
     *    * used (taken)
     *    * scheduled
     *    * pending approval
     *    * leave without entitlements
     * 
     * @param int $empNumber Employee Number
     * @param int $leaveTypeId Leave Type ID
     * @param date $asAtDate Balance as at given date
     * @return LeaveBalance Returns leave balance object
     */
    public function getLeaveBalance($empNumber, $leaveTypeId, $asAtDate, $date = NULL) {
        $conn = Doctrine_Manager::connection()->getDbh(); 
        
        $sql = 'SELECT le.no_of_days AS entitled, ' . 
                      'le.days_used AS used, ' .
                      'sum(IF(l.status = 2, lle.length_days, 0)) AS scheduled, ' .
                      'sum(IF(l.status = 1, lle.length_days, 0)) AS pending ' . 
               'FROM ohrm_leave_entitlement le LEFT JOIN ' . 
                    'ohrm_leave_leave_entitlement lle ON le.id = lle.entitlement_id LEFT JOIN '.
                    'ohrm_leave l ON l.id = lle.leave_id ' .
               'WHERE le.deleted = 0 AND le.emp_number = ? AND le.leave_type_id = ? ' . 
               ' AND le.to_date >= ?';
        
        $parameters = array($empNumber, $leaveTypeId, $asAtDate); 
        
        if (!empty($date)) {
            $sql .= ' AND ? BETWEEN le.from_date AND le.to_date ';
            $parameters[] = $date;            
        }
        
        $sql .= 'GROUP BY le.id';
        
        $sql = 'SELECT sum(a.entitled) as entitled, sum(a.used) as used, sum(a.scheduled) as scheduled, sum(a.pending) as pending ' .
               ' FROM (' . $sql . ') as a';
        
        $statement = $conn->prepare($sql);
        $result = $statement->execute($parameters);
        $balance = new LeaveBalance();
        if ($result) {
            if ($statement->rowCount() > 0) {
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                if (!empty($result['entitled'])) {
                    $balance->setEntitled($result['entitled']);
                }
                if (!empty($result['used'])) {
                    $balance->setUsed($result['used']);
                }
                if (!empty($result['scheduled'])) {
                    $balance->setScheduled($result['scheduled']);
                }                
                if (!empty($result['pending'])) {
                    $balance->setPending($result['pending']);
                }
            }
        }        

        return $balance;    
    }
    
    public function getEntitlementUsageForLeave($leaveId) {
        try {
            $conn = Doctrine_Manager::connection()->getDbh();
            $query = "SELECT e.id, e.no_of_days, e.days_used, e.from_date, e.to_date, sum(lle.length_days) as length_days from ohrm_leave_entitlement e " . 
                     "left join ohrm_leave_leave_entitlement lle on lle.entitlement_id = e.id where " . 
                     "lle.leave_id = ? AND e.deleted = 0 group by e.id order by e.from_date ASC";
            $statement = $conn->prepare($query);
            $result = $statement->execute(array($leaveId));        

            return $statement->fetchAll();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), 0, $e);
        }
        
    }    
    
    public function getLeaveWithoutEntitlements($empNumber, $leaveTypeId, $fromDate, $toDate) {
        try {
            //print_r("$empNumber, $leaveTypeId, $fromDate, $toDate");
            $statusList = array(Leave::LEAVE_STATUS_LEAVE_REJECTED, Leave::LEAVE_STATUS_LEAVE_CANCELLED,
                Leave::LEAVE_STATUS_LEAVE_WEEKEND, Leave::LEAVE_STATUS_LEAVE_HOLIDAY);


            $conn = Doctrine_Manager::connection()->getDbh();
            $query = "select * from (select l.id, l.date, l.length_hours, l.length_days, l.status, l.leave_type_id, l.emp_number, " . 
                     "l.length_days - sum(COALESCE(lle.length_days, 0)) as days_left " . 
                     "from ohrm_leave l left join ohrm_leave_leave_entitlement lle on lle.leave_id = l.id " . 
                     "where l.emp_number = ? and l.leave_type_id = ? and l.date >= ? and l.date <= ? and " .
                     "l.status not in (" . implode(',', $statusList) . ") " .
                     "group by l.id order by l.`date` ASC) as A where days_left > 0";
            
    
    
            $statement = $conn->prepare($query);
            $result = $statement->execute(array($empNumber, $leaveTypeId, $fromDate, $toDate));        

            return $statement->fetchAll();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), 0, $e);
        }
        
    }    
    
    /**
     * Return any matching entitlements (with identical empNumber, leave type, from and to
     * dates. 
     * 
     * @param int $empNumber Employee Number
     * @param int $leaveTypeId Leave Type ID
     * @param Date $fromDate From Date
     * @param Date $toDate To Date
     * 
     * @return array Array of Entitlement objects. Empty array if no matches
     */
    public function getMatchingEntitlements($empNumber, $leaveTypeId, $fromDate, $toDate) {
        try {
            $params = array(':leaveTypeId' => $leaveTypeId,
                            ':empNumber' => $empNumber,
                            ':fromDate' => $fromDate,
                            ':toDate' => $toDate);
            
            $q = Doctrine_Query::create()->from('LeaveEntitlement le')
                    ->addWhere('le.deleted = 0')
                    ->addWhere('le.leave_type_id = :leaveTypeId')
                    ->addWhere('le.emp_number = :empNumber')
                    ->addWhere('le.from_date = :fromDate')
                    ->addWhere('le.to_date = :toDate');

            $results = $q->execute($params);
            return $results;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), 0, $e);
        }        
    }
}
