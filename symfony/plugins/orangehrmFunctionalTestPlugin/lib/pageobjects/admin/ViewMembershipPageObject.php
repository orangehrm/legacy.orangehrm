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
class ViewMembershipPageObject extends Component {

    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $list;
    public $dialogDeleteBtn = "dialogDeleteBtn";
    public $dialogCancelBtn = "dialogCancelBtn";

    public function __construct($selenium) {
        parent::__construct($selenium, "View Membership");
        $this->list = new MembershipsList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
    }

    public function goToAddMembership() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddMembershipPageObject($this->selenium);
    }

    public function deleteMembership($membership) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Membership", $membership);
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function deleteAllMemeberships() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}