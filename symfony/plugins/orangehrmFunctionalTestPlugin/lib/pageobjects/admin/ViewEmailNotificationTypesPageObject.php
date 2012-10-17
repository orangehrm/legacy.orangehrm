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
class ViewEmailNotificationTypesPageObject extends Component {

    public $btnEdit = "btnEdit";
    public $list;

    public function __construct($selenium) {
        parent::__construct($selenium, "View Email Notification Types");
        $this->list = new BasicList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
    }

    public function editEmailNotificationType() {
        $this->selenium->selctFrame("relative=top");
        $this->selenium->click($this->btnEdit);
    }

    public function saveEmailNotificationType() {
        $this->selenium->selctFrame("relative=top");
        $this->selenium->clickAndWait($this->btnEdit);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}