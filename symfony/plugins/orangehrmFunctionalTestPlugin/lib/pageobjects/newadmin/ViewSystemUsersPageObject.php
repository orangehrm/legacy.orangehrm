<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewJobTitlesPageObject
 *
 * @author intel
 */


    public function viewSystemUsers($employeeName, $userType=null, $username=null, $status=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtEmployeeName, $employeeName);
        if ($userType) {
            $this->selenium->select($this->cmbUserType, $userType);
        }
        if ($username) {
            $this->selenium->type($this->txtUsername, $username);
        }
        if ($status) {
            $this->selenium->select($this->cmbStatus, $status);
        }
        $this->selenium->clickAndWait($this->btnSearch);
    }

    public function goToAddSystemUser() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddSystemUserPageObject($this->selenium);
    }

    public function deleteSystemUser($username) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Username", $username);
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function deleteAllSystemUsers() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}