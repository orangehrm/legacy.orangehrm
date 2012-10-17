<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewEmployeeTimeSheet
 *
 * @author Faris
 */
class viewEmployeeTimeSheet extends Page {

    public $txtEmployeeName;
    public $viewButton;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->txtEmployeeName = "employee";
        $this->viewButton = "//input[@value='View']";
    }

    public function viewTimeSheet($employeeName) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtEmployeeName, $employeeName);
        $this->selenium->click($this->viewButton);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

}