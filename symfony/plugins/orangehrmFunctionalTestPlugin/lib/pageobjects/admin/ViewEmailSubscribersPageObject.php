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
class ViewEmailSubscribersPageObject extends Component {

    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $btnBack = "btnBack";
    public $list;
    public $dialogDeleteBtn = "dialogDeleteBtn";
    public $dialogCancelBtn = "dialogCancelBtn";

    public function __construct($selenium) {
        parent::__construct($selenium, "View Email Subscribers");
        $this->list = new EmailSubscribersList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
    }

    public function goBack() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnBack);
        return new ViewEmailNotificationTypesPageObject($this->selenium);
    }

    public function goToAddEmailSubscriber() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddEmailSubscriber($this->selenium);
    }

    public function deleteEmailSubcscriber($subsriberName) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Name", $subsriberName);
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function deleteAllSubscribers() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}