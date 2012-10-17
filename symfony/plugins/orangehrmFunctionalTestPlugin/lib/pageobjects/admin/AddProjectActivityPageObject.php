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
class AddProjectActivityPageObject extends Component {

    public $txtProjectActivity = "addProjectActivity_activityName";
    public $btnSave = "btnActSave";
    public $btnCancel = "btnActCancel";
    public $list;
    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $btnCopyFrom = "btnCopy";
    public $txtCopyProject = "projectName";
    public $btnCopyProjectActivity = "btnCopyDig";
    public $btnCancelCopyProjectActivity = "btnCopyCancel";

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Project Activity");
        $this->list = new ProjectActivityList($selenium, "//form[@id='frmList_ohrmListComponent']", false);
    }

    public function addProjectActivity($projectActivity) {
        $this->selenium->selectFrame("relative=top");
        if ($this->selenium->isElementPresent($this->btnAdd))
            $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->txtProjectActivity, $projectActivity);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editProjectActivity($projectActivity) {
        $this->addProjectActivity($projectActivity);
    }

    public function copyFromAnotherProject($project) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnCopyFrom);
        $this->selenium->type($this->txtCopyProject, $project);
        $this->selenium->clickAndWait($this->btnCopyProjectActivity);
    }

    public function deleteProjectActivity($projectActivity) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Activity Name", $projectActivity);
        $this->selenium->clickAndWait($this->btnDelete);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}