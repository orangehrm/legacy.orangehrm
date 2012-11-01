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
 * Menu Service
 */
class MenuService {
    
    protected $menuDao;
    protected $numberOfLevels = 3;


    public function getMenuDao() {
        
        if (empty($this->menuDao)) {
            $this->menuDao = new MenuDao();
        }       
        
        return $this->menuDao;
        
    }

    public function setMenuDao($menuDao) {       
        $this->menuDao = $menuDao;
    }

    /**
     * Returns menu array for given user roles
     * 
     * Returned array is a multi-dimentional array
     * containing MenuItem objects
     * 
     * @param string $userRoleList Array of user role names or Array of UserRole objects
     * 
     * @return array Array of MenuItem objects
     */    
    public function getMenuItemCollection($userRoleList) {
        
        $menuArray = $this->_getMenuItemListAsArray($userRoleList);
        
        for ($i=$this->numberOfLevels; $i>0; $i--) {
            
            foreach ($menuArray as $menuItem) {
                
                $parentId = $menuItem->getParentId();
                
                if ($menuItem->getLevel() == $i && array_key_exists($parentId, $menuArray)) {
                    
                    if ($menuItem->getScreenId() != "" || !$this->_AreSubMenusEmpty($menuItem)) {                    
                        $menuArray[$parentId]->addSubMenuItem($menuItem);
                    }
                    
                    unset($menuArray[$menuItem->getId()]);
                    
                }
                
            }
            
        }
        
        foreach ($menuArray as $key => $value) {
            
            $subMenuItems = $value->getSubMenuItems();
            
            if ($value->getScreenId() == "" && empty($subMenuItems)) {
                unset($menuArray[$key]);
            }
            
        }

        return $menuArray;
        
    }
    
    public function getMenuItemDetails($userRoleList) {
        
        $firstLevelItems = $this->getMenuItemCollection($userRoleList);
        $menuArray = array();
        $actionArray = array();
        $parentIdArray = array();
        $levelArray = array();
        
        foreach ($firstLevelItems as $firstLevelItem) {   
            
            $secondLevelItems = $firstLevelItem->getSubMenuItems();
            $secondLevelHolder = array();
            
            if (!empty($secondLevelItems)) {
                
                foreach ($secondLevelItems as $secondLevelItem) {
                    
                    $thirdLevelItems = $secondLevelItem->getSubMenuItems();
                    $thirdLevelHolder = array();
                    
                    if (!empty($thirdLevelItems)) {
                        
                        foreach ($thirdLevelItems as $thirdLevelItem) {
                            
                            $tempHolder = $this->_extractMenuItemToArray($thirdLevelItem);
                            
                            $parentIdArray[$tempHolder['id']] = $thirdLevelItem->getParentId();
                            $levelArray[$tempHolder['id']] = $tempHolder['level'];
                            
                            if (!empty($tempHolder['module']) && !empty($tempHolder['action'])) {
                                $actionArray[$tempHolder['module'] . '_' . $tempHolder['action']] = $tempHolder['id'];
                            }                            
                            
                            $thirdLevelHolder[] = $tempHolder;
                            
                        }                      
                        
                    }
                    
                    $tempHolder = $this->_extractMenuItemToArray($secondLevelItem);
                    $tempHolder['subMenuItems'] = $thirdLevelHolder;
                    
                    $parentIdArray[$tempHolder['id']] = $secondLevelItem->getParentId();
                    $levelArray[$tempHolder['id']] = $tempHolder['level'];

                    if (!empty($tempHolder['module']) && !empty($tempHolder['action'])) {
                        $actionArray[$tempHolder['module'] . '_' . $tempHolder['action']] = $tempHolder['id'];
                    }                    
                    
                    $secondLevelHolder[] = $tempHolder;
                    
                }
                
            }
            
            $tempHolder = $this->_extractMenuItemToArray($firstLevelItem);
            $tempHolder['subMenuItems'] = $secondLevelHolder;
            
            $parentIdArray[$tempHolder['id']] = $firstLevelItem->getParentId();
            $levelArray[$tempHolder['id']] = $tempHolder['level'];

            if (!empty($tempHolder['module']) && !empty($tempHolder['action'])) {
                $actionArray[$tempHolder['module'] . '_' . $tempHolder['action']] = $tempHolder['id'];
            }            
            
            $menuArray[] = $tempHolder;
            
        }
        
        return array('menuItemArray' => $menuArray, 
                     'actionArray' => $actionArray, 
                     'parentIdArray' => $parentIdArray, 
                     'levelArray' => $levelArray);
        
    }
    
    public function getCurrentItemDetails($details) {
        
        $module         = $details['module'];
        $action         = $details['action'];
        $actionArray    = $details['actionArray'];
        $parentIdArray  = $details['parentIdArray'];
        $levelArray     = $details['levelArray'];
        
        $currentItemId = $actionArray[$module . '_' . $action];
        $level = $levelArray[$currentItemId];
        $currentItemDetails = array('level1' => '', 'level2' => '', 'level3' => '');
        
        if ($level == 1) {
            
            $currentItemDetails['level1'] = $currentItemId;
            return $currentItemDetails;
            
        } elseif ($level == 2) {
            
            $currentItemDetails['level2'] = $currentItemId;
            $currentItemDetails['level1'] = $parentIdArray[$currentItemId];
            return $currentItemDetails;
            
        } elseif ($level == 3) {
            
            $currentItemDetails['level3'] = $currentItemId;
            $currentItemDetails['level2'] = $parentIdArray[$currentItemId];
            $currentItemDetails['level1'] = $parentIdArray[$currentItemDetails['level2']];
            return $currentItemDetails;
            
        }
        
    }
    
    public function enableModuleMenuItems($moduleName) {
        return $this->getMenuDao()->enableModuleMenuItems($moduleName);
    }

    private function _extractMenuItemToArray(MenuItem $menuItem) {
        
        $menu['id'] = $menuItem->getId();
        $menu['menuTitle'] = $menuItem->getMenuTitle();
        $menu['level'] = $menuItem->getLevel();
        $menu['module'] = '';
        $menu['action'] = '';
        $menu['subMenuItems'] = array();
        
        $path = '';
        $screen = $menuItem->getScreen();
        
        if ($screen instanceof Screen) {
            
            $menu['module'] = $screen->getModule()->getName();
            $menu['action'] = $screen->getActionUrl();            
            
            $module = $screen->getModule()->getName();
            $action = $screen->getActionUrl();
            $urlExtras = $menuItem->getUrlExtras();
            $path = $module . '/' . $action . $urlExtras;
            
        }
        
        $menu['path'] = $path;        
        
        return $menu;
        
    }

    protected function _getMenuItemListAsArray($userRoleList) {
        
        $menuItemList = $this->getMenuDao()->getMenuItemList($userRoleList);
        $menuArray = array();
        
        foreach ($menuItemList as $menuItem) {
            $menuArray[$menuItem->getId()] = $menuItem;
        }
        
        return $menuArray;
        
    }
    
    protected function _AreSubMenusEmpty(MenuItem $menuItem) {
        
        $subMenus = $menuItem->getSubMenuItems();
        
        foreach ($subMenus as $subMenu) {
            
            if ($subMenu->getScreenId() != "") {
                return false;
            }
            
        }
        
        return true;
        
    }
    

    
    
}