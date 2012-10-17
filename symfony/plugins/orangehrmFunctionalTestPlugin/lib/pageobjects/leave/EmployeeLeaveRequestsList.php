<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmployeeLeaveRequestsList
 *
 * @author madusani
 */
class EmployeeLeaveRequestsList extends BasicList {

    public $btnSave = "//input[@id='btnSave']";
    public $btnBack = "btnBack";
    public $commentEditBtn = "commentSave";
    public $commentCancelBtn = "commentCancel";
    public $commentTextArea = "leaveComment";

    public function __construct($selenium) {
        parent::__construct($selenium, "//form[@id='frmList_ohrmListComponent']", $selectAllPresent);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

    public function goToLeaveList() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->clickAndWait($this->btnBack);
        return new ViewLeaveListPageObject($this->selenium);
    }

    public function performActionOnLeaveRequest($date, $action, $comment=null) {
        $this->selenium->selectFrame("relative=top");
        if ($comment && $this->commentEditBtn) {
            $this->selenium->click($this->xpathOfList . "//tr/td[text()='" . $date . "']//..//img");
            $this->selenium->click($this->commentEditBtn);
            $this->selenium->type($this->commentTextArea, $comment);
            $this->selenium->clickAndWait($this->commentEditBtn);
            sleep(5);
        }
        $this->selenium->select($this->xpathOfList . "//tr/td[text()='" . $date . "']//..//select", $action);
        
        $this->selenium->clickAndWait($this->btnSave);
        return $this;
    }

    public function getItemOfSpecifiedRecord($column, $date) {

        
        $this->selenium->selectFrame("relative=top");


        if ($column == "Duration") {

            if ($this->selenium->isElementPresent($this->xpathOfList . "//tr/td[contains(.,'" . $date . "')]/..//td[4]")) {

               
                return $this->selenium->getText($this->xpathOfList . "//tr/td[contains(.,'" . $date . "')]/..//td[4]");
            }
        }

        if ($column == "Status") {

            if ($this->selenium->isElementPresent($this->xpathOfList . "//tr/td[contains(.,'" . $date . "')]/..//td[5]")) {

                return $this->selenium->getText($this->xpathOfList . "//tr/td[contains(.,'" . $date . "')]/..//td[5]");
            }
        }
//        if($column == "Action")
//        {
//            if($this->selenium->isElementPresent($this->xpathOfList . "//tr/td[contains(.,'" . $date . "')]/..//td[6]")){
//            //$array = explode(" ",$this->selenium->getText($this->xpathOfList . "//tr/td[contains(.,'" . $date . "')]/..//td[6]"));
//              
//            return substr($this->selenium->getText($this->xpathOfList . "//tr/td[contains(.,'" . $date . "')]/..//td[6]"),13);
//           // return $array[2];
//            }
//         }
    }

}