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
class AddEmailSubscriber extends Component {

    public $txtSubscriberName = "subscriber_name";
    public $txtEmail = "subscriber_email";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $viewEmailSubscribers;

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Email Subscriber");
        $this->viewEmailSubscribers = new ViewEmailSubscribersPageObject($this->selenium);
    }

    public function addEmailSubscriber($subscriberName, $subscriberEmail) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtSubscriberName, $subscriberName);
        $this->selenium->type($this->txtEmail, $subscriberEmail);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editEmailSubscriber($subscriberName, $subscriberEmail) {
        $this->addEmailSubscriber($subscriberName, $subscriberEmail);
    }

}