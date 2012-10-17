<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddCompanyStructureUnitPageObject
 *
 * @author intel
 */
class AddSystemUserPageObject extends Component {

    public $cmbUserType = "systemUser_userType";
    public $txtEmployeeName = "systemUser_employeeName";
    public $txtUsername = "systemUser_userName";
    public $txtPassword = "systemUser_password";
    public $txtConfirmPassword = "systemUser_confirmPassword";
    public $cmbStatus = "systemUser_status";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";

    public function __construct($selenium) {
        parent::__construct($selenium, "Add System User");
    }

    public function addSystemUser($userType, $employeeName, $username, $password, $confirmPassword, $status) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->select($this->cmbUserType, $userType);
        $this->selenium->type($this->txtEmployeeName, $employeeName);
        $this->selenium->type($this->txtUsername, $username);
        $this->selenium->type($this->txtPassword, $password);
        $this->selenium->type($this->txtConfirmPassword, $confirmPassword);
        $this->selenium->select($this->cmbStatus, $status);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editSystemUser($userType, $employeeName, $username, $password, $confirmPassword, $status) {
        $this->selenium->click($this->btnSave);
        $this->addSystemUser($userType, $employeeName, $username, $password, $confirmPassword, $status);
    }

}