<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LogIn
 *
 * @author Faris
 */
class LogInFlow extends flow {

    public $dataArray;
    public $selenium;
    public $offerJobPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {

        $this->dataArray = $dataArray;
    }

    public function execute($verify=false) {

        Helper::loginUser($this->selenium, $this->dataArray['userName'], $this->dataArray['password']);
        if ($verify == true) {
            return $this->verify();
        }
        return TRUE;
    }

    public function verify() {
        if (Helper::isLoggedIn($this->selenium)) {
            $loggedInUser = trim(Helper::getLoggedInUser($this->selenium)); // == $this->dataArray['userName'])
            echo "loggedInUser is : " . loggedInUser . " , but expected user is: " . $this->dataArray['userName'];
            if (strtoupper($loggedInUser) == strtoupper($this->dataArray['userName'])) {

                echo "returning true";
                return true;
            }
            else
                return false;
        }
    }

}