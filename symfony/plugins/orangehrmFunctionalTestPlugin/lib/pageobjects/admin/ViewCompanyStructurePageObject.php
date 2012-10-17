<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewCompanyStructurePageObject
 *
 * @author intel
 */
class ViewCompanyStructurePageObject extends Component {

    public $btnEdit = "btnEdit";
    public $dialogBtnOk = "dialogYes";
    public $dialogBtnCancel = "dialogNo";

    public function __construct($selenium) {
        parent::__construct($selenium, "View Company Structure");
    }

    public function goToEditCompanyStructure($companyUnit) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnEdit);
        $this->selenium->clickAndWait("//div[@id='divCompanyStructureContainer']//*[contains(.,'" . $companyUnit . "')]");
    }

    public function goToAddCompanyStructure($companyUnit) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnEdit);
        $this->selenium->clickAndWait("//div[@id='divCompanyStructureContainer']//*[contains(.,'" . $companyUnit . "')]//a[@class='addLink']");
        return new AddCompanyStructureUnitPageObject($this->selenium);
    }

    public function deleteCompanyUnit($companyUnit) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnEdit);
        $this->selenium->click("//div[@id='divCompanyStructureContainer']//*[contains(.,'" . $companyUnit . "')]//a[@class='deleteLink']");
        $this->selenium->clickAndWait($this->dialogBtnOk);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}