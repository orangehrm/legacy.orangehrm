<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyLeaveList
 *
 * @author madusani
 */
class MyLeaveList extends LeaveList {

    public function __construct($selenium, $xpathOfList, $selectAllPresent = FALSE) {
        parent::__construct($selenium, $xpathOfList, $selectAllPresent);
    }

}