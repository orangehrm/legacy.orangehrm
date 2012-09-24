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

class mainMenuComponent extends sfComponent {
    
    protected $menuService;
    
    public function getMenuService() {
        
        if (!$this->menuService instanceof MenuService) {
            $this->menuService = new MenuService();
        }
        
        return $this->menuService;
        
    }
    
    public function setMenuService(MenuService $menuService) {
        $this->menuService = $menuService;
    }    

    public function execute($request) {
        
        $menuItemDetails = $this->_getMenuItemDetails();
        
        $this->menuItemArray = $menuItemDetails['menuItemArray'];
        $this->module = $this->getContext()->getModuleName();
        
        $initialAction = $request->getParameter('initialActionName', '');
        
        if (!empty($initialAction)) {
            $this->action = $initialAction;
        } else {
            $this->action = $this->getContext()->getActionName();
        }
        
        $details['module']          = $this->module;
        $details['action']          = $this->action;
        $details['actionArray']     = $menuItemDetails['actionArray'];
        $details['parentIdArray']   = $menuItemDetails['parentIdArray'];
        $details['levelArray']      = $menuItemDetails['levelArray'];       
        
        $this->currentItemDetails = $this->getMenuService()->getCurrentItemDetails($details);

    }

    protected function _getMenuItemDetails() {
        
        if ($this->getUser()->hasAttribute('mainMenu.menuItemArray')) {
            return $this->getUser()->getAttribute('mainMenu.menuItemArray');
        }
        
        $menuItemArray = $this->getContext()->getUserRoleManager()->getAccessibleMenuItemDetails();  
        $this->getUser()->setAttribute('mainMenu.menuItemArray', $menuItemArray);
        
        return $menuItemArray;
        
    }
    
    

}
