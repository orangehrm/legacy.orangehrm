<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JobTitlesList
 *
 * @author intel
 */
class WorkShiftList extends BasicList {

    public function __construct($selenium, $xpathOfList, $selectAllPresent = FALSE) {
        parent::__construct($selenium, $xpathOfList, $selectAllPresent);
    }

}