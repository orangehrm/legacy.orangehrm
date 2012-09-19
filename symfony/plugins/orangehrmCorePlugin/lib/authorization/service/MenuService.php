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
    
    public function getMenuItemsAsArray($userRoleList) {
        
        $firstLevelItems = $this->getMenuItemCollection($userRoleList);
        $menuArray = array();        
        
        foreach ($firstLevelItems as $firstLevelItem) {   
            

            
            $secondLevelItems = $firstLevelItem->getSubMenuItems();
            $b = array();
            
            if (!empty($secondLevelItems)) {
                
                foreach ($secondLevelItems as $secondLevelItem) {
                    
                    $thirdLevelItems = $secondLevelItem->getSubMenuItems();
                    $c = array();
                    
                    if (!empty($thirdLevelItems)) {
                        
                        foreach ($thirdLevelItems as $thirdLevelItem) {
                            
                            $c[] = $this->_abc($thirdLevelItem);
                            
                        }                      
                        
                    }
                    
                    $bb = $this->_abc($secondLevelItem);
                    $bb['subMenuItems'] = $c;
                    
                    $b[] = $bb;
                    
                }
                
            }
            
            $a = $this->_abc($firstLevelItem);
            $a['subMenuItems'] = $b;
            
            $menuArray[] = $a;
            
        }
        
        return $menuArray;
        
    }
    
    private function _abc(MenuItem $menuItem) {
        
        $menu['id'] = $menuItem->getId();
        $menu['menuTitle'] = $menuItem->getMenuTitle();
        $menu['subMenuItems'] = array();
        
        $path = '';
        $screen = $menuItem->getScreen();
        
        if ($screen instanceof Screen) {
            $module = $screen->getModule()->getName();
            $action = $screen->getActionUrl();
            $urlExtras = $menuItem->getUrlExtras();
            $path = $module . '/' . $action . $urlExtras;
        }
        
        $menu['path'] = $path;        
        
        return $menu;
        
    }






    public function printMenu($userRoleList) {
        
        $menuArray = $this->getMenuItemArray($userRoleList);
        
        foreach ($menuArray as $menuItem) {
            
        }
        
        
        
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