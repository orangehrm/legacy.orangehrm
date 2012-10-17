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
class ViewJobCategoryPageObject extends Component {

    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $list;
    public $dialogDeleteBtn = "dialogDeleteBtn";
    public $dialogCancelBtn = "dialogCancelBtn";

    public function __construct($selenium) {
        parent::__construct($selenium, "View Job Category");
        $this->list = new JobCategoryList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
    }

    public function goToAddJobCategory() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddJobCategoryPageObject($this->selenium);
    }

    public function deleteJobCategory($jobCategory) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Job Category", $jobCategory);
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function deleteAllJobCategories() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}