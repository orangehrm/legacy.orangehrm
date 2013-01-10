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
    protected $validatePath = 'auth/validateCredentials';

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
            return 'admin/viewSystemUsers';
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
    
    public function getPathAfterLoggingIn(sfContext $context) {
        
        $redirectToReferer = true;
                            
        $referer = $context->getRequest()->getReferer();
        $host = $context->getRequest()->getHost();           
        
        if (strpos($referer, $this->loginPath)) { // Check whether referer is login page            
            $redirectToReferer = false;
        } elseif (strpos($referer, $this->validatePath)) { // Check whether referer is validate action            
            $redirectToReferer = false;            
        } else {
            
            if (!strpos($referer, $host)) { // Check whether from same host                
                $redirectToReferer = false;                
            }            
        }
        
        /* 
         * Try to get action and module, skip redirecting to referrer and show homepage if:
         * 1) Action is not secure (probably a login related url we should not redirect to)
         * 2) Action is not accessible to current user.
         */        
        if ($redirectToReferer) {            
            try {
                $params = $context->getRouting()->parse($referer);
                if ($params && isset($params['module']) && isset($params['action'])) {

                    $moduleName = $params['module'];
                    $actionName = $params['action'];

                    if ($context->getController()->actionExists($moduleName, $actionName)) {
                        $action = $context->getController()->getAction($moduleName, $actionName);

                        if ($action instanceof sfAction) {
                            if ($action->isSecure()) {

                                $permissions = $context->getUserRoleManager()->getScreenPermissions($moduleName, $actionName);
                                if ($permissions instanceof ResourcePermission) {
                                    if ($permissions->canRead()) {
                                        return $referer;
                                    }
                                }
                            }
                        }
                    }                
                }
            } catch (Exception $e) {
                $logger = Logger::getLogger('core.homepageservice');
                $logger->warn('Error when trying to get referrer action: ' . $e);
            }
        }        
        
        return $this->getHomePagePath();
        
    }
    
}