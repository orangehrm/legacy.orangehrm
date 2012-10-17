<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewTimeSheetFlow
 *
 * @author nusky
 */
class viewTimeSheetFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $viewTimeSheetPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $viewTimeSheet = Menu::goToEmployeeTimeSheet($this->selenium);
        $viewTimeSheet->viewTimeSheet($this->dataArray['person']);

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        return TRUE;
    }

}