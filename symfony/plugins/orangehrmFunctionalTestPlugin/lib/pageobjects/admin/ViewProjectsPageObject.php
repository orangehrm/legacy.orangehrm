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
class ViewProjectsPageObject extends Component {

    public $btnSearch = "btnSearch";
    public $btnReset = "btnReset";
    public $txtCustomer = "searchProject_customer";
    public $txtProject = "searchProject_project";
    public $txtProjectAdmin = "searchProject_projectAdmin";
    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $dialogDeleteBtn = "dialogDeleteBtn";
    public $dialogCancelBtn = "dialogCancelBtn";
    public $list;

    public function __construct($selenium) {
        parent::__construct($selenium, "View Projects");
        $this->list = new ProjectsList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
    }

    public function viewProjectRecords($customer, $project=null, $projectAdmin=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtCustomer, $customer);
        if ($project)
            $this->selenium->type($this->txtProject, $project);
        if ($projectAdmin)
            $this->selenium->type($this->txtProjectAdmin, $projectAdmin);
        $this->selenium->clickAndWait($this->btnSearch);
    }

    public function goToAddProject() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddProjectPageObject($this->selenium);
    }

    public function deleteProject($project) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Project", $project);
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function deleteAllProjects() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}