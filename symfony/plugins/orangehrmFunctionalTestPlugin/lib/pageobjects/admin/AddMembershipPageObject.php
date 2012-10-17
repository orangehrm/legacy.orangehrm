<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddJobTitlePageObject
 *
 * @author intel
 */
class AddMembershipPageObject extends Component {

    public $txtMembership = "membership_name";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $viewMembership;

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Membership");
        $this->viewMembership = new ViewMembershipPageObject($this->selenium);
    }

    public function addMembership($membership) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtMembership, $membership);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editMembership($membership) {
        $this->addMembership($membership);
    }

}