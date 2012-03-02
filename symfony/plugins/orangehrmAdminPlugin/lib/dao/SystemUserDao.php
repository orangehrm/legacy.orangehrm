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
class SystemUserDao extends BaseDao {

    /**
     * Save System User
     * 
     * @param SystemUser $systemUser 
     * @return void
     */
    public function saveSystemUser(SystemUser $systemUser) {
        try {
            $systemUser->clearRelated('Employee');
            $systemUser->save();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Check is existing user according to user name
     * 
     * @param type $userName 
     * @return mixed , false if user not exist  , otherwise it returns SystemUser object
     */
    public function isExistingSystemUser($userName, $userId = null) {
        try {
            $query = Doctrine_Query:: create()->from('SystemUser u')
                    ->andWhere('u.user_name = ?', $userName);
            if (!empty($userId)) {
                $query->andWhere('u.id != ?', $userId);
            }
            //print($query->getSqlQuery());
            return $query->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get System User for given User Id
     * 
     * @param type $userId
     * @return SystemUser  
     */
    public function getSystemUser($userId) {
        try {
            return Doctrine :: getTable('SystemUser')->find($userId);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get System Users
     * 
     * @return Doctrine_Collection 
     */
    public function getSystemUsers() {
        try {
            $query = Doctrine_Query:: create()->from('SystemUser u')
                    ->where('u.deleted=?', 0);

            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Delete System Users
     * @param array $deletedIds 
     * 
     */
    public function deleteSystemUsers(array $deletedIds) {
        try {
            $query = Doctrine_Query :: create()
                    ->update('SystemUser u')
                    ->set('u.deleted', 1)
                    ->whereIn('u.id', $deletedIds);
            $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get System Users
     * 
     * @return Doctrine_Collection 
     */
    public function getPreDefinedUserRole() {
        try {
            $query = Doctrine_Query:: create()->from('UserRole ur')
                    ->whereIn('ur.is_predefined', 1);

            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get Count of Search Query 
     * 
     * @param type $searchClues
     * @return type 
     */
    public function getSearchSystemUsersCount($searchClues) {
        try {
            $q = $this->_buildSearchQuery($searchClues);
            return $q->count();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Search System Users 
     * 
     * @param type $searchClues
     * @return type 
     */
    public function searchSystemUsers($searchClues) {
        try {
            $sortField = ($searchClues['sortField'] == "") ? 'user_name' : $searchClues['sortField'];
            $sortOrder = ($searchClues['sortOrder'] == "") ? 'ASC' : $searchClues['sortOrder'];
            $offset = ($searchClues['offset'] == "") ? 0 : $searchClues['offset'];
            $limit = ($searchClues['limit'] == "") ? SystemUser::NO_OF_RECORDS_PER_PAGE : $searchClues['limit'];

            $q = $this->_buildSearchQuery($searchClues);

            $q->orderBy($sortField . ' ' . $sortOrder)
                    ->offset($offset)
                    ->limit($limit);

            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     *
     * @param type $searchClues
     * @return Doctrine Query 
     */
    private function _buildSearchQuery($searchClues) {

        $query = Doctrine_Query:: create()->from('SystemUser u');

        if (!empty($searchClues['userName'])) {
            $query->addWhere('u.user_name = ?', $searchClues['userName']);
        }
        if (!empty($searchClues['userType'])) {
            $query->addWhere('u.user_role_id = ?', $searchClues['userType']);
        }
        if (!empty($searchClues['employeeId'])) {
            $query->addWhere('u.emp_number = ?', $searchClues['employeeId']);
        }
        if ($searchClues['status'] != '') {
            $query->addWhere('u.status = ?', $searchClues['status']);
        }

        $query->addWhere('u.deleted=?', 0);

        return $query;
    }
    
    public function getAdminUserCount($enabledOnly=true, $undeletedOnly=true) {
        
        $q = Doctrine_Query::create()->from('SystemUser')
                                     ->where('user_role_id = ?', SystemUser::ADMIN_USER_ROLE_ID);
        
        if ($enabledOnly) {
            $q->addWhere('status = ?', SystemUser::ENABLED);
        }
        
        if ($undeletedOnly) {
            $q->addWhere('deleted = ?', SystemUser::UNDELETED);
        }
        
        return $q->count();
        
    }
    
    public function updatePassword($userId, $password) {
        
        try {
            
            $q = Doctrine_Query::create()
                               ->update('SystemUser')
                               ->set('user_password', '?', $password)
                               ->where('id = ?', $userId);
            
            return $q->execute();
            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }
    
}