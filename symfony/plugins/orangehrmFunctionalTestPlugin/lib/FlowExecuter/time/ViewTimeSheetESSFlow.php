<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewTimeSheetESSFlow
 *
 * @author Faris
 */
class ViewTimeSheetESSFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $editTimeSheetPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $viewTimeSheet = Menu::goToEmployeeTimeSheet($this->selenium);


        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        return TRUE;
    }

}