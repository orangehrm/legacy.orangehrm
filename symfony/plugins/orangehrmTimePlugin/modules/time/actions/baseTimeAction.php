<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of baseTimeAction
 *
 * @author nirmal
 */
abstract class baseTimeAction extends sfAction {

    public function getDataGroupPermissions($dataGroups) {
        return $this->getContext()->getUserRoleManager()->getDataGroupPermissions($dataGroups, array(), array(), false, array());
    }

}

?>
