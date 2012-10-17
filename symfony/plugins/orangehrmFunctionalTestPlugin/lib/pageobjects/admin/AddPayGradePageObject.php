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
class AddPayGradePageObject extends Component {

    public $txtPayGradeName = "payGrade_name";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $currencyPageObject;

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Pay Grade");
        $this->currencyPageObject = new AddCurrencyPageObject($this->selenium);
    }

    public function addPayGrade($payGradeName) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtPayGradeName, $payGradeName);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editPayGrade($payGradeName) {
        $this->selenium->click($this->btnSave);
        $this->addPayGrade($payGradeName);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}