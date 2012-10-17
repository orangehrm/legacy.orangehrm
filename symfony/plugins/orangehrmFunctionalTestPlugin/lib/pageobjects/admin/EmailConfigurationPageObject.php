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
class EmailConfigurationPageObject extends Component {

    public $txtMailSentAs = "txtMailAddress";
    public $txtPathToSendMail = "txtSendmailPath";
    public $chkBoxSendTestMail = "chkSendTestEmail";
    public $cmbSendingMethod = "cmbMailSendingMethod";
    public $txtTestEmailAddress = "txtTestEmail";
    public $btnSave = "editBtn";
    public $btnReset = "resetBtn";

    public function __construct($selenium) {
        parent::__construct($selenium, "Configure Email Notifications");
    }

    public function saveEmailConfiguration($mailSentAs, $pathToSendmail=null, $checkSendTestMail=null, $sendingMethod=null, $testEmailAddress=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtMailSentAs, $mailSentAs);
        if ($pathToSendmail)
            $this->selenium->type($this->txtPathToSendMail, $pathToSendmail);
        if ($checkSendTestMail == "yes")
            $this->selenium->click($this->chkBoxSendTestMail, $checkSendTestMail);
        if ($sendingMethod)
            $this->selenium->select($this->cmbSendingMethod, $sendingMethod);
        if ($testEmailAddress)
            $this->selenium->type($this->txtTestEmailAddress, $testEmailAddress);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editEmailConfiguration($mailSentAs, $pathToSendmail=null, $checkSendTestMail=null, $sendingMethod=null, $testEmailAddress=null) {
        $this->selenium->click($this->btnSave);
        $this->saveEmailConfiguration($mailSentAs, $pathToSendmail, $checkSendTestMail, $sendingMethod, $testEmailAddress);
    }

}