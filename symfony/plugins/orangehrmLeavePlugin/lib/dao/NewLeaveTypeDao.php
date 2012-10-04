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
 * Description of NewLeaveTypeDao
 */
class NewLeaveTypeDao extends BaseDao {
    
    public function getLeaveTypeList($operationalCountryId = null) {
        try {
            $q = Doctrine_Query::create()
                            ->from('LeaveType lt')
                            ->where('lt.deleted = 0')
                            ->orderBy('lt.name');
            
            if (!is_null($operationalCountryId)) {
                if (is_array($operationalCountryId)) {
                    $q->andWhereIn('lt.operational_country_id', $operationalCountryId);
                } else {
                    $q->andWhere('lt.operational_country_id = ? ', $operationalCountryId);
                }
            }
            $leaveTypeList = $q->execute();

            return $leaveTypeList;
        } catch (Exception $e) {
            $this->getLogger()->error("Exception in getLeaveTypeList:" . $e);
            throw new DaoException($e->getMessage(), 0, $e);
        }        
    }
    
    /**
     * Get Logger instance. Creates if not already created.
     *
     * @return Logger
     */
    protected function getLogger() {
        if (is_null($this->logger)) {
            $this->logger = Logger::getLogger('leave.LeaveTypeDao');
        }

        return($this->logger);
    }    
}
