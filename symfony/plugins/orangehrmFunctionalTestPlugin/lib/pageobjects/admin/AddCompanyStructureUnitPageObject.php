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
class AddCompanyStructureUnitPageObject extends Component {

    public $txtUnitId = "txtUnit_Id";
    public $txtUnitName = "txtName";
    public $txtDescription = "txtDescription";
    public $btnSave = "ohrmFormActionButton_Save";
    public $btnCancel = "ohrmFormActionButton_Cancel";

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Company Structure Unit");
    }

    public function addCompanyUnit($unitId, $unitName, $description=null) {
        $this->selenium->selectFrame("relative=top");

        $this->selenium->type($this->txtUnitId, $unitId);
        $this->selenium->type($this->txtUnitName, $unitName);
        if ($description)
            $this->selenium->type($this->txtDescription, $description);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editCompanyUnit($unitId, $unitName, $description=null) {
        $this->addCompanyUnit($unitId, $unitName, $description);
    }

}