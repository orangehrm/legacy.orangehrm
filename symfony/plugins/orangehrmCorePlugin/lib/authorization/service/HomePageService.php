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

/**
 * Home Page Service
 */
class HomePageService {

    protected $userSession;
    protected $configService;
    protected $loginPath = 'auth/login';

    public function getConfigService() {
        
        if (!$this->configService instanceof ConfigService) {
            $this->configService = new ConfigService();
        }
        
        return $this->configService;
        
    }

    public function setConfigService($configService) {
        $this->configService = $configService;
    }    
    
    public function __construct(myUser $userSession) {
        $this->userSession = $userSession;
    }
    
    public function getHomePagePath() {
        
        if ($this->userSession->getAttribute('auth.isAdmin') == 'Yes') {
            return 'pim/viewEmployeeList';
        } else {
            return 'pim/viewMyDetails';
        }
        
    }
    
    public function getTimeModuleDefaultPath() {
        
        $isAdmin = ($this->userSession->getAttribute('auth.isAdmin') == 'Yes');
        
        if ($this->getConfigService()->isTimesheetPeriodDefined()) {
            
            if ($isAdmin) {
                return 'time/viewEmployeeTimesheet';
            } else {
                return 'time/viewMyTimesheet';
            }
            
        } else {
            
            return 'time/defineTimesheetPeriod';
            
        }
        
    }
    
    public function getLeaveModuleDefaultPath() {
        $isAdmin = ($this->userSession->getAttribute('auth.isAdmin') == 'Yes');
        $isSupervisor = ($this->userSession->getAttribute('auth.isSupervisor'));
        
        if ($this->getConfigService()->isLeavePeriodDefined()) {
            
            if ($isAdmin || $isSupervisor) {
                return 'leave/viewLeaveList/reset/1';
            } else {
                return 'leave/viewMyLeaveList';
            }
            
        } else {
            if ($isAdmin) {
                return 'leave/defineLeavePeriod';
            } else {
                return 'leave/showLeavePeriodNotDefinedWarning';
            }
            
        }

    }
    
    public function getAdminModuleDefaultPath() {
        
        $isAdmin = ($this->userSession->getAttribute('auth.isAdmin') == 'Yes');
        $isProjectAdmin = ($this->userSession->getAttribute('auth.isProjectAdmin'));    
        
        if ($isAdmin) {
            return 'admin/viewOrganizationGeneralInformation';
        } elseif ($isProjectAdmin) {
            return 'admin/viewProjects';
        }         
        
    }
    
    public function getPimModuleDefaultPath() {
        
        $isAdmin = ($this->userSession->getAttribute('auth.isAdmin') == 'Yes');
        $isSupervisor = ($this->userSession->getAttribute('auth.isSupervisor'));    
        
        if ($isAdmin || $isSupervisor) {
            return 'pim/viewEmployeeList';
        } else {
            return 'pim/viewMyDetails';
        }        
        
    }
    
    public function getRecruitmentModuleDefaultPath() {
        
        return 'recruitment/viewCandidates';
        
    }
    
    public function getPerformanceModuleDefaultPath() {
        
        return 'performance/viewReview';
        
    }
    
    public function getPathAfterLoggingIn($referer, $host) {
        
        $redirectToReferer = true;
        
        if (strpos($referer, $this->loginPath)) { // Check whether referer is login page
            
            $redirectToReferer = false;
            
        } else {
            
            if (!strpos($referer, $host)) { // Check whether from same host
                
                $redirectToReferer = false;
                
            }
            
        }
        
        if ($redirectToReferer) {
            return $referer;
        }
        
        return $this->getHomePagePath();
        
    }
    
}