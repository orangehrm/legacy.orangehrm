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

            $deletedFlag = $searchParameters->getDeletedFlag();
            $leaveTypeId = $searchParameters->getLeaveTypeId();
            $empNumber = $searchParameters->getEmpNumber();
            $fromDate = $searchParameters->getFromDate();            
            $toDate = $searchParameters->getToDate();
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
            
            $orderClause = '';
            
            switch ($orderField) {
                case 'leave_type' : 
                    $q->leftJoin('le.LeaveType l');
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
        try {
            $leaveEntitlement->save();
            return $leaveEntitlement;
        } catch (Exception $e) {
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
        
        foreach ($employeeNumbers as $empNumber) {
            $entitlement = new LeaveEntitlement(); 
            
            $entitlement->setEmpNumber($empNumber);
            $entitlement->setLeaveTypeId($leaveEntitlement->getLeaveTypeId());
            
            $entitlement->setCreditedDate($leaveEntitlement->getCreditedDate());
            $entitlement->setCreatedById($leaveEntitlement->getCreatedById());
            $entitlement->setCreatedByName($leaveEntitlement->getCreatedByName());        
            
            $entitlement->setEntitlementType($leaveEntitlement->getEntitlementType());
            $entitlement->setDeleted(0);            
        
            $entitlement->setNoOfDays($leaveEntitlement->getNoOfDays());
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

            $results = $q->execute($params);
            return $results;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), 0, $e);
        }  
    } 
    
    
    public function getLeaveBalance($empNumber, $leaveTypeId, $asAtDate, $date = NULL) {
        $conn = Doctrine_Manager::connection()->getDbh(); 
        
        $sql = 'SELECT sum(le.no_of_days - le.days_used) FROM ohrm_leave_entitlement le ' . 
               'WHERE le.deleted = 0 AND le.emp_number = ? AND le.leave_type_id = ? ' . 
               ' AND le.to_date >= ?';
        
        $parameters = array($empNumber, $leaveTypeId, $asAtDate); 
        
        if (!empty($date)) {
            $sql .= ' AND ? BETWEEN le.from_date AND le.to_date';
            $parameters[] = $date;            
        }
        
        $statement = $conn->prepare($sql);
        $result = $statement->execute($parameters);
        $balance = 0;
        if ($result) {
            if ($statement->rowCount() > 0) {
                $balance = $statement->fetchColumn();
                
                if (is_null($balance)) {
                    $balance = 0;
                }
            }
        }        

        return $balance;    
    }
}
